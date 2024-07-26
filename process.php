<?php
// process.php
// https://github.com/VolkanSah/GPT-API-Integration-in-HTML-CSS-with-JS-PHP (v2)

// Load settings from config.php
$config = include('config.php');
$api_key = $config['openai_api_key'];

// Function to communicate with GPT
function communicate_with_gpt($message, $api_key) {
    $url = 'https://api.openai.com/v1/chat/completions';
    $data = [
        'model' => 'gpt-4',
        'messages' => [['role' => 'user', 'content' => $message]],
        'max_tokens' => 1000,
        'temperature' => 0.7
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n" .
                         "Authorization: Bearer " . $api_key . "\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        return 'Error communicating with GPT';
    }

    $response = json_decode($result, true);
    if (isset($response['choices']) && count($response['choices']) > 0) {
        return $response['choices'][0]['message']['content'];
    } else {
        return 'No response from GPT';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $response = communicate_with_gpt($message, $api_key);
    echo $response;
}
