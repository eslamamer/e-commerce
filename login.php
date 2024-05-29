<?php 
    session_start();
    $pageTitle = 'login';
    if(isset($_SESSION['user'])){
        header("location: index.php");
        exit();
    }
    include './init.php';
    $signUpMess = [];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['login'])){
            $user = $_POST['name'];
            $pwd  = sha1($_POST['pwd']);
            $stmt = $con->prepare("select
                                            userID, Uname, password
                                    from
                                            users
                                    where
                                            Uname = ? and password = ? ");
            $stmt->execute(array($user, $pwd));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            if($count > 0){
            $_SESSION['user'] = $user;
            $_SESSION['uid']  = $row['userID'];
            header("location: index.php");
            exit();
            }

        }else{
            $user   = $_POST['name'];
            $pwd    = $_POST['pwd'];
            $conPwd = $_POST['conPwd'];
            $email  = $_POST['email'];
            $shPwd  ="";

            if(isset($user)){
                $filteredUser = filter_var($user, FILTER_SANITIZE_FULL_SPECIAL_CHARS);;
                if(strlen($filteredUser) < 3)
                $signUpMess[] = "<p class='alert alert-danger'>username must not less than 3 characters</p>";
            }
            if(isset($pwd)){
                if(strlen($pwd) < 4){
                    $signUpMess[] = "<p class='alert alert-danger'>password must not less than 4 characters </p>";
                }
                if($pwd === $conPwd){
                    $shPwd = sha1($pwd);
                }else{
                    $signUpMess[] = "<p class='alert alert-danger'>password not matches </p>"; 
                }
            }
            if(isset($email)){
                $filteEemail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if(!filter_var($filteEemail, FILTER_VALIDATE_EMAIL)){
                    $signUpMess[] = "<p class='alert alert-danger'>mail not valid</p>";
                }
            }
            if(empty($signUpMess)){
                $count = IsExist('Uname', 'users', $user);
            if($count == 1){
                $signUpMess [] = "<p class='alert alert-danger'>username is already exsisting</p>"; 
            }else{
                $stmt = $con->prepare("insert into
                                                users(Uname , password , email , `Registration Date`)
                                                values(:rname, :rpassword, :remail, now())");
                $stmt->execute(array(
                    'rname'     => $user,
                    'rpassword' => $shPwd,
                    'remail'    => $email
                ));
                $signUpMess[] = "<p class='alert alert-success'>successfull registration</p>"; 
                }
            }
        }  
    }
?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">
        <a 
            class='text-decoration-none' 
            href='login.php?reg=login'>
                log in
        </a>
        | 
        <a 
            class='text-decoration-none' 
            href='login.php?reg=signup'>
                sign up
        </a>
    </h4>
    <?php 
        if(isset($_GET['reg']) && $_GET['reg'] == 'signup'){?>
            <input
                pattern=".{3,8}"
                title="name must between 4 and 8 character"
                class="form-control form-control-lg"
                type="text" name="name"
                placeholder="User Name"
                autocomplete="off"
                require
            /> 
            <input
                pattern=".{4,8}"
                title="name must between 4 and 8 character"
                class="form-control form-control-lg" 
                type="password" name="pwd"
                placeholder="Password"
                require
            />
            <input 
                class="form-control form-control-lg" 
                type="password" name="conPwd" 
                placeholder="Confirm password" 
            />
            <input 
                class="form-control form-control-lg" 
                type="email" name="email" 
                placeholder="E-Mail address" 
            />
            <input class="btn btn-success form-control-lg w-100" type="submit" name="signup" value="Signup" />
    <?php }else{?>
            <input 
                class="form-control form-control-lg" 
                type="text" name="name" 
                placeholder="User Name" 
                autocomplete='off'
            />
            <input 
                class="form-control form-control-lg" 
                type="password" 
                name="pwd" 
                placeholder="Password" 
                autocomplete="new-password" 
            /> 
            <input class="btn btn-primary form-control-lg w-100" type="submit" name="login" value="Login" />
        <?php 
         
        }
        foreach($signUpMess as $Mess){
            echo $Mess;
            }
        ?>
</form>
<?php include $tpls.'footer.php' ?>