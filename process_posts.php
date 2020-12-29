<?php
set_time_limit(500);
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
	'msg' => $email.' - Bắt đầu lấy danh sách bài đăng'
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
$driver->get('https://m.facebook.com/marketplace');
try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('[aria-label="Đang bán"][role="button"]'))
	);
	$driver->findElement(WebDriverBy::cssSelector('[aria-label="Đang bán"][role="button"]'))->click();
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
		    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('._a5o._9_7._2rgt._1j-f._2rgt[style="flex-grow:0;flex-shrink:1;margin:0 0 16px 0"]'))
		);

		$items = $driver->findElements(WebDriverBy::cssSelector('._a5o._9_7._2rgt._1j-f._2rgt[style="flex-grow:0;flex-shrink:1;margin:0 0 16px 0"]'));
	} catch (Exception $e) {
		$response['message'][] = [
			'status' => 'success',
			'msg' => $email.' - Không tìm thấy bài đăng nào'
		];
		endSession($response, $email);
	}
}
  
foreach ($items as $key => $item) {
	$tmp = ['image' => '', 'title' => '', 'price' => '', 'status' => '', 'info' => 'Bài viết đã được niêm yết', 'sts_info' => 0];
	try {
		$image = $item->findElement(WebDriverBy::cssSelector('img'))->getAttribute('src');
		$tmp['image'] = $image;
	} catch (Exception $e) {
		
	}

	try {
		$title = $item->findElement(WebDriverBy::cssSelector('._59k._2rgt._1j-f._2rgt._3zi4._2rgt._1j-f._2rgt[style="flex-grow:0;flex-shrink:1;font-size: 14px;font-weight: 400;line-height: 18px;text-align: left;color: #1D2129"]'))->getAttribute('innerText');
		$tmp['title'] = $title;
	} catch (Exception $e) {
		
	}

	try {
		$price = $item->findElement(WebDriverBy::cssSelector('[style="display: inline;text-decoration: "]'))->getAttribute('innerText');
		$tmp['price'] = $price;
	} catch (Exception $e) {
		
	}

	try {
		$status = $item->findElement(WebDriverBy::cssSelector('._59k._2rgt._1j-f._2rgt._3zi4._2rgt._1j-f._2rgt[style="flex-grow:0;flex-shrink:1;font-size: 12px;font-weight: 400;text-align: left;color: #8A8D91"]'))->getAttribute('innerText');
		$tmp['status'] = $status;
	} catch (Exception $e) {
		
	}

	try {
		$info = $item->findElement(WebDriverBy::cssSelector('._a58._9_7._2rgt._1j-f._2rgt._3zi4._2rgt._1j-f._2rgt'))->getAttribute('innerText');
		$tmp['info'] = $info;
	} catch (Exception $e) {
		
	}

	try {
		$info = $item->findElement(WebDriverBy::cssSelector('._a58._9_7._2rgt._1j-f._2rgt._3zi4._2rgt._1j-f._2rgt'));
		$danger = $info->findElement(WebDriverBy::cssSelector('._k7v._2rgt._1j-f._2rgt._3zi4._2rgt._1j-f._2rgt.img'));
		$tmp['sts_info'] = 1;
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
		'msg' => $email.' - Kết thúc lấy danh sách bài đăng'
	];
	echo json_encode($response);
	die;
}