<?php


namespace App\Models;

use Config;

class Questionnaire
{
	public static function get_tasks($params = [], $join = [])
	{
		$query_params = self::select_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_task($params = [], $join = [])
	{
		$query_params = self::select_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params),'single')['result'];
	}

	public static function add($atts)
	{
		if (!empty($atts['name'])) {
			$params = [
				'name' => $atts['name'],
				'user_id' => $atts['user_id'],
				'after_id' => !empty($atts['after_id']) ? $atts['after_id'] : 0,
			];
			return DBHelp::insert('fin_questionnaire', $params);
		}
	}

	public static function select_query($join_keys = [])
	{
		return [
			'table' => ['fq' => 'fin_questionnaire'],
			'columns' => [
				'id',
				'user_id',
				'name',
				'after_id',
				'priority'
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
