<?php


ini_set('errors', 'on');
ini_set('error_log', 'php.log');
date_default_timezone_set('Asia/Tokyo');

$debugFlg = false;

function debug($str)
{

    global $debugFlg;
    if ($debugFlg) {

        error_log($str);
    }
}


session_save_path('/var/tmp/');
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
setcookie('tunag', 'users', 120 * 60 * 24);

session_start();

session_regenerate_id();


define('MSG01', '入力必須です。');
define('MSG02', '既に登録済みのメールアドレスです。');
define('MSG03', '6文字以上で設定して下さい。');
define('MSG04', 'パスワードが再入力と一致しません。');
define('MSG05', '半角英数字で入力してください。');
define('MSG06', 'プロフィールを更新しました。');
define('MSG07', 'エピソードを登録しました。');
define('MSG08', 'ご登録のお名前と一致しません。');
define('MSG09', 'ご登録の社員番号と一致しません。');
define('MSG10', '予期せぬエラーが発生しました。再度ログインを実行してください。');
define('MSG11', '所属店舗が違います');
define('MSG12', ':いいね順、使われてる順との併用は不可です');
define('MSG13', ':どちらかにして下さい');
define('MSG14', '編集が完了しました。');
define('MSG15', 'ファイルサイズが大き過ぎます');
define('MSG16', 'ファイルが見つかりません');
define('MSG17', 'ファイルサイズオーバーです');
define('MSG18', '削除しました');

$err_msg = array();

function blank_check($str, $key)
{
    global $err_msg;
    if (empty($str)) {
        $err_msg[$key] = MSG01;
    }
    
}

//パスワード文字数チェック
function text_min($str, $key)
{
    global $err_msg;
    if (!empty($str) && mb_strlen($str) < 6) {
        $err_msg[$key] = MSG03;
    }
}

//パスワード合ってるかチェック

function pass_match($str1, $str2, $key)
{

    global $err_msg;
    if (!empty($str1) && !empty($str2) && $str1 !== $str2) {
        $err_msg[$key] = MSG04;
    }
}

