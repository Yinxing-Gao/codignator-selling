<?php


namespace App\Models;

use Config;
use Exception;

class Production
{
//    public static function get_specification($production_product_id)
//    {
//        $db = Config\Database::connect();
//        $query = $db->query('SELECT  fs.id, fsn.name, fs.amount, fsn.unit_id,fu.name as unit, block_id,storage_name_id
//									FROM `fin_specification` fs
//									INNER JOIN fin_storage_names fsn ON fsn.id=fs.storage_name_id
//									LEFT JOIN fin_machine_blocks fmb ON fmb.id=fs.block_id
//									LEFT JOIN `fin_units` fu ON fsn.unit_id=fu.id
//									WHERE production_product_id IN (' . $production_product_id . ')
//									ORDER BY block_id');
//        return $query->getResultArray();
//    }
	public static function get_specifications($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(
			array_merge(['fin_contracts', 'fin_contractors', 'fin_types', 'fin_departments'], $join),
			array_merge([], $has_many)
		);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_specification($params = [], $join = [], $has_many = [])
	{
		$query_params = self::select_query(
			array_merge([], $join),
			array_merge([], $has_many)
		);
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}

	public static function add_specification($atts)
	{
		$params = [
			'name' => $atts['name'],
			'comment' => !empty($atts['comment']) ? $atts['comment'] : '',
			'photo' => !empty($atts['photo']) ? $atts['photo'] : 0,
			'is_virtual' => !empty($atts['is_virtual']) ? $atts['is_virtual'] : 0,
			'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : Account::get_current_account_id()
		];
		if (!empty($atts['id'])) {
			return DBHelp::update('fin_specification', $atts['id'], $params);
		} else {
			return DBHelp::insert('fin_specification', $params);
		}
	}

	public static function get_product_specifications($production_product_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT *
                                    FROM `fin_specification`
                                    WHERE `production_product_id` = ' . $production_product_id
		);
		return $query->getResultArray();
	}

	public static function get_account_specifications($production_product_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT *
                                    FROM `fin_specification`
                                    WHERE `production_product_id` = ' . $production_product_id
		);
		return $query->getResultArray();
	}

	public static function get_specification_items_count()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT production_product_id, COUNT(id) as count
									FROM `fin_specification` fs
									GROUP BY production_product_id');
		$counts = $query->getResultArray();
		$result_counts = [];
		foreach ($counts as $count) {
			$result_counts[$count['production_product_id']] = $count['count'];
		}
		return $result_counts;
	}

	public static function get_specification_with_amounts($production_product_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT  fs.id, fs.storage_name_id, fsn.name, fs.amount, fsn.unit_id,fu.name as unit, block_id, fsi.amount as storage_amount, fsi.buy_price as storage_item_price,fsi.id as item_id
									FROM `fin_specification` fs
									INNER JOIN fin_storage_names fsn ON fsn.id = fs.storage_name_id
                                    LEFT JOIN `fin_storage_items` fsi ON fsn.id = `fsi`.storage_name_id
									LEFT JOIN fin_machine_blocks fmb ON fmb.id = fs.block_id
									LEFT JOIN `fin_units` fu ON fsn.unit_id = fu.id
                                    WHERE production_product_id IN (' . $production_product_id . ')
									ORDER BY block_id');
		return $query->getResultArray();
	}

	public static function get_production_products_with_parts()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM fin_products fpp WHERE department_id = 1 AND has_parts = 2');
		return $query->getResultArray();
	}

