<?php


namespace App\Models;

use Config;
use Exception;

class PrivatBank
{
	private static $p24business_api_url = "https://acp.privatbank.ua/api/";
	private static $p24_api_url = "https://api.privatbank.ua/p24api/";

	//https://api.privatbank.ua/#p24/balance

	public static function get_card_balance($wallet_id, $merchant_id = null, $merchant_code = null)
	{

		if (!empty($wallet_id) || (!empty($merchant_id) && !empty($merchant_code))) {
			$wallet = Wallets::get_wallet(['where' => [
				'fw.id =' . $wallet_id
			]]);
			$merchant_code = !empty($merchant_id) && !empty($merchant_code) ? $merchant_code : $wallet->merchant_code;
			$merchant_id = !empty($merchant_id) && !empty($merchant_code) ? $merchant_id : $wallet->merchant_id;
			$data = '<oper>cmt</oper>
					<wait>0</wait>
					<test>0</test>
					<payment id=""></payment>';
			$password = $merchant_code;
			$sign = sha1(md5($data . $password));

			$xml = '<?xml version="1.0" encoding="UTF-8"?>
            <request version="1.0">
                <merchant>
                    <id>' . $merchant_id . '</id>
                    <signature>' . $sign . '</signature>
                </merchant>
                <data>'
				. $data
				. '</data>
                </request>';

			$result = Requests::postRequestJson(self::$p24_api_url . 'balance', $xml, [], 'xml');

			if (!empty($result['response']->data->info->cardbalance && $result['response']->data->info->cardbalance->card->currency)) {
				$card_currency = $result['response']->data->info->cardbalance->card->currency;
				$fin_limit = (float)$result['response']->data->info->cardbalance->fin_limit;
				$card_balance = $fin_limit + (float)$result['response']->data->info->cardbalance->balance;
				if (!empty($wallet)) {
					if ($wallet->currency != $card_currency) {
						Notifications::add($wallet->user_id, 'Валюта вашої карти <b>' . $wallet->name . '</b> не співпадає з валютою каси в FINEKO');
					}
					if ($wallet->checkout != $card_balance) {
						Notifications::add($wallet->user_id, 'Реальний баланс вашої карти <b>' . $wallet->name . '</b> не співпадає з даними в FINEKO. Будь ласка, перевірте введені операції');
					}
				}
				return [
					'status' => 'ok',
					'balance' => $card_balance,
					'currency' => $card_currency,
					'fin_limit' => $fin_limit

				];
			} else {
				return [
					'status' => 'error',
					'message' => ''
				];
			}
		}
	}

	public static function get_card_operations($wallet_id)
	{
		$wallet = Wallets::get_wallet(['where' => [
			'fw.id =' . $wallet_id
		]]);
		if (!empty($wallet)) {
			$data = '<oper>cmt</oper>
					 <wait>0</wait>
					 <test>0</test>
					 <payment id="">
					 	<prop name="sd" value="' . date('d.m.Y', time() - 60 * 60 * 24 * 7) . '" />
                        <prop name="ed" value="' . date('d.m.Y') . '" />
                      </payment>';
			$password = $wallet->merchant_code;
			$sign = sha1(md5($data . $password));

			$xml = '<?xml version="1.0" encoding="UTF-8"?>
            <request version="1.0">
                <merchant>
                    <id>' . $wallet->merchant_id . '</id>
                    <signature>' . $sign . '</signature>
                </merchant>
                <data>'
				. $data
				. '</data>
                </request>';

			$result = Requests::postRequestJson(self::$p24_api_url . 'rest_fiz', $xml, [], 'xml');
			$operations = [];
			if (!empty($result['response']->data->info->statements->statement)) {
				foreach ($result['response']->data->info->statements->statement as $statement_obj) {
					$statement_arr = (array)$statement_obj;

					if (key_exists("@attributes", $statement_arr)) {
						$statement = (array)$statement_arr["@attributes"];
					} else {
						$statement = $statement_arr;
					}

					$amount_arr = explode(' ', $statement['cardamount']);
					$amount = !empty($amount_arr[0]) ? trim($amount_arr[0]) : 0;
					$currency = !empty($amount_arr[1]) ? trim($amount_arr[1]) : 0;
					$rest_arr = explode(' ', $statement['rest']);
					$checkout = !empty($rest_arr [0]) ? trim($rest_arr [0]) : 0;

					$operations[] = [
						'bank_operation_id' => $statement['appcode'],
						'date' => strtotime($statement['trandate'] . ' ' . $statement['trantime']),
						'human_date' => $statement['trandate'] . ' ' . $statement['trantime'],
						'comment' => $statement['description'],
						'wallet_checkout' => $checkout,
						'contractor' => $statement['terminal'],
						'amount' => trim($amount, '-'),
						'operation_type_id' => (float)$amount > 0 ? 1 : 2,
						'currency' => $currency,
						'wallet_id' => $wallet_id,
						'bank_id' => 1
					];
				}
				return [
					'status' => 'ok',
					'operations' => $operations,
				];
			} else {
				return [
					'status' => 'error',
					'message' => '',
					'operations' => $operations
				];
			}
		}
	}


//https://docs.google.com/document/d/e/2PACX-1vTtKvGa3P4E-lDqLg3bHRF6Wi9S7GIjSMFEFxII5qQZBGxuTXs25hQNiUU1hMZQhOyx6BNvIZ1bVKSr/pub
	public
	static function method($method = 'transactions', $params = [])
	{
		$account_number = !empty($params['account_number']) ? $params['account_number'] : 'UA443007110000026001052676691';
		$params_str = '';
		if (!empty($params['start_date']) && !empty($params['end_date'])) {
			$params_str = '?acc=' . $account_number . '&startDate=' . date("d-m-Y", $params['start_date']) . '&endDate=' . date("d-m-Y", $params['end_date']);
		} elseif (!empty($params['type'])) {
			$params_str = '/' . $params['type'] . '?acc=' . $account_number;
		} else {
			$params_str = '/today?acc=' . $account_number;
		}
		if (!empty($params['id_trn'])) {
			$params_str .= '&id_trn=' . $params['id_trn'];
		}
		$api_url = self::$p24business_api_url . 'proxy/' . $method . $params_str;
		echo $api_url;
		echo "<br/>";
		echo "<br/>";

//		return self::apiRequestJson($api_url);

		return Requests::postRequestJson($api_url, [], [
			"User-Agent" => "FinEko",
			"id" . self::$id,
			"token: " . self::$token,
			"Content-Type" => "application/json;charset=utf8"
		]);
	}
}
