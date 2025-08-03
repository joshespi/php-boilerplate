<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use App\Controllers\AuthController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $result = AuthController::register($username, $password);
    if ($result['success']) {
        echo "Registration successful. <a href=\"/\">Login here</a>.";
    } else {
        echo $result['message'] . ' <a href="/register.php">Try again</a>.';
    }
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
</head>

<body>
    <h2>Register</h2>
    <form method="post" action="register.php">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit">Register</button>
    </form>
</body>

</html>