<?php
    session_start();
    $pageTitle = 'categories';
    include './init.php';
    $catId   = isset($_GET['catid']) && is_numeric($_GET['catid'])? intval($_GET['catid']) : 0;
    $catName = isset($_GET['catName']) ? $_GET['catName'] : '';
    $items = getElement('items', 'item_id', 'cat_id', $catId, 'approved');
    echo '<div class="container">';
        echo "<h1 class = 'text-center mb-5 mt-4'>".$catName." items</h1>";
        echo '<div class="row">';
        if(!empty($items)){
                if(is_array(reset($items))){
                    foreach($items as $item){
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
                }else{
                    echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="card mb-3">';
                                echo '<a href="item.php?id='.$items['item_id'].'" class =""><img src="./images/tempPH.jpeg" alt="'.$items["name"].'" class="card-img-top"></a>';
                                echo '<div class="card-body">';
                                    echo '<a href="item.php?id='.$items['item_id'].'" class ="text-decoration-none "><h4 class="card-title text-truncate">'.$items["name"].'</h4></a>';
                                    echo '<p class="card-text text-truncate">'.$items["description"].'</p>';
                                    echo '<p class="card-text">'.$items["price"].' $</p>';
                                    echo '<p class="card-text date">issued: '.$item["add_date"].'</p>';
                                    echo '<a href="#" class="btn btn-primary btn-block">Buy Now</a>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                }
        }else{
            $MSG = "<div class='alert alert-success'>no existing items</div>";                          
            redirect($MSG);
        }
         echo '</div>';
    echo '</div>';
    include $tpls.'footer.php';
?>