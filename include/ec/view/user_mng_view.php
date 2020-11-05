<!DOCTYPE html>
<html charset="ja">
    <head>
        <meta charset="UTF-8">
        <title>ユーザ管理</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel='stylesheet' href='<?php print CSS_PATH; ?>/common.css'>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php include VIEW_PATH.'/template/header.php'; ?>
        <section class="container">
            <h1>ユーザ管理</h1>
            <a href="goods_mng.php">商品管理ページへ</a>
            <h2>ユーザ情報一覧</h2>
            <table class="table">
                <tr>
                    <th>ユーザID</th>
                    <th>登録日</th>
                </tr>
                <?php foreach ($user_data as $value) { ?>
                <tr>
                    <td><?php print $value['user_name']; ?></td>
                    <td><?php print $value['create_at']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </section>
    </body>
</html>