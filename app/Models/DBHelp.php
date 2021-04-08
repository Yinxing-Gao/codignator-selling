<?php

namespace App\Models;

use Config;
use CodeIgniter\Database\Exceptions\DatabaseException;

class DBHelp
{
	public static function insert($table, $params)
	{
		try {
			$db = Config\Database::connect();
			if (!empty($table) && !empty($params)) {
				$query = "INSERT INTO `" . $table . "`(";
				$query2 = ") VALUES (";
				foreach ($params as $param => $value) {
					if (!empty($value)) {
						$query .= "`" . $param . "`, ";
						if (is_int($value)) {
							$query2 .= $value . ", ";
						} elseif (is_string($value)) {
							$query2 .= '"' . htmlspecialchars(trim($value)) . '", ';
						} else {
							$query2 .= '"' . htmlspecialchars(trim($value)) . '", ';
						}
					}
				}
				$query_string = substr($query, 0, -2) . substr($query2, 0, -2) . ')';

				$db_query = $db->query($query_string);
				$db_query->getResult();
				if ($db->affectedRows() > 0) {
					return ['status' => 'ok', /*'query' => $query_string,*/ 'id' => $db->insertID(), 'message' => "New record created successfully"];
				} else {
					return ['status' => 'error', /*'query' => $query_string,*/ 'message' => "DB error"];
				}
			}
		} catch (DatabaseException $e) {
			return ['status' => 'error', 'message' => $e];
		} catch (Exception $e) {
			Dev::var_dump('insert');
			Dev::var_dump($table);
			Dev::var_dump($params);
			return ['status' => 'error', 'message' => $e];
		}
	}

	public static function update($table, $id, $params)
	{
		try {
			$db = Config\Database::connect();
			if (!empty($table) && !empty($params)) {
				$query = "UPDATE `" . $table . "` SET ";
				foreach ($params as $param => $value) {
//            if (!empty($value)) {
					$query .= "`" . $param . "` =  ";
					if (is_int($value)) {
						$query .= $value . ", ";
					} elseif (is_string($value)) {
						$query .= '"' . htmlspecialchars(trim($value)) . '", ';
					} else {
						$query .= '"' . htmlspecialchars(trim($value)) . '", ';
					}
//            }
				}
				$query_string = substr($query, 0, -2) . ' WHERE ID = ' . $id;

				$db_query = $db->query($query_string);
				$db_query->getResult();
				return ['status' => 'ok', 'query' => $query_string, 'id' => $id, 'message' => "updated"];
			}
		} catch (DatabaseException $e) {
			return ['status' => 'error', 'message' => $e];
		} catch (Exception $e) {
			Dev::var_dump('update');
			Dev::var_dump($table);
			Dev::var_dump($params);
			return ['status' => 'error', 'message' => $e];
		}

	}

	public static function update_where($table, $where, $params)
	{
		try {
			$db = Config\Database::connect();
			if (!empty($table) && !empty($params) && !empty($where)) {
				$query = "UPDATE `" . $table . "` SET ";
				foreach ($params as $param => $value) {
					$query .= "`" . $param . "` =  ";
					if (is_int($value)) {
						$query .= $value . ", ";
					} elseif (is_string($value)) {
						$query .= '"' . htmlspecialchars(trim($value)) . '", ';
					} else {
						$query .= '"' . htmlspecialchars(trim($value)) . '", ';
					}
				}
				$query_string = substr($query, 0, -2);

				$where_array = $where;
				$where_query = ' WHERE ';
				for ($i = 0; $i < count($where_array); $i++) {
					if ($i > 0) {
						$where_query .= ' AND ' . $where_array[$i];
					} else {
						$where_query .= $where_array[0];
					}
				}

				$query_string .= $where_query;

				$db_query = $db->query($query_string);
				$db_query->getResult();
				return ['status' => 'ok', 'query' => $query_string, 'message' => "updated"];
			}
		} catch (DatabaseException $e) {
			return ['status' => 'error', 'message' => $e];
		}
	}