//名前合ってるかチェック
function name_Match($name, $pass, $key)
{
    global $err_msg;
    try {
        $dbh = dbh();
        $sql = 'SELECT username FROM users WHERE pass = :pass';
        $data = array(':pass' => $pass);
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            $rst = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($rst['username'])) {
                if ($rst['username'] !== $name) {
                    $err_msg[$key] = MSG08;
                }
            }
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

//パスワード合ってるかチェック

function pass($name, $s_id, $pass, $key)
{
    global $err_msg;
    try {
        $dbh = dbh();
        $sql = 'SELECT pass FROM users WHERE username = :name AND store_id = :s_id';
        $data = array(':name' => $name, ':s_id' => $s_id);
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            $rst = $stmt->fetch(PDO::FETCH_ASSOC);
            debug('パスワード不一致です。');
            if (!empty($rst['pass'])) {
                if ($rst['pass'] !== $pass) {
                    $err_msg[$key] = MSG09;
                }
            }
        } else {
            debug('パスワード一致です。');
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

//店名合ってるかチェック
function store_Match($pass, $store, $key)
{
    global $err_msg;
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM users WHERE pass = :pass ';
        $data = array(':pass' => $pass);
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            $rst = $stmt->fetch(PDO::FETCH_ASSOC);
            debug('所属店舗不一致です。');
            if (!empty($rst['store_id'])) {
                if ($rst['store_id'] !== $store) {
                    $err_msg[$key] = MSG11;
                }
            }
        } else {
            debug('所属店舗一致です。');
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

//パスワード形式チェック
function pass_half($str, $key)
{
    global $err_msg;

    if (!preg_match('/\A[a-z\d]{5,40}+\z/i', $str)) {
        $err_msg[$key] = MSG05;
    }
}

//検索重複チェック

function selectDump($date, $like, $used, $str1, $str2)
{
    global $err_msg;
    if (!empty($date) && !empty($like)) {

        $err_msg[$str1] = MSG12;
    } else {

        $err_msg = '';
    }


    if (!empty($date) && !empty($used)) {

        $err_msg[$str1] = MSG12;
    } else {

        $err_msg = '';
    }


    if (!empty($like) && !empty($used)) {

        $err_msg[$str2] = MSG13;
    }
}

//できればテキストエリアの文字数チェックエラーJS表示も共に



//DB接続関数
function dbh()
{

    $dsn = 'mysql:dbname=tunag;host=localhost:8889;charset=utf8';  //ローカル環境
    // $dsn = 'mysql:dbname=xs172726_tunag;host=localhost;charset=utf8'; //本番環境
    $name = 'root';   //ローカル環境
    // $name = 'xs172726_chaki';  //本番環境
    $pass = 'root'; // ローカル環境
    // $pass = 'chaki8923';  //本番環境
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );

    $dbh = new PDO($dsn, $name, $pass, $options);
    return $dbh;
}

//クエリ文作成関数
function query($dbh, $sql, $data)
{

    $stmt = $dbh->prepare($sql);
    if (!$stmt->execute($data)) {
        debug('クエリ失敗:' . print_r($stmt, true));
        return false;
    }

    // debug('クエリ成功');
    return $stmt;
}


//画像パス作成＆保存関数
function uploadPic($file,$key)
{
    debug('キーーーー',print_r($key,true));
    global $err_msg;
    if (isset($file['error'])) {
        try {

            switch ($file['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $err_msg[$key] = MSG15;
                   
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $err_msg['pic'] = MSG16;
                    throw new RuntimeException('ファイルがありません');
                    break;
                case UPLOAD_ERR_INI_SIZE:
                    $err_msg['pic'] = MSG17;
                    throw new RuntimeException('ファイルサイズオーバー');
                    break;
            }
            //            
            $type = @exif_imagetype($file['tmp_name']);
            if (!in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF], true)) {
                throw new RuntimeException('ファイル形式が違います。');
            }

            $path = 'img/' . sha1_file($file['tmp_name']) . image_type_to_extension($type);
            if (!move_uploaded_file($file['tmp_name'], $path)) {

                throw new RuntimeException('ファイルアップロード失敗');
            }
            debug('ファイルは正常にアップロードされました');
            chmod($path, 0644);

            debug('$path中身' . print_r($path, true));
            return $path;
        } catch (RuntimeException $e) {
            global $err_msg;
            return $err_msg['pic'] = MSG17;
            error_log('エラー発生:' . $e->getMessage());
          
            debug('エラー中身',print_r($err_msg['pic'],true));
        }
    }
}






//ユーザー情報取得
function getUser($id)
{

    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM users WHERE id = :id';
        $data = array(':id' => $id);
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch (Exception $e) {

        error_log('エラー発生:' . $e->getMessage());
    }
}


function getAlluser()
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM users';
        $data = array();
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
}

//入力保持

function formKeep($str)
{
    global $getFormData;
    global $err_msg;
    //DBに情報あり
    if (!empty($getFormData[$str])) {
        //エラーあり
        if (!empty($err_msg)) {
            //post送信あり
            if (isset($_POST[$str])) {

                return $_POST[$str];
            } else {
                return $getFormData[$str];
            }
        } else {
            //ポスト送信あってDBと内容違う
            if (isset($_POST[$str]) && $_POST[$str] !== $getFormData[$str]) {
                return $_POST[$str];
            } else {
                return $getFormData[$str];
            }
        }
    } else {
        if (isset($_POST[$str])) {
            return $_POST[$str];
        }
    }
}
//事例入力保持
function getCaseForm($str)
{
    global $getCaseOne;
    global $err_msg;

    if (!empty($getCaseOne[$str])) {
        if (!empty($err_msg)) {

            if (isset($_POST[$str])) {
                return $_POST[$str];
            } else {
                return $getCaseOne[$str];
            }
        } else {
            if (isset($_POST[$str]) && $getCaseOne[$str] !== $_POST[$str]) {
                return $_POST[$str];
            } else {
                return $getCaseOne[$str];
            }
        }
    } else {
        if (isset($_POST[$str])) {
            return $_POST[$str];
        }
    }
}


//カテゴリー情報取得関数

function get_category()
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM category';
        $data = array();
        $stmt = query($dbh, $sql, $data);

        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラーが発生しました' . $e->getMessage());
    }
}
//天気情報取得関数
function get_weather()
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM weather ';
        $data = array();
        $stmt = query($dbh, $sql, $data);

        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラーが発生しました' . $e->getMessage());
    }
}

