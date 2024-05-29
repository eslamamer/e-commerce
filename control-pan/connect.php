<?php
    $dsn = 'mysql:host=localhost;dbname=shop';
    $uname = 'root';
    $pw = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );

    try{
        $con = new PDO($dsn, $uname, $pw ,$option );
        $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        echo "failed to connect:" . $e->getMessage();
    }
?>