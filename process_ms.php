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

$cookies = [];
$driver->manage()->deleteAllCookies();
$driver->get('https://www.messenger.com');
if(file_exists('cookie_ms/'.$email.'.txt'))
{
	$cookies = file_get_contents('cookie_ms/'.$email.'.txt');
	$cookies = unserialize($cookies);
}
if(is_array($cookies))
{
	foreach($cookies as $key => $cookie)
	{
		$driver->manage()->addCookie($cookie);
	}
}
$driver->get('https://www.messenger.com');
$response = ['info' => ['email' => $email, 'image' => ''], 'message' => ['status' => 'login_fail', 'msg' =>  $email.' - Đăng nhập thất bại']];

try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('[aria-label="Tạo phòng họp mặt mới"]'))
	);
	$response['message'] = [
		'status' => 'success',
		'msg' => $email.' - Đăng nhập thành công'
	];
	endSession($response, $driver);
} catch (Exception $e) {
	$file_name = 'sts_ms/'.$email.'.txt';
	file_put_contents($file_name, 'fail');
	endSession($response, $driver);
}
function endSession($response, $driver)
{
	echo json_encode($response);
	die;
}