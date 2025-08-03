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

    public static function create($username, $password)
    {
        $pdo = \Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        return $stmt->execute([$username, $hash]);
    }
}
