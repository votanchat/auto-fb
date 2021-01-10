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
use Facebook\WebDriver\Interactions\WebDriverActions;

$host = 'http://localhost:4444';
$ids = $_POST['ids'];
$session = $_POST['session'];
$email = $_POST['email'];
$driver = RemoteWebDriver::createBySessionID($session, $host);
$response['message'][] = [
	'status' => 'success',
	'msg' => $email.' - Bắt đầu xóa bài'
];

// get items
$items = [];
try {
	$items = $driver->findElements(WebDriverBy::cssSelector('._a5o._9_7._2rgt._1j-f._2rgt[style="flex-grow:0;flex-shrink:1;margin:0 0 16px 0"]'));
} catch (Exception $e) {
	$response['message'][] = [
		'status' => 'fail',
		'msg' => $email.' - Không tìm thấy bài đăng nào'
	];
}

// delete item
foreach($items as $key => $item)
{
	if(in_array($key, $ids))
	{
		try {
			$title = $item->findElement(WebDriverBy::cssSelector('._59k._2rgt._1j-f._2rgt._3zi4._2rgt._1j-f._2rgt[style="flex-grow:0;flex-shrink:1;font-size: 14px;font-weight: 400;line-height: 18px;text-align: left;color: #1D2129"]'))->getAttribute('innerText');
			$btn_mn = $item->findElement(WebDriverBy::cssSelector('._59k._2rgt._1j-f._2rgt._3zi4._2rgt._1j-f._2rgt[style="flex-grow:0;flex-shrink:1;font-size: 14px;font-weight: 500;text-align: left;color: #4267B2"]'));
			$driver->executeScript("arguments[0].focus();", [$btn_mn]);
			$driver->executeScript("arguments[0].click();", [$btn_mn]);

			try {
				$driver->wait(7)->until(
					WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('._5m_x.accelerate:not([style$="display: none;"])'))
				);
				$popup = $driver->findElement(WebDriverBy::cssSelector('._5m_x.accelerate:not([style$="display: none;"])'));
				sleep(2);
				$buttons = $popup->findElements(WebDriverBy::cssSelector('._54k8._55i1._58a0.touchable'));
				$buttons[1]->click();

				try {
					$driver->wait(5)->until(
						WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('._54k8._52jg._56bs._26vk._87g1._87g2._87g3._2rgt._1j-g._2rgt._2rgt._1j-f._2rgt._3zi4._2rgt._1j-f._2rgt._56bw._56bu[type="submit"]'))
					);
					$driver->findElement(WebDriverBy::cssSelector('._54k8._52jg._56bs._26vk._87g1._87g2._87g3._2rgt._1j-g._2rgt._2rgt._1j-f._2rgt._3zi4._2rgt._1j-f._2rgt._56bw._56bu[type="submit"]'))->click();
					$response['message'][] = [
						'status' => 'success',
						'msg' => $email.' - Đã xóa: '.$title
					];
				} catch (Exception $e) {
					$response['message'][] = [
						'status' => 'fail',
						'msg' => $email.' - Không tìm thấy nút xóa, vui vòng tải lại danh sách bằng click avatar'
					];
					break;
				}
			} catch (Exception $e) {
				$response['message'][] = [
					'status' => 'fail',
					'msg' => $email.' - Không tìm thấy Xóa mặt hàng'
				];
			}
		} catch (Exception $e) {
			$response['message'][] = [
				'status' => 'fail',
				'msg' => $email.' - Không tìm thấy Quản lý mặt hàng'
			];
		}
		sleep(2);
	}
}

// reload data
try {
	$items = $driver->findElements(WebDriverBy::cssSelector('._a5o._9_7._2rgt._1j-f._2rgt[style="flex-grow:0;flex-shrink:1;margin:0 0 16px 0"]'));
} catch (Exception $e) {
	$response['message'][] = [
		'status' => 'success',
		'msg' => $email.' - Không tìm thấy bài đăng nào'
	];
}

$items_r = [];
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
		'msg' => $email.' - Kết thúc'
	];
	echo json_encode($response);
	die;
}