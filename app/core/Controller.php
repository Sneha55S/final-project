<?php

class Controller {

    protected function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    protected function view($view, $data = []) {
        require_once '../app/views/templates/header.php'; // Header is always included
        require_once '../app/views/' . $view . '.php';
        require_once '../app/views/templates/footer.php'; // Footer is always included
    }
}
