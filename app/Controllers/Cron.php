<?php namespace App\Controllers;

use App\Models;
use CodeIgniter\Model;
use Config;
use CodeIgniter\HTTP\RedirectResponse;
use function GuzzleHttp\Psr7\str;


class Cron extends BaseController
{
	public function index()
	{
		file_put_contents('cron.txt', date("d.m.Y H:m:i", time()), FILE_APPEND);
	}

	public function refresh_apps() // щосереди в 9 ранку
	{
//		Models\Applications::refresh_apps();
//		Models\Applications::refresh_dates();// переписати, термінові мають переноситися на завтра щодня
//		Models\Telegram::start();
//		Models\Telegram::send_message("Всі просрочені заявки перенесено на наступний фінансовий тиждень");
		//Доброго дня. Має бути так - 0 19 * * 1 . Ви можете користуватись онлайн-калькулятором, наприклад тут - https://crontab.guru/ . Інструкція - https://wiki.ukraine.com.ua/hosting:cron:add
		//https://wiki.ukraine.com.ua/hosting:cron:add
	}

//	public function create_repeat_apps() // щопонеділка в 20:00
//	{
//		Models\Applications::create_repeated_apps();
//	}

	public function set_week_checkout() //  фіксація суми в гаманцях щовівторка о 00:00
	{
		$wallets = Models\Wallets::get_wallets();
		if (!empty($wallets)) {
			foreach ($wallets as $wallet) {
				Models\Wallets::add_update_checkout([
					'wallet_id' => $wallet['id'],
					'type' => 'week',
					'amount' => $wallet['checkout'],
					'planned_amount' => $wallet['checkout']
//					'planned_amount' => $wallet['planned_checkout']
				]);
				//todo дописати створення чекаутів на місяць вперед або до найближчої операції далі
//				$plan_checkouts = Models\Wallets::count_plan_checkout($wallet['id'], $wallet['planned_checkout']);
//				if (!empty($plan_checkouts)) {
//					foreach ($plan_checkouts as $plan_checkout) {
//						Models\Wallets::add_update_checkout([
//							'wallet_id' => $wallet['id'],
//							'type' => 'week',
//							'planned_amount' => $plan_checkout['amount']
//						]);
//					}
//				}
//				Models\Dev::var_dump($plan_checkouts);
			}
		}
	}

	public function set_month_checkout() // фіксація суми в гаманцях щомісяця 1 числа о 00:05
	{
		$wallets = Models\Wallets::get_wallets();
		if (!empty($wallets)) {
			foreach ($wallets as $wallet) {
				Models\Wallets::add_update_checkout([
					'wallet_id' => $wallet['id'],
					'type' => 'month',
					'amount' => $wallet['checkout'],
					'planned_amount' => $wallet['checkout']
//					'planned_amount' => $wallet['planned_checkout']
				]);
			}
		}
	}

	//цей крон отримує операції по всіх картах юр.лиць і персональних кожні 15 хв
	public function get_bank_statements()
	{
		$recorded_operations = Models\Operation::get_operations_from_all_active_cards();

		file_put_contents('writable/cron/get_bank_statements/log.txt', json_encode(
			date('d.m.Y H:i:s') . " \r\n" .
			json_encode($recorded_operations) . " \r\n" .
			"\r\n\r\n"
		), FILE_APPEND);

		// сповіщення щодо задач
		$tasks = Models\Tasks::get_tasks(['where' => [
			'notify = 1',
			'date_to > ' . time(),
			'date_to <=' . (time() + 60 * 15),
		]]);
		if (!empty($tasks)) {
			foreach ($tasks as $task) {
				if (!empty($task['telegram_chat_id'])) {
					$text = "<b>Нагадування про задачу:</b>" . " \r\n" .
						$task['task'] . " \r\n" .
						$task['comment'] . " \r\n" .
						date('d.m.Y H:i', $task['date_to']) . " \r\n";
					Models\Telegram::send_message($task['telegram_chat_id'], $text);
				}
			}
		}
	}

	public function update_planned_operations() // щодня в 00:00:00
	{
//		перенесенння невиконаних планових операцій з минулого
		$operations = Models\Operation::get_operations(['where' => [
			'time_type = "plan"',
			'planned_on < ' . time()
		]]);

		if (!empty($operations)) {
			foreach ($operations as $operation) {
				$new_planned_on = strtotime('today 00:00:00') + random_int(60 * 60 * 8, 60 * 60 * 18);

				Models\Operation::update($operation['id'], [
					'planned_on' => $new_planned_on,
					'comment' => htmlspecialchars_decode($operation['comment']) . "<br/><br/>" . 'Планована дата змінена з  ' . date('d.m.Y', $operation['planned_on']) . ' на ' . date('d.m.Y', $new_planned_on)
				]);
			}
		}

		//створення плановани операцій з шаблону
		Models\Operation::create_from_templates();
	}
}

