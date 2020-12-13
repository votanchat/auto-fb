<?php
require 'vendor/autoload.php';
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

$host = 'http://localhost:4444';
$capabilities = DesiredCapabilities::chrome();

/*-------------------------------------------Input Value-----------------------------------------------*/
$user = $_POST;

/*-------------------------------------------Start process-----------------------------------------------*/
$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['--no-sandbox', '--disable-gpu', '--disable-notifications']);
$chromeOptions->addArguments(['--headless']); //on | off chrome

/*-------------------------------------------Open chrome-----------------------------------------------*/
$capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

/*-------------------------------------------Host-----------------------------------------------*/
$driver = RemoteWebDriver::create($host, $capabilities);

/*-------------------------------------------URL-----------------------------------------------*/
$driver->get('https://facebook.com');

/*-------------------------------------------Login-----------------------------------------------*/
$response = ['info' => ['email' => $user['email'], 'image' => ''], 'message' => ['status' => 'fail', 'msg' =>  $user['email'].' - Đăng nhập lần đầu thất bại']];
$driver->findElement(WebDriverBy::id('email'))
    ->sendKeys($user['email']);
$driver->findElement(WebDriverBy::id('pass'))
    ->sendKeys($user['pass'])->submit();
try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.l9j0dhe7.tr9rh885.buofh1pr.cbu4d94t.j83agx80'))
	);
	$response['message'] = [
		'status' => 'success',
		'msg' => $user['email'].' - Đăng nhập lần đầu thành công'
	];
	//save cookie
	$file_name = 'cookie_fb/'.$user['email'].'.txt';
	$cookies = $driver->manage()->getCookies();
	$serialized = serialize($cookies);
	file_put_contents($file_name, $serialized);
	$file_name = 'sts_fb/'.$user['email'].'.txt';
	file_put_contents($file_name, 'success');
} catch (Exception $e) {
	endSession($response, $driver);
}

$image = '';
try {
	$driver->wait(3)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.s45kfl79.emlxlaya.bkmhp75w.spb7xbtv.a8c37x1j'))
	);
	$image = $driver->findElement(WebDriverBy::cssSelector('.s45kfl79.emlxlaya.bkmhp75w.spb7xbtv.a8c37x1j'));
	$image = $image->getAttribute('src');
} catch (Exception $e) {
	
}
// dump($image);
// save image
$file_name = 'images_fb/'.$user['email'].'.txt';
file_put_contents($file_name, $image);

/*-------------------------------------------End process-----------------------------------------------*/
$response['info']['image'] = $image;
endSession($response, $driver);

function endSession($response, $driver)
{
	$driver->quit();
	echo json_encode($response);
	die;
}