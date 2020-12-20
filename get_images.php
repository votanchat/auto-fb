<?php
require 'vendor/autoload.php';
$dir = $_POST['path'];
$files = [];
if ($handle = opendir($dir))
{
	while (false !== ($file = readdir($handle)))
	{
		$files[] = $file;
	}
	closedir($handle);
}
foreach ($files as $key => $value)
{
	if (preg_match("/jpg/i", $value)
		|| preg_match("/jpeg/i", $value)
		|| preg_match("/png/i", $value))
	{
	    $files[$key] = $dir.$value;
	}
	else
	{
		unset($files[$key]);
	}
}
$files = array_values($files);
echo json_encode($files);
die;