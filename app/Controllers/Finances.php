<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Finances extends BaseController
{
	public function fixed_costs()
	{
		if (!empty($this->user_id)) {
			return $this->view('finances/fixed costs',
				[
					'budgets' => Models\Budgets::get_budgets(['where' => [
						'fb.time_type = "plan"',
						'fb.account_id = ' . Models\Account::get_current_account_id()
					]], ['fin_articles'], ['fin_operations']),
					'css' => ['budgets', 'table'],
					'js' => ['budgets']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function model()
	{
		if (!empty($this->user_id)) {
			return $this->view('finances/model',
				[
					'budgets' => Models\Budgets::get_budgets(['where' => [
						'fb.time_type = "plan"',
						'fb.account_id = ' . Models\Account::get_current_account_id()
					]], ['fin_articles'], ['fin_operations']),
					'css' => ['budgets', 'table'],
					'js' => ['budgets']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function salary()
	{
		if (!empty($this->user_id)) {
			return $this->view('finances/salary',
				[
					'css' => ['contract'],
					'js' => ['contract']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function add_budget_ajax()
	{
		echo json_encode(array_merge(['date' => date('d.m.Y H:i')], Models\Budgets::add([
			'name' => !empty($_POST['name']) ? $_POST['name'] : '',
			'article_id' => !empty($_POST['article_id']) ? $_POST['article_id'] : 0,
			'amount' => !empty($_POST['amount']) ? $_POST['amount'] : 0,
			'comment' => !empty($_POST['comment']) ? $_POST['comment'] : [],
		])));
	}
}

