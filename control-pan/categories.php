<?php
    session_start();
    $pageTitle = 'categories';
    if (isset($_SESSION['username'])){
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        if($do == 'manage'){
            $sort = 'DESC';
            $sort_arr = array('ASC', 'DESC');
            if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_arr)){
                $sort = $_GET['sort'];
            }
                $stmt = $con->prepare("select * from categories order by ordering $sort");
                $stmt->execute();
                $cats = $stmt->fetchAll();
            ?>
            <div class="container categories">
            <?php if(!empty($cats)){
                echo '<h1 class="text-center">manage categories</h1>';?>
                <div class='card'>
                    <div class='card-header'>
                        categories
                        <div class='float-end'>
                            ordering 
                            <a class = "<?php echo $sort == 'ASC'  ? 'active' : '' ?>" href="?sort=ASC">asc</a>
                            <a class = "<?php echo $sort == 'DESC' ? 'active' : '' ?>" href="?sort=DESC">desc</a>
                        </div>
                    </div>
                    <div class='card-body'>
                        <?php
                            foreach($cats as $cat){
                                echo "<div class='cats'>";
                                    echo "<div class='hidden-button'>";
                                        echo "<a href='?do=edit&id=".$cat['ID']."' class='btn btn-primary'> <i class='fa fa-edit'></i> edit</a>";
                                        echo "<a href='?do=delete&id=".$cat['ID']."'class='btn btn-danger'> <i class='fa fa-trash'></i> del</a>";
                                    echo "</div>";
                                    echo "<h5>".$cat['name']."</h5>";
                                    echo "<p>".(empty($cat['description']) ? 'there is no description to this item' : $cat['description'])."</p>";
                                    echo $cat['visibility']     == 0 ? "<span class='visibility'>hidden</span>"         : null;
                                    echo $cat['allow_comments'] == 0 ? "<span class='comments'> comment disable</span>" : null;
                                    echo $cat['allow_ads']      == 0 ? "<span class='ads'> ads disable</span>"          : null;
                                echo "</div>";
                                echo "<hr>";
                            }
                        ?>
                    </div>
                </div><?php } else{
                    echo '<h1 class="text-center">no existing categories</h1>';
                }?>
                <a href='?do=add' class='btn btn-primary d-block mx-auto w-50'>add category</a>
            </div>
        <?php
        }elseif($do == 'add'){?>
            <h1 class="text-center">Add New category</h1>
            <div class="container ">
                <form action="?do=insert" method="POST" class="needs-validation col-6 col-md-8 col-sm-10 mx-auto">
                    <div class="mb-3">
                        <label for="cat" class="form-label">name</label>             
                        <input id='cat' class="form-control" type="text" name="cat" placeholder="add new category" require/>
                    </div>
                    <div class="mb-3">
                        <label for="desc" class="form-label">description</label>      
                        <input id='desc' class="form-control" type="text" name="desc" placeholder="write your description" />
                    </div>
                    <div class="mb-3">
                        <label for="ordering" class="form-label">ordering</label>             
                        <input id='ordering' class="form-control" type="text" placeholder="put your order" name="order" />
                    </div>
                    <div class='d-flex justify-content-lg-between flex-wrap'>
                    <div class="mb-3 form-group">
                        <h6 class="form-label">visability</h6>
                            <div>
                                <div class='form-check'>
                                    <input id='visible' class='form-check-input' type="radio" value="1" name="visibility"/>
                                    <label for="visible" class='form-check-label'>visible</label>             
                                </div>
                                <div class='form-check'>             
                                    <input id='invisible' class='form-check-input' type="radio" value="0" name="visibility"/>
                                    <label for="invisible" class="form-check-label">invisible</label>
                                </div>
                            </div>
                    </div>
                    <div class="mb-3 form-group">
                        <h6 class="form-label">comments</h6>
                            <div >
                                <div class='form-check'>
                                    <input id='allow_comments' class='form-check-input' type="radio" value='1' name="comments"/>
                                    <label for="allow_comments" class="form-check-label" >allow</label>             
                                </div>
                                <div class='form-check'>             
                                    <input id='deny_comments' class='form-check-input' type="radio" value='0' name="comments"/>
                                    <label for="deny_comments" class="form-check-label">deny</label>
                                </div>
                            </div>
                    </div>
                    <div class="mb-3 form-group">
                        <h6 class="form-label">ads</h6>
                            <div>
                                <div class='form-check'>
                                    <input id='allow_ads' class='form-check-input' type="radio" value='1' name="ads"/>
                                    <label for="allow_ads" class="form-check-label">allow</label>             
                                </div>
                                <div class='form-check'>             
                                    <input id='deny_ads' class='form-check-input' type="radio" value='0' name="ads"/>
                                    <label for="deny_ads" class="form-check-label">deny</label>
                                </div>
                            </div>
                    </div>
                    </div>
                    <div class="w-50 mx-auto">
                            <input class="btn btn-primary w-100 mt-2" type="submit" value="Add"/>
                    </div>
                </form>
            </div>
       <?php 
        }elseif($do == 'insert'){
            echo "<div class='container mt-5'>";
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    echo '<h1 class="text-center">Insert Member Data</h1>';
                //post variables
                    $cat   = $_POST['cat'];
                    $desc  = $_POST['desc'];
                    $order = !empty($_POST['order']) ? $_POST['order'] : 1;
                    $vis   = isset($_POST['visibility']) ? $_POST['visibility'] : 1;
                    $comts = isset($_POST['comments']) ? $_POST['comments'] : 1;
                    $ads   = isset($_POST['ads']) ? $_POST['ads'] : 1;
                //check if category name not exist
                    if(!empty($cat)){
                    //check if item is existing
                        $count = countIsExist('name', 'categories', $cat);
                        if($count > 0){
                            echo '<div class="alert alert-info">'.$cat.' is an exsisting user</div>';
                            echo '<a href="categories.php" class="btn btn-info">back to categories page ?</a>';
                        }else{
                        $stmt = $con->prepare("insert into 
                                        categories(name , description , ordering , visibility , allow_comments , allow_ads)
                                        values(:cname, :cdescription , :cordering , :cvisibility , :callow_comments , :callow_ads) ");
                        $stmt->execute(array(
                            'cname'             => $cat,
                            'cdescription'      => $desc,
                            'cordering'         => $order,
                            'cvisibility'       => $vis,
                            'callow_comments'   => $comts,
                            'callow_ads'        => $ads
                        ));
                        $MSG = '<div class="alert alert-success">'.$stmt->rowCount()." records Added</div>";                          
                        redirect($MSG, sec:6);
                        }
                    }else{
                        $erMmsg = '<div class="alert alert-danger">Enter category name</div>';
                        redirect($erMmsg);
                    }
                }else{
                    $erMmsg = '<div class="alert alert-danger">you can not access this page</div>';
                    redirect($erMmsg);
                }
            echo "</div>";
        }elseif($do == 'edit'){
            $userID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            $stmt = $con->prepare("select * from categories where ID = ?");
            $stmt->execute(array($userID));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            if($count == 1){?>
                <h1 class="text-center">Edit category</h1>
                <div class="container ">
                <form action="?do=update" method="POST" class="needs-validation col-6 col-md-8 col-sm-10 mx-auto">
                    <input type="hidden" name="id" value="<?php echo $userID ?>" />
                    <div class="mb-3">
                        <label for="name" class="form-label">name</label>             
                        <input id='name' class="form-control" type="text" name="name" value="<?php echo $row['name'] ?>"/>
                    </div>
                    <div class="mb-3">
                        <label for="desc" class="form-label">description</label>      
                        <input id='desc' class="form-control" type="text" name="desc" value="<?php echo $row['description'] ?>" />
                    </div>
                    <div class="mb-3">
                        <label for="ordering" class="form-label">ordering</label>             
                        <input id='ordering' class="form-control" type="text" name="order" value="<?php echo $row['ordering'] ?>"/>
                    </div>
                    <div class='d-flex justify-content-lg-between flex-wrap'>
                        <div class="mb-3 form-group">
                            <h6 class="form-label">visability</h6>
                                <div>
                                    <div class='form-check'>
                                        <input id='visible' class='form-check-input' type="radio" value="1" name="visibility" <?php if ($row['visibility'] == 1) echo "checked" ?>/>
                                        <label for="visible" class='form-check-label'>visible</label>             
                                    </div>
                                    <div class='form-check'>             
                                        <input id='invisible' class='form-check-input' type="radio" value="0" name="visibility" <?php if ($row['visibility'] == 0) echo "checked"?>/>
                                        <label for="invisible" class="form-check-label">invisible</label>
                                    </div>
                                </div>
                        </div>
                        <div class="mb-3 form-group">
                            <h6 class="form-label">comments</h6>
                                <div >
                                    <div class='form-check'>
                                        <input id='allow_comments' class='form-check-input' type="radio" value='1' name="comments" <?php if ($row['allow_comments'] == 1) echo "checked" ?>/>
                                        <label for="allow_comments" class="form-check-label" >allow</label>             
                                    </div>
                                    <div class='form-check'>             
                                        <input id='deny_comments' class='form-check-input' type="radio" value='0' name="comments" <?php if ($row['allow_comments'] == 0) echo "checked" ?>/>
                                        <label for="deny_comments" class="form-check-label">deny</label>
                                    </div>
                                </div>
                        </div>
                        <div class="mb-3 form-group">
                            <h6 class="form-label">ads</h6>
                                <div>
                                    <div class='form-check'>
                                        <input id='allow_ads' class='form-check-input' type="radio" value='1' name="ads" <?php if ($row['allow_ads'] == 1) echo "checked"?>/>
                                        <label for="allow_ads" class="form-check-label">allow</label>             
                                    </div>
                                    <div class='form-check'>             
                                        <input id='deny_ads' class='form-check-input' type="radio" value='0' name="ads" <?php if ($row['allow_ads'] == 0) echo "checked"?>/>
                                        <label for="deny_ads" class="form-check-label">deny</label>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="w-50 mx-auto">
                            <input class="btn btn-primary w-100 mt-2" type="submit" value="Add"/>
                    </div>
                </form>
                </div>
    
      <?php }else{
                $erMmsg = 'no such user<div>';
                redirect($erMmsg);
            }
        }elseif($do == 'update'){
            echo "<div class='container mt-5'>";
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    echo '<h1 class="text-center">update Member Data</h1>';
                        $id       = $_POST['id'];
                        $name     = $_POST['name'];
                        $desc     = $_POST['desc'];
                        $order    = $_POST['order'];
                        $vis      = $_POST['visibility'];
                        $comments = $_POST['comments'];
                        $ads      = $_POST['ads'];

                        if(!empty($name)){
                            $stmt = $con->prepare("update categories set name = ? , description = ? , ordering = ? , visibility = ? , allow_comments = ? , allow_ads = ? where ID= ? ");
                            $stmt->execute(array($name , $desc , $order , $vis ,$comments  ,$ads , $id ));
                            echo '<div class="alert alert-success">'.$stmt->rowCount()." records updated</div>";  
                            header("refresh:5, url=categories.php");
                        }else{
                            $erMmsg = '<div class="alert alert-danger">Enter category name</div>';
                            redirect($erMmsg);
                        }
                }else{
                    $erMmsg = '<div class="alert alert-danger">you can not access this page</div>';
                    redirect($erMmsg);
                }
            echo "</div>";
        }elseif($do == 'delete'){
            echo "<div class='container'>";
            echo '<h1 class="text-center">Delete Member Data</h1>';
            $userID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            $userCount = countIsExist('id', 'categories', $userID);
            if($userCount > 0){
                $stmt = $con->prepare("delete from categories where id = :dID");
                $stmt->bindParam(':dID', $userID );
                $stmt->execute();
                $MSG = '<div class="alert alert-success">'.$stmt->rowCount()." records Deleted</div>";  
                echo '<div class="alert alert-success">User with id number '.$userID." is Deleted</div>";
                redirect($MSG);  
            }else{
                $erMmsg = '<div class="alert alert-danger">user not existed</div>';
                redirect($erMmsg);
            }
            echo "</div>";
        }
            include $tpls.'footer.php';
            exit();
    }else{
        header('location: index.php');
        exit();
    }

    
?>