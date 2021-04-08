<?php


namespace App\Models;

use Config;

class Tasks
{
    public static $STATUS_ACTIVE = "active";
    public static $STATUS_DONE = "done";
    public static $STATUS_POSTPONED = "postponed";

	public static function get_tasks($params = [], $join = [], $has_many = ['fin_lead_comments', 'fin_lead_contacts', 'fin_tasks'])
	{
		$query_params = self::select_query(array_merge([], $join), array_merge([], $has_many));
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function add($atts)
	{
		if (!empty($atts['task'])) {
			$params = [
				'task' => $atts['task'],
				'date' => time(),
				'statistics' => !empty($atts['statistics']) ? $atts['statistics'] : 0,
				'comment' => !empty($atts['comment']) ? $atts['comment'] : 0,
				'date_to' => !empty($atts['date_to']) ? $atts['date_to'] : 0,
				'notify' => !empty($atts['notify']) ? $atts['notify'] : 0,
				'lead_id' => !empty($atts['lead_id']) ? $atts['lead_id'] : 0,
				'user_id' => !empty($atts['user_id']) ? $atts['user_id'] : 0,
                'author_id' => !empty($atts['author_id']) ? $atts['author_id'] : 0,
				'status' => !empty($atts['status']) ? $atts['status'] : 'new',
				'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : 0,
			];
			return DBHelp::insert('fin_tasks', $params);
		}
	}

    /**
     * @param $atts
     * @return array|boolean
     *
     * Зберігає обновлені дані задачі з поста в БД. Без валідації, тільки з елементарним захистом від дурака
     */
    public static function edit($atts)
    {
        if(empty($atts['id']) || empty($atts['task']) || empty($atts['date_to'])){
            return false;
        }
        $task_params = self::get_tasks(['where' => [
            'id = ' . $atts['id']
        ]])[0];
        unset($atts['id']);

        foreach($atts as $key => $value){
            if(isset($value) && $value != "" && isset($task_params[$key]) && $key != "account_id" && $key != "account_id"){
                if($key == "notify" && $value=="on"){
                    $value = 1;
                }
                elseif($key == "date" || $key == "date_to" && !empty($value)){
                    $value = strtotime($value);
                }
                $task_params[$key] = $value;
            }
        }
        $result = DBHelp::update('fin_tasks', $task_params['id'],  $task_params);
        return $result;
    }

    /**
     * @param $atts
     * @return array|boolean
     *
     * Зберігає обновлені дані задачі з поста в БД. Без валідації, тільки з елементарним захистом від дурака
     */
    public static function edit_status($atts)
    {
        if(empty($atts['id']) || empty($atts['status'])){
            return false;
        }
        $task_params = self::get_tasks(['where' => [
            'id = ' . $atts['id']
        ]])[0];
        $task_params['status'] = $atts['status'];
        $result = DBHelp::update('fin_tasks', $task_params['id'],  $task_params);
        return $result;
    }

	public static function get_user_daily_tasks($user_id){
	    $today = (int)strtotime("today");
	    $tomorrow = (int)strtotime("tomorrow") - 1;
        return self::get_tasks(['where' => [
            'user_id = ' . $user_id,
            'date_to >= ' . $today,
            'date_to <= ' . $tomorrow
        ]]);
    }

	public static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['ft' => 'fin_tasks'],
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
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [

			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_contractors', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fc' => 'fin_contractors'],
				'main_table_key' => 'user_id',
				'joined_table_key' => 'user_id',
				'columns' => ['telegram_chat_id'],
				'columns_with_alias' => [

				]
			];
		}

		return $params;
	}
}
