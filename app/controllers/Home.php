<?php

class Home extends Controller {

    public function index() {
      $user = $this->model('User');
      
      $data = []; 

      $this->view('home/index', ['user_data' => $data]);
    }

}
