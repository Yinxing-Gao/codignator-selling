<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Position extends BaseController
{
	public function index($department_id = null)
	{
		if (!empty($this->user_id)) {
//			if (empty($department_id)) {
//				if (!empty($this->user->positions[0])) {
//					$department_id = $this->user->positions[0]['department_id'];
//				} else {
//					$department_id = Models\Departments::get_departments(['where' => [
//						'is_default = 1'
//					]])->id;
//				}
//			}

			$positions = Models\Position::get_positions(['where' => [
//				'department_id = ' . $department_id
				'fp.account_id = ' . Models\Account::get_current_account_id()
			]], ['fin_departments'], ['fin_user_to_position']);

			if (!empty($positions)) {
				foreach ($positions as &$position) {
					$user_ids = [];
					if (!empty($position['users'])) {
						foreach ($position['users'] as $user) {
							$user_ids[] = $user['id'];
						}
					}
					$position['user_ids'] = $user_ids;
				}
			}

			return $this->view('positions/list',
				[
					'users' => Models\User::get_users(['where' => [
						'fu.account_id = ' . $this->account_id,
						'fu.status = "active"'
					]]),
					'positions' => $positions,
					'articles' => Models\Articles::get_articles([
						'where' => [
							'fa.account_id = ' . Models\Account::get_current_account_id()
						]
					]),
					'department_id' => $department_id,
					'week_days' => [
						1 => 'Пн',
						2 => 'Вт',
						3 => 'Ср',
						4 => 'Чт',
						5 => 'Птн',
						6 => 'Сб',
						7 => 'Нд',
					],
					'css' => ['table', 'positions'],
					'js' => ['positions']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function org()
	{
		if (!empty($this->user_id)) {
			return $this->view('positions/org',
				[
					'js' => ['libraries/org_chart', 'positions']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function add_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			$atts['work_days'] = !empty($_POST['work_days']) ? implode(',', $_POST['work_days']) : '';
			echo json_encode(Models\Position::add($atts));
		}
	}

	public function change_users_ajax($position_id)
	{
		echo json_encode(Models\Position::change_users($position_id, !empty($_POST['users']) ? $_POST['users'] : []));
	}

	public function delete_ajax($position_id)
	{
		echo json_encode(Models\Position::delete($position_id));
	}

	public function get_org_ajax($api_key)
	{
		$account = Models\Account::get_account(['where' => [
			'api_key = "' . $api_key . '"'
		]]);
		if (!empty($account)) {
			$positions = Models\Position::get_positions(['where' => [
				'account_id = ' . $account->id
			]], [], ['fin_user_to_position']);

			$org_items = [];
			if (!empty($positions)) {
				foreach ($positions as $position) {

//					Models\Dev::var_dump($position);
					for ($i = 0; $i < $position['potential_amount_of_workers']; $i++) {
						$org_item = [
							"id" => $i < 1 ? $position['id'] : $position['id'] . '_' . $i,
							"name" => !empty($position['users'][$i]) ? $position['users'][$i]['name'] . " " . $position['users'][$i]['surname'] : 'в процесі пошуку...',
							"title" => $position['name'],
							"instruction" => 'a href="/instruction/job/99">Посадова інструкції</a>',
							"img" => base_url() . "/icons/fineko/positions.svg"
						];
						if ($position['name'] !== "Власник") {
							$org_item["pid"] = $position['subordination'];
						}
						$org_items[] = $org_item;
					}
					// перевірити скільки є потенційних і скільки треба створити п
				}
			}

			echo json_encode($org_items);
		}
	}
}
