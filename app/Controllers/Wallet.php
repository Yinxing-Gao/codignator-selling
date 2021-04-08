<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Wallet extends BaseController
{
	public function index() //мої каси
	{
		if (!empty($this->user_id)) {
			return $this->view('wallet/list',
				[
//					'user' => $this->user,
//					'access' => $this->access,
					'wallets' => Models\Wallets::get_wallets(['where' =>
						['user_id = ' . $this->user_id]],
						['fin_departments']
					),
					'balances' => Models\Wallets::get_card_balances($this->user_id),
					'banks' => Models\Banks::get_banks(),
					'currencies_names' => Models\CurrencyRate::get_currencies_names(),
					'notifications' => $this->notifications,
					'entity_types' => [
						1 => 'каса фіз.особи',
						2 => 'безнал ТОВ',
						3 => 'ФОП'
					],
					'forms' => [
						'cash' => 'готівка',
						'card' => 'карта'
					],
					'css' => ['table', 'wallet'],
					'js' => ['wallet']
				] //дописати захисти,
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

//	public function checkout()
//	{
////		if (!empty($this->user_id)) {
////			return view('checkouts',
////				[
////					'user' => $this->user,
////					'access' => $this->access,
////					'wallets' => Models\Wallets::get_actual_user_balance($id)
////				] //дописати захисти,
////			);
////		} else {
////			header('Location: ' . base_url() . 'user/login');
////			exit;
////		}
//		$wallets = Models\Wallets::get_wallets();
//		if (!empty($wallets)) {
//			foreach ($wallets as $wallet) {
//				Models\Wallets::set_checkouts($wallet['id']);
//			}
//		}
//
//	}

	public function user($user_id)
	{
		if (!empty($this->user_id)) {
			return view('wallet',
				[
					'user' => $this->user,
					'access' => $this->access,
					'wallets' => Models\Wallets::get_user_wallets($user_id),
					'currencies_names' => Models\CurrencyRate::get_currencies_names()
				] //дописати захисти,
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	//каси по департаментах
	public function department($with_children = true, $department_id = null)
	{
		$department_ids = Models\Departments::get_single_department_or_with_children($with_children);

		if (strlen($department_ids) > 0) {
			$wallets = Models\Wallets::get_wallets(['where' => [
				'fw.department_id IN (' . $department_ids . ')'
			]], ['fin_departments'], []);
		} else {
			$wallets = [];
		}

		if (!empty($this->user_id)) {
			return $this->view('wallet/department',
				[
					'with_children' => $with_children,
					'entity_types' => [
						1 => 'каса фіз.особи',
						2 => 'безнал ТОВ',
						3 => 'ФОП'
					],
					'forms' => [
						'cash' => 'готівка',
						'card' => 'карта'
					],
					'wallets' => $wallets,
					'currencies_names' => Models\CurrencyRate::get_currencies_names(),
					'css' => ['table', 'wallet'],
					'js' => ['wallet']
				] //дописати захисти,
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function refresh()
	{
		Models\Wallets::refresh();
		return redirect()->to('/operation');
	}

//	public function set_checkouts() //temp
//	{
//
//		$wallets = Models\Wallets::get_wallets();
//		if (!empty($wallets)) {
//			foreach ($wallets as $wallet) {
//				Models\Wallets::update_checkouts($wallet['id']);
//			}
//		}
//	}

	public function get_privat24_balance_ajax()
	{
		if (!empty($_POST)) {
			$merchant_id = $_POST['merchant_id'];
			$merchant_code = $_POST['merchant_code'];
			echo json_encode(Models\PrivatBank::get_card_balance(null, $merchant_id, $merchant_code));
		}
	}

	public
	function get_monobank_wallets_ajax()
	{
		if (!empty($_POST)) {
			$token = $_POST['token'];
			echo json_encode(Models\MonoBankM::get_accounts($token));
		}
	}

	public
	function add_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;

			echo json_encode(Models\Wallets::add($atts));
		}
	}


	public function delete_ajax($wallet_id)
	{
		echo json_encode(Models\Wallets::delete($wallet_id));
	}
}
