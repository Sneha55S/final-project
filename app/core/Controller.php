<?php

class Controller {

    public function model ($model) {
        // Use the MODELS constant for consistency and correct path resolution
        require_once MODELS . DS . $model . '.php';
        return new $model();
    }

    public function view ($view, $data = []) {
        // Use the VIEWS constant for consistency and correct path resolution
        // This ensures it always looks from the project root's app/views/ directory
        require_once VIEWS . DS . $view .'.php';
    }

}