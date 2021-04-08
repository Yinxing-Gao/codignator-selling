<?php


namespace App\Models;

use Config;

class Price
{
	public static function get_prices($department_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fp.`id`, `product_id`,fsn.name, `price_type`, fp.`price`, fp.`currency`, fpp.`department_id`, fpp.storage_name_id as name_id
								FROM `fin_prices` fp
								LEFT JOIN fin_products fpp ON fpp.id = fp.product_id
								LEFT JOIN fin_storage_names fsn ON fsn.id = fpp.storage_name_id
								WHERE fpp.department_id = ' . $department_id);
		return $query->getResultArray();
	}

	public static function get_prices_array($department_id)
	{
		$prices = self::get_prices($department_id);
		$result_array = [];
		if (!empty($prices)) {
			foreach ($prices as $price) {
				$result_array[$price['product_id']][$price['price_type']]['amount'] = $price['price'];
				$result_array[$price['product_id']][$price['price_type']]['currency'] = $price['currency'];
			}
		}
		return $result_array;
	}

	public static function get_price($product_id, $price_type)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fp.`id`, `product_id`,fsn.name, `price_type`, fp.`price`, fp.`currency`, fpp.`department_id`
								FROM `fin_prices` fp
								LEFT JOIN fin_products fpp ON fpp.id = fp.product_id
								LEFT JOIN fin_storage_names fsn ON fsn.id = fpp.storage_name_id
								WHERE product_id = ' . $product_id . ' AND price_type = "' . $price_type . '"');
		return $query->getFirstRow();
	}

	public static function get_price_types($department_id = null)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT  `price_type`
 									FROM `fin_prices` fp
 									LEFT JOIN fin_products fpp ON fpp.id = fp.product_id
 									WHERE fpp.department_id = ' . $department_id .
			' GROUP BY price_type');
		$price_types = $query->getResultArray();
		$result = [];
		if (!empty($price_types)) {
			foreach ($price_types as $price_type) {
				$result[] = $price_type['price_type'];
			}
		}
		return $result;
	}

	public
	static function change_price($product_id, $price_type, $amount, $currency)
	{

		$price = self::get_price($product_id, $price_type);
		$db = Config\Database::connect();
		if (!empty($price)) {
			$query = $db->query('UPDATE `fin_prices` SET `price`= "' . $amount . '", `currency`= "' .$currency .'" WHERE id = ' . $price->id);
		} else {
			$query = $db->query('INSERT INTO `fin_prices`(`product_id`, `price_type`, `price`, `currency`)
 							VALUES (' . $product_id . ', "' . $price_type . '", "' . $amount . '", "' . $currency . '")');
		}
		$query->getResult();

		return ['status' => 'ok'];
	}

//	//temp
//	public static function update_price()
//	{
//		$products = Production::get_production_products(1);
//		$db = Config\Database::connect();
//		if (!empty($products)) {
//			foreach ($products as $product) {
//				$atts = [];
//				$atts['name'] = $product['name'];
//				$atts['unit_id'] = 1;
//				$id = Storage::add_item($atts, 6)['id'];
//				self::change_price($product['id'], 'retail', $product['price'], $product['currency']);
//				$query = $db->query('UPDATE `fin_products` SET `storage_name_id`= ' . $id . ' WHERE id = ' . $product['id']);
//				$query->getResult();
//
//			}
//		}
//	}

	public static function update_price()
	{
		$names = Storage::get_storage_names(2);
		if (!empty($names)) {
			foreach ($names as $name) {
				Products::add($name['id'], 9);
			}
		}
	}

}
