<?php

class Database
{
    public static $users = [];

    public static function reset()
    {
        self::$users = [];
    }

    public static function getConnection()
    {
        return new class {
            public function prepare($sql)
            {
                // Pass the SQL to the statement object
                return new class($sql) {
                    private $sql;
                    public function __construct($sql)
                    {
                        $this->sql = $sql;
                    }
                    public function execute($params)
                    {
                        // Simulate INSERT
                        if (stripos($this->sql, 'insert') !== false) {
                            $username = $params[0];
                            $password = $params[1];
                            if (isset(Database::$users[$username])) {
                                return false;
                            }
                            Database::$users[$username] = [
                                'id' => count(Database::$users) + 1,
                                'username' => $username,
                                'password' => $password,
                            ];
                            return true;
                        }
                        // Simulate SELECT
                        if (stripos($this->sql, 'select') !== false) {
                            $username = $params[0];
                            if (isset(Database::$users[$username])) {
                                $this->last_fetch = Database::$users[$username];
                            } else {
                                $this->last_fetch = false;
                            }
                            return true;
                        }
                        return true;
                    }
                    private $last_fetch;
                    public function fetch()
                    {
                        return $this->last_fetch ?? null;
                    }
                };
            }
        };
    }
}
