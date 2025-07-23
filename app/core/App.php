<?php

class App {

    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        if (empty($url[0])) {
            $this->controller = 'home';
        } elseif (file_exists(APPS . DS . 'controllers' . DS . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        } else {
            $this->controller = 'home';
        }

        require_once APPS . DS . 'controllers' . DS . $this->controller . '.php';

        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                $this->method = 'index';
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        $path = '';
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = $_SERVER['REQUEST_URI'];

            $query_string = parse_url($request_uri, PHP_URL_QUERY);

            $params = [];
            if ($query_string) {
                parse_str($query_string, $params);
            }

            if (isset($params['url'])) {
                $path = $params['url'];
            } else {
                $uri_path = parse_url($request_uri, PHP_URL_PATH);
                if ($uri_path !== '/' && $uri_path !== '/index.php') {
                    $path = str_replace('/index.php', '', $uri_path);
                }
            }
        }

        $path = filter_var(trim($path, '/'), FILTER_SANITIZE_URL);
        return explode('/', $path);
    }

}
