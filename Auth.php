<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth {
    private $db;
    private $secret_key = "ujdezyte@oosklzecvcdeg";

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $this->generateJWT($user['id'], $user['email']);
        }

        return false;
    }

    private function generateJWT($userId, $email) {
        $payload = [
            "iss" => "http://localhost",
            "aud" => "http://localhost",
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "data" => [
                "id" => $userId,
                "email" => $email
            ]
        ];

        return JWT::encode($payload, $this->secret_key, 'HS256');
    }

    public function validateJWT($jwt) {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
            return $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }
}
