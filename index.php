<?php
    session_start();
    $pageTitle = 'home';
    include 'init.php';    
    $allApproved = getElement('items', 'item_id',approved: 'approved');?>
        <div class="container">
            <div class="row mt-3">
        <?php foreach($allApproved as $item){?>
            <div class="col-sm-6 col-md-3">
                <div class="card mb-3">
                    <a href="item.php?id=<?=$item['item_id']?>"><img src="./images/tempPH.jpeg" alt="<?=$item["name"]?>" class="card-img-top"></a>
                    <div class="card-body">
                        <a href="item.php?id=<?=$item['item_id']?>" class ="text-decoration-none "><h4 class="card-title text-truncate"><?=$item["name"]?></h4></a>
                        <p class="card-text text-truncate"><?=$item["description"]?></p>
                        <p class="card-text"><?=$item["price"]?> $</p>
                        <p class="card-text date">issued: <?=$item["add_date"]?></p>
                        <a href="item.php?id=<?=$item['item_id']?>" class="btn btn-primary">buy now</a>
                    </div>
                </div>
            </div>
       <?php }?>
        </div>
    </div>
   <?php include $tpls.'footer.php';
?>