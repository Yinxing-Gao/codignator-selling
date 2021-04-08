<?php


namespace App\Models;

use Google_Client;
use Config;

//require  '../Libraries/autoload.php';
class Google
{
	private static function get_reg_client_id()
	{
		return '1002973690841-m298ou3mubch1jfg45qij9qcq1eh4i8b.apps.googleusercontent.com';
	}

	private static function get_reg_client_secret()
	{
		return 'b1ooPcZkX1sRsQnOFaaMhBsu';
	}

	public static function get_registration_url()
	{
		$params = array(
			'client_id' => self::get_reg_client_id(),
			'redirect_uri' => base_url() . 'user/google',
			'response_type' => 'code',
			'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
			'state' => '123'
		);

		return 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query($params));
	}

	public static function registration($code)
	{
		if (!empty($code)) {
			// Отправляем код для получения токена (POST-запрос).
			$params = array(
				'client_id' => self::get_reg_client_id(),
				'client_secret' => self::get_reg_client_secret(),
				'redirect_uri' => base_url() . 'user/google',
				'grant_type' => 'authorization_code',
				'code' => $code
			);

			$ch = curl_init('https://accounts.google.com/o/oauth2/token');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$data = curl_exec($ch);
			curl_close($ch);

			$data = json_decode($data, true);

			if (!empty($data['access_token'])) {
				// Токен получили, получаем данные пользователя.
				$params = array(
					'access_token' => $data['access_token'],
					'id_token' => $data['id_token'],
					'token_type' => 'Bearer',
					'expires_in' => 3599
				);

				$info = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query($params)));
				$info = json_decode($info, true);

				if (!empty($info)) {

					$user_result = User::get_user(['where' => [
						'google_id = ' . $info['id']
					]]);
					if ($user_result['status'] == 'ok' && !empty($user_result['result'])) {
						$user = $user_result['result'];
						return ['status' => 'ok', 'id' => $user->id];
					} else {
						return Account::create([
							'google_id' => $info['id'],
							'email' => $info['email'],
							'name' => $info['given_name'],
							'surname' => !empty($info['family_name']) ? $info['family_name'] : '',
							'locale' => $info['locale'],
						]);
					}
				}
			}
		}
		return ['status' => 'error'];
	}

	public static function getClient()
	{
		$client = new Google_Client();
		$client->setApplicationName('Google Sheets API PHP Quickstart');
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
		$client->setAuthConfig('credentials.json');
		$client->setAccessType('offline');
		$client->setPrompt('select_account consent');

		// Load previously authorized token from a file, if it exists.
		// The file token.json stores the user's access and refresh tokens, and is
		// created automatically when the authorization flow completes for the first
		// time.
		$tokenPath = 'token.json';
		if (file_exists($tokenPath)) {
			$accessToken = json_decode(file_get_contents($tokenPath), true);
			$client->setAccessToken($accessToken);
		}

		// If there is no previous token or it's expired.
		if ($client->isAccessTokenExpired()) {
			// Refresh the token if possible, else fetch a new one.
			if ($client->getRefreshToken()) {
				$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			} else {
				// Request authorization from the user.
				$authUrl = $client->createAuthUrl();
				printf("Open the following link in your browser:\n%s\n", $authUrl);
				print 'Enter verification code: ';
				$authCode = trim(fgets(STDIN));

				// Exchange authorization code for an access token.
				$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
				$client->setAccessToken($accessToken);

				// Check to see if there was an error.
				if (array_key_exists('error', $accessToken)) {
					throw new Exception(join(', ', $accessToken));
				}
			}
			// Save the token to a file.
			if (!file_exists(dirname($tokenPath))) {
				mkdir(dirname($tokenPath), 0700, true);
			}
			file_put_contents($tokenPath, json_encode($client->getAccessToken()));
		}
		return $client;
	}

	public static function connect()
	{
// Get the API client and construct the service object.
		$client = self::getClient();
		$service = new Google_Service_Sheets($client);

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
		$spreadsheetId = '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms';
		$range = 'Class Data!A2:E';
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$values = $response->getValues();

		if (empty($values)) {
			print "No data found.\n";
		} else {
			print "Name, Major:\n";
			foreach ($values as $row) {
				// Print columns A and E, which correspond to indices 0 and 4.
				printf("%s, %s\n", $row[0], $row[4]);
			}
		}
	}
}
