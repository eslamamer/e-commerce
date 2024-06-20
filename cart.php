<?php
session_start();
session_regenerate_id(true);
$pageTitle ='cart';
if(isset($_SESSION['usernam']) || isset($_SESSION['user'])){
    include 'init.php';
    $stmt = $con->prepare("
                            select 
                                        orders.*,
                                        items.name,
                                        items.price,
                                        users.`full name`
                            from
                                        orders
                            inner join
                                        items
                            on
                                        orders.item_id = items.item_id
                            inner join
                                        users
                            on
                                        orders.member_id = users.userID
                            where
                                        orders.member_id = ?
    ");
    $stmt->execute([
        $_SESSION['uid']
    ]);
    $myOrders = $stmt->fetchAll();
    $total = 0;
    if(isset($_GET['id'])){
        $order_id = is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        if(IsExist('order_id', 'orders', $order_id) > 0){
            $delOrder = $con->prepare("delete from orders where order_id = ? and member_id = ?");
            $delOrder->execute([$order_id, $_SESSION['uid']]);
            if($delOrder){
                $erMmsg = '<div class="alert alert-info">'.$delOrder->rowCount().' order/s is deleted</div>';
                redirect($erMmsg);
            }
        }else{
            $erMmsg = '<div class="alert alert-danger">order not existed</div>';
            redirect($erMmsg);
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['quantity'])){
            $quantity = is_numeric($_POST['quantity']) && $_POST['quantity'] > 0 ? intval($_POST['quantity']) : 1;
            $stmt = $stmt = $con->prepare("
                                            update 
                                                    orders
                                            set
                                                    total_price = :ntotal_price,
                                                    pieces = :npieces
                                            where
                                                    order_id = :iid 
                                            and
                                                    member_id = :mid                                           
                                            ");
            $stmt->execute([
                ':npieces'       => $quantity ,
                ':ntotal_price'  => $_POST['price']*$quantity,
                ':iid'           => $_POST['order_id'],
                ':mid'           => $_SESSION['uid']
                ]);
        }
        if($stmt){
            header("refresh: 1, URL=cart.php");
            echo '<div class="alert alert-info">you ordered '.$quantity.' Peices now</div>';
        }
}
    ?>
    <div class="container ">
        <h1 class="text-center"><?= $_SESSION['user']." ".$pageTitle?></h1>
            <div class='table-responsive'>
                <table class='table text-center table-striped table-hover'>
                    <thead>
                        <tr>
                            <th>image</th>
                            <th>name</th>
                            <th>price</th>
                            <th>peices</th>
                            <th>total</th>
                            <th>control</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php foreach($myOrders as $order){
                        $total +=  $order['price']* $order['pieces'];
                    ?>      
                        <tr>
                            <td class="w-25"><a href="item.php?id=<?= $order['item_id']?>" class="w-25"><img src="./images/tempPH.jpeg" alt="product" class="w-75"></a></td>
                            <td class='align-content-center'><?= $order['name']?></td>
                            <td class='align-content-center'><?= $order['price']?> $</td>
                            <td class='align-content-center'>
                                <form action="" method="post">
                                    <input type="number" value="<?=htmlspecialchars($order['pieces'])?>" name="quantity" class="mx-auto form-control text-center w-25"">
                                    <input type="number" hidden value="<?=htmlspecialchars($order['price'])?>" name="price"/>
                                    <input type="number" hidden value="<?= htmlspecialchars($order['order_id'])?>" name="order_id"/>
                                    <input type="submit" class="btn btn-primary w-25 btn-sm mt-1" value="Edit">
                                </form>
                            </td>
                            <td class='align-content-center'><?= $order['total_price']?> $</td>
                            <td class='align-content-center w-25'>
                                <a href="?id=<?= $order['order_id']?>" class="btn btn-danger w-50">remove</a>
                            </td>
                        </tr>
            <?php }?>
                    </tbody>
                    <tfoot>
                        <td><a href="index.php" class="btn btn-primary w-100">continue shopping</a></td>
                        <td colspan="3">total price</td>
                        <td colspan="2"><?= $total?> $</td>
                    </tfoot>
                </table>


<?php }else{
    echo 'you can not access this page';
}
include $tpls.'footer.php';
?>