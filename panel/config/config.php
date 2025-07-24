<?php
session_start();
$configFilePath = 'config.json';

if (file_exists($configFilePath) && is_readable($configFilePath)) {

    $existingConfig = json_decode(file_get_contents($configFilePath), true);
} else {

    $existingConfig = array();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $newConfigData = array();

    if (isset($_POST['telegramBotToken']) && !empty($_POST['telegramBotToken'])) {
        $newConfigData["telegramBotToken"] = $_POST['telegramBotToken'];
    }

    if (isset($_POST['telegramChatId']) && !empty($_POST['telegramChatId'])) {
        $newConfigData["telegramChatId"] = $_POST['telegramChatId'];
    }

    $mergedConfigData = array_merge($existingConfig ?? array(), $newConfigData);

    $jsonData = json_encode($mergedConfigData, JSON_PRETTY_PRINT);

    file_put_contents($configFilePath, $jsonData);

    $_SESSION['config_success'] = true;

    echo '<script>
        window.opener.configModal.hide();
        window.opener.document.getElementById("success-message").style.display = "block";
    </script>';
    exit;
}
?>