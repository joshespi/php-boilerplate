<?php
require_once __DIR__ . '/start.php';

use App\Controllers\AuthController;
use App\Controllers\SessionManager;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!SessionManager::validateCsrfToken($csrfToken)) {
        echo "Invalid CSRF token.";
        exit;
    }

    // Input validation & sanitization
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!preg_match('/^[a-zA-Z0-9_]{5,50}$/', $username)) {
        echo "Username must be 5-50 characters and contain only letters, numbers, and underscores. <a href=\"register.php\">Try again</a>.";
        exit;
    }
    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters. <a href=\"register.php\">Try again</a>.";
        exit;
    }
    $result = AuthController::register($username, $password);
    if ($result['success']) {
        echo "Registration successful. <a href=\"/\">Login here</a>.";
    } else {
        echo $result['message'] . ' <a href="/register.php">Try again</a>.';
    }
    exit;
}
$csrfToken = SessionManager::generateCsrfToken();
render('register', [
    'csrfToken' => $csrfToken,
    'error' => $error,
    'flash' => $success,
    'title' => 'Register'
]);
