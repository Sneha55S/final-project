<?php

class App {

    protected $controller = 'login';
    protected $method = 'index';
    protected $special_url = ['apply']; // Example: pages that are public and always use index method
    protected $params = [];

    public function __construct() {
        // If authenticated, default to home controller
        if (isset($_SESSION['auth']) == 1) {
            $this->controller = 'home';
        }

        // This will return a broken up URL (e.g., ['1' => 'controller', '2' => 'method', ...])
        $url = $this->parseUrl();

        // Check if a controller segment exists in the URL and if the corresponding file exists
        if (isset($url[1]) && file_exists('app/controllers/' . $url[1] . '.php')) {
            // CRITICAL FIX: Set the controller to the one from the URL
            $this->controller = $url[1]; 

            // Store the actual controller being used in session (optional, but good for debugging/tracking)
            $_SESSION['controller'] = $this->controller;

            // Handle special URLs that should always default to the 'index' method
            // This check should use the *newly set* $this->controller
            if (in_array($this->controller, $this->special_url)) { 
              $this->method = 'index';
            }

            unset($url[1]); // Remove the controller segment from the URL array
        } else {
            // If no valid controller is found in the URL, redirect to home (or login if not authenticated)
            // The default $this->controller ('login' or 'home') will be used here.
            // This 'else' block handles cases where the URL is invalid or empty.
            // The initial $this->controller value ('login' or 'home') will be used.
            // No need for a header redirect here, as the default controller will be loaded.
            // If you want to force a redirect for invalid URLs, you can keep it.
            // For now, let's keep it as per your original logic.
            header('Location: /home'); // This will redirect if the requested URL doesn't match a controller
            die;
        }

        // Include the controller file
        require_once 'app/controllers/' . $this->controller . '.php';

        // Instantiate the controller
        $this->controller = new $this->controller;

        // Check if a method segment is passed in the URL and if it exists in the controller
        if (isset($url[2])) {
            if (method_exists($this->controller, $url[2])) {
                $this->method = $url[2];
                $_SESSION['method'] = $this->method; // Store method in session (optional)
                unset($url[2]); // Remove the method segment
            }
        }

        // Rebase the remaining URL segments to form the parameters array
        $this->params = $url ? array_values($url) : [];

        // Call the controller method with the parameters
        call_user_func_array([$this->controller, $this->method], $this->params);		
    }

    /**
     * Parses the URL from $_SERVER['REQUEST_URI'].
     * @return array An array of URL segments.
     */
    public function parseUrl() {
        $u = "{$_SERVER['REQUEST_URI']}";
        // Trims trailing forward slash, sanitizes URL, explodes it by forward slash to get elements
        $url = explode('/', filter_var(rtrim($u, '/'), FILTER_SANITIZE_URL));
        unset($url[0]); // Remove the first empty element from the explode result
        return $url;
    }
}
