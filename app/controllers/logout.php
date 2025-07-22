<?php

class Logout extends Controller {

    public function index() {
        // --- DEBUG START ---
        echo "DEBUG: Logout controller reached.<br>";
        echo "DEBUG: Session status BEFORE start: " . session_status() . "<br>"; // 0=NONE, 1=ACTIVE, 2=DISABLED
        // --- DEBUG END ---

        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Ensure session is started if it's not already
        }

        // --- DEBUG START ---
        echo "DEBUG: Session status AFTER start: " . session_status() . "<br>";
        echo "DEBUG: Session auth BEFORE unset: " . ($_SESSION['auth'] ?? 'Not Set') . "<br>";
        echo "DEBUG: Session username BEFORE unset: " . ($_SESSION['username'] ?? 'Not Set') . "<br>";
        // --- DEBUG END ---

        $_SESSION = array(); // Unset all session variables

        // --- DEBUG START ---
        echo "DEBUG: Session auth AFTER unset: " . ($_SESSION['auth'] ?? 'Not Set') . "<br>";
        echo "DEBUG: Session username AFTER unset: " . ($_SESSION['username'] ?? 'Not Set') . "<br>";
        // --- DEBUG END ---

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy(); // Destroy the session on the server

        // --- DEBUG START ---
        echo "DEBUG: Session destroyed. Attempting redirect.<br>";
        // --- DEBUG END ---

        header('Location: /login'); // Redirect to the login page
        exit(); // Crucial: Terminate script execution after redirect
    }
}
