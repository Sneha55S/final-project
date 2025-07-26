<?php 


error_reporting(E_ALL);
ini_set('display_errors', 1);


ini_set('session.gc_maxlifetime', 28800); 
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
$sessionCookieExpireTime = 28800; 


session_set_cookie_params([
    'lifetime' => $sessionCookieExpireTime,
    'path' => '/',
    'domain' => '', 
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', 
    'httponly' => true, 
    'samesite' => 'Lax' 
]);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


require_once __DIR__ . '/core/config.php';
require_once APPS . DS . 'core' . DS . 'App.php';
require_once APPS . DS . 'core' . DS . 'Controller.php';
require_once ROOT . DS . 'database.php'; 
