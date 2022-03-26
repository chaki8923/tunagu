<?php

require('function.php');
require('head.php');


$getStore = getStore();
$getFormData = getUser($_SESSION['user_id']);
debug('user情報' . print_r($getFormData, true));

if (!empty($_POST)) {


  $name = $_POST['name'];
  $store = $_POST['store'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];
  $position = $_POST['position'];

  $pic = (!empty($_FILES['pic']['name'])) ? uploadPic($_FILES['pic'],'pic') : "";


  //空白チェック
  blank_check($name, 'name');
  blank_check($pass, 'pass');
  blank_check($store, 'store');

  //パスワード再入力合致チェック
  pass_match($pass, $pass_re, 'pass');

  //文字数チェック
  text_min($pass, 'pass');
  //パスワード形式チェック
  pass_half($pass, 'pass');


  if (empty($err_msg)) {

    try {
      $dbh = dbh();
      $sql = 'INSERT INTO users (username,pass,store_id,position,pic,creat_date) VALUES(:username,:pass,:store_id,:position,:pic,:create_date)';
      $data = array(':username' => str_replace('　', '', $name), ':pass' => $pass, ':store_id' => $store, ':position' => $position, ':pic' => $pic, ':create_date' => date('Y-m-d: h:i:s'));
      debug('送信OK');

      $stmt = query($dbh, $sql, $data);
      if ($stmt) {

        $limit = 60 * 200;
        $_SESSION['user_id'] = $dbh->lastInsertId();
        $_SESSION['login_time'] = time();
        $_SESSION['login_limit'] = $limit;

        header("Location:index.php");
      }
    } catch (Exception $e) {
      error_log('エラー発生' . $e->getmessage());
    }
  }
}
?>

<body>
  <?php require('header.php'); ?>
  <main class="signup">
    <section class="signup-img signup-img-top">
      <?php if ($_SERVER['HTTP_REFERER'] == $_SERVER['PHP_SELF']) echo ''; ?>
      <div class="err_modal <?php if ($_SERVER['HTTP_REFERER'] == 'http://localhost/tunag/signup.php') echo 'fadein'; ?>">
        <h2>フォーム入力に<br>
          誤りがあります。</h2>
        <img src="img/arrow_b.gif" alt="" class="arrow">
      </div>
      <div class="modal">
        <h2 class="modal-title">店内だけにとどまらずにオータの強みを<br>
          店舗外のスタッフと情報共有</h2>
      </div>
    </section>
    <section class="signup-img signup-img-middle">
      <div class="modal">
        <h2 class="modal-title">良い接客事例は皆んなでいいねを<br>
          付けて評価しよう</h2>
      </div>

    </section>
    <section class="signup-img signup-img-bottom">
      <div class="modal">
        <h2 class="modal-title">最終的には組織エンゲージメントを<br>
          向上させて組織を強化に繋げます。</h2>
      </div>
    </section>


    <form action="" method="post" enctype="multipart/form-data" class="signup-form">
      <div class="form-inner">
        <h1 class="signup-title">さあ、始めよう。</h1>
        <!-- 名前入力 -->
        <input type="text" name="name" class="signup-form-inputbox <?php if (!empty($err_msg['name'])) echo 'err' ?>" placeholder="名前(必須)  <?php if (!empty($err_msg['name']))  echo $err_msg['name']; ?>" value="<?php if (!empty($name)) echo $name; ?>">

        <!-- 店舗名入力 -->
        <span class="input-attend <?php if (!empty($err_msg['store'])) echo 'err'; ?>">所属店入力</span>
        <select name="store" id="" class="signup-form-store">
          <option value="">選択して下さい</option>
          <?php foreach ($getStore as $key => $val) :  ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($store) && $store == $val['id']) echo 'selected';  ?>><?php echo $val['store']; ?></option>
          <?php endforeach; ?>
        </select>

        <!-- 役職入力 -->
        <input type="text" name="position" class="signup-form-inputbox" placeholder="役職" value="<?php if (!empty($_POST['position'])) echo $position ?>">

        <!-- パスワード入力 -->
        <span><?php if (!empty($err_msg['pass']))  echo $err_msg['pass']; ?></span>
        <input type="password" name="pass" class="signup-form-inputbox <?php if (!empty($err_msg['pass'])) echo 'err' ?>"" placeholder=" パスワード（社員番号の後にお好きな1桁の数字の合計６文字）" >
        
        <!-- 再入力 -->
        <span><?php if (!empty($err_msg['pass_re']))  echo $err_msg['pass_re']; ?></span>
        <input type="password" name="pass_re" class="signup-form-inputbox">

        <!-- プロフィール画面入力 -->
        <div class="signup-form-drop">
          <input type="hidden" name="MAX_FILE_SIZE" value="940000">
          <input type="file" name="pic" class="signup-form-picbox">
          <img src="" alt="" class="prevew">
          <p>プロフィール写真(任意) <span style="color: red;"><?php if(!empty($err_msg['pic'])) echo $err_msg['pic']; ?></span></p>
        </div>
        <div class="btn-area">
          <input type="submit" class="signup-form-submit" value="登録">
          <div class="c-btn" id="crear">写真クリア</div>
        </div>
      </div>
    </form>
  </main>
  <footer class="footer">
    <ul>
      <li>©︎ohta special servise webservise</li>
      <li>autor ryou chaki</li>
    </ul>
  </footer>
</body>