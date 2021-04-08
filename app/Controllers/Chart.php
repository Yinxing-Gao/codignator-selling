<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Chart extends BaseController
{
	public function index()
	{

	}

	public function operations($is_planned = false)
	{

		$date_from = !empty($date_from) ? $date_from : strtotime("first day of this month 00:00:00");
		$date_to = !empty($date_to) ? $date_to : strtotime("last day of this month 23:59:59");

		$operations = Models\Operation::get_operations(['where' => [
			'(fo.operation_type_id = 1 OR fo.operation_type_id = 2)',
			$is_planned ? 'time_type = "plan"' : 'time_type = "real"',
			$is_planned ? 'fo.planned_on >= ' . $date_from : 'fo.date >= ' . $date_from,
			$is_planned ? 'fo.planned_on <= ' . $date_to : 'fo.date <= ' . $date_from,
			'fo.account_id = ' . $this->account_id
		]]);
		return json_encode(Models\Operation::get_dates_and_amounts($operations, 'month'));
	}

	public function accruals($is_planned = false)
	{

		$date_from = !empty($date_from) ? $date_from : strtotime("first day of this month 00:00:00");
		$date_to = !empty($date_to) ? $date_to : strtotime("last day of this month 23:59:59");

		$accruals = Models\Operation::get_operations(['where' => [
			'(fa.accrual_type = "debit" OR fa.accrual_type = "credit")',
			$is_planned ? 'time_type = "plan"' : 'time_type = "real"',
			$is_planned ? 'fa.planned_on >= ' . $date_from : 'fa.date >= ' . $date_from,
			$is_planned ? 'fa.planned_on <= ' . $date_to : 'fa.date <= ' . $date_from,
			'fo.account_id = ' . $this->account_id
		]]);
		return json_encode(Models\Accruals::get_dates_and_amounts($accruals, 'month'));
	}

	public function structure($is_planned = false)
	{
		$positions = Models\Position::get_positions(['where' => [
			'account_id = ' . Models\Account::get_current_account_id()
		]], [], ['fin_user_to_position']);
//		Models\Dev::var_dump($positions);

		$position_rows = [[
			'0',
			'FINEKO',
			'FINEKO'
		]
		];
		if (!empty($positions)) {
			foreach ($positions as $position) {
				if (!empty($position['users'])) {
					foreach ($position['users'] as $user) {
						$position_rows[] = [
							(object)[
//								'v' => $position['id'] . '_' . $user['id'],
								'v' => $position['id'],
								'f' => $user['name'] . ' ' . $user['surname'] . '<div style="font-style:italic;color: blue;">' . $position['name'] . '</div>'
							],
							$position['subordination'] > 0 ? $position['subordination'] : '',
							$position['name']
						];
					}
				}
			}
		}
		echo \GuzzleHttp\json_encode($position_rows);
		die();
//		Models\Dev::var_dump($position_rows);


//		return [
//			[
//				[
//					'v' => 'Mike',
//					'f' => 'Mike<div style="color=>red; font-style=>italic">President</div>'
//				],
//				'', //верхній
//				'The President'
//			],
//			[
//				[
//					'v' => 'Jim',
//					'f' => 'Jim<div style="color=>red; font-style=>italic">Vice President</div>'
//				],
//				'Mike',
//				'VP'
//			],
//			['Alice', 'Mike', ''],
//			['Bob', 'Jim', 'Bob Sponge'],
//			['Carol', 'Bob', '']
//		];
	}
}
