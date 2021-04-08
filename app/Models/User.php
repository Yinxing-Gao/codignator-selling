<?php


namespace App\Models;

use Config;

class User
{
	public static function get_users($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(array_merge([], $join), array_merge([], $has_many));

		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_user($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(array_merge([], $join), array_merge([], $has_many));

		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single');
	}

	public static function login($login, $password)
	{
		$salt = '_salt';
		$params = [
			'table' => ['fu' => 'fin_users'],
			'where' => [
				'login = "' . $login . '"',
				'password = "' . sha1($password . $salt) . '"',
				'status = "active"'
			],
		];
		$result = DBHelp::select($params, 'single');

		if ($result['status'] == 'ok' && !empty($result['result'])) {
			$user = $result['result'];
			$user_id = $user->id;
			$session = Config\Services::session();
			$session->set('user_id', $user_id);
			$session->set('account_id', $user->account_id);
			return self::update($user_id, [
				'last_activity' => time(),
				'login_time'=> (int)$user->login_time + 1,
				'human_last_activity' => date("d.m.Y", time())
			]);
		} else {
			return ['status' => 'error', 'message' => 'Неправильний логін або пароль'];
		}
	}

	public static function set_login_pass($id, $login, $new_password)
	{
		$salt = '_salt';
		return self::update($id, [
			'password' => sha1($new_password . $salt),
			'login' => $login
		]);
	}

	public static function getUser($id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM `fin_users` WHERE id= "' . $id . '"');
		$user = $query->getFirstRow();
		if (!empty($user)) {
			return ['status' => 'ok', 'message' => 'Успішний вхід', 'user' => $user];
		} else {
			return ['status' => 'error', 'message' => 'Неправильний логін або пароль'];
		}
	}


	public static function get_active_users()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM `fin_users` WHERE status="active"');
		return $query->getResultArray();
	}

	public static function get_users_with_wallets()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT  fu.`id`,  fu.`name`, `surname`, `status`, fw.name as wallet
									FROM `fin_users` fu
									LEFT JOIN fin_wallets fw ON fw.user_id=fu.id
									WHERE status = "active"
									GROUP by fu.id');
		return array_filter($query->getResultArray(), function ($user) {
			if (!empty($user['wallet'])) {
				return $user;
			}
		});
	}

	public static function get_users_with_professions($params = [], $join = [])
	{
		$users = self::get_users($params = [], $join = []);
		if (!empty($users)) {
			foreach ($users as &$user) {
//				$user['professions'] = Position::get_professions(['where'=>['id IN (' . $user]])
			}
		}
		return $users;
	}

	public static function get_user_departments_ids($user_id)
	{
		$user = self::get_user(['where' => ['id = ' . $user_id]], [], ['fin_user_to_position'])['result'];
		$departments_ids = [];
		if (!empty($user) && !empty($user->positions)) {
			foreach ($user->positions as $position) {
				$departments_ids[] = $position['department_id'];
			}
		}
		return $departments_ids;
	}

	public static function add($user)
	{
		$salt = '_salt';
		$result = DBHelp::insert('fin_users', [
			'login' => !empty($user['login']) ? $user['login'] : '',
			'name' => !empty($user['name']) ? $user['name'] : '',
			'surname' => !empty($user['surname']) ? $user['surname'] : '',
			'email' => !empty($user['email']) ? $user['email'] : '',
			'phone' => !empty($user['phone']) ? $user['phone'] : '',
			'status' => !empty($user['status']) ? $user['status'] : 'active',
			'language' => !empty($user['language']) ? $user['language'] : 'ua',
			'google_id' => !empty($user['google_id']) ? $user['google_id'] : '',
			'account_id' => !empty($user['account_id']) ? $user['account_id'] : 0,
			'password'=> !empty($user['password']) ? sha1($user['password'] . $salt) : '',
			'last_activity' => time(),
			'login_time'=> 0,
			'human_last_activity' => date('d.m.Y H:i:s')
		]);

		if ($result['status'] == 'ok') {
			self::create_registration_defaults($result['id'], $user);
		}

		return $result;
	}

	//сутності, що створюються автоматично при створенні нового користувача
	public static function create_registration_defaults($user_id, $user)
	{
		$result1 = Contractors::add([
			'name' => $user['name'] . ' ' . $user['surname'],
			'contractor_type' => 'employee',
			'user_id' => $user_id,
			'account_id' => $user['account_id']
		]);

		$result2 = Wallets::add([
			"name" => "готівка",
			"currency" => "UAH",
			"user_id" => $user_id,
			"type_id" => 1,
			"wallet_type" => 'cash',
			"checkout" => 0,
			"planned_checkout" => 0,
			'is_shown' => 1,
			'is_default' => 1
		]);
	}

	public static function update($id, $params)
	{
		return DBHelp::update('fin_users', $id, $params);
	}

	public static function search($query, $params = [])
	{
		$session = Config\Services::session();
		return self::get_users(DBHelp::params_merge(['where' => [
			'(name LIKE "%' . trim($query) . '%" OR surname  LIKE "%' . trim($query) . '%")',
			'account_id = ' . $session->get('account_id'),
			'status = "active"'
		]], $params));
	}

	public static function get_current_user_id()
	{
		$session = Config\Services::session();
		return $session->get('user_id');
	}

	public
	static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['fu' => 'fin_users'],
			'columns' => [
				'id',
				'login',
				'password',
				'name',
				'surname',
//				'telegram_chat_id',
				'status',
				'language',
				'last_activity',
				'human_last_activity',
				'account_id',
				'login_time'
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

		if (in_array('fin_contractors', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fc' => 'fin_contractors'],
				'main_table_key' => 'id',
				'joined_table_key' => 'user_id',
				'columns' => ['contractor_type', 'telegram_chat_id'],
				'columns_with_alias' => [
					'name' => 'contractor_name'
				]
			];
		}

		if (in_array('fin_user_to_position', $has_many_keys)) {
			$params['has_many'][] = [
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
					], [
						'type' => 'LEFT JOIN',
						'table' => ['fd' => 'fin_departments'],
						'other_table_key' => 'fp.department_id',
						'columns' => [],
						'columns_with_alias' => [
							'name' => 'department_name',
						]
					]
				]
			];
		}

		return $params;
	}
}
