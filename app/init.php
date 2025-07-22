<?php // This must be the absolute first thing in the file, no character before it!

// 1. Error Reporting (for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Set Session INI settings and cookie params BEFORE session_start()
ini_set('session.gc_maxlifetime', 28800); // 8 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
$sessionCookieExpireTime = 28800; // 8hrs
session_set_cookie_params($sessionCookieExpireTime);

// 3. Start the session (only if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. Load Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// 5. Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 6. Include core application files
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/config.php';
require_once __DIR__ . '/../database.php'; // Corrected path to database.php
