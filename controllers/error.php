<?php

class Error404 extends Controller {

    function __construct() {
        parent::__construct();
        parent::loadHeader();
    }
    
    function index() {
        $this->view->title = 'The page you’re looking for cannot be found.';
        
        $this->view->render('header');
        $this->view->render('error/index');
        $this->view->render('footer');
    }

}