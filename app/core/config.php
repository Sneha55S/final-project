<?php

define('VERSION', '0.7.0');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('APPS', ROOT . DS . 'app');
define('CORE', ROOT . DS . 'core');
define('LIBS', ROOT . DS . 'lib');
define('MODELS', ROOT . DS . 'models');
define('VIEWS', ROOT . DS . 'views');
define('CONTROLLERS', ROOT . DS . 'controllers');
define('LOGS', ROOT . DS . 'logs');	
define('FILES', ROOT . DS. 'files');

// ---------------------  NEW DATABASE TABLE -------------------------
define('DB_HOST',         'movie-ratings-db.c6ty2m6ykntk.us-east-1.rds.amazonaws.com');
define('DB_USER',         'admin'); 
define('DB_PASS',         $_ENV['DB_PASS']);
define('DB_DATABASE',     'movie_ratings_db');
define('DB_PORT',         '3306');