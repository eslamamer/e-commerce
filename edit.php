<?php
session_start();
$pageTitle ='edit profile';
if(isset($_SESSION['user'])||isset($_SESSION['username'])){
    include 'init.php';
    $userid = isset($_SESSION['uid']) && is_numeric($_SESSION['uid']) ? intval($_SESSION['uid']) : 0;
    $user = getElement('users', 'userID', 'userID', $_SESSION['uid']);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
        $id     = $_SESSION['uid'];
        $name   = $_POST['uname'];
        $pwd    = $_POST['npwd'] ;
        $email  = $_POST['email'] ;
        $fname  = $_POST['fname'] ;
        $alerts = [];
      
            if($name !== $user['Uname']){
                $alerts[] = '<div class="alert alert-danger">you can not change your previous user name</div>';
            }
            if(empty($pwd)){
                $pwd = $_POST['opwd'];
            }elseif(strlen($pwd) >= 4){
                $pwd = sha1($pwd);
            }else{
                $alerts[] = '<div class="alert alert-danger">password must be not less than 4 characters or numbers</div>';
            }
            if(empty($email)){
                $alerts[] = '<div class="alert alert-danger">email is requierd</div>';
            }
            if(strlen($fname) < 8){
                $alerts[] = '<div class="alert alert-danger">full name is requierd and not less than 8 characters</div>';
            }
            if(empty($alerts)){
                $stmt = $con->prepare("
                                    update 
                                            users 
                                    set        
                                            password   = :npwd,
                                            email      = :nemail,
                                            `full name`= :nfname
                                    where
                                            userID = :id
                                        ");
                $stmt->execute([
                        ':npwd'   => $pwd,
                        ':nemail' => $email,
                        ':nfname' => $fname,
                        ':id'     => $id
                ]);
                if($stmt){
                    $msg = '<div class="alert alert-success mt-3">your record updated successfully</div>';
                    redirect($msg);
                }
            }      
    }
    if($user){?>
        <h1 class="text-center">Edit <?= $userSession?> profile</h1>
        <div class="container ">
            <form action="" method="POST" class="needs-validation col-6 col-md-8 col-sm-10 mx-auto">
                <div class="mb-3">
                    <label for="uname" class="form-label">username</label>             
                    <input  class="form-control" type="text" name="uname" value="<?= htmlspecialchars($user['Uname']) ?>"/>
                </div>
                <div class="mb-3">
                    <label for="pwd" class="form-label">password</label>      
                    <input type="hidden" name="opwd" value="<?= htmlspecialchars($user['password']) ?>"/>       
                    <input class="form-control" type="password" name="npwd" autocomplete="new-password" />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>             
                    <input class="form-control" type="email" value="<?= htmlspecialchars($user['email'])?>" name="email"/>
                </div>
                <div class="mb-3">
                    <label for="fname" class="form-label">full name</label>             
                    <input class="form-control" type="text" value="<?= htmlspecialchars($user['full name'])?>" name="fname"/>
                </div>
                <div class="w-50 mx-auto">
                        <input class="btn btn-primary w-100 mt-2" type="submit" value="Save"/>
                </div>
            </form>
            <div class='mt-3 '>
                <?php 
                if(!empty($alerts)){
                    foreach($alerts as $alert){
                        echo $alert;   
                }         
            }?>
            </div>
        </div>
<?php 
    } else {
        $msg = '<div class="alert alert-danger"> User not found </div>';
        redirect($msg);
    }
}else{
    $msg = '<div class="alert alert-danger"> you can not access this page </div>';
    redirect($msg);
}
include $tpls.'footer.php';
?>