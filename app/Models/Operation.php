<?php


namespace App\Models;

use App\Controllers\Wallet;
use Config;
use Exception;

class Operation
{
	public static function get_operations($params = ['time_type = "real"'])
	{
		$query_params = self::select_query(['fin_projects', 'fin_applications', 'fin_users', 'fin_contractors']);

		if (!empty($params['month_year'])) {
			$month_year = $params['month_year'];
			$some_date_of_month = !empty($month_year) ? strtotime('1.' . $month_year) : time();
			$first_day_of_this_month = strtotime('first day of this month 00:00:00', $some_date_of_month);
			$last_day_of_this_month = strtotime('last day of this month 23:59:59', $some_date_of_month);
			$query_params['where'][] = 'fo.date >= ' . $first_day_of_this_month;
			$query_params['where'][] = 'fo.date <=' . $last_day_of_this_month;
			unset ($params['month_year']);
		}
		$query_params['order_by'] = 'fo.date DESC';
		$query_params = array_merge($query_params, $params);

		return DBHelp::select($query_params)['result'];
	}

	public static function get_operation($params, $join = [])
	{
		$query_params = self::select_query($join);
		$query_params = array_merge($query_params, $params);
		return DBHelp::select($query_params, 'single');
	}

	public static function get_user_operations($user_id, $params = [])
	{
		$operations = self::get_operations($params);
		$operation_list = [];
		$user_wallets = Wallets::get_wallets(['where' => ['user_id = ' . $user_id]]);
		$user_wallets_array = [];
		if (!empty($user_wallets)) {
			foreach ($user_wallets as $user_wallet) {
				$user_wallets_array[] = $user_wallet['id'];
			}
		}
		$contractor_id = Contractors::get_user_contractor($user_id);
		$contractor_id = !empty($contractor_id) ? $contractor_id->id : 0;
		foreach ($operations as &$operation) {
//			if (empty($date) || (!empty($date) && $operation['date'] > $date)) {
			if ($operation['contractor2_id'] == $contractor_id && $operation['contractor1_id'] == $contractor_id && $operation['operation_type_id'] == 3) {
				$operation['operation_type_id'] = 7;  //переміщення між власними рахунками
			} elseif ($operation['contractor1_id'] == $contractor_id && $operation['operation_type_id'] == 3) {// || $operation['user_2'] == $user_id) {
				$operation['operation_type_id'] = 5; //передано іншому співробітнику
			} elseif ($operation['contractor2_id'] == $contractor_id && $operation['operation_type_id'] == 3) {
				$operation['operation_type_id'] = 6;  //отримано від іншого співробітника
			}

			if (in_array($operation['wallet1_id'], $user_wallets_array) || in_array($operation['wallet2_id'], $user_wallets_array)) {
				$operation_list[] = $operation;
			}
		}
//		}
		return $operation_list;
	}

	public static function get_plan_operations_by_projects()
	{
		$session = Config\Services::session();
		$account_id = $session->get('account_id');
		$operations = self::get_operations(['where' => [
			'time_type = "plan"',
			'fo.account_id = ' . $account_id,
			'fo.project_id != 0'
		]]);
		$operations_by_projects = [];
		if (!empty($operations)) {
			foreach ($operations as $operation) {
				$operations_by_projects[$operation['project_id']][$operation['date']] = $operation;
			}
			foreach ($operations_by_projects as $project_id => $date_operation) {
				krsort($operations_by_projects[$project_id]);
			}
		}
		return $operations_by_projects;
	}

	public static function get_application_operations($operation_id)
	{
		$query_params = self::select_query();
		$query_params['where'] = ['fo.`app_id` = ' . $operation_id];

		$query_params['order_by'] = 'id DESC';
		return DBHelp::select($query_params);
	}

