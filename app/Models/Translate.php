<?php


namespace App\Models;

use Config;

class Translate
{
	public static function translate($key, $lang, $translate_file)
	{
//		$file = file_get_contents(PATH)
	}

	public static function translate_array_key($array, $keys, $locale, $translate_file)
	{
		if (!empty($array) && !empty($keys)) {
			foreach ($array as &$row) {
				foreach ($keys as $key) {
					$row[$key] = lang_($translate_file . '.' . $row[$key], $locale);
				}
			}
		}
		return $array;
	}

	public static function translate_row_key($row, $keys, $locale, $translate_file)
	{
		$row = (array)$row;
		if (!empty($row) && !empty($keys)) {
				foreach ($keys as $key) {
					$row[$key] = lang_($translate_file . '.' . $row[$key], $locale);
				}
		}
		return (object)$row;
	}
}
