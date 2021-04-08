<?php

namespace App\Models;

use Config;

class DirFiles
{
	public static function upload_files($upload_dir)
	{
		// ВАЖНО! тут должны быть все проверки безопасности передавемых файлов и вывести ошибки если нужно
		if (!empty($upload_dir)) {
			self::create_dir($upload_dir);
			$files = $_FILES; // полученные файлы
			$done_files = array();

			// переместим файлы из временной директории в указанную
			foreach ($files as $file) {
				$file_name = $file['name'];

				if (move_uploaded_file($file['tmp_name'], "$upload_dir/$file_name")) {
//					$done_files[] = realpath( "$upload_dir/$file_name" );
					$done_files[] = substr("$upload_dir/$file_name", 1);
				}
			}

			return $done_files ?
				['status' => 'ok', 'files' => $done_files] :
				['status' => 'error', 'message' => 'Ошибка загрузки файлов.'];
		}
	}


	public static function create_dir($dir, $clear = true)
	{
		if (!is_dir($dir)) {
			mkdir($dir, 0777);
		} else { // почистимо папку, якщо вона є
			if ($clear) {
				self::clear_dir($dir);
			}
		}
	}

	// функция очищения папки
	public static function clear_dir($dir)
	{
		$list = self::scan_dir($dir);

		foreach ($list as $file) {
			if (is_dir($dir . '/' . $file)) {
				self::clear_dir($dir . '/' . $file);
				rmdir($dir . '/' . $file);
			} else {
				unlink($dir . '/' . $file);
			}
		}
	}

	public static function scan_dir($dir)
	{
		$list = scandir($dir);
		unset($list[0], $list[1]);
		return array_values($list);
	}
}
