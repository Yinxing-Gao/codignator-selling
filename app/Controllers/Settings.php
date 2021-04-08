<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Settings extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {

			$settings = Models\Settings::get_settings(['where' => ['type = "default"']]);
			if (!empty($settings)) {
				foreach ($settings as &$setting) {
					$setting['default'] = $setting['value'];
					$setting['name'] = lang_('Settings.' . $setting['name'], $this->locale);
				}
			}
			//todo витягнути значення по акаунту
			//todo залежно від типу поля налаштування вивести відподний елемент
			//todo доробити збереження налаштувань

			return $this->view('settings/settings',
				[
					'settings' => $settings,
					'css' => ['settings']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}
}
