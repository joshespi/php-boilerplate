<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use App\Controllers\AuthController;
use App\Controllers\SessionManager;

SessionManager::start();

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
    if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        echo "Username must be 3-30 characters and contain only letters, numbers, and underscores. <a href=\"register.php\">Try again</a>.";
        exit;
    }
    if (strlen($password) < 6) {
        echo "Password must be at least 6 characters. <a href=\"register.php\">Try again</a>.";
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
?>
<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
</head>

<body>
    <h2>Register</h2>
    <form method="post" action="register.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit">Register</button>
    </form>
</body>

</html>