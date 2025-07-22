<?php

/* database connection stuff here
 * 
 */

function db_connect() {
    try { 
        $dbh = new PDO('mysql:host=' . DB_HOST . ';port='. DB_PORT . ';dbname=' . DB_DATABASE, DB_USER, $_ENV['DB_PASS']);
        return $dbh;
    } catch (PDOException $e) {
			echo 'mysql:host=' . DB_HOST . ';port='. DB_PORT . ';dbname=' . DB_DATABASE, DB_USER, $_ENV['DB_PASS'];
			echo "</br>";               
			// echo "mysql://4806_learnscene:f0bfa9aa7f85f9473743c8d50f397e9c9ef98365@ybur6.h.filess.io:3307/4806_learnscene";
        //We should set a global variable here so we know the DB is down
    }
}