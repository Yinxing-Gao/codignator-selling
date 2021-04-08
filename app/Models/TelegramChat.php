<?php


namespace App\Models;
require 'vendor/autoload.php';

use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Autoloader;
use PHPSocketIO\SocketIO;

class TelegramChat
{
	private static $bot_token = '';
	private static $api_url;

//	public static function start()
//	{
//		// Listen port 2021 for socket.io client
////		$io = new SocketIO(2021);
////		$io->on('connection', public static function  ($socket) use ($io) {
////			$socket->on('chat message', public static function  ($msg) use ($io) {
////				$io->emit('chat message', $msg);
////			});
////		});
////
////		return Worker::runAll();
//
//
//		$address = '0.0.0.0';
//		$port = 12345;
//
//// Create WebSocket.
//		$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//		socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
//		socket_bind($server, $address, $port);
//		socket_listen($server);
//		$client = socket_accept($server);
//
//// Send WebSocket handshake headers.
//		$request = socket_read($client, 5000);
//		preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
//		$key = base64_encode(pack(
//			'H*',
//			sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
//		));
//		$headers = "HTTP/1.1 101 Switching Protocols\r\n";
//		$headers .= "Upgrade: websocket\r\n";
//		$headers .= "Connection: Upgrade\r\n";
//		$headers .= "Sec-WebSocket-Version: 13\r\n";
//		$headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
//		socket_write($client, $headers, strlen($headers));
//
//// Send messages into WebSocket in a loop.
//		while (true) {
//			sleep(1);
//			$content = 'Now: ' . time();
//			$response = chr(129) . chr(strlen($content)) . $content;
//			socket_write($client, $response);
//		}
//	}
//
//	public static function start2()
//	{
//		define('HOST_NAME', "localhost");
//		define('PORT', "8090");
//		$null = NULL;
//
//		$chatHandler = new TelegramChat();
//
//		$socketResource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//		socket_set_option($socketResource, SOL_SOCKET, SO_REUSEADDR, 1);
//		socket_bind($socketResource, 0, PORT);
//		socket_listen($socketResource);
//
//		$clientSocketArray = array($socketResource);
//		Telegram::send_admin_message(json_encode($clientSocketArray));
//		while (true) {
//			$newSocketArray = $clientSocketArray;
//			socket_select($newSocketArray, $null, $null, 0, 10);
//
//			if (in_array($socketResource, $newSocketArray)) {
//				$newSocket = socket_accept($socketResource);
//				$clientSocketArray[] = $newSocket;
//
//				$header = socket_read($newSocket, 1024);
//				$chatHandler->doHandshake($header, $newSocket, HOST_NAME, PORT);
//
//				socket_getpeername($newSocket, $client_ip_address);
//				$connectionACK = $chatHandler->newConnectionACK($client_ip_address);
//
//				$chatHandler->send($connectionACK);
//
//				$newSocketIndex = array_search($socketResource, $newSocketArray);
//				unset($newSocketArray[$newSocketIndex]);
//			}
//
//			foreach ($newSocketArray as $newSocketArrayResource) {
//				while (socket_recv($newSocketArrayResource, $socketData, 1024, 0) >= 1) {
//					$socketMessage = $chatHandler->unseal($socketData);
//					$messageObj = json_decode($socketMessage);
//
//					$chat_box_message = $chatHandler->createChatBoxMessage($messageObj->chat_user, $messageObj->chat_message);
//					$chatHandler->send($chat_box_message);
//					break 2;
//				}
//
//				$socketData = @socket_read($newSocketArrayResource, 1024, PHP_NORMAL_READ);
//				if ($socketData === false) {
//					socket_getpeername($newSocketArrayResource, $client_ip_address);
//					$connectionACK = $chatHandler->connectionDisconnectACK($client_ip_address);
//					$chatHandler->send($connectionACK);
//					$newSocketIndex = array_search($newSocketArrayResource, $clientSocketArray);
//					unset($clientSocketArray[$newSocketIndex]);
//				}
//			}
//		}
//		socket_close($socketResource);
//	}
//
//	public static function send($message)
//	{
//		global $clientSocketArray;
//		$messageLength = strlen($message);
//		foreach ($clientSocketArray as $clientSocket) {
//			@socket_write($clientSocket, $message, $messageLength);
//		}
//		return true;
//	}
//
//	public static function unseal($socketData)
//	{
//		$length = ord($socketData[1]) & 127;
//		if ($length == 126) {
//			$masks = substr($socketData, 4, 4);
//			$data = substr($socketData, 8);
//		} elseif ($length == 127) {
//			$masks = substr($socketData, 10, 4);
//			$data = substr($socketData, 14);
//		} else {
//			$masks = substr($socketData, 2, 4);
//			$data = substr($socketData, 6);
//		}
//		$socketData = "";
//		for ($i = 0; $i < strlen($data); ++$i) {
//			$socketData .= $data[$i] ^ $masks[$i % 4];
//		}
//		return $socketData;
//	}
//
//	public static function seal($socketData)
//	{
//		$b1 = 0x80 | (0x1 & 0x0f);
//		$length = strlen($socketData);
//
//		if ($length <= 125)
//			$header = pack('CC', $b1, $length);
//		elseif ($length > 125 && $length < 65536)
//			$header = pack('CCn', $b1, 126, $length);
//		elseif ($length >= 65536)
//			$header = pack('CCNN', $b1, 127, $length);
//		return $header . $socketData;
//	}
//
//	public static function doHandshake($received_header, $client_socket_resource, $host_name, $port)
//	{
//		$headers = array();
//		$lines = preg_split("/\r\n/", $received_header);
//		foreach ($lines as $line) {
//			$line = chop($line);
//			if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
//				$headers[$matches[1]] = $matches[2];
//			}
//		}
//
//		$secKey = $headers['Sec-WebSocket-Key'];
//		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
//		$buffer = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
//			"Upgrade: websocket\r\n" .
//			"Connection: Upgrade\r\n" .
//			"WebSocket-Origin: $host_name\r\n" .
//			"WebSocket-Location: ws://$host_name:$port/demo/shout.php\r\n" .
//			"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
//		socket_write($client_socket_resource, $buffer, strlen($buffer));
//	}
//
//	public static function newConnectionACK($client_ip_address)
//	{
//		$message = 'New client ' . $client_ip_address . ' joined';
//		$messageArray = array('message' => $message, 'message_type' => 'chat-connection-ack');
//		$ACK = self::seal(json_encode($messageArray));
//		return $ACK;
//	}
//
//	public static function connectionDisconnectACK($client_ip_address)
//	{
//		$message = 'Client ' . $client_ip_address . ' disconnected';
//		$messageArray = array('message' => $message, 'message_type' => 'chat-connection-ack');
//		$ACK = self::seal(json_encode($messageArray));
//		return $ACK;
//	}
//
//	public static function createChatBoxMessage($chat_user, $chat_box_message)
//	{
//		$message = $chat_user . ": <div class='chat-box-message'>" . $chat_box_message . "</div>";
//		$messageArray = array('message' => $message, 'message_type' => 'chat-box-html');
//		$chatMessage = self::seal(json_encode($messageArray));
//		return $chatMessage;
//	}
//
//	public static function add($atts)
//	{
//		if (!empty($atts['user_id']) && !empty($atts['text']) && !empty($atts['type'])) {
//			$params = [
//				'user_id' => $atts['user_id'],
//				'type' => $atts['type'],
//				'text' => $atts['text'],
//				'date' => time()
//			];
//			return DBHelp::insert('fin_telegram_requests', $params);
//		}
//	}

