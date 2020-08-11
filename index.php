<?php
error_reporting(0);
//push
//ini_set('display_errors', '1');
require 'config.php';


spl_autoload_register(function($class) {
  //    print "<pre>";
  //    var_dump($class);
  //    print "</pre>";
      require LIBS . $class . ".php";
});

// Load MF!
$mf = new MF();

// Optional Path Settings
//$bootstrap->setControllerPath();
//$bootstrap->setModelPath();
//$bootstrap->setDefaultFile();
//$bootstrap->setErrorFile();

$mf->init();
