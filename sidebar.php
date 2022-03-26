<?php

// $err_msg = '';
selectDump($date,$like,$used,'new','like');

?>

<form action="" method="get" class="sidebar">
    <label for="new" id="" class="sidebar-label">新しい順</label><span class="<?php if(!empty($err_msg['new'])) echo 'err-span'; ?>"><?php if(!empty($err_msg['new'])) echo $err_msg['new'] ?></span>
    <select name="date" id="new" class="sidebar-select new-select js-select">
      <option value="" <?php if(!$date) echo 'selected'; ?>>全て</option>
      <option value= "1" <?php if($date == 1) echo 'selected'; ?>>新しい</option>
      <option value= "2" <?php if($date == 2) echo 'selected'; ?>>古い</option>
      
    </select>
    <label for="store" class="sidebar-label">店舗別</label>
    <select name="store" id="store" class="sidebar-select store-select js-select">
      <option value="">全て</option>
      <?php foreach($getstore as $key => $val): ?>
      <option value="<?php echo $val['id'] ?>" <?php if(!empty($store) && $val['id'] == $store) echo 'selected'; ?>><?php echo $val['store']; ?></option>
      <?php endforeach; ?>

      
    </select>
    <label for="like" class="sidebar-label">いいね順</label><span class="<?php if(!empty($err_msg['like'])) echo 'err-span'; ?>"><?php if(!empty($err_msg['like'])) echo $err_msg['like'];  ?></span>
    <select name="like" id="like" class="sidebar-select iine-select js-select">
      <option value= ''>全て</option>
      <option value= 1 <?php if(!empty($like) && $like == 1) echo 'selected' ?>>多い順</option>
      <option value= 2 <?php if(!empty($like) && $like == 2) echo 'selected' ?>>少ない順</option>
    </select>
    <label for="used" class="sidebar-label">使われてる順</label>
    <select name="used" id="used" class="sidebar-select used-select js-select">
      <option value="">全て</option>
      <option value="1" <?php if(!empty($used) && $used == 1) echo 'selected' ?>>多い順</option>
      <option value="2"  <?php if(!empty($used) && $used == 2) echo 'selected' ?>>少ない順</option>
    </select>
    <label for="weather" class="sidebar-label">天候</label>
    <select name="weather" id="weather" class="sidebar-select weather-select js-select">
      <option value="">全て</option>
      <?php foreach($getweather as $key => $val): ?>
      <option value="<?php echo $val['id']; ?>" <?php if(!empty($weather) && $val['id'] == $weather) echo 'selected'; ?>><?php echo $val['weather']; ?></option>
      <?php endforeach; ?>
    </select>
    <label for="place" class="sidebar-label">場所</label>
    <select name="category" id="place" class="sidebar-select place-select js-select">
      <option value="">全て</option>
      <?php foreach($getcategory as $key => $val): ?>
      <option value="<?php echo $val['id'] ?>" <?php if(!empty($c_id) && $val['id'] == $c_id ) echo 'selected'; ?>><?php echo $val['category_name']; ?></option>
      <?php endforeach; ?>
    </select>
    <label for="status" class="sidebar-label">お客様ステータス</label>
    <select name="status" id="status" class="sidebar-select status-select js-select">
      <option value="">全て</option>
      <?php foreach($status as $key => $val): ?>
      <option value="<?php echo $val['id'] ?>" <?php if(!empty($status_id) && $val['id'] == $status_id ) echo 'selected'; ?>><?php echo $val['status_name']; ?></option>
      <?php endforeach; ?>
    </select>
    <label for="segment" class="sidebar-label">お客様セグメント</label>
    <select name="segment" id="status" class="sidebar-select status-select js-select">
      <option value="">全て</option>
      <?php foreach($segment as $key => $val): ?>
      <option value="<?php echo $val['id'] ?>" <?php if(!empty($segment_id) && $val['id'] == $segment_id ) echo 'selected'; ?>><?php echo $val['segment']; ?></option>
      <?php endforeach; ?>
    </select>
    <label for="arcive" class="sidebar-label">アーカイブ</label>
    <select name="arcive" id="arcive" class="sidebar-select arcive-select js-select">
      <option value="">全て</option>
      <?php foreach($arciveALL as $key => $val): ?>
      <option value="<?php echo $val['id'] ?>" <?php if(!empty($a_id) && $val['id'] == $a_id ) echo 'selected'; ?>><?php echo $val['arcive']; ?></option>
      <?php endforeach; ?>
    </select>
    <div class="word-area">
    <label for="arcive" class="sidebar-label">ワード検索</label>
     <input type="text" name="word" value="<?php if(!empty($word)) echo $word; ?>" class="js-select">
    </div>
    <div class="my-pic">
    <h3 class="sidebar-name"><?php if(!empty($getUser)) echo $getUser['username'].'さん'?></h3>
      <img src="<?php  if(!empty($getUser['pic'])){ echo $getUser['pic']; }else{
        echo 'img/hapi.jpg';
      } ?>" alt="" class="my-pic-img">
      </div>
      <div class="btn-flex">
      <input type="submit" class="sidebar-submit" value="検索">
      <span class="clear-val">クリア</span>
      </div>
      <a href="caseEdit.php" class="new-form">+ 新規投稿</a>
    </form>