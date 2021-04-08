<?php


namespace App\Models;

use Config;

class Budgets
{
	public static function get_budgets($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query($join, $has_many);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function add($atts)
	{
		$params = [
			'name' => !empty($_POST['name']) ? $_POST['name'] : '',
			'article_id' => !empty($_POST['article_id']) ? $_POST['article_id'] : 0,
			'amount' => !empty($_POST['amount']) ? $_POST['amount'] : 0,
			'comment' => !empty($_POST['comment']) ? $_POST['comment'] : [],
			'time_type' => !empty($_POST['time_type']) ? $_POST['time_type'] : 'plan',
			'date' => !empty($_POST['date']) ? $_POST['date'] : time(),
			'account_id' => !empty($_POST['account_id']) ? $_POST['account_id'] : Account::get_current_account_id(),
		];
		return DBHelp::insert('fin_budgets', $params);
	}

	public
	static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['fb' => 'fin_budgets'],
			'columns' => [
				'id',
				'amount',
				'name',
				'article_id',
				'comment',
				'time_type',
				'date',
				'account_id'
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

		if (in_array('fin_articles', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['far' => 'fin_articles'],
				'main_table_key' => 'article_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'article_name'
				]
			];
		}

		if (in_array('fin_operations', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['flc' => 'fin_operations'],
				'new_column' => 'operations',
				'main_table_key' => 'id',
				'other_table_key' => 'budget_id',
				'columns' => ['amount1', 'amount2', 'currency1', 'currency2', 'comment', 'operation_type_id'],
				'columns_with_alias' => [
				],
				'join' => [[
					'type' => 'LEFT JOIN',
					'table' => ['far' => 'fin_articles'],
					'main_table_key' => 'article_id',
					'columns' => [],
					'columns_with_alias' => [
						'name' => 'article_name'
					]]
				]
			];
		}
		return $params;
	}
}

