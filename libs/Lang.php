<?php

/**
 * Created by MF.
 * User: MF
 * Date: 6/12/2017
 * Time: 10:17 AM
 */
class Lang {
    public static function get($key) {
        $lang = MF::$_lang;

        if (file_exists('langs/'.$lang.'.php')) {
            require 'langs/'.$lang.'.php';
        } else {
            require 'langs/en.php';
        }

        if (array_key_exists($key, $keys)) {
            return htmlspecialchars_decode($keys[$key]);
        } else {
            return $key;
        }
    }
}