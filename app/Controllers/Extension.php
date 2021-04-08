<?php namespace App\Controllers;


use App\Models\Dev;
use App\Models\Projects;

class Extension extends BaseController
{
	public function Nova_Poshta($method)
	{
		Dev::var_dump($_POST);
		Dev::var_dump(\GuzzleHttp\json_decode(file_get_contents('php://input')));
		$request = \GuzzleHttp\json_decode(file_get_contents('php://input'));

		switch ($method) {
			case 'projects':
				if (!empty($request->projects)) {
					$projects = $request->projects;
					$user_id = $request->fineko_user_id;
					$user = \App\Models\User::get_user(['where' => [
						'id = ' . $user_id
					]]);
					if ($user['status'] == 'ok' && !empty($user['result'])) {
						$user = $user['result'];
						foreach ($projects as $project) {
							Projects::add_update([
								'contact_number' => $project->np_number,
								'contract_amount' => $project->np_cost,
								'contract_currency' => 'UAH',
								'contract_type_id' => 1,

								'name' => $project->np_number,
								'author_id' => $request->fineko_user_id,
								'account_id' => $user->account_id,
								'date' => strtotime($project->np_create_date),
								'end_date' => strtotime($project->np_deliver_date),
								'comment' => $project->np_description . '<br/><br/>' .
									'Відправник: ' . $project->np_sender . '<br/>' .
									'Адреса: ' . $project->np_sender_address . '<br/><br/>' .
									'Отримувач: ' . $project->np_receiver . ' ' . $project->np_receiver_contact . '<br/>' .
							 		'Адреса: ' . $project->np_receiver_address . '<br/><br/>' .
									'Телефон: ' . $project->np_receiver_phone,
								'status' => 'new' //todo
							]);
						}

					}
				}
				return ['status' => 200, 'amount' => count($request->projects)];
				break;
			case
			1:
				echo "i равно 1";
				break;
			case 2:
				echo "i равно 2";
				break;
		}
	}
}

