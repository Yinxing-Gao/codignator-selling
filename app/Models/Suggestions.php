<?php


namespace App\Models;

use Config;

class Suggestions
{
	public static function get_suggestions($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(array_merge(['fin_users'], $join), array_merge(['fin_suggestion_votes'], $has_many));
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_suggestion($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(array_merge(['fin_users'], $join), array_merge(['fin_suggestion_votes'], $has_many));
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public static function add($atts)
	{
		$session = Config\Services::session();
		$user_id = $session->get('user_id');
		$account_id = $session->get('account_id');

		if (!empty($atts['title'])) {
			$params = [
				'title' => $atts['title'],
				'message' => !empty($atts['message']) ? $atts['message'] : $atts['title'],
				'author_id' => !empty($atts['author_id']) ? $atts['author_id'] : $user_id,
				'votes' => !empty($atts['votes']) ? $atts['votes'] : 0,
				'type' => !empty($atts['type']) ? $atts['type'] : 'error',
				'is_public' => !empty($atts['is_public']) ? $atts['is_public'] : '1',
				'date' => !empty($atts['date']) ? $atts['date'] : time(),
				'status' => !empty($atts['status']) ? $atts['status'] : 'open',
				'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : $account_id,
			];
			return DBHelp::insert('fin_suggestions', $params);
		}
		return ['status' => 'error'];
	}

	public static function update($suggestion_id, $params)
	{
		return DBHelp::update('fin_suggestions', $suggestion_id, $params);
	}

	public static function delete($suggestion_id)
	{
		DBHelp::delete('fin_suggestions', $suggestion_id);
		DBHelp::delete_where('fin_suggestion_votes', ['suggestion_id = ' . $suggestion_id]);
		return ['status' => 'ok'];
	}

	public static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['fs' => 'fin_suggestions'],
			'columns' => [
				'id',
				'title',
				'message',
				'screenshot',
				'date',
				'author_id',
				'type',
				'status',
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

		if (in_array('fin_users', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fu' => 'fin_users'],
				'main_table_key' => 'author_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'author_name',
					'surname' => 'author_surname',
				]
			];
		}

		if (in_array('fin_suggestion_votes', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fgv' => 'fin_suggestion_votes'],
				'new_column' => 'votes',
				'main_table_key' => 'id',
				'other_table_key' => 'suggestion_id',
				'columns' => ['user_id'],
				'columns_with_alias' => [
				]
			];
		}
		return $params;
	}
}
