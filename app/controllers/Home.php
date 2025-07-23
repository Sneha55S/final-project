<?php

class Home extends Controller {

    public function index() {
      $user = $this->model('User');
      // The 'test()' method was removed from User model, so this line needs to be adjusted
      // For now, let's just pass an empty array or relevant user data if available
      // For example, if you fetch user details based on session, do it here.
      // If not, just pass an empty array or null for user_data.
      $data = []; // Or fetch actual user data if needed for the home page

      $this->view('home/index', ['user_data' => $data]);
    }

}
