<?php
// delete.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip'])) {
    $ip = $_POST['ip'];
    $file = 'status.json';
    
    // Read the JSON file
    $data = json_decode(file_get_contents($file), true);
    
    // Delete the record
    if (isset($data[$ip])) {
        unset($data[$ip]);
        
        // Write back to file
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Record not found']);
    }
    exit;
}
?>