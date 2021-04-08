<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return $this->view('home/charts',
				[
					'js' => ['g-charts', 'home']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}


}
