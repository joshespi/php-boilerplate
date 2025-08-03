<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use App\Controllers\SessionManager;
use App\Controllers\AuthController;

SessionManager::start();

if (!AuthController::check()) {
    header('Location: /');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <h2>Welcome to your dashboard!</h2>
    <p><a href="logout.php">Logout</a></p>
</body>

</html>