<?php

namespace App\Models;

use Config;

class Settings
{
	public static function get_settings($params = [])
	{
		$query_params = self::select_query( );
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get($name, $account_id = null)
	{
		$result = DBHelp::select(DBHelp::params_merge(self::select_query(), ['where' => [
			'account_id = ' . (!empty($account_id) ? $account_id : Account::get_current_account_id()),
			'name = "' . $name . '"'
		]]), 'single')['result'];
		return !empty($result) ? $result->value : null;
	}

	public static function add($atts)
	{
		if (!empty($atts['name']) && !empty($atts['value']))
			$params = [
				'name' => $atts['name'],
				'value' => $atts['value'],
				'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : Account::get_current_account_id(),
				'business_unit_id' => !empty($atts['business_unit_id']) ? $atts['business_unit_id'] : 0,

			];
		return DBHelp::insert('fin_settings', $params);
	}

	public
	static function select_query()
	{
		return [
			'table' => ['fw' => 'fin_settings'],
			'columns' => [
				'id',
				'name',
				'value',
				'type',
				'field_type',
				'comment'
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
