<?php

require_once 'Auth.php';

$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $token = $matches[1];
    $pdo = new PDO("mysql:host=localhost;dbname=ma_base", "root", "");
    $auth = new Auth($pdo);
    $user = $auth->validateJWT($token);

    if ($user) {
        http_response_code(200);
        exit;
    }
}

http_response_code(401);
exit;
