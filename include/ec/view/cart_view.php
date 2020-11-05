<!DOCTYPE html>
<html charset="ja">
    <head>
        <meta charset="UTF-8">
        <title>ショッピングカートページ</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel='stylesheet' href='<?php print CSS_PATH; ?>/common.css'>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php include VIEW_PATH.'/template/header.php'; ?>
        <section class='container'>        
            <h2>ショッピングカート</h2>
            <?php foreach ($_SESSION['error'] as $put) { ?>
                <p class="err_msg"><?php print $put; ?></p>
            <?php } ?>
            <?php if(isset($comment) === TRUE) { print $comment; } ?>
            <?php if (empty($cart_data) !== TRUE) { ?>
            <table class='table'>
                <?php foreach ($cart_data as $value) { ?>
                        <tr>
                            <td><img src="/ec_site/img/<?php print $value['img']; ?>"></td>
                            <td><?php print $value['goods_name']; ?></td>
                            <td>&yen;<?php print $value['price']; ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="goods_id" value="<?php print $value['goods_id']; ?>"/>                                
                                    <input type="text" name="change_amount" value="<?php print $value['amount'] ?>"/>個&nbsp;
                                    <input class='btn btn-info' type="submit" value="変更"/>
                                </form>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="delete_goods" value="<?php print $value['goods_id']; ?>"/>
                                    <input class='btn btn-danger' type="submit" value="商品削除"/>
                                </form>
                            </td>
                        </tr>
                <?php } ?>
            </table>
            <p>合計金額:&yen;<?php print $total_fee; ?></p>
            <form method="post" action="finish.php">
                <input class='btn btn-danger' name="purchase" type="submit" value="購入する"/>
            </form>
            <?php } else { print '<p>商品はありません</p>'; } ?>
        </section>        
    </body>
</html>        