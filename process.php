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

/*-------------------------------------------Set input key by language-----------------------------------------------*/
$input_key = ['en' => array(), 'vn' => array() ];
$input_key['en']['image'] = 'input[accept^="image"]';
$input_key['en']['title'] = '[aria-label="Title"]';
$input_key['en']['price'] = '[aria-label="Price"]';
$input_key['en']['category'] = '[aria-label="Category"]';
$input_key['en']['condition'] = '[aria-label="Condition"]';
$input_key['en']['brand'] = '[aria-label="Brand"]';
$input_key['en']['description'] = '[aria-label="Description"]';
$input_key['en']['tags'] = '[aria-label="Product tags"]';
$input_key['en']['add_tag'] = '[aria-label="Click to submit current value"]';
$input_key['en']['location'] = '[aria-label="Location"]';
$input_key['en']['next'] = '[aria-label="Next"]:not([aria-disabled="true"])';
$input_key['en']['publish'] = '[aria-label="Publish"]:not([aria-disabled="true"])';

$input_key['vi']['image'] = 'input[accept^="image"]';
$input_key['vi']['title'] = '[aria-label="Tiêu đề"]';
$input_key['vi']['price'] = '[aria-label="Giá"]';
$input_key['vi']['category'] = '[aria-label="Hạng mục"]';
$input_key['vi']['condition'] = '[aria-label="Tình trạng"]';
$input_key['vi']['brand'] = '[aria-label="Thương hiệu"]';
$input_key['vi']['description'] = '[aria-label="Mô tả"]';
$input_key['vi']['tags'] = '[aria-label="Thẻ sản phẩm"]';
$input_key['vi']['add_tag'] = '[aria-label="Nhấp để gửi giá trị hiện tại"]';
$input_key['vi']['location'] = '[aria-label="Vị trí"]';
$input_key['vi']['next'] = '[aria-label="Tiếp"]:not([aria-disabled="true"])';
$input_key['vi']['publish'] = '[aria-label="Đăng"]:not([aria-disabled="true"])';

/*-------------------------------------------Input Value-----------------------------------------------*/
$input_values = $_POST;

/*-------------------------------------------Start process-----------------------------------------------*/
$response[] = [
	'status' => 'success',
	'msg' => $input_values['email'].' - Bắt đầu'
];

$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['--no-sandbox', '--disable-gpu', '--disable-notifications']);
// $chromeOptions->addArguments(['--headless']); //on | off chrome

/*-------------------------------------------Open chrome-----------------------------------------------*/
$capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

/*-------------------------------------------Host-----------------------------------------------*/
$driver = RemoteWebDriver::create($host, $capabilities);

/*-------------------------------------------URL-----------------------------------------------*/
$driver->get('https://facebook.com');

/*-------------------------------------------Login-----------------------------------------------*/
$driver->findElement(WebDriverBy::id('email'))
    ->sendKeys($input_values['email']);
$driver->findElement(WebDriverBy::id('pass'))
    ->sendKeys($input_values['pass'])->submit();
try {
	$driver->wait(2)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.l9j0dhe7.tr9rh885.buofh1pr.cbu4d94t.j83agx80'))
	);
	$response[] = [
		'status' => 'success',
		'msg' => $input_values['email'].' - Đăng nhập thành công'
	];
} catch (Exception $e) {
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - Đăng nhập thất bại'
	];
	endSession($response, $driver, $input_values['email']);
}

/*-------------------------------------------Get language-----------------------------------------------*/
$lang = getLang($driver->getPageSource());

/*-------------------------------------------Set input key by language-----------------------------------------------*/
$input_names = [];
if($lang == 'en' || $lang == 'vi')
{
	$input_names = $input_key[$lang];
}
else
{
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - Tài khoản sử dụng ngôn ngữ khác.'
	];
	endSession($response, $driver, $input_values['email']);
}

