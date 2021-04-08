<?php


namespace App\Models;
require 'vendor/autoload.php';

use NovaPoshta\Config;
use NovaPoshta\ApiModels\InternetDocument;
use NovaPoshta\MethodParameters\InternetDocument_getDocumentList;
use NovaPoshta\MethodParameters\MethodParameters;
use NovaPoshta\ApiModels\TrackingDocument;
use NovaPoshta_example\InternetDocument_example;

//support@novaposhta.ua
class NovaPoshta
{
	public static function index()
	{
//		Config::setApiKey('00cec78f6195c84e77a5351658095fa0');
		Config::setApiKey('c61fc990e73cdc498d774168aae2920b');
		Config::setFormat(Config::FORMAT_JSONRPC2);
		Config::setLanguage(Config::LANGUAGE_UA);
		$api = new InternetDocument();
		$params = new MethodParameters();
		$params->DateTimeFrom = "10.08.2020";
		$params->DateTimeTo = "24.09.2020";
		$params->GetFullList = 1;
//		$params->DateTime = "18.09.2020";

		Dev::var_dump($api->getDocumentList($params));

//		$api = new TrackingDocument();
//		$params = new MethodParameters();
//		$params->Documents = [
//            {
//				"DocumentNumber"=> "20450280594897"
//			}
//		];
//		InternetDocument_example::getDocumentList();
		$data = new InternetDocument_getDocumentList();
		$data->setDateTime('18.09.2020');
		$data->setStateIds(array('1'));

		Dev::var_dump( InternetDocument::getDocumentList($data));
	}
}
