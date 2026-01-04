<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Simple server status check
$status = [
    'server_time' => date('Y-m-d H:i:s'),
    'php_version' => phpversion(),
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'memory_usage' => memory_get_usage(true),
    'memory_limit' => ini_get('memory_limit'),
    'max_execution_time' => ini_get('max_execution_time'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'session_status' => session_status(),
    'extensions' => [
        'curl' => extension_loaded('curl'),
        'json' => extension_loaded('json'),
        'mbstring' => extension_loaded('mbstring'),
        'openssl' => extension_loaded('openssl')
    ],
    'files' => [
        'orders_log' => file_exists('api/orders_log.txt'),
        'contact_log' => file_exists('api/contact_log.txt'),
        'api_writable' => is_writable('api/')
    ],
    'disk_space' => [
        'free' => disk_free_space('.'),
        'total' => disk_total_space('.')
    ]
];

echo json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>