<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;
use DatePeriod;
use DateInterval;
use DateTime;


class Salary extends BaseController
{
	public function index()
	{

	}

	public function hours($month_year = 0, $user_id = null) //m.Y
	{
		if (!empty($this->user_id)) {
			//для дат
			$some_date_of_month = !empty($month_year) ? strtotime('1.' . $month_year) : time();
			$first_day_of_this_month = strtotime('first day of this month 00:00:00', $some_date_of_month);
			$last_day_of_this_month = date('m.Y', time()) == date('m.Y', $some_date_of_month)
				? time()
				: strtotime('last day of this month 23:59:59', $some_date_of_month);

			if (empty($user_id)) {
				$workers = Models\Position::get_department_users($this->user_id, true);
			} else {
				$workers = [(array)Models\User::getUser($user_id)['user']];
			}


//			var_dump($workers);die();

			return $this->view('salary/hours',
				[
//					'user' => $this->user,
//					'access' => $this->access,
					'first_day_of_month' => $first_day_of_this_month,
					'last_day_of_month' => $last_day_of_this_month,
					'months' => Models\Salary::get_months_from_start(),
					'weeks' => Models\Salary::get_weeks(),
					'month_year' => date('m.Y', $some_date_of_month),
					'workers' => $workers,
					'user_id' => $user_id,
					'month_hours' => Models\Salary::get_month_hours_array($workers, date('m.Y', $some_date_of_month)),
					'css' => ['salary'],
					'js' => ['salary']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function pay($month_year = null, $user_id = 0)
	{
		if (!empty($this->user_id)) {
			$user_id = !empty($user_id) ? $user_id : $this->user_id;
			$month_year = !empty($month_year) ? $month_year : date('m.Y');
//			$accruals = Models\Accruals::get_month_accruals(Models\Position::get_department_users($user_id), $month_year);
			$accruals = Models\Accruals::get_month_accruals(Models\User::get_users(['where' => ['account_id = ' . Models\Account::get_current_account_id()]]), $month_year);

			$accruals_array = [];
			if (!empty($accruals)) {
				foreach ($accruals as $accrual) {
					if (empty($accruals_array[$accrual['user_id']][$accrual['type']])) {
						$accruals_array[$accrual['user_id']][$accrual['type']] = 0;
					}
					$amount = $accrual['amount'];
					if ($accrual['currency'] != 'UAH') {
						$currency_rate = Models\CurrencyRate::get_exchange_rates($accrual['currency']);
						$amount = round($accrual['amount'] * $currency_rate['buy'], 2);
					}

					$accruals_array[$accrual['user_id']][$accrual['type']] += (float)$amount;
				}
			}

			return $this->view('salary/pay',
				[
					'title' => 'Виплати зарплат',
					'user' => $this->user,
					'user_id' => $user_id,
					'access' => $this->access,
					'accruals' => $accruals_array,
					'month_year' => $month_year,
					'months' => Models\Salary::get_months_from_start(),
					'workers' => Models\User::get_users(['where' => ['account_id = ' . Models\Account::get_current_account_id()]]),
					'css' => ['salary'],
					'js' => ['salary']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}


	public function change_hours_ajax()
	{
		if (!empty($_POST)) {
			$date = $_POST['date'];
			$user_id = $_POST['user_id'];
			$hours = $_POST['hours'];
			echo json_encode(Models\Salary::change_hours($user_id, strtotime($date), $hours));
		}
	}

	public function set_hours()
	{
		Models\Salary::set_hours();
	}

	public function payroll()
	{
		Models\Accruals::payroll_from_start();
	}
}
