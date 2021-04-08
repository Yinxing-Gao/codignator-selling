<?php


namespace App\Models;

use Config;

class Requests
{
	public static function getRequest($url, $params = [], $headers = [], $http_opts = [])
	{
		$params_string = '';
		if (!empty($params)) {
			$params_string = '?';
			foreach ($params as $key => $value) {
				$params_string .= '&' . $key . '=' . $value;
			}
		}

		$base_headers = [
			"Accept-language" => "ru",
			"Content-type" => "application/json"
		];
		$headers = array_merge($base_headers, $headers);
		$header_string = '';

		foreach ($headers as $key => $value) {
			$header_string .= $key . ': ' . $value . "\r\n";
		}

		$opts = array(
			'http' => array(
				'method' => "GET",
				'header' => $header_string
			)
		);

		$opts['http'] = array_merge($opts['http'], $http_opts);
		$context = stream_context_create($opts);

		$result = file_get_contents($url . $params_string, false, $context);
		return json_decode($result, true);
	}

	public static function postRequestJson($url, $parameters = [], $headers = [], $type = 'json', $curl_opts = [])
	{
		$base_headers = [
			"Accept-language: ru",
			"Content-type: application/json"
		];
		$headers = array_merge($base_headers, $headers);

		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
//    curl_setopt($handle,CURLOPT_USERAGENT,'o_46vu482gno');
		curl_setopt($handle, CURLOPT_TIMEOUT, 60);
		if ($type == 'json') {
			curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
		} elseif ($type == 'xml') {
			curl_setopt($handle, CURLOPT_POSTFIELDS, $parameters);
		}
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		if (!empty($curl_opts)) {
			foreach ($curl_opts as $key => $opt) {
				curl_setopt($handle, $key, $opt);
			}
		}

		$response = curl_exec($handle);
		$message = '';
		if ($response === false) {
			$errno = curl_errno($handle);
			$error = curl_error($handle);
			$message = $errno . ' ' . $error;
		}
		$http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
		curl_close($handle);
		if ($type == 'xml') {
			return ['response' => json_decode(json_encode(simplexml_load_string($response))), 'message' => $message, 'http_code' => $http_code];
		} else {
			return ['response' => $response, 'message' => $message, 'http_code' => $http_code];
		}
	}
}
