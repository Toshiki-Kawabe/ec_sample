<?php
session_start();
require_once '../../include/ec/conf/ec_const.php';
require_once '../../include/ec/model/goods_mng_function.php';

$type = '';
$name = '';
$price = '';
$quantity = '';
$img = '';
$stock = '';
$status = '';
$goods_id ='';
$_SESSION['error'] = [];
$goods_data = [];
$comment = '';

if (isset($_SESSION['user_id']) !== TRUE) {
    go_to_login();
} else if ($_SESSION['user_id'] !== 'admin') {
    go_to_ec_shop();
}

if ($link = get_db_connect()) {
    $request_method = get_request_method();
    if ($request_method === 'POST') {
        if (isset($_POST['add'])) {
            mysqli_autocommit($link, FALSE);
            
            $name = get_post_data('name');
            $price = get_post_data('price');
            $quantity = get_post_data('quantity');
            $status = get_post_data('add_status');
            goods_add_err_check($_FILES['image']['tmp_name'], $name, $price, $quantity, $status);
            
            if (count($_SESSION['error']) === 0) {
                $file_name = file_save($_FILES['image']['tmp_name']);
                $date = get_now_date();
                if (insert_goods_table($link, $name, $price, $file_name, $status, $date) !== TRUE) {
                    $_SESSION['error'][] = 'INSERT失敗:error001 詳細は管理者に問い合わせてください';
                } else {
                    $goods_id = get_insert_goods_id($link);
                    if (insert_stock_table($link, $goods_id, $quantity, $date) !== TRUE) {
                        $_SESSION['error'][] = 'INSERT失敗:error002 詳細は管理者に問い合わせてください';
                    } else {
                        $comment = '商品の追加が完了しました';
                    }
                }
                transaction_check($_SESSION['error'], $link);
            }
        }
        
        if (isset($_POST['change_stock'])) {
            mysqli_autocommit($link, FALSE);
            
            $stock = get_post_data('change_stock');
            $goods_id = get_post_data('goods_id');
            change_stock_check('在庫数', $stock);
            
            if (count($_SESSION['error']) === 0) {
                $date = get_now_date();
                if (update_stock_table_stock($link, $goods_id, $stock, $date) !== TRUE) {
                     $_SESSION['error'][] = 'UPDATE失敗';
                } else {
                    $comment = '在庫数の変更が完了しました';
                }
                transaction_check($_SESSION['error'], $link);
            }    
        }
        
        if (isset($_POST['change_status'])) {
            mysqli_autocommit($link, FALSE);
            
            $status = get_post_data('change_status');
            $goods_id = get_post_data('goods_id');
            status_check($status);

            if (count($_SESSION['error']) === 0) {
                $date = get_now_date();
                if (update_goods_table_status($link, $goods_id, $status, $date) !== TRUE) {
                    $_SESSION['error'][] = 'UPDATE失敗';
                } else {
                    $comment = '公開ステータスの変更が完了しました';
                }
                transaction_check($_SESSION['error'], $link);
            }
        }

        if (isset($_POST['delete_goods'])) {
            mysqli_autocommit($link, FALSE);
            
            $goods_id = get_post_data('delete_goods');
            
            if (delete_goods_table_record($link, $goods_id) !== TRUE || delete_stock_table_record($link, $goods_id) !== TRUE) {
                $_SESSION['error'][] = 'DELETE失敗';
            } else {
                $comment = '商品の削除が完了しました';
            }
            transaction_check($_SESSION['error'], $link);
        }
    }
    $goods_data = get_goods_table_list($link);
    $goods_data = entity_assoc_array($goods_data);
    close_db_connect($link);
}
include_once VIEW_PATH.'/goods_mng_view.php';