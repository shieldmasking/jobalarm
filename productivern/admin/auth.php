<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name('PRNADMIN');
    session_set_cookie_params(['lifetime' => 0, 'path' => '/admin/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
    session_start();
}
if (empty($_SESSION['platform_admin'])) {
    header('location: /admin/login.php');
    exit();
}