//	public static function get_production_products_with_specifications()
//	{
//		$db = Config\Database::connect();
//		$query = $db->query('SELECT fpp.id, name FROM fin_products fpp RIGHT JOIN (SELECT production_product_id FROM `fin_specification` GROUP BY production_product_id) fs ON fs.production_product_id=fpp.id');
//		return $query->getResultArray();
//	}

	public static function get_unit_id($unit)
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM fin_units fu WHERE name LIKE "' . $unit . '%"');
		$unit = $query->getFirstRow();
		if (!empty($unit)) {
			return $unit->id;
		}
		return 0;
	}

	public static function get_units()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM fin_units fu');
		return $query->getResultArray();
	}


	public static function get_machine_blocks()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT * FROM `fin_machine_blocks`');
		return $query->getResultArray();
	}

	public static function change_block_in_specification($spec_item_id, $block_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('UPDATE `fin_specification` SET block_id=' . $block_id . ' WHERE id=' . $spec_item_id);

		$query->getResult();
		return ['status' => 'ok', 'id' => $db->insertID()];
	}

	public static function change_amount_in_specification_item($spec_item_id, $amount)
	{
		$db = Config\Database::connect();

		$query = $db->query('UPDATE `fin_specification_items` SET amount=' . $amount . ' WHERE id=' . $spec_item_id);

		$query->getResult();
		return ['status' => 'ok', 'id' => $db->insertID()];
	}

    public static function change_name_in_specification($specification_id, $name)
    {
        $db = Config\Database::connect();

        $query = $db->query('UPDATE `fin_specification` SET name = "' . $name . '" WHERE id=' . $specification_id);

        $query->getResult();
        return ['status' => 'ok', 'id' => $db->insertID()];
    }

	public static function delete_item_from_specification($item_id)
	{
		$db = Config\Database::connect();
		$query = $db->query('DELETE FROM `fin_specification` WHERE id=' . $item_id);

		$query->getResult();
		return ['status' => 'ok', 'id' => $db->insertID()];
	}

    public static function get_subspecification_item($specification_item_id)
    {
        $db = Config\Database::connect();
        $query = $db->query('SELECT fsi.*, fs.name as name
									FROM `fin_specification_items` fsi
									INNER JOIN fin_specification fs ON fsi.subspecification_id = fs.id
									WHERE fsi.id = ' . $specification_item_id);
        return $query->getResultArray();
    }

    public static function get_parent_specification_id($specification_id)
    {
        $db = Config\Database::connect();
        $query = $db->query('SELECT fsi.specification_id
									FROM `fin_specification_items` fsi
									WHERE fsi.subspecification_id = ' . $specification_id);
        $result = $query->getResultArray();
        return !empty($result) ? $result[0]['specification_id'] : 0;
    }

    public static function get_storage_name_subspecification_item($storage_name_specification_item_id)
    {
        $db = Config\Database::connect();
        $query = $db->query('SELECT fsi.*, fsn.name as name, fs.name as storage_name
									FROM `fin_specification_items` fsi
									INNER JOIN fin_storage_names fsn ON fsi.storage_name_id = fsn.id
									INNER JOIN fin_storage fs ON fsn.storage_id = fs.id
									WHERE fsi.id = ' . $storage_name_specification_item_id);
        return $query->getResultArray();
    }

	public static function add_update_specification_item($specification_id, $atts)
	{
//		$db = Config\Database::connect();
//		if (!empty($id_arr)) {
//			foreach ($id_arr as $id) {
//				$query = $db->query('INSERT INTO `fin_specification`(`storage_name_id`, `production_product_id`, `amount`, `block_id`) VALUES (' . $id . ', ' . $product_id . ', "0,0", 0)');
//
//				$query->getResult();
//			}
//		}
//		return ['status' => 'ok', 'id' => $db->insertID()];
		if (!empty($specification_id) && !empty($atts['amount'] && $atts['amount'] > 0)) {
			$params = [
				'specification_id' => $specification_id,
				'subspecification_id' => !empty($atts['subspecification_id']) ? $atts['subspecification_id'] : 0,
				'storage_name_id' => !empty($atts['storage_name_id']) ? $atts['storage_name_id'] : 0,
				'amount' => !empty($atts['is_virtual']) ? $atts['is_virtual'] : $atts['amount'],
				'type' => !empty($atts['type']) ? $atts['type'] : 'service'
			];
			if (!empty($atts['id'])) {
				return DBHelp::update('fin_specification_items', $atts['id'], $params);
			} else {
				return DBHelp::insert('fin_specification_items', $params);
			}
		}
	}

    public static function delete_specification_item($item_id)
    {
        $db = Config\Database::connect();
        $query = $db->query('DELETE FROM `fin_specification_items` WHERE id=' . $item_id);

        $query->getResult();
        return ['status' => 'ok', 'id' => $db->insertID()];
    }

	public static function copy_specification($from_id, $to_id)
	{
		if (!empty($from_id) && !empty($to_id)) {

			$db = Config\Database::connect();
			$query = $db->query('DELETE FROM `fin_specification` WHERE production_product_id=' . $to_id);
			$query->getResult();

			$new_specification = self::get_specification($from_id);
			if (!empty($new_specification)) {
				$query_row = 'INSERT INTO `fin_specification`(`storage_name_id`, `production_product_id`, `amount`, `block_id`) VALUES ';
				foreach ($new_specification as $item) {
					$query_row .= '( ' . $item['storage_name_id'] . ', ' . $to_id . ', ' . $item['amount'] . ', ' . $item['block_id'] . '),';
				}
//				echo $query_row;
				$query = $db->query(substr($query_row, 0, -1));
			}
			return ['status' => 'ok', 'id' => $db->insertID()];
		} else {
			return ['status' => 'error', 'message' => 'Нема id'];
		}
	}

	public static function move_to_project($storage_id, $project_id, $specification_items_ids)
	{
		try {
			if (!empty($specification_items_ids)) {
				$db = Config\Database::connect();
				$purchase_items = [];
				foreach ($specification_items_ids as $specification_item) {
					$query = $db->query('SELECT * FROM `fin_specification` WHERE id =' . $specification_item['id']);
					$specification = $query->getFirstRow();
					$query = $db->query('SELECT * FROM `fin_storage_items` WHERE id =' . $specification_item['storage_item_id']);
					$storage_item = $query->getFirstRow();

					$purchase_items[] = [
						'id' => $specification->storage_name_id,
						'price' => $storage_item->buy_price,
						'item_id' => $storage_item->id,
						'amount' => $storage_item->amount < $specification->amount ? $storage_item->amount : $specification->amount,
						'block_id' => $specification->block_id
					];
				}
//				echo "<pre>";
//				var_dump($purchase_items);
//				echo "</pre>";
//				die();
				Storage::add_expense(
					[
						'project_id' => $project_id,
						'operation_id' => 0,
						'comment' => 'Списання зі складу згідно проекту № ' . $project_id,
						'storage_id' => $storage_id,
						'date' => time(),
						'names' => $purchase_items
					]
				);
//				$specifications = $query->getResultArray();
//				if (!empty($specifications)) {
//					foreach ($specifications as $specification) {
//
//					}
//				}
			}
		} catch (Exception $e) {

			Telegram::send_admin_message('Помилка в Models/Storage/add_income.' . " \r\n" . 'e - ' . $e);
			return ['status' => 'error', 'message' => 'Помилка відправлена адміністратору і буде виправлена найближчим часом'];
		}

	}


	public static function generate_app_from_spec($missing, $user_id, $project_id)
	{
		if (!empty($missing)) {
			$project = Projects::get_project($project_id);
			$types = [
				1 => ["amount" => 0, "data" => "На складі недостатньо деталей для завершення проекту " . $project->name . ":<br/>"],
				2 => ["amount" => 0, "data" => "На складі недостатньо деталей для завершення проекту " . $project->name . ":<br/>"],
				3 => ["amount" => 0, "data" => "На складі недостатньо деталей для завершення проекту " . $project->name . ":<br/>"],
				4 => ["amount" => 0, "data" => "На складі недостатньо деталей для завершення проекту " . $project->name . ":<br/>"],
			];

			foreach (json_decode($missing) as $storage_name_id => $amount) {
				$name = Storage::get_name($storage_name_id);
				$types[$name->type_id]['amount'] += ($name->buy_price * $amount);
				$types[$name->type_id]['data'] .= $name->name . '(' . $name->buy_price . ' грн) - ' . $amount . ' ' . $name->unit . '(' . (float)($amount * $name->buy_price) . 'грн) <br/>';
			}

			$atts ['author_id'] = $user_id;
			$atts ['department_id'] = 1;
			$atts ['date_for'] = date("Y-m-d", $project->date);
			$atts ['amount'] = 0;
			$atts ['currency'] = 'UAH';
			$atts ['type_id'] = 4;
			$atts ['project_id'] = $project_id;
			$atts ['product'] = "Комплектуючі для проекту " . $project->name;
			$atts ['article_id'] = 3;
			$atts ['situation'] = "На складі недостатньо деталей";
			$atts ['data'] = '';
			$atts ['decision'] = "Закупити необхідні деталі";
			$atts ['uploaded_files'] = "";

			foreach ($types as $type => $type_row) {
				if (!empty($type_row['amount'])) {
					$atts ['amount'] = $type_row['amount'];
					$data_end = "<br/> Загальна сума - " . $type_row['amount'] . "грн<br/>Дата старту проекту - " . date("d.m.Y", $project->date);
					$atts ['data'] = $type_row['data'] . $data_end;
					$atts ['type_id'] = $type;
					Applications::add_app($atts);
				}

			}
		}
	}

	public static function get_specification_parents($specification_id, &$parents = null){
	    if(!$parents){
	        $parents = [];
        }

	    $specification_parent_id = self::get_parent_specification_id($specification_id);
	    if(!empty($specification_parent_id)){
            $parents[] = self::get_specification(['where' => [
                'fs.id = ' . $specification_parent_id
            ]]);
            self::get_specification_parents($specification_parent_id, $parents);
        }
        return $parents;
    }


//	public static function set_specification()
//	{
//		$db = Config\Database::connect();
//		$items = Storage::get_storage_items(1);
//		if (!empty($items)) {
//			foreach ($items as $item) {
//				$query = $db->query('INSERT INTO `fin_specification`(`item_id`, `machine_id`, `amount`) VALUES (' . $item['id'] . ', 5, ' . $item['amount'] .')');
//				$result = $query->getResultArray();
//			}
//			return ['status' => 'ok'];
//		} else {
//			return ['status' => 'error'];
//		}
//	}

	public static function select_query($join_keys = [], $has_many_keys = [])
	{
		$params = [
			'table' => ['fs' => 'fin_specification'],
			'columns' => [
				'id',
				'name',
				'comment',
				'photo',
				'is_virtual',
				'account_id'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
//				'fp.account_id = ' . $account_id
			],
			'limit' => null,
			'offset' => null
		];

		if (in_array('fin_specification_items', $has_many_keys)) {
			$params['has_many'][] = [
				'table' => ['fsi' => 'fin_specification_items'],
				'new_column' => 'items',
				'main_table_key' => 'id',
				'other_table_key' => 'specification_id',
				'columns' => ['id', 'specification_id', 'subspecification_id', 'storage_name_id', 'amount', 'type'],
				'columns_with_alias' => [],
				'join' => [
					[
						'type' => 'LEFT JOIN',
						'table' => ['fs' => 'fin_specification'],
						'main_table_key' => 'subspecification_id',
						'joined_table_key' => 'id',
						'columns' => [
						],
						'columns_with_alias' => [
							'name' => 'specification_name'
						]
					],
					[
						'type' => 'LEFT JOIN',
						'table' => ['fsn' => 'fin_storage_names'],
						'main_table_key' => 'storage_name_id',
						'joined_table_key' => 'id',
						'columns' => [
							'unit_id',
							'article'
						],
						'columns_with_alias' => [
							'name' => 'storage_item_name'
						]
					],
					[
						'type' => 'LEFT JOIN',
						'table' => ['fu' => 'fin_units'],
						'other_table_key' => 'fsn.unit_id',
						'joined_table_key' => 'id',
						'columns' => [
						],
						'columns_with_alias' => [
							'name' => 'unit_name'
						]
					],
					[
						'type' => 'LEFT JOIN',
						'table' => ['fst' => 'fin_storage'],
						'other_table_key' => 'fsn.storage_id',
						'joined_table_key' => 'id',
						'columns' => [
						],
						'columns_with_alias' => [
							'name' => 'storage_name'
						]
					]
				]
			];
		}
		return $params;
	}

    public static function select_items_query($query_params = [], $join_keys = [], $has_many_keys = [])
    {
        $params = [
            'table' => ['fsi' => 'fin_specification_items'],
            'columns' => [
                'id',
                'name',
                'comment',
                'photo',
                'is_virtual',
                'account_id'
            ],
            'columns_with_alias' => [
//				'column' => 'alias'
            ],
            'join' => [],
            'where' => [
//				'fp.account_id = ' . $account_id
            ],
            'limit' => null,
            'offset' => null
        ];
        $params = array_merge($params, $query_params);
        if ($join_keys) {
            $params['join'][] = $join_keys;
        }
        return $params;
    }
}

