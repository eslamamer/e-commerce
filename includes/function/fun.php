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

     
//function which get raws from any table orderd by selected field and optionaly make where restriction.
    function getElement($table, $order, $fName = "", $value = null , $approved = null){
        global $con;
        $where ="";
        $params = [];
        $approved = $approved !=null ? $approved = ' and Regstatus = 1' : '';
        if($fName != ""){
            $where = "where $fName = ? $approved";
            $params[] = $value;
        }else{
            $where = "where 1 $approved";
        }
        $stmt = $con->prepare("select * from $table $where order by $order asc");
        $stmt->execute($params);
        $count = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($count) == 1){
            $elements = reset($count);
        }else{
            $elements = $count;
        }
        return $elements;
    }
    
    //check registeration status value
    function checkUserStatus($user){
        global $con;
        $stmt = $con->prepare("select
                                        Uname, RegStatus
                                from
                                        users
                                where
                                        Uname = ?
                                and
                                        RegStatus = ? ");
        $stmt->execute([$user, 0]);
        $count = $stmt->rowCount();
        return $count;
    }

    //check if item is existing
    function IsExist($fName, $table, $value){
        global $con;
        $stmt = $con->prepare("select $fName from $table where $fName = ?");
        $stmt->execute(array($value));
        $count = $stmt->rowCount();
        return $count;
    }
?>
