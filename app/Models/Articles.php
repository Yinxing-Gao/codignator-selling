<?php


namespace App\Models;

use Config;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class Articles
{
	public static function get_articles($params = [], $join = [], $is_template = false)
	{
		$query_params = self::select_query(array_merge(['fin_departments'], $join), $is_template);
		return DBHelp::select(DBHelp::params_merge($query_params, $params))['result'];
	}

	public static function get_article($params = [], $join = [], $is_template = false)
	{
		$query_params = self::select_query(array_merge(['fin_departments'], $join), $is_template);
		return DBHelp::select(DBHelp::params_merge($query_params, $params), 'single')['result'];
	}


	public static function add($atts)
	{
		$params = [
			'name' => !empty($atts['name']) ? $atts['name'] : '',
			'parents_item_id' => !empty($atts['parents_item_id']) ? $atts['parents_item_id'] : 0,
			'type' => !empty($atts['type']) ? $atts['type'] : '',
			'margin_level' => !empty($atts['margin_level']) ? $atts['margin_level'] : 0,
			'period' => !empty($atts['period']) ? $atts['period'] : '',
			'description' => !empty($atts['description']) ? $atts['description'] : '',
			'is_shown' => !empty($atts['is_shown']) ? $atts['is_shown'] : 1,
			'department_id' => !empty($atts['department_id']) ? $atts['department_id'] : 0,
			'is_template' => !empty($atts['is_template']) ? $atts['is_template'] : 0,
			'template_id' => !empty($atts['template_id']) ? $atts['template_id'] : 0,
			'is_base' => !empty($atts['is_base']) ? $atts['is_base'] : 0,
			'account_id' => !empty($atts['account_id']) ? $atts['account_id'] : 0,
		];
		return DBHelp::insert('fin_articles', $params);
	}

	public static function get_articles_tree($type_id = null, $period = null, $department_id = null)
	{
		if (!empty($type_id)) {
			return self::get_articles_branch(0, self::get_articles(['where' => [
				'fa.type = ' . $type_id,
				'period = "' . $period . '"'
			]]));
		} else {
			$params1 = ['where' => [
				'fa.type = "income"',
				'period = "' . $period . '"'
			]];
			$params2 = ['where' => [
				'fa.type = "expense"',
				'period = "' . $period . '"'
			]];
			if (!empty($department_id)) {
				$params1[] = 'department_id = ' . $department_id;
				$params2[] = 'department_id = ' . $department_id;
			}
			return [
				'income' => self::get_articles_branch(0, self::get_articles($params1)),
				'expense' => self::get_articles_branch(0, self::get_articles($params2))
			];
		}
	}

	public static function get_articles_tree_($params = [], $join = [], $is_template = false, $parent_id = 0)
	{
		return [
			'income' => self::get_articles_branch($parent_id, self::get_articles(DBHelp::params_merge(['where' => [
				'fa.type = "income"',
				'is_template = 0'
			]], $params), [], true)),
			'expense' => self::get_articles_branch($parent_id, self::get_articles(DBHelp::params_merge(['where' => [
				'fa.type = "expense"',
				'is_template = 0'
			]], $params), [], true))
		];
	}

	public static function get_templates_tree($params = [])
	{
		return [
			'income' => self::get_articles_branch(0, self::get_articles(DBHelp::params_merge(['where' => [
				'fa.type = "income"',
				'is_template = 1'
			]], $params), [], true)),
			'expense' => self::get_articles_branch(0, self::get_articles(DBHelp::params_merge(['where' => [
				'fa.type = "expense"',
				'is_template = 1'
			]], $params), [], true))
		];
	}

	public static function get_articles_endpoints($params = [], $join = [], $is_template = false)
	{
		return self::get_articles(DBHelp::params_merge([
			'where' => [
				'children_amount IS null'
			],
			'limit' => null,
			'offset' => null
		], $params), array_merge(['custom_get_endpoints'], $join), $is_template);
	}

	public static function get_articles_branch($parent_id, $articles)
	{
		$article_branch = [];
		if (!empty($articles)) {
			foreach ($articles as $article) {
				if ($article['parents_item_id'] == $parent_id) {
					$article_branch[] =
						[
							'name' => $article['name'],
							'id' => $article['id'],
							'description' => $article['description'],
							'type' => $article['type'],
							'period' => $article['period'],
							'is_base' => $article['is_base'],
							'is_shown' => $article['is_shown'],
							'children' => self::get_articles_branch($article['id'], $articles)
						];
				}
			}
		}
		return $article_branch;
	}

	public static function get_articles_children_ids($parent_id, $articles, $main_article_id, &$article_children_ids)
	{
		if (!empty($articles)) {
			foreach ($articles as $article) {
				if ($article['parents_item_id'] == $parent_id) {
					$article_children_ids[$main_article_id]['articles'][] = $article['id'];
					self::get_articles_children_ids($article['id'], $articles, $main_article_id, $article_children_ids);
				}
			}
		}
		return $article_children_ids;
	}

	public static function update_articles_tree($tree)
	{
		self::update_articles_branch(0, $tree);
	}

	public static function update_articles_branch($parent_id, $tree)
	{
		if (!empty($tree)) {
			$db = Config\Database::connect();
			foreach ($tree as $branch) {
				$query_row = 'UPDATE `fin_articles` SET `parents_item_id`="' . $parent_id . '" WHERE id = ' . $branch->id . ';';

				$query = $db->query($query_row);
				if (!empty($branch->children)) {
					self::update_articles_branch($branch->id, $branch->children);
				}
			}
		}
		if ($db->affectedRows() > 0) {
			return ['status' => 'ok'];
		} else {
			return ['status' => 'error', 'message' => 'Щось пішло не так, зверніться до адміністратора'];
		}
	}

	public static function delete_article($article_id)
	{
		DBHelp::delete('fin_articles', $article_id);
		$articles = self::get_articles(['where' => ['parents_item_id = ' . $article_id]]);
		if (!empty($articles)) {
			foreach ($articles as $article) {
				self::delete_article($article['id']);
			}
		}
		return ['status' => 'ok'];
	}

	public static function get_levels()
	{
		$db = Config\Database::connect();
		$query = $db->query('SELECT margin_level FROM `fin_articles` GROUP BY margin_level');
		$levels = $query->getResultArray();
		$result = [];
		if (!empty($levels)) {
			foreach ($levels as $level) {
				$result[] = (int)$level['margin_level'];
			}
		}
		asort($result);
		return $result;
	}


	public static function get_articles_array()
	{
		$articles = self::get_articles();
		$result_articles = [];
		if (!empty($articles)) {
			foreach ($articles as $article) {
				$result_articles [$article['id']] = $article;
			}
		}
		return $result_articles;
	}

	public static function update($id, $params)
	{
		return DBHelp::update('fin_articles', $id, $params);
	}

	public static function add_from_templates($department_id, array $ids, $account_id)
	{
		if (!empty($department_id) && !empty($ids)) {
			$templates_to_copies = [];
			$copies = [];

			foreach ($ids as $id) {
				$template = (array)self::get_article(['where' => ['fa.id = ' . $id]], [], true);
				if (!empty($template)) {
					$template_id = $template['id'];
					unset($template['id']);

					$already_used = (array)self::get_article(['where' => [
						'fa.template_id = ' . $template_id,
						'fa.department_id = ' . $department_id
					]]);

					if (!empty($already_used)) {
						$copy_id = $already_used['id'];
					} else {
						$template['is_template'] = 0;
						$template['department_id'] = $department_id;
						$template['account_id'] = $account_id;
						$template['template_id'] = $template_id;
						$template['is_shown'] = 0;
						$copies[] = $template;

						$copy_result = self::add($template);
						$copy_id = $copy_result['id'];
					}

					$templates_to_copies[$template_id] = $copy_id;
				}
			}

			if (!empty($templates_to_copies)) {
				foreach ($templates_to_copies as $copy_id) {
					$copy = (array)self::get_article(['where' => ['fa.id = ' . $copy_id]]);

					if (!empty($copy['parents_item_id'])) {
						DBHelp::update('fin_articles', $copy_id, [
							'parents_item_id' => $templates_to_copies[$copy['parents_item_id']]
						]);
					}
				}
			}
			return ['status' => 'ok'];
		}
	}

	public static function search($query, $type = null)
	{
		$user_departments_ids = User::get_user_departments_ids(User::get_current_user_id());
		if (!empty($type)) {
			$params = [
				'where' => [
					'fa.department_id IN (' . implode(',', $user_departments_ids) . ')',
					'fa.type = "' . $type . '"',
					'fa.is_shown = 1'
				],
				'order_by' => 'department_id',
				'limit' => null,
				'offset' => null
			];
		} else {
			$params = [
				'where' => [
					'fa.account_id = ' . Account::get_current_account_id(),
					'fa.is_shown = 1'
				],
				'order_by' => 'department_id',
				'limit' => null,
				'offset' => null
			];
		}

		if (!empty($query)) {
			$params['where'][] = '(fa.name LIKE "%' . trim($query) . '%" OR fa.description LIKE "%' . trim($query) . '%")';
		}

		$articles = count($user_departments_ids) > 0 && !empty($type) ? self::get_articles($params) : [];

		$articles_grouped_by_departments = [];
		if (!empty($articles)) {
			foreach ($articles as $article) {
				$articles_grouped_by_departments[$article['department_id']]['children'][] = $article;
			}

			if (!empty($articles_grouped_by_departments)) {
				foreach ($articles_grouped_by_departments as $department_id => &$d_articles) {
					$d_articles['id'] = $department_id;
					$d_articles['name'] = $d_articles['children'][0]['department_name'];
				}
			}
		}
		return $articles_grouped_by_departments;
	}

	public static function get_allowed_articles()
	{

	}


	public static function select_query($join_keys = [], $template = false)
	{
		$session = Config\Services::session();
		$account_id = $session->get('account_id');
		$params = [
			'table' => ['fa' => 'fin_articles'],
			'columns' => [
				'id',
				'name',
				'parents_item_id',
				'type',
				'margin_level',
				'period',
				'description',
				'is_shown',
				'department_id',
				'account_id',
				'is_template',
				'is_base'
			],
			'columns_with_alias' => [
//				'column' => 'alias'
			],
			'join' => [],
			'where' => [
				'fa.account_id = ' . $account_id
			],
			'limit' => null,
			'offset' => null
		];
		if ($template) {
			$params['where'] = [];
		}

		if (in_array('fin_departments', $join_keys)) {
			$params['join'][] = [
				'type' => 'LEFT JOIN',
				'table' => ['fd' => 'fin_departments'],
				'main_table_key' => 'department_id',
				'columns' => [],
				'columns_with_alias' => ['name' => 'department_name']
			];
		}

		if (in_array('custom_get_endpoints', $join_keys)) {
			$params['custom_join'][] = [
				'columns' => ['children_amount', 'parents_item_id_'],
				'query' => 'LEFT JOIN (
					 SELECT COUNT(id) as children_amount, parents_item_id as parents_item_id_
					 FROM `fin_articles`
					 GROUP BY parents_item_id_) fag ON fag.parents_item_id_ = fa.id'
			];
		}

		return $params;
	}
}
