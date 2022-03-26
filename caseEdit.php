<?php

require('function.php');

if (!empty($c_id)) {
  $site_title = '事例編集';
} else {
  $site_title = '事例登録';
}
require('head.php');
require('auth.php');



$getFormData = getUser($_SESSION['user_id']);
$u_id = $_SESSION['user_id'];
$c_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';

//入力保持用
$getCaseOne = getCaseOne($c_id);

$getWeather = get_weather();
$getCategory = get_category();
$getStoreOne = getStoreOne($getFormData['store_id']);
$arcive = getAllarcive();//本番では代入する関数変えるAll無くす
$status = getStatus();
$segment = getsegment();
$getStore = getStore();
$store = $_POST['store'];

debug('店情報:' . print_r($getStoreOne, true));
debug('ユーザー情報:' . print_r($_SESSION['user_id'], true));
debug('ステータス情報:' . print_r($status, true));
debug('$getCaseOne中身:' . print_r($getCaseOne, true));
debug('$err中身:' . print_r($err_msg, true));


if (!empty($_POST)) {
  $title = (!empty($_POST['title'])) ? $_POST['title'] : '';
  $name = (!empty($_POST['name'])) ? $_POST['name'] : '';
  $a_id = (!empty($_POST['arcive'])) ? $_POST['arcive'] : 0;
  $point = (!empty($_POST['servise_point'])) ? $_POST['servise_point'] : '';
  $episode = (!empty($_POST['episode'])) ? $_POST['episode'] : '';
  $cat_id = (isset($_POST['category_id'])) ? $_POST['category_id'] : 0;
  $w_id = (isset($_POST['weather'])) ?  $_POST['weather'] : 0;
  $store = (isset($_POST['store'])) ? $_POST['store'] : 0;
  $status_id = (!empty($_POST['status_id'])) ? $_POST['status_id'] : '';
  $segment_id = (!empty($_POST['segment'])) ? $_POST['segment'] : '';
  if (empty($err_msg)) {
    debug('エラーなし');
  } else {
    debug(('エラーある'));
  }


  blank_check($store, 'store');
  blank_check($title, 'title');
  blank_check($episode, 'episode');
  blank_check($cat_id, 'category_id');
  blank_check($w_id, 'weather');
  blank_check($a_id, 'arcive');
  blank_check($point, 'servise_point');
  blank_check($status_id, 'status_id');
  blank_check($segment_id, 'segment');


  if (empty($err_msg)) {

    if (isset($_POST['henshu'])) {

      try {
        $dbh = dbh();
        $sql = 'UPDATE service_case SET title = :title,category_id = :c_id ,weather_id = :w_id,store_id = :s_id,user_id = :u_id,`user_name` = :u_name,episode = :epi,arcive_id = :arcive_id,servise_point = :servise_point,status_id = :status_id,segment_id = :segment,create_date = :create_date WHERE id = :id';
        $data = array(':title' => $title, ':c_id' => $cat_id, ':w_id' => $w_id, ':s_id' => $store, ':u_id' => $u_id, ':epi' => $episode, ':arcive_id' => $a_id, ':segment' => $segment_id,':u_name'=>$name, ':servise_point' => $point, ':status_id' => $status_id, ':create_date' => date('Y-m-d H:i:s'), ':id' => $c_id);
        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
          global $getCaseOne;

          $_SESSION['toggle_msg'] = MSG14;
          header("Location:caseList.php");
        } else {
          return false;
        }
      } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
      }
    } elseif (isset($_POST['edit'])) {

      try {
        $dbh = dbh();
        $sql = 'INSERT INTO service_case (title,category_id,weather_id,store_id,user_id,`user_name`,episode,arcive_id,servise_point,status_id,segment_id,create_date) VALUES (:title,:c_id,:w_id,:s_id,:u_id,:u_name,:epi,:arcive_id,:servise_point,:status_id,:segment,:create_date)';
        $data = array(':title' => $title, ':c_id' => $cat_id, ':w_id' => $w_id, ':s_id' => $store, ':u_id' => $u_id, ':u_name'=>$name,':epi' => $episode, ':arcive_id' => $a_id, ':segment' => $segment_id, ':servise_point' => $point, ':status_id' => $status_id, ':create_date' => date('Y-m-d H:i:s'));

        $stmt = query($dbh, $sql, $data);
        if ($stmt) {
          debug('登録処理' . print_r($stmt, true));
          $_SESSION['toggle_msg'] = MSG07;
          header("Location:caseList.php");
        } else {
          return false;
        }
      } catch (Exception $e) {
        error_log('エラー発生' . $e->getMessage());
      }
    }
  }
}

