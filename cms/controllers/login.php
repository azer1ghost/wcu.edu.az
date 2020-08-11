<?php

class Login extends Controller {

    function __construct() {
        parent::__construct();
        //Session::init();
        $this->view->title = 'Login';
        $this->view->description = API_TITLE;
    }

    function index() {
        $count = $this->model->anyibrutCount();
        if($count>3){
            $this->view->recaptca = 1;
        } else {
            $this->view->recaptca = 0;
        }
        $this->view->render('login/index');
    }

    function run() {
        $this->model->run();
    }

    function logout() {
        Session::destroy();
        header('location: ' . URL . 'login');
        exit;
    }


}