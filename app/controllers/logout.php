<?php

class Logout extends Controller {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array(); // Unset all session variables

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy(); // Destroy the session on the server

        header('Location: /login'); // Redirect to the login page
        exit(); // Crucial: Terminate script execution after redirect
    }
}
