<?php namespace App\Controllers;

use App\Models;
use App\Models\Account;
use Config;

class Operation extends BaseController
{
	public function index($selected_wallet_id = null) //мої операції
	{
		if (!empty($this->user_id)) {

			if (empty($selected_wallet_id)) {
				$wallet = Models\Wallets::get_wallet(['where' => ['user_id = ' . $this->user_id]]);
				if (!empty($wallet)) {
					$selected_wallet_id = $wallet->id;
				} else {
					$new_wallet = Models\Wallets::add([
						"name" => "Готівка",
						"currency" => "UAH",
						"user_id" => $this->user_id,
						"type_id" => 1,
						"wallet_type" => "cash",
						"checkout" => 0,
						'is_default' => 1,
					]);
					$selected_wallet_id = $new_wallet['id'];
				}
				header('Location: ' . base_url() . 'operation/index/' . $selected_wallet_id);
				exit;
			}
			$operation_list = Models\Operation::get_user_operations($this->user_id, ['where' => ['time_type = "real"']]);

			//			$processed_operation_list = Models\Operation::preview_process($operation_list);
			$processed_operation_list = Models\Operation::get_operations_grouped_by_date($operation_list);

			$checkouts = Models\Wallets::get_checkouts_array(['where' => [
				'user_id = ' . $this->user_id
			]]);

			if (!empty($checkouts)) {
				foreach ($checkouts as $key => $checkout) {
					$processed_operation_list[$key] = $checkout;
				}
			}

			krsort($processed_operation_list, SORT_NUMERIC);

			$articles_tree = [];
			$departments_string = '';
			if (!empty($this->user->positions)) {
				foreach ($this->user->positions as $user_profession) {
					$departments_string .= $user_profession['department_id'] . ', ';
				}
			}
//			if (strlen($departments_string) > 0) {
//				$articles_tree = Models\Articles::get_articles_endpoints(['where' => [
//					'department_id IN (' . substr($departments_string, 0, -2) . ')'
//				]]);
//			}

			return $this->view('operation/this_user_list',
				[
					'operation_list' => $processed_operation_list,
					'currencies' => [
						'UAH' => 'грн',
						'USD' => 'дол',
						"RUR" => 'руб',
						"EUR" => 'євро'
					],
					'op_user' => $this->user,
					'not_this_user' => false,
					'op_style' => [
						1 => ["green", "green"],    // прихід
						2 => ["red", "red"],        // розхід
						4 => ["orange", "orange"],    // отримання кредиту
						5 => ["red", "blue"],    // переміщення в касу іншого користувача
						6 => ["green", "blue"],    // переміщення з каси іншого користувача
						7 => ["blue", "blue"],        // переміщення між своїми касами
					],
					'applications' => Models\Applications::get_apps_short(['where' => [
						'author_id = ' . $this->user_id
					]]),
					'articles' => !empty($articles_tree) ? $articles_tree : [],
//					'income_list' => !empty($articles_tree) ? $articles_tree['income'] : [],
					'user_wallets' => Models\Wallets::get_wallets(['where' => ['user_id = ' . $this->user_id]]),
					'selected_wallet_id' => $selected_wallet_id,
					'notifications' => $this->notifications,
					'js' => ['operation'],
					'css' => ['table', 'operation'],
//					'start_balance' => Models\Wallets::get_actual_user_balance_on_date($this->user_id)
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function user($user_id)
	{
		if (!empty($user_id)) {
			$operation_list = Models\Operation::get_user_operations($user_id);
			$processed_operation_list = [];
			foreach ($operation_list as $operation) {
				$processed_operation = [];
				$processed_operation2 = [];
				$processed_operation['id'] = $operation['id'];
				$processed_operation['operation_type_id'] = $operation['operation_type_id'];
				$processed_operation['app_id'] = $operation['app_id'];
				$processed_operation['project_id'] = $operation['project_id'];
				$processed_operation['project_name'] = $operation['project_name'];
				$processed_operation['rate'] = $operation['rate'];
				$processed_operation['comment'] = $operation['comment'];
				$processed_operation['date'] = $operation['date'];
				$processed_operation['article_id'] = $operation['article_id'];
				if ($operation['operation_type_id'] == 1 || $operation['operation_type_id'] == 4 || $operation['operation_type_id'] == 6) {
					$processed_operation['amount'] = $operation['amount2'];
					$processed_operation['currency'] = $operation['currency2'];
					$processed_operation['wallet_id'] = $operation['wallet2_id'];
					$processed_operation['wallet_checkout'] = $operation['wallet_2_checkout'];
					$processed_operation['contractor_id'] = $operation['contractor1_id'];
					$processed_operation['contractor2_id'] = $operation['contractor2_id'];
					$processed_operation['contractor_name'] = Models\Contractors::get_contractor($processed_operation['contractor_id'])->name;
					$processed_operation['contractor2_name'] = Models\Contractors::get_contractor($processed_operation['contractor2_id'])->name;
					$processed_operation_list[$processed_operation['date']]['operation'][] = $processed_operation;
				} elseif ($operation['operation_type_id'] == 2 || $operation['operation_type_id'] == 5) {
					$processed_operation['amount'] = $operation['amount1'];
					$processed_operation['currency'] = $operation['currency1'];
					$processed_operation['wallet_id'] = $operation['wallet1_id'];
					$processed_operation['wallet_checkout'] = $operation['wallet_1_checkout'];
					$processed_operation['contractor_id'] = $operation['contractor1_id'];
					$processed_operation['contractor2_id'] = $operation['contractor2_id'];
					$processed_operation['contractor_name'] = Models\Contractors::get_contractor($processed_operation['contractor_id'])->name;
					$processed_operation['contractor2_name'] = Models\Contractors::get_contractor($processed_operation['contractor2_id'])->name;
					$processed_operation_list[$processed_operation['date']]['operation'][] = $processed_operation;
				} elseif ($operation['operation_type_id'] == 3) {
					$processed_operation2 = $processed_operation;
					$processed_operation['amount'] = $operation['amount1'];
					$processed_operation['currency'] = $operation['currency1'];
					$processed_operation['wallet_id'] = $operation['wallet1_id'];
					$processed_operation['wallet_checkout'] = $operation['wallet_1_checkout'];
					$processed_operation['contractor_id'] = $operation['contractor1_id'];
					$processed_operation['contractor2_id'] = $operation['contractor2_id'];
					$processed_operation['contractor_name'] = Models\Contractors::get_contractor($processed_operation['contractor_id'])->name;
					$processed_operation['contractor2_name'] = Models\Contractors::get_contractor($processed_operation['contractor2_id'])->name;

					$processed_operation2['amount'] = $operation['amount2'];
					$processed_operation2['currency'] = $operation['currency2'];
					$processed_operation2['wallet_id'] = $operation['wallet2_id'];
					$processed_operation2['wallet_checkout'] = $operation['wallet_2_checkout'];
					$processed_operation2['contractor_id'] = $operation['contractor1_id'];
					$processed_operation2['contractor2_id'] = $operation['contractor2_id'];
					$processed_operation2['contractor_name'] = Models\Contractors::get_contractor($processed_operation['contractor_id'])->name;
					$processed_operation2['contractor2_name'] = Models\Contractors::get_contractor($processed_operation['contractor2_id'])->name;

					$processed_operation_list[$processed_operation['date']]['operation'][] = $processed_operation;
					$processed_operation_list[$processed_operation2['date']]['operation'][] = $processed_operation2;
				}
			}
			$checkouts = Models\Wallets::get_checkouts_array(['where' => [
				'user_id = ' . $this->user_id
			]]);

			if (!empty($checkouts)) {
				foreach ($checkouts as $key => $checkout) {
					$processed_operation_list[$key] = $checkout;
				}
			}

			krsort($processed_operation_list, SORT_NUMERIC);

			$articles_tree = Models\Articles::get_articles_tree();
			return view('operation_user_list',
				[
					'operation_list' => $processed_operation_list,
					'currencies' => [
						'UAH' => 'грн.',
						'USD' => 'дол.',
						"RUR" => 'руб.',
						"EUR" => 'євро'
					],
					'not_this_user' => true,
					'user' => $this->user,
					'op_user' => Models\User::getUser($user_id)['user'],
					'access' => $this->access,
					'user_wallets' => Models\Wallets::get_user_wallets($user_id),
					'op_style' => [
						1 => ["green", "green"],
						2 => ["red", "red"],
						3 => ["blue", "blue"],
						4 => ["orange", "orange"],
						5 => ["red", "#9797e8"],
						6 => ["green", "#9797e8"],
					],
					'css' => ['operation'],
					'js' => ['operation'],
					'notifications' => $this->notifications,
					'applications' => Models\Applications::get_department_apps($this->user_id),
//					'expense_list' => Models\Articles::get_articles(),
					'expense_list' => !empty($articles_tree) ? $articles_tree['expense'] : [],
					'income_list' => !empty($articles_tree) ? $articles_tree['income'] : []
//					'contractors' => Models\Contractors::get_contractors_array(),
//					'start_balance' => Models\Wallets::get_actual_user_balance_on_date($user_id)
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function company()
	{
		if (!empty($this->user_id)) {
			$user = Models\User::getUser($this->user_id)['user']; //дописати захисти

			$operation_list = Models\Operation::get_operations();

			$currencies = [
				'UAH' => 'грн.',
				'USD' => 'дол.',
				"RUR" => 'руб.',
				"EUR" => 'євро'
			];

			$style = [
				1 => "green",
				2 => "red",
				3 => "blue",
				4 => "orange"
			];

			return view('operation_list',
				[
					'operation_list' => $operation_list,
					'currencies' => $currencies,
					'user' => $this->user,
					'access' => $this->access,
					'op_style' => $style,
					'notifications' => $this->notifications,
					'contractors' => Models\Contractors::get_contractors_array()
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function plan($user_id = null) //заплановані
	{
		if (!empty($user_id)) { //користувача

			$operation_list = Models\Operation::get_user_operations($user_id, ['where' => ['time_type = "plan"']]);

//			$processed_operation_list = Models\Operation::preview_process($operation_list);
			$processed_operation_list = Models\Operation::get_operations_grouped_by_date($operation_list, true);

			$checkouts = Models\Wallets::get_checkouts_array(['where' => [
				'user_id = ' . $this->user_id
			]]);

			if (!empty($checkouts)) {
				foreach ($checkouts as $key => $checkout) {
					$processed_operation_list[$key] = $checkout;
				}
			}

			krsort($processed_operation_list, SORT_NUMERIC);

			$articles_tree = Models\Articles::get_articles_tree();
			return view('operation_plan_user_list',
				[
					'operation_list' => $processed_operation_list,
					'currencies' => [
						'UAH' => 'грн.',
						'USD' => 'дол.',
						"RUR" => 'руб.',
						"EUR" => 'євро'
					],
					'not_this_user' => true,
					'user' => $this->user,
					'op_user' => Models\User::getUser($user_id)['user'],
					'access' => $this->access,
					'user_wallets' => Models\Wallets::get_wallets(['where' => [
						'user_id = ' . $user_id]]),
					'op_style' => [
						1 => ["green", "green"],
						2 => ["red", "red"],
						4 => ["orange", "orange"],
						5 => ["red", "blue"],
						6 => ["green", "blue"],
						7 => ["blue", "blue"],
					],
					'css' => ['table', 'operation'],
					'js' => ['operation'],
					'notifications' => $this->notifications,
					'applications' => Models\Applications::get_department_apps($this->user_id),
//					'expense_list' => Models\Articles::get_articles(),
					'expense_list' => !empty($articles_tree) ? $articles_tree['expense'] : [],
					'income_list' => !empty($articles_tree) ? $articles_tree['income'] : []
//					'contractors' => Models\Contractors::get_contractors_array(),
//					'start_balance' => Models\Wallets::get_actual_user_balance_on_date($user_id)
				]
			);

		} elseif (!empty($this->user_id)) { // мої
			$operation_list = Models\Operation::get_user_operations($this->user_id, ['where' => ['time_type = "plan"']]);
//			$processed_operation_list = Models\Operation::preview_process($operation_list, true);
			$processed_operation_list = Models\Operation::get_operations_grouped_by_date($operation_list, true);

			$checkouts = Models\Wallets::count_plan_checkouts($this->user_id);
//Models\Dev::var_dump($checkouts);
			if (!empty($checkouts)) {
				foreach ($checkouts as $checkout) {
					$processed_operation_list[$checkout['date']]['checkout'][] = $checkout;
				}
			}

			$user_wallets = Models\Wallets::get_wallets(['where' => [
				'user_id = ' . $this->user_id,
				'is_shown = 1'
			]]);

			if (!empty($user_wallets)) {
				foreach ($user_wallets as $user_wallet) {
					$processed_operation_list[strtotime('yesterday 23:59:59')]['checkout'][] = [
						"wallet_id" => $user_wallet['id'],
						"amount" => $user_wallet['checkout'],
						"date" => 'today',
					];
				}
			}

			ksort($processed_operation_list, SORT_NUMERIC);

			$articles_tree = Models\Articles::get_articles_tree();
			return $this->view('operation/plan/this_user_list',
				[
					'operation_list' => $processed_operation_list,
					'currencies' => [
						'UAH' => 'грн',
						'USD' => 'дол',
						"RUR" => 'руб',
						"EUR" => 'євро'
					],
					'user' => $this->user,
					'op_user' => $this->user,
					'access' => $this->access,
					'not_this_user' => false,
					'op_style' => [
						1 => ["green", "green"],    // прихід
						2 => ["red", "red"],        // розхід
						4 => ["orange", "orange"],    // отримання кредиту
						5 => ["red", "#9797e8"],    // переміщення в касу іншого користувача
						6 => ["green", "#9797e8"],    // переміщення з каси іншого користувача
						7 => ["blue", "blue"],        // переміщення між своїми касами

					],
//					'contractors' => Models\Contractors::get_contractors_array(),
//					'applications' => Models\Applications::get_department_apps($this->user_id),
//					'expense_list' => Models\Articles::get_articles(),
//					'expense_list' => array_merge(Models\Articles::get_articles_tree(1), Models\Articles::get_articles_tree(2)),
					'expense_list' => !empty($articles_tree) ? $articles_tree['expense'] : [],
					'income_list' => !empty($articles_tree) ? $articles_tree['income'] : [],
					'user_wallets' => $user_wallets,
					'notifications' => $this->notifications,
					'css' => ['table', 'operation'],
					'js' => ['operation'],
					//'start_balance' => Models\Wallets::get_actual_user_balance_on_date($this->user_id)
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function list()
	{
		if (!empty($this->user_id)) {
			$departments_array = Models\Departments::get_departments_array();
			$operation_list = Models\Operation::get_operations();
			$currencies = [
				'UAH' => 'грн.',
				'USD' => 'дол.',
				"RUR" => 'руб.',
				"EUR" => 'євро'
			];
			foreach ($operation_list as $operation) {
				$processed_operation = [];
				$processed_operation['id'] = $operation['id'];
				$processed_operation['operation_type_id'] = $operation['operation_type_id'];
				$processed_operation['app_id'] = $operation['app_id'];
				$processed_operation['app_product'] = $operation['app_product'];
				$processed_operation['app_author_surname'] = $operation['app_author_surname'];
				$processed_operation['app_author_name'] = $operation['app_author_name'];
				$processed_operation['project_id'] = $operation['project_id'];
				$processed_operation['project_name'] = $operation['project_name'];
				$processed_operation['comment'] = $operation['comment'];
				$processed_operation['date'] = $operation['date'];
				$processed_operation['article_id'] = $operation['article_id'];
				$processed_operation['contractor_id'] = $operation['contractor1_id'];
				$processed_operation['contractor2_id'] = $operation['contractor2_id'];
				$contractor1 = Models\Contractors::get_contractor($processed_operation['contractor_id']);
				$contractor2 = Models\Contractors::get_contractor($processed_operation['contractor2_id']);
				$processed_operation['contractor_name'] = !empty($contractor1) ? $contractor1->name : '';
				$processed_operation['contractor2_name'] = !empty($contractor2) ? $contractor2->name : '';

				if ($operation['operation_type_id'] == 1 || $operation['operation_type_id'] == 4 || $operation['operation_type_id'] == 6) {
					$processed_operation['amount'] = $operation['amount2'];
					$processed_operation['currency'] = $currencies[$operation['currency2']];
					$processed_operation['department_id'] = $operation['department2_id'];
					$processed_operation['department_name'] = !empty($processed_operation['department_id']) ? $departments_array[$processed_operation['department_id']]['name'] : '';
					$processed_operation_list[$processed_operation['date']]['operation'][] = $processed_operation;
				} elseif ($operation['operation_type_id'] == 2 || $operation['operation_type_id'] == 5) {
					$processed_operation['amount'] = $operation['amount1'];
					$processed_operation['currency'] = $currencies[$operation['currency1']];
					$processed_operation['department_id'] = $operation['department1_id'];
					$processed_operation['department_name'] = !empty($processed_operation['department_id']) ? $departments_array[$processed_operation['department_id']]['name'] : '';
					$processed_operation_list[$processed_operation['date']]['operation'][] = $processed_operation;
				} elseif ($operation['operation_type_id'] == 3) {
					$processed_operation['amount'] = $operation['amount1'];
					$processed_operation['currency'] = $currencies[$operation['currency1']];
					$processed_operation['amount2'] = $operation['amount2'];
					$processed_operation['currency2'] = !empty($currencies[$operation['currency2']]) ? $currencies[$operation['currency2']] : '';
					$processed_operation['department_id'] = $operation['department1_id'];
					$processed_operation['department2_id'] = $operation['department2_id'];
					$processed_operation['department_name'] = !empty($processed_operation['department_id']) ? $departments_array[$processed_operation['department_id']]['name'] : '';
					$processed_operation['department2_name'] = !empty($processed_operation['department2_id']) ? $departments_array[$processed_operation['department2_id']]['name'] : '';

					$processed_operation_list[$processed_operation['date']]['operation'][] = $processed_operation;
//					$processed_operation_list[$processed_operation2['date']]['operation'][] = $processed_operation2;
				}
			}
			$articles_tree = Models\Articles::get_articles_tree();

			krsort($processed_operation_list, SORT_NUMERIC);
			return view('operation_list',
				[
					'operation_list' => $processed_operation_list,
					'currencies' => [
						'UAH' => 'грн.',
						'USD' => 'дол.',
						"RUR" => 'руб.',
						"EUR" => 'євро'
					],
					'user' => $this->user,
					'access' => $this->access,
					'op_style' => [
						1 => "green",
						2 => "red",
						3 => "blue",
						4 => "orange"
					],
					'contractors' => Models\Contractors::get_contractors_array(),
//					'department' => Models\Departments::get_contractors_array(),
					'expense_list' => !empty($articles_tree) ? $articles_tree['expense'] : [],
					'income_list' => !empty($articles_tree) ? $articles_tree['income'][0]['children'] : [],
					'departments' => Models\Departments::get_departments(),
					'notifications' => $this->notifications,
					'css' => ['operation'],
					'js' => ['operation'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function add_expenses()
	{
		if (!empty($this->user_id)) {
			$user_wallets = Models\Wallets::get_wallets(['where' => ['user_id = ' . $this->user_id]]);
			$user_applications = Models\Applications::get_user_apps($this->user_id);
			$company_wallets = [];
			$company_applications = [];
			if (Models\Position::control_finance($this->user_id)) {
				$company_wallets = Models\Wallets::get_wallets(['where' => ['type_id = ' . 2]]);
				$company_applications = Models\Applications::get_company_apps();
			}
			return view('operation_add_expenses',
				[
					'title' => 'Операція',
					'user' => $this->user,
					'access' => $this->access,
					'user_wallets' => Models\Position::finance_manager($this->user_id) ? Models\Wallets::get_wallets() : array_merge($user_wallets, $company_wallets),
					'applications' => array_merge($user_applications, $company_applications),
					'projects' => Models\Projects::get_projects(['where' => ['fp.account_id = ' . Models\Account::get_current_account_id()]]),
					'contractors' => Models\Contractors::get_contractors(['where' => ['fc.account_id = ' . Account::get_current_account_id()]]),
					'storages' => Models\Storage::get_storages(),
					'names' => Models\Storage::get_names(),
//					'expenses' => Models\Articles::get_articles(),
					'expenses' => Models\Articles::get_articles_tree(2),
					'currency_rate' => Models\CurrencyRate::get_exchange_rates_all(),
					'css' => ['operation', 'select2.min'],
					'js' => ['operation', 'select2.min']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}
//
//	public function add_income()
//	{
//		if (!empty($this->user_id)) {
//			$user_wallets = Models\Wallets::get_wallets(['where' => ['user_id = ' . $this->user_id]]);
//			$company_wallets = [];
//			if (Models\Position::control_finance($this->user_id)) {
//				$company_wallets = Models\Wallets::get_wallets(['where' => ['type_id = ' . 2]]);
//			}
//			return view('operation_add_income',
//				[
//					'title' => 'Операція',
//					'user' => $this->user,
//					'access' => $this->access,
//					'user_wallets' => Models\Position::finance_manager($this->user_id) ? Models\Wallets::get_wallets() : array_merge($user_wallets, $company_wallets),
//					'applications' => Models\Applications::get_user_apps($this->user_id),
//					'projects' => Models\Projects::get_projects(['where'=>['fp.account_id = ' . Models\Account::get_current_account_id()]]),
//					'contractors' => Models\Contractors::get_contractors(['where' => ['fc.account_id = ' . Account::get_current_account_id()]]),
//					'css' => ['operation', 'select2.min'],
//					'js' => ['operation', 'select2.min']
//				]
//			);
//		} else {
//			header('Location: ' . base_url() . 'user/login');
//			exit;
//		}
//	}
//
//	public function add_transfer()
//	{
//		if (!empty($this->user_id)) {
//			$user_wallets = Models\Wallets::get_wallets(['where' => ['user_id = ' . $this->user_id]]);
//			$company_wallets = [];
//			if (Models\Position::control_finance($this->user_id)) {
//				$company_wallets = Models\Wallets::get_wallets(['where' => ['type_id = ' . 2]]);
//			}
//			return view('operation_add_transfer',
//				[
//					'title' => 'Операція переміщення',
//					'user' => $this->user,
//					'access' => $this->access,
//					'wallets' => Models\Wallets::get_wallets(),
//					'user_wallets' => Models\Position::finance_manager($this->user_id) ? Models\Wallets::get_wallets() : array_merge($user_wallets, $company_wallets),
//					'applications' => Models\Applications::get_user_apps($this->user_id),
//					'projects' => Models\Projects::get_projects(['where'=>['fp.account_id = ' . Models\Account::get_current_account_id()]]),
//					'users' => Models\User::get_users_with_wallets(),
//					'css' => ['operation', 'select2.min'],
//					'js' => ['operation', 'select2.min']
//				]
//			);
//		} else {
//			header('Location: ' . base_url() . 'user/login');
//			exit;
//		}
//	}

	public function templates()
	{
		if (!empty($this->user_id)) {
			$templates = Models\Operation::get_templates([], ['fin_projects']);
//			$processed_operation_list = Models\Operation::preview_process($templates);
			$processed_templates = [];
			foreach ($templates['result'] as $template) {
				$processed_template = $template;
				if ($template['operation_type_id'] == 1 || $template['operation_type_id'] == 4 || $template['operation_type_id'] == 6) {
					$processed_template['amount'] = $template['amount2'];
					$processed_template['currency'] = $template['currency2'];
					$processed_template['wallet_id'] = $template['wallet2_id'];
					$processed_template['wallet_checkout'] = $template['wallet_2_checkout'];
					$processed_template['contractor_id'] = $template['contractor1_id'];
					$processed_template['contractor_name'] = $template['contractor1_name'];

				} elseif ($template['operation_type_id'] == 2 || $template['operation_type_id'] == 5) {
					$processed_template['amount'] = $template['amount1'];
					$processed_template['currency'] = $template['currency1'];
					$processed_template['wallet_id'] = $template['wallet1_id'];
					$processed_template['wallet_checkout'] = $template['wallet_1_checkout'];
					$processed_template['contractor_id'] = $template['contractor2_id'];
					$processed_template['contractor_name'] = $template['contractor2_name'];
				}
				$processed_templates[] = $processed_template;
			}

			return $this->view('operation/plan/templates',
				[
					'templates' => $processed_templates,
					'currencies' => [
						'UAH' => 'грн.',
						'USD' => 'дол.',
						"RUR" => 'руб.',
						"EUR" => 'євро'
					],
					'op_style' => [
						1 => ["green", "green"],    // прихід
						2 => ["red", "red"],        // розхід
						4 => ["orange", "orange"],    // отримання кредиту
						5 => ["red", "#9797e8"],    // переміщення в касу іншого користувача
						6 => ["green", "#9797e8"],    // переміщення з каси іншого користувача
						7 => ["blue", "blue"],        // переміщення між своїми касами
					],
					'contractors' => Models\Contractors::get_contractors_array(),
//					'department' => Models\Departments::get_contractors_array(),
					'expense_list' => !empty($articles_tree) ? $articles_tree['expense'] : [],
					'income_list' => !empty($articles_tree) ? $articles_tree['income'][0]['children'] : [],
					'departments' => Models\Departments::get_departments(),
					'notifications' => $this->notifications,
					'css' => ['table', 'operation'],
					'js' => ['operation'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	function quick()
	{

		if (!empty($this->user_id)) {

			return $this->view('operation/quick',
				[
					'projects' => Models\Projects::get_projects(['where' => ['fp.account_id = ' . Models\Account::get_current_account_id()]]),
					'css' => ['operation']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	function plan_fact()
	{
//		if (empty($department_id)) {
//			if (!empty($this->user->positions[0])) {
//				$department_id = $this->user->positions[0]['department_id'];
//			} else {
//				$department_id = Models\Departments::get_department(['where' => [
//					'is_default = 1'
//				]])->id;
//			}
//		}

		$departments_ids = [];
		if (!empty($this->user->positions)) {
			foreach ($this->user->positions as $position) {
				$departments_ids[] = $position['department_id'];
			}
		}


		$articles = Models\Articles::get_articles(['where' => [
//			'department_id IN ( ' . implode(',', $departments_ids) . ')'
		]]);


		$endpoints = Models\Articles::get_articles_endpoints(['where' => [
			'is_template = 1'
		]], [], true);

		$endpoints_with_articles = [];
		if (!empty($endpoints)) {
			foreach ($endpoints as $endpoint) {
				$article = Models\Articles::get_article(['where' => [
					'template_id = ' . $endpoint['id']
				]]);
				if (!empty($article)) {
					$endpoints_with_articles[$endpoint['id']]['articles'][] = $article->id;
					Models\Articles::get_articles_children_ids($article->id, $articles, $endpoint['id'], $endpoints_with_articles);
				}
			}
		}

		$operations = Models\Operation::get_user_operations($this->user_id, ['where' => [
			'fo.time_type = "real"',
			'(fo.operation_type_id = 1 OR fo.operation_type_id = 2)',
			'fo.date >= ' . (time() - 60 * 60 * 24 * 30),
			'fo.date <= ' . time(),
		]]);

		$planned_operations = Models\Operation::get_user_operations($this->user_id, ['where' => [
			'fo.time_type = "plan"',
			'(fo.operation_type_id = 1 OR fo.operation_type_id = 2)',
			'fo.plan_id = 0',
			'fo.planned_on >= ' . (time() - 60 * 60 * 24 * 30),
			'fo.planned_on <= ' . time(),
		]]);

		if (!empty($endpoints_with_articles)) {
			foreach ($endpoints_with_articles as $template_id => &$data) {
				if (empty($data['real'])) {
					$data['real'] = [];
				}
				if (empty($data['plan'])) {
					$data['plan'] = [];
				}

				if (!empty($operations)) {
					foreach ($operations as $operation) {
						if (!empty($operation['article_id'])) {
							if (in_array($operation['article_id'], $data['articles'])) {
								$data['real'][] = Models\Operation::preview_process($operation);
							}
						}
					}
				}

				if (!empty($planned_operations)) {
					foreach ($planned_operations as $operation) {
						if (!empty($operation['article_id'])) {
							if (in_array($operation['article_id'], $data['articles'])) {
								$data['plan'][] = Models\Operation::preview_process($operation, true);
							}
						}
					}
				}
			}
		}

		// знайти шаблони без дітей ( в шаблонах )
		// для кожної такої статті вивести список ід через кому дочірніх статтей в департаменті
		// погрупувати операції і плановані операції по цих статтях

		if (!empty($this->user_id)) {

			return $this->view('operation/plan/plan_fact',
				[
					'operations' => $endpoints_with_articles,
					'departments' => Models\Departments::get_departments(),
					'tree' => Models\Articles::get_templates_tree(),
					'css' => ['operation']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function charts()
	{

		if (!empty($this->user_id)) {
			$date_to = !empty($date_to) ? $date_to : strtotime("first day of January 00:00:00");
			$date_from = !empty($date_from) ? $date_from : strtotime("last day of December 23:59:59");

			$operations = Models\Operation::get_operations(['where' => [
				'(fo.operation_type_id= 1 OR fo.operation_type_id= 2)',
				'time_type = "real"',
				'fo.account_id = ' . Account::get_current_account_id(),
				'fo.date > ' . $date_to,
				'fo.date < ' . $date_from
			]]);
			return $this->view('operation/charts',
				[
					'title' => 'Графіки',
					'chart_data_week' => json_encode(Models\Operation::get_dates_and_amounts($operations, 'week')),
					'chart_data_month' => json_encode(Models\Operation::get_dates_and_amounts($operations, 'month')),
					'chart_data_year' => json_encode(Models\Operation::get_dates_and_amounts($operations, 'year')),
					'js' => ['g-charts', 'op_charts'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function upload()
	{

		if (!empty($this->user_id)) {
			return view('upload',
				[
					'title' => 'Завантаження операцій',
					'user' => $this->user,
					'access' => $this->access,
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

//
//	public function google_excel()
//	{
//		return Models\Google::connect();
//	}

//	public
//	function add_expense_ajax()
//	{
//		if (!empty($_POST)) {
//			$atts = $_POST;
//			switch ($atts['real_or_plan_expense']) {
//				case 'real':
//					$atts['is_planned'] = 0;
//					$atts['is_template'] = 0;
//					break;
//				case 'plan':
//					$atts['is_planned'] = 1;
//					$atts['is_template'] = 0;
//					$atts['planned_on'] = !empty($_POST['planned_on']) ? strtotime($_POST['planned_on']) : '';
//					break;
//				case 'template':
//					$atts['is_planned'] = 0;
//					$atts['is_template'] = 1;
//					$atts['repeat_start_date'] = !empty($atts['repeat_start_date']) ? strtotime($_POST['repeat_start_date']) : 0;
//					$atts['repeat_end_date'] = !empty($atts['has_end_date']) ? strtotime($_POST['repeat_end_date']) : 0;
//					break;
//			}
//			echo json_encode(Models\Operation::add($atts, 2));
//		}
//	}

	public
	function add_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			switch ($atts['time_type']) {
				case 'real':
					break;
				case 'plan':
					$atts['planned_on'] = !empty($_POST['planned_on']) ? strtotime($_POST['planned_on']) : '';
					break;
				case 'template':
					$atts['repeat_start_date'] = !empty($atts['repeat_start_date']) ? strtotime($_POST['repeat_start_date']) : 0;
					$atts['repeat_end_date'] = !empty($atts['has_end_date']) ? strtotime($_POST['repeat_end_date']) : 0;
					break;
			}
			echo json_encode(Models\Operation::add($atts));
		}
	}
//
//	public
//	function add_income_ajax()
//	{
//		if (!empty($_POST)) {
//			$atts = $_POST;
//			switch ($atts['real_or_plan_income']) {
//				case 'real':
//					$atts['is_planned'] = 0;
//					$atts['is_template'] = 0;
//					break;
//				case 'plan':
//					$atts['is_planned'] = 1;
//					$atts['is_template'] = 0;
//					$atts['planned_on'] = !empty($_POST['planned_on']) ? strtotime($_POST['planned_on']) : '';
//					break;
//				case 'template':
//					$atts['is_planned'] = 0;
//					$atts['is_template'] = 1;
//					$atts['repeat_start_date'] = !empty($atts['repeat_start_date']) ? strtotime($_POST['repeat_start_date']) : 0;
//					$atts['repeat_end_date'] = !empty($atts['has_end_date']) ? strtotime($_POST['repeat_end_date']) : 0;
//					break;
//			}
//			echo json_encode(Models\Operation::add($atts, 1));
//		}
//	}
//
//	public
//	function add_transfer_ajax()
//	{
//		if (!empty($_POST)) {
//			$atts = $_POST;
//			switch ($atts['real_or_plan_transfer']) {
//				case 'real':
//					$atts['is_planned'] = 0;
//					$atts['is_template'] = 0;
//					break;
//				case 'plan':
//					$atts['is_planned'] = 1;
//					$atts['is_template'] = 0;
//					$atts['planned_on'] = !empty($_POST['planned_on']) ? strtotime($_POST['planned_on']) : '';
//					break;
//				case 'template':
//					$atts['is_planned'] = 0;
//					$atts['is_template'] = 1;
//					$atts['repeat_start_date'] = !empty($atts['repeat_start_date']) ? strtotime($_POST['repeat_start_date']) : 0;
//					$atts['repeat_end_date'] = !empty($atts['has_end_date']) ? strtotime($_POST['repeat_end_date']) : 0;
//					break;
//			}
//			echo json_encode(Models\Operation::add($atts, 3));
//		}
//	}

	public function get_ajax($operation_id)
	{
		$operation_result = Models\Operation::get_operation(['where' => [
			'fo.id = ' . $operation_id
		]], ['fin_projects', 'fin_applications', 'fin_articles', 'fin_contractors']);
		if ($operation_result['status'] == 'ok' && !empty($operation_result['result'])) {
			$operation = Models\Operation::preview_process((array)$operation_result['result']);
			echo json_encode(['status' => 'ok', 'result' => $operation]);
		}
	}

	public
	function delete_operation_ajax($operation_id)
	{
		echo json_encode(Models\Operation::delete($operation_id));
	}

	public
	function change_app_in_operation_ajax($opp_id)
	{
		if (!empty($_POST)) {
			$app_id = $_POST['app_id'];
			echo json_encode(Models\Operation::change_app_in_operation($opp_id, $app_id));
		}
	}

	public
	function change_expense_in_operation_ajax($opp_id)
	{
		if (!empty($_POST)) {
			$expense_id = $_POST['expense_id'];
			echo json_encode(Models\Operation::change_expense_in_operation($opp_id, $expense_id));
		}
	}

	public
	function change_department_in_operation_ajax($opp_id)
	{
		if (!empty($_POST)) {
			$department_id = $_POST['department_id'];
			echo json_encode(Models\Operation::change_department_in_operation($opp_id, $department_id));
		}
	}

	public
	function change_date_in_operation_ajax($opp_id)
	{
		if (!empty($_POST)) {
			$date = $_POST['date'];
			echo json_encode(Models\Operation::change_date_in_operation($opp_id, $date));
		}
	}

	public
	function change_is_shown_ajax($opp_id)
	{
		if (!empty($_POST)) {
			$is_shown = !empty($_POST['is_shown']) ? $_POST['is_shown'] : 0;
			echo json_encode(Models\Operation::update($opp_id, ['is_shown' => $is_shown]));
		}

		$operation = Models\Operation::get_operation(['where' => ['id = ' . $opp_id]]);
		if (!empty($operation['result'])) {
			$operation = (array)$operation['result'];
			$is_planned = $operation['time_type'] == 'plan';
			if (!empty($operation['wallet1_id'])) {
				Models\Operation::update_later_operations($operation['wallet1_id'], $is_planned ? $operation['planned_on'] - 5 : $operation['date'] - 5, $is_planned);
			}

			if (!empty($operation['wallet2_id'])) {
				Models\Operation::update_later_operations($operation['wallet2_id'], $is_planned ? $operation['planned_on'] - 5 : $operation['date'] - 5, $is_planned);
			}
		}
	}

	public
	function perform_ajax($opp_id)
	{
		echo json_encode(Models\Operation::update($opp_id, [
			'wallet_1_planned_checkout' => 0,
			'wallet_2_planned_checkout' => 0,
			'date' => time(),
			'human_date' => date('d.m.Y'),
			'time_type' => 'real',
			'is_shown' => 0,
			'planned_on' => 0,
			'notify' => 0,
			'probability' => 0
		]));

		$operation = Models\Operation::get_operation(['where' => ['id = ' . $opp_id]]);
		if (!empty($operation['result'])) {
			$operation = (array)$operation['result'];
			if (!empty($operation['wallet1_id'])) {
				Models\Operation::update_later_operations($operation['wallet1_id'], time() - 10, false);
			}

			if (!empty($operation['wallet2_id'])) {
				Models\Operation::update_later_operations($operation['wallet2_id'], time() - 10, false);
			}
		}
	}

	public
	function change_notify_ajax($opp_id)
	{
		if (!empty($_POST)) {
			$notify = !empty($_POST['notify']) ? $_POST['notify'] : 0;
			echo json_encode(Models\Operation::update($opp_id, ['notify' => $notify]));
		}
	}


//	public function change_time()
//	{
////		Models\Operation::change_time();
//
//		echo strtotime('now');
//		echo "<br/>";
//		echo strtotime(date('Y-m-d'));
//		echo "<br/>";
//		echo (int)(strtotime('now') - strtotime(date('Y-m-d')));
//	}

//	public function transfer_checkouts_into_operations()
//	{
//		Models\Operation::transfer_checkouts_into_operations();
//	}

//	public function add_human_dates()
//	{
//		Models\Operation::add_human_dates();
//	}

//	public
//	function add_income_to_amount_2()
//	{
//		Models\Operation::add_income_to_amount_2();
//	}

	public
	function update_later_operations()
	{
		$wallets = Models\Wallets::get_wallets();
		if (!empty($wallets)) {
			foreach ($wallets as $wallet) {
				Models\Operation::update_later_operations($wallet['id'], 1577836805);
			}
		}
		return redirect()->to('/operation');
	}

	public
	function set_departments_to_operations()
	{
		Models\Operation::set_departments_to_operations();
	}

	public
	function privat()
	{
		Models\Operation::save_bank_operations();
	}
}
