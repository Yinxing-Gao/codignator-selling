<?php


namespace App\Models;

use Config;


class Position
{
	static $positions = [];

	public static function get_positions($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(array_merge([], $join), array_merge([], $has_many));

		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_position($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(array_merge([], $join), array_merge([], $has_many));

		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}


	public static function add($atts)
	{
		$params = [
			'name' => !empty($atts['name']) ? $atts['name'] : '',
			'subordination' => !empty($atts['subordination']) ? $atts['subordination'] : 0,
			'access' => !empty($atts['access']) ? $atts['access'] : '',
			'salary_amount' => !empty($atts['salary_amount']) ? $atts['salary_amount'] : 0,
			'salary_currency' => !empty($atts['salary_currency']) ? $atts['salary_currency'] : 'UAH',
			'work_days' => !empty($atts['work_days']) ? $atts['work_days'] : '',
			'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
			'potential_amount_of_workers' => !empty($atts['potential_amount_of_workers']) ? $atts['potential_amount_of_workers'] : 1,
			'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : 0,
		];
		return DBHelp::insert('fin_positions', $params);
	}

	public static function get_parents($position_id)
	{
		$positions = self::get_positions([], ['fin_departments'], ['fin_user_to_position']);
		$parents = [];
		self::get_parent($position_id, $positions, $parents);
		return $parents;
	}

	private static function get_parent($position_id, $positions, &$parents)
	{
		$this_position = self::get_position(['where' => ['id = ' . $position_id]]);
		if (!empty($this_position)) {
			$subordination = $this_position->subordination;
			if (!empty($positions)) {
				foreach ($positions as $position) {
					if ($position['id'] == $subordination) {
						$parents[] = $position;
						self::get_parent($position['id'], $positions, $parents);
					}
				}
			}
		}
	}

	public static function change_users($position_id, $users_ids, $account_id = null)
	{
		if (!empty($position_id)) {

			$account_id = !empty($account_id) ? $account_id : Account::get_current_account_id();

//			Dev::var_dump(DBHelp::delete_where('fin_user_to_position', [
//				'position_id = ' . $position_id
//			]));

			if (!empty($users_ids)) {
				foreach ($users_ids as $user_id) {
					DBHelp::insert('fin_user_to_position', [
						'user_id' => $user_id,
						'position_id' => $position_id,
						'account_id' => $account_id
					]);
				}
			}
		}
		return ['status' => 'ok'];
	}

	public static function get_department_users($user_id, $with_fields = false)
	{
		$positions = self::get_positions($user_id);
		$positions_id = [];
		foreach ($positions as $position) { // знаходимо всі професії даного користувача
			$positions_id[] = $position;
		}
		if ($with_fields) {
//			return self::get_users_by_positions(self::get_departments_positions($positions_id));
			return User::get_users(['where' => ['account_id = ' . Account::get_current_account_id()]]);
		} else {
			return self::get_user_ids_by_positions(self::get_departments_positions($positions_id));
		}
	}

	public static function get_departments_positions(array $positions_id)
	{
		$db = Config\Database::connect();
		$query_row = '';
		foreach ($positions_id as $pr_id) { // знаходимо всі професії даного користувача
			self::$positions[] = $pr_id;
			$query_row .= $pr_id . ', ';
		}
		$query = $db->query('SELECT * FROM fin_positions fp where subordination IN (' . substr($query_row, 0, -2) . ')');
		$position_list = $query->getResultArray();

		$positions_id2 = [];
		if (!empty($position_list)) {
			foreach ($position_list as $position_) {
				self::$positions[] = $position_['id'];
				$positions_id2[] = $position_['id'];
			}
			self::get_departments_positions($positions_id2);
		}


		return array_unique(self::$positions);
	}

	public static function get_positions_with_access($user_id)
	{
		$db = Config\Database::connect();//(*)
		$query = $db->query('SELECT fp.id, fp.access,department_id FROM `fin_positions` fp
									LEFT JOIN fin_user_to_position fntp ON fp.id=fntp.position_id
									LEFT JOIN fin_users fu ON fntp.user_id=fu.id WHERE fu.id=' . $user_id);
		return $query->getResultArray();
	}

	public static function get_positions_with_fields($user_id = null)
	{
		$db = Config\Database::connect();
		$where = '';
		if (!empty($user_id)) {
			$where = ' WHERE fu.id=' . $user_id;
		}
		$query = $db->query('SELECT fp.id, fp.access, fp.salary_amount, fp.salary_currency, fp.work_days FROM `fin_positions` fp
									LEFT JOIN fin_user_to_position fntp ON fp.id=fntp.position_id
									LEFT JOIN fin_users fu ON fntp.user_id=fu.id' . $where);
		return $query->getResultArray();
	}


	public static function get_user_ids_by_positions($position_ids)
	{
		$user_ids = [];
		if (!empty($position_ids)) {
			$db = Config\Database::connect();
			if (is_array($position_ids)) {
				$position_ids = implode(',', $position_ids);
			}
			$query = $db->query('SELECT fu.id FROM `fin_users` fu
										LEFT JOIN fin_user_to_position fntp ON fntp.user_id=fu.id
										WHERE fntp.position_id IN (' . $position_ids . ')');
			$user_ids = $query->getResultArray();
		}
		return $user_ids;
	}

	public
	static function control_finance($user_id)
	{ // директор, фінансист, бухгалтер
		if (in_array(2, self::get_positions($user_id)) ||
			in_array(12, self::get_positions($user_id))
//			|| !in_array(14, self::get_positions($user_id))
		) {
			return true;
		} else {
			return false;
		}
	}

	public
	static function finance_manager($user_id)
	{
		if (in_array(12, self::get_positions($user_id))) {
			return true;
		} else {
			return false;
		}
	}

	public
	static function change_accesses($position_id, $accesses)
	{
		if (is_array($accesses)) {
			$accesses = implode(', ', $accesses);
		}
		$db = Config\Database::connect();
		$query = $db->query('UPDATE `fin_positions` SET `access`="' . htmlspecialchars($accesses) . '" WHERE id = ' . $position_id);

		$query->getResult();
		return ['status' => 'ok', 'id' => $db->insertID()];
	}

	public static function delete($position_id)
	{
		DBHelp::delete('fin_positions', $position_id);
		return ['status' => 'ok'];
	}

	public
	static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['fp' => 'fin_positions'],
			'columns' => [
				'id',
				'name',
				'subordination',
				'access',
				'department_id',
				'salary_amount',
				'salary_currency',
				'department_id',
				'potential_amount_of_workers',
				'work_days',
				'account_id'
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

		if (in_array('fin_departments', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fd' => 'fin_departments'],
				'main_table_key' => 'department_id',
				'columns' => [],
				'columns_with_alias' => ['name' => 'department_name']
			];
		}

		if (in_array('fin_user_to_position', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['futp' => 'fin_user_to_position'],
				'new_column' => 'users',
				'main_table_key' => 'id',
				'other_table_key' => 'position_id',
				'columns' => ['user_id'],
				'columns_with_alias' => [

				],
				'join' => [
					[
						'type' => 'LEFT JOIN',
						'table' => ['fu' => 'fin_users'],
						'main_table_key' => 'user_id',
						'columns' => ['id', 'name', 'surname'],
						'columns_with_alias' => [
//						'name' => 'user_name',
//						'surname' => 'user_surname',
						]
					],
					[
						'type' => 'LEFT JOIN',
						'table' => ['fc' => 'fin_contractors'],
						'other_table_key' => 'fu.id',
						'joined_table_key' => 'user_id',
						'columns' => ['telegram_chat_id'],
						'columns_with_alias' => [
//						'name' => 'user_name',
//						'surname' => 'user_surname',
						]
					]
				]
			];
		}

		if (in_array('fin_accesses', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fa' => 'fin_accesses'],
				'new_column' => 'accesses',
				'main_table_key' => 'access',
				'other_table_key' => 'id',
				'type' => 'in',
				'columns' => ['name', 'type'],
				'columns_with_alias' => [

				]
			];
		}

		return $params;
	}
}

