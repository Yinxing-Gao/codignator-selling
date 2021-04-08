<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Project extends BaseController
{// додати можливість об"єднувати проекти

	protected $types = [
		'1' => 'готівка',
		'2' => 'на ТОВ',
		"3" => 'на ФОП',
		"4" => 'ТОВ/готівка/невідомо'
	];

	public function index()
	{
		if (!empty($this->user_id)) {

			$projects = Models\Projects::get_projects(['where' => [
				'fp.account_id = ' . Models\Account::get_current_account_id()
			]], [], [
				'fin_products',
				'fin_observers',
				'fin_storage'
			]);
			$observed_projects = [];
			if (!empty($projects)) {
				foreach ($projects as $project) {
					if (!empty($project['observers'])) {
						foreach ($project['observers'] as $observer) {
							if ($observer['id'] === $this->user_id) {
								$observed_projects[] = $project;
							}
						}
					}
				}
			}
			return $this->view('projects/list',
				[
					'projects' => Models\Projects::get_projects(['where' => [
						'fp.author_id = ' . $this->user_id
					]], [], [
						'fin_products',
						'fin_observers',
						'fin_storage'
					]),
					'observed_projects' => $observed_projects,
					'statuses' => [
						'new' => 'Новий',
						'in progress' => 'В процесі',
						'finished' => 'Завершений'
					],
					'css' => ['table', 'project'],
					'js' => ['project']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function department($department_id)
	{
		if (!empty($this->user_id)) {
			$projects = Models\Projects::get_projects($department_id);
			if (!empty($projects)) {
				foreach ($projects as &$project) {
					if ($project['products_id'] != 0) {
						$project['products'] = Models\Products::get_projects_products($project['products_id']);
					}
				}
			}

			return $this->view('projects/list',
				[
					'projects' => $projects,
					'css' => ['project'],
					'js' => ['project']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}
//
//	public function add($department_id = null)
//	{
//		if (!empty($this->user_id)) {
//			return view('project_add',
//				[
//					'user' => $this->user,
//					'access' => $this->access,
//					'department_id' => $department_id,
//					'departments' => Models\Departments::get_departments(),
//					'products' => Models\Products::get_products(),
//					'contracts' => Models\Contracts::get_contracts(),
//					'types' => $this->types,
//					'css' => ['select2.min', 'project'],
//					'js' => ['select2.min', 'project']
//				]
//			);
//		} else {
//			header('Location: ' . base_url() . 'user/login');
//			exit;
//		}
//	}

	public function combine($ids)
	{
		if (!empty($this->user_id)) {
			if (!empty($ids)) {
				$projects = Models\Projects::get_projects_by_ids($ids);
				$combined_name = '';
				$combined_comment = '';
				$combined_products = [];
				foreach ($projects as $project) {
					if ($project['department_id'] != 0) {
						$combined_department = $project['department_id'];
					}

					$combined_start_date = $project['date'];
					$combined_end_date = $project['end_date'];
					$combined_name .= $project['name'] . ' ';
					$combined_comment .= $project['comment'] . ' ';
					$combined_products[] = $project['products_id'];
				}
				return view('project_combine',
					[
						'combined' => [
							'combined_department' => $combined_department,
							'combined_start_date' => $combined_start_date,
							'combined_end_date' => $combined_end_date,
							'combined_name' => $combined_name,
							'combined_comment' => $combined_comment,
							'combined_products' => $combined_products
						],
						'user' => $this->user,
						'access' => $this->access,
						'departments' => Models\Departments::get_departments(),
						'products' => Models\Production::get_production_products(1),
						'contracts' => Models\Contracts::get_contracts(),
						'types' => $this->types,
						'js' => ['project']
					]
				);
			} else {
				header('Location: ' . base_url() . 'project');
				exit;
			}
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

//	public function production_add()
//	{
//		if (!empty($this->user_id)) {
//			return view('production_project_add',
//				[
//					'user' => $this->user,
//					'access' => $this->access,
//					'departments' => Models\Departments::get_deppartments(),
//					'product' => Models\Production::get_production_products(),
//					'types' => $this->types,
//				]
//			);
//		} else {
//			return redirect()->to('/user/login');
//		}
//	}

	public
	function add_edit_ajax()
	{
		if (!empty($_POST)) {//дописати екранування //addcslashes
			$atts = $_POST;
			echo json_encode(Models\Projects::add_update($atts));
		}
	}

	public function get_ajax($project_id)
	{
		$project = Models\Projects::get_project(['where' => [
			'fp.id = ' . $project_id
		]], [], [
			'fin_products',
			'fin_observers',
			'fin_storage'
		]);
		$project['result']->human_date = !empty($project['result']->date) ? date('Y-m-d', $project['result']->date) : '';
		$project['result']->human_end_date = !empty($project['result']->end_date) ? date('Y-m-d', $project['result']->end_date) : '';
//		$project['result']->products = !empty($project['result']->products_id) ? explode(',', $project['result']->products_id) : [];
//		$project['result']->storages = !empty($project['result']->storages_ids) ? explode(',', $project['result']->storages_ids) : [];
//		$project['result']->observers = !empty($project['result']->observers_ids) ? explode(',', $project['result']->observers_ids) : [];
		echo json_encode($project);
	}

	public function search_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : '';
		echo json_encode(Models\Projects::search($query));
	}

	public function delete_ajax($project_id)
	{
		echo json_encode(Models\Projects::delete($project_id));
	}
}
