<?php
error_reporting(0);
//ini_set('display_errors', '1');

require 'config.php';
require 'helper/Auth.php';

spl_autoload_register(function($class) {
  //    print "<pre>";
  //    var_dump($class);
  //    print "</pre>";
      require LIBS . $class . ".php";
});


// Load the Bootstrap!
$MFAdmin = new MFAdmin();

// Optional Path Settings
//$bootstrap->setControllerPath();
//$bootstrap->setModelPath();
//$bootstrap->setDefaultFile();
//$bootstrap->setErrorFile();


$MFAdmin->init();
