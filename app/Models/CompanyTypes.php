<?php


namespace App\Models;

use App\Controllers\Contractor;
use Config;

class CompanyTypes
{
	public static function get_types($params = [], $join = [])
	{
		$query_params = self::select_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function select_query($join_keys = [])
	{
		return [
			'table' => ['fct' => 'fin_company_types'],
			'columns' => [
				'id',
				'name'
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
