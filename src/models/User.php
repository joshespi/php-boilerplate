<?php

namespace App\Models;

use PDO;

class User
{
    public static function findByUsername($username)
    {
        $pdo = \Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function validateCredentials($username, $password)
    {
        if (!preg_match('/^[a-zA-Z0-9_]{5,50}$/', $username)) {
            return ['success' => false, 'message' => 'Username must be 5-50 characters and contain only letters, numbers, and underscores.'];
        }
        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d).{8,64}$/', $password)) {
            return ['success' => false, 'message' => 'Password must be 8-64 characters and contain at least one letter and one number.'];
        }
        return ['success' => true];
    }
    public static function create($username, $password)
    {
        if (empty($username) || empty($password)) {
            return false;
        }
        $validation = self::validateCredentials($username, $password);
        if (!$validation['success']) {
            return false;
        }
        $pdo = \Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        return $stmt->execute([$username, $hash]);
    }
}
