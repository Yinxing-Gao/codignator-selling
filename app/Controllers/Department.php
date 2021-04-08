<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Department extends BaseController
{
	public function index()
	{

		if (!empty($this->user_id)) {
			return $this->view('departments/list',
				[
					'departments' => Models\Departments::get_departments(),
					'tree' => Models\Departments::get_department_tree(),
					'branch_data' => [
						'modules' => Models\Modules::get_modules(),
					],
					'css' => ['table', 'departments'],
					'js' => ['departments']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function get_modules_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		echo json_encode(Models\Modules::search($query));
	}

	public function add_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			$atts['is_shown'] = !empty($_POST['is_shown']) ? 1 : 0;
			$atts['modules'] = !empty($_POST['modules']) ? implode(',', $_POST['modules']) : '';
			echo json_encode(Models\Departments::add($atts));
		}
	}

	public function change_visibility_ajax($id)
	{
		if (!empty($_POST['is_shown'])) {
			echo json_encode(Models\Departments::update($id, ['is_shown' => $_POST['is_shown']]));
		}
	}

	public function delete_ajax($department_id)
	{
		echo json_encode(Models\Departments::delete($department_id));
	}
}
