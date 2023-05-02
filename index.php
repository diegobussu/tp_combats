<?php
ini_set('display_errors', 1);
require 'autoload.php';
require 'vendor/autoload.php';
session_start(); // On appelle session_start() APRÈS avoir enregistré l'autoload.

//var_dump( $_SERVER );




$controllerPath = "combats\\controller";
if( isset( $_REQUEST['controller'] ) ) {
    $controllerName = ucfirst($_REQUEST['controller']);
} else {
    $controllerName = 'Index';
}
$fileName = 'controller/' . $controllerName . 'Controller.php';
$className = $controllerPath . '\\' . $controllerName . 'Controller';

if( file_exists( $fileName ) ) {
    if( class_exists( $className ) ) {
        $controller = new $className();
    } else {
        exit( "Error : Class not found!" );
        die;
    }
} else {
    exit( "Error 404 : not found!" );
    die;
}



