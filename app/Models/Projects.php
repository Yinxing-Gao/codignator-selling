<?php


namespace App\Models;

use App\Controllers\Contract;
use Config;

class Projects
{
	public static function get_projects($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(
			array_merge(['fin_contracts', 'fin_contractors', 'fin_types', 'fin_departments'], $join),
			array_merge([], $has_many)
		);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_project($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(
			array_merge(['fin_contracts', 'fin_contractors', 'fin_types'], $join),
			array_merge([], $has_many)
		);
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single');
	}

	public static function get_projects_by_ids($project_ids)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fp.`id`, fp.`name`, `contract_id`, fp.`date`, `department_id`, `products_id`, fc.number,fco.name as contractor,type, fc.amount,fp.status, fp.comment,fp.end_date
 									FROM fin_projects fp
									LEFT JOIN fin_contracts fc ON fp.contract_id=fc.id
									LEFT JOIN fin_contractors fco ON fc.contractor_id=fco.name
									LEFT JOIN fin_types ft ON ft.id=fc.type_id WHERE fp.id IN (' . $project_ids . ')');
		return $query->getResultArray();
	}



	public static function get_department_projects($department_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fp.`id`, fp.`name`, `contract_id`, fp.`date`, `department_id`, `product_id`,fpp.name as product, fc.number,fco.name as contractor,type, fc.amount,fp.status, fp.comment,fp.end_date FROM fin_projects fp
									LEFT JOIN fin_production_products fpp ON fpp.id=fp.product_id
									LEFT JOIN fin_contracts fc ON fp.contract_id=fc.id
									LEFT JOIN fin_contractors fco ON fc.contractor_id=fco.name
									LEFT JOIN fin_types ft ON ft.id=fc.type_id WHERE fp.department_id=' . $department_id);
		$projects = $query->getResultArray();
		return $projects;
	}

	public static function get_department_projects_in_work($department_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fp.`id`, fp.`name`, `contract_id`, fp.`date`, `department_id`, `products_id`, fc.number,fco.name as contractor,type, fc.amount,fp.status, fp.comment,fp.end_date FROM fin_projects fp
									LEFT JOIN fin_contracts fc ON fp.contract_id=fc.id
									LEFT JOIN fin_contractors fco ON fc.contractor_id=fco.name
									LEFT JOIN fin_types ft ON ft.id=fc.type_id WHERE fp.department_id=' . $department_id . ' AND status ="in work"');
		$projects = $query->getResultArray();
		return $projects;
	}

	public static function add_update($atts)
	{
		$contract_id = 0;
		if (!empty($atts['contract_amount'])) {
			$contractor_params = [
				'amount' => $atts['contract_amount'],
				'currency' => !empty($atts['contract_currency']) ? $atts['contract_currency'] : 'UAH',
				'type_id' => $atts['contract_type_id'],
				'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : null

			];
			if (!empty($atts['contract_id'])) {
				$contractor_params['id'] = $atts['contract_id'];
			} else {
				$contractor_params['number'] = !empty($atts['contract_number']) ? $atts['contract_number'] : $atts['name'];
			}
			$contractor_result = Contracts::add_update($contractor_params);
			$contract_id = $contractor_result['id'];
		}
		$params = [
			'name' => $atts['name'],
			'contract_id' => !empty($contract_id) ? $contract_id : 0,
			'author_id' => $atts['author_id'],
			'date' => !empty($atts['start_date']) ? strtotime($atts['start_date']) : time(),
			'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
			'products_id' => !empty($atts['products']) ? implode(',', $atts['products']) : 0,
			'observers_ids' => !empty($atts['observers_ids']) ? implode(',', $atts['observers_ids']) : 0,
			'storages_ids' => !empty($atts['storages_ids']) ? implode(',', $atts['storages_ids']) : 0,
			'status' => !empty($atts['status']) ? $atts['status'] : 'new',
			'end_date' => !empty($atts['end_date']) ? strtotime($atts['end_date']) : time(),
			'comment' => !empty($atts['comment']) ? $atts['comment'] : '',
			'options' => $atts['name'],
			'is_virtual' => !empty($atts['is_virtual']) ? $atts['is_virtual'] : 0,
			'specification_id' => !empty($atts['specification_id']) ? $atts['specification_id'] : 0,
			'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : Account::get_current_account_id()
		];
		if (!empty($atts['id'])) {
			return DBHelp::update('fin_projects', $atts['id'], $params);
		} else {
			return DBHelp::insert('fin_projects', $params);
		}
	}

	public static function update($id, $atts)
	{
		return DBHelp::update('fin_projects', $id, $atts);
	}


//	public static function add($atts)
//	{
//		$products = !empty($atts['products_id']) ? implode(', ', $atts['products_id']) : 0;
//		$db = Config\Database::connect();
//		$query = $db->query('INSERT INTO `fin_projects`(`name`, `contract_id`, `date`, `department_id`, `products_id`, `status`, `end_date`, `comment`, `options`)
// 													VALUES ("' . addslashes($atts['name']) . '", "' . $atts['contract_id'] . '", ' . time() . ', "' . $atts['department_id'] . '", "' . $products . '", "new", "' . strtotime($atts['end_date']) . '", "' . addslashes($atts['comment']) . '", "' . addslashes($atts['name']) . '")');
//		$query->getResult();
//		if ($db->affectedRows() == 1) {
//			return ['status' => 'ok', 'id' => $db->insertID()];
//		} else {
//			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
//		}
//	}

//	public static function add_short($name)
//	{
//
//		$db = Config\Database::connect();
//		$query = $db->query('INSERT INTO `fin_projects`(`name`, `date`, `options`)
// 													VALUES ("' . addslashes($name) . '", ' . time() . ', ' . addslashes($name) . ')');
//		$query->getResult();
//		if ($db->affectedRows() == 1) {
//			return ['status' => 'ok', 'id' => $db->insertID()];
//		} else {
//			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
//		}
//	}

