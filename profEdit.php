<?php

$site_title = 'プロフィール編集';

require('function.php');
require('head.php');


$getFormData = getUser($_SESSION['user_id']);
$getStore = getStore();

debug('エラー中身' . print_r($err_msg, true));
if (!empty($_POST['submit'])) {
  $user_id = $_SESSION['user_id'];
  $name = $_POST['name'];
  $store = $_POST['store'];
  $position = $_POST['position'];
  //空白チェック
  blank_check($name, 'name');
  blank_check($store, 'store');
  $pic = (!empty($_FILES['pic']['name'])) ? uploadPic($_FILES['pic'],'pic') : "";
  $pic = (empty($_FILES['pic']['name']) && !empty($getFormData['pic'])) ? $getFormData['pic'] : $pic;
  if (empty($err_msg)) {


    try {
      $dbh = dbh();
      $sql = 'UPDATE users SET username = :u_name, store_id = :store, position = :position, pic = :pic WHERE id = :id';
      $data = array(':u_name' => $name, ':store' => $store, ':position' => $position, ':pic' => $pic, ':id' => $user_id);

      $stmt = query($dbh, $sql, $data);
      if ($stmt) {
        $_SESSION['toggle_msg'] = MSG06;
        header("Location:caseList.php");
      }
    } catch (Exception $e) {
      error_log('エラー発生' . $e->getMessage());
    }
  }
} elseif (!empty($_POST['delete'])) {
  $user_id = $_SESSION['user_id'];
  $name = $_POST['name'];
  $store = $_POST['store'];
  $position = $_POST['position'];

  $pic = (!empty($_FILES['pic']['name'])) ? uploadPic($_FILES['pic'],'pic') : "";
  $pic = (empty($_FILES['pic']['name']) && !empty($getFormData['pic'])) ? $getFormData['pic'] : $hapi;
  try {
    $dbh = dbh();
    $sql = 'UPDATE users SET pic = :pic WHERE id = :id';
    $data = array(':pic' => null, ':id' => $user_id);
    $stmt = query($dbh, $sql, $data);

    if ($stmt) {

      header("Location:profEdit.php");
    }
  } catch (Exception $e) {
    error_log('エラー発生' . $e->getMessage());
  }
}
?>

<body>
  <?php if(basename($_SERVER['HTTP_REFERER']) !== 'profEdit.php'): ?>
<div id="loading">
  <img src="img/ohta-load.gif" alt="" class="load-ohta">
</div>
<?php endif; ?>
  <?php require('header.php'); ?>
  </section>
  <form action="" method="post" enctype="multipart/form-data" class="signup-form signup-form-prof">
    <h1 class="signup-title">プロフィール編集</h1>
    <!-- 名前入力 -->
    <input type="text" name="name" class="signup-form-inputbox <?php if (!empty($err_msg['name'])) echo 'err' ?>" placeholder="名前(必須)  <?php if (!empty($err_msg['name']))  echo $err_msg['name']; ?>" value="<?php echo formkeep('username'); ?>">

    <!-- 店舗名入力 -->
    <span class="input-attend <?php if (!empty($err_msg['store'])) echo 'err'; ?>">所属店入力</span>
    <select name="store" id="" class="signup-form-store">
      <option value="">選択して下さい</option>
      <?php foreach ($getStore as $key => $val) :  ?>
        <option value="<?php echo $val['id'];  ?>" <?php if (!empty($store) && $val['id'] == $store) echo 'selected'; ?>><?php echo $val['store'];  ?></option>
      <?php endforeach; ?>
    </select>

    <!-- 役職入力 -->
    <input type="text" name="position" class="signup-form-inputbox" placeholder="役職" value="<?php echo formKeep('position'); ?>">

    <!-- プロフィール画面入力 -->
    <div class="signup-form-drop">
      <input type="hidden" name="MAX_FILE_SIZE" value="1640000">
      <input type="file" name="pic" class="signup-form-picbox">
      <img src="<?php if (!empty($getFormData['pic'])) echo $getFormData['pic']; ?>" alt="" class="prevew" style="<?php if (!empty($getFormData['pic'])) {
        echo 'display:block;';
        } else {echo 'display:none;';} ?>">
      <p>プロフィール写真</p>
      <span class="input-attend <?php if (!empty($err_msg['pic'])) echo 'err'; ?>"><?php echo $err_msg['pic']; ?></span>
    </div>
    <div class="btn-area">
      <input type="submit" name="submit" class="signup-form-submit" value="登録">
      <input type="submit" name="delete" class="signup-form-delete" value="写真削除">

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