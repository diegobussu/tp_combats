<?php

ini_set('display_errors', 1);

define( 'ROOT', dirname( __DIR__ ) );
require_once ROOT . '/autoload.php';
require_once ROOT . '/vendor/autoload.php';
session_start();

// App name witch set in namespaces
$appName = 'combats';


$queryString = rtrim( $_SERVER['QUERY_STRING'], '/' );
$nbRequest = 0;
if( !empty( $queryString ) ) {
    $tabRequest = explode( '/', $queryString );
    $nbRequest = count( $tabRequest );
}
$params = ['action'=>'', 'vars'=>''];
if( $nbRequest >=1 && !empty( $tabRequest[0] ) ) {
    $controllerName = ucfirst( array_shift( $tabRequest ) );
    if( isset( $tabRequest[0] ) ) {
        $params['action'] = array_shift( $tabRequest );
    } elseif( isset( $_POST['action'] ) ) {
        $params['action'] = $_POST['action'];
        unset( $_POST['action'] );
    }
    if( !empty( $_POST ) ) {
        $params['postvars'] =  $_POST;
    } else {
        $params['vars'] = $tabRequest;
    }
} else {
    $controllerName = 'Index';
}



$fileName = 'controller/' . $controllerName . 'Controller.php';
$className = $appName .'\\controller\\' . $controllerName . 'Controller';


$controller = new $className( $params );



