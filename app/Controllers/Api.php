<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;
use function MongoDB\BSON\toJSON;


class Api extends BaseController
{
	public static function new_lead()
	{
		header('Access-Control-Allow-Origin: https://landing.fineko.space');

		if (!empty($_POST['fineko_key']) && !empty($_POST['source']) && !empty($_POST['name'])) {
			$account = Models\Account::get_account(['where' => [
				'api_key = "' . $_POST['fineko_key'] . '"'
			]]);
			if (!empty($account)) {
				$params = [
					'name' => $_POST['name'],
					'product_id' => !empty($_POST['product_id']) ? $_POST['product_id'] : 0,
					'amount' => !empty($_POST['amount']) ? $_POST['amount'] : 0,
					'currency' => !empty($_POST['currency']) ? $_POST['currency'] : 'UAH',
					'source' => $_POST['source'],
					'contacts' => [],
					'department_id' => !empty($_POST['department_id']) ? $_POST['department_id'] : 0,
					'account_id' => $account->id,
				];

				if (!empty($_POST['phone'])) {
					$params['contacts'][] = [
						'name' => 'Телефон',
						'type' => 'phone',
						'value' => $_POST['phone']
					];
				}

				$result = Models\Marketing::add($params);
				if ($result['status'] == 'ok') {

					$text = "<b>Отримано нову заявку</b>" . " \r\n" .
						"<b>Ім'я</b> - " . $_POST['name'] . " \r\n" .
						"<b>Телефон</b> - " . $_POST['phone'] . " \r\n" .
						"<b>Джерело</b> - " . $_POST['source'] . " \r\n";

					Models\Telegram::start();
					$positions = Models\Position::get_positions(['where' => [
						'account_id = ' . $account->id
					]], [], ['fin_user_to_position', 'fin_accesses']);

					if (!empty($positions)) {
						foreach ($positions as $position) {
							if (!empty($position['accesses'] && !empty($position['users']))) {
								foreach ($position['accesses'] as $access){
									if ($access['name'] == 'new lead') {
										foreach ($position['users'] as $user) {
											Models\Telegram::send_message($user['telegram_chat_id'], $text);
										}
									}
								}
							}
						}
					}
//				Models\Telegram::send_admin_message($text);
//				Models\Telegram::send_message(226882129, $text);
					return \GuzzleHttp\json_encode(['status' => 'ok']);
				}
			}

		}
		return \GuzzleHttp\json_encode(['status' => 'error']);
	}
}
