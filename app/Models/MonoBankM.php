<?php


namespace App\Models;
require 'vendor/autoload.php';

use Config;
use Exception;
use Monobank\Monobank;
use DateTime;
use Monobank\Exception\MonobankException;

class MonoBankM
{
	private static $api_url = "https://api.monobank.ua/";

	private static $currencies_codes = [
		980 => 'UAH',
		840 => 'USD',
		978 => 'EUR'
	];

	public static function get_card_balance($wallet_id)
	{
		try {
			$wallet = Wallets::get_wallet(['where' => [
				'fw.id =' . $wallet_id
			]]);
			$result = false;
			$card_balance = null;
			$card_currency = null;
			$fin_limit = null;
			if (!empty($wallet)) {
				$monobank = new Monobank($wallet->merchant_code);
				$accounts = $monobank->personal->getClientInfo()->accounts();
				if (!empty($accounts)) {
					foreach ($accounts as $account) {

						if ($wallet->currency == self::$currencies_codes[$account->currencyCode()]) {
							$result = true;
							$card_balance = (float)$account->balance() / 100;
							$card_currency = self::$currencies_codes[$account->currencyCode()];
							$fin_limit = (float)$account->creditLimit() / 100;
						}
					}
				}
			}
			if ($result) {
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
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => $e];
		}
	}

	public static function get_accounts($token)
	{
		$monobank = new Monobank($token);
		$accounts = $monobank->personal->getClientInfo()->accounts();
		$accounts_array = [];
		if (!empty($accounts)) {
			foreach ($accounts as $account) {
				$accounts_array[] = [
					'card_id' => $account->id(),
					'card_balance' => (float)$account->balance() / 100,
					'card_currency' => self::$currencies_codes[$account->currencyCode()],
					'fin_limit' => (float)$account->creditLimit() / 100
				];
			}
		}
		return ['status' => 'ok', 'accounts' => $accounts_array];
	}

	public static function get_card_operations($wallet_id)
	{
		try {
			$wallet = Wallets::get_wallet(['where' => [
				'fw.id =' . $wallet_id
			]]);
			$operations = [];
			if (!empty($wallet)) {
				$monobank = new Monobank($wallet->merchant_code);
				$statements = $monobank->personal->getStatement($wallet->merchant_id, new DateTime(date('d.m.Y H:i:s', time() - 60 * 16)))->statements();

				if (!empty($statements)) {
					foreach ($statements as $statement) {

						$operations[] = [
							'bank_operation_id' => $statement->id(),
							'date' => $statement->time()->getTimestamp(),
							'human_date' => date('d.m.Y', $statement->time()->getTimestamp()),
							'comment' => $statement->description(),
							'contractor' => $statement->description(),
							'amount' => $statement->amount() / 100,
//							'amount_' => $statement->operationAmount(),
							'checkout' => $statement->balance() / 100,
							'operation_type_id' => $statement->amount() > 0 ? 1 : 2,
							'mcc' => $statement->mcc(),
							'currency' => self::$currencies_codes[$statement->currencyCode()],
							'wallet_id' => $wallet_id,
							'bank_id' => 2
						];
					}
				}
				return [
					'status' => 'ok',
					'operations' => $operations,
				];
			} else {
				return [
					'status' => 'error',
					'message' => ''
				];
			}
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => $e];
		} catch (MonobankException $e) {
			return ['status' => 'error', 'message' => $e];
		}
	}
}
