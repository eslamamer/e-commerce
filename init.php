<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);


/////////////////////////////////////////////////////////////////////////////////////////////////////////
    $fun         = './includes/function/';
    $tpls        = './includes/templates/';
    $lang        = './includes/lang/';
    $userSession = isset($_SESSION['user']) ? $_SESSION['user'] : (isset($_SESSION['username']) ? $_SESSION['username'] : "");

/////////////////////////////////////////////////////////////////////////////////////////////////////////
    include './control-pan/connect.php';
    include $lang .'eng.php';
    include $fun .'fun.php';
    include $tpls .'header.php';
    ?>