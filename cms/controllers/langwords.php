<?php

class Langwords extends Controller {

    function __construct() {
        parent::__construct();
        Auth::handleLogin();

        $this->_menu = 'langwords';
        /**
         * check admin roles
         */
        if(!in_array($this->_menu, $this->_role)){
            header('Location: '.URL);
            exit;
        }

        $this->view->menu = $this->_menu;
        $this->view->title = Lang::get($this->_menu);
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
                header('Location: ' . URL . $this->_menu.'/index');
                exit;
            }


            switch ($_POST['action']) {
                /*
                 * SAVE
                 */
                case 'save':
                    $ids = $this->check_save();

                    if (!empty($ids)) {
                        $result = $this->model->saveItem($ids);

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
    function add() {
        $this->model->addWords();
        header('Location: ' . URL .$this->_menu);
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
    function check_save(){
        /*
         * Ordering
         */
        if (!empty($_POST['id'])) {
            foreach ($_POST['id'] as $key=>$item) {
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