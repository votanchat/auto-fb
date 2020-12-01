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
// $chromeOptions->addArguments(['--headless']);


$capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

$driver = RemoteWebDriver::create($host, $capabilities);
$driver->get('https://facebook.com');
$driver->findElement(WebDriverBy::id('email')) // find search input element
    ->sendKeys('Lehuuduc114114@gmail.com');
$driver->findElement(WebDriverBy::id('pass')) // find search input element
    ->sendKeys('lehuuduc')->submit();
$driver->get('https://www.facebook.com/marketplace/create/item');
$lang = getLang($driver->getPageSource());
$driver->wait()->until(
    WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(WebDriverBy::cssSelector('input[accept^="image"]'))
);
$fileInput = $driver->findElement(WebDriverBy::cssSelector('input[accept^="image"]'));
$fileInput->sendKeys('C:\Users\ITSJ\Pictures\70c6461c88417f44ddb9926577eb3fb4.jpg');
$driver->findElement(WebDriverBy::cssSelector('[aria-label="Tiêu đề"]'))->click()->sendKeys('Test');


function getLang($sources)
{
    preg_match('/lang="(.*?)"/', $sources, $matches);

    return $matches[1];
}