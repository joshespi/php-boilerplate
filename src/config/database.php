<?php

class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            $host = $_ENV['DB_HOST'] ?: 'localhost';
            $db   = $_ENV['DB_NAME'] ?: 'chore_tracker';
            $user = $_ENV['DB_USER'] ?: 'placebo';
            $pass = $_ENV['DB_PASS'] ?: '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$pdo;
    }
}
// Usage example
// $db = Database::getConnection();
// $stmt = $db->query('SELECT * FROM chores');
// $chores = $stmt->fetchAll();
// print_r($chores);
