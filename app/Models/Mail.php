<?php


namespace App\Models;

use Config;


class Mail
{
	public static function send_mail($to, $subject,$message)
	{

// несколько получателей
//		$to = 'johny@example.com, sally@example.com'; // обратите внимание на запятую

// тема письма
//		$subject = 'Birthday Reminders for August';

// текст письма
//		$message = '';

// Для отправки HTML-письма должен быть установлен заголовок Content-type
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Дополнительные заголовки
//		$headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
//		$headers[] = 'From: Birthday Reminder <birthday@example.com>';
//		$headers[] = 'Cc: birthdayarchive@example.com';
//		$headers[] = 'Bcc: birthdaycheck@example.com';

// Отправляем
		mail($to, $subject, $message,$headers);

	}
}
