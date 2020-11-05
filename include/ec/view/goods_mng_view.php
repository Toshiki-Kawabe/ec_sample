<!DOCTYPE html>
<html charset="ja">
    <head>
        <meta charset="UTF-8">
        <title>商品管理</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel='stylesheet' href='<?php print CSS_PATH; ?>/common.css'>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php include VIEW_PATH.'/template/header.php'; ?>
        <!-- 商品追加フォーム --->
        <section class='container'>        
            <h1>商品管理</h1>
            <a href="user_mng.php">ユーザー管理ページへ<br></a>
            <?php foreach ($_SESSION['error'] as $put) { ?>
                <p class="err_msg"><?php print $put; ?></p>
            <?php } ?>
            <?php if(isset($comment) === TRUE) { print $comment; } ?>
            <h2>新規商品追加</h2>
            <form method="post" class='rounded bg-secondary text-light p-4' enctype="multipart/form-data">
                <div class='row'>
                    <div class='form-group col'>
                        <label for='name'>名前:</label>
                        <input  class="form-control" type="text" name="name">
                    </div>
                    <div class='form-group col'>
                        <label for='price'>値段:</label>
                        <input  class="form-control" type="text" name="price">
                    </div>
                    <div class='form-group col'>
                        <label for='quantity'>個数:</label>
                        <input  class="form-control" type="text" name="quantity">
                    </div>
                </div>
                <div class='row'>
                    <div class='form-group col'>
                        <label for='image'>商品画像:</label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name='image' id="inputFile" accept=".png, .jpeg" required>
                          <label class="custom-file-label" for="inputFile">Choose file</label>
                        </div>
                    </div>
                    <div class='form-group col'>
                        <label for='add_status'>ステータス:</label>
                        <select class='form-control' name="add_status">
                            <option value='0'>非公開</option>
                            <option value='1'>公開</option>
                        </select>
                    </div>
                </div>
                <div class='row mt-3'>
                    <div class='form-group col'>
                        <input class='form-control btn btn-warning' type="submit" name="add" value="---商品追加---"/>
                    </div>
                </div>
            </form>
        </section>
        <section class='container'>        
            <h2>商品情報変更</h2>
            <table class='table'>
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>ステータス</th>
                    <th>商品削除</th>
                </tr>
                <?php foreach ($goods_data as $value) { ?>
                    <?php if ($value['status'] === '0') { ?>
                        <tr class="private">
                    <?php } else { ?>
                        <tr>
                    <?php } ?>
                            <td><img src="/ec_site/img/<?php print $value['img']; ?>"></td>
                            <td><?php print $value['goods_name']; ?></td>
                            <td><?php print $value['price']; ?>円</td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="goods_id" value="<?php print $value['goods_id']; ?>"/>                                
                                    <input type="text" name="change_stock" value="<?php print $value['stock'] ?>"/>個&nbsp;
                                    <input class='btn btn-info' type="submit" value="変更"/>
                                </form>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="goods_id" value="<?php print $value['goods_id']; ?>"/>                                
                                <?php if ($value['status'] === '1') { ?>
                                    <input type="hidden" name="change_status" value="0"/>                                
                                    <input class='btn btn-info' type="submit" value="公開→非公開"/>                            
                                <?php } else { ?>
                                    <input type="hidden" name="change_status" value="1"/>
                                    <input class='btn btn-info' type="submit" value="非公開→公開"/>
                                <?php } ?>
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
        </section>        
    </body>
</html>