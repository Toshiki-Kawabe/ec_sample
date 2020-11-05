<!DOCTYPE html>
<html charset="ja">
    <head>
        <meta charset="UTF-8">
        <title>ec_shop</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel='stylesheet' href='<?php print CSS_PATH; ?>/common.css'>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php include VIEW_PATH.'/template/header.php'; ?>
        <div class="container">
            <?php foreach ($_SESSION['error'] as $put) { ?>
                <p class="err_msg"><?php print $put; ?></p>
            <?php } ?>
            <?php if(isset($comment) === TRUE) { print $comment; } ?>
            <div class="card-group">
                <?php foreach ($goods_data as $value) { if ($value['status'] === '1') { ?>
                <form method="post">
                    <div class="card m-2" style="min-width:200px;height:280px">
                        <!--<img class="card-img-top" src="/ec_site/img/<?php //print $value['img']; ?>" alt="Card image" style="height:100%">-->
                        <div class='card-img-top' style='background-image:url(./img/<?php print $value['img']; ?>);height:100px;width:100%;background-size:contain;background-repeat:no-repeat;background-position:center'>
                            
                        </div>
                        <div class="card-body">
                            <h4 class="card-title"><?php print $value['goods_name']; ?></h4>
                            <p class="card-text">&yen;<?php print $value['price']; ?></p>
                            <?php if ($value['stock'] === '0') { ?>
                            <p class="card-text">売り切れ</p>
                            <?php } else { ?>
                            <input type="hidden" name="goods_id" value="<?php print $value['goods_id']; ?>"/> 
                            <input class="btn btn-primary" type="submit" name="cart" value="カートに入れる"/>
                            <?php } ?>
                        </div>
                    </div>
                </form>                   
                <?php } } ?>
            </div>
        </div>    
    </body>
</html>