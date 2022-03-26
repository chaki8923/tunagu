<?php
require('function.php');
$getuser = getUser($_SESSION['user_id']);
$username = htmlspecialchars( $getuser['username'], ENT_QUOTES, 'UTF-8');
$store = getUserStore($getuser['store_id']);

if(isset($_POST['caseID'])){
  $u_id = $_POST['caseID'];

  debug('$u_id中身'.print_r($u_id,true));
  debug('ajax通信開始');



  try{
      $dbh = dbh();

      $sql = 'SELECT * FROM used WHERE user_id =:u_id AND survise_id = :s_id';
      $data = array(':u_id'=>$_SESSION['user_id'],':s_id'=>$u_id);
      $stmt = query($dbh,$sql,$data);

      $rstcount = $stmt->rowCount();
      if(!empty($rstcount)){

        debug('使い回し履歴あり>>>>>>>>>>>>>>>>>>>>>>>>');
        //その事例を自分が使い回していたらテーブルから自分の情報を削除
        $sql = 'DELETE FROM used_users WHERE username = :u_name AND servise_id = :s_id';
        $data = array(':u_name'=>$username,':s_id'=>$u_id);
        $stmt = query($dbh,$sql,$data);

        //接客事例自体の使い回しデータ削除
        $sql = 'UPDATE service_case SET use_flg = 0 ,used = used - 1 WHERE id = :id';
        $data = array(':id'=>$u_id);
        $stmt = query($dbh,$sql,$data);
        $_SESSION['newsUse'] = 0;

        //表示用使い回しデータ総数の中から自分が押したやつだけ削除
        $sql = 'DELETE FROM used WHERE user_id = :u_id AND survise_id = :s_id';
        $data = array(':u_id'=>$_SESSION['user_id'],':s_id'=>$u_id);
        $stmt = query($dbh,$sql,$data);
        
        echo getUsed($u_id);
        
      }else{
        debug('使い回し履歴なし>>>>>>>>>>>>>>>>>>>>>>>>');

        //その事例を自分が使いましてなかったらテーブルにユーザー情報登録
        $sql = 'INSERT INTO used_users (username,user_img,servise_id,store) VALUES (:u_name,:u_img,:s_id,:store)';
        $data = array(':u_name'=>$getuser['username'],':u_img'=>$getuser['pic'],':s_id'=>$u_id,':store'=>$store['store']);
        $stmt = query($dbh,$sql,$data);

        //接客事例自体についてる使い回し数に＋１
        $sql = 'UPDATE service_case SET  use_flg = 1, used = used + 1 WHERE id = :id';
        $data = array(':id'=>$u_id);
        $stmt = query($dbh,$sql,$data);
        $_SESSION['newsUse'] = 1;

        //表示用使い回し総数に自分のIDなければ＋１
        $sql = 'INSERT INTO used (user_id,survise_id) VALUE (:user_id,:survise_id)';
        $data = array(':user_id'=>$_SESSION['user_id'],':survise_id'=>$u_id);
        $stmt = query($dbh,$sql,$data);

        echo getUsed($u_id);

      }

      
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