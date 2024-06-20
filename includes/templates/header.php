<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./layout/css/bootstrap.min.css"/>
            <link rel="stylesheet" href="./layout/fontawesome-free-6.5.1-web/css/all.min.css"/>
            <link rel="stylesheet" href="./layout/css/style.css"/>
            <title><?php addTitle() ?></title>
        </head>
    <body>
        <div class='container text-end'>
            <?php
                if(isset($_SESSION['user'])||isset($_SESSION['username'])){?>
                            <div class='row'>
                                <div class="nav-item dropdown col-md-1 col-sm-2 float-start">
                                    <a class="nav-link dropdown-toggle" href="profile.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="./images/pers.jpeg" alt="User" class="rounded-circle" width="30" height="30">
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="profile.php">profile</a></li>
                                        <li><a class="dropdown-item" href="ads.php">add item</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href='logout.php'>logout</a></li>
                                    </ul>
                                </div>
                                <span class="col-md-11 col-sm-10">  
                                        welcome 
                                        <a class='text-decoration-none me-4' href='profile.php'>
                                    <?php 
                                        echo $userSession;
                                     if(checkUserStatus($userSession) == 1){
                                            echo "<span class = 'alert alert-danger ms-3'>watting for activation</span>";
                                            } ?>
                                        </a>
                                        <a href="cart.php" class='text-decoration-none me-4'>
                                            my cart
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </a>
                                </span>
                            </div>
                            
                <?php
                    }else{
                        echo "<a class='text-decoration-none' href='login.php?reg=login'>log in</a> | <a class='text-decoration-none' href='login.php?reg=signup'>sign up</a>";
                    }      
            ?>
        </div>
    <?php include $tpls .'navBare.php' ?>

