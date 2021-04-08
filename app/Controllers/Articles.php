<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Articles extends BaseController
{
	public function index($department_id = null)
	{
		if (!empty($this->user_id)) {
			if (empty($department_id)) {
				$department_id = Models\Departments::get_single_department_or_with_children();
			}

			return $this->view('articles/list',
				[
					'department_articles' => Models\Articles::get_articles(['where' => [
						'department_id = ' . $department_id
					]]),
					'tree' => Models\Articles::get_articles_tree_(['where' => [
						'department_id = ' . $department_id
					]]),
					'departments' => Models\Departments::get_departments(),
					'department_id' => $department_id,
					'css' => ['table', 'articles'],
					'js' => ['articles']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function tree()
	{
		if (!empty($this->user_id)) {
			return $this->view('articles/tree',
				[
					'css' => ['jquery.tree', 'articles'],
					'js' => ['jquery.tree', 'articles']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function templates($department_id = null)
	{
		if (!empty($this->user_id)) {
			return $this->view('articles/templates',
				[
					'template_tree' => Models\Articles::get_templates_tree(),
					'article_types_lang' => [
						'income' => 'дохід',
						'expense' => 'витрата'
					],
					'department_id' => $department_id,
					'css' => ['table', 'articles'],
					'js' => ['articles']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function get_articles_tree_ajax($parent_article_id)
	{
		$type = $_POST['type'];
		echo json_encode(Models\Articles::get_articles_tree_([], [], false, $parent_article_id)[$type]);
	}

	public function search_ajax($type = null)
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		echo json_encode(Models\Articles::search($query, $type));
	}

	public function update_articles_tree_ajax($article_id)
	{
		if (!empty($_POST['parent_id'])) {
			$parent_id = $_POST['parent_id'];
			echo json_encode(Models\Articles::update($article_id, ['parents_item_id' => $parent_id]));
		}
	}

	public function add_from_template_ajax()
	{
		$ids_array = (array)$_POST['ids'];
		$department_id = $_POST['department_id'];
		$account_id = $_POST['account_id'];
		echo json_encode(
			Models\Articles::add_from_templates($department_id, $ids_array, $account_id)
		);
	}

	public function add_ajax()
	{
		if (!empty($_POST)) {
			echo json_encode(Models\Articles::add($_POST));
		}
	}

	public
	function tree_ajax($article_id)
	{

		return $this->view('articles/tree',
			[
				'article' => Models\Articles::get_article(['where' => ['fa.id = ' . $article_id]]),
				'css' => ['jquery.tree'],
				'js' => ['jquery.tree']
			], 'popup'
		);
	}

	public function change_visibility_ajax($id)
	{
		$is_shown = !empty($_POST['is_shown']) ? 1 : 0;
		echo json_encode(Models\Articles::update($id, ['is_shown' => $is_shown]));
	}

	public function delete_ajax($article_id)
	{
		echo json_encode(Models\Articles::delete_article($article_id));
	}
}
