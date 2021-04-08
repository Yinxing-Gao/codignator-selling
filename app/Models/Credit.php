<?php


namespace App\Models;

use App\Controllers\Contractor;
use Config;

class Credit
{
	public static function get_credits($params = [], $join = [])
	{
		$query_params = self::select_query(array_merge(['fin_contractors', 'fin_types', 'fin_users', 'fin_departments'], $join));

		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_credit($params = [], $join = [])
	{
		$query_params = self::select_query(array_merge(['fin_contractors', 'fin_types', 'fin_users', 'fin_departments'], $join));

		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public static function add($atts)
	{
		$currency = !empty($atts['currency']) ? $atts['currency'] : 'UAH';
		$session = Config\Services::session();
		$user_id = $session->get('user_id');
		$amount = !empty($atts['amount']) ? (float)$atts['amount'] : 0;
		$contractor_id = !empty($atts['contractor_id']) ? $atts['contractor_id'] : 0;
		$contractor = !empty($contractor_id) ? Contractors::get_contractor($contractor_id) : null;
		$contractor_name = !empty($contractor) ? $contractor->name : '';
		$credit_app_id = !empty($atts['credit_app_id']) ? $atts['credit_app_id'] : 0;
		$percent_currency = !empty($atts['percent_currency']) ? $atts['percent_currency'] : $currency;

		if (!empty($atts['end_date']) && empty($atts['credit_app_id'])) {
//			$end_date = $atts['end_date'];
//			$params = [
//				'author_id' => !empty($atts['responsible_id']) ? $atts['responsible_id'] : $user_id,
//				'department_id' => $atts['department_id'],
//				'date' => time(),
//				'date_for' => strtotime($end_date),
//				'amount' => $amount,
//				'currency' => $currency,
//				'type_id' => !empty($atts['type_id']) ? $atts['type_id'] : 0,
//				'product' => "Оплата тіла кредиту -" . $contractor_name . " " . $amount . " " . $currency,
//				'article_id' => self::get_credit_article(),
//				'situation' => "Закінчився термін по кредиту " . $contractor_name . " " . $amount . " " . $currency,
//				'data' => "Закінчився термін по кредиту " . $contractor_name . " " . $amount . " " . $currency . "\r\n",
//				"Був отриманий " . date('d.m.Y'),
//				'decision' => "Виплатити",
//			];
//
//			$result = Applications::add($params);
//			if ($result['status'] == 'ok') {
//				$credit_app_id = $result['id'];
//			}
		}

		$params = [
			'contractor_id' => !empty($atts['contractor_id']) ? $atts['contractor_id'] : 0,
			'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
			'amount' => $amount,
			'currency' => $currency,
//			'type_id' => $atts['type_id'],
			'percent' => !empty($atts['percent']) ? (float)$atts['percent'] : 0,
			'percent_amount' => !empty($atts['percent_amount']) ? (float)$atts['percent_amount'] : 0,
			'percent_currency' => $percent_currency,
			'percent_type_id' => !empty($atts['percent_type_id']) ? $atts['percent_type_id'] : 1,
			'next_payment_date' => !empty($atts['percent_date']) ? strtotime($atts['percent_date']) : 0,
			'percent_period' => !empty($atts['percent_period']) ? $atts['percent_period'] : 'month',
			'end_date' => !empty($atts['end_date']) ? strtotime($atts['end_date']) : 0,
			'percent_app_id' => !empty($atts['percent_app_id']) ? $atts['percent_app_id'] : 0,
			'credit_app_id' => $credit_app_id,
			'comment' => !empty($atts['comment']) ? trim(htmlspecialchars($atts['comment'])) : 0,
			'responsible_id' => !empty($atts['responsible_id']) ? $atts['responsible_id'] : 0
		];
		$result = DBHelp::insert('fin_credits', $params);

		if ($result['status'] == 'ok') {
			if (!empty($atts['percent_amount'])) {
				$params = [
					"department_id" => !empty($atts['department_id']) ? $atts['department_id'] : 0,
					"amount" => (float)$atts['percent_amount'],
					"currency" => !empty($atts['percent_currency']) ? $atts['percent_currency'] : $currency,
					"wallet_id" => !empty($atts['wallet_id']) ? $atts['wallet_id'] : Wallets::get_user_wallet($user_id, $percent_currency),
					"contractor_id" => $contractor_id,
					"operation_type_id" => 2,
					"comment" => "Оплата процентів по кредиту -" . $contractor_name . " " . $amount . " " . $currency,
					"date" => !empty($atts['date']) ? strtotime($atts['date'] . ' ' . date("H:i:s")) : time(),
					"article_id" => self::get_credit_percent_article(),
					"is_template" => 1,
					"account_id" => $atts['account_id'],
					"repeat_period" => !empty($atts['percent_period']) ? $atts['percent_period'] : 'month',
					"repeat_start_date" => time(),
					"repeat_end_date" => !empty($atts['end_date']) ? strtotime($atts['end_date']) : 0
				];
				Operation::add($params);

				$params = [
					'amount' => (float)$atts['percent_amount'],
					'currency' => !empty($atts['percent_currency']) ? $atts['percent_currency'] : $currency,
					'accrual_type' => 'credit', //expense
					'type_id' => !empty($atts['type_id']) ? $atts['type_id'] : 1,
					'article_id' => self::get_credit_percent_article(),
					'contractor_id' => $contractor_id,
					"comment" => "Оплата процентів по кредиту -" . $contractor_name . " " . $amount . " " . $currency,
					"date" => !empty($atts['date']) ? strtotime($atts['date'] . ' ' . date("H:i:s")) : time(),
					"is_template" => 1,
					'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
					"repeat_period" => !empty($atts['percent_period']) ? $atts['percent_period'] : 'month',
					"repeat_start_date" => time(),
					"repeat_end_date" => !empty($atts['end_date']) ? strtotime($atts['end_date']) : 0
				];

				Accruals::add($params);

			}

			if (!empty($atts['urgently'])) {
				if (!empty($atts['project_id'])) {
					$message_project = Projects::get_project($atts['project_id']);
					Telegram::send_message('Отримано нову актуальну заявку на ' . addslashes($atts['product']) . ' на суму ' . $atts['amount'] . ' ' . $atts['currency'] . ' по проекту ' . $message_project->name . '. Перейдіть на сайт, щоб одобрити' . " \r\n" . base_url('application/department', 'https'));
				} else {
					Telegram::send_message('Отримано нову актуальну заявку на ' . addslashes($atts['product']) . ' на суму ' . $atts['amount'] . ' ' . $atts['currency'] . '. Перейдіть на сайт, щоб одобрити' . " \r\n" . base_url('application/department', 'https'));
				}
			}
			return ['status' => 'ok', 'id' => $result['id']];
		} else {
			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
		}
	}

	public static function get_credit_article()
	{
		// todo
		return 63;
	}

	public static function get_credit_percent_article()
	{
		// todo
		return 63;
	}

	public static function edit($atts)
	{
		$db = Config\Database::connect();
		$contractor_id = !empty($atts['contractor_id']) ? $atts['contractor_id'] : 0;
		$department_id = !empty($atts['department_id']) ? $atts['department_id'] : 0;

		$amount = !empty($atts['amount']) ? (float)$atts['amount'] : 0;
		$currency = !empty($atts['currency']) ? $atts['currency'] : 'UAH';
		$type_id = $atts['type_id'];

		$percent = !empty($atts['percent']) ? (float)$atts['percent'] : 0;
		$percent_amount = !empty($atts['percent_amount']) ? (float)$atts['percent_amount'] : 0;
		$percent_currency = !empty($atts['percent_currency']) ? $atts['percent_currency'] : $currency;
		$percent_type_id = !empty($atts['percent_type_id']) ? $atts['percent_type_id'] : 1;
		$next_payment_date = !empty($atts['percent_date']) ? strtotime($atts['percent_date']) : 0;
		$percent_period = !empty($atts['percent_period']) ? $atts['percent_period'] : 'month';


		$end_date = !empty($atts['end_date']) ? strtotime($atts['end_date']) : 0;
		$percent_app_id = !empty($atts['percent_app_id']) ? $atts['percent_app_id'] : 0;
		$credit_app_id = !empty($atts['credit_app_id']) ? $atts['credit_app_id'] : 0;
		$comment = !empty($atts['comment']) ? trim(htmlspecialchars($atts['comment'])) : 0;
		$responsible_id = !empty($atts['responsible_id']) ? $atts['responsible_id'] : 0;
//
//		echo 'INSERT INTO `fin_credits`(`amount`, `currency`, `type_id`, `contractor_id`, `department_id`, `percent`, `percent_amount`, `percent_currency`, `next_payment_date`, `period`, `end_date`, `percent_app_id`, `credit_app_id`, `comment`, `responsible_id`)
// 									VALUES (' . $amount . ', "' . $currency . '", ' . $type_id . ',' . $contractor_id . ',' . $department_id . ', ' . $percent . ', ' . $percent_amount . ', "' . $percent_currency . '", ' . $next_payment_date . ', "' . $period . '", ' . $end_date . ', ' . $percent_app_id . ', ' . $credit_app_id . ', "' . $comment . '", ' . $responsible_id . ')';
//		die();

		$query = $db->query('INSERT INTO `fin_credits`(`amount`, `currency`, `type_id`, `contractor_id`, `department_id`, `percent`, `percent_amount`, `percent_currency`, `percent_type_id`,  `next_payment_date`, `percent_period`, `end_date`, `percent_app_id`, `credit_app_id`, `comment`, `responsible_id`)
 									VALUES (' . $amount . ', "' . $currency . '", ' . $type_id . ',' . $contractor_id . ',' . $department_id . ', ' . $percent . ', ' . $percent_amount . ', "' . $percent_currency . '", "' . $percent_type_id . '", ' . $next_payment_date . ', "' . $percent_period . '", ' . $end_date . ', ' . $percent_app_id . ', ' . $credit_app_id . ', "' . $comment . '", ' . $responsible_id . ')');
		$query->getResult();
		if ($db->affectedRows() == 1) {
			$inserted_id = $db->insertID();
			if ((($atts['department_id'] == 1 || $atts['department_id'] == 8) && (int)$atts['project_id'] > 0) || !empty($atts['urgently'])) {//виробництво або послуга
//				echo 'ppppp';
				if (!empty($atts['project_id'])) {
					$message_project = Projects::get_project($atts['project_id']);
//					echo 'ssss';
					Telegram::send_message('@Rudia1, Отримано нову актуальну заявку на ' . addslashes($atts['product']) . ' на суму ' . $atts['amount'] . ' ' . $atts['currency'] . ' по проекту ' . $message_project->name . '. Перейдіть на сайт, щоб одобрити' . " \r\n" . base_url('application/department', 'https'));
				} else {
//					echo 'lllll';
					Telegram::send_message('@Rudia1, Отримано нову актуальну заявку на ' . addslashes($atts['product']) . ' на суму ' . $atts['amount'] . ' ' . $atts['currency'] . '. Перейдіть на сайт, щоб одобрити' . " \r\n" . base_url('application/department', 'https'));
				}
			}

			if (!empty($atts['repeat'])) {
				$repeat_end_date = !empty($atts['repeat_end_date']) ? strtotime($atts['repeat_end_date']) : 0;
				$query = $db->query('INSERT INTO `fin_application_repeat`( `app_id`, `start_date`, `period`, `end_date`) VALUES ("' . $inserted_id . '", "' . time() . '", "' . $atts['repeat_type'] . '", "' . $repeat_end_date . '")');
				$query->getResult();
			}
			return ['status' => 'ok', 'id' => $inserted_id];
		} else {
			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
		}
	}

	public static function delete($credit_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('DELETE FROM `fin_credits` WHERE id=' . $credit_id);

		$query->getResult();
		return ['status' => 'ok'];
	}

	public
	static function select_query($join_keys = [])
	{
		$session = Config\Services::session();
		$account_id = $session->get('account_id');
		$params = [
			'table' => ['fc' => 'fin_credits'],
			'columns' => [
				'id', 'amount', 'currency', 'contractor_id', 'percent', 'percent_amount', 'percent_currency', 'next_payment_date', 'percent_period', 'end_date', 'percent_app_id', 'credit_app_id', 'comment', 'responsible_id'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
				'fc.account_id =' . $account_id
			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_contractors', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fco' => 'fin_contractors'],
				'main_table_key' => 'contractor_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'contractor_name',
				]
			];
		}

		if (in_array('fin_types', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['ft' => 'fin_types'],
				'main_table_key' => 'type_id',
				'columns' => ['type'],
				'columns_with_alias' => [
				]
			];
		}

		if (in_array('fin_users', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fu' => 'fin_users'],
				'main_table_key' => 'responsible_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'responsible_name',
					'surname' => 'responsible_surname',
				]
			];
		}

		if (in_array('fin_departments', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fd' => 'fin_departments'],
				'main_table_key' => 'department_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'department',
				]
			];
		}
		return $params;
	}

}
