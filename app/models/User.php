<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {

    }

    public function authenticate($username, $password) {
        $username = strtolower($username);
        $db = db_connect();
        $statement = $db->prepare("select * from users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        if ($rows && password_verify($password, $rows['password'])) {
            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            unset($_SESSION['failedAuth']);
            header('Location: /home');
            die;
        } else {
            if(isset($_SESSION['failedAuth'])) {
                $_SESSION['failedAuth'] ++;
            } else {
                $_SESSION['failedAuth'] = 1;
            }
            $_SESSION['register_message'] = ['type' => 'error', 'text' => 'Invalid username or password.']; // Use this for login errors too
            header('Location: /login');
            die;
        }
    }

    public function create($username, $password) {
        $username = strtolower($username);
        $db = db_connect();

        // Check if username already exists
        $statement = $db->prepare("SELECT id FROM users WHERE username = :username");
        $statement->bindValue(':username', $username);
        $statement->execute();
        if ($statement->fetch(PDO::FETCH_ASSOC)) {
            return 'exists'; // Indicate that username already exists
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $statement = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $statement->bindValue(':username', $username);
            $statement->bindValue(':password', $hashed_password);
            return $statement->execute(); // Returns true on success, false on failure
        } catch (PDOException $e) {
            error_log("Database error during user registration: " . $e->getMessage());
            return false;
        }
    }
}
