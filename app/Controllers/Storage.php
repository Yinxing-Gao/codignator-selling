<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Storage extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			if (empty($department_id)) {
				if (!empty($this->user->positions[0])) {
					$department_id = $this->user->positions[0]['department_id'];
				} else {
					$department_id = Models\Departments::get_departments(['where' => [
						'is_default = 1'
					]])->id;
				}
			}
			return $this->view('storage/list',
				[
					'storages' => Models\Storage::get_storages(['where' => [
						'account_id = ' . Models\Account::get_current_account_id(),
					]]),
					'department_id' => $department_id,
					//todo вибирати тыльки ты департаменти, в яких є модуль склад
					'departments' => Models\Departments::get_departments(),
					'totals' => Models\Storage::get_totals(),
//					'activities' => Models\Storage::get_last_activities(),
					'css' => ['table', 'storage'],
					'js' => ['storage']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function department($department_id)
	{
		if (!empty($this->user_id)) {
			return view('storage',
				[
					'user' => $this->user,
					'access' => $this->access,
					'storages' => Models\Storage::get_storages(['where' => ['department_id = ' . $department_id]]),
					'css' => ['storages'],
					'totals' => Models\Storage::get_totals(),
//					'activities' => Models\Storage::get_last_activities(),
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function storage($storage_id)
	{
		if (!empty($this->user_id)) {

			return $this->view('storage/items',
				[
					'items' => Models\Storage::get_storage_items($storage_id),
					'storage' => Models\Storage::get_storage($storage_id),
					'css' => ['storages'],
					'js' => ['storages'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function add_names($storage_id)
	{
		if (!empty($this->user_id)) {

			return $this->view('storage/name_add',
				[
					'user' => $this->user,
					'access' => $this->access,
					'css' => ['storages'],
					'js' => ['storages'],
					'units' => Models\Production::get_units(),
					'storage' => Models\Storage::get_storage($storage_id),
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function inventory($storage_id)
	{
		if (!empty($this->user_id)) {
			return $this->view('storage/inventory',
				[
					'css' => ['storages'],
					'js' => ['storages'],
					'items' => Models\Storage::get_storage_names($storage_id),
					'units' => Models\Production::get_units(),
					'storage' => Models\Storage::get_storage($storage_id),
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function applications($storage_id)
	{
		if (!empty($this->user_id)) {
			return $this->view('storage/applications',
				[
					'applications' => Models\Storage::get_storage_apps($storage_id),
					'css' => ['storages'],
					'js' => ['storages'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function names($storage_id)
	{
		if (!empty($this->user_id)) {
			return $this->view('storage/names',
				[
					'names' => Models\Storage::get_storage_names($storage_id),
					'units' => Models\Production::get_units(),
					'storage' => Models\Storage::get_storage($storage_id),
					'css' => ['table', 'storages'],
					'js' => ['storages'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function add_name_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			echo json_encode(Models\Storage::add_new_storage_name($atts));
		}
	}

	public function add_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			echo json_encode(Models\Storage::add($atts));
		}
	}

	public function delete_ajax($storage_id)
	{
		echo json_encode(Models\Storage::delete($storage_id));
	}

	public function add_item_ajax($storage_id)
	{
		alert($storage_id);
		if (!empty($_POST)) {//дописати екранування //addcslashes
			$atts = $_POST;
			echo json_encode(Models\Storage::add_item($atts, $storage_id));
		}
	}

	public function delete_name_ajax($item_id)
	{
		echo json_encode(Models\Storage::delete_name($item_id));
	}

	public function get_units_ajax()
	{
		echo json_encode(Models\Production::get_units());
	}

//	public function separate()
//	{
//		echo json_encode(Models\Storage::separate_tables());
//	}


	public function set_products()
	{
		Models\Storage::set_products();
	}


	public function do_inventory_ajax($storage_id)
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			echo json_encode(Models\Storage::do_inventory($atts, $storage_id));
		}
	}

	public function change_storage_names_ajax($name_id)
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			echo json_encode(Models\Storage::change_name($atts, $name_id));
		}
	}

	public function get_names_ajax($storage_id)
	{
		echo json_encode(Models\Storage::get_storage_names($storage_id));
	}

	public function add_app_ajax($storage_id)
	{
		$department_id = $_POST['department_id'];
		$project_id = $_POST['project_id'];
		$author_id = $_POST['author_id'];
		$date_for = $_POST['date_for'];
		$names = $_POST['names'];
		$source = $_POST['source'];
		echo json_encode(Models\Storage::add_app($storage_id, $project_id, $department_id, $author_id, $date_for, $names, $source));
	}

	public function get_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		echo json_encode(Models\Storage::search($query, []));
	}
}
