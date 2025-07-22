<?php

class Login extends Controller {

    public function index() {		
			//echo password_hash("admin", PASSWORD_DEFAULT);
	    $this->view('login/index');
    }
    
    public function verify(){
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
			
		$user = $this->model('User');
		$user->authenticate($username, $password); 
    }

}
