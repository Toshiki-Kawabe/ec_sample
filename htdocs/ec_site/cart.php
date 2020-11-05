<?php
session_start();
require_once '../../include/ec/conf/ec_const.php';
require_once '../../include/ec/model/cart_function.php';

$_SESSION['error'] = [];
$total_fee = '';

if ($_SESSION['user_id'] === 'admin') {
    go_to_mng();
} else if (isset($_SESSION['user_id']) !== TRUE) {
    go_to_login();
} else {
    $user_id = $_SESSION['user_id'];
    var_dump($user_id);
    if ($link = get_db_connect()) {
        if (isset($_POST['change_amount'])) {
            mysqli_autocommit($link, FALSE);
            
            $amount = get_post_data('change_amount');
            $goods_id = get_post_data('goods_id');
            change_amount_check('数量', $amount);
            if (count($_SESSION['error']) === 0) {
                $date = get_now_date();
                if (update_cart_table($link, $goods_id, $amount, $date) !== TRUE) {
                     $_SESSION['error'][] = 'UPDATE失敗';
                } else {
                    $comment = '数量の変更が完了しました';
                }
                transaction_check($_SESSION['error'], $link);
            }
        }
        
        if (isset($_POST['delete_goods'])) {
            mysqli_autocommit($link, FALSE);
            
            $goods_id = get_post_data('delete_goods');
            if (delete_cart_table_record($link, $goods_id, $user_id) !== TRUE) {
                $_SESSION['error'][] = 'DELETE失敗';
            } else {
                $comment = '商品の削除が完了しました';
            }
            transaction_check($_SESSION['error'], $link);
        }
        
        $cart_data = get_cart_list($link, $user_id);
        $cart_data = entity_assoc_array($cart_data);
        $total_fee = get_total_fee($cart_data);
        close_db_connect($link);
    }
}

include_once VIEW_PATH.'/cart_view.php';