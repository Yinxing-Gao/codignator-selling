<?php


namespace App\Models;

class Access
{
	public static function get_access($user_id)
	{
		$professions = Position::get_positions_with_access($user_id);
		$access_list = [];
		if (!empty($professions)) {
			$query_row = '';
			foreach ($professions as $profession) {
				if (strlen($profession['access']) > 0) {
					$query_row .= $profession['access'] . ',';
				}
			}

			$access_id_array = array_unique(explode(',', $query_row));
			$query_row = implode(',', $access_id_array);
			$accesses = [];
			if (strlen($query_row) > 0) {
				$accesses = self::get_accesses(['where' => [
					'id IN (' . substr($query_row, 0, -1) . ')'
				]]);
			}

			if (!empty($accesses)) {
				foreach ($accesses as $access) {
					$access_list[] = $access['name'];
				}
			}
		}
		return $access_list;
	}

	public static function get_accesses($params = [], $join = [])
	{
		$query_params = self::select_query($join);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_accesses_tree($params = [], $parent_id = 0, $locale = 'uk')
	{
		return self::get_access_branch($parent_id, self::get_accesses(DBHelp::params_merge(['where' => [

		]], $params), []), $locale);
	}

	public static function get_access_branch($parent_id, $accesses, $locale)
	{
		$access_branch = [];
		if (!empty($accesses)) {
			foreach ($accesses as $access) {
				if ($access['parents_item_id'] == $parent_id) {
					$access_branch[] =
						[
							'id' => $access['id'],
							'name' => lang_('Access.' . $access['name'], $locale),
							'description' => lang_('Access.' . $access['description'], $locale),
							'type' => $access['type'],
							'children' => self::get_access_branch($access['id'], $accesses, $locale)
						];
				}
			}
		}
		return $access_branch;
	}

	public static function select_query($join_keys = [])
	{
		return [
			'table' => ['fa' => 'fin_accesses'],
			'columns' => [
				'id',
				'name',
//				'description',
				'type',
				'parent_id'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
			],
//			'order_by' => 'page',
			'limit' => null,
			'offset' => null,

		];
	}
}
