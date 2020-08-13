<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
        parent::loadHeader();

        $this->_menu = 'index';

        $this->view->newstype = [
            //2=>Lang::get('UPCOMING EVENTS'),
            1=>Lang::get('LATEST NEWS')];
    }

    function index() {
        $this->view->menu = $this->_menu;
        $this->view->listMainBanner = $this->model->listMainBanner();
        $this->view->listMainBannerleft = $this->model->listMainBannerleft();
        $this->view->listPost = $this->model->listPost();
        $this->view->listFeatures = $this->model->listFeatures();

        /*
         * include lang month array
         */
        $array_lang = 'langs/array_'.MF::$_lang.'.php';
        if(file_exists($array_lang)){
            require $array_lang;
            $this->view->month = $monthDShort;
        }

        $this->view->render('header');
        $this->view->render($this->_menu.'/index');
        $this->view->render('footer');
    }
}