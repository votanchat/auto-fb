<?php

require 'vendor/autoload.php';
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

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

/*-------------------------------------------Host-----------------------------------------------*/
$host = 'http://localhost:4444';
$driver = RemoteWebDriver::createBySessionID($input_values['session'], $host);

$response[] = [
	'status' => 'success',
	'msg' => $input_values['email'].' - '.$input_values['location'].' - Bắt đầu'
];

/*-------------------------------------------Login-----------------------------------------------*/
$driver->manage()->deleteAllCookies();
$driver->get('https://facebook.com');
$cookies = [];
if(file_exists('cookie_fb/'.$input_values['email'].'.txt'))
{
	$cookies = file_get_contents('cookie_fb/'.$input_values['email'].'.txt');
	$cookies = unserialize($cookies);
}
if(is_array($cookies))
{
	foreach($cookies as $key => $cookie)
	{
		$driver->manage()->addCookie($cookie);
	}	
}
$driver->get('https://facebook.com');
try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.l9j0dhe7.tr9rh885.buofh1pr.cbu4d94t.j83agx80'))
	);
	$response[] = [
		'status' => 'success',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Đăng nhập thành công'
	];
} catch (Exception $e) {
	$file_name = 'sts_fb/'.$input_values['email'].'.txt';
	file_put_contents($file_name, 'fail');
	$response[] = [
		'status' => 'login_fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Đăng nhập thất bại'
	];
	endSession($response, $input_values);
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
		'msg' =>  $input_values['email'].' - '.$input_values['location'].' - Tài khoản sử dụng ngôn ngữ khác.'
	];
	endSession($response, $input_values);
}

/*-------------------------------------------URL Marketplace-----------------------------------------------*/
$driver->get('https://www.facebook.com/marketplace/create/item');
sleep(3);

/*-------------------------------------------Check ability publish-----------------------------------------------*/
try {
	$driver->wait(3)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.rq0escxv.l9j0dhe7.du4w35lb.j83agx80.pfnyh3mw.i1fnvgqd.gs1a9yip.owycx6da.btwxx1t3.d1544ag0.tw6a2znq.f10w8fjw.pybr56ya.b5q2rw42.lq239pai.mysgfdmx.hddg9phg'))
	);
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Không thể đăng thêm bài'
	];
	endSession($response, $input_values);
} catch (Exception $e) {
	
}

/*-------------------------------------------Enter input-----------------------------------------------*/

/*-------------------------------------------Input Image-----------------------------------------------*/
try {
	$driver->wait(3)->until(
		WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['image']))
	);
	$fileInput = $driver->findElement(WebDriverBy::cssSelector($input_names['image']));
} catch (Exception $e) {
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - Không tìm thấy chỗ đăng ảnh.'
	];
	endSession($response, $input_values);
}
$images = $input_values['images'];
if(empty($images) || count($images) < $input_values['number_image'])
{
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Số lượng ảnh không đủ'
	];
	endSession($response, $input_values);
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
			'msg' => $input_values['email'].' - '.$input_values['location'].' - Không tìm thấy hình ảnh'
		];
		endSession($response, $input_values);
	}
}

/*-------------------------------------------Input Title-----------------------------------------------*/
try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['title']))
	);
	$title = randomArray($input_values['titles1']).' '.randomArray($input_values['titles2']).', '.$input_values['location'];
	$driver->findElement(WebDriverBy::cssSelector($input_names['title']))->click()->sendKeys($title);
} catch (Exception $e) {
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Không tìm thấy tiêu đề'
	];
	endSession($response, $input_values);
}

/*-------------------------------------------Input Price-----------------------------------------------*/
try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['price']))
	);
	$driver->findElement(WebDriverBy::cssSelector($input_names['price']))->click()->sendKeys($input_values['price']);
} catch (Exception $e) {
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Không tìm thấy giá'
	];
	endSession($response, $input_values);
}

/*-------------------------------------------Input Category-----------------------------------------------*/
try {
	$driver->findElement(WebDriverBy::cssSelector($input_names['category']))->click();
	$driver->wait(3)->until(
		WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.oajrlxb2.gs1a9yip.g5ia77u1.mtkw9kbi.tlpljxtp.qensuy8j.ppp5ayq2.goun2846.ccm00jje.s44p3ltw.mk2mc5f4.rt8b4zig.n8ej3o3l.agehan2d.sk4xxmp2.rq0escxv.nhd2j8a9.a8c37x1j.mg4g778l.btwxx1t3.pfnyh3mw.p7hjln8o.kvgmc6g5.cxmmr5t8.oygrvhab.hcukyx3x.tgvbjcpo.hpfvmrgz.jb3vyjys.rz4wbd8a.qt6c0cv9.a8nywdso.l9j0dhe7.i1ao9s8h.esuyzwwr.f1sip0of.du4w35lb.lzcic4wl.abiwlrkh.p8dawk7l.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi[role="button"]'))
	);
	$input_categories = $driver->findElements(WebDriverBy::cssSelector('.oajrlxb2.gs1a9yip.g5ia77u1.mtkw9kbi.tlpljxtp.qensuy8j.ppp5ayq2.goun2846.ccm00jje.s44p3ltw.mk2mc5f4.rt8b4zig.n8ej3o3l.agehan2d.sk4xxmp2.rq0escxv.nhd2j8a9.a8c37x1j.mg4g778l.btwxx1t3.pfnyh3mw.p7hjln8o.kvgmc6g5.cxmmr5t8.oygrvhab.hcukyx3x.tgvbjcpo.hpfvmrgz.jb3vyjys.rz4wbd8a.qt6c0cv9.a8nywdso.l9j0dhe7.i1ao9s8h.esuyzwwr.f1sip0of.du4w35lb.lzcic4wl.abiwlrkh.p8dawk7l.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi[role="button"]'));
	$input_categories[$input_values['category']]->click();
	
} catch (Exception $e) {
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Không tìm thấy hạng mục'
	];
	endSession($response, $input_values);
}

