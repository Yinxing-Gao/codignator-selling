<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;
use function MongoDB\BSON\toJSON;


class Telegram extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {

			$telegram_info = [];
			if (!empty($this->user->telegram_chat_id)) {
				Models\Telegram::start();

				$telegram_info = Models\Telegram::apiRequestJson('getChatMember', [
					'chat_id' => $this->user->telegram_chat_id,
					'user_id' => (int)$this->user->telegram_chat_id,
				])['user'];
			};

			return $this->view('telegram/list',
				[
					'telegram_info' => $telegram_info,
					'css' => ['table', 'telegram'],
					'js' => ['telegram'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}


	public function generate_code_ajax()
	{
		try {
			$contractor = Models\Contractors::get_contractor(['where' => ['user_id =' . $this->user_id]]);
			if (!empty($contractor)) {
				Models\Contractors::update($contractor->id, ['telegram_chat_id' => '']);
				if (!empty($contractor->telegram_code)) {
					$code = $contractor->telegram_code;
				} else {
					$code = sha1($contractor->id . '_contractor');
					Models\Contractors::update($contractor->id, [
						'telegram_code' => $code
					]);
				}

				return json_encode(['status' => 'ok', 'code' => $code]);
			}
		} catch (\Exception $e) {
			Models\Dev::var_dump($e);
		}
	}

	public function generate_contractor_code_ajax($contractor_id)
	{
		try {
			$contractor = Models\Contractors::get_contractor(['where' => ['id =' . $contractor_id]]);
			if (!empty($contractor)) {
				if (!empty($contractor->telegram_code)) {
					$code = $contractor->telegram_code;
				} else {
					$code = sha1($contractor_id . '_contractor');
					Models\Contractors::update($contractor_id, [
						'telegram_code' => $code
					]);
				}

				return json_encode(['status' => 'ok', 'code' => $code, 'message' =>
					'Скопіюйте і відправте контрагенту дане повідомлення:<br/><br/>' .
					'<p style="font-size:14px" >Шановний ' . $contractor->name . '.</br>' .
					'Запрошуємо вас приєднатися до фін.системи FINEKO, ' .
					'яка дозволяє нам планувати фінансові операції і вести управлінський облік.' .
					'Для того, щоб отримувати сповіщення в Telegram про всі плановані фінансові операції між нами,' .
					'перейдіть в чат з ботом FINEKO ( <a href="https://t.me/FinekoBot" >https://t.me/FinekoBot )</a> ' .
					'і відправте йому наступний код: <br/><br/>' .
					$code .
					'</p>'
				]);
			}
		} catch (\Exception $e) {
			Models\Dev::var_dump($e);
		}
	}

	public function check_code_ajax()
	{
		$contractor = Models\Contractors::get_contractor(['where' => ['user_id =' . $this->user_id]]);
		if (!empty($contractor->telegram_chat_id)) {
			return json_encode(['status' => 'ok']);
		} else {
			return json_encode(['status' => 'error']);
		}
	}

	// BOT
	public static function api()
	{
		Models\Telegram::start();
		$inputJSON = file_get_contents('php://input');
		file_put_contents('writable/telegram/requests.txt', $inputJSON, FILE_APPEND);
//		var_dump(json_decode($inputJSON));

		$request = json_decode($inputJSON, true);
		if (!empty($request['message']['text'])) {
			Models\Telegram::process_message($request);
		} elseif (!empty($request['callback_query'])) {
			Models\Telegram::process_callback($request['callback_query']);
		}
//		Models\TelegramTemp::index(json_decode($inputJSON, true));
		return [];//після додавання операції виводити баланс
	}

	public static function set_webhook()
	{
//		Models\Telegram::start();
		Models\TelegramChat::start();
		Models\Telegram::setWebhook();
		Models\Telegram::WebhookInfo();
	}

	public function send_hello_message()
	{
		Models\Telegram::send_hello_message();
	}

	// chat
	public static function chat_api()
	{
		Models\TelegramChat::start();
		$inputJSON = file_get_contents('php://input');
		file_put_contents('writable/telegram/requests.txt', $inputJSON, FILE_APPEND);

		$request = json_decode($inputJSON, true);
		if (!empty($request['message']['text'])) {
			Models\TelegramChat::chat_reply($request);
		}
		return [];
	}

	public function get_chat_messages_ajax($user_id)
	{
		$messages = Models\Telegram::get_requests(['where' => [
			'user_id =' . $user_id,
			'(type = "chat_reply" OR type = "chat_message")'
		], 'order_by' => 'date ASC']);
		if (!empty($messages)) {
			foreach ($messages as &$message) {
				$message['time'] = date('d.m.y H:i', $message['date']);
			}
		}
		return json_encode(['status' => 'ok', 'messages' => $messages]);
	}

	public function chat_send_message_ajax($user_id)
	{
		if (!empty($_POST['message'])) {

			$message = $_POST['message'];
			Models\Telegram::add([
				'type' => 'chat_message',
				'text' => $message,
				'user_id' => $user_id
			]);

			$user = Models\User::get_user(['where' => [
				'id = ' . $user_id
			]]);

			if ($user['status'] == 'ok' && !empty($user['result'])) {
				$user = $user['result'];

				$prev_reply_to_result = Models\Telegram::get_request(['where' => [
					'type = "chat_change"'
				]]);

				$prev_reply_to = !empty($prev_reply_to_result) ? $prev_reply_to_result->user_id : null;

				if ((!empty($prev_reply_to) && $prev_reply_to !== $user->id) || empty($prev_reply_to)) {
					Models\TelegramChat::change_reply_to($user->id);
				}
				$message_with_params = '<b>' . $user->id . ') ' . $user->name . ' ' . $user->surname . '</b>' . " \r\n" . $message;

				Models\Telegram::send_admin_message($message_with_params, 'chat');
				return json_encode(['status' => 'ok', 'message' => $message, 'time' => date('H:i'), 'timestamp' => time()]);
			}
		}
		return json_encode(['status' => 'error', 'message' => 'something got wrong']);
	}

	public function check_chat_reply_ajax($user_id)
	{
		$timestamp = $_POST['timestamp'];
		$messages = Models\Telegram::get_requests(['where' => [
			'user_id =' . $user_id,
			'type = "chat_reply"',
			'date > ' . $timestamp,
			'status = "waiting"'
		]]);

		if (!empty($messages)) {
			foreach ($messages as &$message) {
				$message['time'] = date('d.m.y H:i', $message['date']);
				Models\Telegram::update($message['id'], ['status' => '']);
			}
		}

		return json_encode(['status' => 'ok', 'messages' => $messages]);
	}

}
