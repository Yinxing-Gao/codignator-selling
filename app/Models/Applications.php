<?php


namespace App\Models;

use CodeIgniter\Model;
use Config;

class Applications
{
	public static function get_apps($params = [], $join = [])
	{
		//'custom_payed_amount'
		$query_params = self::select_query(array_merge(['fin_departments', 'fin_projects', 'fin_articles', 'fin_status', 'fin_users'], $join));

		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_apps_short($params = [], $join = [])
	{
		$query_params = self::short_select_query(array_merge(['fin_departments', 'fin_projects', 'fin_articles', 'fin_status'], $join));

		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_active_apps()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fo . id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fd.name as department, fo.date, date_for, fo.amount, currency, type_id, product, fex.item as expense_item, situation, data, decision, status_id, category, fs.name as status, payed_amount, director_comment, urgently
									FROM fin_applications fo
									LEFT JOIN fin_users fu ON fo.author_id=fu.id
									LEFT JOIN fin_departments fd ON fo.department_id=fd.id
									LEFT JOIN fin_articles fex ON fo.article_id=fex.id
									LEFT JOIN fin_status fs ON fo.status_id=fs.id
									LEFT JOIN (SELECT SUM(amount1) as payed_amount,app_id FROM `fin_operations` WHERE operation_type_id=2 GROUP BY app_id) foa ON foa.app_id=fo.id WHERE status_id !=5 AND status_id !=4');
		return $query->getResultArray();
	}

	public static function get_app($id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fo.id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fo.author_id, fd.name as department,fo.department_id,  fo.date, date_for, fo.amount, currency, type_id, product, fo.article_id, fex.item as expense_item, situation, data, decision, status_id, category, project_id, fp.name as project_name, fs.name as status, far.end_date, order_files, order_names, director_comment, urgently, payed_amount, far.period as repeat_period
									FROM fin_applications fo
									LEFT JOIN fin_users fu ON fo.author_id=fu.id
									LEFT JOIN fin_departments fd ON fo.department_id=fd.id
									LEFT JOIN fin_status fs ON fo.status_id=fs.id
									LEFT JOIN fin_articles fex ON fo.article_id=fex.id
									LEFT JOIN fin_projects fp ON fo.project_id=fp.id
									LEFT JOIN fin_application_repeat far ON fo.id=far.app_id
									LEFT JOIN (SELECT SUM(amount1) as payed_amount,app_id FROM `fin_operations` WHERE operation_type_id=2 GROUP BY app_id) foa ON foa.app_id=fo.id
									WHERE fo.id="' . $id . '"');
		return $query->getFirstRow();
	}

	public static function get_apps_by_ids($ids)
	{
		$db = Config\Database::connect();
		if (is_array($ids)) {
			$ids = implode(',', $ids);
		}
		$query = $db->query('SELECT fo.id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fd.name as department, fo.date, date_for, fo.amount, currency, type_id, product, fex.item as expense_item, situation, data, decision, status_id, category, fs.name as status, payed_amount, director_comment, urgently
									FROM fin_applications fo
									LEFT JOIN fin_users fu ON fo.author_id=fu.id
									LEFT JOIN fin_departments fd ON fo.department_id=fd.id
									LEFT JOIN fin_articles fex ON fo.article_id=fex.id
									LEFT JOIN fin_status fs ON fo.status_id=fs.id
									LEFT JOIN (SELECT SUM(amount1) as payed_amount,app_id FROM `fin_operations` WHERE operation_type_id=2 GROUP BY app_id) foa ON foa.app_id=fo.id WHERE fo.id IN (' . $ids . ')');
		return $query->getResultArray();
	}

//	public static function get_user_apps($user_id, $status = 'status_id!=5')
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT fo.id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fd.name as department, fo.date, date_for, fo.amount, fp.name as project,base_app_id, currency, type_id, product, status_id, category , fs.name as status, payed_amount, director_comment, urgently, base_app_id
//									FROM fin_applications fo
//                                    LEFT JOIN fin_users fu ON fo.author_id=fu.id
//									LEFT JOIN fin_departments fd ON fo.department_id=fd.id
//                                    LEFT JOIN fin_projects fp ON fo.project_id=fp.id
//									LEFT JOIN fin_status fs ON fo.status_id=fs.id
//									LEFT JOIN (SELECT SUM(amount1) as payed_amount,app_id
//										FROM `fin_operations`
//										WHERE operation_type_id=2
//										GROUP BY app_id) foa ON foa.app_id=fo.id
//									WHERE author_id=' . $user_id . ' AND ' . $status);
//		return $query->getResultArray();
//	}

	public static function get_company_apps()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fo.id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fd.name as department, fo.date, date_for, fo.amount, currency, type_id, product, fex.item as expense_item, situation, data, decision, status_id, category , fs.name as status, director_comment, urgently FROM fin_applications fo LEFT JOIN fin_users fu ON fo.author_id=fu.id LEFT JOIN fin_departments fd ON fo.department_id=fd.id LEFT JOIN fin_status fs ON fo.status_id=fs.id LEFT JOIN fin_articles fex ON fo.article_id=fex.id WHERE type_id=' . 2);
		return $query->getResultArray();
	}

	public static function get_approved_apps()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fo.id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fd.name as department, fo.date, date_for, fo.amount, currency, type_id, product, fex.item as expense_item, situation, data, decision, status_id, category , fs.name as status, payed_amount, director_comment, urgently
										FROM fin_applications fo
										LEFT JOIN fin_users fu ON fo.author_id=fu.id
										LEFT JOIN fin_departments fd ON fo.department_id=fd.id
										LEFT JOIN fin_status fs ON fo.status_id=fs.id
										LEFT JOIN fin_articles fex ON fo.article_id=fex.id
										LEFT JOIN (SELECT SUM(amount1) as payed_amount,app_id FROM `fin_operations` WHERE operation_type_id=2 GROUP BY app_id) foa ON foa.app_id=fo.id
										WHERE status_id = 3');
		return $query->getResultArray();
	}

