<?php


namespace App\Models;

use CodeIgniter\Model;
use Config;

class Accruals
{

	public static function get_accruals($params = [], $join = [])
	{
		$query_params = self::select_query(array_merge(['fin_contractors', 'fin_departments', 'fin_articles'], $join));

		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

//	public static function get_accruals()
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT * FROM `fin_accruals`');
//		return $query->getResultArray();
//	}

	public static function get_project_accruals($project_id)
	{
		if (!empty($project_id)) {
			$db = Config\Database::connect();
			$query = $db->query('SELECT fa.`id`, fa.`amount`, fa.`currency`, fa.`accrual_type`, fa.`type_id`, fa.`article_id`, fa.`contractor_id`, fc.name as contractor_name, fa.`comment`, fa.`date`, fa.`period_type`, fa.`type`, fa.`hours_id`, fa.`project_id`, fa.`department_id`, fp.name as project_name
									FROM `fin_accruals` fa
                                    LEFT JOIN fin_contractors fc ON fc.id = fa.contractor_id
                                    LEFT JOIN fin_projects fp ON fp.id = fa.`project_id`
									WHERE project_id =' . $project_id);
			return $query->getResultArray();
		}
		return [];
	}

	public static function add($atts)
	{
		$params = [
			'amount' => !empty($atts['amount']) ? $atts['amount'] : 0,
			'currency' => !empty($atts['currency']) ? htmlspecialchars(trim($atts['currency'])) : 0,
			'accrual_type' => !empty($atts['accrual_type']) ? htmlspecialchars(trim($atts['accrual_type'])) : 'credit', //expense
			'type_id' => !empty($atts['type_id']) ? $atts['type_id'] : 1,
			'article_id' => !empty($atts['article_id']) ? $atts['article_id'] : 1,
			'contractor_id' => !empty($atts['contractor_id']) ? $atts['contractor_id'] : 0,
			'comment' => !empty($atts['comment']) ? htmlspecialchars(trim($atts['comment'])) : '',
			'date' => !empty($atts['date']) ? $atts['date'] : time(),
			'period_type' => !empty($atts['period_type']) ? htmlspecialchars(trim($atts['period_type'])) : '',
			'type' => !empty($atts['type']) ? $atts['type'] : 'accrual',
			'hours_id' => !empty($atts['hours_id']) ? $atts['hours_id'] : 0,
			'project_id' => !empty($atts['project_id']) ? $atts['project_id'] : 0,
			'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
			"repeat_period" => !empty($atts['repeat_period']) ? $atts['repeat_period'] : 'month',
			"repeat_start_date" => !empty($atts['repeat_start_date']) ? $atts['repeat_start_date'] : null,
			"repeat_end_date" => !empty($atts["repeat_end_date"]) ? $atts["repeat_end_date"] : null,
			"is_planned" => !empty($atts['is_planned']) ? $atts['is_planned'] : 0,
			"is_template" => !empty($atts['is_template']) ? $atts['is_template'] : 0
		];

		return DBHelp::insert('fin_accruals', $params);
//		$db = Config\Database::connect();
//		$query = $db->query('INSERT INTO `fin_accruals`(`amount`, `currency`, `accrual_type`, `type_id`, `article_id`, `contractor_id`, `comment`, `date`, `period_type`, `type`, `hours_id`, `project_id`, `department_id`)
// 				VALUES (' . $amount . ', "' . $currency . '", "' . $accrual_type . '", ' . $type_id . ', ' . $article_id . ', ' . $contractor_id . ', "' . $comment . '", ' . $date . ', "' . $period_type . '", "' . $type . '", ' . $hours_id . ', ' . $project_id . ', ' . $department_id . ' )');
//		$query->getResult();
//		if ($db->affectedRows() == 1) {
//			return ['status' => 'ok', 'id' => $db->insertID()];
//		} else {
//			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
//		}
	}

	public static function get_project_accrual($project_id, $article_id)
	{
		if (!empty($project_id) && !empty($article_id)) {
			$db = Config\Database::connect();
			$query = $db->query('SELECT `id`, `amount`, `currency`,`comment`, `date`, `period_type`, `type`, `hours_id` FROM `fin_accruals` WHERE project_id = ' . $project_id . ' AND article_id = ' . $article_id);
			return $query->getFirstRow();
		}
		return [];
	}

	public static function set_project_accrual($atts)
	{

		$project_id = $atts['project_id'];
		$article_id = $atts['article_id'];
		$accrual = self::get_project_accrual($project_id, $article_id);
		if (!empty($accrual)) {
			$amount = !empty($atts['amount']) ? $atts['amount'] : 0;
			$currency = !empty($atts['currency']) ? htmlspecialchars(trim($atts['currency'])) : 0;
			$db = Config\Database::connect();
			$query = $db->query('UPDATE `fin_accruals` SET `amount`= "' . $amount . '", `currency`= "' . $currency . '" WHERE id = ' . $accrual->id);
			$query->getResult();
		} else {
			self::add($atts);
		}
	}

	public static function payroll($user_id, $date, $hours, $hours_row_id)
	{
		$professions = Position::get_professions_with_fields($user_id);
		$accrual = self::get_accrual($hours_row_id);
		if (!empty($professions)) {
			foreach ($professions as $profession) {
				$salary = (float)$profession['salary_amount'];
				$currency = $profession['salary_currency'];
				$salary_in_UAH = $salary; // якщо буде мінятися, то тут дописати, щоб тягнулося зп з таблиці salary по даті з останньої зміни
				if ($currency != 'UAH') {
					$currency_rate = CurrencyRate::get_exchange_rates($currency);
					$salary_in_UAH = $salary * $currency_rate['buy'];
				}
				$work_days_amount = self::get_month_work_days_amount($date, explode(',', $profession['work_days']));
				$hour_amount = (float)$salary_in_UAH / (int)$work_days_amount / 8;
				$day_salary = round((float)$hour_amount * $hours, 2);
				$db = Config\Database::connect();
				if (empty($accrual)) {
					$query = $db->query('INSERT INTO `fin_accruals`( `amount`, `currency`, `user_id`, `comment`, `period_type`, `type`, `date`, `hours_id`)
										VALUES ("' . $day_salary . '", "' . $currency . '", ' . $user_id . ', "Нарахування зп за день", "day", "salary", ' . $date . ', ' . $hours_row_id . ')');
				} else {
					$query = $db->query('UPDATE `fin_accruals` SET `amount`="' . $day_salary . '",`currency`="' . $currency . '", `comment`="Нарахування зп за день", `period_type`="day",`type`="salary"  WHERE `id`=' . $accrual->id);
				}
				$query->getResult();
			}
		}
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

	//отримати к-сть робочих днів в місяці
	private static function get_month_work_days_amount($some_date_of_month, $work_days)
	{
		$first_day_of_this_month = strtotime('first day of this month 00:00:00', $some_date_of_month);
		$last_day_of_this_month = strtotime('last day of this month 23:59:59', $some_date_of_month);
		$counter = 0;
		for ($time = $first_day_of_this_month; $time < $last_day_of_this_month; $time += 60 * 60 * 24) {
			if (in_array(date('N', $time), $work_days)) {
				$counter++;
			}
		}
		return $counter;
	}

	static function get_month_accruals($user_ids, $month_year)
	{
		if (!empty($user_ids)) {
			$month_year = !empty($month_year) ? strtotime('1.' . $month_year) : time();
			$first_day_of_this_month = strtotime('first day of this month 00:00:00', $month_year);
			$last_day_of_this_month = date('m.Y', time()) == date('m.Y', $month_year)
				? time()
				: strtotime('last day of this month 23:59:59', $month_year);

//			$db = Config\Database::connect();
			$query_row = $user_ids;
			if (is_array($user_ids)) {
				$query_row = '';
				foreach ($user_ids as $user_id_row) {
					$query_row .= $user_id_row['id'] . ', ';
				}
			}

			return self::get_accruals(['where' => [
//				"user_id IN (" . substr($query_row, 0, -2) . ")",
				"`date` >= " . $first_day_of_this_month,
				"`date` <= " . $last_day_of_this_month
			]]);
//
//			$query = $db->query("SELECT `id`, `amount`, `user_id`, `currency`, `comment`, `period_type`, date, `type` FROM `fin_accruals` WHERE user_id IN (" . substr($query_row, 0, -2) . ") AND `date` >= " . $first_day_of_this_month . ' AND `date` <= ' . $last_day_of_this_month);
//			return $query->getResultArray();
		}
		return [];
	}

	public static function payroll_from_start()
	{
		$hours = Salary::get_all_hours();
		if (!empty($hours)) {
			foreach ($hours as $hour_row) {

				self::payroll($hour_row['user_id'], $hour_row['date'], $hour_row['hours'], $hour_row['id']);
			}
		}
	}

	public static function get_accrual($hours_row_id)
	{
		if (!empty($hours_row_id)) {
			$db = Config\Database::connect();
			$query = $db->query('SELECT `id`, `amount`, `currency`, `user_id`, `comment`, `date`, `period_type`, `type`, `hours_id` FROM `fin_accruals` WHERE hours_id = "' . $hours_row_id . '"');
			return $query->getFirstRow();
		}
		return [];
	}

	public
	static function select_query($join_keys = [])
	{
		$session = Config\Services::session();
		$account_id = $session->get('account_id');

		$params = [
			'table' => ['fa' => 'fin_accruals'],
			'columns' => [
				'id',
				'month_year',
				'amount',
				'currency',
				'accrual_type',
				'type_id',
				'article_id',
				'contractor_id',
				'comment',
				'date',
				'date_template',
				'create_date',
				'period_type',
				'type',
				'hours_id',
				'project_id',
				'contract_id',
				'department_id',
				'account_id',
				'is_planned',
				'is_template',
				'repeat_period',
				'repeat_start_date',
				'repeat_end_date',
				'compare_prev'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
				'fa.account_id = ' . $account_id
			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_contractors', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fc' => 'fin_contractors'],
				'main_table_key' => 'contractor_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'contractor_name',
				]
			];
		}

		if (in_array('fin_projects', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fp' => 'fin_projects'],
				'main_table_key' => 'project_id',
				'columns' => [],
				'columns_with_alias' => ['name' => 'project_name']
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

		if (in_array('fin_articles', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['far' => 'fin_articles'],
				'main_table_key' => 'article_id',
				'columns' => [],
				'columns_with_alias' => ['name' => 'article_name']
			];
		}

		return $params;
	}

}
