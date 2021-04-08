<?php


namespace App\Models;

use Config;

class Departments
{
	public static function get_departments($params = [], $join = [])
	{
		$query_params = self::select_query(array_merge(['fin_users', 'fin_banks'], $join));
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_department($params = [], $join = [])
	{
		$query_params = self::select_query(array_merge(['fin_users', 'fin_banks'], $join));
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public static function get_department_tree($params = [], $join = [], $is_template = false)
	{
		return self::get_department_branch(0, self::get_departments(DBHelp::params_merge(['where' => [

		]], $params), []));
	}

	//використовується всюди, де виводяться дані або тільки по департаменту, або по всіх дочірніх
	public static function get_single_department_or_with_children($with_children = false)
	{
		return $with_children ?
			implode(',', self::get_department_tree_ids(['where' => [
				'fd.account_id = ' . Account::get_current_account_id()
			]])) :
			self::get_department(['where' => [
				'fd.account_id = ' . Account::get_current_account_id(),
				'fd.parent_department_id = 0'
			]])->id;
	}


	//віддає всі ід дочірніх департаментів
	public static function get_department_tree_ids($params = [], $parent_id = 0, $join = [])
	{
		$department_ids = [];
		self::get_department_children_ids($parent_id, self::get_departments(DBHelp::params_merge(['where' => [

		]], $params), []), $department_ids);
		return $department_ids;
	}

	//додає в масив ід дочірніх департаментів в рекурсії
	public static function get_department_children_ids($parent_id, $departments, &$department_ids)
	{
		if (!empty($departments)) {
			foreach ($departments as $department) {
				if ($department['parent_department_id'] == $parent_id) {
					$department_ids[] = $department['id'];
					self::get_department_children_ids($department['id'], $departments, $department_ids);
				}
			}
		}
		return [];
	}

	public static function add($atts)
	{
		$params = [
			'name' => !empty($atts['name']) ? $atts['name'] : '',
			'parent_department_id' => !empty($atts['parent_department_id']) ? $atts['parent_department_id'] : 0,
			'type' => !empty($atts['type']) ? $atts['type'] : '',
			'modules' => !empty($atts['modules']) ? $atts['modules'] : '',
			'is_shown' => !empty($atts['is_shown']) ? $atts['is_shown'] : 0,
			'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : 0,
		];
		return DBHelp::insert('fin_departments', $params);
	}

	public static function update($id, $params)
	{
		return DBHelp::update('fin_departments', $id, $params);
	}

	public static function create_departments_defaults($department_id, $account_id)
	{
		$add_result = Storage::add([
				'name' => 'Основні засоби',
				'department_id' => $department_id,
				'last_change_date' => time(),
				'type' => 'fixed assets',
				'account_id' => $account_id
			]
		);

		if ($add_result['status'] == 'ok') {
			Storage::add([
					'name' => 'Нерухомість',
					'department_id' => $department_id,
					'last_change_date' => time(),
					'type' => 'fixed assets',
					'parent_id' => $add_result['id'],
					'account_id' => $account_id
				]
			);

			Storage::add([
					'name' => 'Транспорт',
					'department_id' => $department_id,
					'last_change_date' => time(),
					'type' => 'fixed assets',
					'parent_id' => $add_result['id'],
					'account_id' => $account_id
				]
			);

			Storage::add([
					'name' => 'Обладнання',
					'department_id' => $department_id,
					'last_change_date' => time(),
					'type' => 'fixed assets',
					'parent_id' => $add_result['id'],
					'account_id' => $account_id
				]
			);
		}
	}

	public static function get_department_branch($parent_id, $departments)
	{
		$department_branch = [];
		if (!empty($departments)) {
			foreach ($departments as $department) {
				if ($department['parent_department_id'] == $parent_id) {
					$department_branch[] =
						[
							'id' => $department['id'],
							'name' => $department['name'],
							'type' => $department['type'],
							'modules' => $department['modules'],
							'is_shown' => $department['is_shown'],
							'is_default' => $department['is_default'],
							'children' => self::get_department_branch($department['id'], $departments)
						];
				}
			}
		}
		return $department_branch;
	}

	public static function get_departments_array($type = null)
	{
		$departments = self::get_departments($type);
		$departments_array = [];
		if (!empty($departments)) {
			foreach ($departments as $department) {
				$departments_array[$department['id']] = $department;
			}
		}
		return $departments_array;
	}

	public static function delete($department_id)
	{
		DBHelp::delete('fin_departments', $department_id);
		$departments = self::get_departments(['where' => ['parent_department_id = ' . $department_id]]);
		if (!empty($departments)) {
			foreach ($departments as $department) {
				self::delete($department['id']);
			}
		}
		return ['status' => 'ok'];
	}

	public static function select_query($join_keys = [])
	{
		return [
			'table' => ['fd' => 'fin_departments'],
			'columns' => [
				'id',
				'name',
				'type',
				'parent_department_id',
				'account_id',
				'modules',
				'is_shown',
				'is_default',
				'accesses'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
				'account_id = ' . Account::get_current_account_id(),
			],
			'limit' => null,
			'offset' => null
		];
	}
}
