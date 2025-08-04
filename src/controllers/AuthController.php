<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public static function login($username, $password)
    {
        $user = User::findByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            SessionManager::regenerate();
            SessionManager::set('user_id', $user['id']);
            return true;
        }
        return false;
    }

    public static function logout()
    {
        SessionManager::destroy();
    }

    public static function check()
    {
        return SessionManager::get('user_id') !== null;
    }
    public static function register($username, $password)
    {
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Username and password cannot be empty.'];
        }
        $validation = User::validateCredentials($username, $password);
        if (!$validation['success']) {
            return $validation;
        }
        if (User::findByUsername($username)) {
            return ['success' => false, 'message' => 'Username already exists.'];
        }

        $created = User::create($username, $password);

        if ($created) {
            return ['success' => true, 'message' => 'Registration successful.'];
        }

        return ['success' => false, 'message' => 'Registration failed.'];
    }
}
