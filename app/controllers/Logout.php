<?php

class Logout extends Controller {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (headers_sent($file, $line)) {
            echo "DEBUG: Headers already sent in file $file on line $line. Cannot redirect.<br>";


            die("Logout failed: Output already started before redirect.");
        }
        

        $_SESSION = array(); 

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy(); 


        header('Location:/home/index'); 

        exit(); 
    }
}
