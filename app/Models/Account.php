<?php


namespace App\Models;

use Config;

class Account
{
	public static function get_accounts($params = [], $join = [])
	{
		$query_params = self::select_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_account($params = [], $join = [])
	{
		$query_params = self::select_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public static function add($atts)
	{
		$salt = '_salt';
		$params = [
			'name' => !empty($atts['name']) ? $atts['name'] : '',
			'type' => !empty($atts['type']) ? $atts['type'] : 'demo',
			'status' => !empty($atts['status']) ? $atts['status'] : 'active',
			'ref' => !empty($atts['name']) ? sha1($atts['name'] . $salt) : sha1(time() . $salt),
			'api_key' => !empty($atts['name']) ? sha1($atts['name'] . $salt) : sha1(time() . $salt),
			'payed_to' => !empty($atts['payed_to']) ? $atts['payed_to'] : time() + 60 * 60 * 24 * 14
		];
		return DBHelp::insert('fin_accounts', $params);
	}

	public static function get_current_account_id()
	{
		$session = Config\Services::session();
		$account_id = $session->get('account_id');
		if (!empty($account_id)) {
			return $account_id;
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public static function create($info)
	{
		$session = Config\Services::session();
		$account_ref = $session->get('account_ref');
		$account_id = 0;

		if (!empty($account_ref)) {
			$account = Account::get_account(['where' => [
				'ref = "' . $account_ref . '"'
			]]);
			$account_id = $account->id;
		}

		if (empty($account)) {
			$account_result = Account::add([
				'name' => $info['name']
			]);
			$account_id = $account_result['status'] == 'ok' ? $account_result['id'] : 0;
			Telegram::send_admin_message("Створено новий акаунт " . $info['name']);
		}

		$user_add_result = User::add([
			'google_id' => !empty($info['google_id']) ? $info['google_id'] : 0,
			'email' => $info['email'],
			'login' => !empty($info['login']) ? $info['login'] : '',
			'name' => $info['name'],
			'surname' => $info['surname'],
			'password' => !empty($info['password']) ? $info['password'] : '',
			'locale' => !empty($info['locale']) ? $info['locale'] : 'ua',
			'account_id' => $account_id
		]);

		if (empty($account) && $user_add_result['status'] == 'ok') {
			Account::create_registration_defaults($account_id, $user_add_result['id']);
		}

		if (!empty($info['password']) && !empty($info['login'])) {
			User::login(htmlspecialchars_decode(trim($info['login'])), htmlspecialchars_decode(trim($info['password'])));
		}

		return $user_add_result;
	}

	//сутності, що створюються автоматично при створенні нового акаунту
	public static function create_registration_defaults($account_id, $user_id)
	{
		$department_add_result = Departments::add([
			'name' => 'Компанія',
			'is_shown' => 0,
			'account_id' => $account_id,
			'is_default' => 1
		]);

		if ($department_add_result['status'] == 'ok') {
			$position_add_result = Position::add([
				'name' => 'Власник',
				'access' => '1, 2, 3, 4, 38, 39, 30, 7, 8, 9, 40, 41, 42, 57, 32, 51, 43, 44, 16, 34, 13, 14',
				'work_days' => '1,2,3,4,5',
				'department_id' => $department_add_result['id'],
				'account_id' => $account_id
			]);

			if ($position_add_result['status'] == 'ok') {
				Position::change_users($position_add_result['id'], [$user_id], $account_id);
			}
		}

		Settings::add(['name' => 'finance_day', 'value' => 'Tuesday', 'account_id' => $account_id]);
		Settings::add(['name' => 'lead_responsible', 'value' => $user_id, 'account_id' => $account_id]);

		$task1 = Questionnaire::add([
			'name' => 'what the company does',
			'user_id' => $user_id,
			'priority' => 1
		]);

		$task2 = Questionnaire::add([
			'name' => 'what are departments',
			'user_id' => $user_id,
			'after_id' => $task1['id'],
			'priority' => 1
		]);

		Questionnaire::add([
			'name' => 'what are departments managers',
			'user_id' => $user_id,
			'after_id' => $task2['id'],
			'priority' => 1
		]);

		$storage1 = Storage::add([
			'name' => "Головний склад",
			'type' => 'materials',
			'account_id' => $account_id
		]);

		if ($storage1['status'] == 'ok') {
			Settings::add([
				'name' => 'main_storage_id',
				'value' => $storage1['id'],
				'account_id' => $account_id
			]);
		}

		$storage2 = Storage::add([
			'name' => "Підрядні роботи",
			'type' => 'services',
			'account_id' => $account_id
		]);

		if ($storage2['status'] == 'ok') {
			Settings::add([
				'name' => 'service_storage_id',
				'value' => $storage2['id'],
				'account_id' => $account_id
			]);
		}
	}

	public static function select_query($join_keys = [])
	{
		return [
			'table' => ['fa' => 'fin_accounts'],
			'columns' => [
				'id',
				'name',
				'type',
				'status',
				'ref',
				'api_key',
				'payed_to'
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
	}
}
