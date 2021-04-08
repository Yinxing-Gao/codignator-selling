<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Options extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return $this->view('options/options',
				[

				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}
}