	public static function get_payed_apps()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fo.id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fd.name as department, fo.date, date_for, fo.amount, currency, type_id, product, fex.item as expense_item, situation, data, decision, status_id, category , fs.name as status, payed_amount, director_comment, urgently
										FROM fin_applications fo
										LEFT JOIN fin_users fu ON fo.author_id=fu.id
										LEFT JOIN fin_departments fd ON fo.department_id=fd.id
										LEFT JOIN fin_status fs ON fo.status_id=fs.id
										LEFT JOIN fin_articles fex ON fo.article_id=fex.id
										LEFT JOIN (SELECT SUM(amount1) as payed_amount,app_id FROM `fin_operations` WHERE operation_type_id=2 GROUP BY app_id) foa ON foa.app_id=fo.id
										WHERE status_id = 5');
		return $query->getResultArray();
	}

//	public static function get_approved_transfered_apps()
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT fo.id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fd.name as department, fo.date, date_for, fo.amount, currency, type_id, product, fex.item as expense_item, situation, data, decision, status_id, category , fs.name as status, order_files, order_names, director_comment, urgently
//								FROM fin_applications fo
//								LEFT JOIN fin_users fu ON fo.author_id=fu.id
//								LEFT JOIN fin_departments fd ON fo.department_id=fd.id
//								LEFT JOIN fin_status fs ON fo.status_id=fs.id
//								LEFT JOIN fin_articles fex ON fo.article_id=fex.id
//								WHERE status_id = 4 OR status_id = 2');
//		return $query->getResultArray();
//	}

	public static function get_tov_transfered_and_payed_apps_orders()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fo.id, status_id, order_names
								FROM fin_applications fo
								WHERE status_id IN(2, 4, 5, 7) AND type_id = 2');
		return $query->getResultArray();
	}

	public static function get_department_apps($user_id, $for_select = false, $status = 'status_id != 5', $date_from = null, $date_to = null)
	{
//		if (!in_array(12, Position::get_positions(['where' =>[''$user_id))) { // фінансист - то має повний доступ
		$users_ids = Position::get_department_users($user_id);

		if (!empty($users_ids)) {
			$db = Config\Database::connect();
			$query_row = '';
			foreach ($users_ids as $u_id) {
				$query_row .= $u_id['id'] . ',';
			}
			$where = ' WHERE author_id IN (' . substr($query_row, 0, -1) . ') AND ' . $status;

			if (!empty($date_from) && !empty($date_to)) {
				$where .= ' AND date_for > ' . $date_from . ' AND date_for < ' . $date_to;
			}

			$query = $db->query('SELECT fo.id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fd.name as department, fo.date, date_for, fo.amount, currency, type_id, product, fex.item as expense_item, situation, fp.name as project, data, decision, status_id, category, fs.name as status, payed_amount, director_comment, urgently, base_app_id
											FROM fin_applications fo
											LEFT JOIN fin_users fu ON fo.author_id=fu.id
											LEFT JOIN fin_projects fp ON fo.project_id=fp.id
											LEFT JOIN fin_departments fd ON fo.department_id=fd.id
											LEFT JOIN fin_status fs ON fo.status_id=fs.id
											LEFT JOIN fin_articles fex ON fo.article_id=fex.id
											LEFT JOIN (SELECT SUM(amount1) as payed_amount,app_id FROM `fin_operations` WHERE operation_type_id=2 GROUP BY app_id) foa ON foa.app_id=fo.id'
				. $where);
			$order_list = $query->getResultArray();
		} else {
			$order_list = [];
		}
//		} else {
//			if ($for_select) {
//				$order_list = Applications::get_apps_for_select($status);
//			} else {
//				$order_list = Applications::get_apps($status, $date_from, $date_to);
//			}
//		}

		return $order_list;
	}

	public static function get_department_apps_()
	{
		//todo замінити на перевірку чи може представник цієї професії бачити всі заявки
		if (!in_array(12, Position::get_professions($user_id))) { // фінансист - то має повний доступ

		}
	}


