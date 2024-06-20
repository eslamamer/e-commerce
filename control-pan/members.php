<?php
    session_start();
    $pageTitle = 'members';
    if(isset($_SESSION['username'])){
        include './init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : "manage";
        if($do == 'manage'){
            $pind = '';
            $manage = 'Manage Member';
            if(isset($_GET['page']) && $_GET['page'] == 'pind'){
                $pind   = 'and RegStatus = 0'; 
                $manage = 'waiting members';
            }
            $stmt = $con->prepare("select * from users where groupID != 1 $pind order by userID desc");
            $stmt->execute();
            $rows = $stmt->fetchAll();?>
                <div class="container ">
            <?php if(!empty($rows)){?>
                <h1 class="text-center"><?php echo $manage ?></h1>
                    <div class='table-responsive'>
                        <table class='main-table table table-bordered text-center'>
                            <thead>
                                <tr>
                                    <th>#id</th>
                                    <th>user name</th>
                                    <th>email</th>
                                    <th>full name</th>
                                    <th>Registration Date</th>
                                    <th>control</th>
                                </tr>
                            </thead>
                        <?php
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>".$row["userID"]."</td>";
                                    echo "<td>".$row["Uname"]."</td>";
                                    echo "<td>".$row["email"]."</td>";
                                    echo "<td>".$row["full name"]."</td>";
                                    echo "<td>".$row["Registration Date"]."</td>";
                                    echo "<td>
                                            <a href='members.php?do=edit&userid=".$row['userID']."' class='btn btn-success btn-sm'><i class='fa-sharp fa-regular fa-pen-to-square'></i> Edit</a>
                                            <a href='members.php?do=delete&userid=".$row['userID']."' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i> delete</a>";
                                        if($row["RegStatus"] == 0){
                                            echo "<a href='members.php?do=activate&userid=".$row['userID']."' class='btn btn-primary btn-sm activate'><i class='fa fa-check'></i> activate</a>";
                                        }  
                                    echo "</td>";
                                echo "<tr>";
                            }
                        ?>
                        </table>
                    </div>
                <?php }else{
                echo '<h1 class="text-center"> no '.$manage.'</h1>';
                 }?>
                    <a href='?do=add' class='btn btn-success'>+ add member</a>
                </div>
    <?php }elseif ($do == 'add'){?>
            <h1 class="text-center">Add New Member</h1>
            <div class="container ">
                <form action="?do=insert" method="POST" class="needs-validation col-6 col-md-8 col-sm-10 mx-auto">
                    <!-- <div class="mb-3">
                        <label for="uid" class="form-label">user ID</label>             
                        <input class="form-control" type="text" name="uid" value="<?php echo $row['userID']?>" disabled  />
                    </div> -->
                    <div class="mb-3">
                        <label for="uname" class="form-label">username</label>             
                        <input id='uname' class="form-control" type="text" name="uname" placeholder="add user name" autocomplete="off"/>
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label">password</label>      
                        <input id='pwd' class="form-control" type="password" name="pwd" placeholder="insert strong password" autocomplete="new-password" />
                        <i class="fa-regular fa-eye"></i>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>             
                        <input id='email' class="form-control" type="email" placeholder="add valid Email" name="email" autocomplete='on'/>
                    </div>
                    <div class="mb-3">
                        <label for="fname" class="form-label">full name</label>             
                        <input id='fname' class="form-control" type="text" placeholder="add your full name" name="fname"/>
                    </div>
                    <div class="w-50 mx-auto">
                            <input class="btn btn-primary w-100 mt-2" type="submit" value="Add"/>
                    </div>
                </form>
            </div>       
        <?php
        }elseif ($do == 'insert'){
            echo '<div class="container mt-5">';
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo '<h1 class="text-center">Insert Member Data</h1>';
                //post variables
                $uname = $_POST['uname'];
                $email = $_POST['email'];
                $fname = $_POST['fname'];
                $pwd   = $_POST['pwd'];

                //password handling 
                if(!empty($pwd)){
                    $pwd = sha1($pwd);
                }
                //validation
                $Errors = array();
                if (empty($uname)){
                    $Errors[] = 'you must enter name';
                }
                if (strlen($uname) <3){
                    $Errors[] = 'name must be not less than 3 characters';
                }
                if(empty($pwd)){
                    $Errors[] = 'insert your password';
                }
                if(empty($email)){
                    $Errors[] = 'insert your E-mail';
                }

                foreach($Errors as $error){
                    echo "<div class='alert alert-danger'> $error </div>";
                }

                if(empty($Errors)){
                    if(countIsExist("Uname", "users", $uname) == 1){
                        $usereExisting =  '<div class="alert alert-info">'.$uname.' is an exsisting user</div>';
                        redirect($usereExisting);
                    }else{
                        // $stmt = $con->prepare("insert users set Uname =?, email  =? , password=?, `full name` =?");
                        // $stmt->execute(array($uname, $email,$pwd, $fname));
                        $stmt = $con->prepare('insert into 
                        users(Uname, email, password, `full name`, `Registration Date` ,RegStatus)
                        values(:nuser , :nemail , :npwd , :nfname  ,  now() , 1)');
                        $stmt->execute(array(
                            'nuser'  => $uname,
                            'nemail' => $email,
                            'npwd'   => $pwd,
                            'nfname' => $fname,
                            ));
                        $MSG = '<div class="alert alert-success">'.$stmt->rowCount()." records Added</div>";                          
                        redirect($MSG, sec:6);
                    }
                    
                }
                
            }else{
                $erMmsg = '<div class="alert alert-danger">you can not access this page</div>';
                redirect($erMmsg);
           }
           echo '</div>';

        }elseif ($do == 'edit'){
            $userID = isset($_GET['userid'])&& is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            $stmt = $con->prepare("select * from users where userID = ? limit 1");
            $stmt->execute(array($userID));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            if($count > 0){?>
                <h1 class="text-center">Edit Member</h1>
                <div class="container ">
                    <form action="?do=update" method="POST" class="needs-validation col-6 col-md-8 col-sm-10 mx-auto">
                        <input type="hidden" name="uid" value=<?php echo $userID?> />
                        <!-- <div class="mb-3">
                            <label for="uid" class="form-label">user ID</label>             
                            <input class="form-control" type="text" name="uid" value=<?php echo $row['userID']?> disabled  />
                        </div> -->
                        <div class="mb-3">
                            <label for="uname" class="form-label">username</label>             
                            <input class="form-control" type="text" name="uname" value="<?php echo $row['Uname'] ?>" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label for="pwd" class="form-label">password</label>      
                            <input type="hidden" name="opwd" value="<?php echo $row['password'] ?>"/>       
                            <input class="form-control" type="password" name="npwd" autocomplete="new-password" />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>             
                            <input class="form-control" type="email" value="<?php echo $row[3]?>" name="email"/>
                        </div>
                        <div class="mb-3">
                            <label for="fname" class="form-label">full name</label>             
                            <input class="form-control" type="text" value="<?php echo $row[4]?>" name="fname"/>
                        </div>
                        <div class="w-50 mx-auto">
                                <input class="btn btn-primary w-100 mt-2" type="submit" value="Save"/>
                        </div>
                    </form>
                    <?php
                            $stmt = $con->prepare("select 
                                                            comments.* ,
                                                            items.name as item
                                                    from 
                                                            comments
                                                    inner join
                                                            items
                                                    on 
                                                            items.item_id = comments.item_id
                                                    where  
                                                            comments.user_id = $userID");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            if(!empty($rows)){?>
                            <h1 class="text-center"><?php echo 'edit '.$row['Uname'].' comments' ?></h1>
                            <div class="container ">
                            <div class='table-responsive'>
                            <table class='main-table table table-bordered text-center'>
                            <thead>
                                <tr>
                                    <th>comment</th>
                                    <th>date</th>
                                    <th>item</th>
                                    <th>control</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($rows as $comment){
                                    echo "<tr>";
                                        echo "<td>$comment[comment]</td>";
                                        echo "<td>$comment[date]</td>";
                                        echo "<td>$comment[item]</td>";
                                        echo "<td>
                                            <a href='comments.php?do=edit&id=".$comment['id']."' class='btn btn-success btn-sm'><i class='fa-sharp fa-regular fa-pen-to-square'></i> Edit</a>
                                            <a href='comments.php?do=delete&id=".$comment['id']."' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i> delete</a>";
                                                if($comment["status"] == 0){
                                                    echo "<a href='comments.php?do=approve&id=".$comment['id']."' class='btn btn-primary btn-sm activate'><i class='fa fa-check'></i> approve</a>";
                                                } 
                                        echo "</td>";
                                    echo "<tr>";
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }else{
                      echo '<h1 class="text-center"> no existing comments for '.$row["Uname"]. '</h1>';
                }?>
            </div>
    
    <?php    }else{
                $erMmsg = 'no such user';
                redirect($erMmsg);
            }
        }elseif($do == 'update'){
            echo '<h1 class="text-center">Update Member Data</h1>';
            echo '<div class="container">';
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        //post variables
                        $id    = $_POST['uid'];
                        $uname = $_POST['uname'];
                        $email = $_POST['email'];
                        $fname = $_POST['fname'];
                        $pwd   = '';

                        //password handling 
                        $pwd = empty($_POST['npwd']) ? $_POST['opwd'] : sha1($_POST['npwd']);

                        //validation
                        $Errors = array();
                        if (empty($uname)){
                            $Errors[] = 'you must enter name';
                        }
                        if (strlen($uname) <3){
                            $Errors[] = 'name must be not less than 3 characters';
                        }
                        if(empty($pwd)){
                            $Errors[] = 'insert your password';
                        }
                        if(empty($email)){
                            $Errors[] = 'insert your E-mail';
                        }

                        foreach($Errors as $error){
                            echo "<div class='alert alert-danger'> $error </div>";
                        }

                        if(empty($Errors)){
                            $checkmember = $con->prepare("select * from users where Uname = ? and userID != ?");
                            $checkmember->execute(array($uname, $id));
                            $count = $checkmember->rowCount();
                            echo $count;
                            if($count > 0 ){
                                $erMmsg = '<div class="alert alert-danger">the username is existing</div>';
                                redirect($erMmsg);
                            }else{
                                $stmt = $con->prepare("update users set Uname =?, email  =? , password=?, `full name` =? where userID = ? ");
                                $stmt->execute(array($uname, $email,$pwd, $fname , $id  ));
                                echo '<div class="alert alert-success">'.$stmt->rowCount()." records updated</div>";  
                                header("refresh:5, url=members.php");
                            }
                    }
                    //else for post request    
                    }else{
                        $erMmsg = '<div class="alert alert-danger">you can not access this page</div>';
                        redirect($erMmsg);
                    }
            echo '</div>';
        }elseif($do == 'delete'){
            //echo '<script>alert("are you sure you want to delete this user ?")</script>';
            echo '<div class="container">';
            echo '<h1 class="text-center">Delete Member Data</h1>';
            $userId = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            $userCount = countIsExist('userID', 'users', $userId);
                if($userCount > 0){
                    $stmt = $con->prepare('delete from users where userID = :duser');
                    $stmt->bindParam(':duser', $userId);
                    $stmt->execute();
                    $MSG = '<div class="alert alert-success">'.$stmt->rowCount()." records Deleted</div>";  
                    echo '<div class="alert alert-success">User with id number '.$userId." is Deleted</div>";
                    redirect($MSG);  
                }else{
                    $erMmsg = '<div class="alert alert-danger">user not existed</div>';
                    redirect($erMmsg);
                }
            echo '</div>';
        } elseif($do == 'activate'){
            echo '<h1 class="text-center">Activate Member</h1>';
            echo '<div class="container">';
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            $count = countIsExist('userID', 'users', $userid);
            if($count > 0){
                $stmt = $con->prepare("update users set RegStatus = ? where userID =?");
                $stmt->execute(array('1',  $userid));
                $MSG = '<div class="alert alert-success">'.$stmt->rowCount()." records Updated</div>"; 
                echo '<div class="alert alert-success">User with id number '.$userid." is Updated</div>"; 
                redirect($MSG);
            }else{
                $erMmsg = '<div class="alert alert-danger">user not existed</div>';
                redirect($erMmsg);
            }
            echo '</div>';

        }
        include $tpls.'footer.php';
        exit();
    }else{
        header('location:index.php');
        exit();
    } 
?>