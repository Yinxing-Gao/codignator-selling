<?php namespace App\Controllers;

use App\Models;
use Config;

class User extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return view('application', [
				'user' => $this->user,
				'access' => $this->access,
			]);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function login()
	{
		return $this->view('user/login', [
			'google_url' => Models\Google::get_registration_url(),
			'css' => ['login'],
			'js' => ['jquery-3.4.1.min', 'login']
		], 'out');
	}

	public function password($id)
	{
		return view('password', [
			'id' => (int)$id / 12345,
			'js' => ['login']
		]);
	}

	public function passwordAjax($id)
	{
		$login = htmlspecialchars_decode(trim($_POST['login']));
		$password = htmlspecialchars_decode(trim($_POST['password']));
		echo json_encode(Models\User::set_login_pass($id, $login, $password));
	}

	public function logout()
	{
		$session = Config\Services::session();
		$session->destroy();
		header('Location: ' . base_url() . 'user/login');
	}

	public function registration($account_ref = null)
	{
		if (!empty($account_ref)) {
			$session = Config\Services::session();
			$session->set('account_ref', $account_ref);
		}
		return $this->view('user/registration', [

			'google_url' => Models\Google::get_registration_url(),
			'css' => ['registration'],
			'js' => ['jquery-3.4.1.min', 'login']
		], 'out');
	}

	public function list()
	{
		if (!empty($this->user_id)) {
			return $this->view('user/list', [
				'user_list' => Models\User::get_users(['where' => [
					'fu.account_id = ' . $this->account_id
				]], [], ['fin_user_to_position']),
				'css' => ['table', 'users']
			]);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public static function google()
	{
		if (!empty($_GET['code'])) {
			$result = Models\Google::registration($_GET['code']);

			if ($result['status'] == 'ok') {
				$session = Config\Services::session();
				$session->set('user_id', $result['id']);
				$user_result = Models\User::get_user(['where' => [
					'id = ' . $result['id']
				]]);

				if ($user_result['status'] == 'ok' && !empty($user_result['result'])) {
					$user = $user_result['result'];
					Models\User::update($result['id'], [
						'last_activity' => time(),
						'human_last_activity' => date('d.m.Y'),
						'login_time' => (int)$user->login_time + 1
					]);

					$session->set('user_id', $user->id);
					$session->set('account_id', $user->account_id);
					header('Location: ' . base_url());
					exit;
				}
			}
		}
	}


	public function login_ajax()
	{
		$login = htmlspecialchars_decode(trim($_POST['login']));
		$password = htmlspecialchars_decode(trim($_POST['password']));
		echo json_encode(Models\User::login($login, $password));
	}

	public function registration_ajax()
	{
		if (!empty($_POST['login']) &&
			!empty($_POST['name']) &&
			!empty($_POST['surname']) &&
			!empty($_POST['password']) &&
			!empty($_POST['password2'])) {
			echo json_encode(Models\Account::create([
				'login' => $_POST['login'],
				'email' => !empty($_POST['email']) ? $_POST['email'] : '',
				'name' => $_POST['name'],
				'surname' => $_POST['surname'],
				'password' => $_POST['password']
			]));
		} else echo \GuzzleHttp\json_encode(['status' => 'error', 'message' => 'empty fields']);
	}

	public function get_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		echo json_encode(Models\User::search($query, []));
	}
}
