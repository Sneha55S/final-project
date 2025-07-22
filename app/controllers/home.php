<?php

class Home extends Controller {

    public function index() {
      $user = $this->model('User');
      $data = $user->test();

      // All the commented-out curl code should be removed from here.
      // And the echo "<pre>"; echo $response; die; lines too.

        $this->view('home/index', ['user_data' => $data]);
    }

}