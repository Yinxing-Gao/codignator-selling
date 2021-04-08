<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;
use function MongoDB\BSON\toJSON;


class Nova_poshta extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {

			Models\NovaPoshta::index();
			die();

			return $this->view('nova_poshta/list',
				[
					'telegram_info' => $telegram_info,
					'css' => ['telegram'],
					'js' => ['telegram'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

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
		Models\Telegram::start();
//		Models\Telegram::setWebhook();
		Models\Telegram::WebhookInfo();
	}

	public function send_hello_message()
	{
		Models\Telegram::send_hello_message();
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
}
