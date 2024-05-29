<?php
    session_start();
    $pageTitle = $userSession.' profile';
    include 'init.php';
    if(isset($_SESSION['user'])||isset($_SESSION['username'])){
    ?>
    <h1 class='text-center'><?php echo $userSession.' profile'?></h1>
    <div class='info'>
        <div class='container'>
            <div class='card mb-4'>
                <div class='card-header bg-primary'>information</div>
                <div class='card-body'>
                <?php
                    $data = getElement('users', 'userID', 'Uname', $userSession);
                    $id = $data['userID'];
                    echo '<p><strong>name</strong> : '.$data['Uname'].'</p>';
                    echo '<p><strong>e-mail</strong> : '.$data['email'].'</p>';
                    echo '<p><strong>full name</strong> : '.$data['full name'].'</p>';
                    echo '<p><strong>date</strong> : '.$data['Registration Date'].'</p>';?>
                </div>
            </div>
        </div>
    </div>
    <div class='ads'>
        <div class='container'>
            <div class='card mb-4'>
                <div class='card-header bg-primary'>ads</div>
                <div class='card-body'>
                <?php
                $items = getElement('items', 'item_id', 'member_id', $id);
                echo '<div class="row">';
                $item ="";
                if(!empty($items)){
                    foreach($items as $item){
                        $item = $item;
                        echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="card mb-3">';
                            if($item['RegStatus'] == 0){
                                echo '<span class="alert alert-danger w-50 approving">not approved</span>';
                            }
                                echo '<a href="item.php?id='.$item['item_id'].'" class =""><img src="./images/tempPH.jpeg" alt="'.$item["name"].'" class="card-img-top"></a>';
                                echo '<div class="card-body">';
                                    echo '<a href="item.php?id='.$item['item_id'].'" class ="text-decoration-none"><h5 class="card-title">'.$item["name"].'</h5></a>';
                                    echo '<p class="card-text text-truncate">'.$item["description"].'</p>';
                                    echo '<p class="card-text">'.$item["price"].' $</p>';
                                    echo '<p class="card-text date">created : '.$item["add_date"].'</p>';
                                    echo '<a href="#" class="btn btn-primary btn-block">Buy Now</a>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        }
                    }else{
                        echo "<p>no ads existing for ".$userSession."</p>";
                    }?>
                    </div>
                    <a class='btn btn-primary float-end' href='ads.php'>add ads</a>
                </div>
            </div>
        </div>
    </div>
    <div class='latest comments'>
        <div class='container'>
            <div class='card mb-4'>
                <div class='card-header bg-primary'>latest comments</div>
                <div class='card-body'>
                <?php
                $comments = getElement('comments', 'id', 'user_id', $id);
                if(! empty($comments)){
                    if(is_array(reset($comments))){
                        foreach($comments as $comment){
                            echo '<div>';
                                $items = getElement('items', 'item_id', 'item_id', $comment["item_id"]);
                                if(!empty($items))
                                    echo '<p><strong>item name</strong> : '.$items['name'].'</p>';
                                    echo '<p><strong>comment</strong> : '.$comment['comment'].'</p>';
                            echo '</div>';
                        }

                    }else{
                        echo '<div>';
                                $items = getElement('items', 'item_id', 'item_id', $comments["item_id"]);
                                if(!empty($items))
                                    echo '<p><strong>item name</strong> : '.$items['name'].'</p>';
                                    echo '<p><strong>comment</strong> : '.$comments['comment'].'</p>';
                            echo '</div>';
                    }
               
            }else{
                echo "<p>no comments existing for ".$userSession."</p>";
            }
            ?>
             </div>
            </div>
        </div>
    </div>
<?php
    }else{
        header("location: login.php");
    }
    include $tpls.'footer.php';
?>