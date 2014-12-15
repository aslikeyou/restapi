<?php

$config = require_once __DIR__.'/config.php';

$publicHash = $config['email'];
$privateHash = $config['private'];
$content    = uniqid('', true);

$hash = hash_hmac('sha256', $content, $privateHash);

$headers = array(
    'X-Email: '.$publicHash,
    'X-Hash: '.$hash,
    'X-Content: '.$content
);

$ch = curl_init('http://localhost:8000/users');
curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

$result = curl_exec($ch);
curl_close($ch);

echo $result.PHP_EOL;