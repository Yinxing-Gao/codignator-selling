<?php


namespace App\Models;

use Config;
use DatePeriod;
use DateInterval;
use DateTime;


class Salary
{
	public static function get_months()
	{
		return ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"];
	}

	public static function get_weeks()
	{
		return ["понеділок", "вівторок", "середа", "четвер", "п'ятниця", "субота", "неділя"];
	}

	public static function get_months_from_start()
	{
		//для вибору місяця
		$start = new DateTime('2020-01-01');
		$start->modify('first day of this month');
		$end = new DateTime(date('Y-m-d'));
		$end->modify('first day of next month');

		$months_names = Salary::get_months();
		$interval = DateInterval::createFromDateString('1 month');
		$period = new DatePeriod($start, $interval, $end);

		$months = [];
		foreach ($period as $dt) {
			$months[$dt->format("m.Y")] = $months_names[(int)$dt->format("m") - 1] . ' ' . $dt->format("Y");
		}
		return $months;
	}

	public
	static function change_hours($user_id, $date, $hours)
	{

		$hours_string = self::get_hours($user_id, $date);

		$db = Config\Database::connect();
		if (!empty($hours_string)) {
			$query = $db->query('UPDATE `fin_workers_hours` SET `hours`="' . $hours . '" WHERE id = ' . $hours_string->id);
			echo $hours;
			Accruals::payroll($user_id, $date, $hours, $hours_string->id);
		} else {
			$query = $db->query('INSERT INTO `fin_workers_hours`(`user_id`, `date`, `hours`, `human_date`)
 								VALUES (' . $user_id . ', ' . strtotime(date("d.m.Y", $date) . ' 00:00:00') . ', "' . $hours . '" , "' . date('d.m.Y', $date) . '")');
			Accruals::payroll($user_id, $date, $hours, $db->insertID());
		}
		$query->getResult();

		return ['status' => 'ok'];
	}


	public
	static function get_hours($user_id, $date)
	{
		$db = Config\Database::connect();
		$query = $db->query("SELECT `id`, `user_id`, `date`, `hours` FROM `fin_workers_hours` WHERE user_id = " . $user_id . " AND date = " . strtotime(date('d.m.Y', $date) . ' 00:00:00'));
		return $query->getFirstRow();
	}

	static function get_month_hours(array $user_ids, $month_year)
	{

		$month_year = !empty($month_year) ? strtotime('1.' . $month_year) : time();
		$first_day_of_this_month = strtotime('first day of this month 00:00:00', $month_year);
		$last_day_of_this_month = date('m.Y', time()) == date('m.Y', $month_year)
			? time()
			: strtotime('last day of this month 23:59:59', $month_year);
		if (!empty($user_ids)) {
			$db = Config\Database::connect();
			$query_row = '';
			foreach ($user_ids as $user_id) {
				$query_row .= $user_id['id'] . ', ';
			}
			$query = $db->query("SELECT `id`, `user_id`, `date`, `hours` FROM `fin_workers_hours` WHERE user_id IN (" . substr($query_row, 0, -2) . ") AND date >= " . $first_day_of_this_month . ' AND date <= ' . $last_day_of_this_month);
			return $query->getResultArray();
		}
	}

	static function get_month_hours_array(array $user_ids, $month_year)
	{
		$hours = self::get_month_hours($user_ids, $month_year);

		$result = [];
		if (!empty($hours)) {
			foreach ($hours as $hour_row) {
				$result[$hour_row['user_id']][date('d.m.Y', $hour_row['date'])] = $hour_row['hours'];
			}
		}
		return $result;
	}

////temp
////	static function set_hours()
////	{
////		$start = strtotime('1.01.2020');
////		$end = time();
////		$workers = User::get_active_users();
////
////		if (!empty($workers)) {
////			foreach ($workers as $worker) {
////				for ($date = $start; $date < $end; $date += 60 * 60 * 24) {
////					if (date("N", $date) != 7) {
////						self::change_hours($worker['id'], $date, 8);
////					}
////				}
////			}
////		}
////	}

	public // temp
	static function get_all_hours()
	{
		$db = Config\Database::connect();
		$query = $db->query("SELECT `id`, `user_id`, `date`, `hours` FROM `fin_workers_hours`");
		return $query->getResultArray();
	}
}
