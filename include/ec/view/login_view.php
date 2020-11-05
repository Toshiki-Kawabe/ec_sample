<!DOCTYPE html>
<html charset="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログインページ</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel='stylesheet' href='<?php print CSS_PATH; ?>/common.css'>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php include VIEW_PATH.'/template/header.php'; ?>
        <section class="container">
            <?php foreach ($_SESSION['error'] as $put) { ?>
                <p class="err_msg"><?php print $put; ?></p>
            <?php } ?>
            <form method="post"  class='rounded bg-secondary text-light p-4'>
                <div class='row form-group col'>
                    <label for="user_name">ユーザー名:</label>
                    <input class="form-control" type="text" name="user_name" placeholder="半角英数字、6文字以上"/>
                </div>
                <div class='row form-group col'>
                    <label for="passwd">パスワード:</label>
                    <input class="form-control" type="password" name="passwd" placeholder="半角英数字、6文字以上"/>
                </div>
                <div class='row mt-3 form-group col p-2'>
                    <input class='form-control btn btn-warning' type="submit" name="login" value="ログイン"/>
                </div>
            </form>
            <a href="user_add.php">ユーザの新規登録</a>
        </section>
    </body>
</html>