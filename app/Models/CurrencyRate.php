<?php


namespace App\Models;


class CurrencyRate
{

	public static function get_exchange_rates_all()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$currency_rate = [];
		foreach (json_decode($output) as $currency){
			$currency_rate[$currency->ccy]['buy'] = $currency->buy;
			$currency_rate[$currency->ccy]['sale'] = $currency->sale;
		}
		return $currency_rate;
	}

	public static function get_exchange_rates($cur = 'USD') //RUR, EUR, USD, BTC
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		$cur_arr = json_decode($output);

		Dev::var_dump($cur_arr);
		switch ($cur) {
			case 'RUR':
				return ['sale' => $cur_arr[2]->sale, 'buy' => $cur_arr[2]->buy];
				break;
			case 'EUR':
				return ['sale' => $cur_arr[1]->sale, 'buy' => $cur_arr[1]->buy];
				break;
			case 'USD':
				return ['sale' => $cur_arr[0]->sale, 'buy' => $cur_arr[0]->buy];
				break;
			case 'UAH':
				return ['sale' => 1, 'buy' => 1];
				break;
			case 'BTC':
				return ['sale' => $cur_arr[3]->sale, 'buy' => $cur_arr[3]->buy];
				break;
			default:
				return ['sale' => $cur_arr[0]->sale, 'buy' => $cur_arr[0]->buy];
				break;
		}
	}

	public static function get_currencies()
	{
		return ['UAH', 'USD', 'EUR', 'RUR'];
	}

	public static function get_currencies_names()
	{
		return ['UAH' => 'грн', 'USD'=>'дол', 'EUR'=>'євро', 'RUR' =>'рубл'];
	}
}
