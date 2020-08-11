<?php

class Vacancy extends Controller {

    function __construct() {
        parent::__construct();
        Auth::handleLogin();

        $this->controller_header('vacancy');
    }

    /* LIST */
    function index() {
        $this->view->titleSub = Lang::get('List');
        /*
         * css js libs
         */
        $this->view->css = [
                'css/jquery.dataTables.min.css'
        ];
        $this->view->js = [
                'js/jquery.dataTables.min.js'
        ];

        if ($_POST) {
            /*
             * Check Token
             */
            $_POST['mf_token'] = isset($_POST['mf_token']) ? Func::check($_POST['mf_token']) : '';
            if (!Func::token_check($_POST['mf_token'])) {
                Session::set('note_error', Lang::get('Token Error!'));
                header('location: ' . URL . $this->_menu.'/index');
                exit;
            }
            switch ($_POST['action']) {
                /*
                 * SAVE
                 */
                case 'save':
                    $ids = $this->check_ordering();

                    if (!empty($ids)) {
                        $result = $this->model->orderingItem($ids);

                        if (isset($result['model_error'])) {
                            Session::set('note_error', $result['model_error']);
                        } else {
                            Session::set('note_success', $result['model_success']);
                        }
                    }
                    header('Location: ' . URL .$this->_menu.'/index');
                    exit;
                    break;

                /*
                 * DELETE
                 */
                case 'delete':
                    $ids = $this->check_box();

                    if (!empty($ids)) {
                        $result = $this->model->deleteItem($ids);
                        if (isset($result['model_error'])) {
                            Session::set('note_error', $result['model_error']);
                        } else {
                            Session::set('note_success', $result['model_success']);
                        }
                    }
                    header('Location: ' . URL .$this->_menu.'/index');
                    exit;
                    break;


                /*
                 * ACTIVATE
                 */
                case 'activate':
                    $ids = $this->check_box();

                    if (!empty($ids)) {
                        $result = $this->model->updateStatus($ids, 2);
                        if (isset($result['model_error'])) {
                            Session::set('note_error', $result['model_error']);
                        } else {
                            Session::set('note_success', $result['model_success']);
                        }
                    }
                    header('Location: ' . URL .$this->_menu.'/index');
                    exit;
                    break;


                /*
                 * DEACTIVATE
                 */
                case 'deactivate':
                    $ids = $this->check_box();

                    if (!empty($ids)) {
                        $result = $this->model->updateStatus($ids, 1);
                        if (isset($result['model_error'])) {
                            Session::set('note_error', $result['model_error']);
                        } else {
                            Session::set('note_success', $result['model_success']);
                        }
                    }
                    header('Location: ' . URL .$this->_menu.'/index');
                    exit;
                    break;
            }
        }

        $this->view->listItems = $this->model->listItems();
        $this->view->render('header');
        $this->view->render($this->_menu.'/index');
        $this->view->render('footer');
    }

    /* ADD */
    function add($postData = '') {
        $this->view->js = [
                'tinymce/tinymce.min.js',
                'tinymce/tiny_mce_inc.js'
        ];

        $this->view->titleSub = Lang::get('Add');
        if($postData){
            $this->view->data = $postData;
        }

        $this->view->maxOrder = $this->model->maxOrder() + 1;

        $this->view->render('header');
        $this->view->render($this->_menu.'/add');
        $this->view->render('footer');
    }
    function create() {
        $result = $this->model->createItem();

        if ($result == 1) {
            Session::set('note_success', Lang::get('Data added'));
            header('location: ' . URL .$this->_menu.'/add');
            exit;
        } else {
            Session::set('note_error', $result['error']);
            $this->add($result['data']);
        }
    }

    /* EDIT */
    function edit($id='') {
        $id = ($id)?(int) $id:0;
        if(!$id){
            header('location: ' . URL . $this->_menu);
            exit;
        }

        $this->view->js = [
                'tinymce/tinymce.min.js',
                'tinymce/tiny_mce_inc.js'
        ];

        $this->view->titleSub = Lang::get('Edit');
        $this->view->item = $this->model->singleItem($id);

        $this->view->render('header');
        $this->view->render($this->_menu.'/edit');
        $this->view->render('footer');
    }
    function update($id='') {
        $id = ($id)?(int) $id:0;
        if(!$id){
            header('location: ' . URL . $this->_menu);
            exit;
        }

        $result = $this->model->updateItem($id);
        if (isset($result['model_error'])) {
            Session::set('note_error', $result['model_error']);
        } else {
            Session::set('note_success', $result['model_success']);
        }
        header('location: ' . URL . $this->_menu.'/edit/'.$id);
        exit;
    }

    /* HELPER */
    function check_box() {
        /*
         * Check Selected
         */
        if (!empty($_POST['ch'])) {
            foreach ($_POST['ch'] as $key=>$item) {
                $k = (int) $key;
                $ids[$k] = (int) $item;
            }
            return $ids;
        } else {
            Session::set('note_error', Lang::get('Please select items!'));
            header('Location: ' . URL .$this->_menu.'/index');
            exit;
        }
    }
    function check_ordering(){
        /*
         * Ordering
         */
        if (!empty($_POST['or'])) {
            foreach ($_POST['or'] as $key=>$item) {
                $k = (int) $key;
                $ids[$k] = (int) $item;
            }
            return $ids;
        } else {
            Session::set('note_error', Lang::get('Please change ordering!'));
            header('Location: ' . URL .$this->_menu.'/index');
            exit;
        }
    }
}