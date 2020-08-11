<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
        Auth::handleLogin();

        header('Location:'.URL.'pages');
        exit;
    }

    function index() {
        $this->view->render('header');
        $this->view->render('index/index');
        $this->view->render('footer');
    }
}