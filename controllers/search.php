<?php

class Search extends Controller {

    function __construct() {
        parent::__construct();
        parent::loadHeader();

        $this->_menu = 'search';
    }

    function index($q='', $page = 1) {
        $q = !empty($q)?Func::check($q):'';
        $page = isset($page) ? (int) $page : 1;


        $this->view->menu = $this->_menu;
        $this->view->q = $q;

        $search_str_len = strlen($q);
        if (($search_str_len < 3) || ($search_str_len > 50)) {
            $this->view->get_total_rows = 0;
        } else {
            /**
             * Pagination
             */
            $this->view->get_total_rows = $get_total_rows = $this->model->listCount($q);
            $item_per_page = LIMIT_NEWS; //item to display per page
            $page_number = $page; //if there's no page number, set it to 1
            $page_url = URL.MF::$_lang.'/search/index/'.$q;
            $total_pages = ceil($get_total_rows/$item_per_page); //break records into pages
            $page_position = (($page_number-1) * $item_per_page); //get starting position to fetch the records
            $this->view->pagination = Func::paginate($item_per_page, $page_number, $get_total_rows, $total_pages, $page_url);

            /**
             * Search list
             */
            $this->view->listItems = $this->model->listItems($q, $page_position, $item_per_page);

        }

        $this->view->render('header');
        $this->view->render($this->_menu.'/index');
        $this->view->render('footer');
    }
}