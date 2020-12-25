<?php
require 'vendor/autoload.php';
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Interactions\WebDriverActions;

$host = 'http://localhost:4444';
$id = $_POST['id'];
$session = $_POST['session'];
$email = $_POST['email'];
$inputs = $_POST['inputs'];
$driver = RemoteWebDriver::createBySessionID($session, $host);
$response['message'][] = [
	'status' => 'success',
	'msg' => $email.' - Bắt đầu đăng bình luận'
];

$driver->get($id);			
try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('textarea[id="composerInput"]'))
	);
	$driver->findElement(WebDriverBy::cssSelector('textarea[id="composerInput"]'))->click()->sendKeys($inputs['title']);
	try {
		$driver->wait(5)->until(
			WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('input[accept^="image"]'))
		);
		if(isset($inputs['images']))
		{
			foreach($inputs['images'] as $image)
			{
				$input_image = $driver->findElement(WebDriverBy::cssSelector('input[accept^="image"]'));
				$input_image->sendKeys($image);
				sleep(5);
			}
		}
		try {
			$driver->wait(5)->until(
				WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('button[type="submit"][value="Đăng"]:not([disabled="true"])'))
			);
			$driver->findElement(WebDriverBy::cssSelector('button[type="submit"][value="Đăng"]:not([disabled="true"])'))->click();
			$response['message'][] = [
				'status' => 'success',
				'msg' => $email.' - Đăng bình luận thành công'
			];
		} catch (Exception $e) {
			$response['message'][] = [
				'status' => 'fail',
				'msg' => $email.' - Không tìm thấy nút đăng'
			];
		}
	} catch (Exception $e) {
		$response['message'][] = [
			'status' => 'fail',
			'msg' => $email.' - Không tìm thấy chỗ đăng ảnh'
		];
	}
} catch (Exception $e) {
	$response['message'][] = [
		'status' => 'fail',
		'msg' => $email.' - Không tìm thấy chỗ đăng bình luận'
	];
}

/*-------------------------------------------End process-----------------------------------------------*/
endSession($response, $email);

function endSession($response, $email)
{
	$response['message'][] = [
		'status' => 'success',
		'msg' => $email.' - Kết thúc đăng bình luận'
	];
	echo json_encode($response);
	die;
}