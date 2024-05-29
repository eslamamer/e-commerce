<?php
    session_start();
    $pageTitle = 'dashboard';
    if(isset($_SESSION['username'])){
        include './init.php';
        //start dashboard page
        $LatestNum = 5;
        ?>
    <div class='latest'>
        <div class='container text-center states'>
            <h1 class='text-center'>dashboard</h1>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='state total-member'>
                        total members
                        <span><a href="members.php"><?php echo countItems('userID', 'users') ?></a></span>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='state pending'>
                        pending members
                        <span><a href="members.php?page=pind"><?php echo countIsExist('RegStatus', 'users', 0) ?></a></span>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='state items'>
                        total items
                        <span><a href="items.php"><?php echo countItems('item_id', 'items') ?></a></span>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='state comments'>
                        total comments
                        <span><a href="comments.php"><?php echo countItems('id', 'comments') ?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='latest'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-header'>
                            <i class='fa fa-user'></i>latest <?php echo $LatestNum ?> registered user :
                        </div>
                        <div class='card-body'>
                            <?php
                            $users = latest('*','users', 'userID' , $LatestNum);
                            echo '<ul class="list-unstyled">';
                            foreach($users as $user){
                                echo "<li>"
                                        .$user['Uname'].
                                        "<a href='members.php?do=delete&userid=".$user['userID'].
                                        "' class='btn btn-danger float-end btn-sm'>
                                            <i class='fa fa-trash'></i> Delete
                                        </a>
                                        <a href='members.php?do=edit&userid=".$user['userID'].
                                            "' class='btn btn-success float-end btn-sm'>
                                            <i class='fa-sharp fa-regular fa-pen-to-square'></i>
                                                Edit
                                        </a>";
                                        if($user["RegStatus"] == 0){
                                            echo "<a href='members.php?do=activate&userid=".$user['userID'].
                                                    "'class='btn btn-primary btn-sm float-end activate'>
                                                    <i class='fa fa-check'></i> Activate
                                                  </a>";
                                            }  
                                    echo "</li>";
                                    }
                                echo '</ul>';
                            ?>
                        </div>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-header'>
                            <i class='fa fa-tag'></i>latest <?php echo $LatestNum ?> items :

                        </div>
                        <div class='card-body'>
                        <?php
                            $items = latest('*', 'items', 'item_id', $LatestNum);
                            echo '<ul class="list-unstyled">';
                            foreach($items as $item){
                                echo "<li>"
                                        .$item['name'].
                                        "<a href='items.php?do=delete&id=".$item['item_id'].
                                        "' class='btn btn-danger float-end btn-sm'>
                                            <i class='fa fa-trash'></i> Delete
                                        </a>
                                        <a href='items.php?do=edit&id=".$item['item_id'].
                                            "' class='btn btn-success float-end btn-sm'>
                                            <i class='fa-sharp fa-regular fa-pen-to-square'></i>
                                                Edit
                                        </a>";
                                        if($item["RegStatus"] == 0){
                                            echo "<a href='items.php?do=approve&id=".$item['item_id'].
                                                    "'class='btn btn-primary btn-sm float-end activate'>
                                                    <i class='fa fa-check'></i> approve
                                                  </a>";
                                            }  
                                    echo "</li>";
                                    }
                            echo '</ul>';
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='latest'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-header'>
                            <i class='fa fa-comments'></i>latest <?php echo $LatestNum ?> comments :
                        </div>
                        <div class='card-body'>
                    <?php   $stmt = $con->prepare("select
                                                            comments.comment,
                                                            users.Uname as member
                                                    from
                                                            comments
                                                    inner join
                                                            users
                                                    on
                                                            comments.user_id = users.userID
                                                    order by
                                                            id desc 
                                                    limit 
                                                            $LatestNum
                                                                 ");
                            $stmt->execute();
                            $comments = $stmt->fetchAll();
                            foreach($comments as $comment){
                                echo "<div class = 'com-box'>";
                                    echo "<span class='member'>".$comment['member']."</span>";
                                    echo "<p class ='comment'>".$comment['comment']."</p>";
                                echo "</div>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        //end dashboard page
        include $tpls.'footer.php';
        exit();
    }
    else{
        header('location:index.php');
        exit();
    }
?>