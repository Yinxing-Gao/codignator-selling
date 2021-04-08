<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Report extends BaseController
{
	public function company_result($month_year = null)
	{
		$month_year = !empty($month_year) ? $month_year : date('m.Y');
		$company_result = Models\PlanFact::get_company_result($month_year);

		if (!empty($this->user_id)) {
			return $this->view('reports/company_result_',
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

	public function cashflow($department_id = null, $with_children = true, $month_year = null)
	{
		$month_year = !empty($month_year) ? $month_year : date('m.Y');
		//отримуємо ід або одного департаменту
		$department_ids = Models\Departments::get_single_department_or_with_children($with_children);

		// вибрати всі гаманці і відповідно всі операції, які по них проходили, які відносяться до цих департаментів
		// операції мають бути тільки доходу і приходу від зовнішніх котрагентів
		// потім те ж саме повторити з планованими операціями
		if (strlen($department_ids) > 0) {
			$wallets = Models\Wallets::get_wallets(['where' => [
				'fw.department_id IN (' . $department_ids . ')'
			]], [], ['fin_operations']);
		} else {
			$wallets = [];
		}
		//треба взяти всі операції даного департаменту, або всіх піддепартаментів, які проходтили за конкретний місяць
//		$operations = Models\Operation::get_operations(['where' => [
//			'fo.account_id = ' . Models\Account::get_current_account_id(),
//			'fo.time_type = "real"',
//			'(fo.operation_type_id = 1 OR fo.operation_type_id = 2)',
//			'fo.date >= ' . Models\DateHelper::get_first_and_last_days_of_month($month_year)['first_day_timestamp'],
//			'fo.date <= ' . Models\DateHelper::get_first_and_last_days_of_month($month_year)['last_day_timestamp'],
//		]]);


		//витягаємо всі статті, які відносяться до даних департаментів
		$articles = Models\Articles::get_articles(['where' => [
			'department_id IN ( ' . $department_ids . ')'
		]]);

		//витягаємо шаблони статтей
		$endpoints = Models\Articles::get_articles_endpoints(['where' => [
			'is_template = 1'
		]], [], true);

		//перевіряємо чи серед статтей вибраних департаментів є ті,
		//які створені на основі шаблонів, і є кінцевими точками в cashflow
		$endpoints_with_articles = [];
		if (!empty($endpoints)) {
			foreach ($endpoints as $endpoint) {
				$article = Models\Articles::get_article(['where' => [
					'template_id = ' . $endpoint['id']
				]]);

				//знаходимо всі дочірні статті цих ендпоінтів
				if (!empty($article)) {
					$endpoints_with_articles[$endpoint['id']]['articles'][] = $article->id;
					Models\Articles::get_articles_children_ids($article->id, $articles, $endpoint['id'], $endpoints_with_articles);
				}
			}
		}

//		Models\Dev::var_dump($endpoints_with_articles);die();


		if (!empty($endpoints_with_articles)) {
			foreach ($endpoints_with_articles as $template_id => &$data) {
				if (empty($data['real'])) {
					$data['real'] = [];
				}

				if (!empty($operations)) {
					foreach ($operations as $operation) {
						if (!empty($operation['article_id'])) {
							if (in_array($operation['article_id'], $data['articles'])) {
								$data['real'][] = Models\Operation::preview_process($operation);
							}
						}
					}
				}
			}
		}

//		Models\Dev::var_dump($endpoints_with_articles);

		// знайти шаблони без дітей ( в шаблонах )
		// для кожної такої статті вивести список ід через кому дочірніх статтей в департаменті
		// погрупувати операції і плановані операції по цих статтях

		if (!empty($this->user_id)) {

			return $this->view('reports/cashflow',
				[
					'month_year' => $month_year,
					'months' => Models\Salary::get_months_from_start(),
					'operations' => $endpoints_with_articles,
					'departments' => Models\Departments::get_departments(),
					'tree' => Models\Articles::get_templates_tree(),
					'css' => ['table', 'operation']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function profit_and_loss($month_year = null)
	{
		$month_year = !empty($month_year) ? $month_year : date('m.Y');

		$departments_ids = [];
		if (!empty($this->user->positions)) {
			foreach ($this->user->positions as $position) {
				if (!empty($position['department_id'])) {
					$departments_ids[] = $position['department_id'];
				}
			}
		}

		$articles = Models\Articles::get_articles(['where' => [
//			'department_id IN ( ' . implode(',', $departments_ids) . ')'
		]]);

		$endpoints = Models\Articles::get_articles_endpoints(['where' => [
			'is_template = 1'
		]], [], true);

		$endpoints_with_articles = [];
		if (!empty($endpoints)) {
			foreach ($endpoints as $endpoint) {
				$article = Models\Articles::get_article(['where' => [
					'template_id = ' . $endpoint['id']
				]]);
				if (!empty($article)) {
					$endpoints_with_articles[$endpoint['id']]['articles'][] = $article->id;
					Models\Articles::get_articles_children_ids($article->id, $articles, $endpoint['id'], $endpoints_with_articles);
				}
			}
		}

		$operations = Models\Operation::get_operations(['where' => [
			'fo.account_id = ' . Models\Account::get_current_account_id(),
			'fo.time_type = "real"',
			'(fo.operation_type_id = 1 OR fo.operation_type_id = 2)',
			'fo.date >= ' . Models\DateHelper::get_first_and_last_days_of_month($month_year)['first_day_timestamp'],
			'fo.date <= ' . Models\DateHelper::get_first_and_last_days_of_month($month_year)['last_day_timestamp'],
		]]);

		if (!empty($endpoints_with_articles)) {
			foreach ($endpoints_with_articles as $template_id => &$data) {
				if (empty($data['real'])) {
					$data['real'] = [];
				}

				if (!empty($operations)) {
					foreach ($operations as $operation) {
						if (!empty($operation['article_id'])) {
							if (in_array($operation['article_id'], $data['articles'])) {
								$data['real'][] = Models\Operation::preview_process($operation);
							}
						}
					}
				}
			}
		}

//		Models\Dev::var_dump($endpoints_with_articles);

		// знайти шаблони без дітей ( в шаблонах )
		// для кожної такої статті вивести список ід через кому дочірніх статтей в департаменті
		// погрупувати операції і плановані операції по цих статтях

		if (!empty($this->user_id)) {

			return $this->view('reports/profit_and_loss',
				[
					'month_year' => $month_year,
					'months' => Models\Salary::get_months_from_start(),
					'operations' => $endpoints_with_articles,
					'departments' => Models\Departments::get_departments(),
					'tree' => Models\Articles::get_templates_tree(),
					'css' => ['table', 'operation']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function balance()
	{
		if (!empty($this->user_id)) {
			return $this->view('reports/balance',
				[
					'wallets' => Models\Wallets::get_wallets(['where' => [
						'fw.account_id = ' . Models\Account::get_current_account_id()
					]]),
					'main_assets_storages' => Models\Storage::get_storages(['where' => ['type = "main assets"']]),
					'storages' => Models\Storage::get_storages(['where' => ['type != "main assets"']]),
					'css' => ['reports'],
					'js' => []
				]);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

}

