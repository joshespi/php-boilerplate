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

    public static function create($username, $passwordHash)
    {
        $pdo = \Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        return $stmt->execute([$username, $passwordHash]);
    }
}
