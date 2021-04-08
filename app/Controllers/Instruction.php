<?php namespace App\Controllers;

use App\Models;
use App\Models\Account;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Instruction extends BaseController
{
	public function job($position_id)
	{
		if (!empty($this->user_id)) {
			$instruction = Models\Instruction::get_instruction(['where' => [
				'position_id =' . $position_id,
				'type="job"'
			]]);
			if (empty($instruction)) {
				Models\Instruction::add([
					'type' => 'job',
					'position_id' => $position_id
				]);
				$instruction = Models\Instruction::get_instruction(['where' => [
					'position_id =' . $position_id,
					'type="job"'
				]]);
			}

			return $this->view('instruction/job',
				[
					'position' => Models\Position::get_position(['where' => [
						'fp.id = ' . $position_id
					]]),
					'instruction' => $instruction,
//					'css' => ['credits', 'table'],
					'js' => ['ckeditor', 'instruction']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public
	function save_job_ajax($position_id)
	{
		if (!empty($_POST['text'])) {
			$text = $_POST['text'];
			echo json_encode(Models\Instruction::update($position_id, ['text' => htmlspecialchars($text)]));
		}
	}
}
