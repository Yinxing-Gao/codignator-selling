<?php


namespace App\Models;

use Config;

class Notifications
{
	public static function get_user_notifications($user_id)
	{
		$notifications = [];
		$result = DBHelp::select([
			'table' => ['fn' => 'fin_notifications'],
			'where' => ['user_id = ' . $user_id]
		]);
		if ($result['status'] == 'ok' && empty($result['result'])) {
			$notifications = $result['result'];
			foreach ($notifications as &$notification) {
				$notification['text'] = htmlspecialchars_decode($notification['text']);
			}
		}
		return $notifications;
	}

	public static function add($user_id, $message)
	{
		if (empty(DBHelp::select([
				'table' => ['fn' => 'fin_notifications'],
				'where' => [
					'text = "' . $message . '"',
					'user_id = ' . $user_id
				]]
		)['result'])) {
			return DBHelp::insert('fin_notifications', [
				'text' => $message,
				'user_id' => $user_id,
				'date' => time()
			]);
		}
	}
}
