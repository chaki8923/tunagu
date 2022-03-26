<?php
$site_title = 'ログイン';
require('function.php');
require('head.php');
require('auth.php');



$u_id = $_SESSION['user_id'];
getUser($u_id);
$getStore = getStore();
if(!empty($_POST)){

  $store = $_POST['store'];
  $name = str_replace('　', '', $_POST['name']);
  $pass = $_POST['pass'];
  $re_pass = $_POST['re_pass'];
  $check = (!empty($_SESSION['check']))? true:false;
  debug('ストアID'.print_r($store,true));
//各種未入力チェック
  blank_check($store,'store');
  blank_check($name,'name');
  blank_check($pass,'pass');
  blank_check($re_pass,'re_pass');


  if(empty($err_msg)){
    store_Match($pass,$store,'store');

  }
    if(empty($err_msg)){
      pass($name,$store,$pass,'pass');
  
  
      if(empty($err_msg)){
      
        name_Match($name,$pass,'name');
        pass_match($pass,$re_pass,'pass');
      
      
      }
  
    }

if(empty($err_msg)){
  try{
    $dbh = dbh();
    $sql = 'SELECT * FROM users WHERE username = :name AND store_id = :s_id';
    $data = array(':name'=>$name,':s_id'=>$store);
    $stmt = query($dbh,$sql,$data);

    if($stmt){
      $rst = $stmt->fetch(PDO::FETCH_ASSOC);

      if($check){
        $_SESSION['login_limt'] = 60 * 60 * 24;
        $_SESSION['login_time'] = time();
        
      }else{
        $_SESSION['login_limt'] = 60 * 60;
        $_SESSION['login_time'] = time();

      }
      $_SESSION['user_id'] = (!empty($rst['id']))? $rst['id']:'';
      header("Location:index.php");
    }else{
      $err_msg['common'] = MSG10;
    }
  }catch(Exception $e){
    error_log('エラー発生'.$e->getMessage());
  }
}

}
?>  
<body>
  <?php require('header.php'); 
  var_dump(getUser(13));
  ?>
  <div class="p-login-container">
  <p class="general-err"><?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?></p>
    <div class="p-login-inner">
    <h2 class="p-login-title">ログイン</h2>
      <form action="" class="p-login-form" method="post">

      <span class="p-login-errtxt"><?php if(!empty($err_msg['store'])) echo $err_msg['store']; ?></span>
        <select name="store" id="" class="p-login-store <?php if(!empty($err_msg['store'])) echo 'err'; ?>">
          <option value="">選択して下さい</option>
          <?php foreach($getStore as $key => $val):  ?>
          <option value="<?php echo $val['id'];  ?>"<?php if(!empty($store) && $store == $val['id']) echo 'selected';  ?>><?php echo $val['store']; ?></option>
          <?php endforeach; ?>
      </select>
      <label for="">
      <span class="p-login-errtxt"><?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?></span>
      <input type="text" class="p-login-name <?php if(!empty($err_msg['name'])) echo 'err'; ?>" name="name"  placeholder="name" value="<?php if(!empty($name)) echo $name; ?>">
      </label>

      <label for="">
      <span class="p-login-errtxt"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?></span>
      <input type="password" class="p-logi-pass <?php if(!empty($err_msg['pass'])) echo 'err'; ?>" name="pass" placeholder="pass" value="<?php if(!empty($pass)) echo $pass; ?>">
      </label>
      <label for="">
      <span class="p-login-errtxt"><?php if(!empty($err_msg['re_pass'])) echo $err_msg['re_pass']; ?></span>
      <input type="password" class="p-login-repass <?php if(!empty($err_msg['re_pass'])) echo 'err'; ?>" name="re_pass" placeholder="pass（確認用" value="<?php if(!empty($re_pass)) echo $re_pass; ?>">
      </label>
      <label for="check">次回から自動でログインする。</label>
      <input type="checkbox" class="p-login-check" name="check" id="check">

      <input type="submit" class="p-login-submit" value="送信">

      </form>
    </div>
  </div>
  <footer class="footer">
    <ul>
      <li>©︎ohta special servise webservise</li>
      <li>autor ryou chaki</li>
    </ul>
  </footer>
</body>