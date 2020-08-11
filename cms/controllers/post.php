<?php

class Post extends Controller {
    public $_type = [1=>'News', 'Event'];

    function __construct() {
        parent::__construct();
        Auth::handleLogin();

        $this->controller_header('post');
        $this->view->type = $this->_type;
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
        $this->view->css = [
                'css/bootstrap-datetimepicker.min.css'
        ];
        $this->view->js = [
                'js/moment.min.js',
                'js/bootstrap-datetimepicker.min.js',
                'js/upload.js',
                'tinymce/tinymce.min.js',
                'tinymce/tiny_mce_inc.js'
        ];

        $this->view->titleSub = Lang::get('Add');
        if($postData){
            $this->view->postData = $postData;
        }
        $this->view->listCategory = $this->model->listCategory();

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

        $this->view->css = [
                'css/bootstrap-datetimepicker.min.css'
        ];
        $this->view->js = [
                'js/moment.min.js',
                'js/bootstrap-datetimepicker.min.js',
                'js/jquery-ui.min.js',
                'js/upload.js',
                'tinymce/tinymce.min.js',
                'tinymce/tiny_mce_inc.js'
        ];
        $this->view->titleSub = Lang::get('Edit');

        $this->view->listCategory = $this->model->listCategory();
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
        header('Location: ' . URL .$this->_menu.'/edit/'.$id);
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
    function multiupload(){
        $this->model->multiupload();
    }
    function deletephoto($gid=NULL, $sid=NULL){
        if(!Session::get('last_session_request')){
            Session::set('last_session_request', 0);
        }

        $limit = microtime(true) - Session::get('last_session_request');
        if($limit<=0.2){
            $result['error'] = 'Floot error';
        } else {
            if(isset($gid) && isset($sid)){
                $this->model->deletePhoto($gid,$sid);
                $result['success'] = 1;
            } else {
                $result['error'] = Lang::get('Image not deleted');
            }
        }
        Session::set('last_session_request', microtime(true));
        echo json_encode($result);
    }
    function orderphoto(){
        $this->model->orderphoto();
    }
}