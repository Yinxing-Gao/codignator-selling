<?php


namespace App\Models;

use Config;

class Wallets
{
	public static function get_wallets($params = [], $join = [], $has_many = [], $month_year = null)
	{
		$query_params = self::select_query(array_merge(['fin_users', 'fin_banks'], $join), $has_many, $month_year);

		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public
	static function get_wallet($params = [], $join = [])
	{
		$query_params = self::select_query(array_merge(['fin_users', 'fin_banks'], $join));
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public
	static function get_wallet_checkout($params = [], $join = [])
	{
		$query_params = self::select_checkout_query(array_merge(['fin_users', 'fin_banks'], $join));
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public
	static function get_wallet_checkouts($params = [], $join = [])
	{
		$query_params = self::select_checkout_query(array_merge(['fin_wallets'], $join));
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public
	static function get_checkouts_array($params = [], $join = [])
	{
		$checkouts = self::get_wallet_checkouts($params, $join);
		$result_checkouts = [];
		if (!empty($checkouts)) {
			foreach ($checkouts as $checkout) {
				$result_checkouts[$checkout['date']]['checkout'][$checkout['wallet_id']] = [
					'wallet_id' => $checkout['wallet_id'],
					'amount' => $checkout['amount'],
					'date' => $checkout['date']
				];
			}
		}

		return $result_checkouts;
	}

	public
	static function update_later_checkouts($wallet_id, $date, $planned = false) // дописати можливість оновлювати з певної дати
	{
//		DBHelp::delete_where('fin_wallets_checkout', [
//			'wallet_id = ' . $wallet_id,
//			'(type = "week") OR (type = "month")',
//			'date > ' . $date
//		]);

		$fin_day = Settings::get('finance day');
		//щотижнева фіксація
		$date_from = strtotime('next ' . $fin_day . ' 00:00:00', time());
		$date_to = time();
		$period_time = DateHelper::period('week');
		$periods_end_dates = [];

		for ($date = $date_from; $date < $date_to; $date += $period_time) {
			$periods_end_dates[] = $date;
		}

		for ($i = 0; $i < (count($periods_end_dates) - 1); $i++) {
			self::calculate_checkout($wallet_id, $periods_end_dates[$i + 1], 'week');
		}

		$periods_end_dates2 = [];
		for ($i = 1; $i <= (int)date('m', time()); $i++) {
			$periods_end_dates2 [] = strtotime(date('Y', time()) . "-" . $i . "-1 00:00:00");
		}

		for ($i = 0; $i < (count($periods_end_dates2) - 1); $i++) {
			self::calculate_checkout($wallet_id, $periods_end_dates2[$i + 1], 'month');
		}
		return ['status' => 'ok'];
	}

	public
	static function add_update_checkout($atts)
	{
		if (!empty($atts['wallet_id']) &&
			(!empty($atts['amount']) || !empty($atts['planned_amount']))) {
			$exist = self::get_wallet_checkout(['where' => [
				'human_date = "' . date('d.m.Y', !empty($atts['date']) ? $atts['date'] : time()) . '"',
				'wallet_id = ' . $atts['wallet_id'],
				'type != "start"'
			]]);

			$params = [
				'wallet_id' => $atts['wallet_id'],
				'date' => !empty($atts['date']) ? $atts['date'] : time(),
				'human_date' => !empty($atts['date']) ? date('d.m.Y', $atts['date']) : date('d.m.Y'),
				'amount' => !empty($atts['amount']) ? $atts['amount'] : 0,
				'planned_amount' => !empty($atts['planned_amount']) ? $atts['planned_amount'] : 0,
				'type' => !empty($atts['type']) ? $atts['type'] : 'week',
			];

			if ($params['type'] == 'start') {
				$params['planned_amount'] = $params['amount'];
			}


			if (!empty($exist)) {
				DBHelp::update('fin_wallets_checkout', $exist->id, $params);
			} else {
				DBHelp::insert('fin_wallets_checkout', $params);
			}
		}
	}

	public
	static function calculate_checkout($wallet_id, $date, $type, $planned = false)
	{
		$checkout_obj = Wallets::get_wallet_checkout(['where' => [
			'wallet_id = ' . $wallet_id,
			'date < "' . $date . '"',
		]]);

		$checkout = !empty($checkout_obj) ? (float)$checkout_obj->amount : 0;

		$last_checkout_date = !empty($checkout_obj) ? (float)$checkout_obj->date : 0;

		$params = [
			'where' => [
				'(fo.`wallet1_id` = ' . $wallet_id . ' OR  fo.`wallet2_id` = ' . $wallet_id . ')'
			],
			'order_by' => 'date ASC'
		];
		if ($planned) {
			$params['where'][] = 'fo.`planned_on` > ' . $last_checkout_date;
			$params['order_by'] = 'planned ON ASC';
		} else {
			$params['where'][] = 'fo.`date` > ' . $last_checkout_date;
			$params['order_by'] = 'date ASC';
		}

		$operations = Operation::get_operations($params);

		if (!empty($operations)) {
			foreach ($operations as $operation) {
				if ($operation['date'] > $last_checkout_date &&
					$operation['date'] < $date) {

					if ($operation['wallet1_id'] == $wallet_id &&
						$operation['operation_type_id'] == 3) {
						$operation['operation_type_id'] = 6;  //передано іншій касі
					}

					if ($operation['wallet2_id'] == $wallet_id &&
						$operation['operation_type_id'] == 3) {
						$operation['operation_type_id'] = 5;    //отримано з іншої каси
					}

					if ($operation['operation_type_id'] == 1 ||
						$operation['operation_type_id'] == 4 ||
						$operation['operation_type_id'] == 5) {
						$checkout += $operation['amount2'];
					}

					if ($operation['operation_type_id'] == 2 ||
						$operation['operation_type_id'] == 6) {
						$checkout -= $operation['amount1'];
					}
				}
			}

			$params = [
				'wallet_id' => $wallet_id,
				'date' => $date,

				'type' => $type
			];

			if ($planned) {
				$params['planned_amount'] = $checkout;
			} else {
				$params['amount'] = $checkout;
			}

			self::add_update_checkout($params);
		}
	}

	public
	static function update($id, $params)
	{
		DBHelp::update('fin_wallets', $id, $params);
	}


	public
	static function delete($wallet_id)
	{
		DBHelp::delete('fin_wallets', $wallet_id);
		//обдумати як зробити з операціями, які залишилися
		return ['status' => 'ok'];
	}

//	public
//	static function get_user_wallet($user_id, $currency)
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT fw . `id`, fw . `name`, `currency`, `user_id`, `type_id`, fu . name as user_name, fu . surname as user_surname FROM fin_wallets fw LEFT JOIN fin_users fu ON fu . id = fw . user_id WHERE user_id = ' . $user_id . ' AND currency = "' . $currency . '"');
//		return $query->getFirstRow();
//	}

	public
	static function add($atts)
	{
		$params = [
			"name" => $atts['name'],
			"currency" => $atts['currency'],
			"user_id" => $atts['user_id'],
			"type_id" => $atts['type_id'],
			"wallet_type" => $atts['wallet_type'],
			"bank_id" => !empty($atts['bank_id']) && $atts['wallet_type'] == 'card' ? $atts['bank_id'] : 0,
			"merchant_id" => !empty($atts['merchant_id']) ? $atts['merchant_id'] : '',
			"merchant_code" => !empty($atts['merchant_code']) ? $atts['merchant_code'] : '',
			"checkout" => $atts['checkout'],
			"planned_checkout" => $atts['checkout'],
			'is_shown' => !empty($atts['is_shown']) ? $atts['is_shown'] : 1,
			'is_default' => !empty($atts['is_default']) ? $atts['is_default'] : 0,
			'create_date' => time()
		];

		$result = DBHelp::insert('fin_wallets', $params);
		if ($result['status'] == 'ok') {
			$params = [
				'wallet_id' => $result['id'],
				'date' => time(),
				'human_date' => date('d.m.Y'),
				'amount' => $atts['checkout'],
				'planned_amount' => $atts['checkout'],
				'type' => 'start'
			];
			self::add_update_checkout($params);
		}
		return $result;
	}

	public
	static function get_privat24_balance()
	{
		$balance = 0;
		$response = PrivatBank::method('rest', ['type' => 'today']);
		if (!empty($response["balanceResponse"][0])) {
			var_dump($response["balanceResponse"][0]);
			foreach ($response["balanceResponse"][0] as $key => $value) {
				$balance = $value['balanceOutEq'];
			}
		}
		return $balance;
	}

	public
	static function count_plan_checkouts($user_id)
	{
		$wallets = self::get_wallets(['where' => [
			'user_id = ' . $user_id]]);
		$checkouts = [];
		if (!empty($wallets)) {
			foreach ($wallets as $wallet) {
				$checkouts = array_merge($checkouts, self::count_plan_checkout($wallet['id'], $wallet['planned_checkout']));
			}
		}
		return $checkouts;
	}

	public
	static function count_plan_checkout($wallet_id, $checkout)
	{
		$checkouts = [];
		$result = [];
		$dates = DateHelper::get_future_timestamps_of_period('week', 1610985611);
		$plan_operations = Operation::get_operations([
			'where' => [
//				'is_planned = 1',
				'fo.time_type = "plan"',
				'is_shown = 1',
//				'fo . `planned_on` > ' . time(),
				'fo . `planned_on` < ' . DateHelper::get_same_day_next_month(),
				'(fo . `wallet1_id` = ' . $wallet_id . ' OR fo . `wallet2_id` = ' . $wallet_id . ')'
			], 'order_by' => 'planned_on ASC']);

		if (!empty($dates)) {
			foreach ($dates as $date) {
				$checkouts[$date] = $checkout;
				if (!empty($plan_operations)) {
					foreach ($plan_operations as $operation) {
						if (//$operation['planned_on'] > time() &&
							$operation['planned_on'] < $date) {
							if ($operation['wallet1_id'] == $wallet_id && $operation['operation_type_id'] == 3) {
								$operation['operation_type_id'] = 6;  //передано іншій касі
							}
							if ($operation['wallet2_id'] == $wallet_id && $operation['operation_type_id'] == 3) {
								$operation['operation_type_id'] = 5;    //отримано з іншої каси
							}

							if ($operation['operation_type_id'] == 1 || $operation['operation_type_id'] == 4 || $operation['operation_type_id'] == 5) {
								$checkouts[$date] += $operation['amount2'];
							}

							if ($operation['operation_type_id'] == 2 || $operation['operation_type_id'] == 6) {
								$checkouts[$date] -= $operation['amount1'];
							}
						}
					}
				}
				$result[] = [
					"wallet_id" => $wallet_id,
					"amount" => $checkouts[$date],
					"date" => $date
				];
			}
		}
		return $result;
	}

	public
	static function get_card_balances($user_id)
	{
		$wallets = self::get_wallets(['where' => ['user_id = ' . $user_id]]);
		$balances = [];
		if (!empty($wallets)) {
			foreach ($wallets as $wallet) {
				if ($wallet['wallet_type'] == 'card') {
					if ($wallet['type_id'] == 1) { // карти фіз.осіб
						if ($wallet['bank_id'] == 1) { // ПриватБанк
							$result = PrivatBank::get_card_balance($wallet['id']);
						} elseif ($wallet['bank_id'] == 2) { // МоноБанк
							$result = MonoBankM::get_card_balance($wallet['id']);
						}
						if ($result['status'] == 'ok') {
							$balances[$wallet['id']]['balance'] = $result['status'] == 'ok' ? $result['balance'] : null;
							$balances[$wallet['id']]['fin_limit'] = $result['status'] == 'ok' ? $result['fin_limit'] : null;
						}
					} else { //карти безнальних рахунків

					}
				}
			}
		}
		return $balances;
	}

	public
	static function select_query($join_keys = [], $has_many_keys = [], $month_year = null)
	{
		$params = [
			'table' => ['fw' => 'fin_wallets'],
			'columns' => [
				'id',
				'name',
				'currency',
				'user_id',
				'type_id',
				'checkout',
				'planned_checkout',
				'wallet_type',
				'bank_id',
				'merchant_id',
				'merchant_code',
				'is_shown',
				'is_virtual',
				'is_default',
				'department_id',
				'for_income',
				'for_expense',
				'create_date'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_users', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fu' => 'fin_users'],
				'main_table_key' => 'user_id',
				'columns' => ['account_id'],
				'columns_with_alias' => [
					'name' => 'user_name',
					'surname' => 'user_surname'
				]
			];
		}

		if (in_array('fin_banks', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fb' => 'fin_banks'],
				'main_table_key' => 'bank_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'bank_name',
				]
			];
		}

		if (in_array('fin_departments', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fd' => 'fin_departments'],
				'main_table_key' => 'department_id',
				'columns' => [],
				'columns_with_alias' => ['name' => 'department_name']
			];
		}

		if (in_array('fin_accounts', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fa' => 'fin_accounts'],
				'other_table_key' => 'fu . account_id',
				'columns' => [],
				'columns_with_alias' => [
					'status' => 'account_status',
				]
			];
		}

		if (in_array('fin_operations', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fo' => 'fin_operations'],
				'new_column' => 'expense_operations',
				'main_table_key' => 'id',
				'other_table_key' => 'wallet1_id',
				'columns' => ['article_id'],
				'columns_with_alias' => [
					'amount1' => 'amount',
					'currency1' => 'currency'
				],
				'where' => [
					'fo.time_type = "real"',
					!empty($month_year) ? 'fo.date >= ' . DateHelper::get_first_and_last_days_of_month($month_year)['first_day_timestamp'] : '1',
					!empty($month_year) ? 'fo.date <= ' . DateHelper::get_first_and_last_days_of_month($month_year)['last_day_timestamp'] : '1',
				]
			];
			$params['has_many'][] = [
				'table' => ['fo' => 'fin_operations'],
				'new_column' => 'income_operations',
				'main_table_key' => 'id',
				'other_table_key' => 'wallet2_id',
				'columns' => ['article_id'],
				'columns_with_alias' => [
					'amount2' => 'amount',
					'currency2' => 'currency'
				],
				'where' => [
					'fo.time_type = "real"',
					!empty($month_year) ? 'fo.date >= ' . DateHelper::get_first_and_last_days_of_month($month_year)['first_day_timestamp'] : '1',
					!empty($month_year) ? 'fo.date <= ' . DateHelper::get_first_and_last_days_of_month($month_year)['last_day_timestamp'] : '1',
				]
			];
			$params['has_many'][] = [
				'table' => ['fo' => 'fin_operations'],
				'new_column' => 'plan_expense_operations',
				'main_table_key' => 'id',
				'other_table_key' => 'wallet1_id',
				'columns' => ['article_id'],
				'columns_with_alias' => [
					'amount1' => 'amount',
					'currency1' => 'currency'
				],
				'where' => [
					'fo.time_type = "plan"',
					!empty($month_year) ? 'fo.date >= ' . DateHelper::get_first_and_last_days_of_month($month_year)['first_day_timestamp'] : '1',
					!empty($month_year) ? 'fo.date <= ' . DateHelper::get_first_and_last_days_of_month($month_year)['last_day_timestamp'] : '1',
				]
			];
			$params['has_many'][] = [
				'table' => ['fo' => 'fin_operations'],
				'new_column' => 'plan_income_operations',
				'main_table_key' => 'id',
				'other_table_key' => 'wallet2_id',
				'columns' => ['article_id'],
				'columns_with_alias' => [
					'amount2' => 'amount',
					'currency2' => 'currency'
				],
				'where' => [
					'fo.time_type = "plan"',
					!empty($month_year) ? 'fo.date >= ' . DateHelper::get_first_and_last_days_of_month($month_year)['first_day_timestamp'] : '1',
					!empty($month_year) ? 'fo.date <= ' . DateHelper::get_first_and_last_days_of_month($month_year)['last_day_timestamp'] : '1',
				]
			];
		}
		return $params;
	}

	public
	static function select_checkout_query($join_keys = [])
	{
		$params = [
			'table' => ['fwch' => 'fin_wallets_checkout'],
			'columns' => [
				'id',
				'wallet_id',
				'date',
				'human_date',
				'amount',
				'planned_amount',
				'type'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
			],
			'order_by' => 'date DESC',
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_wallets', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fw' => 'fin_wallets'],
				'main_table_key' => 'wallet_id',
				'columns' => [
					'user_id'
				],
				'columns_with_alias' => [

				]
			];
		}

		return $params;
	}
}
