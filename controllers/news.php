<?php

class News extends Controller {

    function __construct() {
        parent::__construct();
        parent::loadHeader();

        $this->_menu = 'news';
    }

    function index($category = 'all', $page = 1) {
        $category = isset($category) ? Func::check($category) : 'all';
        $page = isset($page) ? (int) $page : 1;

        $slug = $this->_menu;
        $this->pageInfo($slug);

        /**
         * List items
         */
        $this->view->menu = $this->_menu;
        $this->view->category = $category;

        /**
         * Pagination
         */
        $get_total_rows = $this->model->listCount($category);
        $item_per_page = LIMIT_NEWS; //item to display per page
        $page_number = $page; //if there's no page number, set it to 1
        $page_url = URL.MF::$_lang.'/news/index/'.$category;
        $total_pages = ceil($get_total_rows/$item_per_page); //break records into pages
        $page_position = (($page_number-1) * $item_per_page); //get starting position to fetch the records
        $this->view->pagination = Func::paginate($item_per_page, $page_number, $get_total_rows, $total_pages, $page_url);

        /**
         * Category ans news list
         */

        /*
         * include lang month array
         */
        $array_lang = 'langs/array_'.MF::$_lang.'.php';
        if(file_exists($array_lang)){
            require $array_lang;
            $this->view->month = $monthD;
        }
        $this->view->listCategory = $this->model->listCategory();
        $this->view->listItems = $this->model->listItems($category, $page_position, $item_per_page);


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

        /*
         * include lang month array
         */
        $array_lang = 'langs/array_'.MF::$_lang.'.php';
        if(file_exists($array_lang)){
            require $array_lang;
            $this->view->month = $monthD;
        }

        /**
         * load static information
         */
        $slug1 = $this->_menu;
        $this->pageInfo($slug1);

        $this->view->viewPost = $this->model->viewPost($id);

        $this->view->render('header');
        $this->view->render($this->_menu.'/view');
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