?>

<body>
  <!-- ローディング画面 -->
  <?php if (basename($_SERVER['HTTP_REFERER']) !== 'caseEdit.php') : ?>
    <div id="loading">
      <img src="img/ohta-load.gif" alt="" class="load-ohta">
    </div>
  <?php endif; ?>
  <?php require('header.php'); ?>

  <form action="" method="POST" class="form form-caseedit" style="<?php if (!empty($c_id)) echo 'display: none;'; ?>">

    <div class="form-caseedit-title">
      <h1>case edit</h1>
      <span class="form-caseedit-sub">case edit</span>
      <h2 class="form-caseedit-main">接客事例登録</h2>
      <h2 class="err-title" style="<?php if (empty($err_msg)) echo 'display: none;'; ?>">赤色の場所の入力が完了しておりません。</h2>
      <?php
      if (empty($err_msg)) {
        debug('エラーなし2');
      } else {
        debug(('エラーある2'));
        debug('エラー内容' . print_r($err_msg, true));
      }
      ?>
    </div>
    <div class="form-input">
      <span class="<?php if (!empty($err_msg['store'])) echo 'err'; ?>">店名</span>
      <select name="store" id="" class="signup-form-store edit-select">
      <option value="">選択して下さい</option>
      <?php foreach($getStore as $key => $val): ?>
        <option value="<?php echo $val['id'];  ?>" <?php if (!empty($store) && $store == $val['id']) echo 'selected'; ?>><?php  echo $val['store'];  ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-input">
      <span>タイトル</span>
      <input type="text" class="form-caseedit-input <?php if (!empty($err_msg['title'])) echo 'err'; ?>" placeholder="<?php if (!empty($err_msg['title'])) echo $err_msg['title']; ?>" value="<?php echo formKeep('title'); ?>" name="title">
    </div>
    <div class="form-category-flex">
      <div class="form-category">
        <label for="category" class="edit-label <?php if (!empty($err_msg['category_id'])) echo 'err-span' ?>">接客場所　</label>
        <select name="category_id" id="category" class="edit-select">
          <option value=0>選択してください。</option>
          <?php foreach ($getCategory as $key => $val) : ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($cat_id) && $cat_id == $val['id']) echo 'selected'; ?>><?php echo $val['category_name'];  ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-category">
        <label for="weather" class="edit-label <?php if (!empty($err_msg['weather'])) echo 'err-span' ?>">天候　</label>
        <select name="weather" id="weather" class="edit-select">
          <option value=0>選択してください。</option>
          <?php foreach ($getWeather as $key => $val) :  ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($w_id) && $w_id == $val['id']) echo 'selected'; ?>><?php echo $val['weather']; ?></option>
          <?php endforeach;  ?>

        </select>
      </div>
      <div class="form-category">
        <label for="arcive" class="edit-label <?php if (!empty($err_msg['arcive'])) echo 'err-span' ?>">年月　</label>
        <select name="arcive" id="arcive" class="edit-select">
          <option value=0>選択してください。</option>
          <?php foreach ($arcive as $key => $val) :  ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($a_id) && $a_id == $val['id']) echo 'selected'; ?>><?php echo $val['arcive']; ?></option>
          <?php endforeach;  ?>
        </select>
      </div>
    </div>
    <div class="form-category-flex">
      <div class="form-category">
        <label for="status" class="edit-label <?php if (!empty($err_msg['status_id'])) echo 'err-span' ?>">お客様ステータス　</label>
        <select name="status_id" id="status" class="edit-select">
          <option value=''>選択してください。</option>
          <?php foreach ($status as $key => $val) :  ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($status_id) && $status_id == $val['id']) echo 'selected'; ?>><?php echo $val['status_name']; ?></option>
          <?php endforeach;  ?>
        </select>
      </div>
      <div class="form-category">
        <label for="segment" class="edit-label <?php if (!empty($err_msg['segment'])) echo 'err-span' ?>">お客様セグメント　</label>
        <select name="segment" id="segment" class="edit-select">
          <option value=0>選択してください。</option>
          <?php foreach ($segment as $key => $val) :  ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($segment_id) && $segment_id == $val['id']) echo 'selected'; ?>><?php echo $val['segment']; ?></option>
          <?php endforeach;  ?>

        </select>
      </div>
    </div>

    </div>
    <div class="survise-point">
      <label for="point" class="edit-label <?php if (!empty($err_msg['servise_point'])) echo 'err-span' ?>">考動ポイント</label>
      <input type="text" name="servise_point" class="survise-point__text" id="point" style="width: 90%; height: 42px;" value="<?php if (!empty($_POST['servise_point'])) echo $point; ?>">
    </div>

    <div class="form-flex">
      <span>スタッフ</span>
      <div class="form-input-flex">
        <input type="text" class="form-caseedit-inputflex <?php if (!empty($err_msg['name'])) echo 'err'; ?>" placeholder="<?php if (!empty($err_msg['name'])) echo $err_msg['name']; ?>" value="<?php if(!empty($name)) echo $name; ?>" name="name">
      </div>
    </div>

    <div class="form-input">
      <span class="<?php if (!empty($err_msg['episode'])) echo 'err-span' ?>">内容　</span>
      <textarea name="episode" id="" cols="50" rows="20" class="textarea <?php if (!empty($err_msg['episode'])) echo 'err'; ?>"><?php if (!empty($_POST['episode']))  echo $episode; ?></textarea>
      <div class="count-area">
        <span class="js-text-count count-text"></span>/<span class="max">1000</span><span class="js-fontlength-err">文字数オーバーです。</span>
      </div>
    </div>

    <input type="submit" name="edit" class="c-btn servise-btn" value="登録">
  </form>

  <!-- -------------------------------------------------------------------------------- -->
  <!-------------- //編集画面から飛んできたらこっち表示 ------------------------->
  <!-- -------------------------------------------------------------------------------- -->
  <form action="" method="POST" class="form form-caseedit" style="<?php if (empty($c_id)) echo 'display: none;'; ?>">
    <div class="form-caseedit-title">
      <h1>case edit</h1>
      <span class="form-caseedit-sub">case edit</span>
      <h2 class="form-caseedit-main">接客事例編集</h2>
      <h2 class="err-title" style="<?php if (empty($err_msg)) echo 'display: none;'; ?>">赤色の場所の入力が完了しておりません。</h2>
    </div>
    <div class="form-input">
      <span>店名</span>
      <select name="store" id="" class="signup-form-store edit-select">
        <!-- <option value="<?php //if (!empty($getCaseOne)) echo $getStoreOne['id'];  ?>"><?php //if (!empty($getStoreOne)) echo $getStoreOne['store'];  ?></option> -->
        <?php foreach($getStore as $key => $val): ?>
        <option value="<?php echo $val['id'];  ?>" <?php if (!empty($store) && $store == $val['id']) echo 'selected'; ?>><?php  echo $val['store'];  ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-input">
      <span>タイトル</span>
      <input type="text" class="form-caseedit-input <?php if (!empty($err_msg['title'])) echo 'err'; ?>" placeholder="<?php if (!empty($err_msg['title'])) echo $err_msg['title']; ?>" value="<?php echo getCaseForm('title'); ?>" name="title">
    </div>
    <div class="form-category-flex">
      <div class="form-category">
        <label for="category" class="edit-label <?php if (!empty($err_msg['category_id'])) echo 'err-span' ?>">接客場所　</label>
        <select name="category_id" id="category" class="edit-select">
          <option value=0>選択してください。</option>
          <?php foreach ($getCategory as $key => $val) : ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($cat_id) && $cat_id == $val['id']) echo 'selected'; ?>><?php echo $val['category_name'];  ?></option>
          <?php endforeach; ?>

        </select>
      </div>

      <div class="form-category">
        <label for="weather" class="edit-label <?php if (!empty($err_msg['weather'])) echo 'err-span' ?>">天候　</label>
        <select name="weather" id="weather" class="edit-select">
          <option value=0>選択してください。</option>
          <?php foreach ($getWeather as $key => $val) :  ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($w_id) && $w_id == $val['id']) echo 'selected'; ?>><?php echo $val['weather']; ?></option>
          <?php endforeach;  ?>

        </select>
      </div>
      <div class="form-category">
        <label for="arcive" class="edit-label <?php if (!empty($err_msg['arcive'])) echo 'err-span' ?>">年月　</label>
        <select name="arcive" id="arcive" class="edit-select">
          <option value=0>選択してください。</option>
          <?php foreach ($arcive as $key => $val) :  ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($a_id) && $a_id == $val['id']) echo 'selected'; ?>><?php echo $val['arcive']; ?></option>
          <?php endforeach;  ?>

        </select>
      </div>
    </div>
    <div class="form-category-flex">
      <div class="form-category">
        <label for="status" class="edit-label <?php if (!empty($err_msg['status_id'])) echo 'err-span' ?>">お客様ステータス　</label>
        <select name="status_id" id="status" class="edit-select">
          <option value=0>選択してください。</option>
          <?php foreach ($status as $key => $val) :  ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($status_id) && $status_id == $val['id']) echo 'selected'; ?>><?php echo $val['status_name']; ?></option>
          <?php endforeach;  ?>
        </select>
      </div>
      <div class="form-category">
        <label for="segment" class="edit-label <?php if (!empty($err_msg['segment'])) echo 'err-span' ?>">お客様セグメント　</label>
        <select name="segment" id="segment" class="edit-select">
          <option value=0>選択してください。</option>
          <?php foreach ($segment as $key => $val) :  ?>
            <option value="<?php echo $val['id'];  ?>" <?php if (!empty($segment_id) && $segment_id == $val['id']) echo 'selected'; ?>><?php echo $val['segment']; ?></option>
          <?php endforeach;  ?>
        </select>
      </div>
    </div>

    </div>
    <div class="survise-point">
      <label for="point" class="edit-label <?php if (!empty($err_msg['servise_point'])) echo 'err-span' ?>">考動ポイント</label>
      <input type="text" name="servise_point" class="survise-point__text" id="point" style="width: 90%; height: 42px;" value="<?php echo getCaseForm('servise_point'); ?>">
    </div>

    <div class="form-flex">
      <span>スタッフ</span>
      <div class="form-input-flex">
        <input type="text" class="form-caseedit-inputflex <?php if (!empty($err_msg['name'])) echo 'err'; ?>" placeholder="<?php if (!empty($err_msg['name'])) echo $err_msg['name']; ?>" value="<?php if(!empty($name)) echo $name; ?>" name="name">
      </div>
    </div>

    <div class="form-input">
      <span class="<?php if (!empty($err_msg['episode'])) echo 'err-span' ?>">内容　</span>
      <textarea name="episode" id="" cols="50" rows="20" class="textarea <?php if (!empty($err_msg['episode'])) echo 'err'; ?>"><?php if (!empty($getCaseOne)) echo $getCaseOne['episode']; ?></textarea>
      <div class="count-area">
        <span class="js-text-count count-text"></span>/<span class="max">1000</span><span class="js-fontlength-err">文字数オーバーです。</span>
      </div>
    </div>

    <input type="submit" name="henshu" class="c-btn servise-btn" value="編集完了">
  </form>
  <footer class="footer">
    <ul>
      <li>©︎ohta special servise webservise</li>
      <li>autor ryou chaki</li>
    </ul>
  </footer>
</body>