//	public static function add_app($atts)
//	{
//		$db = Config\Database::connect();
//
//		$query = $db->query('INSERT INTO `fin_applications`( `author_id`, `department_id`, `date`, `date_for`, `amount`, `currency`, `type_id`, `project_id`, `product`, `article_id`, `situation`, `data`, `decision`, `status_id`, `category`, `order_files`, `base_app_id`, `urgently`, `order_names`, `director_comment`)
//									VALUES ("' . $atts['author_id'] . '", "' . $atts['department_id'] . '", "' . time() . '", "' . strtotime($atts['date_for']) . '", "' . $atts['amount'] . '", "' . $atts['currency'] . '", "' . $atts['type_id'] . '", "' . $atts['project_id'] . '", "' . addslashes($atts['product']) . '", "' . $atts['article_id'] . '", "' . addslashes($atts['situation']) . '", "' . addslashes($atts['data']) . '", "' . addslashes($atts['decision']) . '", 1, "A", "' . addslashes($atts['uploaded_files']) . '", 0,  ' . (!empty($atts['urgently']) ? 1 : 0) . ', "' . addslashes($atts['order_names']) . '", 0 )');
//		$query->getResult();
//		if ($db->affectedRows() == 1) {
//			$inserted_id = $db->insertID();
//			if ((($atts['department_id'] == 1 || $atts['department_id'] == 8) && (int)$atts['project_id'] > 0) || !empty($atts['urgently'])) {//виробництво або послуга
////				echo 'ppppp';
//				if (!empty($atts['project_id'])) {
//					$message_project = Projects::get_project($atts['project_id']);
////					echo 'ssss';
//					Telegram::send_message('@Rudia1, Отримано нову актуальну заявку на ' . addslashes($atts['product']) . ' на суму ' . $atts['amount'] . ' ' . $atts['currency'] . ' по проекту ' . $message_project->name . '. Перейдіть на сайт, щоб одобрити' . " \r\n" . base_url('application//department', 'https'));
//				} else {
////					echo 'lllll';
//					Telegram::send_message('@Rudia1, Отримано нову актуальну заявку на ' . addslashes($atts['product']) . ' на суму ' . $atts['amount'] . ' ' . $atts['currency'] . '. Перейдіть на сайт, щоб одобрити' . " \r\n" . base_url('application/department', 'https'));
//				}
//			}
//
//			if (!empty($atts['repeat'])) {
//				$repeat_end_date = !empty($atts['repeat_end_date']) ? strtotime($atts['repeat_end_date']) : 0;
//				$query = $db->query('INSERT INTO `fin_application_repeat`( `app_id`, `start_date`, `period`, `end_date`) VALUES ("' . $inserted_id . '", "' . time() . '", "' . $atts['repeat_type'] . '", "' . $repeat_end_date . '")');
//				$query->getResult();
//			}
//			return ['status' => 'ok', 'id' => $inserted_id];
//		} else {
//			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
//		}
//	}

	public static function add($atts)
	{
		$session = Config\Services::session();
		$account_id = $session->get('account_id');

		$params = [
			'author_id' => $atts['author_id'],
			'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
			'date' => time(),
			'date_for' => strtotime($atts['date_for']),
			'amount' => $atts['amount'],
			'currency' => $atts['currency'],
			'type_id' => $atts['type_id'],
			'project_id' => !empty($atts['project_id']) ? $atts['project_id'] : 0,
			'product' => $atts['product'],
			'article_id' => !empty($atts['article_id']) ? $atts['article_id'] : 0,
			'situation' => $atts['situation'],
			'data' => $atts['data'],
			'decision' => $atts['decision'],
			'authority_id' => !empty($atts['authority_id']) ? $atts['authority_id'] : 0,
			'status_id' => 1,
			'contractor_id' => !empty($atts['contractor_id']) ? $atts['contractor_id'] : 0,
			'pay_from' => !empty($atts['pay_from']) ? $atts['pay_from'] : 'direct',
			'order_files' => !empty($atts['uploaded_files']) ? $atts['uploaded_files'] : '',
			'urgently' => !empty($atts['urgently']) ? 1 : 0,
			'order_names' => !empty($atts['order_names']) ? $atts['order_names'] : '',
			'director_comment' => !empty($atts['director_comment']) ? $atts['director_comment'] : '',
			'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : $account_id
		];

		$result = DBHelp::insert('fin_applications', $params);

		if ($result['status'] == 'ok') {
			$op_params = [
				"app_id" => $result['id'],
				"project_id" => !empty($atts['project_id']) ? $atts['project_id'] : 0,
				"comment" => $atts['product'] . "\r\n" . 'Сформовано автоматично з заявки # ' . $result['id'],
				"article_id" => !empty($atts['article_id']) ? $atts['article_id'] : 0,
				"time_type" => 'plan',
				"account_id" => !empty($atts['account_id']) ? $atts['account_id'] : $account_id,
				"probability" => 100,
				"is_shown" => 0,
				'amount' => $atts['amount'],
				'currency' => $atts['currency'],
//				"department_id" => // треба обдумати,
				'operation_type_id' => 2,
				'contractor_id' => !empty($atts['contractor_id']) ? $atts['contractor_id'] : 0
			];

			if ($params['pay_from'] == 'direct') {

				$wallet_1 = Wallets::get_wallet(['where' => [
//					'user_id = ' . $atts['authority_id'],
					'currency = "' . $atts['currency'] . '"'
				]]);

				if (!empty($wallet_1)) {
					$op_params['wallet_id'] = $wallet_1->id;
					$op_params['planned_on'] = strtotime($atts['date_for']);
					$op_params['user_id'] = !empty($atts['authority_id']) ? $atts['authority_id'] : 0;
				}
			} else { //transfer
				$wallet_1 = Wallets::get_wallet(['where' => [
					'user_id = ' . $atts['authority_id'],
					'currency = "' . $atts['currency'] . '"'
				]]);

				$wallet_2 = Wallets::get_wallet(['where' => [
					'user_id = ' . $atts['author_id'],
					'currency = "' . $atts['currency'] . '"'
				]]);

				if (!empty($wallet_1) && !empty($wallet_2)) {

					$op_params['wallet_id'] = $wallet_2->id;
					$op_params['user_id'] = $atts['author_id'];
					$op_params['planned_on'] = strtotime('last ' . Settings::get('finance day'), strtotime($atts['date_for']));
					$op_params2 = $op_params;

					$op_params2['operation_type_id'] = 3;
					$op_params2['wallet_1_id'] = $wallet_1->id;
					$op_params2['wallet_2_id'] = $wallet_2->id;
					$op_params2['user_id'] = $atts['authority_id'];
					$op_params2['user_2_id'] = $atts['author_id'];
					$op_params2['planned_on'] = strtotime($atts['date_for']);
					Operation::add($op_params2, true);
				}
			}
			$result_2 = Operation::add($op_params);
		}
		return $result;
	}

	public
	static function edit_app($app_id, $atts)
	{
		$db = Config\Database::connect();
		$query = $db->query('UPDATE `fin_applications` SET `author_id`="' . $atts['author_id'] . '",`department_id`= "' . $atts['department_id'] . '",`date`="' . time() . '",`date_for`="' . strtotime($atts['date_for']) . '",`amount`="' . $atts['amount'] . '",`currency`="' . $atts['currency'] . '",`type_id`="' . $atts['type_id'] . '",`project_id`="' . $atts['project_id'] . '",`product`="' . addslashes($atts['product']) . '",`article_id`="' . $atts['article_id'] . '",`situation`="' . addslashes($atts['situation']) . '",`data`="' . addslashes($atts['data']) . '",`decision`="' . addslashes($atts['decision']) . '",`order_files`="' . addslashes($atts['uploaded_files']) . '"  WHERE id=' . $app_id);

		$query->getResult();
		if ($db->affectedRows() == 1) {
			$inserted_id = $db->insertID();
			if (($atts['department_id'] == 1 || $atts['department_id'] == 8) && (int)$atts['project_id'] > 0) {//виробництво або послуга
				$message_project = Projects::get_project($atts['project_id']);
				Telegram::start();
				Telegram::send_message('Відредаговано заявку на ' . addslashes($atts['product']) . ' на суму ' . $atts['amount'] . ' ' . $atts['currency'] . ' по проекту ' . $message_project->name . '. Перейдіть на сайт, щоб переглянути зміни' . " \r\n" . base_url('application/all', 'https'));
			}
			$query = $db->query('DELETE FROM `fin_application_repeat` WHERE app_id=' . $app_id);
			$query->getResult();
			if (!empty($atts['repeat'])) {
				$repeat_end_date = !empty($atts['repeat_end_date']) ? strtotime($atts['repeat_end_date']) : 0;
				$query = $db->query('INSERT INTO `fin_application_repeat`( `app_id`, `start_date`, `period`, `end_date`) VALUES ("' . $app_id . '", "' . time() . '", "' . $atts['repeat_type'] . '", "' . $repeat_end_date . '")');
				$query->getResult();
			}
			return ['status' => 'ok', 'id' => $inserted_id];
		} else {
			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
		}
	}

	public
	static function delete_app($app_id)
	{
		self::delete_uploaded_files($app_id);
//		$db = Config\Database::connect();
//		$query = $db->query('DELETE FROM `fin_applications` WHERE id=' . $app_id);
//
//		$query->getResult();
		DBHelp::delete('fin_applications', $app_id);
		self::delete_copies($app_id);
		return ['status' => 'ok'];
	}

	public
	static function delete_uploaded_files($app_id)
	{
		$app = self::get_app($app_id);
		if (!empty($app)) {
			if (!empty($app->order_files)) {
				$order_files = json_decode(stripslashes($app->order_files));
				foreach ($order_files as $file) {
					self::delete_uploaded_file($file);
				}
			}
		}
		return ['status' => 'ok'];
	}

	public
	static function delete_uploaded_file($file)
	{

		$dir = getcwd(); // Save the current directory
		if (file_exists($dir . $file)) {
			if (unlink($dir . $file)) {
				return ['status' => 'ok'];
			}
		} else {
			return ['status' => 'error'];
		}
	}

	public
	static function save_director_comment($app_id, $comment)
	{
		$db = Config\Database::connect();
		$query = $db->query('UPDATE `fin_applications` SET `director_comment`= "' . addslashes($comment) . '" WHERE id=' . $app_id);

		$query->getResult();
		if ($db->affectedRows() == 1) {
			return ['status' => 'ok', 'id' => $db->insertID()];
		} else {
			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
		}
	}

	public
	static function change_category($app_id, $category)
	{
		$db = Config\Database::connect();
		$query = $db->query('UPDATE `fin_applications` SET `category`= "' . $category . '" WHERE id=' . $app_id);

		$query->getResult();
		if ($db->affectedRows() == 1) {
			return ['status' => 'ok', 'id' => $db->insertID()];
		} else {
			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
		}
	}

