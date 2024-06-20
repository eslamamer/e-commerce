<?php
    session_start();
    $pageTitle = 'item';
    include 'init.php';
    $itemId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
    if(IsExist('item_id', 'items', $itemId) > 0){
        $stmt = $con->prepare("select 
                                        items.* ,
                                        categories.name as catName,
                                        users.Uname
                                from
                                        items
                                inner join
                                        categories
                                on
                                        categories.ID = items.cat_id
                                inner join
                                        users
                                on
                                        users.userID = items.member_id
                                where
                                        item_id = ?
                                and 
                                        items.RegStatus = ?
                                ");
        $stmt->execute(array($itemId, 1));
        $item = $stmt->fetch();
    }else{
        echo '<p class="alert alert-danger w-75 text-center mx-auto mt-4">item not existing</p>';
        exit();
    }
    $quantity = 1;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['quantity'])){
            $quantity = isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0 ? intval($_POST['quantity']) : 1;
        }
        if(isset($_POST['addCart'])){
            $stmt = $con->prepare("
                                    insert into
                                                orders(item_id, member_id, pieces, total_price, date)
                                                values(:aitem_id, :amember_id, :apieces, :atotal_price, NOW())
            ");
            $stmt->execute([
                ':aitem_id'     => $item['item_id'],
                ':amember_id'   => $_SESSION['uid'],
                ':apieces'      => $quantity,
                ':atotal_price' => $item['price']*$quantity
            ]);
        }
        if(isset($_SESSION['user'])  &&  isset($_POST['comment'])){
            $comment = htmlspecialchars($_POST['comment']);
            if(!empty($comment)){
                $stmt = $con->prepare("insert into 
                                            comments(comment, item_id, date, user_id)
                                            value(:ncomment, :nitem_id,now(), :nuser_id)");
                $stmt->execute(array(
                    ':ncomment' => $comment,
                    ':nitem_id' => $itemId,
                    ':nuser_id' => $_SESSION['uid']
                ));
                if($stmt){
                    $_SESSION['inserted'] = true;
                    header("location: ".$_SERVER['PHP_SELF']."?id=".$itemId);
                    exit();
                    }
                }
            }
    }  
    if(isset($item['RegStatus'])){?>
        <div class="container">
            <div class="row">
                <div class="col-md-5 mt-5">
                        <img src="./images/tempPH.jpeg" class="card-img-top" alt="Product Image">
                </div>
                <div class="col-md-7">
                    <h1><?= $item['name'] ?></h1>
                    <h6>prouduct id : <?= $item['item_id'] ?></h6>
                    <div class="mb-3">
                        <span class="h2">price : <?= $item['price']*$quantity ?>$</span>
                    </div>
                    <p class="short-description"><?= $item['description'] ?></p>
                    <div class="mb-3">
                        <span class="badge bg-success">In Stock</span>
                    </div>
                    <p class="card-text date float-start w-100">issued: <?= $item["add_date"]?></p>
                    <form method="POST" action="">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" id="quantity" name="quantity" class="form-control w-25" value="<?= htmlspecialchars($quantity) ?>" min="1">
                            </div>
                            <button type="submit" class="btn btn-primary me-2" name="Update">Update Quantity</button>
                            <button type="submit" class="btn btn-success me-2" name="addCart">add to cart <i class="fa-solid fa-cart-plus"></i></button>
                            <button type="button" class="btn btn-outline-secondary">Wishlist</button>
                    </form>
                    <hr>
                    <h3><?= $item['catName'] ?> category</h3>
                    <h4>added by : <?= $item['Uname'] ?></h4>
                    <h3>Specifications</h3>
                    <ul>
                        <li>Specification 1</li>
                        <li>Specification 2</li>
                    </ul>
                    <h3>Usage Instructions</h3>
                    <p>How to use the product.</p>
                    <h3>Warranty</h3>
                    <p>Warranty information.</p>
                </div>
            </div>
        </div>
        <hr>
        <div><?php $visaibilty = !isset($_SESSION['user']) ? 'disabled' : '';?>
            <form method="POST" action="<?php $_SERVER['PHP_SELF']?>" class="offset-5">
                <textarea <?php echo $visaibilty?> class="form-control mb-3" name="comment" placeholder = "add your comment"></textarea>
                <input type="submit" name='add comment' class='btn btn-primary w-100'>
            </form>
            <?php
                if(isset($_SESSION['inserted'])){
                    echo '<p class="alert alert-success offset-5 mt-3 col-md-7">your comment inserted successfully</p>';
                    unset($_SESSION['inserted']);
                }
            ?>
        </div>
        <hr>
        <?php
}else{
    echo '<p class="alert alert-danger w-75 text-center mx-auto mt-4">item is watting for approving</p>';
    
    }
    if(isset($_SESSION['user']) || isset($_SESSION['username'])){
        $stmt = $con->prepare("
                                select 
                                        comment, status, date , Uname 
                                from    
                                        comments
                                inner join
                                        users
                                on 
                                        comments.user_id = users.userID
                                where 
                                        comments.item_id = ?  
                                and
                                        comments.status = ?                            

                    ");
        $stmt->execute(array($itemId, 1));
        $comments = $stmt->fetchAll();
        foreach($comments as $comment){
            
            echo '<div class="row com-box ">';
                echo '<div class="col-md-2 person">
                        <img class="photo" src="./images/pers.jpeg" alt="'.$comment['Uname'].'" />
                        <p class="name text-center">'.$comment['Uname'].'</p>
                     </div>';
                echo '<div class="col-md-10 comment">
                          <div>'.$comment["comment"].'</div> 
                          <span>date: '.$comment["date"].'</span>
                     </div>';
            echo '</div>';
        }
    }else{
        echo '<p class="alert alert-info offset-5 mt-3 col-md-7">may you <a href="login.php">log in</a> or <a href="login.php?reg=signup">sign up</a> to show comments</p>';
    }
?>
<a href=""></a>
<?php
    include $tpls.'footer.php';
?>