<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public static function login($username, $password)
    {
        $user = User::findByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
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
        if (User::findByUsername($username)) {
            return ['success' => false, 'message' => 'Username already exists.'];
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $created = User::create($username, $hash);
        if ($created) {
            return ['success' => true, 'message' => 'Registration successful.'];
        }
        return ['success' => false, 'message' => 'Registration failed.'];
    }
}
