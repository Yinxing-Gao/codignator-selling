<?php

namespace App\Models;

use Config;

class Dev
{
	public static function var_dump($arr)
	{
		echo "<pre>";
		var_dump($arr);
		echo "</pre>";
		echo "<br/>";
		echo "<br/>";
	}
}
