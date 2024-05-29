<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

/////////////////////////////////////////////////////////////////////////////////////////////////////////
    $fun  = './includes/function/';
    $tpls = './includes/templates/';
    $lang = './includes/lang/';

/////////////////////////////////////////////////////////////////////////////////////////////////////////
    include './connect.php';
    include $lang .'eng.php';
    include $fun .'fun.php';
    include $tpls .'header.php';
    
    
    if(!isset($nonave)){
        include $tpls .'navBare.php';
    }
