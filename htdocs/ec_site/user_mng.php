<?php
session_start();
require_once '../../include/ec/conf/ec_const.php';
require_once '../../include/ec/model/user_mng_function.php';

$user_data = [];

if (isset($_SESSION['user_id']) !== TRUE) {
    go_to_login();
} else if ($_SESSION['user_id'] !== 'admin') {
    go_to_ec_shop();
}

if (isset($_SESSION['user_id']) !== TRUE) {
    header ('Location: ./login.php');
    exit;
} else if ($_SESSION['user_id'] !== 'admin') {
    header ('Location: ./ec_shop.php');
    exit;    
}


if ($link = get_db_connect()) {
    $user_data = get_user_table_list($link);
    $user_data = entity_assoc_array($user_data);
    close_db_connect($link);
}

include_once VIEW_PATH.'/user_mng_view.php';