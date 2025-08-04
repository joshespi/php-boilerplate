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
    public function testDuplicateUsernameRegistration()
    {
        $userModel = new User();
        $userModel->create('uniqueuser', 'password1');
        $result = $userModel->create('uniqueuser', 'password2');
        $this->assertFalse($result, 'Should not allow duplicate usernames');
    }
    public function testFindByUsernameReturnsNullForNonExistentUser()
    {
        $userModel = new User();
        $result = $userModel->findByUsername('no_such_user');
        $this->assertFalse($result);
    }
    public function testCreateWithEmptyUsernameFails()
    {
        $userModel = new User();
        $result = $userModel->create('', 'password');
        $this->assertFalse($result);
    }
    public function testCreateWithInvalidUsernameFails()
    {
        $userModel = new User();
        $this->assertFalse($userModel->create('ab', 'Password123'));
        $this->assertFalse($userModel->create('user!@#$', 'Password123'));
        $this->assertFalse($userModel->create(str_repeat('a', 51), 'Password123'));
    }
    public function testCreateWithWeakPasswordFails()
    {
        $userModel = new User();
        $this->assertFalse($userModel->create('validuser', 'short'));
        $this->assertFalse($userModel->create('validuser', 'allletters'));
        $this->assertFalse($userModel->create('validuser', '12345678'));
    }
    public function testCreateWithSqlInjectionUsernameFails()
    {
        $userModel = new User();
        $this->assertFalse($userModel->create("test'; DROP TABLE users; --", 'Password123'));
    }
    public function testCreateWithSqlInjectionPasswordFails()
    {
        $userModel = new User();
        $this->assertFalse($userModel->create('validuser', "password'; DROP TABLE users; --"));
    }
}
