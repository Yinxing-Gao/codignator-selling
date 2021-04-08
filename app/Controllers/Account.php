<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Account extends BaseController
{
	public function index()
	{
		header('Location: ' . base_url() . 'user/login');
		exit;
	}

	public function registration()
	{
		return view('account_registration');
	}
}
