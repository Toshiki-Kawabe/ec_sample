<?php
session_start();
require_once '../../include/ec/conf/ec_const.php';
require_once '../../include/ec/model/finish_function.php';

$_SESSION['error'] = [];
$total_fee = '';

if ($_SESSION['user_id'] === 'admin') {
    go_to_mng();
} else if (isset($_SESSION['user_id']) !== TRUE) {
    go_to_login();
} else {
    $user_id = $_SESSION['user_id'];
    if ($link = get_db_connect()) {
        $cart_data = get_cart_list($link, $user_id);
        $cart_data = entity_assoc_array($cart_data);
        $total_fee = get_total_fee($cart_data);
        
        if (empty($cart_data) === TRUE) {
            $_SESSION['error'][] = '商品はありません';
        } else {
            sales_check($cart_data, 'goods_name', 'amount', 'stock', 'status');
            if (count($_SESSION['error']) === 0) {
                $date = get_now_date();
                mysqli_autocommit($link, FALSE);
                foreach ($cart_data as $value) {
                    if (stock_table_update($link, $value, $date) !== TRUE) {
                        $_SESSION['error'][] = 'UPDATE失敗';
                    }
                }
                if (cart_table_record_delete($link, $user_id) !== TRUE) {
                    $_SESSION['error'][] = 'DELETE失敗';
                }
                transaction_check($_SESSION['error'], $link);
            }
        }
    }
    close_db_connect($link);
}
include_once VIEW_PATH.'/finish_view.php';