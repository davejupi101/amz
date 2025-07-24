<?php
function sendTelegramNotification($message) {
    // Read the config.json file
    $configJson = file_get_contents('panel/config/config.json');
    $config = json_decode($configJson, true);

    // Get the bot token and chat ID from the config
    $botToken = $config['telegramBotToken'];
    $chatId = $config['telegramChatId'];

    // Telegram Bot API URL
    $url = "https://api.telegram.org/bot$botToken/sendMessage";

    // Prepare the payload
    $payload = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    // Create a new cURL resource
    $ch = curl_init();

    // Set the URL and options for the cURL request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check if the request was successful
    if ($response === false) {
        // Handle cURL error
        $error = curl_error($ch);
        curl_close($ch);
        return false;
    }

    // Close the cURL resource
    curl_close($ch);

    // Check the response for success
    $responseData = json_decode($response, true);
    if ($responseData['ok']) {
        return true;
    } else {
        // Handle failed sending (e.g., log error, notify admin)
        return false;
    }
}
?>