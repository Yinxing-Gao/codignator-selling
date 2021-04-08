<?php

namespace App\Models;

use Config;

class Modules
{
	public static function get_modules($params = [], $join = [])
	{
		$query_params = self::select_query(array_merge(['fin_users', 'fin_banks'], $join));
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function search($query, $type = null)
	{
		return self::get_modules(['where' => [
			'name LIKE "%' . trim($query) . '%"'
		]]);
	}

	public static function select_query($join_keys = [])
	{
		return [
			'table' => ['fm' => 'fin_modules'],
			'columns' => [
				'id', 'name', 'link'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
			],
			'limit' => null,
			'offset' => null
		];
	}
}
