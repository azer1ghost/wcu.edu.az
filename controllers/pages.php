<?php

class Pages extends Controller {

    private $id;
    function __construct() {
        parent::__construct();

        $this->_menu = 'pages';
        parent::loadHeader();
    }

    function index() {
        header('Location:' .URL.MF::$_lang);
        exit;
    } 

    function view($slug = '') {
        $slug = (isset($slug) && !empty($slug)) ? Func::check($slug) : '';
        if(!$slug){
            header('Location: ' . URL . MF::$_lang . '/error');
            exit;
        }

        $this->pageInfo($slug);

        /**
         * post in page
         */
        $this->view->page = $this->model->viewPage($slug);
        $this->view->innerPost = $this->model->innerPost($this->id);

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
        $this->id = 1;
        if(is_array($page) && $page['id']>0){
            $this->id =  $page['id'];
        }
        $parents = $this->model->getParent($this->id);
        $this->view->breadcrumb = $this->model->create_breadcrumb($parents);
        $this->view->parents = $parents;
    }

}