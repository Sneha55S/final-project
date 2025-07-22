<?php

class App {

    protected $controller = 'login';
    protected $method = 'index';
    protected $special_url = ['apply'];
    protected $params = [];

    public function __construct() {
        // If authenticated, default to home controller
        if (isset($_SESSION['auth']) == 1) {
            $this->controller = 'home';
        }

        $url = $this->parseUrl();

        // Check if a controller segment exists in the URL and if the corresponding file exists
        if (isset($url[1]) && file_exists(APPS . DS . 'controllers' . DS . $url[1] . '.php')) { // Use APPS constant here too for consistency
            // If a valid controller is found in the URL, use it
            $this->controller = $url[1]; 

            // Store the actual controller being used in session (optional)
            $_SESSION['controller'] = $this->controller;

            // Handle special URLs that should always default to the 'index' method
            if (in_array($this->controller, $this->special_url)) { 
              $this->method = 'index';
            }

            unset($url[1]); // Remove the controller segment from the URL array
        } 
        // ELSE: If no valid controller is found in the URL (e.g., for '/'),
        // the $this->controller will remain its default value ('login' or 'home').
        // NO REDIRECT HERE. Let the default controller handle it.

        // Include the controller file using the APPS constant
        require_once APPS . DS . 'controllers' . DS . $this->controller . '.php'; // Line 38

        // Instantiate the controller
        $this->controller = new $this->controller;

        // Check if a method segment is passed in the URL and if it exists in the controller
        if (isset($url[2])) {
            if (method_exists($this->controller, $url[2])) {
                $this->method = $url[2];
                $_SESSION['method'] = $this->method;
                unset($url[2]);
            }
        }

        // Rebase the remaining URL segments to form the parameters array
        $this->params = $url ? array_values($url) : [];

        // Call the controller method with the parameters
        call_user_func_array([$this->controller, $this->method], $this->params);		
    }

    public function parseUrl() {
        $u = "{$_SERVER['REQUEST_URI']}";
        $url = explode('/', filter_var(rtrim($u, '/'), FILTER_SANITIZE_URL));
        unset($url[0]);
        return $url;
    }
}
