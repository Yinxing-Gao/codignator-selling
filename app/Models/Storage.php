<?php


namespace App\Models;

use Config;
use Exception;

class Storage
{
	public static function get_storages($params = [], $join = [])
	{
		$query_params = self::select_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_storage($storage_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM fin_storage fp WHERE id=' . $storage_id);
		return $query->getFirstRow();
	}

	public static function get_storage_names($params = [], $join = [])
	{
		$query_params = self::select_storage_names_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function add($atts)
	{
		if (!empty($atts['name'])) {
			$params = [
				'name' => !empty($atts['name']) ? $atts['name'] : '',
				'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
				'last_change_date' => time(),
				'parent_id' => !empty($atts['parent_id']) ? $atts['parent_id'] : 0,
				'type' => !empty($atts['type']) ? $atts['type'] : 'materials',
				'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : Account::get_current_account_id()
			];
			return DBHelp::insert('fin_storage', $params);
		}
	}

	public static function add_storage_name($atts)
	{
		if (!empty($atts['name'])) {
			$params = [
				'name' => !empty($atts['name']) ? $atts['name'] : '',
				'article' => !empty($atts['article']) ? $atts['article'] : '',
				'unit_id' => !empty($atts['unit_id']) ? $atts['unit_id'] : 1,
				'buy_price' => !empty($atts['buy_price']) ? $atts['buy_price'] : 0,
				'currency' => !empty($atts['currency']) ? $atts['currency'] : 'UAH',
				'min_amount' => !empty($atts['min_amount']) ? $atts['min_amount'] : 0,
				'description' => !empty($atts['description']) ? $atts['description'] : '',
				'storage_id' => !empty($atts['storage_id']) ? $atts['storage_id'] : 0,
				'photo' => !empty($atts['photo']) ? $atts['photo'] : '',
				'supplier_id' => !empty($atts['supplier_id']) ? $atts['supplier_id'] : 0,
				'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : Account::get_current_account_id()
			];
			return DBHelp::insert('fin_storage_names', $params);
		}
	}

	public static function get_storage_items($storage_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fsi.`id`, fsn.`name`, fsn.`unit_id`, fsi.`buy_price`, fsi.`amount`, fsn.`description`,fsi.`last_change_date`, fsi.`storage_id`, fu.name as unit, fs.name as storage_name FROM `fin_storage_names` fsn LEFT JOIN `fin_storage_items` fsi ON fsi.storage_name_id=fsn.id LEFT JOIN `fin_units` fu ON fsn.unit_id=fu.id LEFT JOIN `fin_storage` fs ON fs.id = fsi.storage_id WHERE fsi.storage_id = ' . $storage_id);
		$items = $query->getResultArray();
		return $items;
	}

//	public static function get_storage_names($storage_id)
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT fsn.`id`, fsn.`name`, `unit_id`, `buy_price`, `amount`, `description`, `storage_id`, fu.name as unit FROM `fin_storage_names` fsn LEFT JOIN `fin_units` fu ON fsn.unit_id=fu.id WHERE storage_id = ' . $storage_id);
//		return $query->getResultArray();
//	}


//	public static function get_account_storage_names($term, $account_id = null)
//	{
//		$account_id = isset($account_id) ? $account_id : Account::get_current_account_id();
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT fsn.`id`, fsn.`name`, fsn.`storage_id`, fs.account_id as account_id FROM `fin_storage_names` fsn LEFT JOIN `fin_storage` fs ON fsn.storage_id=fs.id WHERE fsn.name LIKE \'' . $term . '%\' AND  account_id = ' . $account_id);
//		return $query->getResultArray();
//	}

	public static function get_storage_apps($storage_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fsa.`id`, `storage_id`, CONCAT_WS(\' \', fu.name,fu.surname)  as author, `project_id`, `date`, `author_id`, `date_for`,fd.name as department, fsa.status
									FROM `fin_storage_applications`fsa
									LEFT JOIN fin_users fu ON fsa.author_id=fu.id
									LEFT JOIN fin_departments fd ON fsa.department_id=fd.id
									WHERE storage_id = ' . $storage_id);
		$apps = $query->getResultArray();
		if (!empty($apps)) {
			foreach ($apps as &$app) {
				$app['items'] = self::get_storage_app_items($app['id']);
			}
		}
		return $apps;
	}

	public static function get_storage_app_ids_by_source($project_id, $source)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fsa.`id`
									FROM `fin_storage_applications` fsa
									WHERE project_id = ' . $project_id . ' AND source = "' . htmlspecialchars(trim($source)) . '"');
		$apps = $query->getResultArray();
		$result = [];
		if (!empty($apps)) {
			foreach ($apps as $app) {
				$result[] = $app['id'];
			}
		}
		return $result;
	}


	public static function get_storage_app_items($storage_application_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fsai.`id`, fsai.`storage_name_id`, fsn.name, fsai.`amount`, `storage_application_id`, fsn.unit_id, fu.name as unit
									FROM `fin_storage_application_names` fsai
									LEFT JOIN fin_storage_names fsn ON fsn.id=fsai.storage_name_id
                                    LEFT JOIN fin_units fu ON fu.id= fsn.unit_id
									WHERE storage_application_id = ' . $storage_application_id);
		return $query->getResultArray();
	}

	public static function add_app($storage_id, $project_id, $department_id, $author_id, $date_for, $names, $source)
	{
		$db = Config\Database::connect();
		$storage_id = !empty($storage_id) ? $storage_id : 0;
		$project_id = !empty($project_id) ? $project_id : 0;
		$department_id = !empty($department_id) ? $department_id : 0;
		$date = time();
		$date_for = !empty($date_for) ? strtotime($date_for) : time() + 60 * 60 * 24 * 7;
		$author_id = !empty($author_id) ? $author_id : 0;
		$source = !empty($source) ? $source : '';
		$query = $db->query('INSERT INTO `fin_storage_applications`(`storage_id`, `project_id`, `date`, `author_id`, `date_for`, `department_id`, `status`, `source`)
									VALUES ("' . $storage_id . '", "' . $project_id . '", "' . $date . '", "' . $author_id . '", "' . $date_for . '", "' . $department_id . '", "new", "' . $source . '")');

		$query->getResult();
		if ($db->affectedRows() == 1) {
			$inserted_id = $db->insertID();
			if (!empty($names)) {
				foreach ($names as $name) {
					$query = $db->query('INSERT INTO `fin_storage_application_names`(`storage_name_id`, `amount`, `storage_application_id`)
								VALUES (' . $name['id'] . ',' . $name['amount'] . ',' . $inserted_id . ')');
					$query->getResult();
				}
			}

			if (date('d.m.Y', $date_for) == date('d.m.Y')) {
				$prof_ids = Position::get_user_ids_by_professions(11);
				if (!empty($prof_ids)) {
					foreach ($prof_ids as $prof_id) {
						Telegram::send_personal_message($prof_id['id'], 'Отримано нову актуальну заявку на склад. Перейдіть на сайт, щоб виконати замовлення' . " \r\n" . base_url('storage/applications/' . $storage_id, 'https'));

					}
				}
			}
			return ['status' => 'ok', 'id' => $inserted_id];
		} else {
			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
		}
	}
//
//	public static function get_names()
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT fsn.`id`, fsn.`name`, `unit_id`, `buy_price`, `amount`, `description`, `storage_id`, fu.name as unit FROM `fin_storage_names` fsn LEFT JOIN `fin_units` fu ON fsn.unit_id=fu.id');
//		return $query->getResultArray();
//	}

	public static function get_department_storage_names($department_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fsn.`id`, fsn.`name`, `unit_id`, `buy_price`, `amount`, `description`, `storage_id`, fu.name as unit
									FROM `fin_storage_names` fsn
									LEFT JOIN `fin_storage` fs ON fs.id=fsn.storage_id
									LEFT JOIN `fin_units` fu ON fsn.unit_id=fu.id
									WHERE department_id = ' . $department_id);
		return $query->getResultArray();
	}

	public static function get_name($name_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fsn.`id`, fsn.`name`, `unit_id`, `buy_price`,`currency`, `amount`, `description`, `storage_id`, `photo`, fu.name as unit
									FROM `fin_storage_names` fsn
									LEFT JOIN fin_units fu ON fu.id=fsn.unit_id
									WHERE fsn.id=' . $name_id);
		return $query->getFirstRow();
	}

	public static function add_item($atts, $storage_id)
	{
//		var_dump($atts);

		if (!empty($atts)) {
			$db = Config\Database::connect();
			if (!empty($atts['name'])) {
				$name = $atts['name'];
				$unit_id = $atts['unit_id'];
				$query = $db->query('INSERT INTO `fin_storage_names`(`name`, unit_id, `amount`, `storage_id`) VALUES ( "' . addslashes($name) . '", ' . $unit_id . ', 0, ' . $storage_id . ')');
				$query->getResult();
				if ($db->affectedRows() == 1) {
					return ['status' => 'ok', 'id' => $db->insertID()];
				} else {
					return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
				}
			} elseif (!empty($atts['data'])) {
				$items = preg_split('/\\r\\n?|\\n/', $atts['data']);
				$query_row = 'INSERT INTO `fin_storage_names`(`name`, unit_id, `amount`, `storage_id`) VALUES ';
				if (!empty($items)) {
					foreach ($items as $item) {
						$item_row = explode(',', $item);
						$name = !empty($item_row[0]) ? trim($item_row[0]) : '';
						$unit = !empty($item_row[1]) ? trim(trim($item_row[1], '.')) : null;

						$unit_id = !empty($unit) ? Production::get_unit_id($unit) : 1;
						if (!empty($name)) {
							$query_row .= '( "' . addslashes($name) . '", ' . $unit_id . ', 0, ' . $storage_id . '),';
						}
					}
					$query = $db->query(substr($query_row, 0, -1));
					$query->getResult();
					if ($db->affectedRows() > 0) {
						return ['status' => 'ok'];
					} else {
						return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
					}
				}
			}
		}


	}

	public static function delete_name($storage_name_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('DELETE FROM `fin_storage_names` WHERE id=' . $storage_name_id);

		$query->getResult();
		return ['status' => 'ok', 'id' => $db->insertID()];
	}

	public static function do_inventory($atts, $storage_id)
	{
		$db = Config\Database::connect();
		$query_row0 = 'DELETE FROM `fin_storage_items` WHERE `storage_id`=' . $storage_id;
		$query = $db->query($query_row0);
		$query->getResult();

		$items = !empty($atts['items']) ? $atts['items'] : [];


		if (!empty($atts['new_items'])) {
			$new_items = $atts['new_items'];
			$query_row = 'INSERT INTO `fin_storage_names`( `name`, `unit_id`, `buy_price`, `amount`, `description`, `storage_id`, `supplier_id`) VALUES ';
			$query_row2 = 'INSERT INTO `fin_storage_items`(`storage_name_id`, `buy_price`, `amount`, `storage_id`) VALUES ';

			foreach ($new_items as $new_item) {
				if (!empty($new_item['name'])) {

					$query_row .= '( "' . addslashes($new_item['name']) . '", "' . $new_item['unit_id'] . '", "' . $new_item['price'] . '","' . $new_item['amount'] . '", "' . addslashes($new_item['description']) . '","' . $storage_id . '", 0),';
					echo $query_row;
					echo "<br/>";
					$query = $db->query(substr($query_row, 0, -1));
					$query->getResult();
					$inserted_id = $db->insertID();

					$query_row2 .= '( "' . $inserted_id . '", "' . $new_item['price'] . '", "' . $new_item['amount'] . '",' . $storage_id . '),';
//					echo $query_row2; echo "<br/>";
					$query2 = $db->query(substr($query_row2, 0, -1));
					$query2->getResult();
					$items[] = [
						"id" => $inserted_id,
						"price" => $new_item['price'],
						"amount" => $new_item['amount'],
						"description" => $new_item['description']
					];
				}
			}
		}
		if (!empty($items)) {
			$query_row = 'INSERT INTO `fin_storage_items_checkout`(`storage_name_id`, `date`, `amount`, `storage_operation_id`, `comment` ) VALUES ';

			foreach ($items as $item) {
				$amount = !empty($item['amount']) ? (int)$item['amount'] : 0;
				$query_row .= '( "' . addslashes($item['id']) . '", ' . time() . ', "' . $amount . '", 0, "інвентаризація ' . date('d.m.Y', time()) . '" ),';
			}
			$query = $db->query(substr($query_row, 0, -1));
			$query->getResult();
		}

		return ['status' => 'ok'];
	}

	public static function change_name($atts, $name_id)
	{
		$db = Config\Database::connect();
		$query_row = 'UPDATE `fin_storage_names` SET `name`="' . htmlspecialchars($atts['name']) . '", `unit_id`=' . $atts['unit_id'] . ',`buy_price`="' . $atts['price'] . '" ,`amount`="' . $atts['amount'] . '",`description`="' . $atts['description'] . '" WHERE id = ' . $name_id . ';';
		$query = $db->query($query_row);
		if ($db->affectedRows() > 0) {
			return ['status' => 'ok'];
		} else {
			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
		}
	}

	public static function get_total($storage_id)
	{
		$db = Config\Database::connect();
		$query_row = 'SELECT SUM(`buy_price` * `amount` ) as total FROM `fin_storage_items` WHERE storage_id=' . $storage_id . ' AND buy_price > 0';
		$query = $db->query($query_row);
		return $query->getFirstRow()->total;
	}

	public static function get_totals()
	{
		$storages = self::get_storages();
		$totals = [];
		if (!empty($storages)) {
			foreach ($storages as $storage) {
				$totals[$storage['id']] = (float)self:: get_total($storage['id']) . ' грн';
			}
		}
		return $totals;
	}

//	public static function get_last_activity($storage_id)
//	{
//		$db = Config\Database::connect();
//		$query_row = 'SELECT MAX(`date`) as max_date FROM `fin_storage_items_checkout` fsit LEFT JOIN fin_storage_names fsn ON fsn.id=fsit.storage_name_id WHERE fsn.storage_id=' . $storage_id;
//		$query = $db->query($query_row);
//		return $query->getFirstRow()->max_date;
//	}

//	public static function get_last_activities()
//	{
//		$storages = self::get_storages();
//		$activities = [];
//		if (!empty($storages)) {
//			foreach ($storages as $storage) {
//				$date = self:: get_last_activity($storage['id']);
//
//				$activities[$storage['id']] = $date > 0 ? date("d.m.Y", $date) : ' - ';
//			}
//		}
//		return $activities;
//	}

	public static function get_item_by_name_and_price($name_id, $price)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT `id`, `storage_name_id`, `buy_price`, `amount`, `storage_id`
 									FROM `fin_storage_items`
 									 WHERE storage_name_id = ' . $name_id . ' AND buy_price = ' . $price);
		return $query->getFirstRow();
	}

	public static function get_item($item_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT `id`, `storage_name_id`, `buy_price`, `amount`, `storage_id`
 									FROM `fin_storage_items`
 									 WHERE id = ' . $item_id);
		return $query->getFirstRow();
	}

	public static function add_item_to_project($storage_name_id, $price, $amount, $project_id, $block_id)
	{
		if (!empty($storage_name_id) && !empty($price) && !empty($amount) && !empty($project_id)) {
			$db = Config\Database::connect();
			$query = $db->query('INSERT INTO `fin_project_items`( `storage_name_id`, `price`, `amount`, `project_id`, `block_id`)
									VALUES ("' . $storage_name_id . '", "' . $price . '", "' . $amount . '", "' . $project_id . '","' . $block_id . '")');
			$query->getResult();
			return ['status' => 'ok', 'id' => $db->insertID()];
		}
	}

	public static function add_storage_checkout($item_id, $new_amount, $storage_operation_id, $storage_operation_type_id) // дописати
	{
		try {
			if (!empty($item_id)) {
				$new_amount = !empty($new_amount) ? (int)$new_amount : 0;
				$storage_operation_id = !empty($storage_operation_id) ? (int)$storage_operation_id : 0;
				$storage_operation_type_id = !empty($storage_operation_type_id) ? (int)$storage_operation_type_id : 0;
				$comment = "";
				switch ($storage_operation_type_id) {
					case 1: //додавання на склад
						$comment = htmlspecialchars("Сума після операції додавання №" . $storage_operation_id);
						break;
					case 2: //зі складу
						$comment = htmlspecialchars("Сума після операції списання №" . $storage_operation_id);
						break;
					case 3: // переміщення
						$comment = htmlspecialchars("Сума після операції переміщення №" . $storage_operation_id);
						break;
				}

				$db = Config\Database::connect();
				$query = $db->query('INSERT INTO `fin_storage_items_checkout`( `item_id`, `date`, `amount`, `storage_operation_id`, `comment`)
									VALUES ("' . $item_id . '", ' . time() . ', "' . $new_amount . '","' . $storage_operation_id . '","' . $comment . '")');
				return $query->getResult();
			} else {
				return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
			}
		} catch (Exception $e) {

			Telegram::send_admin_message('Помилка в Models/Storage/add_storage_checkout.' . " \r\n" . 'e - ' . $e);
			return ['status' => 'error', 'message' => 'Помилка відправлена адміністратору і буде виправлена найближчим часом'];
			die();
		}
	}

	public static function search($query, $params = [])
	{
		return self::get_storages(DBHelp::params_merge(['where' => [
			'(name LIKE "%' . trim($query) . '%")',
			'account_id = ' . Account::get_current_account_id(),
		]], $params));
	}

	protected static function update_item($storage_operation_id, $storage_operation_type_id, $name_id, $price, $amount, $storage_id, $item_id = null)
	{
		try {
			$db = Config\Database::connect();
			$existing_item = empty($item_id) ? self::get_item_by_name_and_price($name_id, $price) : self::get_item($item_id);

			if (!empty($existing_item)) {
				$updated_existing_item = [];
				$updated_existing_item['id'] = $existing_item->id;

				switch ($storage_operation_type_id) {
					case 1: // додавання
						$updated_existing_item['amount'] = htmlspecialchars($existing_item->amount + $amount);
						break;
					case 2:
						$updated_existing_item['amount'] = $existing_item->amount - $amount;
						break;
					case 3:
						// треба викликати два рази функцію
						break;
				}
				$updated_existing_item['last_change_date'] = time();
				$query_row = 'UPDATE `fin_storage_items` SET `amount`="' . $updated_existing_item['amount'] . '", `last_change_date`="' . $updated_existing_item['last_change_date'] . '" WHERE id = ' . $updated_existing_item['id'] . ';';

				$query = $db->query($query_row);
				$query->getResult();

				if ($db->affectedRows() > 0) {
					$result_item_id = $updated_existing_item['id'];
					$result_amount = $updated_existing_item['amount'];
					$result_name_id = $existing_item->storage_name_id;
				} else {
					throw new Exception('Не відбувся update позиції ' . $updated_existing_item['id'] . ' на складі.Тип операції -' . $storage_operation_type_id);
				}
			} else {
				$new_item = [];
				$new_item['storage_name_id'] = !empty($name_id) ? (int)$name_id : 0;
				$new_item['buy_price'] = !empty($price) ? (float)$price : 0;;

				switch ($storage_operation_type_id) {
					case 1: // додавання
						$new_item['amount'] = !empty($amount) ? (int)$amount : 0;
						break;
					case 2:
						$new_item['amount'] = !empty($amount) ? -(int)$amount : 0;
						break;
					case 3:
						// треба викликати два рази функцію
						break;
				}
				$new_item['storage_id'] = !empty($storage_id) ? (int)$storage_id : 0;;
				$new_item['last_change_date'] = time();
				$query_row = 'INSERT INTO `fin_storage_items`( `storage_name_id`, `buy_price`, `amount`, `storage_id`, `last_change_date`)
 						VALUES (' . $new_item['storage_name_id'] . ',' . $new_item['buy_price'] . ',' . $new_item['amount'] . ',' . $new_item['storage_id'] . ',' . $new_item['last_change_date'] . ')';
				$query = $db->query($query_row);
				$query->getResult();
				$inserted_id = $db->insertID();
				if ($inserted_id > 0) {
					$result_item_id = $inserted_id;
					$result_amount = $new_item['amount'];
					$result_name_id = $new_item['storage_name_id'];
				} else {
					throw new Exception('Не відбувся update позиції на складі.');
				}
			}

			self::add_storage_checkout($result_item_id, $result_amount, $storage_operation_id, $storage_operation_type_id);
			return ['status' => 'ok', 'item_id' => $result_item_id, 'amount' => $result_amount, 'name_id' => $result_name_id];
		} catch (Exception $e) {

			Telegram::send_admin_message('Помилка в Models/Storage/update_item.' . " \r\n" . 'e - ' . $e);
			return ['status' => 'error', 'message' => 'Помилка відправлена адміністратору і буде виправлена найближчим часом'];
			die();
		}
	}


	public static function add_income($atts)
	{
		try {
			$names = $atts['names'];
			if (!empty($names)) {
				$db = Config\Database::connect();

				$storage_operation_type_id = 1;
				$project_id = $atts['project_id'];
				$comment = $atts['comment'];
				$storage_id = $atts['storage_id'];
				$date = $atts['date'];
				$operation_id = $atts['operation_id'];
				foreach ($names as $name) {
					$item_names_ids[] = $name['id'];
					$storage_name_id = $name['id'];
					$price = $name['price'];
					$amount = $name['amount'];
					$insert_operation_query_row = 'INSERT INTO `fin_storage_operations`( `price`, `amount`, `storage_name_id`, `storage_operation_type_id`, `storage_id_1`, `project_id`, `comment`, `date`,`operation_id`) VALUES ';
					$insert_operation_query_row .= ' ("' . $price . '", "' . $amount . '", "' . $storage_name_id . '", "' . $storage_operation_type_id . '", "' . $storage_id . '", "' . $project_id . '", "' . $comment . '", "' . $date . '", "' . $operation_id . '")';
					$query = $db->query($insert_operation_query_row);
					$query->getResult();
					$inserted_id = $db->insertID();
//					echo $inserted_id;
					self::update_item($inserted_id, $storage_operation_type_id, $name['id'], $price, $amount, $storage_id);
				}
				return ['status' => 'ok'];
			}
		} catch (Exception $e) {

			Telegram::send_admin_message('Помилка в Models/Storage/add_income.' . " \r\n" . 'e - ' . $e);
			return ['status' => 'error', 'message' => 'Помилка відправлена адміністратору і буде виправлена найближчим часом'];
		}
	}

	public static function add_expense($atts)
	{
		try {
			$names = $atts['names'];
			if (!empty($names)) {
				$db = Config\Database::connect();

				$storage_operation_type_id = 2;
				$project_id = $atts['project_id'];
				$comment = $atts['comment'];
				$storage_id = $atts['storage_id'];
				$date = $atts['date'];
				$operation_id = $atts['operation_id'];
				foreach ($names as $name) {
					$item_names_ids[] = $name['id'];
					$storage_name_id = $name['id'];
					$price = $name['price'];
					$amount = $name['amount'];
					$insert_operation_query_row = 'INSERT INTO `fin_storage_operations`( `price`, `amount`, `storage_name_id`, `storage_operation_type_id`, `storage_id_1`, `project_id`, `comment`, `date`,`operation_id`) VALUES ';
					$insert_operation_query_row .= ' ("' . $price . '", "' . $amount . '", "' . $storage_name_id . '", "' . $storage_operation_type_id . '", "' . $storage_id . '", "' . $project_id . '", "' . $comment . '", "' . $date . '", "' . $operation_id . '")';
					$query = $db->query($insert_operation_query_row);
					$query->getResult();
					$inserted_id = $db->insertID();
//					echo $inserted_id;
					$update_result = self::update_item($inserted_id, $storage_operation_type_id, $name['id'], $price, $amount, $storage_id, $name['item_id']);
//					echo "<pre>";
//					var_dump($update_result);
//					echo "</pre>";die();
					if ($update_result['status'] == 'ok') {
						self::add_item_to_project($storage_name_id, $price, $amount, $project_id, $name['block_id']);
					}
				}
				return ['status' => 'ok'];
			}
		} catch (Exception $e) {

			Telegram::send_admin_message('Помилка в Models/Storage/add_expense.' . " \r\n" . 'e - ' . $e);
			return ['status' => 'error', 'message' => 'Помилка відправлена адміністратору і буде виправлена найближчим часом'];
		}

	}

	public static function set_products()
	{
		$names = self::get_storage_names(3);
		if (!empty($names)) {
			foreach ($names as $name) {
				Products::add($name['id'], 8);
			}
		}
	}

	public static function delete($contract_id)
	{
		DBHelp::delete('fin_storage', $contract_id);
		return ['status' => 'ok'];
	}

	public static function select_storage_names_query($join_keys = [])
	{
		if (in_array('fin_accesses', $join_keys)) {
			$paramas['has_many'][] = [
				'table' => ['fa' => 'fin_accesses'],
				'new_column' => 'accesses',
				'main_table_key' => 'access',
				'other_tabe_key' => 'id',
				'type' => 'in',
				'columns' => ['name', 'type'],
				'columns_with_aloas' => [

				]
			];
		}

		return [
			'table' => ['fs' => 'fin_storage_names'],
			'columns' => [
				'id',
				'name',
				'article',
				'unit_id',
				'buy_price',
				'currency',
				'min_amount',
				'description',
				'storage_id',
				'photo',
				'supplier_id'
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

	public static function select_query($join_keys = [])
	{

		$params = [
			'table' => ['fs' => 'fin_storage'],
			'columns' => [
				'id',
				'name',
				'department_id',
				'last_change_date',
				'type',
				'parent_id',
				'comment',
				'account_id'
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

		if (in_array('fin_accesses', $join_keys)) {
			$params['has_many'][] = [
				'table' => ['fa' => 'fin_accesses'],
				'new_column' => 'accesses',
				'main_table_key' => 'access',
				'other_tabe_key' => 'id',
				'type' => 'in',
				'columns' => ['name', 'type'],
				'columns_with_alias' => [

				]
			];
		}

		return $params;
	}

	// add storage items
	public static function add_new_storage_name($atts)
	{
		if (!empty($atts['name'])) {
			$params1 = [
				'name' => !empty($atts['name']) ? $atts['name'] : '',
				'article' => !empty($atts['article']) ? $atts['article'] : '',
				'unit_id' => !empty($atts['unit_id']) ? $atts['unit_id'] : 1,
				'buy_price' => !empty($atts['buy_price']) ? $atts['buy_price'] : 0,
				'currency' => !empty($atts['currency']) ? $atts['currency'] : 'UAH',
				'min_amount' => !empty($atts['min_amount']) ? $atts['min_amount'] : 0,
				'description' => !empty($atts['description']) ? $atts['description'] : '',
				'storage_id' => !empty($atts['storage_id']) ? $atts['storage_id'] : 0,
				'photo' => !empty($atts['photo']) ? $atts['photo'] : '',
				'supplier_id' => !empty($atts['supplier_id']) ? $atts['supplier_id'] : 0,
				'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : Account::get_current_account_id()
			];
			$result1 = DBHelp::insert('fin_storage_names', $params1);
			if ($result1['status'] == 'ok') {
				$params2 = [
					'storage_name_id' => $result1['id'],
					'buy_price' => !empty($atts['buy_price']) ? $atts['buy_price'] : 0,
					'amount' => !empty($atts['min_amount']) ? $atts['min_amount'] : 0,
					'storage_id' => !empty($atts['storage_id']) ? $atts['storage_id'] : 0,
					'last_change_date' => time(),
				];
				$result2 = DBHelp::insert('fin_storage_items', $params2);
				if ($result2['status'] == 'ok') {
					return ['status' => 'ok'];
				} else {
					return ['status' => 'error', 'message' => 'Помилка відправлена адміністратору і буде виправлена найближчим часом'];
				}
			} else {
				return ['status' => 'error', 'message' => 'Помилка відправлена адміністратору і буде виправлена найближчим часом'];
			}
		}
	}
}
