<?php

class View {
    private $_role;

    function __construct($role) {
        $this->_role = $role;
    }

    public function render($name, $noInclude = false) {
        require 'views/' . $name . '.php';
    }

}