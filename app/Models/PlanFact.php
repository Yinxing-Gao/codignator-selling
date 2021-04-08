<?php


namespace App\Models;

use Config;

class PlanFact
{
	public static function get_plan_fact($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(
			array_merge(['fin_contracts', 'fin_contractors', 'fin_types'], $join),
			array_merge(['fin_plan_fact_workers', 'fin_plan_fact_products'], $has_many)
		);
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

//	public static function add_plan_fact($project_id)
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('INSERT INTO `fin_plan_facts`(`project_id`, `responsible_id`, `training`, `fact_by_plan`)
//		 						VALUES (' . $project_id . ', 0, 0, 1)');
//		$query->getResult();
//		return self::get_plan_fact($db->insertID());
//	}


	public static function add($atts)
	{
		$params = [
			'project_id' => !empty($atts['project_id']) ? $atts['project_id'] : 0,
			'currency' => !empty($atts['currency']) ? $atts['currency'] : 'UAH',
			'responsible_id' => !empty($atts['responsible_id']) ? $atts['responsible_id'] : 0,
			'away' => !empty($atts['away']) ? $atts['away'] : 0,
			'training' => !empty($atts['training']) ? $atts['training'] : 0,
			'fact_by_plan' => !empty($atts['fact_by_plan']) ? $atts['fact_by_plan'] : 1,
		];
		return DBHelp::insert('fin_plan_facts', $params);
	}

	public static function get_company_result($month_year = null)
	{
		$month_year = !empty($month_year) ? $month_year : date('m.Y');

		$article_list = Articles::get_articles();
		$articles_array = Articles::get_articles_array();
		$operations = Operation::get_operations(['month_year' => $month_year]);
		$departments = Departments::get_departments('main');
		$departments_array = Departments::get_departments_array();
		$incomes = [];
		$expenses = [];
		$articles_total_income = [];
		$articles_total_expense = [];
		$top = [];
		$top_expense_articles = [];

		if (!empty($operations) && !empty($article_list)) {

			foreach ($article_list as $article) {
				if ($article['parents_item_id'] == 0 && $article['type'] == 2) {
					$top_expense_articles[] = $article['id'];
				}

				$articles_total_income[$article['id']] = 0;
				$articles_total_expense[$article['id']] = 0;
				foreach ($departments as $department) {
					foreach ($operations as $operation) {
						if (!empty($operation['department1_id']) && !empty($operation['article_id'])) {
							if (($operation['department1_id'] == $department['id'] ||
									$departments_array[$operation['department1_id']]['parent_department_id'] == $department['id'])
								&& $operation['operation_type_id'] == 2
								&& $operation['article_id'] == $article['id']) {

//								$totals = self::count_article_total($article['id'], $operation['amount1'], $expenses, $department['id'], 'expense', $departments_total_expense);
								$totals = self::count_article_total($article['id'], $operation['amount1'], $expenses, $department['id'], 'expense', $articles_array, $top);
								$expenses = $totals['totals'];
//								$departments_total_expense = $totals['top'];
								$top = $totals['top'];
								$articles_total_expense[$article['id']] += (float)$operation['amount1'];
							}
						}

						if (!empty($operation['department2_id']) && !empty($operation['article_id'])) {
							if (($operation['department2_id'] == $department['id']
									|| $departments_array[$operation['department2_id']]['parent_department_id'] == $department['id'])
								&& $operation['operation_type_id'] == 1
								&& $operation['article_id'] == $article['id']) {

//								$totals = self::count_article_total($article['id'], $operation['amount2'], $incomes, $department['id'], 'income', $departments_total_income);
								$totals = self::count_article_total($article['id'], $operation['amount2'], $incomes, $department['id'], 'income', $articles_array, $top);
								$incomes = $totals['totals'];
//								$departments_total_income = $totals['top'];
								$top = $totals['top'];


								$articles_total_income[$article['id']] += (float)$operation['amount2'];
							}
						}
					}
				}
			}
		}

		$margins = [];
		if (!empty($top['expense'])) {
			$departments_total_expense = $top['expense'];
			$departments_total_income = $top['income'];

			foreach ($departments_total_expense as $department_id => $department_expenses) { // беремо всі витрати по департаментах
				if (empty($departments_total_income[$department_id])) { // якщо дохід по якомусь департаменту відсутній призначаємо 0
					$departments_total_income[$department_id] = 0;
				}
				if (!empty($department_expenses)) {
					foreach ($top_expense_articles as $article_id) {
						$article_expense = !empty($department_expenses[$article_id]) ? $department_expenses[$article_id] : [
							'amount' => 0,
							'margin_level' => !empty($articles_array[$article_id]) ? (int)$articles_array[$article_id]['margin_level'] : 0
						];
						//дохід рахується неправильно, тому і маржинальність теж не працює
						$margins[$department_id][$article_id] = (float)$departments_total_income[$department_id];
//						$margins[$department_id][$article_id] = (float)$incomes[$article_id][$department_id];
						for ($i = 1; $i <= (int)$article_expense['margin_level']; $i++) {
							foreach ($department_expenses as $article_id_ => $article_expense_) {
								if ($i == $article_expense_['margin_level']) {
									$margins[$department_id][$article_id] -= (float)$article_expense_['amount'];
								}
							}
						}
					}
				}
			}
		}


		return
			[
				'departments' => $departments,
				'incomes' => $incomes,
				'margins' => $margins,
				'articles_total_income' => $articles_total_income,
				'articles_total_expense' => $articles_total_expense,
				'expenses' => $expenses,
			];
	}

	public
	static function count_article_total($article_id, $amount, &$totals, $department_id, $type, $articles_array, &$top = [])
	{
		if (empty($totals[$article_id][$department_id])) {
			$totals[$article_id][$department_id] = 0;
		}
		if (empty($totals[$article_id]['company'])) {
			$totals[$article_id]['company'] = 0;
		}

		$totals[$article_id][$department_id] += (float)$amount;
		$totals[$article_id]['company'] += (float)$amount;

		if (!empty($articles_array[$article_id])) {
			$article = $articles_array[$article_id];
			if ($article['parents_item_id'] != 0) {
				self::count_article_total($article['parents_item_id'], $amount, $totals, $department_id, $type, $articles_array, $top);
			} else { //верхні статті
				if ($type == 'income') {
					$top[$type][$department_id] = $totals[$article_id][$department_id];
					if (empty($top[$type]['company'])) {
						$top[$type]['company'] = 0;
					}
					$top[$type]['company'] = $totals[$article_id]['company'];

				} elseif ($type == 'expense') {
					$top[$type][$department_id][$article_id]['amount'] = (float)$totals[$article_id][$department_id];
					$top[$type][$department_id][$article_id]['margin_level'] = (int)$article['margin_level'];

					$top[$type]['company'][$article_id]['margin_level'] = (int)$article['margin_level'];
					if (empty($top[$type]['company'][$article_id]['amount'])) {
						$top[$type]['company'][$article_id]['amount'] = 0;
					}
					$top[$type]['company'][$article_id]['amount'] = (float)$totals[$article_id]['company'];
				}
			}

		}
		return [
			'totals' => $totals,
			'top' => $top
		];
	}

	public static function get_param($plan_fact_id, $name, $type)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM `fin_plan_fact_params` WHERE plan_fact_id = ' . $plan_fact_id . ' AND name = "' . htmlspecialchars(trim($name)) . '" AND type = "' . $type . '"');
		return (array)$query->getFirstRow();
	}

	public static function set_param($plan_fact_id, $name, $value, $type, $article_id = null, $date = null, $department_id = null)
	{
		$db = Config\Database::connect();
		if (in_array($name, ['responsible_id', 'contract_id', 'away', 'currency', 'training'])) {
			$query = $db->query('UPDATE `fin_plan_facts` SET `' . htmlspecialchars(trim($name)) . '`="' . htmlspecialchars(trim($value)) . '" WHERE id = ' . $plan_fact_id);
		} else {
			$param_row = self::get_param($plan_fact_id, $name, $type);
			if (!empty($param_row)) {
				$query = $db->query('UPDATE  `fin_plan_fact_params` SET value = "' . $value . '" WHERE id = ' . $param_row['id']);
			} else {
				$query = $db->query('INSERT INTO `fin_plan_fact_params`( `name`, `value`, `type`, `plan_fact_id`)
								VALUES ("' . htmlspecialchars(trim($name)) . '", "' . htmlspecialchars(trim($value)) . '", "' . htmlspecialchars(trim($type)) . '", ' . $plan_fact_id . ')');
			}
		}
		if (in_array($name, ['total_amount']) && $type == 'fact') {
			$plan_fact = PlanFact::get_plan_fact($plan_fact_id);
			if (!empty($plan_fact) && !(bool)$plan_fact->training) {
				$department_id = !empty($atts['department_id']) ? $atts['department_id'] : 0;
				$atts['amount'] = (int)$value;
				$atts['currency'] = $plan_fact->currency;
				if (in_array($name, ['total_amount'])) {
					$atts['accrual_type'] = 'debit';
					$atts['type'] = 'sale';
				} else {
					$atts['accrual_type'] = 'credit';
					$atts['type'] = 'project expense';
				}
				$atts['type_id'] = 0;
				$atts['article_id'] = $article_id;
				$atts['comment'] = 'Оплата по проекту ';
				$project = Projects::get_project($plan_fact->project_id);
				if (!empty($project)) {
					$atts['comment'] .= $project->name;
				}
				$atts['date'] = strtotime($date);
				$atts['period_type'] = 'project';
				$atts['project_id'] = $plan_fact->project_id;
				$atts['department_id '] = $department_id;

				Accruals::set_project_accrual($atts);
			}
		}
		$query->getResult();
		return ['status' => 'ok'];
	}

	public static function get_project_params($plan_fact_id)
	{
		$param_names = [
			'start_date',
			'end_date',
			'days_amount',
			'square',
			'total_amount',
			'transport_total',
			'agent_total',
			'other_total'
		];
		$project_params = [];
		foreach ($param_names as $param_name) {
			$plan_param_value = self::get_param($plan_fact_id, $param_name, 'plan');
			$fact_param_value = self::get_param($plan_fact_id, $param_name, 'fact');
			$project_params['plan'][$param_name] = !empty($plan_param_value) ? $plan_param_value['value'] : '';
			$project_params['fact'][$param_name] = !empty($fact_param_value) ? $fact_param_value['value'] : '';
		}
		return $project_params;
	}

	public static function update_products($plan_fact_id, $type, $products, $date, $placement, $article_id, $department_id)
	{
		$db = Config\Database::connect();
		$plan_fact = PlanFact::get_plan_fact($plan_fact_id);
		if (!empty($plan_fact)) {
			$query = $db->query('DELETE  FROM `fin_plan_fact_products`
					WHERE plan_fact_id = ' . $plan_fact_id . ' AND placement = "' . $placement . '" AND type = "' . $type . '"');
			$query->getResult();

			$total_price = 0;
			//створити нові пункти зі складу
			if (!empty($products)) {
				foreach ($products as $product) {
					if ((int)$product['amount'] > 0) {
//						echo  'INSERT INTO `fin_plan_fact_products`( `plan_fact_id`, `type`, `product_id`, `amount`, `placement`)
//								 VALUES (' . $plan_fact_id . ', "' . $type . '", ' . $product['id'] . ', ' . $product['amount'] . ',"' . $placement . '")';
						$query = $db->query('INSERT INTO `fin_plan_fact_products`( `plan_fact_id`, `type`, `product_id`, `amount`, `placement`)
								 VALUES (' . $plan_fact_id . ', "' . $type . '", ' . $product['id'] . ', ' . $product['amount'] . ',"' . $placement . '")');
						$query->getResult();
						if ($type == 'fact' && !(bool)$plan_fact->training) {
							$product_row = Products::get_product($product['id']);
							if (!empty($product_row)) {
								//тут дописати конвертацію валют

								$total_price += ($product_row->buy_price * $product['amount']);
							}
						}
					}
				}
				if ($type == 'fact' && !(bool)$plan_fact->training) {
					$atts['amount'] = $total_price;
					$atts['currency'] = $plan_fact->currency;
					$atts['accrual_type'] = 'credit';
					$atts['type'] = 'project expense';
					$atts['type_id'] = 0;
					$atts['article_id'] = $article_id;
					$atts['comment'] = 'Матеріали по проекту ';
					$project = Projects::get_project($plan_fact->project_id);
					if (!empty($project)) {
						$atts['comment'] .= $project->name;
					}
					$atts['date'] = strtotime($date);
					$atts['period_type'] = 'project';
					$atts['project_id'] = $plan_fact->project_id;
					$atts['department_id '] = $department_id;

					Accruals::set_project_accrual($atts);
				}
			}
		}
		return ['status' => 'ok'];
	}


	public
	static function update_workers($plan_fact_id, $workers, $type, $date, $placement, $article_id, $department_id)
	{
		$db = Config\Database::connect();
		$plan_fact = PlanFact::get_plan_fact($plan_fact_id);
		if (!empty($plan_fact)) {
			$query = $db->query('DELETE FROM `fin_plan_fact_workers`
 					WHERE plan_fact_id = ' . $plan_fact_id . ' AND type = "' . $type . '" AND placement ="' . $placement . '"');
			$query->getResult();
			$total_price = 0;
			//створити нові пункти зі складу
			if (!empty($workers)) {
				foreach ($workers as $worker) {
					if ((int)$worker['amount'] > 0) {
						$query = $db->query('INSERT INTO `fin_plan_fact_workers`(`plan_fact_id`, `type`, `worker_id`, `amount`, `currency`, `placement`)
								 VALUES (' . $plan_fact_id . ', "' . $type . '", ' . $worker['id'] . ', ' . $worker['amount'] . ', "' . $plan_fact->currency . '", "' . $placement . '")');
						$query->getResult();

						if ($type == 'fact' && !(bool)$plan_fact->training) {
							$total_price += $worker['amount'];
						}
					}
				}
			}
			if ($type == 'fact') {
				if (!(bool)$plan_fact->training) {
					$atts['amount'] = $total_price;
					$atts['currency'] = $plan_fact->currency;
					$atts['accrual_type'] = 'credit';
					$atts['type'] = 'project expense';
					$atts['type_id'] = 0;
					$atts['article_id'] = $article_id;
					$atts['comment'] = 'ЗП чи відрядні по проекту ';
					$project = Projects::get_project($plan_fact->project_id);
					if (!empty($project)) {
						$atts['comment'] .= $project->name;
					}
					$atts['date'] = strtotime($date);
					$atts['period_type'] = 'project';
					$atts['project_id'] = $plan_fact->project_id;
					$atts['department_id '] = $department_id;

					Accruals::set_project_accrual($atts);
				}
			}

			return ['status' => 'ok'];
		}
	}


	public
	static function get_plan_fact_products($plan_fact_id, $price_type, $currency = null)
	{
		$db = Config\Database::connect();

//		echo 'SELECT fp.`id`, fpfp.id as pf_product_id, fp.storage_name_id as name_id, fsn.name ,`plan_fact_id`, `type`, fsn.unit_id, fu.name as unit, fpfp.`amount`, fsn.`storage_id`,fps.price, fps.currency
//								FROM `fin_plan_fact_products` fpfp
//                                LEFT JOIN fin_products fp ON fp.id = fpfp.product_id
//								LEFT JOIN fin_storage_names fsn ON fsn.id = fp.storage_name_id
//								LEFT JOIN fin_units fu ON fu.id = fsn.unit_id
//								LEFT JOIN fin_prices fps ON fps.product_id = fp.id
//								WHERE plan_fact_id = ' . $plan_fact_id . ' AND price_type = "' . $price_type . '"';
		$query = $db->query('SELECT fp.`id`, fpfp.id as pf_product_id, fp.storage_name_id as name_id, fsn.name ,`plan_fact_id`, `type`, fsn.unit_id, fu.name as unit, fpfp.`amount`, fsn.`storage_id`,fps.price, fps.currency
								FROM `fin_plan_fact_products` fpfp
                                LEFT JOIN fin_products fp ON fp.id = fpfp.product_id
								LEFT JOIN fin_storage_names fsn ON fsn.id = fp.storage_name_id
								LEFT JOIN fin_units fu ON fu.id = fsn.unit_id
								LEFT JOIN fin_prices fps ON fps.product_id = fp.id
								WHERE plan_fact_id = ' . $plan_fact_id . ' AND price_type = "' . $price_type . '"');
		$products = $query->getResultArray();
		$result = [];
		if (!empty($currency) && !empty($products)) {
			foreach ($products as &$product) {
				if ($product['currency'] != $currency) {
					$currency_rate = CurrencyRate::get_exchange_rates($product['currency']);
					$product['price_'] = $product['price'] * $currency_rate['buy'];
				} else {
					$product['price_'] = $product['price'];
				}
				$result[$product['type']][] = $product;
			}
		}
		return $result;
	}

	public
	static function get_involved_workers($plan_fact_id)
	{

		$db = Config\Database::connect();
		$query = $db->query('SELECT fu.`id`, fpfw.id as pfw_id, `plan_fact_id`, `type`, `worker_id`, `amount`, `currency`, `placement`, fu.name, fu.surname, fntp.profession_id
									FROM `fin_plan_fact_workers` fpfw
									LEFT JOIN fin_users fu ON fu.id=worker_id
									LEFT JOIN fin_user_to_position fntp ON fntp.user_id=worker_id =' . $plan_fact_id);
		$storage_items = $query->getResultArray();
		$result = [];
		if (!empty($storage_items)) {
			foreach ($storage_items as $storage_item) {
				$result[$storage_item['type']][] = $storage_item;
			}
		}
		return $result;

	}

	public
	static function delete_product($pfp_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('DELETE FROM `fin_plan_fact_products` WHERE id=' . $pfp_id);

		$query->getResult();
		return ['status' => 'ok'];
	}

	public
	static function delete_worker($worker_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('DELETE FROM `fin_plan_fact_workers` WHERE id=' . $worker_id);

		$query->getResult();

		//видаляти начислення
		return ['status' => 'ok'];
	}

	public static function select_query($join_keys = [], $has_many_keys = [])
	{
//		$session = Config\Services::session();
//		$account_id = $session->get('account_id');
		$params = [
			'table' => ['fpf' => 'fin_plan_facts'],
			'columns' => [
				'id',
				'project_id',
				'currency',
				'responsible_id',
				'away',
				'training',
				'fact_by_plan'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [

			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_plan_fact_workers', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fpfw' => 'fin_plan_fact_workers'],
				'new_column' => 'workers',
				'main_table_key' => 'id',
				'other_table_key' => 'plan_fact_id',
				'columns' => [
					'id', 'plan_fact_id', 'type', 'worker_id', 'amount', 'currency', 'placement'
				],
				'columns_with_alias' => [
				],
				'join' => [//	LEFT JOIN fin_users fu ON fu.id=worker_id
					[
						'type' => 'LEFT JOIN',
						'table' => ['fu' => 'fin_users'],
						'main_table_key' => 'worker_id',
						'joined_table_key' => 'id',
						'columns' => [
						],
						'columns_with_alias' => [
							'id' => 'user_id',
							'name' => 'user_name',
							'surname' => 'user_surname',
						]
					]
				],
				'has_many' => [
					'table' => ['futp' => 'fin_user_to_position'],
					'new_column' => 'positions',
					'main_table_key' => 'id',
					'other_table_key' => 'user_id',
					'columns' => ['position_id'],
					'columns_with_alias' => [
					],
					'join' => [
						[
							'type' => 'LEFT JOIN',
							'table' => ['fp' => 'fin_positions'],
							'main_table_key' => 'position_id',
							'joined_table_key' => 'id',
							'columns' => [
								'name', 'department_id'
							],
							'columns_with_alias' => [

							]
						]
					]
				]
			];
		}

		if (in_array('fin_plan_fact_products', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fpfp' => 'fin_plan_fact_products'],
				'new_column' => 'products',
				'main_table_key' => 'id',
				'other_table_key' => 'plan_fact_id',
				'columns' => [
					'id', 'plan_fact_id', 'type', 'product_id', 'amount', 'placement'
				],
				'columns_with_alias' => [
				],
				'join' => [//	LEFT JOIN fin_users fu ON fu.id=worker_id
					[
						'type' => 'LEFT JOIN',
						'table' => ['fu' => 'fin_products'],
						'main_table_key' => 'product_id',
						'joined_table_key' => 'id',
						'columns' => [
						],
						'columns_with_alias' => [
							'id' => 'product_id',
							'name' => 'product_name'
						]
					]
				],
				'has_many' => [
					'table' => ['futp' => 'fin_user_to_position'],
					'new_column' => 'positions',
					'main_table_key' => 'id',
					'other_table_key' => 'user_id',
					'columns' => ['position_id'],
					'columns_with_alias' => [
					],
					'join' => [
						[
							'type' => 'LEFT JOIN',
							'table' => ['fp' => 'fin_positions'],
							'main_table_key' => 'position_id',
							'joined_table_key' => 'id',
							'columns' => [
								'name', 'department_id'
							],
							'columns_with_alias' => [

							]
						]
					]
				]
			];
		}
//
//		if (in_array('fin_contracts', $join_keys)) {
//			$params['join'][] = [
//				'type' => 'LEFT JOIN',
//				'table' => ['fc' => 'fin_contracts'],
//				'main_table_key' => 'contract_id',
//				'columns' => [],
//				'columns_with_alias' => [
//					'amount' => 'contract_amount',
//					'currency' => 'contract_currency',
//					'number' => 'contract_number',
//					'type_id' => 'contract_type_id',
//				]
//			];
//		}
//
//		if (in_array('fin_contractors', $join_keys)) {
//			$params['join'][] = [
//				'type' => 'LEFT JOIN',
//				'table' => ['fco' => 'fin_contractors'],
//				'other_table_key' => 'fc.contractor_id',
//				'columns' => [],
//				'columns_with_alias' => [
//					'id' => 'contractor_id',
//					'name' => 'contractor_name'
//				]
//			];
//		}
//
//		if (in_array('fin_departments', $join_keys)) {
//			$params['join'][] = [
//				'type' => 'LEFT JOIN',
//				'table' => ['fd' => 'fin_departments'],
//				'main_table_key' => 'department_id',
//				'columns' => [],
//				'columns_with_alias' => [
//					'id' => 'department_id',
//					'name' => 'department_name'
//				]
//			];
//		}
//
//		if (in_array('fin_types', $join_keys)) {
//			$params['join'][] = [
//				'type' => 'LEFT JOIN',
//				'table' => ['ft' => 'fin_types'],
//				'other_table_key' => 'fc.type_id',
//				'columns' => ['type'],
//				'columns_with_alias' => [
//				]
//			];
//		}

		return $params;
	}
}
