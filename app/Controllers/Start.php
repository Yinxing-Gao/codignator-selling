<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Start extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return $this->view('start/main',
				[

				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function questionnaire()
	{
		$task = Models\Questionnaire::get_task(
			[
				'where' => [
					'user_id = ' . $this->user_id
				],
				'order_by' => 'priority, after_id'
			]
		);
		if (!empty($task)) {
			$page = '';
			switch ($task->name) {
				case 'what the company does':
					$page = 'what the company does';
					$data = [
						'company_types' => Models\CompanyTypes::get_types(),
						'css' => ['questionnaire']
					];
					break;
				case 'what are departments':
					$page = 'what are departments';
					break;
				case 'what are departments managers':
					$page = 'what are departments managers';
					break;
			}

			echo $this->view('start/questionnaire/' . $page,
				$data, 'popup'
			);
			die();
		}
	}

}
