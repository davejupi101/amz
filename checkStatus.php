<?php
// checkStatus.php - Legacy status checker (now primarily for SSE fallback)
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Input validation
$userIp = filter_var($_POST['ip'] ?? '', FILTER_VALIDATE_IP);
if (!$userIp) {
    http_response_code(400);
    die(json_encode([
        'error' => 'Invalid IP address', 
        'status' => 'default', 
        'mobile' => '', 
        'timestamp' => time()
    ]));
}

$statusFile = 'panel/data/status.json';
if (!file_exists($statusFile)) {
    http_response_code(404);
    die(json_encode([
        'error' => 'Status file not found',
        'status' => 'default', 
        'mobile' => '', 
        'timestamp' => time()
    ]));
}

$statusJson = json_decode(file_get_contents($statusFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    die(json_encode([
        'error' => 'Invalid JSON in status file',
        'status' => 'default', 
        'mobile' => '', 
        'timestamp' => time()
    ]));
}

if (!isset($statusJson[$userIp])) {
    die(json_encode([
        'status' => 'default', 
        'mobile' => '', 
        'timestamp' => time(),
        'file_modified' => filemtime($statusFile)
    ]));
}

$response = [
    'status' => $statusJson[$userIp]['Status'] ?? 'default',
    'mobile' => $statusJson[$userIp]['Mobile'] ?? '',
    'timestamp' => time(),
    'file_modified' => filemtime($statusFile)
];

die(json_encode($response));
?>