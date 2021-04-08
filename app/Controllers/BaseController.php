<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use App\Models\Account;
use CodeIgniter\Controller;
use App\Models;
use Config;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];
	protected $user_id = null;
	protected $account_id = null;
	protected $account = null;
	protected $user = null;
	protected $access = [];
	protected $locale = 'ru';
	protected $notifications = [];
	protected $currencies = [
		'UAH' => 'грн.',
		'USD' => 'дол.',
		"RUR" => 'руб.',
		"EUR" => 'євро'
	];
	protected $menu;

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? true : false;
		if (!$is_https) {
			$location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $location);
			exit;
		}
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:

		$session = Config\Services::session();
		$this->user_id = $session->get('user_id');
		if (!empty($this->user_id)) {
			$user_result = Models\User::get_user(['where' => [
				'fu.id = ' . $this->user_id
			]], ['fin_contractors'], ['fin_user_to_position']);
			if ($user_result['status'] == 'ok' && !empty($user_result['result'])) {
				$this->access = Models\Access::get_access($this->user_id);
				$this->user = $user_result['result'];
				$this->locale = $this->user->language;
				$this->notifications = Models\Notifications::get_user_notifications($this->user_id);

				$this->account_id = $session->get('account_id');
				if (!empty($this->account_id)) {
					$this->account = Models\Account::get_account(['where' => ['id =' . $this->account_id]]);
				}
			} else {
				$session->destroy();
				header('Location: ' . base_url() . 'user/login');
				exit;
			}
		}
	}

	public function view($page, $data, $type = 'base')
	{
		if (!is_file(APPPATH . '/Views/' . $page . '.php')) {
			// Whoops, we don't have a page for that!
			throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
		}
		switch ($type) {
			case 'base':

				$head_data = [];
				if (!empty($data['css'])) {
					$head_data['css'] = $data['css'];
					unset($data['css']);
				}
				$header_data = [];

				if (!empty($this->user_id)) {
					$header_data['account_id'] = $this->account_id;
					$header_data['account'] = $this->account;
					$header_data['user_id'] = $this->user_id;
					$header_data['access'] = $this->access;
					$header_data['user'] = $this->user;
					$header_data['locale'] = $this->locale;
					$header_data['notifications'] = $this->notifications;

//					$header_data['contractors'] = Models\Contractors::get_contractors();
					$header_data['sidenav_clients'] = Models\Contractors::get_contractors(['where' => [
						'contractor_type = "client"',
						'fc.account_id = ' . Account::get_current_account_id()
					]]);
					$header_data['sidenav_contractors'] = Models\Contractors::get_contractors(['where' => [
						'contractor_type = "provider"',
						'fc.account_id = ' . Account::get_current_account_id()
					]]);
                    $header_data['sidenav_tasks'] = Models\Tasks::get_user_daily_tasks((int)$this->user_id);

					$user_wallets = Models\Wallets::get_wallets(['where' => ['user_id = ' . $this->user_id]]);
					$header_data['sidenav_user_wallets'] = Models\Wallets::get_wallets(['where' => ['user_id = ' . $this->user_id]]);
//					$header_data['user_applications'] = Models\Applications::get_user_apps($this->user_id);
					if (Models\Position::control_finance($this->user_id)) {
						$company_wallets = Models\Wallets::get_wallets(['where' => ['type_id = ' . 2]]);
						$header_data['sidenav_user_wallets'] = Models\Position::finance_manager($this->user_id) ? Models\Wallets::get_wallets() : array_merge($user_wallets, $company_wallets);
					} else {
						$header_data['sidenav_user_wallets'] = $user_wallets;
					}

//					$user_departments_ids = Models\User::get_user_departments_ids($this->user_id);

//					$header_data['sidenav_articles'] = [
//						'income' => !empty($user_departments_ids) ? Models\Articles::get_articles(['where' => [
//							'fa.department_id IN (' . implode(',', $user_departments_ids) . ')',
//							'fa.is_shown = 1',
//							'fa.type = "income"'
//						]]) : [],
//						'expense' => !empty($user_departments_ids) ? Models\Articles::get_articles(['where' => [
//							'fa.department_id IN (' . implode(',', $user_departments_ids) . ')',
//							'fa.is_shown = 1',
//							'fa.type = "expense"'
//						]]) : []
//					];

					$header_data['currencies'] = $this->currencies;

					$header_data['departments'] = Models\Departments::get_departments();
					$header_data['types'] = [
						'1' => 'готівка',
						'2' => 'на ТОВ',
						"3" => 'на ФОП',
						"4" => 'ТОВ/готівка/невідомо'
					];

					$header_data['statuses'] = [ // вставити тут переклад
						'new' => 'Новий',
						'in progress' => 'В процесі',
						'finished' => 'Завершений'
					];

					$header_data['products'] = Models\Products::get_products();
					$header_data['contracts'] = Models\Contracts::get_contracts();
					$header_data['projects'] = Models\Projects::get_projects(['where' => ['fp.account_id = ' . Models\Account::get_current_account_id()]]);

					$header_data['menu'] = Models\Menu::get($this->locale);
				}

				$footer_data = [];
				if (!empty($data['js'])) {
					$footer_data['js'] = $data['js'];
					unset($data['js']);
				}

				echo view('templates/head', $head_data);
				echo view('templates/header', $header_data);
				echo view($page, $data);
				echo view('templates/popup');
				echo view('templates/footer', $footer_data);
				break;
			case 'popup':
//				echo json_encode(view('templates/head', $data));
//				echo view('templates/head', $data);
				echo json_encode(view($page, $data));
//				echo json_encode(view('templates/foot', $data));
				break;
			case 'out':
				echo view('templates/head', $data);
				echo "<div class='container'>";
				echo "<br/>";
				echo view($page, $data);
				echo "</div>";
				echo view('templates/foot', $data);
				break;
		}
	}

}
