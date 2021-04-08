<?php


namespace App\Models;

use Config;

class Contractors
{
	public static function get_contractors($params = [], $join = [], $all_accounts = false)
	{
		$query_params = self::select_query($join, $all_accounts);

		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_contractor($params = [], $join = [], $all_accounts = false)
	{
		$query_params = self::select_query($join, $all_accounts);

		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}


	public static function get_contractors_array()
	{
		$contractors = self::get_contractors(['where' => ['fc.account_id = ' . Account::get_current_account_id()]]);
		$contractors_arr = [];
		if (!empty($contractors)) {
			$contractors_arr = [];
			foreach ($contractors as $contractor) {
				$contractors_arr[$contractor['id']] = $contractor['name'];
			}
		}
		return $contractors_arr;
	}

	public static function get_user_contractor($user_id)
	{
		return self::get_contractor(['where' => [
			'user_id = ' . $user_id
		]], [], true);
	}

	public static function search($query, $type = null)
	{
		return self::get_contractors(['where' => [
			'options LIKE "%' . trim($query) . '%"',
			'account_id = ' . Account::get_current_account_id(),
			!empty($type) ? 'contractor_type = "' . $type . '"' : '1'
		]]);
	}

	public static function get_types()
	{
		return [
			'employee',// - співробітник
			'provider', // - поставщик,
			'client', // - клиент,
			'contractor' // - підрядник
		];
	}

	public static function search_one($query)
	{
		return self::get_contractor([
			'where' => [
				'options LIKE "%' . htmlspecialchars(trim($query)) . '%"'
			]
		]);
	}

	public static function search_template($contractor_id)
	{
		$result = DBHelp::select([
			'table' => ['fct' => 'fin_contractor_templates'],
			'columns' => [
				'id', 'contractor_id', 'project_id', 'article_id', 'department_id'
			],
			'where' => [
				'fct.contractor_id = ' . $contractor_id
			],
		]);
		if ($result['status'] == 'ok') {
			return $result['result'];
		}
		return [];
	}

	public static function add($contractor)
	{
		if (!empty($contractor['name'])) {
			$contractor['account_id'] = !empty($contractor['account_id']) ? $contractor['account_id'] : Account::get_current_account_id();
			$contractor['options'] = !empty($contractor['options']) ? ($contractor['options'] . ' ' . $contractor['name']) : $contractor['name'];
			$contractor['lead_id'] = !empty($contractor['lead_id']) ? $contractor['lead_id'] : 0;
			return DBHelp::insert('fin_contractors', $contractor);
		}
		return ['status' => 'error'];
	}

	public static function update($id, $atts)
	{
		return DBHelp::update('fin_contractors', $id, $atts);
	}

	public static function unite($united_contractor, $ids)
	{
		if (!empty($ids)) {
			$final_id = $ids[0];
			unset($ids[0]);
			DBHelp::update('fin_contractors', $final_id, $united_contractor);

			foreach ($ids as $id) {
				DBHelp::update_where('fin_accruals', ['contractor_id = ' . $id], ['contractor_id' => $final_id]);
				DBHelp::update_where('fin_contracts', ['contractor_id = ' . $id], ['contractor_id' => $final_id]);
				DBHelp::update_where('fin_credits', ['contractor_id = ' . $id], ['contractor_id' => $final_id]);
				DBHelp::update_where('fin_operations', ['contractor1_id = ' . $id], ['contractor1_id' => $final_id]);
				DBHelp::update_where('fin_operations', ['contractor2_id = ' . $id], ['contractor2_id' => $final_id]);
				DBHelp::update_where('fin_storage_names', ['supplier_id = ' . $id], ['supplier_id' => $final_id]);

				DBHelp::delete('fin_contractors', $id);
			}

			return ['status' => 'ok', 'id' => $final_id];
		}
		return ['status' => 'error', 'message' => 'empty ids'];
	}

	public static function delete($id)
	{
		return DBHelp::delete('fin_contractors', $id);
	}

	public
	static function select_query($join_keys = [], $all_accounts = false)
	{

		$params = [
			'table' => ['fc' => 'fin_contractors'],
			'columns' => [
				'id',
				'name',
				'contractor_type',
				'address',
				'phone',
				'user_id',
				'options',
				'account_id',
				'telegram_code',
				'telegram_chat_id',
				'comment',
				'is_default'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
//				'fc.account_id = ' . Account::get_current_account_id(),
			],
			'limit' => null,
			'offset' => null
		];

		if ($all_accounts) {
			$params['where'] = [];
		}


		return $params;
	}
}
