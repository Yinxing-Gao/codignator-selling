<?php


namespace App\Models;

use Config;

class Products
{
	public static function get_products($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(array_merge(['fin_storage_names'], $join), $has_many);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_product($params = [], $join = [])
	{
		$query_params = self::select_query(array_merge(['fin_storage_names'], $join));
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public static function get_questions($product_id)
	{
//		$product = Products::get_product(['where' => [
//			'fpr.id = ' . $product_id
//		]]);
//
//		if (!empty($product)) {
//			if (!empty($product->question_group_ids)) {
//				return Marketing::get_question_groups(['where' => ['fqg.id IN (' . $product->question_group_ids . ')']]);
		return Marketing::get_question_groups(['where' => [
			'account_id = ' . Account::get_current_account_id()
		]]);
//			} else {
//				return [];
//			}
//		} else {
//			return ['status' => 'error'];
//		}
	}

//	public static function get_products($department_id)
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT fp.`id`, fsn.name, fp.`type_id`, `has_parts`, fp.`department_id`, `storage_name_id` as name_id
//									FROM `fin_products` fp
//									LEFT JOIN fin_storage_names fsn ON fsn.id = fp.storage_name_id
//									WHERE fp.department_id = ' . $department_id);
//		return $query->getResultArray();
//	}

//	public static function get_product($product_id)
//	{
//		$db = Config\Database::connect();
////		echo 'SELECT fp.`id`, fsn.name, fp.`type_id`, `has_parts`, fp.`department_id`, `storage_name_id` as name_id, fsn.buy_price, fsn.currency
////									FROM `fin_products` fp
////									LEFT JOIN fin_storage_names fsn ON fsn.id = fp.storage_name_id
////									WHERE fp.id = ' . $product_id;
//		$query = $db->query('SELECT fp.`id`, fsn.name, fp.`type_id`, `has_parts`, fp.`department_id`, `storage_name_id` as name_id, fsn.buy_price, fsn.currency
//									FROM `fin_products` fp
//									LEFT JOIN fin_storage_names fsn ON fsn.id = fp.storage_name_id
//									WHERE fp.id = ' . $product_id);
//		return $query->getFirstRow();
//	}

	public static function get_storage_products($storage_id, $price_type, $currency = null)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT fp.`id`, fsn.name, fp.`type_id`, `has_parts`, fp.`department_id`, `storage_name_id` as name_id, fps.price, fps.currency, fu.name as unit
									FROM `fin_products` fp
									LEFT JOIN fin_storage_names fsn ON fsn.id = fp.storage_name_id
                                    LEFT JOIN fin_prices fps ON fps.product_id = fp.id
                                    LEFT JOIN fin_units fu ON fsn.unit_id = fu.id
									WHERE fsn.storage_id = ' . $storage_id . ' AND fps.price_type = "' . $price_type . '"');
		$products = $query->getResultArray();
		if (!empty($currency) && !empty($products)) {
			foreach ($products as &$product) {
				if ($product['currency'] != $currency) {
					$currency_rate = CurrencyRate::get_exchange_rates($product['currency']);
					$product['price_'] = $product['price'] * $currency_rate['buy'];
				} else {
					$product['price_'] = $product['price'];
				}
			}
		}
		return $products;
	}

	public static function get_projects_products($products_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM fin_products fpp WHERE id IN (' . htmlspecialchars($products_id) . ')');
		return $query->getResultArray();
	}

	public static function get_product_name($product_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM fin_products fpp WHERE id=' . $product_id);
		return $query->getFirstRow();
	}

	public
	static function add($atts)
	{
        $params = [
            'name' => $atts['name'],
            'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
            'article_id' => !empty($atts['article_id']) ? $atts['article_id'] : 0,
            'type' => $atts['type'],
            'payment_type' => $atts['payment_type'],
            'first_payment' => !empty($atts['first_payment']) ? $atts['first_payment'] : 0,
            'average_sale_time' => !empty($atts['average_sale_time']) ? $atts['average_sale_time'] : 0,
            'average_project_time' => !empty($atts['average_project_time']) ? $atts['average_project_time'] : 0,
            'price' => !empty($atts['price']) ? $atts['price'] : 0,
            'currency' => $atts['currency'],
            'comment' => !empty($atts['comment']) ? $atts['comment'] : '',
            'has_parts' => $atts['has_parts'],
            'question_group_ids' => !empty($atts['question_group_ids']) ? $atts['question_group_ids'] : '',
            'storage_name_id' => !empty($atts['storage_name_id']) ? $atts['storage_name_id'] : 0,
            'account_id' => Account::get_current_account_id()
        ];
        return DBHelp::insert('fin_products', $params);
	}

	public static function search($query)
	{
		return self::get_products($params = ['where' => [
			'fpr.name LIKE "%' . trim($query) . '%"'
		]]);
	}

	public static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['fpr' => 'fin_products'],
			'columns' => [
				'id',
				'type_id',
				'type',
				'payment_type',
				'first_payment',
				'average_sale_time',
				'average_project_time',
				'group_id',
				'name',
				'question_group_ids',
				'price',
				'currency',
				'has_parts',
				'comment',
				'department_id'
			],
			'columns_with_alias' => [
				'storage_name_id' => 'name_id'
			],
			'join' => [],
			'where' => [
				'fpr.account_id = ' . Account::get_current_account_id()
			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_storage_names', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fsn' => 'fin_storage_names'],
				'main_table_key' => 'storage_name_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'storage_name'
				]
			];
		}

		if (in_array('fin_articles', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fa' => 'fin_articles'],
				'main_table_key' => 'article_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'article_name'
				]
			];
		}

		if (in_array('fin_departments', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fd' => 'fin_departments'],
				'main_table_key' => 'department_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'department_name'
				]
			];
		}

		if (in_array('fin_question_groups', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fqg' => 'fin_question_groups'],
				'type' => 'in',
				'new_column' => 'question_groups',
				'main_table_key' => 'question_group_ids',
				'other_table_key' => 'id',
				'columns' => ['name'],
				'columns_with_alias' => [
				]
			];
		}

		return $params;
	}
}
