<?php

class App {

    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Default to 'home' if URL is empty or first element is not a valid controller
        if (empty($url[0])) {
            $this->controller = 'home';
        } elseif (file_exists(APPS . DS . 'controllers' . DS . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        } else {
            $this->controller = 'home'; // Fallback to home if controller doesn't exist
        }

        require_once APPS . DS . 'controllers' . DS . $this->controller . '.php';

        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                // Method not found, default to index
                $this->method = 'index';
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        // This method now *expects* the 'url' GET parameter to be set.
        // All internal links and form actions will be updated to provide it.
        if (isset($_GET['url'])) {
            $path = $_GET['url'];
        } else {
            // Fallback for root access or direct index.php access without 'url' param
            $path = ''; 
        }

        // Clean up the path: trim slashes, filter, explode
        $path = filter_var(trim($path, '/'), FILTER_SANITIZE_URL);
        return explode('/', $path);
    }

}