	public static function delete($table, $id)
	{
		if (!empty($table) && !empty($id)) {
			$db = Config\Database::connect();
			$query_string = "DELETE FROM `" . $table . "` WHERE id = " . $id;
			$db_query = $db->query($query_string);
			$db_query->getResult();
			return ['status' => 'ok', 'query' => $query_string, 'message' => "deleted"];
		}
	}

	public static function delete_where($table, $where_array = [])
	{
		if (!empty($table)) {
			$db = Config\Database::connect();
			$where_query = '';
			if (!empty($where_array)) {
				$where_query = ' WHERE ';
				for ($i = 0; $i < count($where_array); $i++) {
					if ($i > 0) {
						$where_query .= ' AND ' . $where_array[$i];
					} else {
						$where_query .= $where_array[0];
					}
				}
			}
			$query_string = "DELETE FROM `" . $table . "`" . $where_query;
			$db_query = $db->query($query_string);
			$db_query->getResult();
			return ['status' => 'ok', 'query' => $query_string, 'message' => "deleted"];
		}
	}

	public static function select($params, $result_type = 'array_all')
	{
		try {
			if (!empty($params['table'])) {
				$db = Config\Database::connect();
				$tables = [];
				$query = 'SELECT ';
				$join_query = '';
				$where_query = '';
				$order_by = !empty($params['order_by']) ? ' ORDER BY ' . $params['order_by'] . ' ' : '';
				$group_by = !empty($params['group_by']) ? ' GROUP BY ' . $params['group_by'] . ' ' : '';
				$limit = !empty($params['limit']) ? ' LIMIT ' . $params['limit'] . ' ' : '';
				$offset = !empty($params['offset']) ? ' OFFSET ' . $params['offset'] . ' ' : '';
				$table_arr = $params['table'];
				$alias = key($table_arr) !== range(0, count($table_arr) - 1) ? key($table_arr) : self::alias($table_arr);
				$table = $table_arr[$alias];
				$tables[$alias] = $table;

				if (!empty($params['columns'])) {
					$columns = $params['columns'];
					foreach ($columns as $column) {
						$query .= $alias . '.`' . $column . '`, ';
					}
					$query = substr($query, 0, -2);
				} else {
					$query .= $alias . '.* ';
				}

				if (!empty($params['custom_columns'])) {
					if (!empty($params['columns'])) {
						$query .= ', ';
					}

					$custom_columns = $params['custom_columns'];
					foreach ($custom_columns as $custom_column => $column_alias) {
						$query .= $custom_column . ' AS ' . $column_alias . ', ';
					}
					$query = substr($query, 0, -2);
				}

				if (!empty($params['columns_with_alias'])) {
					if (!empty($params['columns']) || !empty($params['custom_columns'])) {
						$query .= ', ';
					}
					$columns_with_alias = $params['columns_with_alias'];
					foreach ($columns_with_alias as $column => $column_alias) {
						$query .= $alias . '.`' . $column . '` AS ' . $column_alias . ', ';
					}
					$query = substr($query, 0, -2);
				}

				if (!empty($params['join'])) {
					if (!empty($params['columns']) || !empty($params['columns_with_alias'])) {
						$query .= ', ';
					}
					foreach ($params['join'] as $join_table) {
						if (in_array($join_table['type'], ['INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN'])
							&& !empty($join_table['table'])
							&& (!empty($join_table['main_table_key']) || !empty($join_table['other_table_key']))
							&& (!empty($join_table['columns']) || !empty($join_table['columns_with_alias']) || !empty($join_table['custom_columns']))) {

							$table_arr_ = $join_table['table'];
							//дописати провірку чи не повторюються аліаси
							$alias_ = key($table_arr_) !== range(0, count($table_arr_) - 1) ? key($table_arr_) : self::alias($table_arr_);
							$table_ = $table_arr_[$alias_];


							if (!empty($join_table['columns'])) {
								foreach ($join_table['columns'] as $column_) {
									$query .= $alias_ . '.`' . $column_ . '`, ';
								}
							}

							if (!empty($join_table['custom_columns'])) {
								$custom_columns = $join_table['custom_columns'];
								foreach ($custom_columns as $custom_column => $column_alias) {
									$query .= $custom_column . ' AS ' . $column_alias . ', ';
								}
							}

							if (!empty($join_table['columns_with_alias'])) {
//								if (!empty($join_table['columns'])) {
//									$query .= ', ';
//								}
								foreach ($join_table['columns_with_alias'] as $column_ => $column_alias_) {
									{
										$query .= $alias_ . '.`' . $column_ . '` AS ' . $column_alias_ . ', ';
									}
								}
							}
							$joined_table_key = !empty($join_table['joined_table_key']) ? $join_table['joined_table_key'] : 'id';
							$second_table_alias_and_name = !empty($join_table['other_table_key']) ? $join_table['other_table_key'] : $alias . '.' . $join_table['main_table_key'];
							$join_query .= ' ' . $join_table['type'] . ' ' . $table_ . ' ' . $alias_ . ' ON ' . $alias_ . '.' . $joined_table_key . ' = ' . $second_table_alias_and_name;
						}
					}
					$query = substr($query, 0, -2);
				}

				if (!empty($params['custom_join'])) {
					if (!empty($params['columns']) || !empty($params['columns_with_alias'])) {
						$query .= ', ';
					}

					foreach ($params['custom_join'] as $custom_join) {
						$query .= implode(',', $custom_join['columns']);
						$join_query .= ' ' . $custom_join['query'];
					}
				}

				if (!empty($params['where'])) {
					$where_array = $params['where'];
					$where_query .= ' WHERE ';
					for ($i = 0; $i < count($where_array); $i++) {
						if ($i > 0) {
							$where_query .= ' AND ' . $where_array[$i];
						} else {
							$where_query .= $where_array[0];
						}
					}
				}
				$query .= ' FROM `' . $table . '` ' . $alias . ' ' . $join_query . $where_query . $group_by . $order_by . $limit . $offset;
//echo '<div style="display:none" data-table="' .$table .'">';
//				Dev::var_dump($query);
//				echo '</div>';

				$query_obj = $db->query($query);

				switch ($result_type) {
					case 'single':
						$result = $query_obj->getFirstRow();
//						Dev::var_dump($result);
//						Dev::var_dump($params['has_many']);
						if (!empty($result) && !empty($params['has_many'])) {
							$result = (object)self::has_many($result, $params['has_many']);
						}

//						return ['status' => 'ok', 'result' => $query_obj->getFirstRow(), 'query' => $query];
						break;
					default:
						$result = $query_obj->getResultArray();
						if (!empty($result) && !empty($params['has_many'])) {
							foreach ($result as &$row) {
								$row = self::has_many($row, $params['has_many']);
							}
						}
						break;
				}
				return ['status' => 'ok', 'result' => $result, 'query' => $query];
			}

		} catch (DatabaseException $e) {
			return ['status' => 'error', 'message' => $e];
		} catch (Exception $e) {
			Dev::var_dump('select');
			Dev::var_dump($params);
			return ['status' => 'error', 'message' => $e];
		}
	}

