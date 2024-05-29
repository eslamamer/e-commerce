<?php
        function addTitle() {
        global $pageTitle;
                echo isset($pageTitle) ? $pageTitle : 'Defaulte';
        }   

    //redirect function for errors. v2
        function redirect($msg, $URL = 'index.php' , $sec = 5 ){
        $URL = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' ? $_SERVER['HTTP_REFERER'] : $URL;
        echo $msg;
        echo "<div class='alert alert-info'>you wil be redirected to ".($URL == 'index.php' ? 'home page' : 'previous page')." after $sec seconds</div>";
        header("refresh:$sec, url = $URL");
        exit();
     }

    //check existing item function 
        function countIsExist($fName, $table, $value){
            global $con;
            $stmt = $con->prepare("select $fName from $table where $fName = ?");
            $stmt->execute(array($value));
            $count = $stmt->rowCount();
            return $count;
        }
    //count items v1
    function countItems($items, $table){
        global $con;
        $stmt = $con->prepare("select count($items) from $table");
        $stmt->execute();
        $count = $stmt->fetchColumn();
        // if ($count<10)
        //     return "00".$count;
        // elseif ($count<100)
        //     return "0".$count;
        // elseif ($count<1000)
            return $count;
    }

    function latest($fName,$table, $order, $lim = 3){
        global $con;
        $stmt = $con->prepare("select $fName from $table order by $order DESC limit $lim");
        $stmt->execute();
        $items = $stmt->fetchAll();
        return $items;
    }
?>