<?php


namespace App\Models;

use Config;


class Instruction
{
	public static function get_instruction($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(array_merge([], $join), array_merge([], $has_many));

		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public static function add($atts)
	{
		$params = [
			'text' => !empty($atts['text']) ? $atts['text'] : '',
			'position_id' => !empty($atts['position_id']) ? $atts['position_id'] : 0,
			'type' => !empty($atts['type']) ? $atts['type'] : '',
		];
		return DBHelp::insert('fin_instruction', $params);
	}

	public static function update($position_id, $params)
	{
		return DBHelp::update('fin_instruction', $position_id, $params);
	}

	public static function delete($position_id)
	{
		DBHelp::delete('fin_instruction', $position_id);
		return ['status' => 'ok'];
	}

	public
	static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['fp' => 'fin_instruction'],
			'columns' => [
				'id', 'text', 'position_id', 'type'
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
		return $params;
	}
}

