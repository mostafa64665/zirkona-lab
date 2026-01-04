<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = array(
    'status' => 'OK',
    'timestamp' => date('c'),
    'server' => 'PHP ' . phpversion(),
    'mail_function' => function_exists('mail') ? 'Available' : 'Not Available',
    'json_functions' => function_exists('json_encode') ? 'Available' : 'Not Available'
);

echo json_encode($response, JSON_PRETTY_PRINT);
?>