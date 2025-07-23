<?php

class App {

    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        
        if (!empty($url[0]) && file_exists(APPS . DS . 'controllers' . DS . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]); 
        } else {
            
            $this->controller = 'Home'; 
        }

        
        require_once APPS . DS . 'controllers' . DS . $this->controller . '.php';

        
        $this->controller = new $this->controller;

       
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]); 
        } else {
            
            $this->method = 'index';
        }

        
        $this->params = $url ? array_values($url) : [];

        
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        $path = '';
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = $_SERVER['REQUEST_URI'];

            
            $request_uri = strtok($request_uri, '?');

            
            $script_name = $_SERVER['SCRIPT_NAME']; 
            $base_path = dirname($script_name);

            if ($base_path !== '/' && strpos($request_uri, $base_path) === 0) {
                $request_uri = substr($request_uri, strlen($base_path));
            }

            
            if (strpos($request_uri, '/index.php') === 0) {
                $request_uri = substr($request_uri, strlen('/index.php'));
            }

            
            $path = $request_uri;
        }

        
        $path = filter_var(trim($path, '/'), FILTER_SANITIZE_URL);

        
        return explode('/', $path);
    }

}
