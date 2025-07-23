<?php

class App {

    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Determine the controller
        // If URL is empty or first segment is not a valid controller file, default to 'home'
        if (empty($url[0]) || !file_exists(APPS . DS . 'controllers' . DS . ucfirst($url[0]) . '.php')) {
            $this->controller = 'home';
        } else {
            $this->controller = ucfirst($url[0]);
            unset($url[0]); // Remove controller segment from URL array
        }

        // Include the controller file
        require_once APPS . DS . 'controllers' . DS . $this->controller . '.php';

        // Instantiate the controller
        $this->controller = new $this->controller;

        // Determine the method
        // If a second segment exists and is a valid method in the controller, use it
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]); // Remove method segment from URL array
        } else {
            // Default to 'index' method if no method specified or method not found
            $this->method = 'index';
        }

        // Remaining URL segments are parameters
        $this->params = $url ? array_values($url) : [];

        // Call the controller method with parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        $path = '';
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = $_SERVER['REQUEST_URI'];

            // Remove the query string part (e.g., ?movie=Titanic)
            $request_uri = strtok($request_uri, '?');

            // Handle Replit's potential base path (e.g., /my-replit-name/ if not at root)
            $script_name = $_SERVER['SCRIPT_NAME']; // e.g., /index.php
            $base_path = dirname($script_name); // e.g., / or /my-replit-name

            if ($base_path !== '/' && strpos($request_uri, $base_path) === 0) {
                $request_uri = substr($request_uri, strlen($base_path));
            }

            // Handle /index.php being part of the path (e.g., /index.php/login)
            if (strpos($request_uri, '/index.php') === 0) {
                $request_uri = substr($request_uri, strlen('/index.php'));
            }

            // The remaining part is our clean URL path
            $path = $request_uri;
        }

        // Clean up the path: trim leading/trailing slashes, filter for URL safety
        $path = filter_var(trim($path, '/'), FILTER_SANITIZE_URL);

        // Split the path into segments
        return explode('/', $path);
    }

}