/*-------------------------------------------Input Condition-----------------------------------------------*/
try {
	$driver->wait(2)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('[aria-label="Condition"]'))
	);
	$driver->findElement(WebDriverBy::cssSelector('[aria-label="Condition"]'))->click();
	$driver->wait(2)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.oajrlxb2.g5ia77u1.qu0x051f.esr5mh6w.e9989ue4.r7d6kgcz.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.oi9244e8.oygrvhab.h676nmdw.pybr56ya.dflh9lhu.f10w8fjw.scb9dxdr.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.bp9cbjyn.dwo3fsh8.btwxx1t3.pfnyh3mw.du4w35lb'))
	);
	$input_conditions = $driver->findElements(WebDriverBy::cssSelector('.oajrlxb2.g5ia77u1.qu0x051f.esr5mh6w.e9989ue4.r7d6kgcz.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.oi9244e8.oygrvhab.h676nmdw.pybr56ya.dflh9lhu.f10w8fjw.scb9dxdr.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.bp9cbjyn.dwo3fsh8.btwxx1t3.pfnyh3mw.du4w35lb'));
	$input_conditions[$input_values['condition']]->click();
} catch (Exception $e) {
	
}

try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['condition']))
	);
	$driver->findElement(WebDriverBy::cssSelector($input_names['condition']))->click();
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.oajrlxb2.g5ia77u1.qu0x051f.esr5mh6w.e9989ue4.r7d6kgcz.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.oi9244e8.oygrvhab.h676nmdw.pybr56ya.dflh9lhu.f10w8fjw.scb9dxdr.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.bp9cbjyn.dwo3fsh8.btwxx1t3.pfnyh3mw.du4w35lb'))
	);
	$input_conditions = $driver->findElements(WebDriverBy::cssSelector('.oajrlxb2.g5ia77u1.qu0x051f.esr5mh6w.e9989ue4.r7d6kgcz.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.oi9244e8.oygrvhab.h676nmdw.pybr56ya.dflh9lhu.f10w8fjw.scb9dxdr.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.bp9cbjyn.dwo3fsh8.btwxx1t3.pfnyh3mw.du4w35lb'));
	$input_conditions[$input_values['condition']]->click();
} catch (Exception $e) {
	
}


/*-------------------------------------------Input Brand-----------------------------------------------*/
try {
	$driver->wait(5)->until(
		WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['brand']))
	);
	$driver->findElement(WebDriverBy::cssSelector($input_names['brand']))->click()->sendKeys($input_values['brand']);
} catch (Exception $e) {
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Không tìm thấy thương hiệu'
	];
	endSession($response, $input_values);
}


/*-------------------------------------------Input Description-----------------------------------------------*/
try {
	$driver->wait(5)->until(
		WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector($input_names['description']))
	);
	$description = randomArray($input_values['descriptions1']).' '.randomArray($input_values['descriptions2']);
	$driver->findElement(WebDriverBy::cssSelector($input_names['description']))->click()->sendKeys($description);
} catch (Exception $e) {
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Không tìm thấy mô tả'
	];
	endSession($response, $input_values);
}

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
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Không tìm thấy thẻ tag'
	];
}

/*-------------------------------------------Input Location-----------------------------------------------*/
try {
	$driver->findElement(WebDriverBy::cssSelector($input_names['location']))->click()->sendKeys($input_values['location']);
	$driver->wait(5)->until(
		WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.bp9cbjyn.nhd2j8a9.j83agx80.ni8dbmo4.stjgntxs.l9j0dhe7.dwzzwef6.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi'))
	);
	$input_locations = $driver->findElements(WebDriverBy::cssSelector('.bp9cbjyn.nhd2j8a9.j83agx80.ni8dbmo4.stjgntxs.l9j0dhe7.dwzzwef6.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi'));
	$input_locations[0]->click();
} catch (Exception $e) {
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Không tìm thấy địa điểm'
	];
	endSession($response, $input_values);
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
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Đăng bài thành công'
	];
} catch (Exception $e) {
	$response[] = [
		'status' => 'fail',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Không thể đăng bài vì thiếu nội dung nào đó'
	];
	endSession($response, $input_values);
}


/*-------------------------------------------End process-----------------------------------------------*/
endSession($response, $input_values);

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
	$result =  isset($arr[$index]) ? $arr[$index] : '';
	return $result;
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

function endSession($response, $input_values)
{
	$response[] = [
		'status' => 'success',
		'msg' => $input_values['email'].' - '.$input_values['location'].' - Kết thúc'
	];
	echo json_encode($response);
	die;
}