	public static function has_many($row, $has_many_params)
	{
		$row = (array)$row;
//		Dev::var_dump($row);
		foreach ($has_many_params as $has_many_param) {
			$has_many_param['other_table_key'] = !empty($has_many_param['other_table_key']) ? $has_many_param['other_table_key'] : $has_many_param['main_table_key'];
			$table = $has_many_param['table'];
			$type = !empty($has_many_param['type']) ? $has_many_param['type'] : 'equal';

			switch ($type) {
				case 'equal':
					$params = [
						'table' => $has_many_param['table'],
						'columns' => $has_many_param['columns'],
						'columns_with_alias' => $has_many_param['columns_with_alias'],
						'join' => !empty($has_many_param['join']) ? $has_many_param['join'] : [],
						'where' => [
							key($table) . '.' . $has_many_param['other_table_key'] . ' = ' . $row[$has_many_param['main_table_key']]
						]
					];
					$row[$has_many_param['new_column']] = self::select($params)['result'];
					break;
				case 'in':
					if (!empty($row[$has_many_param['main_table_key']])) {
						$params = [
							'table' => $has_many_param['table'],
							'columns' => $has_many_param['columns'],
							'columns_with_alias' => $has_many_param['columns_with_alias'],
							'join' => !empty($has_many_param['join']) ? $has_many_param['join'] : [],
							'where' => [
								key($table) . '.' . $has_many_param['other_table_key'] . ' IN ( ' . $row[$has_many_param['main_table_key']] . ')'
							]
						];

						$row[$has_many_param['new_column']] = self::select($params)['result'];
					} else {
						$row[$has_many_param['new_column']] = [];
					}
					break;
			}
		}
		return $row;
	}


