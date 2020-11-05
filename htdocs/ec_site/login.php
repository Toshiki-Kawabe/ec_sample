<?php
session_start();
require_once '../../include/ec/conf/ec_const.php';
require_once '../../include/ec/model/login_function.php';

$user_name = '';
$passwd = '';
$_SESSION['error'] = [];

if (isset($_SESSION['user_id']) === TRUE) {
    go_to_ec_shop();
}

if ($link = get_db_connect()) {
    $request_method = get_request_method();
    if ($request_method === 'POST') {
        if (isset($_POST['login'])) {
            if (admin_check('user_name', 'passwd') === TRUE ) {
                close_db_connect($link);
                go_to_goods_mng();
            }
            
            $user_name = get_post_data('user_name');
            $passwd = get_post_data('passwd');
            user_name_passwd_check($link, $user_name, $passwd);
        }
    }
    close_db_connect($link);
}

include_once VIEW_PATH.'/login_view.php';