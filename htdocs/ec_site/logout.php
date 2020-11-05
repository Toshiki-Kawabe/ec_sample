<?php
session_start();
require_once '../../include/ec/conf/ec_const.php';
$_SESSION = [];
session_destroy();
header ('Location: ./login.php');
exit;