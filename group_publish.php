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
$driver = RemoteWebDriver::createBySessionID($session, $host);
$response['message'][] = [
	'status' => 'success',
	'msg' => $email.' - Bắt đầu đăng bài'
];

$driver->get($id);
				
try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('._55wr._7om2._3m1m'))
	);
	$elemnt = $driver->findElement(WebDriverBy::cssSelector('._55wr._7om2._3m1m'));
	$title = $elemnt->findElement(WebDriverBy::cssSelector('h1'))->getAttribute('innerText');

	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('._4g34._6ber._78cq._7cdk._5i2i._52we'))
	);
	$driver->findElement(WebDriverBy::cssSelector('._4g34._6ber._78cq._7cdk._5i2i._52we'))->click();
	try {
		$driver->wait(5)->until(
			WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.composerInput.mentions-input'))
		);
		$driver->findElement(WebDriverBy::cssSelector('.composerInput.mentions-input'))->click()->sendKeys('Xin chao cac ban');
		try {
			$images = ['C:\Users\ITSJ\OneDrive\Desktop\giay\1.jpg','C:\Users\ITSJ\OneDrive\Desktop\giay\2.jpg'];
			$driver->wait(5)->until(
				WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('input[accept^="image"]'))
			);
			foreach($images as $image)
			{
				$input_image = $driver->findElement(WebDriverBy::cssSelector('input[accept^="image"]'));
				$input_image->sendKeys($image);
			}
			try {
				$driver->wait(5)->until(
					WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('button[type="submit"][value="Đăng"]:not([name="view_post"])'))
				);
				$driver->findElement(WebDriverBy::cssSelector('button[type="submit"][value="Đăng"]:not([name="view_post"])'))->click();
				$response['message'][] = [
					'status' => 'success',
					'msg' => $email.' - Đă đăng: '.$title
				];
			} catch (Exception $e) {
				$response['message'][] = [
					'status' => 'fail',
					'msg' => $email.' - Không tin thấy chỗ xóa'
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
			'msg' => $email.' - Không tìm thấy chỗ đăng nội dung'
		];
	}
} catch (Exception $e) {
	$response['message'][] = [
		'status' => 'fail',
		'msg' => $email.' - Không tìm thấy chỗ đăng bài'
	];
}
sleep(10);

/*-------------------------------------------End process-----------------------------------------------*/
endSession($response, $email);

function endSession($response, $email)
{
	$response['message'][] = [
		'status' => 'success',
		'msg' => $email.' - Kết thúc'
	];
	echo json_encode($response);
	die;
}