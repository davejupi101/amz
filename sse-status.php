<?php
// sse-status.php - Server-Sent Events endpoint for status monitoring
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Cache-Control');

// Prevent script timeout and buffering issues (important for XAMPP)
set_time_limit(0);
ignore_user_abort(false);
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);

// Disable Apache's output buffering
if (function_exists('apache_setenv')) {
    apache_setenv('no-gzip', '1');
}

// Get user IP from query parameter
$userIp = $_GET['ip'] ?? '';
if (empty($userIp)) {
    echo "event: error\n";
    echo "data: " . json_encode(['error' => 'No IP provided']) . "\n\n";
    exit;
}

$statusFile = 'panel/data/status.json';
$lastModified = 0;
$lastStatus = null;  // Start with null to detect initial state

// Send initial connection event
echo "event: connected\n";
echo "data: " . json_encode(['message' => 'SSE connection established', 'ip' => $userIp]) . "\n\n";
if (ob_get_level()) ob_end_flush();
flush();

while (true) {
    // Check if client disconnected
    if (connection_aborted()) {
        error_log("SSE: Client disconnected for IP: $userIp");
        break;
    }

    // Check if status file exists
    if (!file_exists($statusFile)) {
        echo "event: error\n";
        echo "data: " . json_encode(['error' => 'Status file not found']) . "\n\n";
        if (ob_get_level()) ob_end_flush();
        flush();
        sleep(1);
        continue;
    }

    // Clear file stat cache to ensure we get fresh file modification time
    clearstatcache(true, $statusFile);
    $currentModified = filemtime($statusFile);
    
    // Process if file was modified OR this is the first check
    if ($currentModified > $lastModified || $lastStatus === null) {
        $statusContent = file_get_contents($statusFile);
        $statusJson = json_decode($statusContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "event: error\n";
            echo "data: " . json_encode(['error' => 'Invalid JSON in status file']) . "\n\n";
            if (ob_get_level()) ob_end_flush();
            flush();
            sleep(1);
            continue;
        }
        
        $currentStatus = 'default';
        
        if (isset($statusJson[$userIp])) {
            $currentStatus = $statusJson[$userIp]['Status'] ?? 'default';
        }
        
        // Send status change if this is first check OR if status actually changed
        if ($lastStatus === null || $currentStatus !== $lastStatus) {
            $response = [
                'status' => $currentStatus,
                'timestamp' => time(),
                'ip' => $userIp,
                'file_modified' => $currentModified,
                'previous_status' => $lastStatus
            ];
            
            echo "event: statusChange\n";
            echo "data: " . json_encode($response) . "\n\n";
            if (ob_get_level()) ob_end_flush();
            flush();
            
            error_log("SSE: Status change sent for IP $userIp - Status: $lastStatus -> $currentStatus");
            
            $lastStatus = $currentStatus;
        }
        
        $lastModified = $currentModified;
    }
    
    // Send heartbeat every 30 seconds
    static $lastHeartbeat = 0;
    if (time() - $lastHeartbeat > 30) {
        echo "event: heartbeat\n";
        echo "data: " . json_encode(['timestamp' => time(), 'ip' => $userIp]) . "\n\n";
        if (ob_get_level()) ob_end_flush();
        flush();
        $lastHeartbeat = time();
    }
    
    // Sleep for 1 second before next check
    sleep(1);
}
?>