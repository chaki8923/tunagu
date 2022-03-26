<?php
if(basename($_SERVER['HTTP_REFERER']) == 'newLike.php'){

  deleteLike();
}
if(basename($_SERVER['HTTP_REFERER']) == 'newsUse.php'){

  deleteUse();
}


function deleteLike()
{
  try {
    $dbh = dbh();
    $sql = 'UPDATE service_case SET like_flg = 0 WHERE like_flg = 1';
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


function deleteUse()
{
  try {
    $dbh = dbh();
    $sql = 'UPDATE service_case SET use_flg = 0 WHERE use_flg = 1';
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

//ログインタイムに値が入ってればログインしたことあるユーザー
if(!empty($_SESSION['login_time'])){

  if($_SESSION['login_time'] + $_SESSION['login_limit'] < time()){
    debug('セッションタイムアウト切れ');
    
    if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
      
      debug('ログイン画面へ遷移');
      session_destroy();
      header(("Location:login.php"));
    }
  }else{
    
    debug('セッション切れ');
    $_SESSION['login_time'] = time();
    $_SESSION['login_limit'] = 60*60*30*24;
    if(basename($_SERVER['PHP_SELF']) === 'login.php'){
      
      header("Location:index.php");
    }
  }
  
  
}else{
  
  debug('未ログインユーザー');
  if(basename($_SERVER['PHP_SELF']) !== 'login.php'){

    header("Location:signup.php");
  }
}


?>