<?php

class Awards extends Controller {

    function __construct() {
        parent::__construct();
        parent::loadHeader();

        $this->_menu = 'awards';
    }

    function index() {
        $slug = $this->_menu;
        $this->pageInfo($slug);

        /**
         * List items
         */
        $this->view->listItems = $this->model->listItems();

        $this->view->render('header');
        $this->view->render($this->_menu.'/index');
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