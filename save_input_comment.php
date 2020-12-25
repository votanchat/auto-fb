<?php
require 'vendor/autoload.php';
$data = $_POST;
$file_name = 'input_comment.txt';
$serialized = serialize($data);
file_put_contents($file_name, $serialized);
$response = ['sts' => 200, 'msg' => 'success'];
echo json_encode($response);
die;