<?php namespace App\Controllers;

use App\Models;
use CodeIgniter\Language\Language;
use CodeIgniter\Model;
use Config;
use CodeIgniter\HTTP\RedirectResponse;
use http\Exception;

//оплачені заявки відображати
// формувати документи по оплатах

class Application extends BaseController
{
	protected $types = [
		'1' => 'готівка',
		'2' => 'на ТОВ',
		"3" => 'на ФОП',
		"4" => 'ТОВ/готівка/невідомо'
	];

	public function index($status = 'all')//мої заявки
	{//todo в заявках додати вибір - оплатити чи передати мені ( автору заявки)
		if (!empty($this->user_id)) {
			switch ($status) {
				case 'approved':
					$applications = Models\Applications::get_apps(['where' => [
						'fa.author_id = ' . $this->user_id,
						'status_id = 3'
					]], ['custom_payed_amount']);
					$approved = true;
					$payed = false;
					break;
				case 'payed':
					$applications = Models\Applications::get_apps(['where' => [
						'fa.author_id = ' . $this->user_id,
						'status_id = 5'
					]], ['custom_payed_amount']);
					$approved = true;
					$payed = true;
					break;
				default:
					$applications = Models\Applications::get_apps(['where' => [
						'fa.author_id = ' . $this->user_id
					]], ['custom_payed_amount']);
					$approved = false;
					$payed = false;
					break;
			}


			if (!empty($applications)) {
				foreach ($applications as &$app) {
					if ($app['currency'] != 'UAH') {
						$currency_rate = Models\CurrencyRate::get_exchange_rates($app['currency']);
						$app['total'] = $app['amount'] * $currency_rate['buy'];
					} else {
						$app['total'] = $app['amount'];
					}
				}
			}

			return $this->view('application/list',
				[
					'applications' => $applications,
					'currencies' => [
						'UAH' => 'грн.',
						'USD' => 'дол.',
						"RUR" => 'руб.',
						"EUR" => 'євро'
					],
					'types' => $this->types,
					'url' => '/application/index/' . $status . '/',
					'page_options' => [
						'can_edit_apps' => true,
						'payed' => $payed,
						'approved' => $approved
					],
					'css' => ['table', 'application'],
					'js' => ['application']
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function user($user_id, $status = 'all')
	{
		if (!empty($this->user_id)) {

			$order_list = Models\Applications::get_user_apps($user_id);
			if (!empty($order_list)) {
				foreach ($order_list as &$app) {
					if ($app['currency'] != 'UAH') {
						$currency_rate = Models\CurrencyRate::get_exchange_rates($app['currency']);
						$app['total'] = $app['amount'] * $currency_rate['buy'];
					} else {
						$app['total'] = $app['amount'];
					}
				}
			}

			return view('application_list',
				[
					'order_list' => $order_list,
					'currencies' => [
						'UAH' => 'грн.',
						'USD' => 'дол.',
						"RUR" => 'руб.',
						"EUR" => 'євро'
					],
					'user' => $this->user,
					'access' => $this->access,
					'types' => $this->types,
					'locale' => $this->locale,
					'notifications' => $this->notifications,
					'url' => '/application/user/' . $status . '/',
					'page_options' => [
						'can_edit_apps' => true,
						'payed' => false,
						'approved' => false
					],
					'js' => ['application'],
					'css' => ['application']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function department($status = 'all', $date_from = null, $date_to = null)
	{
		$date_from = !empty($date_from) ? $date_from : date("Y-m-d", strtotime('Last Tuesday'));
		$date_to = !empty($date_to) ? $date_to : date("Y-m-d", strtotime('Next Tuesday'));
		if (!empty($this->user_id)) {
			switch ($status) {
				case 'approved':
					$order_list = Models\Applications::get_department_apps($this->user_id, false, 'status_id = 3', strtotime($date_from), strtotime($date_to));
					$approved = true;
					$payed = false;
					break;
				case 'payed':
					$order_list = Models\Applications::get_department_apps($this->user_id, false, 'status_id = 5', strtotime($date_from), strtotime($date_to));
					$approved = true;
					$payed = true;
					break;
				default:
					$order_list = Models\Applications::get_department_apps($this->user_id, false, 'status_id != 5', strtotime($date_from), strtotime($date_to));
					$approved = false;
					$payed = false;
					break;
			}
//			var_dump($order_list);die();

//			echo "<pre>";
//			var_dump($order_list);
//			echo "</pre>";die();
			if (!empty($order_list)) {
				foreach ($order_list as &$app) {
					if ($app['currency'] != 'UAH') {
						$currency_rate = Models\CurrencyRate::get_exchange_rates($app['currency']);
						$app['total'] = $app['amount'] * $currency_rate['buy'];
					} else {
						$app['total'] = $app['amount'];
					}
				}
			}
			$currencies = [
				'UAH' => 'грн.',
				'USD' => 'дол.',
				"RUR" => 'руб.',
				"EUR" => 'євро'
			];

			return view('application_list',
				[
					'order_list' => $order_list,
					'currencies' => $currencies,
					'user' => $this->user,
					'access' => $this->access,
					'types' => $this->types,
					'date_filter' => true,
					'date_from' => $date_from,
					'date_to' => $date_to,
					'applications_for_search' => Models\Applications::get_apps(1),
					'url' => '/application/department/' . $status . '/',
					'locale' => $this->locale,
					'notifications' => $this->notifications,
					'page_options' => [
						'can_edit_apps' => false,
						'payed' => $payed,
						'approved' => $approved,
						'search' => true
					],
					'js' => ['select2.min', 'application'],
					'css' => ['select2.min', 'application']
//					'can_pay' => (in_array(12, Models\Position::get_positions($this->user_id)) || in_array(2, Models\Position::get_positions($this->user_id))) ? true : false
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function department_($status = 'all', $date_from = null, $date_to = null)
	{
		$date_from = !empty($date_from) ? $date_from : date("Y-m-d", strtotime('Last Tuesday'));
		$date_to = !empty($date_to) ? $date_to : date("Y-m-d", strtotime('Next Tuesday'));
		if (!empty($this->user_id)) {
			switch ($status) {
				case 'approved':
					$order_list = Models\Applications::get_department_apps($this->user_id, false, 'status_id = 3', strtotime($date_from), strtotime($date_to));
					$approved = true;
					$payed = false;
					break;
				case 'payed':
					$order_list = Models\Applications::get_department_apps($this->user_id, false, 'status_id = 5', strtotime($date_from), strtotime($date_to));
					$approved = true;
					$payed = true;
					break;
				default:
					$order_list = Models\Applications::get_department_apps($this->user_id, false, 'status_id != 5', strtotime($date_from), strtotime($date_to));
					$approved = false;
					$payed = false;
					break;
			}
//			var_dump($order_list);die();

//			echo "<pre>";
//			var_dump($order_list);
//			echo "</pre>";die();
			if (!empty($order_list)) {
				foreach ($order_list as &$app) {
					if ($app['currency'] != 'UAH') {
						$currency_rate = Models\CurrencyRate::get_exchange_rates($app['currency']);
						$app['total'] = $app['amount'] * $currency_rate['buy'];
					} else {
						$app['total'] = $app['amount'];
					}
				}
			}
			$currencies = [
				'UAH' => 'грн.',
				'USD' => 'дол.',
				"RUR" => 'руб.',
				"EUR" => 'євро'
			];

			return view('table',
				[
					'order_list' => $order_list,
					'currencies' => $currencies,
					'user' => $this->user,
					'access' => $this->access,
					'types' => $this->types,
					'date_filter' => true,
					'date_from' => $date_from,
					'date_to' => $date_to,
					'applications_for_search' => Models\Applications::get_apps(1),
					'url' => '/application/department/' . $status . '/',
					'locale' => $this->locale,
					'notifications' => $this->notifications,
					'page_options' => [
						'can_edit_apps' => false,
						'payed' => $payed,
						'approved' => $approved,
						'search' => true
					],
					'js' => ['select2.min', 'application'],
					'css' => ['select2.min', 'application']
//					'can_pay' => (in_array(12, Models\Position::get_positions($this->user_id)) || in_array(2, Models\Position::get_positions($this->user_id))) ? true : false
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function add()
	{
		if (!empty($this->user_id)) {

			$authorities_positions = [];

			if (!empty($this->user->positions)) {
				foreach ($this->user->positions as $position) {
					$authorities_positions = array_merge($authorities_positions, Models\Position::get_parents($position['position_id']));
				}
			}


			$authorities_grouped_by_departments = [];
			if (!empty($authorities_positions)) {
				$authorities = [];
				foreach ($authorities_positions as $authorities_position) {
					if (!empty($authorities_position['users'])) {
						foreach ($authorities_position['users'] as $user) {
							$authorities[] = [
								'department_id' => $authorities_position['department_id'],
								'department_name' => $authorities_position['department_name'],
								'position_id' => $authorities_position['id'],
								'position_name' => $authorities_position['name'],
								'user_id' => $user['id'],
								'user_name' => $user['name'],
								'user_surname' => $user['surname'],
							];
						}
					}
				}

				if (!empty($authorities)) {
					foreach ($authorities as $authority) {
						$authorities_grouped_by_departments[$authority['department_id']]['children'][] = $authority;
					}

					if (!empty($authorities_grouped_by_departments)) {
						foreach ($authorities_grouped_by_departments as $department_id => &$d_authorities) {
							$d_authorities['children'] =
								Models\ArrayHelper::unique_multidim_array($d_authorities['children'], 'position_id');
							$d_authorities['id'] = $department_id;
							$d_authorities['name'] = $d_authorities['children'][0]['department_name'];
						}
					}
				}
			}
			return $this->view('application/add',
				[
					'title' => 'Заявка на виділення коштів',
					'departments' => Models\Departments::get_departments(),
					'expenses' => Models\Articles::get_articles(['where' => ['fa.type = 2']]), // можна виводити тільки статті по певній спеціальності
					'types' => $this->types,
					'projects' => Models\Projects::get_projects(['where' => ['fp.account_id = ' . Models\Account::get_current_account_id()]]),
					'authorities' => $authorities_grouped_by_departments,
					'js' => ['upload', 'application'],
					'css' => ['application']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function edit($app_id)
	{
		if (!empty($this->user_id)) {

			return view('application_edit',
				[
					'edit' => true,
					'edited_app' => Models\Applications::get_app($app_id),
					'title' => 'Заявка на виділення коштів',
					'user' => $this->user,
					'access' => $this->access,
					'locale' => $this->locale,
					'notifications' => $this->notifications,
					'departments' => Models\Departments::get_departments(), //дописати захисти,
					'expenses' => Models\Articles::get_articles(), //дописати захисти,
					'types' => $this->types,
					'projects' => Models\Projects::get_projects(['where' => ['fp.account_id = ' . Models\Account::get_current_account_id()]]),
					'js' => ['upload', 'application']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function rules()
	{
		if (!empty($this->user_id)) {
			return view('application_rules',
				[
					'title' => 'Заявка на виділення коштів',
					'user' => $this->user,
					'access' => $this->access,
					'locale' => $this->locale,
					'departments' => Models\Departments::get_departments(), //дописати захисти,
					'expenses' => Models\Articles::get_articles(), //дописати захисти,
					'types' => $this->types
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

//	public
//	function department_approved()
//	{
//		if (!empty($this->user_id)) {
//			$order_list = Models\Applications::get_department_apps($this->user_id, 'status_id = 3');
//			if (!empty($order_list)) {
//				foreach ($order_list as &$app) {
//					if ($app['currency'] != 'UAH') {
//						$currency_rate = Models\CurrencyRate::get_exchange_rates($app['currency']);
//						$app['total'] = $app['amount'] * $currency_rate['buy'];
//					} else {
//						$app['total'] = $app['amount'];
//					}
//				}
//			}
//			$currencies = [
//				'UAH' => 'грн.',
//				'USD' => 'дол.',
//				"RUR" => 'руб.',
//				"EUR" => 'євро'
//			];
//			return view('application_list',
//				[
//					'order_list' => $order_list,
//					'currencies' => $currencies,
//					'user' => $this->user,
//					'access' => $this->access,
//					'types' => $this->types,
//					'show_approve_btn' => false,
//					'can_edit_apps' => false,
//					'can_pay' => (in_array(12, Models\Position::get_positions($this->user_id)) || in_array(2, Models\Position::get_positions($this->user_id))) ? true : false
//				]
//			);
//
//		} else {
//			header('Location: ' . base_url() . 'user/login');
//			exit;
//		}
//	}
//
//	public
//	function department_payed()
//	{
//		if (!empty($this->user_id)) {
//			$order_list = Models\Applications::get_department_apps($this->user_id, 'status_id = 5');
//			if (!empty($order_list)) {
//				foreach ($order_list as &$app) {
//					if ($app['currency'] != 'UAH') {
//						$currency_rate = Models\CurrencyRate::get_exchange_rates($app['currency']);
//						$app['total'] = $app['amount'] * $currency_rate['buy'];
//					} else {
//						$app['total'] = $app['amount'];
//					}
//				}
//			}
//			$currencies = [
//				'UAH' => 'грн.',
//				'USD' => 'дол.',
//				"RUR" => 'руб.',
//				"EUR" => 'євро'
//			];
//			return view('application_list',
//				[
//					'order_list' => $order_list,
//					'currencies' => $currencies,
//					'user' => $this->user,
//					'access' => $this->access,
//					'types' => $this->types,
//					'show_approve_btn' => false,
//					'can_edit_apps' => false,
//					'can_pay' => (in_array(12, Models\Position::get_positions($this->user_id)) || in_array(2, Models\Position::get_positions($this->user_id))) ? true : false
//				]
//			);
//
//		} else {
//			header('Location: ' . base_url() . 'user/login');
//			exit;
//		}
//	}

	public
	function approved_tov()
	{
		if (!empty($this->user_id)) {
			$order_list = Models\Applications::get_approved_transfered_apps();
			if (!empty($order_list)) {
				foreach ($order_list as &$app) {
					if ($app['currency'] != 'UAH') {
						$currency_rate = Models\CurrencyRate::get_exchange_rates($app['currency']);
						$app['total'] = $app['amount'] * $currency_rate['buy'];
					} else {
						$app['total'] = $app['amount'];
					}
				}
			}
			$currencies = [
				'UAH' => 'грн.',
				'USD' => 'дол.',
				"RUR" => 'руб.',
				"EUR" => 'євро'
			];
			return $this->view('application/TOV_to_pay',
				[
					'order_list' => $order_list,
					'currencies' => $currencies,
					'types' => $this->types,
					'can_pay' => (in_array(12, Models\Position::get_positions($this->user_id)) || in_array(2, Models\Position::get_positions($this->user_id))) ? true : false,
					'js' => ['application'],
					'css' => ['application'],
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function charts()
	{
		if (!empty($this->user_id)) {
			return $this->view('application/charts',
				[
					'title' => 'Графіки',
					'chart_data_month' => json_encode(Models\Applications::get_apps_dates_and_amounts(time(), time() + 60 * 60 * 24 * 30)),
					'chart_data_3_month' => json_encode(Models\Applications::get_apps_dates_and_amounts(time(), time() + 60 * 60 * 24 * 30 * 3)),
					'js' => ['g-charts', 'app_charts'],
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function add_ajax()
	{
		if (!empty($_POST)) {//дописати екранування //addcslashes
			$atts = $_POST;
			echo json_encode(Models\Applications::add($atts));
		}
	}

	public
	function edit_ajax()
	{
		if (!empty($_POST)) {//дописати екранування //addcslashes
			$atts = $_POST;
			$app_id = $atts['app_id'];
			echo json_encode(Models\Applications::edit_app($app_id, $atts));
		}
	}

	public
	function info_ajax($application_id)
	{
		echo json_encode(view('app_info',
			[
				'title' => 'Інформація про заявку',
				'types' => $this->types,
				'op_style' => [
					1 => "green",
					2 => "red",
					3 => "blue",
					4 => "orange"
				],
				'currencies' => [
					'UAH' => 'грн.',
					'USD' => 'дол.',
					"RUR" => 'руб.',
					"EUR" => 'євро'
				],
				'locale' => $this->locale,
				'contractors' => Models\Contractors::get_contractors_array(),
				'application' => Models\Applications::get_app($application_id),
				'operations' => Models\Operation::get_application_operations($application_id),
			]
		));
	}

	public
	function change_category_ajax($id)
	{
		if (!empty($_POST)) {//дописати екранування //addcslashes
			$atts = $_POST;
			echo json_encode(Models\Applications::change_category($id, $atts['category']));
		}
	}

	public function approve_ajax()
	{
		if (!empty($_POST)) {
			try {
				$atts = $_POST;
				$id_arr = json_decode($atts['id_arr']);
				$user_id = $atts['user_id'];
				echo json_encode(Models\Applications::change_status($id_arr, '3', $user_id));

			} catch (Exception $e) {
				Models\Telegram::send_admin_message('Помилка в Controllers/Application/approve_ajax.' . " \r\n" . 'e - ' . $e);
				return ['status' => 'error', 'message' => 'Помилка відправлена адміністратору і буде виправлена найближчим часом'];
			}
		}
	}

	public
	function transfer_to_pay_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			$id_arr = json_decode($atts['id_arr']);
			$user_id = $atts['user_id'];
//			Models\Applications::send_application_mail($id_arr);
			echo json_encode(Models\Applications::transfer_to_pay($id_arr, $user_id));
		}
	}

	public
	function pay_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			$id_arr = json_decode($atts['id_arr']);
			$user_id = $atts['user_id'];
//			Models\Applications::send_application_mail($id_arr);
			echo json_encode(Models\Applications::pay($id_arr, $user_id));
//			echo json_encode(Models\Applications::change_status( $id_arr, '4'));
		}
	}

	public
	function check_as_payed_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			$id_arr = json_decode($atts['id_arr']);
			$user_id = $atts['user_id'];
			echo json_encode(Models\Applications::check_as_payed($id_arr, $user_id));
		}
	}

	public
	function reject_ajax()
	{
		if (!empty($_POST)) {
			$atts = $_POST;
			$id_arr = json_decode($atts['id_arr']);
			$user_id = $atts['user_id'];
			echo json_encode(Models\Applications::reject($id_arr, $user_id));
		}
	}

	public
	function delete_app_ajax($app_id)
	{
		echo json_encode(Models\Applications::delete_app($app_id));
	}

	public
	function delete_uploaded_file_ajax($file)
	{
		if (!empty($_POST['file'])) {
			$file = $_POST['file'];
		}
		echo json_encode(Models\Applications::delete_uploaded_file($file));
	}

	public
	function save_comment_ajax($app_id)
	{
		$comment = !empty($_POST['comment']) ? $_POST['comment'] : '';
		echo json_encode(Models\Applications::save_director_comment($app_id, $comment));
	}


	public
	function upload_order_ajax()
	{
		if (isset($_POST['my_file_upload'])) {
			// ВАЖНО! тут должны быть все проверки безопасности передавемых файлов и вывести ошибки если нужно

			$uploaddir = './uploads/orders/' . time();

			// cоздадим папку если её нет
			if (!is_dir($uploaddir)) mkdir($uploaddir, 0777);

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

	public function get_user_apps_ajax($user_id)
	{
		if (!empty($_POST['term'])) {
			$query = $_POST['term'];
			echo json_encode(Models\Applications::search($query, ['where' => ['author_id = ' . $user_id]]));
		} else {
			echo json_encode(Models\Applications::get_apps_short(['where' => ['author_id = ' . $user_id]]));
		}
	}

	public
	function search_ajax()
	{
		$query = !empty($_POST['term']) ? $_POST['term'] : null;

		echo json_encode(Models\Applications::search_all($query));
	}

	public
	function search_by_id_ajax()
	{
		$app_id = !empty($_POST['term']) ? $_POST['term'] : null;
		echo json_encode(Models\Applications::search_all(null, $app_id));
	}

	public
	function display_search_results_ajax()
	{
		$ids = !empty($_POST['ids']) ? $_POST['ids'] : 0;
		$order_list = Models\Applications::get_apps_by_ids($ids);
		if (!empty($order_list)) {
			foreach ($order_list as &$app) {
				if ($app['currency'] != 'UAH') {
					$currency_rate = Models\CurrencyRate::get_exchange_rates($app['currency']);
					$app['total'] = $app['amount'] * $currency_rate['buy'];
				} else {
					$app['total'] = $app['amount'];
				}
			}
		}
		$currencies = [
			'UAH' => 'грн.',
			'USD' => 'дол.',
			"RUR" => 'руб.',
			"EUR" => 'євро'
		];
		echo json_encode(view('app_search_results',
			[
				'title' => 'Інформація про заявку',
				'types' => $this->types,
				'op_style' => [
					1 => "green",
					2 => "red",
					3 => "blue",
					4 => "orange"
				],
				'access' => $this->access,
				'order_list' => $order_list,
				'page_options' => [
					'can_edit_apps' => false,
//					'payed' => false,
//					'approved' => false
				],
				'currencies' => [
					'UAH' => 'грн.',
					'USD' => 'дол.',
					"RUR" => 'руб.',
					"EUR" => 'євро'
				],
				'locale' => $this->locale,

			]
		));
	}


//	public function pdf(){
//
//		require('../Libraries/FPDF/fpdf.php');
////		$this->load->library('your_lib');
//
//		$pdf = new FPDF();
//		$pdf->AddPage();
//		$pdf->SetFont('Arial', 'B', 16);
//		$pdf->Cell(40, 10, 'Hello World!');
//		$pdf->Output();
//
//	}

	public
	function refresh()
	{
		Models\Applications::refresh_apps();
		Models\Applications::refresh_dates();
		return redirect()->to('/application');
	}

	public
	function repeat()
	{
		Models\Applications::create_repeated_apps();
	}
}