//	public static function change_status($app_id_arr, $status_id, $changer_user_id = 0)
//	{
//		if (!empty($app_id_arr)) {
//			if (is_array($app_id_arr)) {
//				$query_row = ' WHERE id IN (' . implode(',', $app_id_arr) . ')';
//			} else {
//				$query_row = ' WHERE id=' . $app_id_arr;
//			}
//			$db = Config\Database::connect();
//			$query = $db->query('UPDATE `fin_applications` SET `status_id`= "' . $status_id . '"' . $query_row);
//
//			$query->getResult();
//			if ($db->affectedRows() > 0) {
//
//				Telegram::send_personal_message($user_id, $text)
//
//				return ['status' => 'ok'];
//			} else {
//				return ['status' => 'error1', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
//			}
//		} else {
//			return ['status' => 'error2', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
//		}
//	}

	public
	static function change_status($app_id_arr, $status_id, $changer_user_id = 0, $status_text = null)
	{
		if (!empty($app_id_arr)) {
			$db = Config\Database::connect();
			if (empty($status_text)) {
				$status = self::get_status($status_id);
				$status_text = !empty($status) ? $status->name : '';
			}

			foreach ($app_id_arr as $app_id) {
				$query = $db->query('UPDATE `fin_applications` SET `status_id`= "' . $status_id . '" WHERE id=' . $app_id);
				$query->getResult();

				$app = self::get_app($app_id);
				if (!empty($app)) {
//					if ($changer_user_id != $app->author_id) {
					Telegram::send_personal_message($app->author_id, "Вашу заявку #" . $app->id . '. ' . $app->product . ' - ' . $app->amount . ' ' . $app->currency . ' ' . $status_text);
//					}
				}
			}
			if ($db->affectedRows() > 0) {
				return ['status' => 'ok'];
			} else {
				return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
			}
		} else {
			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
		}
	}

