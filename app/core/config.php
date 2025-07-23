<?php

// Define constants for paths to make includes easier and more robust
define('DS', DIRECTORY_SEPARATOR); // Directory Separator
define('ROOT', dirname(dirname(__DIR__))); // Project root directory
define('APPS', ROOT . DS . 'app'); // App directory
define('CORE', APPS . DS . 'core'); // Core directory
define('CONTROLLERS', APPS . DS . 'controllers'); // Controllers directory
define('MODELS', APPS . DS . 'models'); // Models directory
define('VIEWS', APPS . DS . 'views'); // Views directory

// You can add other configurations here if needed
