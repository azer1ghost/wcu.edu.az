<?php

class Expeditions extends Controller {

    function __construct() {
        parent::__construct();
        parent::loadHeader();

        $this->_menu = 'expeditions';
    }

    function index() {
        $slug = $this->_menu;
        $this->pageInfo($slug);

        $this->view->menu = $this->_menu;
        $this->view->listPost = $this->model->listPost();
        $this->view->render('header');
        $this->view->render($this->_menu.'/index');
        $this->view->render('footer');
    }

    function view($slug = '') {
        $slug1 = $this->_menu;
        $this->pageInfo($slug1);

        $this->view->menu = $this->_menu;
        if($slug){
            $this->view->post = $this->model->viewPost($slug);
            $this->view->listPhoto = $this->model->listPhoto($slug);
        } else {
            $this->view->post = ['title'=>'', 'text'=>'', 'thumb'=>''];
            $this->view->listPhoto= [];
        }

        $this->view->render('header');
        $this->view->render($this->_menu.'/postview');
        $this->view->render('footer');
    }

    /**
     * Helper
     */
    function pageInfo($slug){
        /**
         * get title
         */
        $page = $this->model->getPage($slug);
        $this->view->title = $page['title'];
        $this->view->page = $page;

        /**
         * get parent
         */
        $id = 1;
        if(is_array($page) && $page['id']>0){
            $id =  $page['id'];
        }
        $parents = $this->model->getParent($id);
        $this->view->breadcrumb = $this->model->create_breadcrumb($parents);
        $this->view->parents = $parents;
    }
}