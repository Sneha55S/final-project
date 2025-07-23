<?php

class Logout extends Controller {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // --- DEBUG: Check if headers have already been sent ---
        if (headers_sent($file, $line)) {
            echo "DEBUG: Headers already sent in file $file on line $line. Cannot redirect.<br>";
            // You might want to log this or display a user-friendly message instead of die() in production
            die("Logout failed: Output already started before redirect.");
        }
        // --- END DEBUG ---

        $_SESSION = array(); // Unset all session variables

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy(); // Destroy the session on the server

        header('Location: /login'); // Changed to clean URL
        exit(); // Crucial: Terminate script execution after redirect
    }
}
