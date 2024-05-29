<?php
   session_start();
    $nonave='';
    $pageTitle ='Login';
    if(isset($_SESSION['username'])){
        header('location: dashboard.php');
        exit();
    }
    include './init.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['Uname'];
        $pwd = $_POST['pwd'];
        $shPWD = sha1($pwd);
        $stmt = $con->prepare("select 
                                        userID, Uname, password 
                                from 
                                        users
                                where 
                                        Uname = ?
                                AND     
                                        password = ?
                                AND 
                                        groupID = 1
                                limit  
                                        1   ");
        $stmt->execute(array($username, $shPWD));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        if($count > 0){
            //print_r($row);
            $_SESSION['username']=$username;
            $_SESSION['userID']=$row['userID'];
            header('location:dashboard.php');
           
            exit();
        }   
    }  
?>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h4 class="text-center">Login</h4>
        <input class="form-control form-control-lg" type="text" name="Uname" placeholder="user name" /> <!--autocomplete="off"-->
        <input class="form-control form-control-lg" type="password" name="pwd" placeholder="password" /> <!--autocomplete="new-password"-->
        <input class="btn btn-primary form-control-lg w-100" type="submit" name="send" value="Login" />
    </form>
<?php
    include $tpls.'footer.php';
?>