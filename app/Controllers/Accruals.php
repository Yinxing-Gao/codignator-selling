<?php namespace App\Controllers;

use App\Models;
use App\Models\Articles;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Accruals extends BaseController
{
	public function index($month_year = null)
	{
		$month_year = !empty($month_year) ? $month_year : date('m.Y');

		$first_and_last_days_of_month = Models\DateHelper::get_first_and_last_days_of_month($month_year);

		if (!empty($this->user_id)) {
			//todo зарплатні нарахування додавати і видавати як єдину суму
			//todo для своїх юр.лиць придумати і додавати значки або скорочення, щоб не займали місця
			//todo зробити зверху фыльтри по юр. компаніях
			//todo виводити нарахування помісячно
			return $this->view('accruals/list',
				[
					'accruals' => Models\Accruals::get_accruals([
						'where' => [
							'date > ' . $first_and_last_days_of_month['first_day_timestamp'],
							'date < ' . $first_and_last_days_of_month['last_day_timestamp'],
						]
					]),
					'months' => Models\Salary::get_months_from_start(),
					'month_year' => $month_year,
					'params' => [
						'op_style' => [
							'debit' => "green",
							'credit' => "red",
						],
					],
					'css' => ['table', 'accruals'],
					'js'=> ['accruals']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function templates()
	{
		if (!empty($this->user_id)) {

			return $this->view('accruals/templates',
				[
					'accruals' => Models\Accruals::get_accruals([
						'where' => [
						'fa.is_template = 1'
						]
					]),
					'months' => Models\Salary::get_months_from_start(),
					'params' => [
						'op_style' => [
							'debit' => "green",
							'credit' => "red",
						],
					],
					'css' => ['accruals'],
					'js'=> ['accruals']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function project($project_id)
	{
		if (!empty($this->user_id)) {
			$currencies = [
				'UAH' => 'грн.',
				'USD' => 'дол.',
				"RUR" => 'руб.',
				"EUR" => 'євро'
			];

			return $this->view('accruals/project',
				[
					'user' => $this->user,
					'access' => $this->access,
					'articles_tree' => Articles:: get_articles_tree(null, 'project', 8),
					'params' => [
						'op_style' => [
							'debit' => "green",
							'credit' => "red",
						],
						'expense_list' => !empty($articles_tree) ? $articles_tree['expense'] : [],
						'income_list' => !empty($articles_tree) ? $articles_tree['income'][0]['children'] : [],
						'currencies' => $currencies,
						'accruals' => Models\Accruals::get_project_accruals($project_id)],
					'notifications' => $this->notifications,
					'contractors' => Models\Contractors::get_contractors_array(),
					'css' => ['accruals']
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}
}



