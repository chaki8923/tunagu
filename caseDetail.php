<?php

$site_title = '事例詳細';
require('function.php');
require('head.php');
require('auth.php');


$getFormData = getUser($_SESSION['user_id']);
$u_id = $_SESSION['user_id'];
$c_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : 0;
$cat_id = (!empty($_GET['category'])) ? $_GET['category'] : 0;
$p_id = (!empty($_GET['p_id']))? $_GET['p_id']:0;
$now_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : 1;
//場所の検索の値取得
//新しい準の取得
$date = (!empty($_GET['date'])) ? $_GET['date'] : '';
//いいね順の値取得
$like = (!empty($_GET['like'])) ? $_GET['like'] : '';
//使い回し値登録
$used_id = (!empty($_GET['used'])) ? $_GET['used'] : '';
//天気情報取得
$weather = (!empty($_GET['weather'])) ? $_GET['weather'] : '';
//店舗ID取得
$store = (!empty($_GET['store'])) ? $_GET['store'] : '';
//アーカイブID取得
$a_id = (!empty($_GET['arcive'])) ? $_GET['arcive'] : '';
//ステータスID取得
$status_id = (!empty($_GET['status'])) ? $_GET['status'] : '';
//セグメントID取得
$segment_id = (!empty($_GET['segment'])) ? $_GET['segment'] : '';
//ワード検索
$word = (!empty($_GET['word'])) ? $_GET['word'] : '';


$getCaseOne = getCaseOne($c_id);
$getUser = getUser($getCaseOne['user_id']);

debug('事例詳細'.print_r($getCaseOne,true));
$liker = like_people($c_id);
$used = use_people($c_id);
//画像設定してなかったらデフォルト画像

function defauli_pic($img){
  if(empty($img)){
    echo 'img/hapi.jpg';
  }else{
    echo $img;
  }
}

if(!empty($_POST['delete'])){
  debug('削除処理開始');
  try{
    $dbh = dbh();
    $sql = 'UPDATE service_case SET delete_flg = 1 WHERE id = :s_id';
    $data = array(':s_id' => $c_id);
    
    $stmt = query($dbh,$sql,$data);
    if($stmt){
      $_SESSION['toggle_msg'] = MSG18;
      header("Location:caseList.php");
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生'.$e->getMessage());
  }
}

?>

<body>
  <!-- ローディング画面 -->
<div id="loading">
  <img src="img/ohta-load.gif" alt="" class="load-ohta">
</div>
  <?php require('header.php'); ?>
  <!-- いいね押したら出てくる人たち -->
<section class="slide">
<div class="slide-iine">
  <?php foreach($liker as $key => $val): ?>
    <?php debug('liker中身',print_r($liker,true))  ?>
    <?php ?>
    <div class="slide-iine__list">
    <img src="<?php defauli_pic($val['user_img']) ?>" alt="" class="slide-iine__img"><p class="slide-iine__text"><?php echo $val['username'] ?><br><span class="user-store">(<?php echo $val['store']; ?>)</span></p>
    </div>
  <?php endforeach; ?>
</div>
</section>
<!-- 使い回した押したらでくる人達 -->
<section class="slide">
<div class="slide-use">
  <?php foreach($used as $key => $val): ?>
    <div class="slide-use__list">
      <img src="<?php defauli_pic($val['user_img']) ?>" alt="" class="slide-iine__img"><p class="slide-iine__text"><?php echo $val['username'] ?><br><span class="user-store">(<?php echo $val['store']; ?>)</span></p>
    </div>
  <?php endforeach; ?>
</div>
</section>
<a href="caseList.php?<?php echo 'p_id='.$now_id.'&date='.$date.'&store='.$store.'&like='.$like.'&used='.$used_id.'&weather='.$weather.'&category='.$cat_id.'&status='.$status_id.'&segment='.$segment_id.'&arcive='.$a_id.'&word='.$word; ?>" class="return-btn"><< 戻る</a>
  <div class="detail-box">
    <div class="icon">
      <div class="used-area">
        <i class="fas fa-2x fa-sync js-used used <?php if (isUsed($_SESSION['user_id'], $c_id)) echo 'active'; ?>" data-caseid=<?php echo $c_id;  ?>></i><span class="use_num <?php if (isUsed($_SESSION['user_id'], $c_id)) echo 'beta'; ?>"><?php echo getUsed($c_id); ?></span>
      </div>

      <div class="like-area">
        <i class="fas fa-2x fa-heart js-heart heart <?php if (isLike($_SESSION['user_id'], $c_id)) echo 'active'; ?>" data-likeid=<?php echo $c_id;  ?>></i><span class="like_num <?php if (isLike($_SESSION['user_id'], $c_id)) echo 'beta'; ?>"><?php echo getLike($c_id); ?></span>
      </div>
    </div>
    <h1>接客事例詳細</h1>
    <div class="detail-box-inner">
      <img src="img/kyun.gif" alt="" class="kyun">
      <img src="img/motituki.gif" alt="" class="motituki">

      <h2 class="detail-box-title">タイトル：<?php if (!empty($getCaseOne['title'])) echo $getCaseOne['title']; ?></h2>
      <div class="cat-flex">
        <p class="detail-box-staff">接客者：<?php if (!empty($getCaseOne['user_name'])) echo $getCaseOne['user_name'];  ?></p>
        <p class="detail-box-date">接客月：<?php if (!empty($getCaseOne['arcive'])) echo $getCaseOne['arcive'];  ?></p>
        <p class="detail-box-store">所属店舗：<?php if (!empty($getCaseOne['store'])) echo $getCaseOne['store'];  ?></p>
        <p class="detail-box-place">場所：<?php if (!empty($getCaseOne['category_name'])) echo $getCaseOne['category_name'];  ?></p>
        <p class="detail-box-weather">天気：<?php if (!empty($getCaseOne['weather'])) echo $getCaseOne['weather'];  ?></p>
      </div>
      <p class="detail-box-point point"><span class="point-left">考動ポイント：</span> <span class="point-right"><?php if(!empty($getCaseOne['servise_point'])) echo $getCaseOne['servise_point']; ?></span></p>
      <div class="clear"></div>
    </div>
    <div class="detail-box-textarea">
      <p class="detail-box_text"><?php if (!empty($getCaseOne['episode'])) echo $getCaseOne['episode'];  ?></p>

    </div>
    <div class="detail-box_btn detail-box_iine js-iine-btn"><i class="fas fa-2x fa-heart heart active"></i><span class="iine-user">を押した<br>ユーザー</span> <span class="iine-close">✖️閉じる</span></div>
    <div class="detail-box_btn detail-box_used js-used-btn"> <i class="fas fa-2x fa-sync used active"></i><span class="used-user">を押した<br>ユーザー</span> <span class="used-close">✖️閉じる</span></div>
    
    <div class="host-btn" style="<?php if($getCaseOne['user_id'] !== $_SESSION['user_id']) echo 'display: none;'; ?>">
      <a href="caseEdit.php?c_id=<?php if(!empty($c_id)) echo $c_id; ?>">編集する</a>
      <form action="" method="post">
        <input type="submit" name="delete" value="削除" class="delete">
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