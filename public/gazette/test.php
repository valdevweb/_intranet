Send SMS
Choose your language
PHP
<?php
    // Account details
    $apiKey = urlencode('1DranUz1XdQ-aou9uoC4wkRFchCS0oNyAKaYPUbTZR');

    // Message details
    $numbers = array('0684378456');
    $sender = urlencode('Jims Autos');
    $message = rawurlencode('This is your message');

    $numbers = implode(',', $numbers);

    // Prepare data for POST request
    $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

    // Send the POST request with cURL
    $ch = curl_init('https://api.txtlocal.com/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Process your response here
    echo $response;