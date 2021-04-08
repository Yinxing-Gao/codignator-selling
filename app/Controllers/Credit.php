<?php namespace App\Controllers;

use App\Models;
use App\Models\Account;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Credit extends BaseController
{
	protected $types = [
		'1' => 'готівка',
		'2' => 'на ТОВ',
		"3" => 'на ФОП',
		"4" => 'ТОВ/готівка/невідомо'
	];

	public function index()
	{
		if (!empty($this->user_id)) {
			$credits = Models\Credit::get_credits();
			$total = 0;
			$total_percent = 0;
			if (!empty($credits)) {
				foreach ($credits as &$credit) {
					if ($credit['currency'] != 'UAH') {
						$currency_rate = Models\CurrencyRate::get_exchange_rates($credit['currency']);
						$credit['total'] = $credit['amount'] * $currency_rate['buy'];
					} else {
						$credit['total'] = $credit['amount'];
					}
					$total += $credit['total'];
					if ($credit['percent_currency'] != 'UAH') {
						$currency_rate = Models\CurrencyRate::get_exchange_rates($credit['percent_currency']);
						$credit['percent_total'] = $credit['percent_amount'] * $currency_rate['buy'];
					} else {
						$credit['percent_total'] = $credit['percent_amount'];
					}
					$total_percent += $credit['percent_total'];
				}
			}
			return $this->view('credit/list',
				[
					'credits' => $credits,
					'total' => $total,
					'total_percent' => $total_percent,
					'currencies_names' => Models\CurrencyRate::get_currencies_names(),
					'css' => ['table','credits'],
					'js' => ['credits']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function add()
	{
		if (!empty($this->user_id)) {
			return $this->view('credit/add',
				[
					'action' => 'add',
					'contractors' => Models\Contractors::get_contractors(['where' => ['fc.account_id = ' . Account::get_current_account_id()]]),
//					'applications' => Models\Applications::get_department_apps($this->user_id),
					'departments' => Models\Departments::get_departments(),
					'types' => $this->types,
					'css' => ['select2.min', 'credits'],
					'js' => ['select2.min', 'credits']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function edit($credit_id)
	{
		if (!empty($this->user_id)) {
			if (!empty($credit_id)) {

				return view('credit_add',
					[
						'user' => $this->user,
						'access' => $this->access,
						'action' => 'edit',
						'credit' => Models\Credit::get_credit($credit_id),
						'contractors' => Models\Contractors::get_contractors(['where' => ['fc.account_id = ' . Account::get_current_account_id()]]),
						'applications' => Models\Applications::get_department_apps($this->user_id),
						'departments' => Models\Departments::get_departments(),
						'types' => $this->types,
						'css' => ['select2.min', 'credits'],
						'js' => ['select2.min', 'credits']
					]
				);
			} else {
				header('Location: ' . base_url() . 'credit');
				exit;
			}
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function add_ajax()
	{

		if (!empty($_POST)) {
			$atts = $_POST;
			echo json_encode(Models\Credit::add($atts));
		}
	}

	public
	function edit_ajax()
	{

		if (!empty($_POST)) {
			$atts = $_POST;
			echo json_encode(Models\Credit::edit($atts));
		}
	}

	public
	function delete_ajax($credit_id)
	{
		echo json_encode(Models\Credit::delete($credit_id));
	}
}
