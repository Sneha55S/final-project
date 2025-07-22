<?php 

// Correct path to init.php when public/ is the document root
require_once __DIR__ . '/../app/init.php';

// --- TEMPORARY DEBUGGING START ---
echo "Attempting DB Connection...<br>";
try {
    $db = db_connect(); // Attempt to connect to the database
    echo "Database connection successful!<br>";

    // Verify some ENV variables are loaded (optional, but good check)
    echo "DB Host from ENV: " . ($_ENV['DB_HOST'] ?? 'DB_HOST NOT SET') . "<br>";
    echo "OMDB Key from ENV: " . (isset($_ENV['OMDB_KEY']) ? 'OMDB_KEY IS SET' : 'OMDB_KEY NOT SET') . "<br>";
    echo "Gemini Key from ENV: " . (isset($_ENV['GEMINI_KEY']) ? 'GEMINI_KEY IS SET' : 'GEMINI_KEY NOT SET') . "<br>";

} catch (PDOException $e) {
    echo "Database connection FAILED: " . $e->getMessage() . "<br>";
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage() . "<br>";
}

die("Reached end of public/index.php initialization. Check DB status above.");
// --- TEMPORARY DEBUGGING END ---

$app = new App;