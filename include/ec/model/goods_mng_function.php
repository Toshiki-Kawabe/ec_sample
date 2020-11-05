<?php
/**
 * ログインページへ移行
 */
 
function go_to_login() {
    header ('Location: ./login.php');
    exit;
}

/**
 * ショップページへ移行
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
       $str = preg_replace(SPACE_DELETE, '', $_POST[$key]);
   }
   return $str;
}

/**
* add POSTデータのエラーチェック
* @return array エラーメッセージ
*/

function goods_add_err_check($tmp_name, $name, $price, $quantity, $status) {
    add_file_check($tmp_name);
    str_length_check('商品名', $name);
    add_num_check('値段', $price);
    add_num_check('在庫数', $quantity);
    status_check($status);
}    

/**
* 添付ファイルの有無チェック
* @return str $type ファイル形式 or array エラーメッセージ
*/

function add_file_check($str) {
    //global $type;
    if (is_uploaded_file($str) === TRUE) {
        $type = get_file_type($str);
        file_type_check($type);
        //return $type;
    } else {
        $_SESSION['error'][] = '画像ファイルを選択してください';
        //return false;
    }    
}

/**
* 添付ファイルの形式チェック
* @return array エラーメッセージ
*/

function file_type_check($str) {
    if ($str !== 'image/png' && $str !== 'image/jpeg') {
        $_SESSION['error'][] = 'ファイル形式エラー。「.png」「.jpg」のみ選択できます';
    }
}    

/**
* 商品追加時の価格と個数のチェック
* @param $str 価格と個数
* @return array エラーメッセージ
*/

function add_num_check($key, $str) {
    if (str_length_check($key, $str) === TRUE) {
        if (preg_match(INT_CHECK, $str) !== 1) {
            $_SESSION['error'][] = $key . 'は0以上の整数としてください';
        }    
    }
}

/**
* 未入力チェック
* @param $str 入力データ
* @return array エラーメッセージ or bool 未入力でない
*/

function str_length_check($key, $str) {
    if (mb_strlen($str) === 0) {
        $_SESSION['error'][] = $key . 'を入力してください';
    } else {
        return TRUE;
    }
}

/**
* status POSTデータのエラーチェック
* @return array エラーメッセージ
*/

function status_check($status) {
    if ($status !== '1' && $status !== '0') {
        $_SESSION['error'][] = '公開ステータスは「公開」or「非公開」を選択してください';
    }
}   

/**
* 画像の保存
* @param $str ファイル形式
*/

function file_save($tmp_name) {
    $str = get_file_type($tmp_name);
    $file_name = substr(base_convert(hash('sha256', uniqid()), 16, 19), 0, 10);
    if ($str === 'image/png') {
        $file_name .= '.png';
    } else {
        $file_name .= '.jpg';
    }
    move_uploaded_file($_FILES['image']['tmp_name'],'./img/'.$file_name);
    return $file_name;
}

function get_file_type($tmp_name){
    return mime_content_type($tmp_name);
}

/**
 * POSTが送信された日時を取得する
 * @return str 投稿日時
*/

function get_now_date() {
    return date(DATETIME);
}

/**
* ec_goods_tableにレコードを追加する
* @param obj $link DBハンドル
* @param str $name 商品名
* @param str $price 価格
* @param str $file_name 商品画像
* @param str $status 公開ステータス
* @param str $date 登録日時
* @param str $date 更新日時
* @return bool
*/
function insert_goods_table($link, $name, $price, $file_name, $status, $date) {
    $sql = 'INSERT INTO ec_goods_table(goods_name, price, img, status, create_at, update_at)
            VALUES(\'' . $name . '\', \'' . $price . '\', \'' . $file_name . '\', \'' . $status . '\', \'' . $date . '\', \'' . $date . '\')';
    return insert_db($link, $sql);
}

/**
 * insertしたgoods_idを取得する
 * @return str goods_id
*/

function get_insert_goods_id($link) {
    return mysqli_insert_id($link);
}

/**
* ec_stock_tableにレコードを追加する
* @param obj $link DBハンドル
* @param str $goods_id goods_id
* @param str $quantity 在庫数
* @param str $date 登録日時
* @param str $date 更新日時
* @return bool
*/

function insert_stock_table($link, $goods_id, $quantity, $date) {
    $sql = 'INSERT INTO ec_stock_table(goods_id, stock, create_at, update_at)
            VALUES(\'' . $goods_id . '\', \'' . $quantity . '\', \'' . $date . '\', \'' . $date . '\')';
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
    /*
    if (mysqli_query($link, $sql) === TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
    */
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
* 在庫数変更時の個数チェック
* @param $str 「在庫数」
* @param $str 個数
* @return array エラーメッセージ
*/

function change_stock_check($key, $str) {
    if (str_length_check($key, $str) === TRUE) {
        if (preg_match(INT_CHECK, $str) !== 1) {
            $_SESSION['error'][] = $key . 'は0以上の整数としてください';
        }    
    }
}

/**
* ec_stock_tableのstockを変更する
* @param obj $link DBハンドル
* @param str $goods_id goods_id
* @param str $stock 在庫数
* @param str $date 更新日時
* @return bool
*/

function update_stock_table_stock($link, $goods_id, $stock, $date) {
    $sql = 'UPDATE ec_stock_table
            SET update_at = \'' . $date . '\' ,stock = \'' . $stock . '\'    
            WHERE goods_id = \'' . $goods_id . '\''; 
    return insert_db($link, $sql);
}


/**
* ec_stock_tableのstatusを変更する
* @param obj $link DBハンドル
* @param str $goods_id goods_id
* @param str $status 公開ステータス
* @param str $date 更新日時
* @return bool
*/

function update_goods_table_status($link, $goods_id, $status, $date) {
    $sql = 'UPDATE ec_goods_table
            SET update_at = \'' . $date . '\' ,status = \'' . $status . '\'    
            WHERE goods_id = \'' . $goods_id . '\''; 
    return insert_db($link, $sql);
}

/**
* ec_goods_tableのレコードを削除する
* @param obj $link DBハンドル
* @param str $goods_id goods_id
* @return bool
*/

function delete_goods_table_record($link, $goods_id) {
    $sql = 'DELETE
            FROM ec_goods_table
            WHERE goods_id = \'' . $goods_id . '\''; 
    return insert_db($link, $sql);
}

/**
* ec_stock_tableのレコードを削除する
* @param obj $link DBハンドル
* @param str $goods_id goods_id
* @return bool
*/

function delete_stock_table_record($link, $goods_id) {
    $sql = 'DELETE
            FROM ec_stock_table
            WHERE goods_id = \'' . $goods_id . '\''; 
    return insert_db($link, $sql);
}

/**
 * 商品の一覧を取得する
 * 
 * @param obj $link DBハンドル
 * @return array 商品一覧配列データ
 */
 
function get_goods_table_list($link) {
    //SQL生成
    $sql = 'SELECT egt.goods_id, egt.goods_name, egt.img, egt.price, egt.status, est.stock
            FROM ec_goods_table AS egt
            INNER JOIN ec_stock_table AS est
            ON egt.goods_id = est.goods_id ';
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
 * DBとのコネクション切断
 * @param obj $link DBハンドル
 */

function close_db_connect($link) {
    //接続を閉じる
    mysqli_close($link);
}