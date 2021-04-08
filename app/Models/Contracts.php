<?php


namespace App\Models;

use Config;

class Contracts
{
	public static function get_contracts($params = [], $join = [])
	{
		$query_params = self::select_query(array_merge([], $join));

		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}


	public static function search($query)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM fin_contracts fc WHERE number LIKE "%' . trim($query) . '%"');
		return $query->getResultArray();
	}

	public static function add_update($atts)
	{
		if (!empty($atts['number'])) {
			$params = [
				'number' => !empty($atts['number']) ? $atts['number'] : '',
				'amount' => !empty($atts['amount']) ? $atts['amount'] : 0,
				'currency' => !empty($atts['currency']) ? $atts['currency'] : 'UAH',
				'date' => !empty($atts['date']) ? $atts['date'] : time(),
				'end_date' => !empty($atts['end_date']) ? $atts['end_date'] : 0,
				'start_date' => !empty($atts['start_date']) ? $atts['start_date'] : 0,
				'contractor_id' => !empty($atts['contractor_id']) ? $atts['contractor_id'] : 0,
				'products_id' => !empty($atts['products_id']) ? $atts['products_id'] : 0,
				'contract_type' => !empty($atts['contract_type']) ? $atts['contract_type'] : 0,
				'type_id' => !empty($atts['type_id']) ? $atts['type_id'] : 0,
				'comment' => !empty($atts['comment']) ? $atts['comment'] : '',
				'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : Account::get_current_account_id(),
			];
			if (!empty($atts['id'])) {
				return DBHelp::update('fin_contracts', $atts['id'], $params);
			} else {
				return DBHelp::insert('fin_contracts', $params);
			}
		}
	}

	public static function delete($contract_id)
	{
		DBHelp::delete('fin_contracts', $contract_id);
		return ['status' => 'ok'];
	}

	public
	static function select_query($join_keys = [])
	{
		$account_id = Account::get_current_account_id();
		$params = [
			'table' => ['fc' => 'fin_contracts'],
			'columns' => [
				'id',
				'amount',
				'currency',
				'date',
				'start_date',
				'end_date',
				'number',
				'contractor_id',
				'products_id',
				'type_id',
				'contract_type',
				'account_id',
				'comment'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
				'fc.account_id = ' . $account_id
			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_contractors', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fco' => 'fin_contractors'],
				'other_table_key' => 'fc.contractor_id',
				'columns' => [],
				'columns_with_alias' => [
					'id' => 'contractor_id',
					'name' => 'contractor_name'
				]
			];
		}

		return $params;
	}
}
