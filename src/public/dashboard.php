<?php
require_once __DIR__ . '/start.php';

use App\Controllers\AuthController;

if (!AuthController::check()) {
    header('Location: /');
    exit;
}
render('dashboard', [
    'title' => 'Dashboard'
]);