	public static function get_templates($params = [], $join_keys = [])
	{
		$query_params = self::select_query();
		$query_params['where'] = [
			'fo.`time_type` = "template"',
			'fo.account_id = ' . Account::get_current_account_id()
		];
		$query_params['join'][] = [
			'type' => 'LEFT JOIN',
			'table' => ['fc1' => 'fin_contractors'],
			'main_table_key' => 'contractor1_id',
			'columns' => [],
			'columns_with_alias' => [
				'name' => 'contractor1_name',
				'user_id' => 'user_1'
			]
		];
		$query_params['join'][] = [
			'type' => 'LEFT JOIN',
			'table' => ['fc2' => 'fin_contractors'],
			'main_table_key' => 'contractor2_id',
			'columns' => [],
			'columns_with_alias' => [
				'name' => 'contractor2_name',
				'user_id' => 'user_2'
			]
		];

		if (in_array('fin_projects', $join_keys)) {
			$query_params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fp' => 'fin_projects'],
				'main_table_key' => 'project_id',
				'columns' => [],
				'columns_with_alias' => ['name' => 'project_name']
			];
		}
		$query_params['order_by'] = 'id DESC';
		return DBHelp::select($query_params);
	}

	public static function add($atts, $from_app = false)
	{
		$operation_type_id = $atts['operation_type_id'];
		$params = [
			"operation_type_id" => $operation_type_id,
			"app_id" => !empty($atts['app_id']) ? $atts['app_id'] : 0,
			"project_id" => !empty($atts['project_id']) ? $atts['project_id'] : 0,
			"rate" => !empty($atts['rate']) ? $atts['rate'] : null,
			"comment" => !empty($atts['comment']) ? $atts['comment'] : '',
			"article_id" => !empty($atts['article_id']) ? $atts['article_id'] : 0,
			"bank_operation_id" => !empty($atts['bank_operation_id']) ? $atts['bank_operation_id'] : '',
			"time_type" => !empty($atts['time_type']) ? $atts['time_type'] : "real",
			"account_id" => $atts['account_id'],
			"template_id" => !empty($atts["template_id"]) ? $atts["template_id"] : 0,
			"copy_id" => !empty($atts["copy_id"]) ? $atts["copy_id"] : 0,
			"created_copies" => !empty($atts["created_copies"]) ? $atts["created_copies"] : '[]',
			"chat_id" => !empty($atts["chat_id"]) ? $atts["chat_id"] : 0,
		];

		switch ($atts['time_type']) {
			case 'real':
				$params["date"] = !empty($atts['date']) ? strtotime($atts['date'] . ' ' . date("H:i:s")) : time();
				$params['human_date'] = date('d.m.Y H:i:s', $params["date"]);
				break;
			case 'plan':
				$params["planned_on"] = !empty($atts['planned_on']) ? $atts['planned_on'] : null;
				$params["is_shown"] = !empty($atts["is_shown"]) ? $atts["is_shown"] : 0;
				$params["notify"] = !empty($atts["notify"]) ? $atts["notify"] : 0;
				$params["probability"] = !empty($atts['probability']) ? $atts['probability'] : 100;
				break;
			case 'template':
				$params["repeat_period"] = !empty($atts['repeat_period']) ? $atts['repeat_period'] : 0;
				$params["repeat_start_date"] = !empty($atts["repeat_start_date"]) ? $atts["repeat_start_date"] : 0;
				$params["repeat_end_date"] = !empty($atts["repeat_end_date"]) ? strtotime($atts["repeat_end_date"]) : 0;
				break;
			default:
				$params["date"] = !empty($atts['date']) ? $atts['date'] : 0;
				$params['human_date'] = !empty($atts['date']) ? date('d.m.Y H:i:s', $params["date"]) : '';
				$params["is_shown"] = !empty($atts["is_shown"]) ? $atts["is_shown"] : 0;
				$params["planned_on"] = !empty($atts['planned_on']) ? $atts['planned_on'] : null;
				$params["notify"] = !empty($atts["notify"]) ? $atts["notify"] : 0;
				$params["probability"] = !empty($atts['probability']) ? $atts['probability'] : 0;
				break;
		}

		$wallet = Wallets::get_wallet(['where' => ['fw.id = ' . $atts['wallet_id']]]);
		if (!empty($wallet)) {
			switch ($operation_type_id) {
				case 1: //дохід
					$params["amount2"] = $atts['amount'];
					$params["currency2"] = !empty($atts['currency']) ? $atts['currency'] : $wallet->currency;
					$params["wallet2_id"] = $atts['wallet_id'];
					$params["contractor1_id"] = !empty($atts['contractor_id']) ? $atts['contractor_id'] : null;
					$params["contractor2_id"] = Contractors::get_user_contractor($atts['user_id'])->id;
					$params["department2_id"] = !empty($atts["department_id"]) ? $atts["department_id"] : null;
//				if (empty($atts['is_planned']) && empty($atts['is_template'])) {
//					$last_balance = self::get_last_wallet_operation_or_checkout($params["wallet2_id"], $params['date'])['checkout'];
//					$params["wallet_2_checkout"] = isset($atts['checkout']) ? $atts['checkout'] : (float)$last_balance + (float)$atts['amount'];
//				} elseif ($atts['is_planned'] == 1) {
//					$last_planned_balance = self::get_last_wallet_operation_or_checkout($params["wallet2_id"], $params['planned_on'], true)['checkout'];
//					$params["wallet_2_planned_checkout"] = (float)$last_planned_balance + (float)$atts['amount'];
//				}

					break;

				case 2: // розхід
					$params["amount1"] = $atts['amount'];
					$params["currency1"] = !empty($atts['currency']) ? $atts['currency'] : $wallet->currency;
					$params["wallet1_id"] = $atts['wallet_id'];
					$params["contractor1_id"] = Contractors::get_user_contractor($atts['user_id'])->id;
					$params["contractor2_id"] = !empty($atts['contractor_id']) ? $atts['contractor_id'] : null;
					$params["department1_id"] = !empty($atts["department_id"]) ? $atts["department_id"] : null;
//				if (empty($atts['is_planned']) && empty($atts['is_template'])) {
//					$last_balance = self::get_last_wallet_operation_or_checkout($params["wallet1_id"], $params['date'])['checkout'];
//					$params["wallet_1_checkout"] = isset($atts['checkout']) ? $atts['checkout'] : (float)$last_balance - (float)$atts['amount'];
//				} elseif ($atts['is_planned'] == 1) {
//					$last_planned_balance = self::get_last_wallet_operation_or_checkout($params["wallet1_id"], $params['planned_on'], true)['checkout'];
//					$params["wallet_1_planned_checkout"] = (float)$last_planned_balance + (float)$atts['amount'];
//				}
					break;
				case 3:
					if (!empty($atts['wallet_2_id']) && $atts['user_2_id'] === $atts['user_id']) {
						$wallet2 = Wallets::get_wallet(['where' => [
							'fw.id = ' . $atts['wallet_2_id']
						]]);
					} else {
						$wallet2 = Wallets::get_wallet(['where' => [
							'fw.user_id = ' . $atts['user_2_id'],
							'fw.currency = "' . $wallet->currency . '"',
							'fw.type_id = ' . $wallet->type_id
						]]);
					}
					if (empty($wallet2)) {
						$add_result = Wallets::add([
							"name" => "Автоматично згенерований гаманець",
							"currency" => $wallet->currency,
							"user_id" => $atts['user_2_id'],
							"type_id" => $wallet->type_id,
							"wallet_type" => "cash",
							"checkout" => 0,
							"planned_checkout" => 0,
							'is_shown' => 1,
						]);
						$wallet2 = Wallets::get_wallet(['where' => ['fw.id = ' . $add_result['id']]]);

					}
					if (!empty($wallet2)) {
						$params["amount1"] = $atts['amount'];
						$params["amount2"] = !empty($atts['amount2']) ? $atts['amount2'] : $atts['amount'];
						$params["currency1"] = !empty($atts['currency']) ? $atts['currency'] : $wallet->currency;
						$params["currency2"] = !empty($atts['currency2']) ? $atts['currency2'] : $wallet2->currency;
						$params["wallet1_id"] = $atts['wallet_id'];
						$params["wallet2_id"] = $wallet2->id;
						$params["contractor1_id"] = Contractors::get_user_contractor($atts['user_id'])->id;
						$params["contractor2_id"] = Contractors::get_user_contractor($atts['user_2_id'])->id;
//						$params["department1_id"] = !empty($atts["department1_id"]) ? $atts["department1_id"] : null;
//						$params["department2_id"] = !empty($atts["department2_id"]) ? $atts["department2_id"] : null;
						break;
					}
			}

			$insert_result = DBHelp::insert('fin_operations', $params);
			if (empty($atts['is_template']) && !isset($atts['checkout'])) {
				switch ($operation_type_id) {
					case 1: //дохід
						if ($atts['time_type'] == 'real') {
							self::update_later_operations($params["wallet2_id"], $params['date'], false);
						} elseif ($atts['time_type'] == 'plan') {
							self::update_later_operations($params["wallet2_id"], $params['planned_on'], true);
						}
						break;

					case 2: // розхід
						if ($atts['time_type'] == 'real') {
							self::update_later_operations($params["wallet1_id"], $params['date'], false);
						} elseif ($atts['time_type'] == 'plan') {
							self::update_later_operations($params["wallet1_id"], $params['planned_on'], true);
						}
						break;
					case 3:
						if ($atts['time_type'] == 'real') {
							self::update_later_operations($params["wallet1_id"], $params['date'], false);
							self::update_later_operations($params["wallet2_id"], $params['date'], false);
						} elseif ($atts['time_type'] == 'plan') {
							self::update_later_operations($params["wallet1_id"], $params['planned_on'], true);
							self::update_later_operations($params["wallet2_id"], $params['planned_on'], true);
						}

						break;
				}
			}
			return $insert_result;
		}
		return ['status' => 'error'];
	}

	public
	static function update_later_operations($wallet_id, $date, $planned = false, $checkout = null) //в $date вказується точна дата операції
	{
		if (empty($checkout)) {
			// знаходить останню операцію або checkout, відповідно і суму
			$checkout_obj = self::get_last_wallet_operation_or_checkout($wallet_id, $date, $planned);
			$checkout = !empty($checkout_obj) ? $checkout_obj['checkout'] : 0;
			$last_checkout_date = !empty($checkout_obj) ? $checkout_obj['date'] : 1577836800;
		} else {
			$last_checkout_date = $date;
		}


		$params = ['(fo.`wallet1_id` = ' . $wallet_id . ' OR  fo.`wallet2_id` = ' . $wallet_id . ')'];
		if ($planned) {
			$params['where'][] = 'fo.`planned_on` > ' . $last_checkout_date;
//			$params['where'][] = 'fo.`is_planned` = 1';
			$params['where'][] = 'fo.`time_type` = "plan"';
			$params['where'][] = 'fo.`is_shown` = 1';
			$params['order_by'] = 'planned_on ASC';
		} else {
			$params['where'][] = 'fo.`date` > ' . $last_checkout_date;
//			$params['where'][] = 'fo.`is_planned` = 0';
			$params['where'][] = 'fo.`time_type` = "real"';
			$params['order_by'] = 'date ASC';
		}

		$operations = Operation::get_operations($params);

		if (!empty($operations)) {
			foreach ($operations as $operation) {
				if ($operation['wallet1_id'] == $wallet_id && $operation['operation_type_id'] == 3) {
					$operation['operation_type_id'] = 6;  //передано іншій касі
				}

				if ($operation['wallet2_id'] == $wallet_id && $operation['operation_type_id'] == 3) {
					$operation['operation_type_id'] = 5;    //отримано з іншої каси
				}

				if ($operation['operation_type_id'] == 1 ||
					$operation['operation_type_id'] == 4 ||
					$operation['operation_type_id'] == 5) {
					$checkout += $operation['amount2'];
					DBHelp::update('fin_operations', $operation['id'], $planned ? [
						'wallet_2_planned_checkout' => $checkout
					] : [
						'wallet_2_checkout' => $checkout
					]);
				}

				if ($operation['operation_type_id'] == 2 ||
					$operation['operation_type_id'] == 6) {
					$checkout -= $operation['amount1'];
					DBHelp::update('fin_operations', $operation['id'], $planned ? [
						'wallet_1_planned_checkout' => $checkout
					] : [
						'wallet_1_checkout' => $checkout
					]);
				}
			}
		}
		Wallets::update_later_checkouts($wallet_id, $date, $planned);
		if ($planned) {

		} else {
			Wallets::update($wallet_id, [
				'checkout' => $checkout
			]);
		}

		return ['status' => 'ok'];
	}

	/**
	 * get operations from date
	 *
	 * @param int $wallet_id wallet_id
	 * @param int $timestamp date
	 * @param array $params params
	 * @return array operations[]
	 */
	public
	static function get_operations_from_date($wallet_id, $timestamp, $params = [])
	{
		$query_params = self::select_query();
		$query_params['where'] = [
			'fo.`date` > ' . $timestamp,
			'(fo.`wallet1_id` = ' . $wallet_id . ' OR  fo.`wallet2_id` = ' . $wallet_id . ')'
		];
		$query_params['order_by'] = 'date ASC';
		$query_params = array_merge($query_params, $params);

		return DBHelp::select($query_params);
	}

	public
	static function delete($operation_id)
	{
		$operation = self::get_operation(['where' => ['id =' . $operation_id]])['result'];

		DBHelp::delete('fin_operations', $operation_id);
		if (!empty($operation)) {
			$is_planned = $operation->time_type == 'plan';
			if ($operation->wallet1_id != 0) {
				self::update_later_operations($operation->wallet1_id, $is_planned ? $operation->planned_on : $operation->date, $is_planned);
			}
			if ($operation->wallet2_id != 0) {
				self::update_later_operations($operation->wallet2_id, $is_planned ? $operation->planned_on : $operation->date, $is_planned);
			}
		}

		return ['status' => 'ok'];
	}

	public
	static function get_dates_and_amounts($operations, $period, $date_from = null, $date_to = null)
	{
		$periods_end_dates = [];
		if (empty($date_from) && empty($date_to)) {
			$periods_end_dates = DateHelper::get_timestamps_of_period($period);
		}

		$date_to = !empty($date_to) ? $date_to : strtotime("first day of January 00:00:00");
		$date_from = !empty($date_from) ? $date_from : strtotime("last day of December 23:59:59");

//		$operations = self::get_operations(['where' => [
//			'(fo.operation_type_id= 1 OR fo.operation_type_id= 2)',
//			'fo.date > ' . $date_to,
//			'fo.date < ' . $date_from
//		]]);

		$data_charts = [];
		for ($i = 0; $i < count($periods_end_dates) - 1; $i++) {
			if (empty($data_charts[$periods_end_dates[$i + 1]]['income'])) {
				$data_charts[$periods_end_dates[$i + 1]]['income'] = 0;
			}
			if (empty($data_charts[$periods_end_dates[$i + 1]]['expense'])) {
				$data_charts[$periods_end_dates[$i + 1]]['expense'] = 0;
			}
			foreach ($operations as $operation) {

				if ($operation['date'] > $periods_end_dates[$i] && $operation['date'] < $periods_end_dates[$i + 1]) {

					if ($operation['operation_type_id'] == 1) {

						if ($operation['currency2'] != 'UAH') {
							$currency_rate = CurrencyRate::get_exchange_rates($operation['currency2']);
							$operation['total'] = $operation['amount2'] * $currency_rate['buy'];
						} else {
							$operation['total'] = $operation['amount2'];
						}
						$data_charts[$periods_end_dates[$i + 1]]['income'] += $operation['total'];
					}

					if ($operation['operation_type_id'] == 2) {
						if ($operation['currency1'] != 'UAH') {
							$currency_rate = CurrencyRate::get_exchange_rates($operation['currency1']);
							$operation['total'] = $operation['amount1'] * $currency_rate['buy'];
						} else {
							$operation['total'] = $operation['amount1'];
						}

						$data_charts[$periods_end_dates[$i + 1]]['expense'] += $operation['total'];
					}
				}
			}
		}
		ksort($data_charts);

		$data_charts_formatted = [['Дата', 'Доходи', 'Витрати', 'Дельта']];
		$months = ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"];

		foreach ($data_charts as $timestamp => $total_left) {
			if ($period == 'week' || $period == 'month') {
				$data_charts_formatted[] = [date("d.m", $timestamp), $total_left['income'], $total_left['expense'], $total_left['income'] - $total_left['expense']];
			} elseif ($period == 'year') {
				$data_charts_formatted[] = [
					$months[(int)date("m", $timestamp) - 1],
					$total_left['income'],
					$total_left['expense'],
					$total_left['income'] - $total_left['expense']
				];
			}
		}

		return $data_charts_formatted;
	}

	public
	static function change_app_in_operation($opp_id, $operation_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('UPDATE `fin_operations` SET app_id=' . $operation_id . ' WHERE id=' . $opp_id);

		$query->getResult();
		return ['status' => 'ok', 'id' => $db->insertID()];
	}

	public
	static function change_expense_in_operation($opp_id, $expense_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('UPDATE `fin_operations` SET article_id=' . $expense_id . ' WHERE id=' . $opp_id);

		$query->getResult();
		return ['status' => 'ok', 'id' => $db->insertID()];
	}

	public
	static function change_department_in_operation($opp_id, $department_id)
	{
		$operation = self::get_operation(['where' => ['id =' . $opp_id]]);
		if (!empty($operation)) {
			$db = Config\Database::connect();
			if ($operation->operation_type_id == 1) {
				$query = $db->query('UPDATE `fin_operations` SET department2_id=' . $department_id . ' WHERE id=' . $opp_id);
			} elseif ($operation->operation_type_id == 2) {
				$query = $db->query('UPDATE `fin_operations` SET department1_id = ' . $department_id . ' WHERE id=' . $opp_id);
			}
			$query->getResult();
			return ['status' => 'ok'];
			return ['status' => 'ok'];
		}
	}

	public
	static function change_date_in_operation($opp_id, $date)
	{
		$timestamp = strtotime($date . date('H:i:s', time()));
		$human_date = date('d.m.Y', $timestamp);
		$operation = self::get_operation(['where' => ['id =' . $opp_id]]);
		if ($operation['status'] && !empty($operation['result'])) {
			$operation = $operation['result'];
			if ($operation->time_type === 'real') {
				self::update($opp_id, ['date' => $timestamp, 'human_date' => $human_date]);
			}
			if ($operation->time_type === 'plan') {
				self::update($opp_id, ['planned_on' => $timestamp]);
			}

			$date_to_update_from = $operation->date > $timestamp ? $timestamp : $operation->date;
			if ($operation->wallet1_id != 0) { // зона ризику
				self::update_later_operations($operation->wallet1_id, $date_to_update_from, $operation->time_type === 'plan');
			}
			if ($operation->wallet2_id != 0) { // зона ризику
				self::update_later_operations($operation->wallet2_id, $date_to_update_from, $operation->time_type === 'plan');
			}
			return ['status' => 'ok'];
		}
	}

	public static function update($id, $params)
	{
		return DBHelp::update('fin_operations', $id, $params);
	}

	public
	static function get_last_wallet_operation_or_checkout($wallet_id, $date, $is_planned = false)
	{
		$operation = self::get_operation([
			'where' => $is_planned ?
				[
					'time_type = "plan"',
					'is_shown = 1',
					'planned_on < "' . $date . '"',
					'(wallet1_id = "' . $wallet_id . '" OR wallet2_id = "' . $wallet_id . '")'
				] :
				[
					'time_type = "real"',
					'date < "' . $date . '"',
					'(wallet1_id = "' . $wallet_id . '" OR wallet2_id = "' . $wallet_id . '")'
				],
			'order_by' => $is_planned ? 'planned_on DESC' : 'date DESC',
			'limit' => 1
		]);

		$result_operation = [];
		if (!empty($operation['result'])) {
			$operation = $operation['result'];
			$result_operation['id'] = $operation->id;
			$result_operation['date'] = $is_planned ? $operation->planned_on : $operation->date;
			if ($wallet_id == $operation->wallet1_id) {
				$result_operation['checkout'] = $is_planned ? $operation->wallet_1_planned_checkout : $operation->wallet_1_checkout;
				$result_operation['currency'] = $operation->currency1;
			} elseif ($wallet_id == $operation->wallet2_id) {
				$result_operation['checkout'] = $is_planned ? $operation->wallet_2_planned_checkout : $operation->wallet_2_checkout;
				$result_operation['currency'] = $operation->currency2;
			}
		} else { // спочатку шукаємо серед операцій, якщо раніші операції відсутні шукаємо чекаут
			$result_operation['id'] = 0;

			if (!$is_planned) {
				$last_checkout = Wallets::get_wallet_checkout([
					'where' => [
						'wallet_id = ' . $wallet_id,
						'date < ' . $date,
					],
					'order_by' => 'date DESC'
				]);
//Dev::var_dump($last_checkout);
				$result_operation['date'] = !empty($last_checkout) ? $last_checkout->date : Wallets::get_wallet(['where' => [
					'fw.id = ' . $wallet_id
				]])->create_date;

				$result_operation['checkout'] = !empty($last_checkout) ? ($last_checkout->amount) : 0;
				$result_operation['currency'] = '';
			} else {
				$last_checkout = Wallets::get_wallet_checkout([
					'where' => [
						'wallet_id = ' . $wallet_id,
						'date < ' . $date,
						'date > ' . strtotime('today 00:00:00')
					],
					'order_by' => 'date DESC'
				]);
//Dev::var_dump($last_checkout);
				if (!empty($last_checkout)) {
					$result_operation['date'] = $last_checkout->date;
					$result_operation['checkout'] = $last_checkout->amount;
					$result_operation['currency'] = '';
				} else {
					$wallet = Wallets::get_wallet([
						'where' => [
							'fw.id = ' . $wallet_id,
						],
						'order_by' => 'date DESC'
					]);

					$result_operation['date'] = strtotime('today 00:00:00');
					$result_operation['checkout'] = $wallet->checkout;
					$result_operation['currency'] = $wallet->currency;
				}
			}
		}

		return $result_operation;
	}

	static public function set_departments_to_operations()
	{
		$operations = self::get_operations();
		if (!empty($operations)) {
			foreach ($operations as $operation) {
				$department1_id = 0;
				$department2_id = 0;
				$contractor1 = Contractors::get_contractor($operation['contractor1_id']);
				if (!empty($contractor1)) {
					$profession1 = Position::get_positions_with_access($contractor1->user_id);
					if (!empty($profession1)) {
						$department1_id = $profession1[0]['department_id'];
					}
				}

				$contractor2 = Contractors::get_contractor($operation['contractor2_id']);
				if (!empty($contractor2)) {
					$profession2 = Position::get_positions_with_access($contractor2->user_id);
					if (!empty($profession2)) {
						$department2_id = $profession2[0]['department_id'];
					}
				}
				$db = Config\Database::connect();
				$query_row = 'UPDATE `fin_operations` SET `department1_id`=' . $department1_id . ',`department2_id`=' . $department2_id . ' WHERE id = ' . $operation['id'];
				echo $query_row;
				echo "<br/>";
				echo "<br/>";
				$query = $db->query($query_row);

				$query->getResult();
			}
		}
	}

	public static function get_tov_operations($from_last_statement = false, $type = null, $start_date = null, $end_date = null)
	{
		$id_trn = null;
		if ($from_last_statement) {
			$last_statement = self::get_last_inserted_bank_operation_id();
			$id_trn = !empty($last_statement) ? $last_statement->bank_operation_id : null;
		}
		$pb_data = PrivatBank::method('transactions', [
			'start_date' => $start_date,
			'end_date' => $end_date,
			'type' => $type,
			'id_trn' => $id_trn
		]);
//		var_dump($pb_data);
		$operations = [];
		if (!empty($pb_data['StatementsResponse']['statements'])) {
			$statements = $pb_data['StatementsResponse']['statements'];

			foreach ($statements as $statement_link) {
				foreach ($statement_link as $statement) {
					$operations[] = [
						'amount' => $statement['BPL_SUM'],
						'currency' => $statement['BPL_CCY'],
						'operation_type_id' => $statement['TRANTYPE'] == 'C' ? 1 : 2,
						'contractor_name' => $statement['TRANTYPE'] == 'C' ? $statement['BPL_A_NAM'] : $statement['BPL_B_NAM'],
						'date' => strtotime($statement['DATE_TIME_DAT_OD_TIM_P']),
						'comment' => $statement['BPL_OSND'] . ' перенесено автоматично з Приват 24',
//						'contractor_type' => 'existing',
						'app_id' => 0,
						'project_id' => 0,
						'bank_operation_id' => $statement['ID']
					];
				}
			}
		}
		return $operations;
	}

	public
	static function save_bank_operations()
	{
		$operations = self::get_tov_operations(true);
//		$operations = self::get_tov_operations(false, null,1577837344, time());
		$operationlications = Applications::get_tov_transfered_and_payed_apps_orders();
		$service_contractors = [
			'TDT КОМИССИЯ ЗА ДЕБЕТОВАНИЕ СЧЕТА(UAH)',
			'Доходы за кассовое обслуживание зарпл.',
			'TBK КОМИС. ЗА ОБСЛ. ТЕК. СЧ. БАНК-КЛ(U',
			'ТРВ КОМ ЗА Р/О Ю/Л В ПОСЛЕОПЕРАЦ.ВРЕМЯ'
		];
		if (!empty($operations)) {
			foreach ($operations as &$operation) {
				if (in_array($operation['contractor_name'], $service_contractors)) {
					$contractor_id = 202; // Приват Банк
					$operation['article_id'] = 49;
				} else {
					$contractor = Contractors::search_one($operation['contractor_name']);
					if (!empty($contractor)) {
						$contractor_id = $contractor->id;
					} else {
						$contractor_id = Contractors::add(['name' => $operation['contractor_name']])['id'];
					}
				}
				//визначати заявку по номеру рахунку
				if (!empty($operationlications)) {
					foreach ($operationlications as $operationlication) {
						if (!empty($operationlication['order_names'])) {
							$order_names = explode(',', $operationlication['order_names']);
							foreach ($order_names as $order_name) {
								if (strripos($operation['comment'], $order_name) !== false) {
									$operation['app_id'] = $operationlication['id'];
								}
							}
						}
					}
				}

				// по назві визначати статтю доходів, розходів
				if ($operation['operation_type_id'] == 1) { // посилати в відділ продаж повідомлення про прихід
					$operation['user_id'] = 15;
					$operation['contractor_id'] = $contractor_id;
					$operation['wallet_2_id'] = 1;
					self::add_income($operation, true);
//					Telegram::send_message('На ТОВ зайшло ' . number_format($operation['amount'], 2, ',', ' ') . ' грн. Для деталей перейдіть у виписку http://fin.ekonombud.in.ua/operation/user/15');
				} elseif ($operation['operation_type_id'] == 2) {
					$operation['user_id'] = 15;
					$operation['contractor_id'] = $contractor_id;
					$operation['wallet_1_id'] = 1;
					self::add_expense($operation, true);
				}
			}

		}
	}

	public
	static function get_last_inserted_bank_operation_id()
	{
		$db = Config\Database::connect();
		$query_row = "SELECT id, date, bank_operation_id FROM `fin_operations` WHERE `bank_operation_id`!= 0 ORDER BY bank_operation_id DESC LIMIT 1";
		$query = $db->query($query_row);
		return $query->getFirstRow();
	}

	public
	static function get_project_operations($project_id)
	{
		$query_params = self::select_query(['fin_contractors']);
		$query_params['where'] = ['project_id =' . $project_id];

		$query_params['order_by'] = 'id DESC';
		return DBHelp::select($query_params);
	}

	public static function get_operations_from_all_active_cards()
	{
		$wallets = Wallets::get_wallets(
			['where' => [
				'wallet_type = "card"',
				'fa.status = "active"'
			]],
			['fin_accounts']
		);

		$operations = [];

		if (!empty($wallets)) {
			foreach ($wallets as $wallet) {

				$user_result = User::get_user(
					['where' => [
						'id =' . $wallet['user_id']
					]]
				);

				if ($wallet['type_id'] == 1) { // карти фіз.осіб
					if ($wallet['bank_id'] == 1) { // ПриватБанк

						$operations = PrivatBank::get_card_operations($wallet['id'])['operations'];
					} elseif ($wallet['bank_id'] == 2) { // МоноБанк
						$operations = MonoBankM::get_card_operations($wallet['id'])['operations'];
					}
				} else { //карти безнальних рахунків
//					Models\Operation::save_bank_operations(); // ПриватБанк ТОВ
				}

				if (!empty($operations) && $user_result['status'] == 'ok') {
					$user = $user_result['result'];
					foreach ($operations as $operation) {
						$exist = self::get_operation(['where' => ['bank_operation_id ="' . $operation['bank_operation_id'] . '"']]);
						if (empty($exist['result'])) {
							$operation['user_id'] = $user->id;
							$operation['account_id'] = $user->account_id;
							$contractor_info_string = str_replace([',', '_', '-'], "***", $operation['contractor']);
							$contractor_info = explode('***', $contractor_info_string);
							$contractor = Contractors::search_one($contractor_info[0]);
							if (!empty($contractor)) {

								$operation['contractor_id'] = $contractor->id;
								$contractor_template = Contractors::search_template($contractor->id);
								if (!empty($contractor_template)) {
									$operation = array_merge($operation, $contractor_template);
								}
							} else {
								echo 'not found';
								$new_contractor = Contractors::add(['name' => $contractor_info[0]]);
								$operation['contractor_id'] = $new_contractor['id'];
								Telegram::send_personal_message(
									$user->id,
									'Додано нового контрагента ' . $contractor_info[0]
								);
							}

							self::add($operation, true);
						}
					}
				}
			}
		}
		return $operations;
	}

	public static function preview_process($operation, $is_planned = false)
	{
		$processed_operation = [];
		$processed_operation['id'] = $operation['id'];
		$processed_operation['operation_type_id'] = $operation['operation_type_id'];
		$processed_operation['app_id'] = $operation['app_id'];
		$processed_operation['app_name'] = $operation['app_product'];
		$processed_operation['project_id'] = $operation['project_id'];
		$processed_operation['project_name'] = !empty($operation['project_name']) ? $operation['project_name'] : '';
		$processed_operation['rate'] = $operation['rate'];
		$processed_operation['comment'] = $operation['comment'];
		$processed_operation['date'] = $operation['date'];
		$processed_operation['human_date'] = date("yy-m-d", $operation['date']);
		$processed_operation['article_id'] = $operation['article_id'];
		$processed_operation['article_name'] = !empty($operation['article_name']) ? $operation['article_name'] : '';
		$processed_operation['time_type'] = !empty($operation['time_type']) ? $operation['time_type'] : 'real';

		if ($is_planned) {
			$processed_operation['notify'] = $operation['notify'];
			$processed_operation['is_shown'] = $operation['is_shown'];
			$processed_operation['planned_on'] = $operation['planned_on'];
			$processed_operation['human_planned_on'] = date("yy-m-d", $operation['planned_on']);
		}

		if ($operation['operation_type_id'] == 1 || $operation['operation_type_id'] == 4 || $operation['operation_type_id'] == 6) {
			$processed_operation['amount'] = $operation['amount2'];
			$processed_operation['currency'] = $operation['currency2'];
			$processed_operation['wallet_id'] = $operation['wallet2_id'];
			$processed_operation['wallet_checkout'] = $operation['wallet_2_checkout'];
			$processed_operation['wallet_planned_checkout'] = $operation['wallet_2_planned_checkout'];
			$processed_operation['contractor_id'] = $operation['contractor1_id'];
			$processed_operation['contractor_name'] = !empty($operation['contractor1_name']) ? $operation['contractor1_name'] : '';

		} elseif ($operation['operation_type_id'] == 2 || $operation['operation_type_id'] == 5) {
			$processed_operation['amount'] = $operation['amount1'];
			$processed_operation['currency'] = $operation['currency1'];
			$processed_operation['wallet_id'] = $operation['wallet1_id'];
			$processed_operation['wallet_checkout'] = $operation['wallet_1_checkout'];
			$processed_operation['wallet_planned_checkout'] = $operation['wallet_1_planned_checkout'];
			$processed_operation['contractor_id'] = $operation['contractor2_id'];
			$processed_operation['contractor_name'] = !empty($operation['contractor2_name']) ? $operation['contractor2_name'] : '';

		} elseif ($operation['operation_type_id'] == 7) {
			$processed_operation['amount'] = $operation['amount1'];
			$processed_operation['currency'] = $operation['currency1'];
			$processed_operation['wallet_id'] = $operation['wallet1_id'];
			$processed_operation['wallet_checkout'] = $operation['wallet_1_checkout'];
			$processed_operation['contractor_id'] = $operation['contractor1_id'];
			$processed_operation['contractor_name'] = $operation['contractor1_name'];
			$processed_operation['amount2'] = $operation['amount2'];
			$processed_operation['currency2'] = $operation['currency2'];
			$processed_operation['wallet_2_id'] = $operation['wallet2_id'];
			$processed_operation['wallet_2_checkout'] = $operation['wallet_2_checkout'];
		}

		return $processed_operation;
	}

	public static function get_operations_grouped_by_date($operation_list, $is_planned = false)
	{
		$processed_operation_list = [];
		foreach ($operation_list as $operation) {
			$processed_operation = self::preview_process($operation, $is_planned);
			if ($operation['operation_type_id'] == 1 || $operation['operation_type_id'] == 4 || $operation['operation_type_id'] == 6) {
				if ($is_planned) {
					$processed_operation_list[$processed_operation['planned_on']]['operation'][] = $processed_operation;
				} else {
					$processed_operation_list[$processed_operation['date']]['operation'][] = $processed_operation;
				}
			} elseif ($operation['operation_type_id'] == 2 || $operation['operation_type_id'] == 5) {
				if ($is_planned) {
					$processed_operation_list[$processed_operation['planned_on']]['operation'][] = $processed_operation;
				} else {
					$processed_operation_list[$processed_operation['date']]['operation'][] = $processed_operation;
				}
			} elseif ($operation['operation_type_id'] == 7) {
				if ($is_planned) {
					$processed_operation_list[$processed_operation['planned_on']]['operation'][] = $processed_operation;
				} else {
					$processed_operation_list[$processed_operation['date']]['operation'][] = $processed_operation;
				}
			}
		}
		return $processed_operation_list;
	}

	public
	static function create_from_templates()
	{
		$templates = self::get_operations(['where' => [
//			'is_planned = 0',
//			'is_template = 1',
			'time_type = "template"',
			'repeat_start_date < ' . time(),
			'(repeat_end_date > ' . time() . ' OR repeat_end_date IS null)'
		]]);

		if (!empty($templates)) {
			foreach ($templates as $template) {
				self::create_from_template((object)$template);
			}
		}
	}

	public
	static function create_from_template($template)
	{
		$in_a_month = time() + 60 * 60 * 24 * 30;
		$end_date = (!empty($template->repeat_end_date) && $template->repeat_end_date < $in_a_month) ? $template->repeat_end_date : $in_a_month;

		$dates = DateHelper::get_future_timestamps_of_period($template->repeat_period, time(), $end_date, $template->repeat_start_date);

		$created_copies = (array)json_decode(htmlspecialchars_decode($template->created_copies));

		if (!empty($created_copies)) {
			ksort($created_copies);
		}

		$index = !empty($created_copies) ? max($created_copies) : 1;

		if (!empty($dates)) {
			foreach ($dates as $date) {
				if (!key_exists(date('d.m.Y', $date), $created_copies)) {
					$params = [
						'operation_type_id' => $template->operation_type_id,
						"app_id" => $template->app_id,
						"project_id" => $template->project_id,
						"comment" => htmlspecialchars_decode($template->comment) .
							'<br/><br/>Сформовано автоматично з шаблону # ' . $template->id,
						"article_id" => $template->article_id,
//						"is_planned" => 1,
						'time_type' => "plan",
						"account_id" => $template->account_id,
						"probability" => 100,
						"is_shown" => 1,
						"rate" => $template->rate,
						"notify" => $template->notify,
						'is_template' => 0,
						"template_id" => $template->id,
						"copy_id" => $index,
						'planned_on' => $date
					];

					switch ($template->operation_type_id) {
						case 1:
							$params['amount'] = $template->amount2;
							$params['currency'] = $template->currency2;
							$params['wallet_id'] = $template->wallet2_id;
							$params['contractor_id'] = $template->contractor1_id;
							$params['user_id'] = !empty($template->contractor2_id) ? Contractors::get_contractor(['where' => ['id = ' . $template->contractor2_id]])->user_id : 0;
							break;
						case 2:
							$params['amount'] = $template->amount1;
							$params['currency'] = $template->currency1;
							$params['wallet_id'] = $template->wallet1_id;
							$params['contractor_id'] = $template->contractor2_id;
							$params['user_id'] = !empty($template->contractor1_id) ? Contractors::get_contractor(['where' => ['id = ' . $template->contractor1_id]])->user_id : 0;
							break;
						case 3:
							$params['amount'] = $template->amount;
							$params['amount2'] = $template->amount2;
							$params['currency'] = $template->currency1;
							$params['currency2'] = $template->currency2;
							$params['wallet_id'] = $template->wallet1_id;
							$params['wallet_2_id'] = $template->wallet2_id;
							$params['contractor_id'] = $template->contractor1id;
							$params['contractor2_id'] = $template->contractor2_id;
							break;
					}
					self::add($params);

					$created_copies[date('d.m.Y', $date)] = $index;
					$index++;
				}
			}
			self::update($template->id, ['created_copies' => \GuzzleHttp\json_encode($created_copies)]);
		}
	}

	public static function select_query($join_keys = [])
	{
		$params = [
			'table' => ['fo' => 'fin_operations'],
			'columns' => [
				'id',
				'amount1',
				'amount2',
				'currency1',
				'currency2',
				'operation_type_id',
				'wallet1_id',
				'wallet2_id',
				'wallet_1_checkout',
				'wallet_2_checkout',
				'wallet_1_planned_checkout',
				'wallet_2_planned_checkout',
				'contractor1_id',
				'contractor2_id',
				'app_id',
				'article_id',
				'project_id',
				'rate',
				'comment',
				'bank_operation_id',
				'date',
				'department1_id',
				'department2_id',
//				'is_planned',
//				'is_template',
				'time_type',
				'planned_on',
				'is_shown',
				'notify',
				'template_id',
				'copy_id',
				'created_copies',
				'probability',
				'account_id',
				'repeat_period',
				'repeat_start_date',
				'repeat_end_date'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
//				'is_planned = 0'
			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_projects', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fp' => 'fin_projects'],
				'main_table_key' => 'project_id',
				'columns' => [],
				'columns_with_alias' => ['name' => 'project_name']
			];
		}
		if (in_array('fin_applications', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fa' => 'fin_applications'],
				'main_table_key' => 'app_id',
				'columns' => [],
				'columns_with_alias' => ['product' => 'app_product']
			];
		}
		if (in_array('fin_users', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fu' => 'fin_users'],
				'other_table_key' => 'fa.author_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'app_author_name',
					'surname' => 'app_author_surname'
				]
			];
		}

		if (in_array('fin_articles', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['far' => 'fin_articles'],
				'main_table_key' => 'article_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'article_name'
				]
			];
		}

		if (in_array('fin_contractors', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fc1' => 'fin_contractors'],
				'main_table_key' => 'contractor1_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'contractor1_name',
				]
			];

			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fc2' => 'fin_contractors'],
				'main_table_key' => 'contractor2_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'contractor2_name',
				]
			];
		}
		return $params;
	}
}
