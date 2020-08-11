<?php

class Controller {
    protected $_menu;
    protected $_menu_list;
    protected $_role;

    function __construct() {
        $this->_role = json_decode(Session::get('role'));
        $this->view = new View($this->_role);
    }

    /**
     *
     * @param string $name Name of the model
     * @param string $path Location of the models
     */
    public function loadModel($name, $modelPath = 'models/') {
        $path = $modelPath . $name . '_model.php';

        if (file_exists($path)) {
            require $modelPath . $name . '_model.php';

            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }
    }

    public function controller_header($menu) {
        $this->_menu = $menu;

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

}