<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {

    }

    // Removed the 'test()' method as it was for debugging.

    public function authenticate($username, $password) {
        $username = strtolower($username);
        $db = db_connect(); // Ensure db_connect() is available and working
        $statement = $db->prepare("select * from users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        if ($rows && password_verify($password, $rows['password'])) { // Added $rows check for robustness
            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            unset($_SESSION['failedAuth']);
            header('Location: /index.php?url=home'); // Re-enabled redirect
            die; // Re-enabled die
        } else {
            if(isset($_SESSION['failedAuth'])) {
                $_SESSION['failedAuth'] ++;
            } else {
                $_SESSION['failedAuth'] = 1;
            }
            header('Location: /index.php?url=login'); // Re-enabled redirect
            die; // Re-enabled die
        }
    }
}
