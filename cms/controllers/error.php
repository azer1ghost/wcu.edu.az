<?php

class Error404 extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->view->title = '404 Error';
        $this->view->msg = Lang::get('The page you are looking for was not found.');
        
        $this->view->render('error/inc/header');
        $this->view->render('error/index');
        $this->view->render('error/inc/footer');
    }

}