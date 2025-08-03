<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\SessionManager;

class SessionManagerTest extends TestCase
{
    public function testSessionSanity()
    {
        // Set some session data
        $_SESSION['test'] = 'value';

        // Check if session data is set correctly
        $this->assertEquals('value', $_SESSION['test']);

        // Destroy the session
        SessionManager::destroy();

        // Check if session is destroyed
        $this->assertEmpty($_SESSION);
    }
    public function testCsrfTokenGeneration()
    {
        $token = SessionManager::generateCsrfToken();

        $this->assertNotEmpty($token);
        $this->assertIsString($token);
        $this->assertEquals(64, strlen($token)); // Default length for CSRF token
    }
    public function testCsrfTokenValidation()
    {
        $token = SessionManager::generateCsrfToken();
        $_SESSION['csrf_token'] = $token;

        $this->assertTrue(SessionManager::validateCsrfToken($token));
        $this->assertFalse(SessionManager::validateCsrfToken('invalid_token'));
    }
    /**
     * @runInSeparateProcess
     */
    public function testSessionRegenerate()
    {
        SessionManager::start();
        $oldId = session_id();
        SessionManager::regenerate();
        $newId = session_id();

        $this->assertNotEquals($oldId, $newId);
    }
}
