<?php namespace App\Controllers;

use App\Models;
use App\Models\Account;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Contractor extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return $this->view('contractors/list',
				[
					'contractors' => Models\Contractors::get_contractors(['where' => ['fc.account_id = ' . Account::get_current_account_id()]]),
					'contractor_types' => Models\Contractors::get_types(),
					'css' => ['table', 'contractor'],
					'js' => ['contractor']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function search_ajax($type = null)
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		echo json_encode(Models\Contractors::search($query, $type));
	}

	public function get_one_ajax($id)
	{
		echo json_encode(Models\Contractors::get_contractor(['where' => ['id = ' . $id]]));
	}

	public function get_ajax()
	{
		$ids_array = (array)$_POST['ids'];
		echo json_encode(Models\Contractors::get_contractors(['where' => [
			'id IN (' . implode(',', $ids_array) . ' )',
			'fc.account_id = ' . Account::get_current_account_id()]]));
	}

//	public function get_users_ajax()
//	{
//		$query = !empty($_POST['term']) ? $_POST['term'] : '' ;
//		echo json_encode(Models\Contractors::search($query));
//	}

	public function add_options()
	{
		echo json_encode(Models\Contractors::add_options());
	}


	public function add_ajax()
	{
		if (!empty($_POST)) {
			echo json_encode(Models\Contractors::add($_POST));
		}
	}

	public function update_ajax()
	{
		if (!empty($_POST)) {
			echo json_encode(Models\Contractors::update($_POST['id'], $_POST));
		}
	}

	public function unite_ajax()
	{
		if (!empty($_POST)) {
			$ids = $_POST['id'];
			unset($_POST['id']);
			echo json_encode(Models\Contractors::unite($_POST, explode(',', $ids)));
		}
	}

	public function delete_ajax($id)
	{
		echo json_encode(Models\Contractors::delete($id));
	}
}
