<?php

class Faculties extends Controller {

    private $id;
    function __construct() {
        parent::__construct();
        parent::loadHeader();

        $this->_menu = 'faculties';
    }

    function index() {
        $slug = $this->_menu;
        $this->pageInfo($slug);

        /**
         * List items
         */
        $this->view->menu = $this->_menu;
        $this->view->listItems = $this->model->listItems($this->_menu);

        $this->view->render('header');
        $this->view->render($this->_menu.'/index');
        $this->view->render('footer');
    }

    function view($slug = '', $subslug='') {
        $this->view->slug = $slug = isset($slug) ? Func::check($slug) : '';
        $this->view->subslug = $subslug = isset($subslug) ? Func::check($subslug) : '';
        if(!$slug){
            header('Location: ' . URL . MF::$_lang . '/error');
            exit;
        }

        $this->pageInfo($slug);

        /*
         * post in page
         */
        $parentId = $this->id;
        $this->view->page = $this->model->viewPage($slug);
        $this->view->innerPost = $this->model->innerPost($parentId);
        $this->view->listChair = $this->model->listChair($parentId);

        if($subslug){
            $this->view->pageInner = $this->model->innerChair($subslug);

            $this->view->render('header');
            $this->view->render($this->_menu.'/viewchair');
            $this->view->render('footer');
        } else {
            $this->view->render('header');
            $this->view->render($this->_menu.'/view');
            $this->view->render('footer');
        }
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
        $this->id = $id;
        $parents = $this->model->getParent($id);
        $this->view->breadcrumb = $this->model->create_breadcrumb($parents);
        $this->view->parents = $parents;
    }
}