//	public
//	static function get_apps_by_ids($id_arr)
//	{
//		$query_row = '';
//		foreach ($id_arr as $id) {
//			$query_row = $id . ', ';
//		}
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT fo.id,  fo.amount, currency,type_id, product FROM fin_applications fo WHERE id IN (' . substr($query_row, 0, -2) . ')');
//		$app_list = $query->getResultArray();
//		return $app_list;
//	}

	public
	static function send_application_mail()
	{
		$apps = self::get_approved_transfered_apps();

		if (!empty($apps)) {
			$message = '<p>У вас є нові заявки до оплати, щоб оплатити ці заявки перейдіть, будь ласка в <a href="http://fin.ekonombud.in.ua/application/approved_tov">систему</a>:</p>
						<h2>Рахунки на оплату</h2>
						<table>
						<tr>
						<th>ID</th>
						<th>Товар/послуга</th>
						<th>Сума</th>
						<th>Валюта</th>
						</tr>';
			foreach ($apps as $app) {

				$message .= '<tr>';
				$message .= '<td>' . $app['id'] . '</td>';
				$message .= '<td>' . $app['product'] . '</td>';
				$message .= '<td>' . $app['amount'] . '</td>';
				$message .= '<td>' . $app['currency'] . '</td>';
				$message .= '</tr>';

			}
			$message .= '</table>';
//			Mail::send_mail('olexandrmatsuk@gmail.com, srudenkos@gmail.com', 'Заявки до оплати', $message);
		}

	}

	public
	static function transfer_to_pay($id_arr, $user_id)
	{
		if (!empty($id_arr)) {
			$has_tovs = false;
//			var_dump($id_arr);die();
			foreach ($id_arr as $app_item) {
				$id = $app_item->id;
				$amount_to_transfer = $app_item->amount;
				$comment = $app_item->comment;
				$app = self::get_app($id);
				if ($app->type_id == 2 || $app->type_id == 3) { // на ФОП або на ТОВ
					if (!empty($amount_to_transfer)) {
						self::change_status([$id], 2, $user_id);
						self::save_director_comment($id, (!empty($comment) ? $comment : 'Оплатити ' . $amount_to_transfer));
					} else {
						self::change_status([$id], 4, $user_id);
					}

					$has_tovs = true;
				} elseif (($app->type_id == 1)) {
					self::change_status([$id], !empty($amount_to_transfer) ? 2 : 4, $user_id);
					if (!in_array(2, Position::get_professions($app->author_id))) {
						$contractor_1 = Position::get_user_ids_by_professions(2)[0]; // беремо касу директора
						$user1_wallet = Wallets::get_user_wallet($contractor_1['id'], $app->currency);
						$user2_wallet = Wallets::get_user_wallet($app->author_id, $app->currency);
						$new_operation = [
							'amount' => !empty($amount_to_transfer) ? $amount_to_transfer : $app->amount,
							'currency' => $app->currency,
							'wallet_1_id' => !empty($user1_wallet) ? $user1_wallet->id : 0,
							'wallet_2_id' => !empty($user2_wallet) ? $user2_wallet->id : 0,
							'user_1' => $contractor_1['id'],
							'user_2' => $app->author_id,
							'project_id' => !empty($app->project_id) ? $app->project_id : 0,
							'app_id' => $app->id,
							'comment' => 'Переміщення згідно заявки ' . $app->id . '.' . $app->product . ' від ' . date("d.m.Y", $app->date),
							'date' => time()
						];
						Operation::add_transfer($new_operation, true);
					}

				}
			}
//			Wallets::refresh();
			if ($has_tovs) {
				$accountant_ids = Position::get_user_ids_by_professions(14);
				$text = 'У вас є нові заявки на оплату. Перейдіть, будь ласка, в фін.систему для оплати. ' . " \r\n" . 'http://fin.ekonombud.in.ua/application/approved_tov';
				if (!empty($accountant_ids)) {
					foreach ($accountant_ids as $accountant_id) {
						Telegram::send_personal_message($accountant_id['id'], $text);
					}
				}
			}
			self::send_application_mail();
			return ['status' => 'ok'];
		}
		return ['status' => 'error'];
	}

	public
	static function pay($id_arr, $op_user_id)
	{
		if (!empty($id_arr)) {
			foreach ($id_arr as $app_item) {
				$id = $app_item->id;
				$amount_to_transfer = $app_item->amount;
				$app = self::get_app($id);
//
				self::change_status([$id], !empty($amount_to_transfer) ? 7 : 5, $op_user_id);
				if ($app->type_id == 2 || $app->type_id == 3) {
					$user_id = 15; // обираємо ТОВ
					$user1_wallet = Wallets::get_user_wallet($user_id, $app->currency);
				} else {
					$user_id = $app->author_id;
					$user1_wallet = Wallets::get_user_wallet($user_id, $app->currency);
				}
				$new_operation = [
					'amount' => !empty($amount_to_transfer) ? $amount_to_transfer : $app->amount,
					'currency' => $app->currency,
					'wallet_1_id' => !empty($user1_wallet) ? $user1_wallet->id : 0,
					'user_id' => $user_id,
					'contractor_type' => 'existing', //додати в заявки контрагентів
					'contractor_id' => 49,
					'project_id' => !empty($app->project_id) ? $app->project_id : 0,
					'app_id' => $app->id,
					'comment' => 'Витрата згідно заявки ' . $app->id . '.' . $app->product . ' від ' . date("d.m.Y", $app->date),
					'date' => time()
				];
//				var_dump($new_operation);
//				die();
				Operation::add_expense($new_operation, true);

			}
//			Wallets::refresh();
			self::send_application_mail();
			return ['status' => 'ok'];
		}
		return ['status' => 'error'];
	}

	public
	static function check_as_payed($id_arr, $user_id)
	{
		self::change_status($id_arr, 5, $user_id, 'позначено як оплачено');
		return ['status' => 'ok'];
	}


	public
	static function reject($id_arr, $user_id)
	{
		self::change_status($id_arr, 6, $user_id, 'відхилено');
		return ['status' => 'ok'];
	}


	public
	static function refresh_apps()
	{
		$apps = self::get_apps();
		if (!empty($apps)) {
			foreach ($apps as $app) {
				if ($app['payed_amount'] > 0) {
					if ($app['payed_amount'] >= $app['amount']) {
						self::change_status([$app['id']], 5);
					} else {
						self::change_status([$app['id']], 7);
					}
				}


			}
		}
	}

	public
	static function refresh_app($app_id)
	{
		$app = self::get_app($app_id);
		if (!empty($app)) {
			if ($app->payed_amount > 0) {
				if ($app->payed_amount >= $app->amount) {
					self::change_status([$app->id], 5);
				} else {
					self::change_status([$app->id], 7);
				}
			}
		}
	}

	public
	static function get_apps_dates_and_amounts($date_from = 1577836800, $date_to = 1577836800 + 60 * 60 * 24 * 90)
	{
		$data_charts_formatted = [];
		$db = Config\Database::connect();
		$query = $db->query('SELECT fo.id, date_for, fo.amount, currency, type_id,status_id, payed_amount
									FROM fin_applications fo
									LEFT JOIN fin_status fs ON fo.status_id=fs.id
									LEFT JOIN (SELECT SUM(amount1) as payed_amount,app_id FROM `fin_operations` WHERE operation_type_id=2 GROUP BY app_id) foa ON foa.app_id=fo.id
									WHERE status_id != 5 AND status_id != 4 AND date_for > ' . $date_from);
		$apps_list = $query->getResultArray();

		if (!empty($apps_list)) {
			$date = $date_from; // 1.1.20
			$day = 60 * 60 * 24;
			$week = $day * 7;
			while (date('D', $date) !== 'Tue') {
				$date += $day;
			}
			$thuesdays = [$date_from];
			for ($i = $date; $i < $date_to; $i += $week) {
				$thuesdays[] = $i;
			}
			$data_charts = [];
			foreach ($apps_list as &$app) {
				if ($app['currency'] != 'UAH') {
					$currency_rate = CurrencyRate::get_exchange_rates($app['currency']);
					$app['total'] = $app['amount'] * $currency_rate['buy'];
				} else {
					$app['total'] = $app['amount'];
				}
				$app['left'] = $app['total'] - $app['payed_amount'];
				if ($app['left'] < 0) {
					$app['left'] = 0;
				}
				for ($i = 0; $i < count($thuesdays) - 1; $i++) {

					if (empty($data_charts[$thuesdays[$i + 1]])) {

						$data_charts[$thuesdays[$i + 1]] = 0;
					}

					if ($app['date_for'] >= $thuesdays[$i] && $app['date_for'] < $thuesdays[$i + 1]) {
						$data_charts[$thuesdays[$i + 1]] += $app['left'];
					}

				}
			}
			ksort($data_charts);
			$data_charts_formatted = [['Дата', 'Заявки', 'Необхідний оборот при маржинальності в 20%']];
			foreach ($data_charts as $timestamp => $total_left) {
				$data_charts_formatted[] = [date("d.m.Y", $timestamp), $total_left, $total_left / 0.2];
			}
		}
//		[
//			['Year', 'Sales', 'Expenses'],
//			['2004',  1000,      400],
//			['2005',  1170,      460],
//			['2006',  660,       1120],
//			['2007',  1030,      540]
//		]

		return $data_charts_formatted;
	}

	public
	static function refresh_dates() //запускається по крону в середу вранці
	{
		$apps = self::get_apps();
		if (!empty($apps)) {
			$last_Tuesday = strtotime("last Tuesday 00:00:00");
			$next_Tuesday = strtotime("next Tuesday 00:00:00");// - 60 * 60 * 2;

			$db = Config\Database::connect();
			foreach ($apps as $app) {
				if ($app['date_for'] < $last_Tuesday && $app['status_id'] != 4 && $app['status_id'] != 5) {
					$new_date_for = $next_Tuesday + random_int(60 * 60 * 9, 60 * 60 * 18);
					$new_data = $app['data'] . "\r\n Заявка просрочена на " . date('d.m.Y', $app['date_for']) . ' і перенесена автоматично на ' . date('d.m.Y', $new_date_for);
					$query = $db->query('UPDATE `fin_applications` SET `date_for` = "' . $new_date_for . '", `data`= "' . $new_data . '" WHERE id = ' . $app['id']);
					$query->getResult();
				}
			}
		}
	}

//	public
//	static function create_repeated_apps()
//	{
////
//		$db = Config\Database::connect();
////
//		$query = $db->query('SELECT fa.`id`, `start_date`, far.`period`, `end_date`, `author_id`, `department_id`, `date`, `date_for`, `amount`, `currency`, `type_id`, `project_id`, `product`, `article_id`, `situation`, `data`, `decision`, `status_id`, `category`, `order_files` FROM `fin_application_repeat` far INNER JOIN fin_applications fa ON fa.id=far.app_id');
//		$repeated_apps = $query->getResultArray();
//		if (!empty($repeated_apps)) {
//			$copies_string_array = self::get_copies(true);
////			var_dump($copies_string_array);die();
//			foreach ($repeated_apps as $repeated_app) {
//				$date_for = $repeated_app['date_for'];;
//				$period = $repeated_app['period'];
//				$end_date = $repeated_app['end_date'];
//				$in_a_month = time() + 60 * 60 * 24 * 30;
//				//якщо дата старту менше за дату чере змісяць і дата закінчення =0 або  даза закінчення менша дати через місяць
//				if ($date_for < strtotime('+1 month', time())) {
//					if ($end_date == 0 || $end_date > $in_a_month) {
//						$end_date = $in_a_month;
//					}
//
//					$day = 60 * 60 * 24;
//					$week = $day * 7;
//					$dates = [];
//					switch ($period) {
//						case "day":
//							$date = $date_for;
//							while ($date < $end_date) {
//								$date += $day;
//								echo 'created app with date ' . date('d.m.Y', $date) . '<br/>';
//								$dates[] = $date;
//							}
//							break;
//						case "week":
//							$date = $date_for;
//							while ($date < $end_date) {
//								$date += $week;
//								echo 'created app with date ' . date('d.m.Y', $date) . '<br/>';
//
//								$dates[] = $date;
//							}
//							break;
//						case "month":
//							echo 'created app with date ' . date('d.m.Y', strtotime('+1 month', time())) . '<br/>';
//							$dates[] = strtotime('+1 month', time());
//							break;
//					}
////					var_dump($dates);
////					echo "<br/>";
////					echo "<br/>";
//					foreach ($dates as $repeat_date) {
//						$copy = $repeated_app;
//						unset($copy['start_date']);
//						unset($copy['period']);
//						unset($copy['end_date']);
//						$copy['date'] = time();
////						echo "repeat_date - " . $repeat_date . 'date: ' . date('d.m.Y', $repeat_date);
//						$copy['date_for'] = date('Y-m-d', $repeat_date);
//						$copy['data'] = $copy['data'] . "<br/><br/> Це повторювана заявка, створена системою автоматично з заявки " . $repeated_app['id'] . ') ' . $repeated_app['product'] . ' від ' . date('d.m.Y', $repeated_app['date']);
//						$copy['status_id'] = 1;
//						$copy['uploaded_files'] = $copy['order_files'];
//						$copy['base_app_id'] = $repeated_app['id'];
//						$message_String = '';
//						if (empty($copies_string_array) || !in_array($copy['base_app_id'] . '_' . $copy['date_for'], $copies_string_array)) {
//							$message_String .= date("d.m.Y", time()) . " - створено копію заявки " . $repeated_app['id'] . ') ' . $repeated_app['product'] . ' від ' . date('d.m.Y', $repeated_app['date']) . " на " . $copy['date_for'] . " \r\n";
//							echo $message_String;
//							Telegram::start();
//							Telegram::send_message($message_String);
//							file_put_contents('writable/repeat_apps/log.txt', $message_String, FILE_APPEND);
//
//							self::add_app($copy);
//						}
//					}
//				}
//			}
//		}
//	}

//	public
//	static function get_copies($make_string_array = false)
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT base_app_id, date_for FROM `fin_applications` WHERE base_app_id != 0');
//		$copies = $query->getResultArray();
//		if ($make_string_array) {
//			$copy_strings_array = [];
//			if (!empty($copies)) {
//				foreach ($copies as $copy) {
//					$copy_strings_array[] = $copy['base_app_id'] . '_' . date("Y-m-d", $copy['date_for']);
//				}
//				return $copy_strings_array;
//			}
//		} else {
//			$copies = $query->getResultArray();
//			return $copies;
//		}
//	}
//
//	public
//	static function delete_copies($base_id)
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('DELETE FROM `fin_applications` WHERE base_app_id = ' . $base_id);
//		return $query->getResult();
//	}

	public
	static function search($query, $params = [])
	{
		return self::get_apps_short(DBHelp::params_merge(['where' => [
			'product LIKE "%' . trim($query) . '%" OR data  LIKE "%' . trim($query) . '%"'
		]], $params));
	}

	public
	static function search_all($query, $app_id = null)
	{
		if (!empty($query) || !empty($app_id)) {
			$db = Config\Database::connect();
			$where = ' WHERE product LIKE "%' . trim($query) . '%" OR data  LIKE "%' . trim($query) . '%"';
			if (!empty($app_id)) {
				$where = ' WHERE fo.id = ' . $app_id;
			}
			$query = $db->query('SELECT fo.id,  CONCAT_WS(\' \', fu.name,fu.surname)  as author, fd.name as department, date, date_for, fo.amount, currency, type_id, product, fex.item as expense_item, situation, data, decision, status_id, category, fs.name as status, payed_amount, director_comment, urgently
									FROM fin_applications fo
									LEFT JOIN fin_users fu ON fo.author_id=fu.id
									LEFT JOIN fin_departments fd ON fo.department_id=fd.id
									LEFT JOIN fin_articles fex ON fo.article_id=fex.id
									LEFT JOIN fin_status fs ON fo.status_id=fs.id
									LEFT JOIN (SELECT SUM(amount1) as payed_amount,app_id FROM `fin_operations` WHERE operation_type_id=2 GROUP BY app_id) foa ON foa.app_id=fo.id' . $where);
			return $query->getResultArray();
		}
	}


	public
	static function get_status($status_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM `fin_status` WHERE id = ' . $status_id);
		return $query->getFirstRow();
	}

	public
	static function select_query($join_keys = [])
	{
		$params = [
			'table' => ['fa' => 'fin_applications'],
			'columns' => [
				'id',
				'author_id',
				'date',
				'date_for',
				'amount',
				'currency',
				'type_id',
				'authority_id',
				'product',
				'situation',
				'data',
				'decision',
				'status_id',
				'category',
//				'payed_amount',
				'director_comment',
				'urgently',
				'base_app_id'
			],
			'columns_with_alias' => [

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
				'columns_with_alias' => [
					'name' => 'department_name',
				]
			];
		}

		if (in_array('fin_projects', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fp' => 'fin_projects'],
				'main_table_key' => 'project_id',
				'columns' => [],
				'columns_with_alias' => ['name' => 'project_name']
			];
		}

		if (in_array('fin_articles', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['far' => 'fin_articles'],
				'main_table_key' => 'article_id',
				'columns' => [],
				'columns_with_alias' => ['name' => 'article_name']
			];
		}

		if (in_array('fin_status', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fs' => 'fin_status'],
				'main_table_key' => 'status_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'status_name',
				]
			];
		}

		if (in_array('fin_users', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fu' => 'fin_users'],
				'main_table_key' => 'author_id',
				'columns' => ['account_id'],
				'columns_with_alias' => [
					'name' => 'author_name',
					'surname' => 'author_surname'
				],
				'custom_columns' => [
					'CONCAT_WS(\' \', fu.name, fu.surname)' => 'author'
				]
			];
		}

		if (in_array('custom_payed_amount', $join_keys)) {
			$params['custom_join'][] = [
				'columns' => ['payed_amount'],
				'query' => 'LEFT JOIN (SELECT
					SUM(amount1) as payed_amount,
					app_id
				FROM `fin_operations`
				WHERE operation_type_id = 2
				GROUP BY app_id) foa
				ON foa.app_id = fa.id '];

		}

		return $params;
	}

	public
	static function short_select_query($join_keys = [])
	{
		$params = [
			'table' => ['fa' => 'fin_applications'],
			'columns' => [
				'id',
				'date_for',
				'amount',
				'currency',
				'product',
			],
			'columns_with_alias' => [

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
				'columns_with_alias' => [
					'name' => 'department_name',
				]
			];
		}
		return $params;
	}
}
