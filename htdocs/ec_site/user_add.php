<?php
session_start();
require_once '../../include/ec/conf/ec_const.php';
require_once '../../include/ec/model/user_add_function.php';

$_SESSION['error'] = [];

if (isset($_SESSION['user_id']) === TRUE) {
    go_to_ec_shop();
}

if ($link = get_db_connect()) {
    $request_method = get_request_method();
    if ($request_method === 'POST') {
        if (isset($_POST['user_add'])) {
            mysqli_autocommit($link, FALSE);
            
            $user_name = get_post_data('user_name');
            $passwd = get_post_data('passwd');
            user_add_err_check($link, $user_name, $passwd);
            
            if(count($_SESSION['error']) === 0) {
                $date = get_now_date();
                if(insert_user_table($link, $user_name, $passwd, $date) !== TRUE) {
                    $_SESSION['error'][] = 'INSERT失敗:error001 詳細は管理者に問い合わせてください';
                } else {
                    $comment = 'アカウント作成が完了しました';
                }
            }
            transaction_check($_SESSION['error'], $link);
        }
    }
    close_db_connect($link);
}

include_once VIEW_PATH.'/user_add_view.php';