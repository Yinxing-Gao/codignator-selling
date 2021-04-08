<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Plan_fact extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return view('plan_fact',
				[
					'user' => $this->user,
					'access' => $this->access,
				]);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function project($project_id)
	{
		//todo добавити планові приходи
		//todo кожен блок має загальну плашку назви, з якої його можна розкрити чи скрити
		//todo додати витрати на зовнішні сервіси і послуги і, можливо, і на інші статті витрат
		//todo в планованих операціях повернути поодинокі операції, а не загальну суму
		//todo виводити доходи і розходи відповідно до доступів, а маржинальність - тому, кому доступні і приходи і розходи
		//todo змінити роботу галочки "все по плану", щоб вона створювала операції не одразу, а в дату, коли вони мали відбутися
		if (!empty($this->user_id)) {
			$project = Models\Projects::get_project(['where' => ['fp.id = ' . $project_id]]);
			if ($project['status'] == 'ok' && !empty($project['result'])) {
				$project = $project['result'];
				$plan_fact = Models\PlanFact::get_plan_fact(['where' => ['project_id = ' . $project_id]]);
				if (empty($plan_fact)) {
					$plan_fact = Models\PlanFact::add(['project_id' => $project_id]);
				}
				switch ($project->department_id) {
					case 8:
						$storage1_id = 2;
						$storage2_id = 3;
						$responsibles_professions = [7, 17];
						$worker_professions = [8];
						$other_professions = [18];
						break;
					default:
						$storage1_id = 1;
						$storage2_id = 1;
						$responsibles_professions = [9];
						$worker_professions = [10];
						$other_professions = [];
						break;
				}

				$involved_professions = array_merge($responsibles_professions, $worker_professions, $other_professions);
				$operations = Models\Operation::get_project_operations($project_id);

				$transport_operations = [];
				$other_operations = [];
				$transport_total = 0;
				if (!empty($operations) && $operations['status'] == 'ok') {
					$operations = $operations['result'];
					foreach ($operations as $operation) {
						if ($operation['article_id'] == 15) {
							$transport_operations [] = $operation;
//						$transport_total += $operation['amount'];
						}
						if ($operation['article_id'] == 40) {
							$other_operations [] = $operation;
//						$transport_total += $operation['amount'];
						}
					}
				}


				//тут провірити, якщо не збігаються прорахунки то замінити в базі значення
				$costs = [
					'plan' => 1100000,
					'fact' => 100000
				];

//				Models\Dev::var_dump(Models\PlanFact::get_plan_fact(['where' => ['fpf.id =' . $plan_fact->id]]));
				return $this->view('plan_facts/main',
					[
						'user' => $this->user,
						'access' => $this->access,
						'project' => $project,
						'plan_fact' => Models\PlanFact::get_plan_fact(['where' => ['fpf.id =' . $plan_fact->id]]),
						'departments' => Models\Departments::get_departments(),
						'department_id' => $project->department_id,
//						'department_workers' => $department_users,
						'project_params' => Models\PlanFact::get_project_params($plan_fact->id),
//						'products' => Models\PlanFact::get_plan_fact_products($plan_fact->id, "wholesale franchise", $plan_fact->currency),
//						'involved_workers' => Models\PlanFact::get_involved_workers($plan_fact->id),
						'costs' => $costs,
						'transport_operations' => $transport_operations,
						'other_operations' => $other_operations,
						'contracts' => Models\Contracts::get_contracts(),
//						'responsibles' => Models\Position::get_users_by_positions($responsibles_professions),
//						'responsibles' => Models\Position::get_positions(['where' => ['id IN (' . implode(',',$responsibles_professions) . ')'],
						'responsibles_professions' => $responsibles_professions,
						'worker_professions' => $worker_professions,
						'other_professions' => $other_professions,
						'storage1_id' => $storage1_id,
						'storage2_id' => $storage2_id,
						'materials_apps' => Models\Storage::get_storage_app_ids_by_source($project->id, 'plan-fact_plan_materials_table'),
						'materials2_apps' => Models\Storage::get_storage_app_ids_by_source($project->id, 'plan-fact_plan_materials2_table'),
//					'storage1_names' => Models\Storage::get_storage_names($storage1_id),
//					'storage2_names' => Models\Storage::get_storage_names($storage2_id),
						'storage1_names' => Models\Products::get_storage_products($storage1_id, "wholesale franchise", $plan_fact->currency),
						'storage2_names' => Models\Products::get_storage_products($storage2_id, "wholesale franchise", $plan_fact->currency),
//						'workers' => $department_users,
						'css' => ['jquery-ui.min', 'plan_fact'],
						'js' => ['jquery-ui.min', 'plan_fact'],
					]
				);
			}
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function update_products_ajax($plan_fact_id)
	{

		$type = $_POST['type'];//plan,fact
		$products = $_POST['products'];
		$date = $_POST['date'];
		$placement = $_POST['placement'];
		$article_id = $_POST['article_id'];
		$department_id = $_POST['department_id'];

//		echo json_encode(
		Models\PlanFact::update_products($plan_fact_id, $type, $products, $date, $placement, $article_id, $department_id);
//		);
	}


	public
	function update_workers_ajax($plan_fact_id)
	{
		$type = $_POST['type'];//plan,fact
		$workers = $_POST['workers'];
		$date = $_POST['date'];
		$department_id = $_POST['department_id'];
		echo json_encode(Models\PlanFact::update_workers($plan_fact_id, $workers['salary'], $type, $date, 'project_percentage', 38, $department_id));
		echo json_encode(Models\PlanFact::update_workers($plan_fact_id, $workers['travel_payments'], $type, $date, 'travel_payments', 39, $department_id));
	}


	public
	function update_params_ajax($plan_fact_id)
	{
		$name = $_POST['param'];
		$value = $_POST['value'];
		$type = $_POST['type'];//plan,fact
		$date = $_POST['date'];
		$department_id = $_POST['department_id'];
		$article_id = !empty($_POST['article_id']) ? $_POST['article_id'] : 0;
		if (in_array($name, ['start_date', 'end_date'])) {
			$value = strtotime($value);
		}
		echo json_encode(Models\PlanFact:: set_param($plan_fact_id, $name, $value, $type, $article_id, $date, $department_id));
	}

	public static function get_workers_ajax($project_id)
	{
		$department_users = [];
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		$project = Models\Projects::get_project(['where' => ['fp.id = ' . $project_id]]);
		if ($project['status'] == 'ok' && !empty($project['result'])) {
			$project = $project['result'];

			$params = [
				'where' =>
					['fp.department_id = ' . $project->department_id]
			];

//			if (!empty($query)) {
//				$params['where'][] = '(fp.name LIKE "%' . trim($query) . '%" OR fp.comment LIKE "%' . trim($query) . '%")';
//			}

			$department_positions = Models\Position::get_positions($params, [], ['fin_user_to_position']);

			if (!empty($department_positions)) {
				foreach ($department_positions as $department_position) {
					if (!empty($department_position['users'])) {
						$accepted = true;
						if (!empty($query)) {
							$accepted = false;
							foreach ($department_position['users'] as $user) {
								if (strripos($user['name'], $query) !== false || strripos($user['surname'], $query) !== false) {
									$department_users[$department_position['id']]['children'][] = $user;
									$accepted = true;
								}
							}
						}

						if ($accepted) {
							$department_users[$department_position['id']]['id'] = $department_position['id'];
							$department_users[$department_position['id']]['name'] = $department_position['name'];
							$department_users[$department_position['id']]['children'] = $department_position['users'];
						}
					}
				}
			}
		}
		return json_encode(['status' => 'ok', 'workers' => $department_users]);
	}

	public
	function add_operation_ajax($plan_fact_id)
	{
		$plan_fact = Models\PlanFact::get_plan_fact($plan_fact_id);
		if (!empty($plan_fact)) {
			switch ($_POST['type']) {
				case 'transport':
					$contractor_id = 41;
					$article_id = 15;
					break;
				default:
					$contractor_id = 40;
					$article_id = 40;
					break;
			}
			$new_operation = [
				'amount' => $_POST['amount'],
				'currency' => $_POST['currency'],
				'wallet_1_id' => Models\Wallets::get_user_wallet($_POST['worker_id'], $_POST['currency'])->id,
				'user_id' => $_POST['worker_id'],
				'contractor_id' => $contractor_id,
				'contractor1_id' => 0,
				'contractor2_id' => $contractor_id,
				'accrual_type' => 'credit',
				'type_id' => 1, //готівка
				'article_id' => $article_id,
				'project_id' => $plan_fact->project_id,
				'app_id' => 0,
				'comment' => $_POST['comment'],
				'date' => strtotime($_POST['date'])
			];
			echo json_encode([
				'operation' => Models\Operation:: add_expense($new_operation, true),
				'accrual' => Models\Accruals:: add($new_operation)
			]);
		}
	}

	public
	function delete_product_ajax($pfp_id)
	{
		echo json_encode(Models\PlanFact::delete_product($pfp_id));
	}

	public
	function delete_worker_ajax($worker_id)
	{
		echo json_encode(Models\PlanFact::delete_worker($worker_id));
	}
}
