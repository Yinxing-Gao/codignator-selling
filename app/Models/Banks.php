<?php


namespace App\Models;

use Config;

class Banks
{
	public static function get_banks($params = [], $join = [])
	{
		$query_params = self::select_query( $join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function select_query($join_keys = [])
	{
		return [
			'table' => ['fb' => 'fin_banks'],
			'columns' => [
				'id', 'name'
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
