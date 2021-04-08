<?php namespace App\Controllers;

use App\Models;
use App\Models\Account;
use App\Models\DBHelp;
use App\Models\Operation;
use App\Models\Projects;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Marketing extends BaseController
{
	public function index($month_year = null)
	{
		$month_year = !empty($month_year) ? $month_year : date('m.Y');
		$company_result = Models\PlanFact::get_company_result($month_year);

		if (!empty($this->user_id)) {
			return $this->view('reports/company_result',
				[
					'departments' => $company_result['departments'],
					'company_result' => $company_result,
					'month_year' => $month_year,
					'article_tree' => Models\Articles::get_articles_tree(),
					'months' => Models\Salary::get_months_from_start(),
					'tab' => '&nbsp;&nbsp;',
					'css' => ['plan_fact'],
					'js' => ['company_result']
				]);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function operations_ajax($lead_id)
	{
		$lead = Models\Marketing::get_lead(['where' => ['fl.id =' . $lead_id]], ['fin_contractors']);
//		Models\Dev::var_dump($lead);
		if (!empty($lead) && !empty($lead->contractor_id)) {
			$planned_operations = Operation::get_operations([
				'where' => [
					'fo.contractor1_id =' . $lead->contractor_id,
//				'is_planned = 1',
//				'is_template = 0',
					'time_type = "plan"',
					'operation_type_id = 1'
				]
			]);

			echo json_encode(view('operation/lead_operations',
				[
					'title' => 'Інформація про заявку',
					'currencies' => [
						'UAH' => 'грн.',
						'USD' => 'дол.',
						"RUR" => 'руб.',
						"EUR" => 'євро'
					],
					'op_style' => [
						1 => "green",
						2 => "red",
						3 => "blue",
						4 => "orange"
					],
					'locale' => $this->locale,
					'lead' => $lead,
//				'income_list' => !empty($articles_tree) ? $articles_tree['income'][0]['children'] : [],
					'planned_operations' => $planned_operations,
				]
			));
		}
	}

	public function leads()
	{
		if (!empty($this->user_id)) {
			return $this->view('marketing/leads',
				[
					'leads' => Models\Marketing::get_leads(['where' => [
						'fl.account_id = ' . Account::get_current_account_id(), '(status = \'new\' OR status = \'presentation\')'
					]], ['fin_sources']),
					'users' => Models\User::get_users(DBHelp::params_merge(['where' => [
						'account_id = ' . Account::get_current_account_id(),
						'status = "active"'
					]], [])),
					'currencies' => $this->currencies,
					'lead_statuses' => [
						'new' => 'Новий',
						'presentation' => 'Презентація',
						'calculation' => 'Прорахунок',
						'contract' => 'Договір',
						'bad lead' =>'Неякісний або нецільовий лід'
					],
					'lead_qualifications' => ['A', 'B', 'C'],
					'css' => ['marketing', 'table'],
					'js' => ['marketing', 'upload', 'jquery.doubleScroll']
				]);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function clients()
	{
		if (!empty($this->user_id)) {
			return $this->view('marketing/clients',
				[
					'leads' => Models\Marketing::get_leads(['where' => [
						'fl.account_id = ' . Account::get_current_account_id(), '(status = \'calculation\' OR status = \'contract\')'
					]], ['fin_sources']),
					'currencies' => $this->currencies,
					'lead_statuses' => [
						'new' => 'Новий',
						'presentation' => 'Презентація',
						'calculation' => 'Прорахунок',
						'contract' => 'Договір'
					],
					'lead_qualifications' => ['A', 'B', 'C'],
					'css' => ['marketing'],
					'js' => ['marketing', 'upload', 'jquery.doubleScroll']
				]);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function projects()
	{
		if (!empty($this->user_id)) {

			$projects = Models\Projects::get_projects(['where' => [
				'fp.account_id = ' . Models\Account::get_current_account_id(),
				'fp.lead_id != 0',
				'fl.status = "calculation"'

			]], ['fin_leads'], [
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
			return $this->view('marketing/projects',
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

	public function get_sources_ajax($api_key)
	{
		$account = Models\Account::get_account(['where' => [
			'api_key = "' . $api_key . '"'
		]]);
		if (!empty($account)) {
			echo json_encode(Models\Marketing::get_sources(['where' => [
				'account_id = ' . $account->id
			]]));
		}
	}

	public function add_lead_ajax()
	{
		if (!empty($_POST['documents'])) {
			$files = [];
			$files_arr = explode(',', substr($_POST['documents'], 2, -2));
			if (!empty($files_arr)) {
				foreach ($files_arr as $file) {
					$files[] = trim($file, '"');
				}
			}
		}
		echo json_encode(array_merge(['date' => date('d.m.Y H:i')], Models\Marketing::add([
			'name' => !empty($_POST['name']) ? $_POST['name'] : '',
			'product_id' => !empty($_POST['product_id']) ? $_POST['product_id'] : 0,
			'amount' => !empty($_POST['amount']) ? $_POST['amount'] : 0,
			'currency' => !empty($_POST['currency']) ? $_POST['currency'] : 'UAH',
			'source' => !empty($_POST['source']) ? $_POST['source'] : 'default',
			'status' => !empty($_POST['status']) ? $_POST['status'] : 'new',
			'qualification' => $_POST['qualification'],
			'docs' => !empty($_POST['documents']) ? $_POST['documents'] : '',
			'department_id' => !empty($_POST['department_id']) ? $_POST['department_id'] : 0,
			'contacts' => !empty($_POST['contacts']) ? $_POST['contacts'] : [],
		])));
	}

	public function edit_lead_ajax($lead_id)
	{
		$leadModel = Models\Marketing::get_lead(['where' => [
			'fl.id = ' . $lead_id
		]], [], ['fin_lead_answers', 'fin_tasks', 'fin_lead_contacts', 'fin_lead_comments']);

		if (empty($_POST['docs'])) {
			unset($_POST['docs']);
		}

		$lead_attrs = get_object_vars($leadModel);
		$attrs = array_merge($lead_attrs, $_POST);

		$result = Models\Marketing::update($lead_id, $attrs);

		if ($result['status'] == "ok" && isset($_POST['create_project'])) {
			$new_project_result = Models\Projects::add_update([
				'name' => $attrs['name'],
				'is_virtual' => 1,
//				'department_id' => $product['department_id'],
				'author_id' => $this->user_id,
				'lead_id' => $attrs['id']
			]);
			if ($new_project_result['status'] == "ok" && !empty($new_project_result['id'])) {
				$new_plan_fact_result = Models\PlanFact::add([
//					'name' => 'Virtual Plan Fact',
					'project_id' => $new_project_result['id'],
					'training' => 1
				]);
			}
		}

		echo json_encode(array_merge(['date' => date('d.m.Y H:i')], [
			'lead' => $result,
			'virtual_project' => !empty($new_project_result) ? $new_project_result : [],
			'virtual_plan_fact' => !empty($new_plan_fact_result) ? $new_plan_fact_result : []
		]));
	}


	public function add_lead_comment_ajax($lead_id)
	{
		$comment = !empty($_POST['comment']) ? $_POST['comment'] : '';
		echo json_encode(array_merge(['date' => date('d.m.Y H:i')], Models\Marketing::add_comment([
			'lead_id' => $lead_id,
			'comment' => $comment
		])));
	}

	public function add_lead_task_ajax($lead_id)
	{
		echo json_encode(array_merge(['date' => date('d.m.Y H:i')], Models\Tasks::add([
			'lead_id' => $lead_id,
			'task' => $_POST['task'],
			'comment' => !empty($_POST['description']) ? $_POST['description'] : '',
			'date_to' => !empty($_POST['date_to']) ? strtotime($_POST['date_to']) : '',
			'notify' => !empty($_POST['notify']) ? 1 : 0,
			'statistics' => !empty($_POST['statistic']) ? $_POST['statistic'] : '',
		])));
	}

	public function edit_lead_task_ajax()
	{
		if (empty($_POST['id']) || (isset($_POST['account_id']) && ($_POST['account_id'] != Account::get_current_account_id()))) {
			return false;
		}
		echo json_encode(array_merge(['date' => date('d.m.Y H:i')], Models\Tasks::edit($_POST)));
	}

	public function edit_lead_task_status_ajax()
	{
		if (empty($_POST['id'])) {
			return false;
		}
		echo json_encode(array_merge(['date' => date('d.m.Y H:i')], Models\Tasks::edit_status($_POST)));
	}

	public function get_lead_task_ajax($task_id)
	{
		echo json_encode(Models\Tasks::get_tasks(['where' => [
			'id = ' . $task_id
		]])[0]);
	}

	public function lead_info_ajax($lead_id)
	{
		$lead = Models\Marketing::get_lead(['where' => [
			'fl.id = ' . $lead_id
		]], [], ['fin_lead_answers', 'fin_tasks', 'fin_lead_contacts', 'fin_lead_comments']);
		$lead->docs = html_entity_decode($lead->docs);
		if (!empty($lead)) {
			$from_statuses = "";
			if (isset($lead->status)) {
				switch ($lead->status) {
					case 'new':
						$from_statuses = "'new'";
						break;
					case 'presentation':
						$from_statuses = "'new', 'presentation'";
						break;
					case 'calculation':
						$from_statuses = "'new', 'presentation', 'calculation'";
						break;
					case 'contract':
						$from_statuses = "'new', 'presentation', 'calculation', 'contract'";
						break;

				}
			}

			$unused_question_groups = Models\Marketing::get_question_groups(['where' => [
				isset($lead->status) ? 'fqg.from_status IN (' . $from_statuses . ')' : ''
			]]);

			$question_groups = [];
			if (!empty($lead->product_id)) {
				if (!empty($lead->question_groups_ids)) {
					$question_groups = Models\Marketing::get_question_groups(['where' => [
						'id IN (' . $lead->question_groups_ids . ')'
					]]);
				} else {
					$question_groups = [];
				}

				if (!empty($question_groups)) {
					foreach ($question_groups as &$question_group) {
						if (!empty($question_group['questions'])) {
							foreach ($question_group['questions'] as &$question) {
								$question['answer'] = '';
								if (!empty($lead->answers)) {
									foreach ($lead->answers as $answer) {
										if ($answer['question_id'] == $question['id']) {
											$question['answer'] = $answer['answer'];
										}
									}
								}
							}
						}
					}
				}
			}

			if (!empty($unused_question_groups)) {
				foreach ($unused_question_groups as &$unused_question_group) {
					$unused_question_group['used'] = false;
					if (!empty($question_groups)) {
						foreach ($question_groups as &$question_group) {
							if ($unused_question_group['id'] === $question_group['id']) {
								$unused_question_group['used'] = true;
							}
						}
					}
				}
			}

			if (!empty($lead->question_groups_ids)) {
				$lead->question_groups_ids = explode(",", $lead->question_groups_ids);
			}

			$this->view('marketing/lead_info',
				[
					'title' => 'Інформація про заявку',
					'currencies' => $this->currencies,
					'lead' => $lead,
					'contact_types' => [
						'phone' => 'Телефон',
						'telegram' => 'Телеграм',
						'fb' => 'facebook',
						'other' => 'Інший контакт'
					],
					'products' => Models\Products::get_products(),
					'sources' => Models\Marketing::get_sources(),
					'question_groups' => $question_groups,
					'all_question_groups' => $unused_question_groups
				],
				'popup'
			);
		}
	}

	public function get_question_group_ajax($group_id)
	{
		$question_groups = Models\Marketing::get_question_groups(['where' => [
			'id =' . $group_id
		]]);
		if (!empty($question_groups) && !empty($_POST['lead_id'])) {
			$lead = Models\Marketing::get_lead(['where' => [
				'fl.id = ' . $_POST['lead_id']
			]], [], ['fin_lead_answers', 'fin_tasks', 'fin_lead_contacts', 'fin_lead_comments']);
			foreach ($question_groups as &$question_group) {
				if (!empty($question_group['questions'])) {
					foreach ($question_group['questions'] as &$question) {
						$question['answer'] = '';
						if (!empty($lead->answers)) {
							foreach ($lead->answers as $answer) {
								if ($answer['question_id'] == $question['id']) {
									$question['answer'] = $answer['answer'];
								}
							}
						}
					}
				}
			}
		}
		echo json_encode($question_groups[0]);
	}

	public function upload_order_ajax()
	{
		if (isset($_POST['my_file_upload'])) {
			// ВАЖНО! тут должны быть все проверки безопасности передавемых файлов и вывести ошибки если нужно

			$uploaddir = './uploads/lead_documents/' . time();

			// cоздадим папку если её нет
			if (!is_dir($uploaddir)) mkdir($uploaddir, 0777, true);

			$files = $_FILES; // полученные файлы
			$done_files = array();

			// переместим файлы из временной директории в указанную
			foreach ($files as $file) {
				$file_name = $file['name'];

				if (move_uploaded_file($file['tmp_name'], "$uploaddir/$file_name")) {
//					$done_files[] = realpath( "$uploaddir/$file_name" );
					$done_files[] = substr("$uploaddir/$file_name", 1);
				}
			}

			$data = $done_files ? array('files' => $done_files) : array('error' => 'Ошибка загрузки файлов.');

			die(json_encode($data));
		}
	}


}
