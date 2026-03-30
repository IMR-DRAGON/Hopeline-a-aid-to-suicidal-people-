<?php
$payload = json_encode([
    'model' => 'LongCat-Flash-Thinking-2601',
    'messages' => [['role' => 'user', 'content' => 'hello']],
    'max_tokens' => 60,
]);

$ch = curl_init('https://api.longcat.chat/openai/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ak_2Bl7ew0UM1SE7pi1zP5bR6z35Ai0K',
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

echo "HTTP CODE: $http_code\n";
echo "ERROR: $err\n";
echo "RESPONSE: $response\n";
?>