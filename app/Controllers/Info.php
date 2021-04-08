<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Info extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return $this->view('info/update',
				[
					'css' => ['info']
				]
			);
		} else {
			return $this->view('info/update',
				[
					'css' => ['info']
				], 'out'
			);
		}
	}

	public function bugs()
	{
		if (!empty($this->user_id)) {
			return $this->view('info/bugs',
				[
					'bugs' => Models\Suggestions::get_suggestions(['where' => [
						'fs.type = "error"',
						'fs.author_id = ' . $this->user_id,
						'fs.status = "open"'
					]]),
					'css' => ['info'],
					'js' => ['suggestions']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function suggestions()
	{
		if (!empty($this->user_id)) {

			$suggestions = Models\Suggestions::get_suggestions(['where' => [
				'fs.type = "suggestion"',
				'fs.is_public = 1',
				'fs.status = "open"'
			]]);
			if (!empty($suggestions)) {
				foreach ($suggestions as &$suggestion) {
					$suggestion['voted'] = false;
					$suggestion['votes_amount'] = count($suggestion['votes']);
					if (!empty($suggestion['votes'])) {
						foreach ($suggestion['votes'] as $vote) {
							if ($vote['user_id'] == $this->user_id) {
								$suggestion['voted'] = true;
							}
						}
					}
				}
			}

			return $this->view('info/suggestions',
				[
					'suggestions' => $suggestions,
					'css' => ['table', 'info'],
					'js' => ['suggestions']
				]
			);
		} else {
			$suggestions = Models\Suggestions::get_suggestions(['where' => [
				'type = "suggestion"',
				'is_public = 1',
				'status = "open"'
			]]);
			if (!empty($suggestions)) {
				foreach ($suggestions as &$suggestion) {
					$suggestion['voted'] = false;
					$suggestion['votes_amount'] = count($suggestion['votes']);
				}
			}

			return $this->view('info/suggestions',
				[
					'suggestions' => $suggestions,
					'css' => ['info'],
					'js' => ['jquery-3.4.1.min', 'suggestions']
				], 'out'
			);
		}
	}

	public function all()
	{
		if (!empty($this->user_id)) {
			$suggestions = Models\Suggestions::get_suggestions(['where' => [
				'fs.status = "open"'
			]]);;
			if (!empty($suggestions)) {
				foreach ($suggestions as &$suggestion) {
					$suggestion['voted'] = false;
					$suggestion['votes_amount'] = count($suggestion['votes']);
					if (!empty($suggestion['votes'])) {
						foreach ($suggestion['votes'] as $vote) {
							if ($vote['user_id'] == $this->user_id) {
								$suggestion['voted'] = true;
							}
						}
					}
				}
			}
			return $this->view('info/all',
				[
					'suggestions' => $suggestions,
					'css' => ['info'],
					'js' => ['suggestions']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function add_suggestion_ajax()
	{
		if (!empty($_POST)) {
			echo json_encode(Models\Suggestions::add($_POST));
		}
	}

	public function change_suggestion_status_ajax($suggestion_id)
	{
		if (!empty($_POST['status'])) {
			$status = $_POST['status'];
			echo json_encode(Models\Suggestions::update($suggestion_id, ['status' => $status]));

			$suggestion = Models\Suggestions::get_suggestion(['where' => [
				'fs.id = ' . $suggestion_id
			]]);
			if (!empty($suggestion)) {
				$user = Models\User::get_user(['where' => [
					'fu.id = ' . $suggestion->author_id
				]], ['fin_contractors']);

				if ($user['status'] == 'ok' && !empty($user['result']) && ($user['result']->telegram_chat_id)) {
					$chat_id = $user['result']->telegram_chat_id;
					if ($status == 'close') {
						if ($suggestion->type == 'suggestion') {
							$message = 'Ідея, яку ви придумали ' . " \r\n" .
								'(' . $suggestion->title . '),  була втілена в FINEKO.' . " \r\n" .
								'Дякуємо, що робите наш сервіс кращим';
						} else {
							$message = 'Помилка, про яку ви повідомили нам раніше' . " \r\n" .
								'(' . $suggestion->title . ') була виправлена . ' . " \r\n" .
								'Дякуємо, що робите наш сервіс кращим';
						}
					}

					if (!empty($message)) {
						Models\Telegram::send_message($chat_id, $message);
					}
				}
			}
		}
	}

	public
	function delete_suggestion_ajax($suggestion_id)
	{
		echo json_encode(Models\Suggestions::delete($suggestion_id));
	}
}
