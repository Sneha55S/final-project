<?php

class Register extends Controller {

    public function index() {
        // Display the registration form
        $data = ['message' => $_SESSION['register_message'] ?? null];
        unset($_SESSION['register_message']); // Clear message after displaying
        $this->view('register/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            $userModel = $this->model('User');

            // Basic validation
            if (empty($username) || empty($password) || empty($confirm_password)) {
                $_SESSION['register_message'] = ['type' => 'error', 'text' => 'All fields are required.'];
                header('Location: /register');
                exit();
            }

            if ($password !== $confirm_password) {
                $_SESSION['register_message'] = ['type' => 'error', 'text' => 'Passwords do not match.'];
                header('Location: /register');
                exit();
            }

            if (strlen($password) < 6) {
                $_SESSION['register_message'] = ['type' => 'error', 'text' => 'Password must be at least 6 characters long.'];
                header('Location: /register');
                exit();
            }

            // Attempt to create the user
            $result = $userModel->create($username, $password);

            if ($result === 'exists') {
                $_SESSION['register_message'] = ['type' => 'warning', 'text' => 'Username already taken. Please choose another.'];
                header('Location: /register');
                exit();
            } elseif ($result) {
                $_SESSION['register_message'] = ['type' => 'success', 'text' => 'Registration successful! You can now log in.'];
                header('Location: /login'); // Redirect to login page after successful registration
                exit();
            } else {
                $_SESSION['register_message'] = ['type' => 'error', 'text' => 'Registration failed. Please try again.'];
                header('Location: /register');
                exit();
            }
        } else {
            // If not a POST request, redirect to the registration form
            header('Location: /register');
            exit();
        }
    }
}