	public static function params_merge($params1, $params2)
	{
		$result = $params1;
		if (!empty($params1) && !empty($params2)) {
			$fields = ['columns', 'columns_with_alias', 'limit', 'where'];
			foreach ($fields as $field) {
				if (!empty($params1[$field]) && !empty($params2[$field])) {
					$result[$field] = array_merge($params1[$field], $params2[$field]);
				} elseif (!empty($params1[$field]) && empty($params2[$field])) {
					$result[$field] = $params1[$field];
				} elseif (empty($params1[$field]) && !empty($params2[$field])) {
					$result[$field] = $params2[$field];
				} else {
					$result[$field] = [];
				}
			}
			if (!empty($params1['limit'])) {
				$result['limit'] = !empty($params2['limit']) ? $params2['limit'] : $params1['limit'];
			}
			if (!empty($params1['offset'])) {
				$result['offset'] = !empty($params2['offset']) ? $params2['offset'] : $params1['offset'];
			}
			if (!empty($params1['order_by'])) {
				$result['order_by'] = !empty($params2['order_by']) ? $params2['order_by'] : $params1['order_by'];
			}
		}
		return $result;
	}

	public
	static function alias($table_name)
	{
		$parts = explode('_', $table_name[0]);
		$alias = '';
		if (!empty($parts)) {
			foreach ($parts as $part) {
				$alias .= substr($part, 0, 2);
			}
		}
		return $alias;
	}

	public static function parse_query($string)
	{
		preg_match_all('/`[a-zA-Z0-9]*_*[a-zA-Z0-9]*`/', $string, $matches);
		$params = '';
		if (!empty($matches)) {
			foreach ($matches[0] as $match) {
				$params .= '"' . trim($match, '`') . '" => "test", ' . "\r\n";
			}
		}
		echo $params;
	}

	public static function change_table($query){
		$db = Config\Database::connect();
		$db_query = $db->query($query);
		$db_query->getResult();
		return ['status' => 'ok', 'query' => $query, 'message' => "updated"];
	}

	public
	static function select_query($join_keys = [])
	{
		$params = [
			'table' => ['ft' => 'fin_table'],
			'columns' => [
				'column'
			],
			'columns_with_alias' => [
				'column' => 'alias'
			],
			'join' => [
				[
					'type' => 'LEFT JOIN',
					'table' => ['ft' => 'fin_table'],
					'other_table_key' => 'fp.id',
					'joined_table_key' => 'table_id',
					'main_table_key' => 'table_id',
					'columns' => ['column'],
					'columns_with_alias' => [
						'column' => 'alias'
					]
				]
			],
			'where' => [
			],
			'limit' => null,
			'offset' => null,
			'has_many' => [
				[
					'table' => ['ft' => 'fin_table'],
					'new_column' => 'users',
					'main_table_key' => 'user_id', // ключ основної таблиці ( береться для WHERE )
					'other_table_key' => 'user_id', // ключ таблиці з запросу ( не обов'язково )
				]
			]
		];
		return $params;
	}
}
