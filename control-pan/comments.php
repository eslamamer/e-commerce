<?php
    session_start();
    $pageTitle = 'comments';
    if(isset($_SESSION['username'])){
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        if($do == 'manage'){
            $pind = '';
            $manage = 'manage comments';
            if(isset($_GET['approve']) && $_GET['approve'] == 'pind'){
                $pind = '&& comments.status = 0';
                $manage = 'waiting comments';
            }
            $stmt = $con->prepare("select 
                                            comments.* ,
                                            items.name as item,
                                            users.Uname as member
                                    from 
                                            comments
                                    inner join
                                            items
                                    on 
                                            items.item_id = comments.item_id
                                    inner join
                                            users
                                    on      
                                            users.userID = comments.user_id 
                                    where  
                                            1  $pind 
                                    order by 
                                            id desc ");
            $stmt->execute();
            $rows = $stmt->fetchAll();?>
            <div class="container ">
     <?php   if(! empty($rows)){ ?>
                <h1 class="text-center"><?php echo $manage ?></h1>
                <div class='table-responsive'>
                    <table class='main-table table table-bordered text-center'>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>comment</th>
                                <th>date</th>
                                <th>item_id</th>
                                <th>item</th>
                                <th>member_id</th>
                                <th>member</th>
                                <th>control</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($rows as $comment){
                                echo "<tr>";
                                    echo "<td>$comment[id]</td>";
                                    echo "<td>$comment[comment]</td>";
                                    echo "<td>$comment[date]</td>";
                                    echo "<td>$comment[item_id]</td>";
                                    echo "<td>$comment[item]</td>";
                                    echo "<td>$comment[user_id]</td>";
                                    echo "<td>$comment[member]</td>";
                                    echo "<td>
                                        <a href='comments.php?do=edit&id=".$comment['id']."' class='btn btn-success btn-sm'><i class='fa-sharp fa-regular fa-pen-to-square'></i> Edit</a>
                                        <a href='comments.php?do=delete&id=".$comment['id']."' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i> delete</a>";
                                            if($comment["status"] == 0){
                                                echo "<a href='comments.php?do=approve&id=".$comment['id']."' class='btn btn-primary btn-sm activate'><i class='fa fa-check'></i> approve</a>";
                                            } 
                                    echo "</td>";
                                echo "<tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
        <?php }else{
                  echo  "<h1 class='text-center'>no available comments</h1>";
                } ?>
            </div>
  <?php }elseif($do == 'edit'){
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            $stmt = $con->prepare("select comment from comments where id = ? ");
            $stmt->execute(array($id));
            $row = $stmt->fetch();
            if($row > 0){?>
                <h1 class="text-center">edit comment</h1>
                <div class="container"> 
                    <form action="?do=update" method="POST" class="needs-validation col-md-8 col-lg-6 mx-auto">
                        <input type="hidden" name = "id" value = "<?php echo $id ?>"/>
                        <div class="mb-3">
                            <label for="comment" class="form-label">edit the comment</label>             
                            <textarea id='comment' name='comment' class="form-control" cols="30" rows="5"><?php echo $row['comment'] ?></textarea>
                        </div>
                        <div class="w-100 mx-auto">
                                <input class="btn btn-primary w-100 mt-2" type="submit" value="Edit"/>
                        </div>
                    </form>
                </div>
            <?php  }
        }elseif($do == 'update'){
            echo '<h1 class="text-center">Update comment</h1>';
            echo '<div class="container">';
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $id      = $_POST['id'];
                    $comment = $_POST['comment'];
                    $stmt = $con->prepare("update comments set comment = ?  where id = ?");
                    $stmt->execute(array($comment , $id));
                    echo '<div class="alert alert-success">'.$stmt->rowCount()." records updated</div>";  
                    echo "<div class='alert alert-info'>you will return to comments page in 5 seconds</div>"; 
                    header("refresh:5, url=comments.php");
                }else{
                    $erMmsg = '<div class="alert alert-danger">you can not access this page</div>';
                    redirect($erMmsg);
                }
            echo '</div>';
        }elseif($do == 'delete'){
            echo '<h1 class="text-center">delete comment</h1>';
            echo '<div class="container">';
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            $count = countIsExist('id', 'comments', $id);
            if($count > 0){
                $stmt = $con->prepare("delete from comments where id = :dID");
                $stmt->bindParam(':dID', $id );
                $stmt->execute();
                $MSG = '<div class="alert alert-success">'.$stmt->rowCount()." records Deleted</div>";  
                echo '<div class="alert alert-success">User with id number '.$id." is Deleted</div>";
                redirect($MSG);  
            }else{
                $erMmsg = '<div class="alert alert-danger">user not existed</div>';
                redirect($erMmsg);
            }              
            echo '</div>';
        }elseif($do == 'approve'){
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            echo '<h1 class="text-center">delete comment</h1>';
            echo '<div class="container">';
            $count = countIsExist ('id', 'comments' , $id);
            if($count > 0 ){
                $stmt = $con->prepare("update comments set status = ? where id = ?");
                $stmt->execute(array(1, $id));
                $MSG = '<div class="alert alert-success">'.$stmt->rowCount()." records approved</div>";  
                echo '<div class="alert alert-success">User with id number '.$id." is approved</div>";
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
        header("location:index.php");
        exit();
    }
    ?>
