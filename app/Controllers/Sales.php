<?php namespace App\Controllers;

use App\Models;
use App\Models\Account;
use App\Models\CurrencyRate;
use App\Models\DBHelp;
use App\Models\Marketing;
use  App\Models\Projects;
use  App\Models\Operation;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Sales extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			$articles_tree = Models\Articles::get_articles_tree();

			$current_clients = Models\Marketing::get_leads(['where' => [
				'fl.status = "contract"',
				'fl.account_id = ' . Account::get_current_account_id(),
			]]);
			if (!empty($current_clients)) {
				foreach ($current_clients as &$current_client) {
					$current_client['payed_amount'] = 0;
					if (!empty($current_client['operations'])) {
						foreach ($current_client['operations'] as $operation) {
							if ($operation['currency'] != $current_client['currency']) {
								$app['payed_amount'] = +$operation['amount'];
							}
						}
					}

					$current_client['amount_left'] = 0;
					if (!empty($current_client['planned_operations'])) {
						foreach ($current_client['planned_operations'] as $planned_operation) {
							if ($planned_operation['currency'] != $current_client['currency']) {
								$app['amount_left'] = +$planned_operation['amount'];
							}
						}
					}

				}
			}

			return $this->view('sales/clients',
//			return view('op/info',
				[
					'current_clients' => $current_clients,
					'income_wallets' => Models\Wallets::get_wallets(['where' => [
						'fw.account_id = ' . Account::get_current_account_id(),
						'fw.for_income = 1'
					]]),
					'planned_operations' => Operation::get_plan_operations_by_projects(),
					'currencies' => [
						'UAH' => 'грн.',
						'USD' => 'дол.',
						"RUR" => 'руб.',
						"EUR" => 'євро'
					],
					'op_style' => [
						1 => ["green", "green"],
						2 => ["red", "red"],
						3 => ["blue", "blue"],
						4 => ["orange", "orange"],
						5 => ["red", "#9797e8"],
						6 => ["green", "#9797e8"],
					],
					'expense_list' => !empty($articles_tree) ? $articles_tree['expense'] : [],
					'income_list' => !empty($articles_tree) ? $articles_tree['income'] : [],
					'css' => ['table', 'sales'],
					'js' => ['jquery-ui.min', 'sales']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function products()
	{
		if (!empty($this->user_id)) {
			return $this->view('sales/products',
				[
					'products' => Models\Products::get_products([
						'where' => [
							'fpr.account_id = ' . Models\Account::get_current_account_id()
						]],
						['fin_articles', 'fin_departments'],
						['fin_question_groups']
					),
					'departments' => Models\Departments::get_departments(['where' => ['account_id = ' . $this->account_id]]),
					'types' => [
						'service' => 'Послуга',
						'product' => 'Продукт'
					],
					'payment_types' => [
						'pre' => 'Предоплата',
						'post' => 'Постоплата',
						'pre & post' => 'Предоплата і постоплата'
					],
					'has_parts_options' => [
						'1' => 'Неділима деталь',
						'2' => 'Має частини'
					],
					'css' => ['table', 'sales'],
					'js' => ['sales']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function questions()
	{
		if (!empty($this->user_id)) {

			return $this->view('sales/questions',
				[
					'question_groups' => Models\Marketing::get_question_groups(),
					'css' => ['table', 'sales'],
					'js' => ['sales']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function operations_ajax($project_id)
	{
		$planned_operations = Operation::get_operations([
			'where' => [
				'fo.project_id =' . $project_id,
//				'is_planned = 1',
//				'is_template = 0',
				'time_type = "plan"',
				'operation_type_id = 1'
			]
		]);

		$done_operations = Operation::get_operations([
			'where' => [
				'fo.project_id =' . $project_id,
//				'is_planned = 0',
//				'is_template = 0',
				'time_type =  "real"',
				'operation_type_id = 1'
			]
		]);


		echo json_encode(view('operation/sales_project_operations',
			[
				'title' => 'Інформація про заявку',
				'currencies' => [
					'UAH' => 'грн.',
					'USD' => 'дол.',
					"RUR" => 'руб.',
					"EUR" => 'євро'
				],
				'op_style' => [
					1 => "green",
					2 => "red",
					3 => "blue",
					4 => "orange"
				],
				'locale' => $this->locale,
				'project' => Projects::get_project(['where' => ['fp.id =' . $project_id]])['result'],
//				'income_list' => !empty($articles_tree) ? $articles_tree['income'][0]['children'] : [],
				'planned_operations' => $planned_operations,
				'done_operations' => $done_operations
			]
		));
	}


	public function get_products_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		echo json_encode(Models\Products::search($query));
	}

	public function add_product_ajax()
	{
		echo json_encode(array_merge(['date' => date('d.m.Y H:i')], Models\Products::add($_POST)));
	}

	public function get_question_groups_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		$results = Models\Marketing::get_question_groups(['where' => [
			'fqg.name LIKE \'' . $query . '%\''
		]]);
		echo json_encode($results);
	}

	/**
	 * Get all storage names related to current account
	 */
	public function get_account_storage_names_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		$results = Models\Storage::get_account_storage_names($query);
		echo json_encode($results);
	}
}
