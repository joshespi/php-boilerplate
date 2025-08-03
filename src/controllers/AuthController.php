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
        // Username validation: 3-30 chars, letters, numbers, underscores
        if (!preg_match('/^[a-zA-Z0-9_]{5,50}$/', $username)) {
            return [
                'success' => false,
                'message' => 'Username must be 5-50 characters and contain only letters, numbers, and underscores.'
            ];
        }
        // Password validation: 8-64 chars, at least one letter and one number
        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,64}$/', $password)) {
            return [
                'success' => false,
                'message' => 'Password must be 8-64 characters and contain at least one letter and one number.'
            ];
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
