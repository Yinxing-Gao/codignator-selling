<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Access extends BaseController
{
	public function index($department_id = null)
	{
		if (!empty($this->user_id)) {
			$department = Models\Departments::get_department(['where' => ['id = ' . $department_id]]);
			$department_accesses = explode(',', $department->accesses);

			return $this->view('accesses/list',
				[
					'department' => $department,
					'department_accesses' => $department_accesses,
					'menu_items' => Models\Menu::get($this->locale),
//					'accesses' => $accesses_formatted,
					'sidenav_accesses' => Models\Translate::translate_array_key(Models\Access::get_accesses(['where' => [
						'type = "sidenav"',
						'parent_id = 0'
					]]), ['name'], $this->locale, 'Access'),
					'telegram_accesses' => Models\Translate::translate_array_key(Models\Access::get_accesses(['where' => [
						'type = "telegram"',
						'parent_id = 0'
					]]), ['name'], $this->locale, 'Access'),
					'css' => ['access'],
					'js' => ['access']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function position($position_id = null)
	{
		if (!empty($this->user_id)) {
			$position = Models\Departments::get_department(['where' => ['id = ' . $department_id]]);
			$department_accesses = explode(',', $department->accesses);

			return $this->view('accesses/list',
				[
					'department' => $department,
					'department_accesses' => $department_accesses,
					'menu_items' => Models\Menu::get($this->locale),
//					'accesses' => $accesses_formatted,
					'sidenav_accesses' => Models\Translate::translate_array_key(Models\Access::get_accesses(['where' => [
						'type = "sidenav"',
						'parent_id = 0'
					]]), ['name'], $this->locale, 'Access'),
					'telegram_accesses' => Models\Translate::translate_array_key(Models\Access::get_accesses(['where' => [
						'type = "telegram"',
						'parent_id = 0'
					]]), ['name'], $this->locale, 'Access'),
					'css' => ['access'],
					'js' => ['access']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function change_department_access_ajax($department_id)
	{
		if (!empty($_POST)) {
			$access_id = $_POST['access_id'];
			$checked = $_POST['value'];
			$department = Models\Departments::get_department(['where' => ['id = ' . $department_id]]);
			$department_accesses = explode(',', $department->accesses);

			if ($checked) {
				$department_accesses[] = $access_id;
			} else {
				if (!empty($department_accesses)) {
					foreach ($department_accesses as $key => $department_access) {
						if ($department_access == $access_id) {
							unset($department_accesses[$key]);
						}
					}
				}
			}
			return json_encode(Models\Departments::update($department_id, ['accesses' => implode(',', array_unique($department_accesses))]));
		}
		return ['status' => 'error'];
	}

	public
	function details_ajax($parent_access_id)
	{
//		if (!empty($_POST['department_id'])) {
		$_POST['department_id'] = 15;
		$department = Models\Departments::get_department(['where' => ['id = ' . $_POST['department_id']]]);
		$department_accesses = explode(',', trim($department->accesses, ','));
		Models\Dev::var_dump(Models\Access::get_accesses_tree(['where' => [
			'parent_id = ' . $parent_access_id
		]], $parent_access_id, $this->locale));

		 $this->view('accesses/details_list',
//			echo ($this->view('accesses/details_list',
			[
				'accesses' => Models\Access::get_accesses_tree(['where' => [
					'parent_id = ' . $parent_access_id
				]], $parent_access_id, $this->locale),
				'department_accesses' => $department_accesses
			],
			'popup'
		);
		exit();
//		}
	}
}
