<?php

define('VERSION', '0.7.0');

define('DS', DIRECTORY_SEPARATOR);

// CORRECT ROOT DEFINITION:
// If config.php is in 'app/core/', then dirname(__DIR__, 2) goes up two levels to the project root.
define('ROOT', dirname(__DIR__, 2)); 

// Define paths relative to the corrected ROOT
define('APPS', ROOT . DS . 'app');
define('CORE', APPS . DS . 'core'); // Consistent with APPS
define('LIBS', ROOT . DS . 'lib'); // Assuming 'lib' is at project root
define('MODELS', APPS . DS . 'models'); // Consistent with APPS
define('VIEWS', APPS . DS . 'views'); // Consistent with APPS
define('CONTROLLERS', APPS . DS . 'controllers'); // Consistent with APPS
define('LOGS', ROOT . DS . 'logs'); // Assuming 'logs' is at project root
define('FILES', ROOT . DS. 'files'); // Assuming 'files' is at project root

// --------------------- DATABASE CONFIGURATION -------------------------
// Pull ALL database credentials from environment variables (Replit secrets)
define('DB_HOST',         $_ENV['DB_HOST']);
define('DB_USER',         $_ENV['DB_USER']);
define('DB_PASS',         $_ENV['DB_PASS']);
define('DB_DATABASE',     $_ENV['DB_NAME']); // Use DB_NAME as defined in Replit secrets
define('DB_PORT',         $_ENV['DB_PORT']);

// Ensure OMDB and Gemini API keys are also pulled from environment variables
// (These are used in app/models/Api.php)
if (!defined('OMDB_KEY')) { // Check if already defined to avoid redeclaration if included elsewhere
    define('OMDB_KEY', $_ENV['OMDB_KEY'] ?? null); // Use null coalescing for safety
}
if (!defined('GEMINI_KEY')) {
    define('GEMINI_KEY', $_ENV['GEMINI_KEY'] ?? null);
}

// You can also define your API keys here if you prefer, but it's generally better
// to access $_ENV directly in the models/controllers that use them,
// or define them here if they are truly global constants.
// For now, I'll add them as constants for completeness, assuming they are in $_ENV.

