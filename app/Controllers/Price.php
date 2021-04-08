<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Price extends BaseController
{
	public function index()
	{
		return view('price',
			[
				'user' => $this->user,
				'locale' => $this->locale,
				'access' => $this->access,
			]
		);

	}

	public function department($department_id)
	{
		if (!empty($this->user_id)) {
			return view('price',
				[
					'user' => $this->user,
					'access' => $this->access,
//					'products' => Models\Products::get_products($department_id),
					'products' => Models\Products::get_products(),
					'prices' => Models\Price:: get_prices_array($department_id),
					'price_types' => Models\Price::get_price_types($department_id),
					'css' => ['price'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function edit($department_id)
	{
		if (!empty($this->user_id)) {
			return view('price_edit',
				[
					'user' => $this->user,
					'access' => $this->access,
//					'products' => Models\Products::get_products($department_id),
					'products' => Models\Products::get_products(),
					'prices' => Models\Price:: get_prices_array($department_id),
					'price_types' => Models\Price::get_price_types($department_id),
					'css' => ['price'],
					'js'=>['price']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}


	public function update_price()
	{
		echo json_encode(Models\Price::update_price());
	}

	public function change_price_ajax()
	{

		if (!empty($_POST)) {
			$product_id = $_POST['product_id'];
			$type = $_POST['type'];
			$amount = number_format(str_replace(',', '.', $_POST['amount']), 2, '.', '');
			$currency = $_POST['currency'];
			echo json_encode(Models\Price::change_price($product_id, $type, $amount, $currency));
		}
	}
}
