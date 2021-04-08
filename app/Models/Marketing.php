<?php


namespace App\Models;

use Config;

class Marketing
{
	public static function get_leads($params = [], $join = [], $has_many = ['fin_lead_comments', 'fin_lead_contacts', 'fin_tasks'])
	{
		$query_params = self::select_query(array_merge([], $join), array_merge([], $has_many));
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_lead($params = [], $join = [], $has_many = ['fin_lead_comments', 'fin_lead_contacts', 'fin_tasks'])
	{
		$query_params = self::select_query(array_merge([], $join), array_merge([], $has_many));
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public static function add($atts)
	{
		$params = [
			'name' => !empty($atts['name']) ? $atts['name'] : '',
			'date' => time(),
			'product_id' => !empty($atts['product_id']) ? $atts['product_id'] : 0,
			'amount' => !empty($atts['amount']) ? $atts['amount'] : 0,
			'currency' => !empty($atts['currency']) ? $atts['currency'] : 'UAH',
			'source_id' => !empty($atts['source_id']) ? $atts['source_id'] : 0,
			'status' => !empty($atts['status']) ? $atts['status'] : 'new',
			'qualification' => !empty($atts['qualification']) ? $atts['qualification'] : 'B',
			'docs' => !empty($atts['docs']) ? $atts['docs'] : '',
			'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
			'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : Account::get_current_account_id(),
		];
		$result = DBHelp::insert('fin_leads', $params);

		if (!empty($atts['contacts'])) {
			foreach ($atts['contacts'] as &$contact) {
				$contact['lead_id'] = $result['id'];
				self::add_contact($contact);
			}
		}

		self::create_lead_defaults($result['id'], $params);

		return $result;
	}

	public static function update($lead_id, $atts)
	{
		$params = [
			'name' => !empty($atts['name']) ? $atts['name'] : '',
			'date' => time(),
			'product_id' => !empty($atts['product_id']) ? $atts['product_id'] : 0,
			'amount' => !empty($atts['amount']) ? $atts['amount'] : 0,
			'currency' => !empty($atts['currency']) ? $atts['currency'] : 'UAH',
			'source_id' => !empty($atts['source_id']) ? $atts['source_id'] : 0,
			'status' => !empty($atts['status']) ? $atts['status'] : 'new',
			'qualification' => !empty($atts['qualification']) ? $atts['qualification'] : 'B',
			'docs' => !empty($atts['docs']) ? $atts['docs'] : '',
			'question_groups_ids' => !empty($atts['question_groups_ids']) ? $atts['question_groups_ids'] : '',
			'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
			'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : Account::get_current_account_id(),
		];
		$result = DBHelp::update('fin_leads', $lead_id, $params);

		DBHelp::delete_where('fin_lead_contacts', ['lead_id = ' . $lead_id]);
		if (!empty($atts['contacts'])) {
			foreach ($atts['contacts'] as &$contact) {
				$contact['lead_id'] = $lead_id;
				self::add_contact($contact);
			}
		}

		DBHelp::delete_where('fin_lead_answers', ['lead_id = ' . $lead_id]);
		if (!empty($atts['answers'])) {
			foreach ($atts['answers'] as &$answer) {
				$answer['lead_id'] = $lead_id;
				self::add_answer($answer);
			}
		}

		return $result;
	}

	public static function add_contact($atts)
	{
		if (!empty($atts['value']) && !empty($atts['lead_id'])) {
			$params = [
				'name' => !empty($atts['name']) ? $atts['name'] : '',
				'type' => !empty($atts['type']) ? $atts['type'] : 'other',
				'value' => !empty($atts['value']) ? $atts['value'] : 0,
				'lead_id' => $atts['lead_id']
			];
			return DBHelp::insert('fin_lead_contacts', $params);
		}
	}

	public static function add_answer($atts)
	{
		if (!empty($atts['question_id']) && !empty($atts['lead_id']) && !empty($atts['answer'])) {
			$params = [
				'question_id' => $atts['question_id'],
				'answer' => $atts['answer'],
				'lead_id' => $atts['lead_id']
			];
			return DBHelp::insert('fin_lead_answers', $params);
		}
	}

	public static function add_comment($atts)
	{
		if (!empty($atts['comment']) && !empty($atts['lead_id'])) {
			$params = [
				'comment' => $atts['comment'],
				'lead_id' => $atts['lead_id'],
				'date' => time()
			];
			return DBHelp::insert('fin_lead_comments', $params);
		}
	}

	public static function create_lead_defaults($lead_id, $lead)
	{
		if (!empty($lead_id)) {
			Tasks::add([
				'task' => 'Продзвонити нового ліда',
				'comment' => 'a href="' . base_url('marketing/leads') . '" >Таблиця лідів</a>',
				'notify' => 1,
				'lead_id' => $lead_id,
				'date_to' => time() + 60 * 60,
				'user_id' => Settings::get('lead_responsible', $lead['account_id']),
				'account_id' => $lead['account_id']
			]);

			Contractors::add([
				'name' => $lead['name'],
				'contractor_type' => 'client',
				'lead_id' => $lead_id,
				'account_id' => $lead['account_id']
			]);
		}
	}

	public static function get_question_groups($params_ = [])
	{
		$params = [
			'table' => ['fqg' => 'fin_question_groups'],
			'columns' => [
				'id',
				'name'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
				'fqg.account_id = ' . Account::get_current_account_id(),
			],
			'limit' => null,
			'offset' => null,
			'has_many' => [[
				'table' => ['fq' => 'fin_questions'],
				'new_column' => 'questions',
				'main_table_key' => 'id',
				'other_table_key' => 'group_id',
				'columns' => ['id', 'question', 'group_id'],
				'columns_with_alias' => [
				]],
			]];
		return DBHelp::select(DBHelp::params_merge($params, $params_))['result'];
	}

	public static function get_lead_question_groups($params_ = [])
	{

	}


	public static function get_sources($params = [], $join = [], $has_many = [])
	{
		$params2 = [
			'table' => ['fs' => 'fin_sources'],
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
		return DBHelp::select(DBHelp::params_merge($params2, $params))['result'];
//
//		return DBHelp::select($params)['result'];
	}

	public static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['fl' => 'fin_leads'],
			'columns' => [
				'id',
				'date',
				'name',
				'product_id',
				'amount',
				'currency',
				'source_id',
				'wallet_id',
				'status',
				'qualification',
				'docs',
				'question_groups_ids',
				'department_id',
				'account_id'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
				'fl.account_id = ' . Account::get_current_account_id(),
			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_contractors', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fc' => 'fin_contractors'],
				'main_table_key' => 'id',
				'joined_table_key' => 'lead_id',
				'columns' => [],
				'columns_with_alias' => [
					'id' => 'contractor_id',
				]
			];
		}

		if (in_array('fin_sources', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fs' => 'fin_sources'],
				'main_table_key' => 'source_id',
				'columns' => [],
				'columns_with_alias' => [
					'name' => 'source_name',
				]
			];
		}

		if (in_array('fin_lead_comments', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['flc' => 'fin_lead_comments'],
				'new_column' => 'comments',
				'main_table_key' => 'id',
				'other_table_key' => 'lead_id',
				'columns' => ['comment', 'date'],
				'columns_with_alias' => [
				],
			];
		}

		if (in_array('fin_lead_contacts', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['flc2' => 'fin_lead_contacts'],
				'new_column' => 'contacts',
				'main_table_key' => 'id',
				'other_table_key' => 'lead_id',
				'columns' => ['name', 'type', 'value'],
				'columns_with_alias' => [
				],
			];
		}

		if (in_array('fin_tasks', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['ft' => 'fin_tasks'],
				'new_column' => 'tasks',
				'main_table_key' => 'id',
				'other_table_key' => 'lead_id',
				'columns' => [
					'id',
					'date',
					'task',
					'statistics',
					'comment',
					'date_to',
					'notify',
					'lead_id',
					'user_id',
					'status',
					'account_id'
				],
				'columns_with_alias' => [
				],
			];
		}

		if (in_array('fin_operations', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['flc' => 'fin_operations'],
				'new_column' => 'operations',
				'joined_table_key' => 'contractor_1_id',
				'other_table_key' => 'fc.contractor_id',
				'columns' => [],
				'where' => [
					'time_type = "real"',
					'operation_type_id = 1'
				],
				'columns_with_alias' => [
					'amount2' => 'amount',
					'currency2' => 'currency'
				],
			];

			$params['has_many'][] = [
				'table' => ['flc' => 'fin_operations'],
				'new_column' => 'planned_operations',
				'joined_table_key' => 'contractor_1_id',
				'other_table_key' => 'fc.contractor_id',
				'columns' => [],
				'columns_with_alias' => [
					'amount2' => 'amount',
					'currency2' => 'currency'
				],
				'where' => [
					'time_type = "plan"',
					'operation_type_id = 1'
				],
			];
		}

		if (in_array('fin_lead_answers', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fla' => 'fin_lead_answers'],
				'new_column' => 'answers',
				'main_table_key' => 'id',
				'other_table_key' => 'lead_id',
				'columns' => ['answer', 'question_id'],
				'columns_with_alias' => [
				],
				'join' => [
					[
						'type' => 'LEFT JOIN',
						'table' => ['fq' => 'fin_questions'],
						'main_table_key' => 'question_id',
						'joined_table_key' => 'id',
						'columns' => [
							'question'
						],
						'columns_with_alias' => [

						]
					],
				]
			];
		}

		return $params;
	}

	/**
	 * Return received mode's data as params-like array for further use, like update
	 */
	public function as_array()
	{
		return get_object_vars($this);
	}
}
