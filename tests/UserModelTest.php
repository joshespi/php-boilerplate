<?php

require_once __DIR__ . '/TestDatabase.php';

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserModelTest extends TestCase
{
    public function testPasswordIsHashedOnRegistration()
    {
        $userModel = new User();
        $plainPassword = 'MySecret123!';
        $userModel->create('testuser', $plainPassword);

        $storedUser = $userModel->findByUsername('testuser');
        $this->assertNotEquals($plainPassword, $storedUser['password']);
        $this->assertTrue(password_verify($plainPassword, $storedUser['password']));
    }
}
