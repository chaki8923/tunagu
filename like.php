<?php
require('function.php');
$getuser = getUser($_SESSION['user_id']);
$username = htmlspecialchars( $getuser['username'], ENT_QUOTES, 'UTF-8');
$store = getUserStore($getuser['store_id']);
debug('store'.print_r($store,true));


if(isset($_POST['likeID'])){
  $p_id = $_POST['likeID'];

  debug('$p_id中身'.print_r($p_id,true));
  debug('ajax通信開始');

  


  try{
      $dbh = dbh();

      $sql = 'SELECT * FROM likes WHERE user_id =:u_id AND survise_id = :s_id';
      $data = array(':u_id'=>$_SESSION['user_id'],':s_id'=>$p_id);
      $stmt = query($dbh,$sql,$data);

      $rstcount = $stmt->fetchColumn();
      if(!empty($rstcount)){

        debug('いいね履歴あり>>>>>>>>>>>>>>>>>>>>>>>>');
         //誰がいいねしたか取得してその人の情報を消す。
         $sql = 'DELETE FROM like_users WHERE username = :u_name AND servise_id = :s_id';
         $data = array(':u_name'=>$username,':s_id'=>$p_id);
         $stmt = query($dbh,$sql,$data);
        
        //接客事例テーブルのいいね数をひとつ減らす
        $sql = 'UPDATE service_case SET like_flg = 0, like_number = like_number - 1 WHERE id = :id';
        $data = array(':id'=>$p_id);
        $stmt = query($dbh,$sql,$data);
        //セッションに追加してnews非表示表示の判断材料にする。
        $_SESSION['newsLike'] = 0;
        //表示用いいね数総数を指定のテーブルから消す
        $sql = 'DELETE FROM likes WHERE user_id = :u_id AND survise_id = :s_id';
        $data = array(':u_id'=>$_SESSION['user_id'],':s_id'=>$p_id);
        $stmt = query($dbh,$sql,$data);
        
        debug('いいね履歴ありの総いいね'.print_r(getLike($p_id)));
        return getLike($p_id);
        
      }else{
        debug('いいね履歴なし>>>>>>>>>>>>>>>>>>>>>>>>');
        //誰がいいねしたかテーブルに登録
        $sql = 'INSERT INTO like_users (username,user_img,servise_id,store) VALUES (:username,:user_img,:servise_id,:store)';
        $data = array(':username'=>$username,':user_img'=>$getuser['pic'],':servise_id'=>$p_id,':store'=>$store['store']);
        $stmt = query($dbh,$sql,$data);
        
       
        //接客事例自体につけたいいねを増やす。
        $sql = 'UPDATE service_case SET like_flg = 1, like_number = like_number + 1 WHERE id = :id';
        $data = array(':id'=>$p_id);
        $stmt = query($dbh,$sql,$data);
        
        //セッションに追加してnews非表示表示の判断材料にする。
        $_SESSION['newsLike'] = 1;
        //いいねした事なければいいね総数に追加
        $sql = 'INSERT INTO likes (user_id,survise_id) VALUE (:user_id,:survise_id)';
        $data = array(':user_id'=>$_SESSION['user_id'],':survise_id'=>$p_id);
        $stmt = query($dbh,$sql,$data);
        
        
        
        
      }
      
      debug('いいね履歴なしの総いいね'.print_r(getLike($p_id)));
      return getLike($p_id);
      
    if($stmt){
      debug('ajax成功');
      
    }else{
      debug('ajax失敗');
    }

  }catch(Exception $e){
    error_log('エラー発生'.$e->getMessage());
  }
}

?>