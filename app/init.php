<?php

// Ensure error reporting is on for development, turn off for production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session at the very beginning
// It's good practice to ensure session_start() is called only once per request.
// If you have session_start() in other files (like Logout.php), you might need conditional checks.
// For now, let's keep it here as the primary session start.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set session cookie parameters
ini_set('session.gc_maxlifetime', 28800); // 8 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
$sessionCookieExpireTime = 28800; // 8hrs
session_set_cookie_params($sessionCookieExpireTime);


// Load Composer's autoloader for phpdotenv (assuming vendor/autoload.php is at project root)
// This path is relative to init.php (which is in 'app/')
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables from .env file (assuming .env is at project root)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Go up one directory from 'app/' to project root
$dotenv->load();

// Include core application files
// These paths are relative to init.php (which is in 'app/')
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/config.php'; // config.php defines ROOT, so it should be loaded before database.php uses DB_HOST etc.

// CORRECT PATH FOR database.php:
// database.php is in the project root, so it's one directory up from 'app/'
require_once __DIR__ . '/../database.php';

?>
