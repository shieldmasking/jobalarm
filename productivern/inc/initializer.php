<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
    session_start();
}
ini_set('display_errors',0);
define('IS_QBL_APP', isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'qbl.') === 0);
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