foreach($input_values['locations'] as $k => $location)
{

	/*-------------------------------------------URL Marketplace-----------------------------------------------*/
	sleep(1);
	$driver->get('https://www.facebook.com/marketplace/create/item');

	/*-------------------------------------------Check ability publish-----------------------------------------------*/
	try {
		$driver->wait(3)->until(
		    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.rq0escxv.l9j0dhe7.du4w35lb.j83agx80.pfnyh3mw.i1fnvgqd.gs1a9yip.owycx6da.btwxx1t3.d1544ag0.tw6a2znq.f10w8fjw.pybr56ya.b5q2rw42.lq239pai.mysgfdmx.hddg9phg'))
		);
		$response[] = [
			'status' => 'fail',
			'msg' => $input_values['email'].' - Không thể đăng thêm bài'
		];
		endSession($response, $driver, $input_values['email']);
	} catch (Exception $e) {
		
	}

	/*-------------------------------------------Enter input-----------------------------------------------*/

	/*-------------------------------------------Input Image-----------------------------------------------*/
	$driver->wait(3)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['image']))
	);
	$fileInput = $driver->findElement(WebDriverBy::cssSelector($input_names['image']));
	$images = randomImages($input_values['images']);
	if(empty($images))
	{
		$response[] = [
			'status' => 'fail',
			'msg' => $input_values['email'].' - Số lượng ảnh không đủ - '.$location
		];
		endSession($response, $driver, $input_values['email']);
	}
	foreach($images as $image)
	{
		try {
			$fileInput->sendKeys($image);
			$driver->executeScript('document.querySelector(\'input[accept^="image"]\').value = ""');
			sleep(3);
		} catch (Exception $e) {
			$response[] = [
				'status' => 'fail',
				'msg' => $input_values['email'].' - Không tìm thấy hình ảnh.'
			];
			endSession($response, $driver, $input_values['email']);
		}
	}

	/*-------------------------------------------Input Title-----------------------------------------------*/
	$driver->findElement(WebDriverBy::cssSelector($input_names['title']))->click()->sendKeys(randomArray($input_values['titles1']).' '.randomArray($input_values['titles2']).', '.$location);

	/*-------------------------------------------Input Price-----------------------------------------------*/
	$driver->findElement(WebDriverBy::cssSelector($input_names['price']))->click()->sendKeys($input_values['price']);

	/*-------------------------------------------Input Category-----------------------------------------------*/
	$driver->findElement(WebDriverBy::cssSelector($input_names['category']))->click();
	try {

		$driver->wait(3)->until(
			WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.oajrlxb2.gs1a9yip.g5ia77u1.mtkw9kbi.tlpljxtp.qensuy8j.ppp5ayq2.goun2846.ccm00jje.s44p3ltw.mk2mc5f4.rt8b4zig.n8ej3o3l.agehan2d.sk4xxmp2.rq0escxv.nhd2j8a9.a8c37x1j.mg4g778l.btwxx1t3.pfnyh3mw.p7hjln8o.kvgmc6g5.cxmmr5t8.oygrvhab.hcukyx3x.tgvbjcpo.hpfvmrgz.jb3vyjys.rz4wbd8a.qt6c0cv9.a8nywdso.l9j0dhe7.i1ao9s8h.esuyzwwr.f1sip0of.du4w35lb.lzcic4wl.abiwlrkh.p8dawk7l.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi[role="button"]'))
		);
		$input_categories = $driver->findElements(WebDriverBy::cssSelector('.oajrlxb2.gs1a9yip.g5ia77u1.mtkw9kbi.tlpljxtp.qensuy8j.ppp5ayq2.goun2846.ccm00jje.s44p3ltw.mk2mc5f4.rt8b4zig.n8ej3o3l.agehan2d.sk4xxmp2.rq0escxv.nhd2j8a9.a8c37x1j.mg4g778l.btwxx1t3.pfnyh3mw.p7hjln8o.kvgmc6g5.cxmmr5t8.oygrvhab.hcukyx3x.tgvbjcpo.hpfvmrgz.jb3vyjys.rz4wbd8a.qt6c0cv9.a8nywdso.l9j0dhe7.i1ao9s8h.esuyzwwr.f1sip0of.du4w35lb.lzcic4wl.abiwlrkh.p8dawk7l.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi[role="button"]'));
		$input_categories[$input_values['category']]->click();
		
	} catch (Exception $e) {
		$response[] = [
			'status' => 'fail',
			'msg' => $input_values['email'].' - Không tìm thấy hạng mục'
		];
		endSession($response, $driver, $input_values['email']);
	}

	/*-------------------------------------------Input Condition-----------------------------------------------*/
	try {
		$driver->wait(3)->until(
		    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['condition']))
		);
		$driver->findElement(WebDriverBy::cssSelector($input_names['condition']))->click();
		$driver->wait(3)->until(
		    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.oajrlxb2.g5ia77u1.qu0x051f.esr5mh6w.e9989ue4.r7d6kgcz.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.oi9244e8.oygrvhab.h676nmdw.pybr56ya.dflh9lhu.f10w8fjw.scb9dxdr.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.bp9cbjyn.dwo3fsh8.btwxx1t3.pfnyh3mw.du4w35lb'))
		);
		$input_conditions = $driver->findElements(WebDriverBy::cssSelector('.oajrlxb2.g5ia77u1.qu0x051f.esr5mh6w.e9989ue4.r7d6kgcz.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.oi9244e8.oygrvhab.h676nmdw.pybr56ya.dflh9lhu.f10w8fjw.scb9dxdr.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.bp9cbjyn.dwo3fsh8.btwxx1t3.pfnyh3mw.du4w35lb'));
		$input_conditions[$input_values['condition']]->click();
	} catch (Exception $e) {
		$response[] = [
			'status' => 'fail',
			'msg' => $input_values['email'].' - Không tìm thấy tình trạng'
		];
		endSession($response, $driver, $input_values['email']);
	}
	

	/*-------------------------------------------Input Brand-----------------------------------------------*/
	$driver->wait(3)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['brand']))
	);
	$driver->findElement(WebDriverBy::cssSelector($input_names['brand']))->click()->sendKeys($input_values['brand']);

	/*-------------------------------------------Input Description-----------------------------------------------*/
	$driver->findElement(WebDriverBy::cssSelector($input_names['description']))->click()->sendKeys($input_values['description']);

	/*-------------------------------------------Input Product tags-----------------------------------------------*/
	try {
		$driver->wait(2)->until(
			WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['tags']))
		);
		$tagInput = $driver->findElement(WebDriverBy::cssSelector($input_names['tags']));
		$tagInput->click();
		foreach ($input_values['tags'] as $key => $value) {
			$tagInput->sendKeys($value);
			$driver->wait(2)->until(
				WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['add_tag']))
			);
			$driver->findElement(WebDriverBy::cssSelector($input_names['add_tag']))->click();
		}
	} catch (Exception $e) {
		$response[] = [
			'status' => 'warn',
			'msg' => $input_values['email'].' - Không thể đăng tag ở người dùng này'
		];
	}

	/*-------------------------------------------Input Location-----------------------------------------------*/
	$driver->findElement(WebDriverBy::cssSelector($input_names['location']))->click()->sendKeys($location);
	try {
		$driver->wait(3)->until(
			WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.bp9cbjyn.nhd2j8a9.j83agx80.ni8dbmo4.stjgntxs.l9j0dhe7.dwzzwef6.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi'))
		);
		$input_locations = $driver->findElements(WebDriverBy::cssSelector('.bp9cbjyn.nhd2j8a9.j83agx80.ni8dbmo4.stjgntxs.l9j0dhe7.dwzzwef6.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi'));
		$input_locations[0]->click();
	} catch (Exception $e) {
		$response[] = [
			'status' => 'fail',
			'msg' => $input_values['email'].' - Không tìm thấy địa điểm'
		];
		continue;
	}

	/*-------------------------------------------Publish-----------------------------------------------*/
	try {
		$driver->wait(1)->until(
		    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['next']))
		);
		$driver->findElement(WebDriverBy::cssSelector($input_names['next']))->click();
	} catch (Exception $e) {
		
	}
	try {
		$driver->wait(3)->until(
		    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['publish']))
		);
		$driver->findElement(WebDriverBy::cssSelector($input_names['publish']))->click();

		$response[] = [
			'status' => 'success',
			'msg' => $input_values['email'].' - Đăng bài thành công - '.$location
		];
	} catch (Exception $e) {
		$response[] = [
			'status' => 'fail',
			'msg' => $input_values['email'].' - Không thể đăng bài vì thiếu nội dung'
		];
		endSession($response, $driver, $input_values['email']);
	}
}


/*-------------------------------------------End process-----------------------------------------------*/
endSession($response, $driver, $input_values['email']);

/*-------------------------------------------Function get language-----------------------------------------------*/
function getLang($sources)
{
    preg_match('/lang="(.*?)"/', $sources, $matches);

    return $matches[1];
}

function randomArray($arr)
{
	$lent = count($arr);
	$index = rand(0, $lent - 1);
	return isset($arr[$index]) ? $arr[$index] : false;
}

function randomImages(&$images)
{
	$result = [];
	$numbers = range(0, count($images) - 1);
	shuffle($numbers);
	foreach($numbers as $key => $value)
	{
		$result[] = $images[$value];
		unset($images[$value]);
		if($key == 1)
		{
			break;
		}
	}
	$images = array_values($images);
	return $result;
}

function endSession($response, $driver, $email)
{
	$response[] = [
		'status' => 'success',
		'msg' => $email.' - Kết thúc'
	];
	$driver->quit();
	echo json_encode($response);
	die;
}