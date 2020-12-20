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
	'msg' => $email.' - Bắt đầu'
];


$cookies = [];
$driver->get('https://facebook.com');
sleep(2);
$driver->manage()->deleteAllCookies();
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
$driver->get('https://facebook.com');

try {
	$driver->wait(5)->until(
	    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.l9j0dhe7.tr9rh885.buofh1pr.cbu4d94t.j83agx80'))
	);
	$response['message'][] = [
		'status' => 'success',
		'msg' => $email.' - Đăng nhập thành công'
	];
} catch (Exception $e) {
	$file_name = 'sts_fb/'.$email.'.txt';
	file_put_contents($file_name, 'fail');
	$response[] = [
		'status' => 'login_fail',
		'msg' => $email.' - Đăng nhập thất bại'
	];
	endSession($response, $email);
}

$driver->get('https://www.facebook.com/marketplace/you/selling');
sleep(2);
$items = [];
$current = -1;
while ($current < count($items)) {
	$current = count($items);
	$driver->executeScript('window.scrollTo(0,document.body.scrollHeight)');
	sleep(2);
	
	try {
		$driver->wait(5)->until(
		    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.cwj9ozl2.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi.o16s864r.sej5wr8e.m8hsej2k.k4urcfbm.rnsnyeob'))
		);

		$items = $driver->findElements(WebDriverBy::cssSelector('.cwj9ozl2.ue3kfks5.pw54ja7n.uo3d90p7.l82x9zwi.o16s864r.sej5wr8e.m8hsej2k.k4urcfbm.rnsnyeob'));
		
	} catch (Exception $e) {
		$response['message'][] = [
			'status' => 'success',
			'msg' => $email.' - Không tìm thấy bài đăng nào'
		];
		endSession($response, $email);
	}

	try {
		$driver->wait(2)->until(
		    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('[aria-label="Xem thêm"][role="button"]'))
		);

		$btn = $driver->findElement(WebDriverBy::cssSelector('[aria-label="Xem thêm"][role="button"]'))->click();
	} catch (Exception $e) {
		
	}
}
$items_r = [];
foreach ($items as $key => $item) {
	$tmp = ['title' => '', 'price' => '', 'status' => '', 'info' => 'Bài viết đã được niêm yết', 'view' => '', 'delete' => 0, 'renew' => 0, 'renew_text' => ''];
	try {
		$title = $item->findElement(WebDriverBy::cssSelector('.a8c37x1j.ni8dbmo4.stjgntxs.l9j0dhe7.ojkyduve'))->getAttribute('innerText');
		$tmp['title'] = $title;
	} catch (Exception $e) {
		
	}

	try {
		$price = $item->findElement(WebDriverBy::cssSelector('.d2edcug0.hpfvmrgz.qv66sw1b.c1et5uql.rrkovp55.a8c37x1j.keod5gw0.nxhoafnm.aigsh9s9.d3f4x2em.fe6kdd0r.mau55g9w.c8b282yb.iv3no6db.jq4qci2q.a3bd9o3v.knj5qynh.oo9gr5id.hzawbc8m'))->getAttribute('innerText');
		$tmp['price'] = $price;
	} catch (Exception $e) {
		
	}

	try {
		$status = $item->findElements(WebDriverBy::cssSelector('.tvmbv18p'));
		$tmp['status'] = $status[1]->getAttribute('innerText');
		$tmp['view'] = $status[2]->getAttribute('innerText');
	} catch (Exception $e) {
		
	}
	
	try {
		$info = $item->findElement(WebDriverBy::cssSelector('.q66pz984.gfeo3gy3.n3ffmt46'))->getAttribute('innerText');
		$tmp['info'] = $info;
	} catch (Exception $e) {
		
	}

	try {
		$info = $item->findElement(WebDriverBy::cssSelector('.jdix4yx3.gfeo3gy3.n3ffmt46'))->getAttribute('innerText');
		$tmp['info'] = $info;
	} catch (Exception $e) {
		
	}

	try {
		$item_del = $item->findElement(WebDriverBy::cssSelector('.oajrlxb2.tdjehn4e.gcieejh5.bn081pho.humdl8nn.izx4hr6d.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.cxmmr5t8.oygrvhab.hcukyx3x.jb3vyjys.hv4rvrfc.qt6c0cv9.dati1w0a.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.beltcj47.p86d2i9g.aot14ch1.kzx2olss.cbu4d94t.taijpn5t.ni8dbmo4.stjgntxs.k4urcfbm.tv7at329[role="button"]'));
		$tmp['delete'] = 1;

		$driver->executeScript("arguments[0].focus();", [$item_del]);
		$driver->executeScript("arguments[0].click();", [$item_del]);

		$driver->wait(3)->until(
			WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('.oajrlxb2.g5ia77u1.qu0x051f.esr5mh6w.e9989ue4.r7d6kgcz.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.oi9244e8.oygrvhab.h676nmdw.cxgpxx05.dflh9lhu.sj5x9vvc.scb9dxdr.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.bp9cbjyn.dwo3fsh8.btwxx1t3.pfnyh3mw.du4w35lb'))
		);
		try {
			$btn_renew = $driver->findElement(WebDriverBy::cssSelector('.oajrlxb2.g5ia77u1.qu0x051f.esr5mh6w.e9989ue4.r7d6kgcz.rq0escxv.rj84mg9z.j83agx80.p7hjln8o.kvgmc6g5.oi9244e8.oygrvhab.h676nmdw.cxgpxx05.dflh9lhu.sj5x9vvc.scb9dxdr.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.bp9cbjyn.dwo3fsh8.btwxx1t3.pfnyh3mw.du4w35lb'));
			$tmp['renew_text'] = $btn_renew->getAttribute('innerText');
		} catch (Exception $e) {
			$tmp['renew'] = 1;
			$btn_renew = $driver->findElements(WebDriverBy::cssSelector('.oajrlxb2.g5ia77u1.qu0x051f.esr5mh6w.e9989ue4.r7d6kgcz.rq0escxv.nhd2j8a9.j83agx80.p7hjln8o.kvgmc6g5.oi9244e8.oygrvhab.h676nmdw.cxgpxx05.dflh9lhu.sj5x9vvc.scb9dxdr.i1ao9s8h.esuyzwwr.f1sip0of.lzcic4wl.l9j0dhe7.abiwlrkh.p8dawk7l.bp9cbjyn.dwo3fsh8.btwxx1t3.pfnyh3mw.du4w35lb'));
			$tmp['renew_text'] = $btn_renew[0]->getAttribute('innerText');
		}
		$driver->executeScript("arguments[0].focus();", [$item_del]);
		$driver->executeScript("arguments[0].click();", [$item_del]);
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