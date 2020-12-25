<?php
require 'vendor/autoload.php';
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverExpectedCondition;

$host = 'http://localhost:4444';
$email = $_GET['email'];
$session = $_GET['session'];
$driver = RemoteWebDriver::createBySessionID($session, $host);
$response['message'][] = [
	'status' => 'success',
	'msg' => $email.' - Bắt đầu lấy danh sách bái đã đăng'
];
$cookies = [];
$driver->manage()->deleteAllCookies();
$driver->get('https://m.facebook.com');
if(file_exists('cookie_fb/'.$email.'.txt'))
{
	$cookies = file_get_contents('cookie_fb/'.$email.'.txt');
	$cookies = unserialize($cookies);
}
if(is_array($cookies))
{
	foreach($cookies as $key => $cookie)
	{
		$driver->manage()->addCookie($cookie);
	}
}
$driver->get('https://m.facebook.com/groups_browse/your_groups/');
try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('[aria-label="Nhóm"]'))
	);
	$file_name = 'sts_fb/'.$email.'.txt';
	file_put_contents($file_name, 'success');
} catch (Exception $e) {
	$file_name = 'sts_fb/'.$email.'.txt';
	file_put_contents($file_name, 'fail');
	$response['message'][] = [
		'status' => 'login_fail',
		'msg' => $email.' - Đăng nhập thất bại'
	];
	endSession($response, $email);
}

//get data
$response['data'] = [];
$file_name = 'db_post/'.$email.'.txt';
if(file_exists($file_name))
{
	$data = file_get_contents($file_name);
	$data = formatData($data);
	$response['data'] = $data;
	// dump($response);die;
} 
endSession($response, $email);

function endSession($response, $email)
{
	$response['message'][] = [
		'status' => 'success',
		'msg' => $email.' - Kết thúc lấy danh sách bài đã đăng'
	];
	echo json_encode($response);
	die;
}

function formatData($data)
{
	$tmp = explode(PHP_EOL, $data);
	foreach ($tmp as $key => $value) {
		if($value == "")
		{
			unset($tmp[$key]);
			continue;
		}
		$tmp[$key] = json_decode($value, TRUE);
	}

	return $tmp;
}