<?php
    session_start();
    $pageTitle = 'home';
    include 'init.php';    
    $allApproved = getElement('items', 'item_id',approved: 'approved');
    echo '<div class="container">';
        echo '<div class="row mt-3">';
        foreach($allApproved as $item){
            echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="card mb-3">';
                    echo '<a href="item.php?id='.$item['item_id'].'" class =""><img src="./images/tempPH.jpeg" alt="'.$item["name"].'" class="card-img-top"></a>';
                    echo '<div class="card-body">';
                        echo '<a href="item.php?id='.$item['item_id'].'" class ="text-decoration-none "><h4 class="card-title text-truncate">'.$item["name"].'</h4></a>';
                        echo '<p class="card-text text-truncate">'.$item["description"].'</p>';
                        echo '<p class="card-text">'.$item["price"].' $</p>';
                        echo '<p class="card-text date">issued: '.$item["add_date"].'</p>';
                        echo '<a href="#" class="btn btn-primary btn-block">Buy Now</a>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    echo '</div>';
    include $tpls.'footer.php';
?>