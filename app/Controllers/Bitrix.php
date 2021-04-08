<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;
use function MongoDB\BSON\toJSON;


class Bitrix extends BaseController
{
	public function index()
	{
		$data = mb_parse_str(file_get_contents('writable/bitrix/requests.txt'), $result);

		echo "<pre>";
		var_dump($result);
		echo "</pre>";
		echo "</br>";
		if(!empty($result)){
			foreach ($result as $item){
				echo "<pre>";
				var_dump(mb_parse_str($item, $item_result));
				echo "</pre>";
			}
		}

		echo "<pre>";
		var_dump($item_result);
		echo "</pre>";
		echo "</br>";
	}

	public static function api()
	{
		$inputJSON = file_get_contents('php:input');
		file_put_contents('writable/bitrix/' .time() .'requests.txt', $inputJSON);
		return [];
	}


}