//店舗名取得
function getStore()
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM store';
        $data = array();
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

function getStoreOne($u_id)
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM store WHERE id = :id';
        $data = array(':id' => $u_id);
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

function getUserStore($u_id)
{
    try {
        $dbh = dbh();
        $sql = 'SELECT s.store ,u.store_id FROM store As s
        INNER JOIN users As u ON s.id = u.store_id WHERE s.id = :id';
        $data = array(':id'=>$u_id);
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

//ステータス情報取得
function getStatus()
{
    try {

        $dbh = dbh();
        $sql = 'SELECT * FROM status';
        $data = array();

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

//セッションを一回だけ取得
function getSession($key)
{


    $data = $_SESSION[$key];

    $_SESSION[$key] = '';

    return $data;
}

//接客事例取得関数（ページ数取得するためだけ）
function allCase()
{
    try {
        $dbh = dbh();
        $sql = 'SELECT id FROM service_case';
        $data = array();

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            $rst['total'] = $stmt->rowCount();
            $rst['total_page'] = ceil($rst['total'] / 9);
            return $rst;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

function getarcive()
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM arcive WHERE id BETWEEN 57 AND 70';
        $data = array();

        $stmt = query($dbh, $sql, $data);

        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

function getAllarcive()
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM arcive ';
        $data = array();

        $stmt = query($dbh, $sql, $data);

        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}
function getsegment()
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM segment';
        $data = array();

        $stmt = query($dbh, $sql, $data);

        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}




//こっちは接客事例を実際に表示させる様関数
function selectCase($span, $minpage = 1, $c_id, $date, $like, $weather, $store, $used, $a_id, $status_id, $segment_id, $word)
{

    try {
        $dbh = dbh();
        $sql = 'SELECT s.id ,title,category_id,weather_id,s.store_id,used,like_number,user_id,episode,s.arcive_id,s.servise_point,s.status_id, s.segment_id,create_date,c.category_name,user_name,pic,
        st.store,stat.status_name,sg.segment,a.arcive,weather FROM service_case As s 
        INNER JOIN category As c ON c.id = s.category_id 
        INNER JOIN store As st ON st.id = s.store_id  
        INNER JOIN users As u ON u.id = user_id
        INNER JOIN weather As w ON w.id = s.weather_id
        INNER JOIN arcive As a ON a.id = s.arcive_id
        INNER JOIN status As stat ON stat.id = s.status_id
        INNER JOIN segment As sg ON sg.id = s.segment_id WHERE delete_flg = 0';
        // 接客場所カテゴリーソート

        if (!empty($c_id)) {
            switch ($c_id) {
                case 1:
                    $sql .= ' AND s.category_id =' . $c_id;
                    break;
                case 2:
                    $sql .= ' AND s.category_id =' . $c_id;
                    break;
                case 5:
                    $sql .= ' AND s.category_id =' . $c_id;
                    break;
                case 7:
                    $sql .= ' AND s.category_id =' . $c_id;
                    break;
                case 8:
                    $sql .= ' AND s.category_id =' . $c_id;
                    break;
                case 9:
                    $sql .= ' AND s.category_id =' . $c_id;
                    break;
                case 10:
                    $sql .= ' AND s.category_id =' . $c_id;
                    break;
            }
        }
        //天気ソート
        if (!empty($weather)) {
            switch ($weather) {
                case 2:
                    $sql .= ' AND s.weather_id =' . $weather;
                    break;
                case 3:
                    $sql .= ' AND s.weather_id =' . $weather;
                    break;
                case 4:
                    $sql .= ' AND s.weather_id =' . $weather;
                
            }
        }
        //店舗別ソート
        if (!empty($store)) {
            switch ($store) {
                case 1:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 2:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 3:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 4:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 5:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 6:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 7:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 8:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 11:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 12:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 13:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 14:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 15:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 16:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 17:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 18:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 19:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 20:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 21:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            switch ($store) {
                case 22:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            
            switch ($store) {
                case 23:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            
            switch ($store) {
                case 24:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
            
            switch ($store) {
                case 25:
                    $sql .= ' AND st.id =' . $store;
                    break;
            }
        }

        //アーカイブ取得
        if (!empty($a_id)) {
            switch ($a_id) {
                case 1:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 2:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 3:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 4:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 5:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 6:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 7:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 8:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 9:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 10:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 11:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 12:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 13:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 14:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 15:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 16:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 17:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 18:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 19:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 20:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 21:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 22:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 23:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 24:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 25:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 26:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 27:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 28:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 29:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 30:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 31:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 33:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 34:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 35:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 36:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 37:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 38:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 39:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 40:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 41:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 42:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 43:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 44:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 45:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 46:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 47:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 48:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 49:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 50:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 51:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 52:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 53:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 54:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 55:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 56:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 57:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 58:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 59:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
                case 60:
                    $sql .= ' AND s.arcive_id = ' . $a_id;
                    break;
            }
        }

        //お客様ステータス取得
        if (!empty($status_id)) {
            switch ($status_id) {
                case 1:
                    $sql .= ' AND s.status_id = ' . $status_id;
                    break;
                case 2:
                    $sql .= ' AND s.status_id = ' . $status_id;
                    break;
                case 3:
                    $sql .= ' AND s.status_id = ' . $status_id;
                    break;
                case 4:
                    $sql .= ' AND s.status_id = ' . $status_id;
                    break;
                case 5:
                    $sql .= ' AND s.status_id = ' . $status_id;
                    break;
                case 6:
                    $sql .= ' AND s.status_id = ' . $status_id;
                    break;
                case 7:
                    $sql .= ' AND s.status_id = ' . $status_id;
                    break;
                case 8:
                    $sql .= ' AND s.status_id = ' . $status_id;
                    break;
            }
        }
        //お客様セグメント
        if (!empty($segment_id)) {
            switch ($segment_id) {
                case 1:
                    $sql .= ' AND s.segment_id = ' . $segment_id;
                    break;
                case 2:
                    $sql .= ' AND s.segment_id = ' . $segment_id;
                    break;
                case 3:
                    $sql .= ' AND s.segment_id = ' . $segment_id;
                    break;
                case 4:
                    $sql .= ' AND s.segment_id = ' . $segment_id;
                    break;
                case 5:
                    $sql .= ' AND s.segment_id = ' . $segment_id;
                    break;
            }
        }
        //ワード検索
        if (!empty($word)) {
            $sql .= ' AND episode LIKE "%' . $word . '%"';
        }
        //新しい順、古い順
        if (!empty($date)) {
            switch ($date) {
                case 1:
                    $sql .= ' ORDER BY create_date DESC ';
                    break;
                case 2:
                    $sql .= ' ORDER BY create_date ASC ';
                    break;
            }
        }
        //いいね順
        if (!empty($like)) {
            switch ($like) {
                case 1:
                    $sql .= ' ORDER BY like_number DESC ';
                    break;
                case 2:
                    $sql .= ' ORDER BY like_number ASC ';
                    break;
            }
        }
        //使い回した順
        if (!empty($used)) {
            switch ($used) {
                case 1:
                    $sql .= ' ORDER BY used DESC ';
                    break;
                case 2:
                    $sql .= ' ORDER BY used ASC ';
                    break;
            }
        }
        $data = array();
        $stmt = query($dbh, $sql, $data);
        $rst['total'] = $stmt->rowCount();
        debug('ヒット件数' . print_r($rst['total'], true));

        $sql .= ' LIMIT ' . $span . ' OFFSET ' . $minpage;
        $data = array();
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            debug('SQL文' . print_r($stmt, true));
            $rst['data'] = $stmt->fetchAll();
            return $rst;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

function servidseArc()
{
    $dbh = dbh();
    $sql = 'SELECT * FROM service_case WHERE arcive = :arcive';
    $data = array(':arcive' => '2021-07');

    $stmt = query($dbh, $sql, $data);
    debug('アーカイブSQL文' . print_r($stmt, true));
    if ($stmt) {
        return $stmt->fetchAll();
    } else {
        return false;
    }
}

function getCaseOne($c_id)
{
    try {
        $dbh = dbh();
        $sql = 'SELECT s.id, title, category_id, s.weather_id, s.store_id, s.used, like_number, user_id,s.user_name, episode,servise_point, s.status_id,create_date,a.arcive,s.arcive_id,s.segment_id,
        st.store,st.id, c.category_name,u.username,w.weather ,stat.status_name,sg.segment
        FROM service_case As s 
        INNER JOIN category As c ON category_id = c.id
        INNER JOIN store As st  ON s.store_id = st.id
        INNER JOIN users As u 
        INNER JOIN `status` As stat  
        INNER JOIN arcive as a ON s.arcive_id = a.id
        INNER JOIN segment as sg
        INNER JOIN weather As w ON s.weather_id = w.id
        WHERE s.id = :id';

        $data = array(':id' => $c_id);
        $stmt = query($dbh, $sql, $data);

        if ($stmt) {
            debug('取得成功' . print_r($stmt, true));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {

            debug('取得失敗' . print_r($stmt, true));
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

//いいね数取得関数から
function getLike($s_id)
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM likes WHERE survise_id = :s_id';
        $data = array(':s_id' => $s_id);

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {

            return $stmt->rowCount();
        } else {

            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}
//使い回し数取得
function getUsed($u_id)
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM used WHERE survise_id = :s_id';
        $data = array(':s_id' => $u_id);

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {

            return $stmt->rowCount();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

function isLike($u_id, $s_id)
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM likes WHERE user_id = :u_id AND survise_id = :s_id';
        $data = array(':u_id' => $u_id, ':s_id' => $s_id);

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {

            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

function isUsed($u_id, $s_id)
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM used WHERE user_id = :u_id AND survise_id = :s_id';
        $data = array(':u_id' => $u_id, ':s_id' => $s_id);

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {

            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}


//いいねした人達取得
function like_people($s_id)
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM like_users WHERE servise_id = :s_id';
        $data = array(':s_id' => $s_id);

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

//その接客事例を使い回したユーザーを全員取得
function use_people($s_id)
{
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM used_users WHERE servise_id = :s_id';
        $data = array(':s_id' => $s_id);

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}
//===========================================
//セレクト入力保持用
//===========================================
function getSelectOne($id, $table, $culm)
{
    try {

        $dbh = dbh();
        $sql = "SELECT $culm FROM $table WHERE id = $id";
        $data = array();

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            debug('ゲットセレクト' . print_r($stmt, true));
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

function newsLike($id){
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM service_case WHERE like_flg = 1 AND `user_id`= :id';
        $data = array(':id'=>$id);

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}
function newsUse($id){
    try {
        $dbh = dbh();
        $sql = 'SELECT * FROM service_case WHERE use_flg = 1 AND `user_id` =:id ';
        $data = array(':id'=>$id);

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }
}

//ユーザー数取得

function getAllUsers(){
    try{

        $dbh = dbh();
        $sql = 'SELECT COUNT(*) FROM users';
        $data = array();
        $stmt = query($dbh, $sql, $data);
        if($stmt){
            return $stmt->fetchColumn();
        }else{
            return false;
        }
    }catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
    }


}

