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
    //コネクション取得
    if (!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
        die('error: ' . mysqli_connect_error());
    }
    //文字コードセット
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
* POSTデータの前後の全半角スペースを除去した状態で取得
* @param str $key 配列キー
* @return str POST値
*/
function get_post_data($key) {
   $str = '';
   if (isset($_POST[$key]) === TRUE) {
       $str = preg_replace(ALL_SPACE_DELETE, '', $_POST[$key]);
   }
   return $str;
}

/**
* add POSTデータのエラーチェック
* @return array エラーメッセージ
*/

function user_add_err_check($link, $user_name, $passwd) {
    user_name_check($link, 'ユーザ名', $user_name);
    passwd_check('パスワード', $passwd);
}

/**
* add user_nameのエラーチェック
* @return array エラーメッセージ
*/

function user_name_check($link, $key, $str) {
    if (str_length_check($key, $str) === TRUE) {
        if (preg_match(ADD_ID_PASS_CHECK, $str) !== 1) {
            $_SESSION['error'][] = $key . 'は半角英数字を入力してください';
        } else {
            user_name_deplicate_check($link, $str);
        }
    }
}

/**
* add user_nameのエラーチェック
* @return array エラーメッセージ
*/

function passwd_check($key, $str) {
    if (str_length_check($key, $str) === TRUE) {
        if (preg_match(ADD_ID_PASS_CHECK, $str) !== 1) {
            $_SESSION['error'][] = $key . 'は半角英数字を入力してください';
        }
    }
}

/**
* 入力文字数チェック
* @param $str 入力データ
* @return array エラーメッセージ or bool 未入力/6文字未満でない
*/

function str_length_check($key, $str) {
    if (mb_strlen($str) < 6) {
        $_SESSION['error'][] = $key . 'は6文字以上の文字を入力してください';
    } else {
        return TRUE;
    }
}

/**
* 登録するユーザ名が使用されていないかチェック
* @param $str 入力データ
* @return array エラーメッセージ
*/

function user_name_deplicate_check($link, $str) {
    $sql = 'SELECT COUNT(user_name)
            FROM ec_user_table
            WHERE user_name = \'' . $str . '\'';
    if ((int)get_as_array($link, $sql) !== 0) {
        $_SESSION['error'][] = '入力したユーザ名は既に使用されています';
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
    return $data[0]['COUNT(user_name)'];
}

/**
 * POSTが送信された日時を取得する
 * @return str 投稿日時
*/

function get_now_date() {
    return date(DATETIME);
}

/**
* ec_user_tableにレコードを追加する
* @param obj $link DBハンドル
* @param str $user_name ユーザ名
* @param str $passwd 
* @param str $date 登録日時
* @param str $date 更新日時
* @return bool
*/

function insert_user_table($link, $user_name, $passwd, $date) {
    $sql = 'INSERT INTO ec_user_table(user_name, password, create_at, update_at)
            VALUES(\'' . $user_name . '\', \'' . $passwd . '\', \'' . $date . '\', \'' . $date . '\')';
    return insert_db($link, $sql);
}

/**
* insertを実行する
* @param obj $link DBハンドル
* @param str SQL文
* @return bool
*/

function insert_db($link, $sql) {
    return mysqli_query($link,$sql);
}

/**
* トランザクション成否判定
* @param array エラー文配列
* @param obj $link DBハンドル
*/

function transaction_check($err, $link) {
    if (count($err) === 0) {
        mysqli_commit($link);
    } else {
        mysqli_rollback($link);
    }
}

/**
 * DBとのコネクション切断
 * @param obj $link DBハンドル
 */

function close_db_connect($link) {
    //接続を閉じる
    mysqli_close($link);
}