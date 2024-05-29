<?php
    session_start();
    $pageTitle = 'items';
    if(isset($_SESSION['username'])){
        include './init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        if($do == 'manage'){
            $approve = '';
            $manage = 'manage items';
            if(isset($_GET['approve']) && $_GET['approve'] == 'pind'){
                $approve = '&& items.RegStatus = 0';
                $manage = 'waiting items';
            }
            $stmt = $con->prepare("select 
                                        items.*,
                                        categories.name as category,
                                        users.Uname as member
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
                                    where 1
                                        $approve ");
            $stmt->execute();
            $items = $stmt->fetchAll();?>
            <div class="container ">
      <?php if(!empty($items)){?>
            <h1 class="text-center"><?php echo $manage ?></h1>
                <div class='table-responsive'>
                    <table class='main-table table table-bordered text-center'>
                        <thead>
                            <tr>
                                <th>#id</th>
                                <th>name</th>
                                <th>description</th>
                                <th>price</th>
                                <th>date</th>
                                <th>country</th>
                                <th>member</th>
                                <th>category</th>
                                <th>control</th>
                            </tr>
                        </thead> <?php
                         foreach($items as $item){
                            echo "<tr>";
                                echo "<td>".$item["item_id"]."</td>";
                                echo "<td>".$item["name"]."</td>";
                                echo "<td>".$item["description"]."</td>";
                                echo "<td>".$item["price"]."</td>";
                                echo "<td>".$item["add_date"]."</td>";
                                echo "<td>".$item["country_made"]."</td>";
                                echo "<td>".$item["member"]."</td>";
                                echo "<td>".$item["category"]."</td>";
                                echo "<td>
                                        <a href='items.php?do=edit&id=".$item['item_id']."' class='btn btn-success btn-sm'><i class='fa-sharp fa-regular fa-pen-to-square'></i> Edit</a>
                                        <a href='items.php?do=delete&id=".$item['item_id']."' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i> delete</a>";
                                        if($item["RegStatus"] == 0){
                                            echo "<a href='items.php?do=approve&id=".$item['item_id']."' class='btn btn-primary btn-sm activate'><i class='fa fa-check'></i> approve</a>";
                                        } 
                                echo "</td>";
                            echo "<tr>";
                        } 
                        ?>
                    </table>
                </div>
        <?php }else{
                    echo "<h1 class='text-center'>no items to manage</h1>";
                }?>
                <a href='?do=add' class='btn btn-primary w-50 d-block mx-auto'>+ add item</a>
            </div>
       <?php }elseif($do == 'add'){?>
            <h1 class="text-center">Add New item</h1>
            <div class="container">
                <form action="?do=insert" method="POST" class="needs-validation col-md-8 col-lg-6 mx-auto">
                    <div class="mb-3 row">
                        <label for="name" class="form-label col-sm-3">Name</label>
                        <div class="col-sm-9">
                            <input id="name" class="form-control" type="text" name="name" placeholder="Add item name" >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="description" class="form-label col-sm-3">Description</label>
                        <div class="col-sm-9">
                            <input id="description" class="form-control" type="text" name="description" placeholder="Describe the item" >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="price" class="form-label col-sm-3">Price</label>
                        <div class="col-sm-9">
                            <input id="price" class="form-control" type="text" placeholder="Add valid price" name="price" >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="country" class="form-label col-sm-3">Country</label>
                        <div class="col-sm-9">
                            <input id="country" class="form-control" type="text" placeholder="Add valid country" name="country" >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="status" class="form-label col-sm-3">Status</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="status" id="status" >
                                <option value="0">----</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="categories" class="form-label col-sm-3">Categories</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="cats" id="cats" >
                                <option value="0">----</option>
                                <?php
                                    $catstmt = $con->prepare("select id, name from categories");
                                    $catstmt->execute();
                                    $cats  = $catstmt->fetchAll();
                                    foreach($cats as $cat){
                                    echo "<option value=".$cat['id']." >".$cat['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="user" class="form-label col-sm-3">User</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="user" id="user" required>
                                <option value="0">----</option>
                                <?php
                                    $usertmt = $con->prepare("select userID, Uname from users");
                                    $usertmt->execute();
                                    $user  = $usertmt->fetchAll();
                                    foreach($user as $user){
                                    echo "<option value=".$user['userID']." >".$user['Uname']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-9 offset-sm-3">
                            <input class="btn btn-primary form-control" type="submit" value="Add">
                        </div>
                    </div>
                </form>
            </div>
       <?php 
       }elseif($do == 'insert'){
        echo '<div class="container mt-5">';
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo '<h1 class="text-center">Insert Member Data</h1>';
                $name    = $_POST['name'];
                $desc    = $_POST['description'];
                $price   = $_POST['price'];
                $country = $_POST['country'];
                $status  = $_POST['status'];
                $cats    = $_POST['cats'];
                $user    = $_POST['user'];
                //validation
                $Errors = array();
                if (empty($name)){
                    $Errors[] = 'you must enter item name';
                }
                if ($price < 0){
                    $Errors[] = 'price must be not less than 0';
                }
                if(empty($country)){
                    $Errors[] = 'insert item country';
                }
                if($status == null || $status == 0){
                    $Errors[] = 'choose status to the inserted item';
                }
                if($cats == null || $cats == 0){
                    $Errors[] = 'choose category to the inserted item';
                }
                if($user == null || $user == 0){
                    $Errors[] = 'choose member to the inserted item';
                }

                if(empty($Errors)){
                    if(countIsExist('name', 'items', $name) == 1){
                        $exist = '<div class="alert alert-info">'.$name.' is an exsisting item</div>';
                        redirect($exist);
                    }else{
                        $stmt = $con->prepare("insert into
                                                      items (name, description, price, add_date, country_made, status, cat_id , member_id , RegStatus)
                                                      values(:iname, :idesc, :iprice, now(), :icountry, :istatus, :icat_id, :imember_id , :iRegStatus)");
                        $stmt->execute(array(
                                'iname'      => $name,
                                'idesc'      => $desc,
                                'iprice'     => $price,
                                'icountry'   => $country,
                                'istatus'    => $status,
                                'icat_id'    => $cats,
                                'imember_id' => $user,
                                'iRegStatus' => '1'
                        ));
                        $count = $stmt->rowCount();
                        $MSG = "<div class='alert alert-success'>".$stmt->rowCount()." records Added</div>";                          
                        redirect($MSG);                              
                    }
                }else{
                    foreach($Errors as $error){
                        echo "<div class='alert alert-danger'> $error </div>";
                    }
                }
            }else{
                $erMmsg = '<div class="alert alert-danger">you can not access this page</div>';
                redirect($erMmsg);
            }
        echo '</div>';
       }elseif($do == 'edit'){
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            $stmt = $con->prepare(" select 
                                        items.*,
                                        categories.name as category,
                                        users.Uname as member
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
                                        item_id = ? ");
            $stmt-> execute(array($id));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            if($count > 0){?>
                <div class="container ">
                    <h1 class="text-center">edit item</h1>
                    <form action="?do=update" method="POST" class="needs-validation col-6 col-md-8 col-sm-10 mx-auto">
                        <input type='hidden' name='id' value = "<?php echo $row['item_id'] ?>"/>
                        <div class="mb-3">
                            <label for="name" class="form-label">name</label>             
                            <input id='name' class="form-control" type="text" name="name" value = "<?php echo $row['name'] ?>" />
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">description</label>      
                            <input id='description' class="form-control" type="text" name="description" value = "<?php echo $row['description'] ?>" />
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">price</label>             
                            <input id='price' class="form-control" type="text" value = "<?php echo $row['price'] ?>" name="price"/>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">country</label>             
                            <input id='country' class="form-control" type="text" value = "<?php echo $row['country_made'] ?>"" name="country"/>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">status</label>             
                            <select class="form-select" name="status" id="status">
                                <option value="0" <?php echo $row['status'] == 0 ? 'selected' : null ?> >----</option>
                                <option value="1" <?php echo $row['status'] == 1 ? 'selected' : null ?> >new</option>
                                <option value="2" <?php echo $row['status'] == 2 ? 'selected' : null ?> >like new</option>
                                <option value="3" <?php echo $row['status'] == 3 ? 'selected' : null ?> >used</option>
                                <option value="4" <?php echo $row['status'] == 4 ? 'selected' : null ?> >old</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cats" class="form-label">categories</label>             
                            <select class="form-select" name="cats" id="cats">
                                <option value="0" >----</option>
                                <?php
                                    $catsstmt = $con->prepare("select ID, name from categories");
                                    $catsstmt->execute();
                                    $cats  = $catsstmt->fetchAll();
                                    foreach($cats as $cat){
                                       echo "<option value=".$cat['ID'].($row['cat_id'] == $cat['ID'] ? ' selected' : null).">".$cat['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="users" class="form-label">users</label>             
                            <select class="form-select" name="users" id="users">
                                <option value="0" >----</option>
                                <?php
                                    $userstmt = $con->prepare("select userID, Uname from users");
                                    $userstmt->execute();
                                    $users  = $userstmt->fetchAll();
                                    foreach($users as $user){
                                       echo "<option value=".$user['userID'].($row['member_id'] == $user['userID'] ? ' selected' : null).">".$user['Uname']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="w-50 mx-auto">
                                <input class="btn btn-primary w-100 mt-2" type="submit" value="Save"/>
                        </div>
                </form>
        <?php
         $stmt = $con->prepare("select 
                                        comments.* ,
                                        users.Uname as member
                                from 
                                        comments
                                inner join
                                        users
                                on      
                                        users.userID = comments.user_id 
                                where   
                                        comments.item_id = $id    
                                                    ");
                $stmt->execute();
                $comments = $stmt->fetchAll();
                if(!empty($comments)){?>
                <h1 class="text-center">edit <?php echo $row['name']?> comments</h1>
                <div class='table-responsive'>
                <table class='main-table table table-bordered text-center'>
                    <thead>
                        <tr>
                            <th>comment</th>
                            <th>date</th>
                            <th>member</th>
                            <th>control</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($comments as $comment){
                            echo "<tr>";
                                echo "<td>$comment[comment]</td>";
                                echo "<td>$comment[date]</td>";
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
            echo '<h1 class="text-center"> no existing comments for '.$row["name"]. '</h1>';
        } ?>     
    </div>
    <?php }else{
                redirect('no such user');
            }
       }elseif($do == 'update'){
            echo "<div class='container'>";
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                echo "<h1 class='text-center'>item updated</h1>";
                $id      = $_POST['id'];
                $name    = $_POST['name'];
                $desc    = $_POST['description'];
                $price   = $_POST['price'];
                $country = $_POST['country'];
                $status  = $_POST['status'];
                $cats    = $_POST['cats'];
                $users   = $_POST['users'];

                $errors = array();
                if(empty($name)){
                    $errors[] = "Enter item's name";
                }
                if(empty($price)){
                    $errors[] = "Enter item's price";
                }
                if($status == 0){
                    $errors[] = "Enter item's status";
                }
                if($cats == 0 ){
                    $errors[] = "Enter item's category";
                }
                if($users ==0){
                    $errors[] = "Enter item's owner";
                }

                if(empty($errors)){
                    $stmt = $con->prepare("update
                                                items
                                            set
                                                name = ?,
                                                description = ?,
                                                price = ?,
                                                country_made = ?,
                                                status = ?,
                                                cat_id =?,
                                                member_id = ?
                                            where
                                                item_id = ? ");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $cats, $users , $id ));
                    $updated = '<div class="alert alert-success">'.$stmt->rowCount()." records updated</div>";  
                    redirect($updated);
                }else{
                    foreach($errors as $error){
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                }

            
            }else{
                    $erMmsg = '<div class="alert alert-danger">you can not access this page</div>';
                    redirect($erMmsg);
                }
        echo "</div>";
        }elseif($do == 'delete'){
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            echo "<div class='container'>";
            echo '<h1 class="text-center">Delete item</h1>';
            if(countIsExist('item_id', 'items', $id) > 0){
                $stmt = $con->prepare("delete from items where item_id = ?");
                $stmt->execute(array($id));
                $erMmsg = '<div class="alert alert-info">'.$stmt->rowCount().'record is deleted</div>';
                redirect($erMmsg);
            }else{
                $erMmsg = '<div class="alert alert-danger">user not existed</div>';
                redirect($erMmsg);
            }
            echo "</div>";
        }elseif($do == 'approve'){
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            echo "<div class='container'>";
            echo '<h1 class="text-center">item approved</h1>';
            if(countIsExist('item_id', 'items' , $id ) > 0){
                $stmt = $con->prepare("update items set RegStatus = ? where item_id = ?");
                $stmt->execute(array(1 , $id));
                echo "<div class='container'>";
                $erMmsg = '<div class="alert alert-info">'.$stmt->rowCount().'item is approved</div>';
                redirect($erMmsg);
            }else{
                $erMmsg = '<div class="alert alert-danger">item not existed</div>';
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