	public static function search_one($query)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM fin_projects fp WHERE options LIKE "%' . htmlspecialchars(trim($query)) . '%"');
		return $query->getFirstRow();
	}

	public static function get_project_items($project_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fpi.`id`,fsn.name , fpi.`storage_name_id`, `price`, fpi.`amount`, `project_id`,fpi.`block_id`, unit_id, fu.name as unit, fs.amount as spec_amount
									FROM `fin_project_items` fpi
									LEFT JOIN fin_storage_names fsn ON fsn.id=fpi.storage_name_id
									LEFT JOIN fin_specification fs ON fs.storage_name_id=fpi.storage_name_id AND fs.block_id=fpi.block_id AND fpi.product_id=fs.production_product_id
									LEFT JOIN fin_units fu ON fu.id=fsn.unit_id
									WHERE project_id=' . $project_id);
		return $query->getResultArray();
	}

	public static function search($query)
	{
		$params = [
			'limit' => null,
			'offset' => null,
			'order_by' => 'fp.department_id, fp.name'
		];

		if (!empty($query)) {
			$params['where'][] = '(fa.name LIKE "%' . trim($query) . '%" OR fa.comment LIKE "%' . trim($query) . '%")';
		}
		$projects = self::get_projects($params, ['fin_departments']);

		$projects_grouped_by_departments = [];
		if (!empty($projects)) {
			foreach ($projects as $project) {
				$projects_grouped_by_departments[$project['department_id']]['children'][] = $project;
			}

			if (!empty($projects_grouped_by_departments)) {
				foreach ($projects_grouped_by_departments as $department_id => &$d_projects) {
					$d_projects['id'] = $department_id;
					$d_projects['name'] = $d_projects['children'][0]['department_name'];
				}
			}
		}
		return $projects_grouped_by_departments;
	}

	public static function delete($project_id)
	{
		DBHelp::delete('fin_projects', $project_id);
		DBHelp::delete_where('fin_operations', ['project_id = ' . $project_id]);
		DBHelp::delete_where('fin_accruals', ['project_id = ' . $project_id]);
		DBHelp::delete_where('fin_applications', ['project_id = ' . $project_id]);

		return ['status' => 'ok'];
	}

	public static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['fp' => 'fin_projects'],
			'columns' => [
				'id',
				'name',
				'contract_id',
				'date',
				'author_id',
				'observers_ids',
				'department_id',
				'storages_ids',
				'products_id',
				'status',
				'end_date',
				'comment',
				'options',
				'specification_id',
				'account_id'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
//				'fp.account_id = ' . $account_id
			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_contracts', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fc' => 'fin_contracts'],
				'main_table_key' => 'contract_id',
				'columns' => [],
				'columns_with_alias' => [
					'amount' => 'contract_amount',
					'currency' => 'contract_currency',
					'number' => 'contract_number',
					'type_id' => 'contract_type_id',
				]
			];
		}

		if (in_array('fin_contractors', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fco' => 'fin_contractors'],
				'other_table_key' => 'fc.contractor_id',
				'columns' => [],
				'columns_with_alias' => [
					'id' => 'contractor_id',
					'name' => 'contractor_name'
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
					'id' => 'department_id',
					'name' => 'department_name'
				]
			];
		}

		if (in_array('fin_types', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['ft' => 'fin_types'],
				'other_table_key' => 'fc.type_id',
				'columns' => ['type'],
				'columns_with_alias' => [
				]
			];
		}

		if (in_array('fin_leads', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fl' => 'fin_leads'],
				'main_table_key' => 'lead_id',
				'columns' => [],
				'columns_with_alias' => [
					'status' => 'lead_status'
				]
			];
		}

		if (in_array('fin_products', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fp' => 'fin_products'],
				'type' => 'in',
				'new_column' => 'products',
				'main_table_key' => 'products_id',
				'other_table_key' => 'id',
				'columns' => ['id', 'name'],
				'columns_with_alias' => [],
			];
		}

		if (in_array('fin_observers', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fu' => 'fin_users'],
				'type' => 'in',
				'new_column' => 'observers',
				'main_table_key' => 'observers_ids',
				'other_table_key' => 'id',
				'columns' => ['id', 'name', 'surname'],
				'columns_with_alias' => [],
			];
		}

		if (in_array('fin_storage', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fs' => 'fin_storage'],
				'type' => 'in',
				'new_column' => 'storages',
				'main_table_key' => 'storages_ids',
				'other_table_key' => 'id',
				'columns' => ['id', 'name'],
				'columns_with_alias' => [],
			];
		}

		return $params;
	}
}
