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
	'msg' => $email.' - Bắt đầu lấy danh sách nhóm'
];
$items_r = [];
$response['data'] = $items_r;

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

$items = [];
$current = -1;
while ($current < count($items)) {
	$current = count($items);
	$driver->executeScript('window.scrollTo(0,document.body.scrollHeight)');
	sleep(2);
	
	try {
		$driver->wait(5)->until(
		    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('._7hkf._3qn7._61-3._2fyi._3qng:not(._3-8n)'))
		);

		$items = $driver->findElements(WebDriverBy::cssSelector('._7hkf._3qn7._61-3._2fyi._3qng:not(._3-8n)'));
	} catch (Exception $e) {
		$response['message'][] = [
			'status' => 'fail',
			'msg' => $email.' - Không tìm thấy nhóm nào'
		];
		endSession($response, $email);
	}
}
  
foreach ($items as $key => $item) {
	$tmp = ['id' => '', 'image' => '', 'title' => '', 'status' => ''];
	try {
		$image = $item->findElement(WebDriverBy::cssSelector('img'))->getAttribute('src');
		$tmp['image'] = $image;
	} catch (Exception $e) {
		
	}

	try {
		$title = $item->findElement(WebDriverBy::cssSelector('._52je._52jb._52jh'))->getAttribute('innerText');
		$tmp['title'] = $title;
	} catch (Exception $e) {
		
	}

	try {
		$status = $item->findElement(WebDriverBy::cssSelector('._52jd._52j9'))->getAttribute('innerText');
		$tmp['status'] = $status;
	} catch (Exception $e) {
		
	}

	try {
		$id = $item->findElement(WebDriverBy::cssSelector('a'))->getAttribute('href');
		$tmp['id'] = $id;
	} catch (Exception $e) {
		
	}

	$items_r[] = $tmp;
}
$response['data'] = $items_r;
/*-------------------------------------------End process-----------------------------------------------*/
endSession($response, $email);

function endSession($response, $email)
{
	$response['message'][] = [
		'status' => 'success',
		'msg' => $email.' - Kết thúc lấy danh sách nhóm'
	];
	echo json_encode($response);
	die;
}