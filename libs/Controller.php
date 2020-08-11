<?php

class Controller {
    public $_menu;

    function __construct() {
        $this->view = new View();
    }

    public function loadHeader(){
        $this->loadModel('header');
        $this->view->def = $this->model->settingsList();
        $this->view->topMenuFirst = $this->model->topMenuFirst();
        $this->view->topMenuThird = $this->model->topMenuThird();
        $this->view->topMenuSecond = $this->model->topMenuSecond();
        $this->view->menuList = $this->model->menuList();
    }

    /**
     *
     * @param string $name Name of the model
     * @param string $path Location of the models
     */
    public function loadModel($name, $modelPath = 'models/') {
        $path = $modelPath . $name . '_model.php';
        //echo $path.'<br>';

        if (file_exists($path)) {
            require_once $modelPath . $name . '_model.php';

            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }
    }

}