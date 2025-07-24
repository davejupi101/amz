<?php
// checkStatus.php
header('Content-Type: application/json');

$userIp = $_POST['ip'] ?? '';
if (empty($userIp)) {
    die(json_encode(['status' => 'default', 'mobile' => '']));
}

$statusJson = json_decode(file_get_contents('panel/data/status.json'), true);
if (!isset($statusJson[$userIp])) {
    die(json_encode(['status' => 'default', 'mobile' => '']));
}

$response = [
    'status' => $statusJson[$userIp]['Status'],
    'mobile' => $statusJson[$userIp]['Mobile'] ?? ''
];

die(json_encode($response));
?>