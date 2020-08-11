<?php

class Masterdegree extends Controller {

    function __construct() {
        parent::__construct();
        parent::loadHeader();

        $this->_menu = 'masterdegree';
    }

    function index() {
        /**
         * load static information
         */
        $slug = $this->_menu;
        $this->pageInfo($slug);

        /**
         * List items
         */
        $this->view->listItems = $this->model->listItems();
//        print '<pre>';
//        print_r($this->view->listItems);
//        exit;

        $this->view->render('header');
        $this->view->render($this->_menu.'/index');
        $this->view->render('footer');
    }

    function view($id = '') {
        $id = isset($id) ? (int) $id : 0;
        if(!$id){
            header('Location: ' . URL . MF::$_lang . '/error');
            exit;
        }
        /**
         * load static information
         */
        $slug1 = $this->_menu;
        $this->pageInfo($slug1);


        $post = $this->model->viewPost($id);
        $this->view->post = $post;

        /*
         * post in page
         */
        $parentId = $post['id'];
        $this->view->innerPost = $this->model->innerPost($parentId);

        $this->view->render('header');
        $this->view->render($this->_menu.'/view');
        $this->view->render('footer');
    }

    /**
     * Helper
     */
    function pageInfo($slug){
        $slug = isset($slug) ? Func::check($slug) : '';
        if(!$slug){
            header('Location: ' . URL . MF::$_lang . '/error');
            exit;
        }
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