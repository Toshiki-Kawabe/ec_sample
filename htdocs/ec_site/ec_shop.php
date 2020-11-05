<?php
//sample
session_start();
require_once '../../include/ec/conf/ec_const.php';
require_once '../../include/ec/model/ec_shop_function.php';

$goods_data = [];
$_SESSION['error'] = [];

if ($_SESSION['user_id'] === 'admin') {
    go_to_mng();
} else if (isset($_SESSION['user_id']) !== TRUE) {
    go_to_login();
} else {
    $user_id = $_SESSION['user_id'];
    if ($link = get_db_connect()) {
        $request_method = get_request_method();
        if ($request_method === 'POST') {
            if (isset($_POST['goods_id']) === TRUE) {
                mysqli_autocommit($link, FALSE);
                
                $goods_id = get_post_data('goods_id');
                $date = get_now_date();
                if (iu_cart_table($link, $user_id, $goods_id, $date) !== TRUE) {
                    $_SESSION['error'] = 'INSERT or UPDATE失敗:err003 詳細は管理者に問い合わせてください';
                } else {
                    $comment = 'カートに登録しました';
                }
                transaction_check($_SESSION['error'], $link);
            }
        }
        $goods_data = get_goods_list($link);
        $goods_data = entity_assoc_array($goods_data);
        close_db_connect($link);
    }
}
include_once VIEW_PATH.'/ec_shop_view.php';