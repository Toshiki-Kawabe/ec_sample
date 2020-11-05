<?php
/**
 * 管理ページへ移行
 */
 
function go_to_mng() {
    header ('Location: ./goods_mng.php');
    exit;
}

/**
 * ログインページへ移行
 */
 
function go_to_login() {
    header ('Location: ./login.php');
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
 * カート内の商品一覧を取得する
 * 
 * @param obj $link DBハンドル
 * @return array 商品一覧配列データ
 */
 
function get_cart_list($link, $user_id) {
    //SQL生成
    $sql = 'SELECT egt.goods_id, egt.goods_name, egt.img, egt.price, egt.status, 
                    est.stock,
                    ect.amount
            FROM ec_goods_table AS egt
            INNER JOIN ec_cart_table AS ect
            ON egt.goods_id = ect.goods_id
            INNER JOIN ec_stock_table AS est
            ON egt.goods_id = est.goods_id
            WHERE ect.user_id =' . $user_id;
    //クエリ実行
    return get_as_array($link, $sql);
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
 * 特殊文字をHTMLエンティティに変換する(2次元配列の値)
 * @param array $assoc_array 変換前配列
 * @return array 変換後配列
 */

function entity_assoc_array($assoc_array) {
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            //特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    return $assoc_array;
}

/**
 * 特殊文字をHTMLエンティティに変換する
 * @param str $str 変換前文字
 * @return str 変換後文字
 */

function entity_str($str) {
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

/**
 * 合計金額取得
 * @param obj $cart_data カートデータ
 * @return int $total_fee 合計金額
 */

function get_total_fee($cart_data) {
    $total_fee = 0;
    foreach($cart_data as $value) {
        $total_fee += $value['price'] * $value['amount'];
    }
    return $total_fee;
}

/**
* 購入品のエラーチェック
* @return array エラーメッセージ
*/
function sales_check($cart_data, $goods_name, $amount, $stock, $status) {
    foreach ($cart_data as $value) {
        if ($value[$amount] > $value[$stock]) {
            $_SESSION['error'][] = $value[$goods_name] . 'の購入数が在庫数を上回っています。数量を減らしてください。';
        }
        if ($value[$status] === '0') {
            $_SESSION['error'][] = $value[$goods_name] . 'はオーナーにより販売中止になりました。';
        }
    }    
}

/**
 * POSTが送信された日時を取得する
 * @return str 投稿日時
*/

function get_now_date() {
    return date(DATETIME);
}

/**
* ec_stock_tableのstockを変更する
* @param obj $link DBハンドル
* @param str $goods_id goods_id
* @param str $amount 数量
* @param str $date 更新日時
* @return bool
*/

function stock_table_update($link, $value, $date) {
    $stock = (int)$value['stock'] - (int)$value['amount'];
    $sql = 'UPDATE ec_stock_table
            SET update_at = \'' . $date . '\' ,stock = \'' . $stock . '\'    
            WHERE goods_id = \'' . $value['goods_id'] . '\''; 
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
* ec_cart_tableのレコードを削除する
* @param obj $link DBハンドル
* @param str $goods_id goods_id
* @return bool
*/

function cart_table_record_delete($link, $user_id) {
    $sql = 'DELETE
            FROM ec_cart_table
            WHERE user_id = \'' . $user_id . '\''; 
    return insert_db($link, $sql);
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