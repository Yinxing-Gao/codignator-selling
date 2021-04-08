<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Excel extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return $this->view('excel/main',
				[
					'css' => ['table', 'excel'],
					'js' => ['jquery-ui.min', 'upload', 'excel']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function create_template()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Hello World !');

		$writer = new Xlsx($spreadsheet);
		$writer->save('uploads/hello world.xlsx');
	}

	public function upload_users_ajax()
	{
		if (isset($_POST['users'])) {
			$dir = './uploads/excel/users';
			$uploaded = Models\DirFiles::upload_files($dir);
			if ($uploaded['status'] == 'ok') {
				$users_file = Models\DirFiles::scan_dir($dir)[0];
				$get_mime = explode('.', $users_file);
				$ext = end($get_mime);
				if ($ext == 'xlsx') {
					echo json_encode(Models\Excel::upload_users($dir . '/' . $users_file));
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Неправильне розширення файлу']);
				}
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Не вдалося завантажити файл']);
			}
		}
	}
}
