<?php
    session_start();
    $pageTitle ='create new ads';
    if(isset($_SESSION['user']) || isset($_SESSION['username'])){
        include 'init.php';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
             if(isset($_POST['name'])){$title = filter_var($_POST['name'], FILTER_SANITIZE_STRING);}
             if(isset($_POST['description'])){ $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);}
             if(isset($_POST['price'])){ $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);}
             if(isset($_POST['country'])){ $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);}
             if(isset($_POST['status'])){$status =  filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);}
             if(isset($_POST['cats'])){ $cats =   filter_var($_POST['cats'], FILTER_SANITIZE_NUMBER_INT);}

            
            print_r($_POST);
            $MSGS = [];

            if(strlen($title) < 3){
                $MSGS [] = 'title must be not less than 3 characters';
            }
            if(strlen($desc) < 10){
                $MSGS [] = 'description must be not less than 20 characters';
            }
            if($price <= 0){
                $MSGS [] = 'price must be grater than 0';
            }
            if(empty($country)){
                $MSGS [] = 'country must be not empty';
            }
            if(empty($status)){
                $MSGS [] = 'status must be not empty';
            }
            if(empty($cats)){
                $MSGS [] = 'cats must be not empty';
            }
            if(empty($MSGS)){
                $uid = isset($_SESSION['uid']) && is_numeric($_SESSION['uid']) ? intval($_SESSION['uid']) : 0;
                if(IsExist('name', 'items', $title) == 1){
                    $MSGS[] = '<div class="alert alert-info">'.$title.' is an exsisting item</div>';
                    redirect($exist);
                }else{
                    $stmt = $con->prepare("insert into
                                                  items (name, description, price, add_date, country_made, status, cat_id , member_id , RegStatus)
                                                  values(:iname, :idesc, :iprice, now(), :icountry, :istatus, :icat_id, :imember_id , :iRegStatus)");
                    $stmt->execute(array(
                            'iname'      => $title,
                            'idesc'      => $desc,
                            'iprice'     => $price,
                            'icountry'   => $country,
                            'istatus'    => $status,
                            'icat_id'    => $cats,
                            'imember_id' => $uid,
                            'iRegStatus' => '0'
                    ));
                    $count = $stmt->rowCount();
                    $MSG = "<div class='alert alert-success'>".$stmt->rowCount()." records Added</div>";                          
                    redirect($MSG);
                }
            }
        }
    ?>
    <h1 class='text-center'><?php echo $pageTitle?></h1>
    <div class='ads'>
        <div class='container'>
            <div class='card mb-4'>
                <div class='card-header bg-primary'><?php echo $pageTitle?></div>
                <div class='card-body'>
                    <div class="col-sm-6 col-md-8 float-start">
                            <form action="?do=insert" method="POST" class="needs-validation col-md-10 mx-auto">
                                <div class="mb-3 row">
                                    <label for="name" class="form-label col-sm-5">Name</label>
                                    <div class="col-sm-9">
                                        <input id="name" class="form-control live" data-class=".live-title" type="text" name="name" placeholder="Add advertisement name" >
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="description" class="form-label col-sm-5" >Description</label>
                                    <div class="col-sm-9">
                                        <input id="description" class="form-control live" data-class=".live-desc" type="text" name="description" placeholder="Describe the advertisement" >
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="price" class="form-label col-sm-6" >Price</label>
                                    <div class="col-sm-9">
                                        <input id="price" class="form-control live" data-class=".live-price" type="text" placeholder="Add valid price" name="price" >
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="country" class="form-label col-sm-6">Country</label>
                                    <div class="col-sm-9">
                                        <input id="country" class="form-control" type="text" placeholder="Add valid country" name="country" >
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="status" class="form-label col-sm-6">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="status" id="status" >
                                            <option value="0" disabled selected></option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Used</option>
                                            <option value="4">Old</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="cats" class="form-label col-sm-6">Categories</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="cats" id="cats" >
                                            <option value="0" disabled selected></option>
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
                                    <div class="col-sm-9">
                                        <input class="btn btn-primary form-control" type="submit" value="Add advertisement" >
                                    </div>
                                </div>
                            </form>
                            <?php
                                    if(!empty($MSGS)){
                                    foreach ($MSGS as $msg) {
                                        echo "<p class='alert alert-danger'>$msg</p>";
                                    }
                                }
                            ?>
                        </div>
                        <div class="col-sm-6 col-md-4 float-end">
                            <div class="card mb-3">
                                <a href="./ad.php" class =""><img src="./images/tempPH.jpeg" alt="name" class="card-img-top"></a>
                                <div class="card-body">
                                    <a href="#" class ="text-decoration-none live-title" ><h4 class="card-title">item name</h4></a>
                                    <p class="card-text live-desc">description</p>
                                    <p class="card-text live-price d-inline-block">price </p> <span>$</span>
                                </div>
                            </div>
                        </div>
                    </div>
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