	public static function start()
	{
		Telegram::$bot_token = '1260122605:AAHNaMXHDzFfld7XFirhYBwr6HOCjq06QFc';
		Telegram::$api_url = 'https://api.telegram.org/bot' . Telegram::$bot_token . '/';
	}

	public static function chat_reply($request)
	{
		$message = $request['message']['text'];
		$username = !empty($post['message']['from']['username']) ? $post['message']['from']['username'] : '';
		$user_id = self::get_reply_to($request);

		Dev::var_dump(Telegram::add([
			'user_id' => $user_id,
			'user_name' => $username,
			'type' => 'chat_reply',
			'text' => $message,
			'status' => 'waiting'
		]));
	}

	public static function get_reply_to($request)
	{
		$prev_reply_to_result = Telegram::get_request([
			'type = "chat_change"'
		]);

		$prev_reply_to = !empty($prev_reply_to_result) ? $prev_reply_to_result->user_id : null;

		if (!empty($request['message']['reply_to_message'])) {
			$replied_message = $request['message']['reply_to_message']['text'];
			$user_id = substr($replied_message, 0, strripos($replied_message, ') '));

		}
//		else {
//			$prev_chat_result = Telegram::get_request([
//				'type = "chat_message"'
//			]);
//			$user_id = !empty($prev_chat_result) ? $prev_chat_result->user_id : null;
//		}

		if (!empty($user_id) && ((!empty($prev_reply_to)&& $prev_reply_to !== $user_id) || empty($prev_reply_to))) {
			self::change_reply_to($user_id);
			$prev_reply_to = $user_id;
		}
		return $prev_reply_to;
	}

	public static function change_reply_to($user_id)
	{
		Telegram::add([
			'user_id' => $user_id,
			'type' => "chat_change",
			'text' => "chat_changed to " . $user_id
		]);

		$user_result = User::get_user(['where' => [
			'id = "' . $user_id . '"'
		]]);
		if ($user_result['status'] == 'ok' && !empty($user_result['result'])) {
			$user = $user_result['result'];

			Telegram::send_admin_message('Чат змінено на: <b>' . $user->name . ' ' . $user->surname . '</b>', 'chat');
		}
	}
}
