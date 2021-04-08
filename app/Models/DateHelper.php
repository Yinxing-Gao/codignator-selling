<?php

namespace App\Models;

use DateTime;

class DateHelper
{
	public static $finance_day = 'Tuesday';

	//віддає дати за період
	public static function get_timestamps_of_period($period)
	{
		$periods_end_dates = [];
		switch ($period) {
			case "week": // дані за тиждень з періодом 1 день
				$date_from = strtotime('last ' . Settings::get('finance day'), time());
				$date_to = strtotime('next ' . Settings::get('finance day'), time()) - 1;
				$periods_end_dates[] = $date_from;
				for ($date = $date_from; $date < $date_to; $date += self::period('day')) {
					$periods_end_dates[] = $date;
				}
				break;
			case "month":// дані за місяць з періодом 1 тиждень
				$date_from = strtotime('first day of this month 00:00:00', time());
				$date_to = strtotime('last day of this month 23:59:59', time());
				$periods_end_dates[] = $date_from;
				for ($date = $date_from; $date < $date_to; $date += self::period('week')) {
					$periods_end_dates[] = $date;
				}
				if (date("d.m.Y", $date) != date("d.m.Y", $date_to)) {
					$periods_end_dates[] = $date_to;
				}
				break;
			case "year":// дані за рік з періодом 1 місяць
				$date_from = strtotime('first day of January', time());
				$periods_end_dates[] = $date_from;
				for ($i = 1; $i <= 12; $i++) {
					$periods_end_dates[] = strtotime('last day of this month 23:59:59', strtotime(date("Y", time()) . "-" . $i . "-" . 1));
				}
				break;
		}
		return $periods_end_dates;
	}

	public static function period($period)
	{
		switch ($period) {
			case 'day':
				return 60 * 60 * 24;
				break;
			case 'week':
				return 60 * 60 * 24 * 7;
				break;
		}
	}

	public static function week()
	{
		return 60 * 60 * 24;
	}

