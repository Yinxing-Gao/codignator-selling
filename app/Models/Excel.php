<?php

namespace App\Models;
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use Config;

class Excel
{
	public static function get_table($type_id = null, $period = null, $department_id = null)
	{

	}

	public static function upload_users($name)
	{
		//todo зробити, щоб в базу попадали професії, ставки і департаменти
		$new_users = self::open_excel($name);
		$success = 0;
		$errors = 0;
		$success2 = 0;
		$errors2 = 0;
		if ($new_users['status'] == 'ok' && !empty($new_users['data'])) {
			$new_users = $new_users['data'];
			foreach ($new_users as $new_user) {
				$result = User::add($new_user);
				if ($result['status'] == 'ok') {
					$success++;
					$result2 = Contractors::add([
						'name' => $new_user['name'] . ' ' . $new_user['surname'],
						'phone' => $new_user['phone'],
						'user_id' => $result['id']
					]);
					if ($result2['status'] == 'ok') {
						$success2++;
					} else {
						$errors2++;
					}
				} else {
					$errors++;
				}
			}
		}
		return ['status' => 'ok', 'message' => 'Користувачів збережено: ' . $success . ', помилок: ' . $errors . ', контрагентів добавлено: ' . $success2 . ', помилок: ' . $errors2];
	}

	public static function upload_spec($name, $specification_id)
	{
		//todo зробити, щоб в базу попадали професії, ставки і департаменти
		$spec = self::open_excel($name);

		$success = 0;
		$errors = 0;
		$success2 = 0;
		$errors2 = 0;

		$storage_names = Storage::get_storage_names([
			'where' => [
				'account_id = ' . Account::get_current_account_id()
			]
		]);
		if ($spec['status'] == 'ok' && !empty($spec['data'])) {

			$spec = $spec['data'];

			foreach ($spec as $spec_item) {
				$atts = [
					'specification_id' => $specification_id,
					'amount' => $spec_item['amount']
				];

				switch ($spec_item['type']) {
					case 'product':
						$atts['type'] = 'storage_name';
						$found = false;
						if (!empty($storage_names)) {
							foreach ($storage_names as $storage_name) {
								if ($storage_name['article'] == $spec_item['article']) {
									$atts['storage_name_id'] = $storage_name['id'];
									$found = true;
								}
							}
						}
						if (!$found) {
							$new_storage_name = Storage::add_storage_name([
								'name' => $spec_item['name'],
								'article' => $spec_item['article'],
								'buy_price' => $spec_item['price'],
								'currency' => $spec_item['currency'],
								'description' => $spec_item['name'],
								'storage_id' => Settings::get('main_storage_id')
							]);
							if ($new_storage_name['status'] == 'ok') {
								$atts['storage_name_id'] = $new_storage_name['id'];
								$success++;
							} else {
								$errors++;
							}
						}
						break;
					default:
						$atts['type'] = 'service';
						$found = false;
						if (!empty($storage_names)) {
							foreach ($storage_names as $storage_name) {
								if ($storage_name['article'] == $spec_item['article']) {
									$atts['storage_name_id'] = $storage_name['id'];
									$found = true;
								}
							}
						}
						if (!$found) {
							$new_storage_name = Storage::add_storage_name([
								'name' => $spec_item['name'],
								'article' => $spec_item['article'],
								'buy_price' => $spec_item['price'],
								'currency' => !empty($spec_item['currency']) ? $spec_item['currency'] : 'UAH',
								'description' => $spec_item['name'],
								'storage_id' => Settings::get('service_storage_id')
							]);
							if ($new_storage_name['status'] == 'ok') {
								$atts['storage_name_id'] = $new_storage_name['id'];
								$success++;
							} else {
								$errors++;
							}
						}
						break;
				}
				$result = Production::add_update_specification_item($specification_id, $atts); //todo продовжити з цього місця

				if ($result['status'] == 'ok') {
					$success2++;
				} else {
					$errors2++;
				}
			}
		}
		return ['status' => 'ok', 'message' => 'Позицій на складі збережено: ' . $success . ', помилок: ' . $errors . ', позицій в специфікацію добавлено: ' . $success2 . ', помилок: ' . $errors2];
	}

	public static function open_excel($name)
	{
		try {
			$reader = IOFactory::createReader("Xlsx");
			$spreadsheet = $reader->load($name);
			$sheet = $spreadsheet->getActiveSheet();
			$rows = $sheet->getHighestDataRow();
			$keys_amount = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
			$keys = [];
			for ($i = 1; $i <= $keys_amount; $i++) {
				$keys[$i] = strtolower($sheet->getCellByColumnAndRow($i, 1)->getValue());
			}
			$data = [];
			for ($j = 2; $j <= $rows; $j++) { //рядки
				$data_row = [];
				for ($i = 1; $i <= $keys_amount; $i++) {//колонки
					$data_row[$keys[$i]] = $sheet->getCellByColumnAndRow($i, $j)->getValue();
				}
				$data[] = $data_row;
			}
			return ['status' => 'ok', 'message' => 'success', 'data' => $data];
		} catch (Exception $e) {
			return ['status' => 'error', 'message' => $e];
		}

	}
}
