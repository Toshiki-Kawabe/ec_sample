<?php
/**
 * 管理ページへ移行
 */
 
function go_to_mng() {
    header ('Location: ./goods_mng.php');
    exit;
}

/**
 * ショップへ移行
 */
 
function go_to_ec_shop() {
    header ('Location: ./ec_shop.php');
    exit;
}

/**
 * DBハンドルを取得
 * @return obj $link DBハンドル
 */
 
function get_db_connect() {
    if (!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
        die('error: ' . mysqli_connect_error());
    }
    mysqli_set_charset($link, DB_CHARACTER_SET);
    return $link;
}

/**
* リクエストメソッドを取得
* @return str GET/POST/PUTなど
*/

function get_request_method() {
   return $_SERVER['REQUEST_METHOD'];
}

/**
* 管理者チェック
* @param str $user_name ユーザ名
* @param str $passwd パスワード
* @return bool
*/

function admin_check($user_name, $passwd) {
    if($_POST[$user_name] === 'admin' && $_POST[$passwd] === 'admin') {
        $_SESSION['user_id'] = $_POST[$user_name];
        return TRUE;
    }
}

/**
 * 管理者ページへ移行
 */
 
function go_to_goods_mng() {
    header ('Location: ./goods_mng.php');
    exit;
}

/**
* POSTデータの前後の全半角スペースを除去した状態で取得
* @param str $key 配列キー
* @return str POST値
*/

function get_post_data($key) {
   $str = '';
   if (isset($_POST[$key]) === TRUE) {
       $str = preg_replace(SPACE_DELETE, '', $_POST[$key]);
   }
   return $str;
}

/**
* POSTデータの前後の全半角スペースを除去した状態で取得
* @param str $key 配列キー
* @return str POST値
*/

function user_name_passwd_check($link, $user_name, $passwd) {
    $sql = 'SELECT user_id, user_name, password
            FROM ec_user_table
            WHERE user_name = \'' . $user_name . '\'
            AND password = \'' . $passwd . '\'';
    $data = get_as_array($link, $sql);
    if (isset($data[0]['user_name'])) {
        $_SESSION['user_id'] = $data[0]['user_id'];
        close_db_connect($link);
        go_to_ec_shop();
    } else {
        $_SESSION['error'][] = 'ユーザ名又はパスワードが違います';
    }
}

/**
 * クエリを実行しその結果を配列で取得する
 * 
 * @param obj $link DBハンドル
 * @param str $sql SQL文
 * @return array 結果配列データ
 */
 
function get_as_array($link, $sql) {
    //返却用配列
    $data = [];
    //クエリを実行する
    if ($result = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        //結果セットを開放
        mysqli_free_result($result);
    }
    return $data;
}

/**
 * DBとのコネクション切断
 * @param obj $link DBハンドル
 */

function close_db_connect($link) {
    //接続を閉じる
    mysqli_close($link);
}