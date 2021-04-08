<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Rules extends BaseController
{
	public function index()
	{
		return view('base_rules',
			[
				'user' => $this->user,
				'locale' => $this->locale,
				'access' => $this->access,
			]
		);

	}

	public function page($page)
	{
		if (!empty($this->user_id)) {
			return view('rules/'.$page,
				[
					'user' => $this->user,
					'access' => $this->access,
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

}
