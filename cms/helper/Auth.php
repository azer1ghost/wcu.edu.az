<?php

class Auth {

    public static function handleLogin() {
        if(!isset($_SESSION)){
            session_start();
        }

        $logged = isset($_SESSION['loggedIn']) ? $_SESSION['loggedIn'] : NULL;
        if ($logged == false) {
            session_destroy();
            header('Location: ' . URL.'login');
            exit;
        }
    }
}