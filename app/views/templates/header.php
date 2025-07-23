<?php // This must be the absolute first thing in the file, no character before it!

  // Get the current URI path for conditional redirects
  $current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

  // For Replit, we need to handle the base path if the app isn't at the root
  $script_name = $_SERVER['SCRIPT_NAME']; // e.g., /index.php
  $base_path = dirname($script_name); // e.g., / or /my-replit-name

  // Remove base path and index.php from the URI for routing comparison
  $current_route = $current_uri;
  if ($base_path !== '/' && strpos($current_route, $base_path) === 0) {
      $current_route = substr($current_route, strlen($base_path));
  }
  if (strpos($current_route, '/index.php') === 0) {
      $current_route = substr($current_route, strlen('/index.php'));
  }
  $current_route = trim($current_route, '/'); // Remove leading/trailing slashes

  // Extract the first segment as the controller
  $current_controller = explode('/', $current_route)[0] ?? '';

  // Define pages that are accessible without login
  $public_pages = ['login', 'movie']; // 'movie' covers both /movie and /movie/search

  // CONDITION: If user is NOT authenticated
  if (!isset($_SESSION['auth'])) {
      // If they are trying to access a page that requires login (i.e., not a public page)
      // and they are not already on the login page itself, redirect them to login.
      if (!in_array($current_controller, $public_pages) && $current_controller !== 'login') {
          header('Location: /login'); // Redirect to login
          exit(); // Crucial: Terminate script execution after redirect
      }
      // If current_controller IS 'login' or a public page, we allow it to load.
  } 
  // CONDITION: If user IS authenticated
  else { // isset($_SESSION['auth']) is true
      // If they are trying to access the 'login' page or the root URL ('')
      // Redirect them to the home page if they are already logged in.
      if ($current_controller === 'login' || $current_controller === '') {
          header('Location: /home'); // Redirect to home
          exit(); // Crucial: Terminate script execution after redirect
      }
  }
  ?>
  <!DOCTYPE html>
  <html lang="en">
      <head>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
          <link rel="icon" href="/favicon.png">
          <title>Movie Rating</title>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta name="apple-mobile-web-app-capable" content="yes">
          <meta name="mobile-web-app-capable" content="yes">
      </head>
      <body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Movie Rating</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/movie">Movies</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Dropdown
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>
          <?php if (isset($_SESSION['auth'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="/logout">Logout</a>
          </li>
          <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/login">Login</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-4">
