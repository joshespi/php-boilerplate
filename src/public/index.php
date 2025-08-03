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
        $error = "Invalid CSRF token.";
    } else {
        // Input validation & sanitization
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
            $error = "Invalid username format.";
        } else {
            $success = AuthController::login($username, $password);
            if ($success) {
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        }
    }
}
$csrfToken = SessionManager::generateCsrfToken();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <div style="color:red"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="index.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>

</html>