<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Simple email test
$to = 'zirkonalab@gmail.com';
$subject = '🧪 Email Test from Zirkona Lab';
$message = 'This is a test email to verify that the email system is working on Hostinger.';
$headers = 'From: zirkonalab@gmail.com' . "\r\n" .
           'Reply-To: zirkonalab@gmail.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

$result = mail($to, $subject, $message, $headers);

echo json_encode([
    'email_sent' => $result,
    'php_version' => phpversion(),
    'mail_function_exists' => function_exists('mail'),
    'server_info' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'timestamp' => date('Y-m-d H:i:s')
]);
?>