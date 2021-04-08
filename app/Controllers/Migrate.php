<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;
use function MongoDB\BSON\toJSON;


class Migrate extends BaseController
{
	public static function index()
	{
		Models\Migrations::update_tables();
	}
}
