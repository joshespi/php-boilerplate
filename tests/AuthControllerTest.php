<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/TestDatabase.php';

use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;

class AuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = []; // Clear session before each test
        Database::reset();
    }
    public function testRegisterNewUser()
    {
        $username = 'testuser_' . uniqid();
        $password = 'password123';

        $result = AuthController::register($username, $password);

        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
        $this->assertEquals('Registration successful.', $result['message']);
    }
    public function testRegisterExisitingUser()
    {
        $username = 'duplicateuser';
        $password = 'password123';

        // First registration should succeed
        AuthController::register($username, $password);
        // Second registration should fail
        $result = AuthController::register($username, $password);

        $this->assertIsArray($result);
        $this->assertFalse($result['success']);
        $this->assertEquals('Username already exists.', $result['message']);
    }
    public function testLoginWithInvalidCredentials()
    {
        $username = 'invaliduser';
        $password = 'validpassword';

        // Register the user first
        AuthController::register($username, $password);

        // Try logging in with wrong password
        $result = AuthController::login($username, 'wrongpassword');

        $this->assertFalse($result);
    }
    public function testLoginWithNonExistentUser()
    {
        $username = 'doesnotexist';
        $password = 'irrelevant';

        $result = AuthController::login($username, $password);

        $this->assertFalse($result);
    }
    public function testRegisterWithInvalidUsername()
    {
        $result = AuthController::register('ab', 'password123');
        $this->assertFalse($result['success']);
    }
    public function testRegisterWithShortPassword()
    {
        $result = AuthController::register('validuser', '123');
        $this->assertFalse($result['success']);
    }
    public function testRegisterWithEmptyFields()
    {
        $result = \App\Controllers\AuthController::register('', '');
        $this->assertFalse($result['success']);
    }
    public function testCheckWithoutLogin()
    {
        $this->assertFalse(AuthController::check());
    }
    public function testLogout()
    {
        $username = 'logoutuser';
        $password = 'logoutpassword';

        // Register and login the user first
        AuthController::register($username, $password);
        AuthController::login($username, $password);

        // Logout
        AuthController::logout();

        // Check if session is destroyed
        $this->assertFalse(AuthController::check());
    }
    // public function testRegisterLoginLogoutSessionManagement()
    // {
    //     $username = 'sessionuser';
    //     $password = 'sessionpassword';

    //     // Register the user first
    //     AuthController::register($username, $password);
    //     AuthController::login($username, $password);

    //     // Check if session is active
    //     $this->assertTrue(AuthController::check());

    //     // Logout and check session again
    //     AuthController::logout();
    //     $this->assertFalse(AuthController::check());
    // }
    // public function testLoginWithValidCredentials()
    // {
    //     $username = 'validuser';
    //     $password = 'validpassword';

    //     // Register the user first
    //     AuthController::register($username, $password);

    //     $result = AuthController::login($username, $password);

    //     $this->assertTrue($result);
    // }
}
