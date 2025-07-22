<?php

class User {
    // ... (existing properties and test method)

    public function authenticate($username, $password) {
        $username = strtolower($username);
        $db = db_connect();
        $statement = $db->prepare("select * from users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $rows['password'])) {
            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            unset($_SESSION['failedAuth']);
            echo "DEBUG: User.php redirecting to /home on successful login.<br>";
            // header('Location: /home'); // Temporarily comment out
            // die; // Temporarily comment out
        } else {
            if(isset($_SESSION['failedAuth'])) {
                $_SESSION['failedAuth'] ++;
            } else {
                $_SESSION['failedAuth'] = 1;
            }
            echo "DEBUG: User.php redirecting to /login on failed login.<br>";
            // header('Location: /login'); // Temporarily comment out
            // die; // Temporarily comment out
        }
    }
}
