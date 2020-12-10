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
$input_values['email'] = 'lehuuduc114114@gmail.com';
$input_values['pass'] = 'lehuuduc';

$driver->findElement(WebDriverBy::id('email'))
    ->sendKeys($input_values['email']);
$driver->findElement(WebDriverBy::id('pass'))
    ->sendKeys($input_values['pass'])->submit();
try {
	$driver->wait(3)->until(
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
	$response[] = [
		'status' => 'success',
		'msg' => $input_values['email'].' - Kết thúc'
	];
	$driver->quit();
	echo json_encode($response);
	die;
}

/*-------------------------------------------URL Message-----------------------------------------------*/
$driver->get('https://www.facebook.com/messages');
$html = $driver->findElement(WebDriverBy::cssSelector('html'));
echo '<html id="facebook" class="_9dls" lang="vi" dir="ltr">';
echo($html->getAttribute('innerHTML'));
echo '</html>';

/*-------------------------------------------Function get language-----------------------------------------------*/
function getLang($sources)
{
    preg_match('/lang="(.*?)"/', $sources, $matches);

    return $matches[1];
}

?>
<script type="text/javascript">
	
</script>