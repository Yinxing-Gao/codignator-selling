<?php


namespace App\Models;

use Config;
use Exception;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

class Telegram
{
	public static $bot_token = '';
	public static $api_url;

//	private static $finance_chat_id = "-346498924";
	public static function get_requests($params = [], $join = [])
	{
		$query_params = self::select_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_request($params = [], $join = [])
	{
		$query_params = self::select_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public static function start()
	{
		self::$bot_token = '1278233396:AAFBPyuKz4SBnCRnv2YcMAdP1TMnlt006fw';
		self::$api_url = 'https://api.telegram.org/bot' . self::$bot_token . '/';
	}

	public static function apiRequestJson($method, $parameters)
	{
		if (!is_string($method)) {
			var_dump("Method name must be a string\n");
			return false;
		}

		if (!$parameters) {
			$parameters = array();
		} else if (!is_array($parameters)) {
			var_dump("Parameters must be an array\n");
			return false;
		}

		$parameters["method"] = $method;

		$handle = curl_init();

		curl_setopt($handle, CURLOPT_URL, self::$api_url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($handle, CURLOPT_TIMEOUT, 60);
		curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
		curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		return self::exec_curl_request($handle);
	}

	public static function exec_curl_request($handle)
	{
		$response = curl_exec($handle);

		if ($response === false) {
			$errno = curl_errno($handle);
			$error = curl_error($handle);
			var_dump("Curl returned error $errno: $error\n");
			curl_close($handle);
			return false;
		}

		$http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
		curl_close($handle);

		if ($http_code >= 500) {
			// do not wat to DDOS server if something goes wrong
			sleep(10);
			return false;
		} else if ($http_code != 200) {
			$response = json_decode($response, true);
			var_dump("Request has failed with error {$response['error_code']}: {$response['description']}\n");
			if ($http_code == 401) {
				throw new Exception('Invalid access token provided');
			}
			return false;
		} else {
			$response = json_decode($response, true);
			if (isset($response['description'])) {
				var_dump("Request was successfull: {$response['description']}\n");
			}
			$response = $response['result'];
		}

		return $response;
	}

	public static function send_message($chat_id, $text)
	{
		self::start();
		self::apiRequestJson("sendMessage",
			array('chat_id' => $chat_id,
				"text" => $text,
				'disable_notification' => true,
				'parse_mode' => 'HTML',
			));
		return ['status' => 'ok'];
		die();
	}

	public static function send_message_with_buttons($chat_id, $text, $menu = 'main')
	{
		if (!empty($chat_id)) {
			switch ($menu) {
				case 'main':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Отримати дані", "callback_data" => '/get_data'],
							["text" => "Додати дані", "callback_data" => '/add_data'],
						],
						[
							["text" => "Перейти в FINEKO", "url" => "http://app.fineko.space/"]
						],
						[
							["text" => "Зареєструвати код", "callback_data" => '/reg_code']
						]
					]);
					break;
				case 'get_data':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Мої каси", "callback_data" => '/my_wallets'],
						],
						[
							["text" => "Мої операції", "callback_data" => '/my_operations'],
							["text" => "Мої плановані операції", "callback_data" => '/my_planned_operations']
						],
						[
							["text" => "Мої проекти", "callback_data" => '/my_projects']
						],
						[
							["text" => "Курс валют", "callback_data" => '/kurs']
						]
					]);
					break;
				case 'get_data_general':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Курс валют", "callback_data" => '/kurs']
						]
					]);
					break;
				case 'add_data':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Додати здійснену операцію", "callback_data" => '/add_operation'],
							["text" => "Додати плановану операцію", "callback_data" => '/add_planned_operation'],
						],
						[
							["text" => "Додати заявку", "callback_data" => '/add_app']
						],
						[
							["text" => "Додати проект", "callback_data" => '/add_project']
						]
					]);
					break;
				case 'add_operation':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Дохід", "callback_data" => '/add_income'],
							["text" => "Витрата", "callback_data" => '/add_expense'],
							["text" => "Переміщення", "callback_data" => '/add_transfer']
						],
					]);
					break;
				case 'add_income_amount':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Змінити гаманець", "callback_data" => '/add_income_change_wallet'],
							["text" => "Змінити дату", "callback_data" => '/add_income_change_date'],
							["text" => "Відмінити", "callback_data" => '/main']
						],
					]);
					break;
				case 'add_expense_amount':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Змінити гаманець", "callback_data" => '/add_expense_change_wallet'],
							["text" => "Змінити дату", "callback_data" => '/add_expense_change_date'],
							["text" => "Відмінити", "callback_data" => '/main']
						],
					]);
					break;
				case 'add_transfer_amount':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Змінити гаманець", "callback_data" => '/add_transfer_change_wallet'],
							["text" => "Змінити дату", "callback_data" => '/add_transfer_change_date'],
							["text" => "Відмінити", "callback_data" => '/main']
						],
					]);
					break;
				case 'add_planned_operation':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Дохід", "callback_data" => '/add_planned_income'],
							["text" => "Витрата", "callback_data" => '/add_planned_expense'],
							["text" => "Переміщення", "callback_data" => '/add_planned_transfer']
						],
					]);
					break;
				case 'go_to_web':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Перейти у FINEKO", "url" => "http://app.fineko.space/"],
							["text" => "Повернутися в головне меню", "callback_data" => '/main']
						],
					]);
					break;
				case 'wrong_code':
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Спробувати знову", "callback_data" => '/reg_code'],
							["text" => "Повернутися в головне меню", "callback_data" => '/main']
						],
					]);
					break;

				default:
					$keyboard = array("inline_keyboard" => [
						[
							["text" => "Отримати дані", "callback_data" => '/get_data'],
						],
						[
							["text" => "Перейти в FINEKO", "url" => "http://app.fineko.space/"]
						],
						[
							["text" => "Зареєструвати код", "callback_data" => '/reg_code']
						]
					]);
					break;
			}

			self::apiRequestJson("sendMessage",
				[
					'chat_id' => $chat_id,
					"text" => $text,
					'disable_notification' => true,
					'parse_mode' => 'HTML',
					'reply_markup' => json_encode($keyboard)
				]);
			return ['status' => 'ok'];
			die();
		}
	}

	public static function send_admin_message($text, $type = 'notification')
	{
		switch ($type) {
			case 'chat':
				TelegramChat::start();
				break;
			default:
				self::start();
				break;
		}

		self::apiRequestJson("sendMessage",
			array('chat_id' => 345126254,
				"text" => $text,
				'disable_notification' => true,
				'parse_mode' => 'HTML'));
		return ['status' => 'ok'];
//		die();
	}

	public static function send_personal_message($user_id, $text)
	{
		self::start();
		$user = User::getUser($user_id)['user'];
		if (!empty($user) && !empty($user->telegram_chat_id)) {
			self::apiRequestJson("sendMessage",
				array('chat_id' => $user->telegram_chat_id,
					"text" => $text,
					'disable_notification' => true,
					'parse_mode' => 'HTML'));
			return ['status' => 'ok'];
		} else {
			return ['status' => 'error', 'message' => 'Не знайдений користувач'];
		}
	}

	public static function setWebhook()
	{
//		echo base_url('/telegram/api','https');
		$updates = self::apiRequestJson('setWebhook',
			[
				'url' => base_url('/telegram/chat_api', 'https'),
				'allowed_updates' => 'message'
			]
		);

	}

	public static function WebhookInfo()
	{
//		echo base_url('/telegram/api','https');
		var_dump(self::apiRequestJson('getWebhookInfo', []));

	}

	public static function process_message($post)
	{
		$chat_id = $post['message']['from']['id'];
		$username = !empty($post['message']['from']['username']) ? $post['message']['from']['username'] : '';
		$message = trim($post['message']['text']);

		$prev_request = self::get_request(['where' => ['chat_id = ' . $chat_id]]);

		self::add([
			'chat_id' => $chat_id,
			'user_name' => $username,
			'type' => 'message',
			'text' => $message,
		]);

		$contractor = Contractors::get_contractor(['where' => [
			'telegram_chat_id = ' . $chat_id,
			'user_id != 0'
		]]);

		$new_process = true;
		if (!empty($prev_request)) {
			if ($prev_request->type == 'callback') {
				switch ($prev_request->text) {
					case '/reg_code':
						$new_process = false;
						$contractors = Contractors::get_contractors(['where' => ['telegram_code = "' . $message . '"']]);
						if (!empty($contractors)) {

							foreach ($contractors as $contractor) {
								Contractors::update($contractor['id'], ['telegram_chat_id' => $chat_id]);
							}
							self::send_message_with_buttons($chat_id, 'Дякуємо, код знайдено. Тепер ви будете отримувати відповідні сповіщення в даному чаті', 'go_to_web');
						} else {
							self::send_message_with_buttons($chat_id, 'Код невірний', 'wrong_code');
						}
						break;
					case '/add_income':
						$new_process = false;
						if (!empty($contractor)) {
							// todo додати перірку чи сума -числове значення
							$default_wallet = Wallets::get_wallet(['where' => [
								'user_id = ' . $contractor->user_id,
								'is_default = 1'
							]]);

							self::add_update_operation($chat_id, [
								'operation_type_id' => 1,
								'currency' => 'UAH',
								'date' => time(),
								'amount' => (float)$message,
								'wallet_id' => $default_wallet->id,
								'user_id' => $contractor->user_id,
								'account_id' => $contractor->account_id
							]);

							self::send_message_with_buttons($chat_id, 'Введіть коментар до цієї операції ', 'add_income_amount');
						}
						break;
					case '/add_expense':
						$new_process = false;
						if (!empty($contractor)) {
							// todo додати перірку чи сума -числове значення
							$default_wallet = Wallets::get_wallet(['where' => [
								'user_id = ' . $contractor->user_id,
								'is_default = 1'
							]]);

							self::add_update_operation($chat_id, [
								'operation_type_id' => 2,
								'currency' => 'UAH',
								'date' => time(),
								'amount' => (float)$message,
								'wallet_id' => $default_wallet->id,
								'user_id' => $contractor->user_id,
								'account_id' => $contractor->account_id
							]);

							self::send_message_with_buttons($chat_id, 'Введіть коментар до цієї операції ', 'add_expense_amount');
						}
						break;
					case '/add_transfer':
						break;
				}
			} else {
				$prev_request = self::get_request(['where' => [
					'chat_id = ' . $chat_id,
					'type = "callback"'
				]]);

				$intermediate_requests_amount = count(self::get_requests(['where' => [
					'date > ' . $prev_request->date,
					'chat_id = ' . $chat_id
				]]));

				switch ($prev_request->text) {
					case '/add_income':
						if ($intermediate_requests_amount == 2) {
							$new_process = false;
							self::add_update_operation($chat_id, [
								'comment' => $message,
								'time_type' => 'real'
							]);
							self::send_message_with_buttons($chat_id, 'Операція приходу успішно збережена ', 'main');
						}
						break;
					case '/add_expense':
						if ($intermediate_requests_amount == 2) {
							$new_process = false;
							self::add_update_operation($chat_id, [
								'comment' => $message,
								'time_type' => 'real'
							]);

							self::send_message_with_buttons($chat_id, 'Операція витрати успішно збережена ', 'main');
						}
						break;
					case '/add_transfer':
						if ($intermediate_requests_amount == 2) {
							$new_process = false;
							self::add_update_operation($chat_id, [
								'comment' => $message,
								'time_type' => 'real'
							]);

							self::send_message_with_buttons($chat_id, 'Операція переміщення успішно збережена ', 'main');
						}
						break;
				}
			}
		}

		if ($new_process) {
			if (!empty($contractor)) {
				file_put_contents('writable/telegram/messages.txt', json_encode($message . " \r\n \r\n"), FILE_APPEND);
				if (strripos($message, '/help') !== false) {
					self::send_help();
					return [];
				}
				self::send_message_with_buttons($chat_id, 'Привіт. Я Фінансовий бот. Вибери необідний пункт в меню)', 'main');
			} else {
				self::send_message_with_buttons($chat_id, 'Привіт. Я Фінансовий бот. Вибери необідний пункт в меню)', 'default');
			}
		}
	}

	public
	static function process_callback($query)
	{
		$chat_id = $query['message']['chat']['id'];
		$username = !empty($query['message']['chat']['username']) ? $query['message']['chat']['username'] : '';
		$command = $query['data'];

		self::add([
			'chat_id' => $chat_id,
			'user_name' => $username,
			'type' => 'callback',
			'text' => $command,
		]);

		$user_result = User::get_user(['where' => [
			'telegram_chat_id = ' . $chat_id
		]], ['fin_contractors']);

		$default_wallet = Wallets::get_wallet(['where' => [
			'user_id = ' . $user_result['result']->id,
			'is_default = 1'
		]]);

		if ($user_result['status'] === 'ok' && !empty($user_result['result'])) {
			switch ($command) {
				case '/main':
					self::send_message_with_buttons($chat_id, 'Головне меню. Виберіть пункт нижче', '/main');
					break;
				case '/get_data':
					self::send_message_with_buttons($chat_id, 'Що вас цікавить?', 'get_data');
					break;
				case '/get_data_general':
					self::send_message_with_buttons($chat_id, 'Що вас цікавить?', 'get_data_general');
					break;
				case '/my_wallets':
					self::send_my_wallets($chat_id, $user_result['result']->id);
					break;
				case '/my_operations':
					self::send_my_operations($chat_id, $user_result['result']->id);
					break;
				case '/my_planned_operations':
					self::send_my_planned_operations($chat_id, $user_result['result']->id);
					break;
				case '/my_projects':
					self::send_my_projects($chat_id, $user_result['result']->id);
					break;
				case '/kurs':
					self::send_kurs($chat_id);
					break;
				case '/add_data':
					self::send_message_with_buttons($chat_id, 'Додати дані', 'add_data');
					break;
				case '/add_operation':
					self::send_message_with_buttons($chat_id, 'Додати операцію', 'add_operation');
					break;
				case '/add_income':
					self::send_message_with_buttons($chat_id, '<b>Додати дохід.</b>' . " \r\n" .
						'Гаманець (по замовчуванню)  - ' . $default_wallet->name . " \r\n" .
						'Дата (по замовчуванню) - сьогодні' . " \r\n" . " \r\n" .
						'Введіть, будь ласка, суму операції ' . " \r\n",
						'add_income_amount');
					break;
				case '/add_expense':
					self::send_message_with_buttons($chat_id, '<b>Додати витрату.</b>' . " \r\n" .
						'Гаманець (по замовчуванню)  - ' . $default_wallet->name . " \r\n" .
						'Дата (по замовчуванню) - сьогодні' . " \r\n" . " \r\n" .
						'Введіть, будь ласка, суму операції ' . " \r\n",
						'add_expense_amount');
					break;
				case '/add_transfer':
					self::send_message_with_buttons($chat_id, '<b>Додати переміщення.</b>' . " \r\n" .
						'Гаманець (по замовчуванню)  - ' . $default_wallet->name . " \r\n" .
						'Дата (по замовчуванню) - сьогодні' . " \r\n" . " \r\n" .
						'Введіть, будь ласка, суму операції ' . " \r\n",
						'add_transfer_amount');
					break;
				case '/add_planned_operation':
					self::send_message_with_buttons($chat_id, 'Додати плановану операцію', 'add_planned_operation');
					break;
				case '/reg_code':
					self::send_message($chat_id, 'Вставте, будь ласка, код нижче');
					break;
			}
		} else {
			switch ($command) {
				case '/main':
					self::send_message_with_buttons($chat_id, 'Головне меню. Виберіть пункт нижче', 'default');
					break;
				case '/kurs':
					self::send_kurs($chat_id);
					break;
				case '/reg_code':
					self::send_message($chat_id, 'Вставте, будь ласка, код нижче');
					break;
				case '/get_data':
					self::send_message_with_buttons($chat_id, 'Що вас цікавить?', 'get_data_general');
					break;
			}
		}
	}

	public static function add($atts)
	{
		if ((!empty($atts['chat_id']) || !empty($atts['user_id']))
			&& !empty($atts['text']) && !empty($atts['type'])) {
			$params = [
				'chat_id' => !empty($atts['chat_id']) ? $atts['chat_id'] : 0,
				'user_id' => !empty($atts['user_id']) ? $atts['user_id'] : 0,
				'type' => $atts['type'],
				'text' => $atts['text'],
				'status' => !empty($atts['status']) ? $atts['status'] : "",
				'date' => time()
			];
			return DBHelp::insert('fin_telegram_requests', $params);
		}
	}

	public static function update($id, $params)
	{
		return DBHelp::update('fin_telegram_requests', $id, $params);
	}

	public static function add_update_operation($chat_id, $params)
	{
		$operation = Operation::get_operation(['where' => [
			'chat_id = ' . $chat_id,
			'time_type = "temp"']]);
		if ($operation['status'] == 'ok') {
			if (!empty($operation['result'])) {
				$operation = $operation['result'];
				Operation::update($operation->id, $params);
			} else {
				Operation::add(array_merge([
					'time_type' => 'temp',
					'chat_id' => $chat_id
				], $params));
			}
		}
	}

	public
	static function send_hello_message()
	{
		$users = User::get_users();
		if (!empty($users)) {
			foreach ($users as $user) {
				$text = 'Привіт,' . $user['name'] . ' ' . $user['surname'] . ', я фінансовий бот, я буду сповіщати
				тебе про зміни статусів в твоїх заявках, нагадувати
				за важливі речі у фінансовій сфері і виконувати інші
				 важливі функції. Приємно познайомитися)
				 Ссилка на фін.систему - http://fin.ekonombud.in.ua/';
				self::send_personal_message($user['id'], $text);
			}
		}
	}

	public
	static function send_help()
	{
		$help_message = '/help - допомога ( список операцій )' . " \r\n";
		$help_message .= '/site - перейти в фін.систему' . " \r\n";
		$help_message .= '/my_apps - мої заявки' . " \r\n";
		$help_message .= '/apps - всі заявки' . " \r\n";
		$help_message .= '/kurs - курс валют' . " \r\n";
		$help_message .= '/my_projects- мої проекти' . " \r\n";
		$help_message .= '/projects- проекти' . " \r\n";
		$help_message .= '/contractors- список контрагентів' . " \r\n";
		$help_message .= '/my_wallets- мої каси' . " \r\n";
		$help_message .= '/wallets - каси департаменту' . " \r\n";
		$help_message .= '/tov - каси ТОВ' . " \r\n";
		$help_message .= " \r\n";
		$help_message .= '<b>Додавання витрати:</b>' . " \r\n";
		$help_message .= '/номер заявки' . " \r\n";
		$help_message .= '-сума(валюту писати не потрібно, по замовчуванню стоїть грн), контрагента, коментар, проект (необов\'язково, можна писати слово проект)' . " \r\n";
		$help_message .= " \r\n";
		$help_message .= '<i>Приклад:' . " \r\n";
		$help_message .= '/51' . " \r\n";
		$help_message .= '-500, Бондар Андрей, разходы по складу ' . " \r\n";
		$help_message .= '-300, ВИ, разходники, проект Днепр' . " \r\n";
		$help_message .= '-700, Калюжный, зп </i>' . " \r\n";
		$help_message .= '(може бути як одна операція так і одразу кілька)' . " \r\n";
		$help_message .= " \r\n";
		$help_message .= '<b>Додавання приходу:</b>' . " \r\n";
		$help_message .= '+сума(валюту писати не потрібно, по замовчуванню стоїть грн), контрагент, коментар ,проекту' . " \r\n";
		$help_message .= " \r\n";
		$help_message .= '<i>Приклад:' . " \r\n";
		$help_message .= '+500000, Киричок В.І., напилення ППУ, Дніпро' . " \r\n";
		$help_message .= '+300, СС, використання автомобіля в власних цілях' . " \r\n";
		$help_message .= '+700, Петренко А., Продаж компонентів ППУ</i>' . " \r\n";
		$help_message .= '(може бути як одна операція так і одразу кілька)' . " \r\n";
		$help_message .= " \r\n";
		$help_message .= '<b>Додавання переміщення:</b>' . " \r\n";
		$help_message .= '/номер заявки (необов\'язково)' . " \r\n";
		$help_message .= '*сума(валюту писати не потрібно, по замовчуванню стоїть грн), коментар, номер контрагента, номер проекту' . " \r\n";
		$help_message .= " \r\n";
		$help_message .= '<i>Приклад:' . " \r\n";
		$help_message .= '/51' . " \r\n";
		$help_message .= '*500, ВИ, на розходніки, Днепр' . " \r\n";
		$help_message .= '*300, Мацук, оплата реклами' . " \r\n";
		$help_message .= '*700, Бондар, база </i>' . " \r\n";
		$help_message .= '(може бути як одна операція так і одразу кілька)' . " \r\n";
		$help_message .= " \r\n";
		self::send_message($help_message);
	}

	public
	static function send_kurs($chat_id)
	{
		$rates = CurrencyRate::get_exchange_rates_all();
		if (!empty($rates)) {
			$message_text = '';
			foreach ($rates AS $currency => $rate) {
				$message_text .= $currency . " \r\n";
				$message_text .= 'купівля - ' . round($rate['buy'], 2) . " \r\n";
				$message_text .= 'продаж - ' . round($rate['sale'], 2) . " \r\n";
				$message_text .= " \r\n";
			}
			self::send_message($chat_id, $message_text);
		}
	}

	public
	static function send_my_apps($chat_id, $user_id)
	{
		$applications = Applications::get_apps(['where' => ['author_id = ' . $user_id]]);
		if (!empty($applications)) {
			$app_message = '<pre>';
			foreach ($applications as $application) {
				$app_message .= $application['id'] . ") " . $application['product'] . " \r\n";
			}
			$app_message .= '</pre>';
			self::send_message($chat_id, $app_message);
		}
	}

	public
	static function send_my_wallets($chat_id, $user_id)
	{
		$wallets = Wallets::get_wallets(['where' => ['user_id = ' . $user_id]
		]);
		if (!empty($wallets)) {
			$app_message = '<pre>';
			foreach ($wallets as $wallet) {
				$app_message .= $wallet['id'] . ") " . $wallet['name'] . ' - ' . (float)$wallet['checkout'] . ' ' . $wallet['currency'] . " \r\n";
			}
			$app_message .= '</pre>';
			self::send_message($chat_id, $app_message);
		}
		return [];
	}

	public
	static function send_my_operations($chat_id, $user_id)
	{
		$operations = Operation::get_user_operations((int)$user_id,
			['where' => [
				'fo.time_type = "real"',
				'fo.date >' . (time() - 60 * 60 * 24 * 30)
			]]);

		if (!empty($operations)) {
			$app_message = '<b>Доходи:</b>' . " \r\n";
			foreach ($operations as $operation) {
				if ($operation['operation_type_id'] == 1) {
					$app_message .= date('d.m.Y', $operation['date']) . " - " . $operation['amount2'] . ' ' . $operation['currency2'] . ' ' . $operation['comment'] . " \r\n";
				}
			}
			$app_message .= " \r\n" . '<b>Витрати:</b>' . " \r\n";
			foreach ($operations as $operation) {
				if ($operation['operation_type_id'] == 2) {
					$app_message .= date('d.m.Y', $operation['date']) . " - " . $operation['amount1'] . ' ' . $operation['currency1'] . ' ' . $operation['comment'] . " \r\n";
				}
			}

		} else {
			$app_message = 'Операцій не знайдено';
		}
		self::send_message($chat_id, $app_message);
		return [];
	}

	public
	static function send_my_planned_operations($chat_id, $user_id)
	{
		$operations = Operation::get_user_operations((int)$user_id,
			['where' => [
//				'is_planned = 1',
//				'is_template = 0',
				'fo.time_type = "plan"',
				'fo.planned_on <' . (time() + 60 * 60 * 24 * 30)
			]]);

		if (!empty($operations)) {
//			$app_message = '<pre>';
			$app_message = '<b>Плановані доходи:</b>' . " \r\n";
			foreach ($operations as $operation) {
				if ($operation['operation_type_id'] == 1) {
					$app_message .= date('d.m.Y', $operation['planned_on']) . " - " . $operation['amount2'] . ' ' . $operation['currency2'] . ' ' . str_replace("<br/>", " \r\n", htmlspecialchars_decode($operation['comment'])) . " \r\n";
				}
			}
			$app_message .= " \r\n" . '<b>Плановані витрати:</b>' . " \r\n";
			foreach ($operations as $operation) {
				if ($operation['operation_type_id'] == 2) {
					$app_message .= date('d.m.Y', $operation['planned_on']) . " - " . $operation['amount1'] . ' ' . $operation['currency1'] . ' ' . str_replace("<br/>", " \r\n", htmlspecialchars_decode($operation['comment'])) . " \r\n" . " \r\n";
				}
			}
//			$app_message .= '</pre>';
		} else {
			$app_message = 'Планованих операцій не знайдено';
		}
		self::send_message($chat_id, $app_message);

		return [];
	}

	public
	static function send_wallets($chat_id, $user_id)
	{
		$wallets = Wallets::get_department_wallets($user_id);
		if (!empty($wallets)) {
			$app_message = '<pre>';
			foreach ($wallets as $wallet) {
				$app_message .= $wallet['id'] . ") " . $wallet['name'] . ' - ' . (float)$wallet['checkout'] . ' ' . $wallet['currency'] . " \r\n";
			}
			$app_message .= '</pre>';
			self::send_message($chat_id, $app_message);
		}
	}

	public
	static function send_my_projects($chat_id, $user_id)
	{
		$projects = Projects::get_projects(['where' => [
			'fp.author_id = ' . $user_id,
			'status != "finished"'
		]]);
		$project_message = '';
		if (!empty($projects)) {
			$project_message .= '<b>Мої проекти:</b>' . " \r\n";
			foreach ($projects as $project) {
				$project_message .= $project['id'] . ") " . $project['name'] . " \r\n";
			}
		}

		$user_result = User::get_user(['where' => ['id = ' . $user_id]]);

		if ($user_result['status'] == 'ok' && !empty($user_result['result'])) {
			$projects = Projects::get_projects(['where' => [
				'fp.account_id = ' . $user_result['result']->account_id,
				'status != "finished"'
			]]);
			if (!empty($projects)) {
				$project_message .= " \r\n" . '<b>Спостерігаю:</b>' . " \r\n";
				foreach ($projects as $project) {
					if (in_array($user_id, explode(',', $project['observers_ids']))) {
						$project_message .= $project['id'] . ") " . $project['name'] . " \r\n";
					}
				}
			}
		}
		self::send_message($chat_id, $project_message);
	}

	public
	static function send_contractors($chat_id, $user_id)
	{
		$user_result = User::get_user(['where' => ['id = ' . $user_id]]);
		if ($user_result['status'] == 'ok' && !empty($user_result['result'])) {
			$contractors = Contractors::get_contractors(['where' => [
				'fp.account_id = ' . $user_result['result']->account_id
			]]);
			if (!empty($contractors)) {
				$contractor_message = '<pre>';
				foreach ($contractors as $contractor) {
					$contractor_message .= $contractor['id'] . ") " . $contractor['name'] . " \r\n";
				}
				$contractor_message .= '</pre>';
				self::send_message($chat_id, $contractor_message);
			}
		}
	}

	public
	static function select_query($join_keys = [])
	{
		return [
			'table' => ['ftr' => 'fin_telegram_requests'],
			'columns' => [
				'id',
				'chat_id',
				'user_id',
				'type',
				'text',
				'date'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [

			],
			'order_by' => 'date DESC',
			'limit' => null,
			'offset' => null
		];
	}
}