	/**
	 * get_future_timestamps_of_period
	 *
	 * @param string $period period (day, week, month, year)
	 * @param int $interval_date_from start date of time interval
	 * @param int $interval_date_to end date of time interval
	 * @param int $base_date base date for creating loops
	 * @return array operations[]
	 */
	public static function get_future_timestamps_of_period($period, $interval_date_from = null, $interval_date_to = null, $base_date = null)
	{
		$periods_end_dates = [];
		switch ($period) {
			case 'day': // дані з періодом 1 день ( за тиждень )
				$base_day = !empty($base_date) ? date('l', $base_date) : Settings::get('finance day');

				$date_from = $interval_date_from;
				$date_to = $interval_date_to;

				$date_from = !empty($date_from) ? $date_from : strtotime('next ' . $base_day, time());
				$date_to = !empty($date_to) ? $date_to : strtotime('next ' . $base_day, time() + self::period('week'));
				$periods_end_dates[] = $date_from;

				for ($date = $date_from; $date < $date_to; $date += self::period('day')) {
					$periods_end_dates[] = $date;
				}
				break;
			case "week":// дані з періодом 1 тиждень ( за місяць )
				$base_day = !empty($base_date) ? date('l', $base_date) : Settings::get('finance_day');

				$interval_date_from = !empty($interval_date_from) ? self::get_midnight($interval_date_from) : strtotime('next ' . $base_day, time());
				$interval_date_to = !empty($interval_date_to) ? self::get_midnight($interval_date_to) : strtotime('next month', time());

				$date_from = strtotime('next ' . $base_day, $interval_date_from);
				$date_to = $interval_date_to;

				$periods_end_dates[] = $date_from;
				$date = $date_from;

				while ($date <= $interval_date_to) {
					$date = strtotime("+7 days", $date);
					$periods_end_dates[] = $date;
				}

				if (empty($date_to)) {
					$periods_end_dates[] = strtotime('first day of next month 00:00:00', time());
				}

				break;
			case "month":// дані з періодом 1 місяць ( за рік )
				$base_date = !empty($base_date) ? $base_date : strtotime('first day of this month 00:00:00', time());
				$base_day = !empty($base_date) ? date('d', $base_date) : 1;

				$interval_date_from = !empty($interval_date_from) ? self::get_midnight($interval_date_from) : strtotime('first day of this month 00:00:00', time());
				$interval_date_to = !empty($interval_date_to) ? self::get_midnight($interval_date_to) : strtotime('last day of this month 00:00:00', time());

				if (strtotime($base_day . date('.m.Y')) < ($interval_date_from - 1)) {
					$date_from = $base_date + 60 * 60 * 24 * cal_days_in_month(CAL_GREGORIAN, 2, date('Y', $base_date));
				} else {
					$date_from = $base_date;
				}
				$date_to = $interval_date_to;

				$periods_end_dates[] = $date_from;
				$date = $date_from;

				do {
					$month_days_amount = cal_days_in_month(CAL_GREGORIAN, date('n', $date), date('Y', $date));
					$prev_date = $date;
					$date = strtotime("+" . $month_days_amount . " days", $date);
					if (date('d', $date) !== $base_day) {
						$month_days_amount = cal_days_in_month(CAL_GREGORIAN, date('n', $date - 60 * 60 * 24 * 5), date('Y', $date - 60 * 60 * 24 * 5));
						$date = ((int)$base_day > (int)$month_days_amount) ? strtotime('last day of next month', $prev_date) : strtotime($base_day . date('.m.Y', $date));
					}
					$periods_end_dates[] = $date;
				} while ($date <= $interval_date_to);

				if (empty($date_to)) {
					$periods_end_dates[] = strtotime('first day of next month 00:00:00', time());
				}
				break;
			case "year":
				$base_date = !empty($base_date) ? $base_date : strtotime('first day of this year 00:00:00', time());
				$base_day_month = !empty($base_date) ? date('d.m', $base_date) : '1.01';

				$interval_date_from = !empty($interval_date_from) ? self::get_midnight($interval_date_from) : strtotime('first day of this year 00:00:00', time());
				$interval_date_to = !empty($interval_date_to) ? self::get_midnight($interval_date_to) : strtotime('last day of this year 00:00:00', time());

				if (strtotime($base_day_month . date('.Y')) < ($interval_date_from - 1)) {
					$date_from = $base_date + 60 * 60 * 24 * (337 + cal_days_in_month(CAL_GREGORIAN, 2, date('Y')));
				} else {
					$date_from = $base_date;
				}

				if (date('d.m', $date_from) !== $base_day_month) {
					$date_from = strtotime('last day of previous month', $date_from);
				}

				$date_to = $interval_date_to;

				$periods_end_dates[] = $date_from;
				$date = $date_from;
				do {
					$date = strtotime(date('d.m.', $base_date) . ((int)date('Y', $date) + 1));

					if (date('d.m', $date) !== $base_day_month) {
						$date = strtotime('last day of previous month', $date);
					}
					$periods_end_dates[] = $date;
				} while ($date <= $date_to);

				break;
		}

		foreach ($periods_end_dates as $key => $date) {
			if ($date > $interval_date_to) {
				unset($periods_end_dates[$key]);
			}
		}
		return array_unique($periods_end_dates);
	}

	public static function get_same_day_next_month()
	{
		return strtotime('next month 00:00:00', time());
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

	public static function get_first_and_last_days_of_month($month_year)
	{
		$date = new DateTime('1.' . $month_year);
		$date->modify('first day of this month');
		$date_arr = (array)$date;
		$first_day = $date_arr['date'];

		$date->modify('last day of this month');
		$date_arr = (array)$date;
		$last_day = $date_arr['date'];
		return [
			'first_day' => $first_day,
			'first_day_timestamp' => strtotime($first_day),
			'last_day' => $last_day,
			'last_day_timestamp' => strtotime($last_day)
		];
	}

	public static function get_midnight($timestamp)
	{
		return strtotime(date('d.m.Y', $timestamp) . ' 00:00:00');
	}
}
