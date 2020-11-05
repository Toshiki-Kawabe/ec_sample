<!DOCTYPE html>
<html charset="ja">
    <head>
        <meta charset="UTF-8">
        <title>購入完了ページ</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel='stylesheet' href='<?php print CSS_PATH; ?>/common.css'>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php include VIEW_PATH.'/template/header.php'; ?>
        <section class='container'>
            <?php foreach ($_SESSION['error'] as $put) { ?>
                <p class="err_msg"><?php print $put; ?></p>
            <?php } ?>
            <?php if (empty($_SESSION['error']) === TRUE) { ?>
                <p class="text-success">ご購入有り難うございました</p>
            <?php } ?>
            <?php if(isset($comment) === TRUE) { print $comment; } ?>
            <?php if (empty($cart_data) !== TRUE) { ?>
            <table class='table'>
                <tr>
                    <th>商品</th>
                    <th>商品名</th>
                    <th>単価</th>
                    <th>数量</th>
                </tr>
                <?php foreach ($cart_data as $value) { ?>
                        <tr>
                            <td><img src="/ec_site/img/<?php print $value['img']; ?>"></td>
                            <td><?php print $value['goods_name']; ?></td>
                            <td>&yen;<?php print $value['price']; ?></td>
                            <td><?php print $value['amount'] ?></td>
                        </tr>
                <?php } ?>
            </table>
            <p>合計金額:&yen;<?php print $total_fee; ?></p>
            <?php } ?>
        </section>        
    </body>
</html>        