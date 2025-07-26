<?php
$current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_name = $_SERVER['SCRIPT_NAME']; 
$base_path = dirname($script_name); 

$current_route = $current_uri;
if ($base_path !== '/' && strpos($current_route, $base_path) === 0) {
    $current_route = substr($current_route, strlen($base_path));
}
if (strpos($current_route, '/index.php') === 0) {
    $current_route = substr($current_route, strlen('/index.php'));
}
$current_route = trim($current_route, '/'); 

$current_controller = explode('/', $current_route)[0] ?? '';
if (empty($current_controller)) {
    $current_controller = 'home'; 
}

$always_public_controllers = ['login', 'movie', 'register']; 

if (!isset($_SESSION['auth'])) {
    if (!in_array($current_controller, $always_public_controllers) && $current_controller !== 'login' && $current_controller !== 'home' && $current_controller !== '') {
        header('Location: /login'); 
        exit(); 
    }
} else {
    if ($current_controller === 'login' || $current_controller === '') {
        header('Location: /home'); 
        exit(); 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        <link rel="icon" href="https://placehold.co/32x32/2c3e50/ecf0f1?text=CR" type="image/png"> 
        <title>Cine Review</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
            body {
                font-family: 'Inter', sans-serif;
                background-color: #f5f7fa; 
                color: #343a40; 
                padding-top: 70px; 
                padding-bottom: 60px; 
                min-height: 100vh; 
                display: flex;
                flex-direction: column;
            }
            .navbar {
                background-color: #2c3e50 !important;
                border-bottom: 1px solid #34495e; 
                box-shadow: none;
                position: fixed; 
                top: 0;
                width: 100%;
                z-index: 1030; 
            }
            .navbar-brand, .nav-link {
                color: #ecf0f1 !important; 
            }
            .navbar-nav .nav-link.active {
                font-weight: 600;
                color: #ffffff !important; 
            }
            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28236, 240, 241, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
            }
            .navbar-toggler {
                border-color: rgba(236, 240, 241, 0.1) !important;
            }
            .container {
                max-width: 960px;
                flex-grow: 1; 
            }
            .card {
                border: 1px solid #e9ecef; 
                box-shadow: none;
                border-radius: 0.5rem;
            }
            .btn-primary {
                background-color: #3498db; 
                border-color: #3498db;
                border-radius: 0.3rem;
                box-shadow: none;
            }
            .btn-primary:hover {
                background-color: #217dbb; 
                border-color: #217dbb;
            }
            .btn-success {
                background-color: #2ecc71; 
                border-color: #2ecc71;
                border-radius: 0.3rem;
                box-shadow: none;
            }
            .btn-success:hover {
                background-color: #27ae60;
                border-color: #27ae60;
            }
            .btn-secondary {
                background-color: #95a5a6; 
                border-color: #95a5a6;
                border-radius: 0.3rem;
                box-shadow: none;
            }
            .btn-secondary:hover {
                background-color: #7f8c8d;
                border-color: #7f8c8d;
            }
            .alert {
                border-radius: 0.3rem;
            }
            .form-control {
                border-radius: 0.3rem;
                border-color: #ced4da;
            }
            .form-control:focus {
                border-color: #3498db; 
                box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
            }
            .card.bg-white {
                background-color: #ffffff !important;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="/home">Cine Review</a> 
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php 
                        $active_controller = $current_controller ?: 'home';
                        ?>
                        <?php if (isset($_SESSION['auth'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($active_controller === 'home') ? 'active' : ''; ?>" href="/home">Home</a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($active_controller === 'movie') ? 'active' : ''; ?>" href="/movie">Movies</a>
                        </li>
                        <?php if (isset($_SESSION['auth'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($active_controller === 'logout') ? 'active' : ''; ?>" href="/logout">Logout</a>
                        </li>
                        <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($active_controller === 'login') ? 'active' : ''; ?>" href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($active_controller === 'register') ? 'active' : ''; ?>" href="/register">Sign Up</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
