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
		$driver->findElement(WebDriverBy::cssSelector('.composerInput.mentions-input'))->click()->sendKeys($inputs['title']);
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
				}
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
				try {
					$driver->wait(15)->until(
						WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('a._6rap.inv'))
					);
					$url = $driver->findElement(WebDriverBy::cssSelector('a._6rap.inv'))->getAttribute('href');
					$data ='{"title": "'.$inputs['title'].'", "group": "'.$title.'", "url": "'.$url.'"}'."\r\n";
					if(!is_dir('db_post/'))
					{
						mkdir('db_post');
					}
					$file_name = 'db_post/'.$email.'.txt';
					file_put_contents($file_name, $data, FILE_APPEND | LOCK_EX);
				} catch (Exception $e) {
					$response['message'][] = [
						'status' => 'fail',
						'msg' => $email.' - Không lấy được url bài đăng vừa rồi'
					];
				}
			} catch (Exception $e) {
				$response['message'][] = [
					'status' => 'fail',
					'msg' => $email.' - Không tin thấy nút đăng'
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

/*-------------------------------------------End process-----------------------------------------------*/
endSession($response, $email);

function endSession($response, $email)
{
	$response['message'][] = [
		'status' => 'success',
		'msg' => $email.' - Kết thúc đăng bài'
	];
	echo json_encode($response);
	die;
}