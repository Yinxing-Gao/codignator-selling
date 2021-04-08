<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Contract extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return $this->view('contracts/list',
				[
					'contracts' => Models\Contracts::get_contracts([], ['fin_contractors']),
					'contract_types' => [
						'once' => 'Разовий',
						'month' => 'Щомісячний'
					],
					'products' => Models\Products::get_products(),
					'css' => ['contract'],
					'js' => ['contract']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function get_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		echo json_encode(Models\Contracts::search($query));
	}

	public function add_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			$atts['start_date'] = !empty($_POST['start_date']) ? strtotime($_POST['start_date']) : 0;
			$atts['end_date'] = !empty($_POST['end_date']) ? strtotime($_POST['end_date']) : 0;
			$atts['products_id'] = !empty($_POST['products']) ? implode(',', $_POST['products']) : '';

			echo json_encode(Models\Contracts::add_update($atts));
		}
	}

	public function get_products_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		echo json_encode(Models\Products::search($query));
	}

	public function delete_ajax($contract_id)
	{
		echo json_encode(Models\Contracts::delete($contract_id));
	}
}
