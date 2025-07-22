<?php

/* database connection stuff here
 *
 */

function db_connect() {
    try {
        $dbh = new PDO('mysql:host=' . DB_HOST . ';port='. DB_PORT . ';dbname=' . DB_DATABASE, DB_USER, $_ENV['DB_PASS']);
        // Set PDO attributes for error handling and default fetch mode
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Crucial for catching errors
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Fetch rows as associative arrays by default
        return $dbh;
    } catch (PDOException $e) {
        // Log the error message (for production, use a proper logging system)
        error_log("Database connection error: " . $e->getMessage());

        // Display a user-friendly message (for development/debugging)
        echo "<h1>Database Connection Error</h1>";
        echo "<p>A database connection error occurred. Please try again later.</p>";
        // Optionally, for debugging, you can show the detailed error:
        // echo "<p>Details: " . $e->getMessage() . "</p>";

        // This line was for debugging connection string, keep it commented or remove
        // echo 'mysql:host=' . DB_HOST . ';port='. DB_PORT . ';dbname=' . DB_DATABASE, DB_USER, $_ENV['DB_PASS'];
        // echo "</br>";
        // echo "mysql://4806_learnscene:f0bfa9aa7f85f9473743c8d50f397e9c9ef98365@ybur6.h.filess.io:3307/4806_learnscene";

        // Terminate script execution if database connection fails
        die("Fatal Error: Could not connect to the database."); 
    }
}
