<?php
$site_title = '事例一覧';
require('function.php');
require('head.php');
require('auth.php');


$span = 9;
//ページ数取得
$now_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : 1;
//場所の検索の値取得
$c_id = (!empty($_GET['category'])) ? $_GET['category'] : '';
//新しい準の取得
$date = (!empty($_GET['date'])) ? $_GET['date'] : '';
//いいね順の値取得
$like = (!empty($_GET['like'])) ? $_GET['like'] : '';
//使い回し値登録
$used = (!empty($_GET['used'])) ? $_GET['used'] : '';
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

debug('アーカイブID' . print_r($a_id, true));

//店舗情報取得
$getstore = getStore();
//天候取得
$getweather = get_weather();
//場所取得
$getcategory = get_category();
//アーカイブ取得
$arcive = getarcive();
//アーカイブ取得
$arciveALL = getAllarcive();
//ステータス取得
$status = getStatus();
//セグメント取得
$segment = getsegment();

//現在のページの最初の数
$minpage = ($now_id - 1) * $span;
//ユーザー情報取得
$getUser = getUser($_SESSION['user_id']);
//ページ数とトータルページ取得
$allCase = allCase();
//検索結果取得
$select = selectCase($span, $minpage, $c_id, $date, $like, $weather, $store, $used, $a_id, $status_id, $segment_id, $word);

$count = count($select['data']);

debug('ユーザー情報' . print_r($select, true));
// debug('事例'.print_r($select,true));

$getNewsLike = (!empty(newsLike($_SESSION['user_id'])))? newsLike($_SESSION['user_id']):'';
$getNewsUse = (!empty(newsUse($_SESSION['user_id'])))? newsUse($_SESSION['user_id']):'';

?>

<body>
  <!-- ローディング画面 -->
<?php if(!preg_match("/List/",basename($_SERVER['PHP_SELF']))): ?>
<div id="loading">
  <img src="img/ohta-load.gif" alt="" class="load-ohta">
</div>
<?php endif; ?>

  <?php require('header.php'); ?>
  <p class="toggle_msg"><?php echo getSession('toggle_msg'); ?></p>

  <?php require('sidebar.php'); ?>


  <!-- 表示エリア -->
  <?php

  if (empty($err_msg)) :

  ?>

    <div class="case-container">

      <span class="search-result">検索結果:<?php echo $select['total'];  ?>件ヒット</span>
      <div class="news-area">
        <p><i class="fas fa-bullhorn"></i>あなたへのお知らせ</p>
        <ul class="news-list">
          <?php if ($getNewsLike) : ?>
            <!-- ここのリンクにもp_idつければほぼほぼ完成 -->
            <li class="news-item"><a href="newLike.php" class="news-link news-link__iine">新着いいねがあります。</a></li>
          <?php endif; ?>
          <?php if ($getNewsUse) : ?>
            <li class="news-item"><a href="newsUse.php" class="news-link news-link__use">誰かがあなたの接客と同じ接客をしました。</a></li>
          <?php endif; ?>
          <?php if(!$getNewsLike && !$getNewsUse): ?>
            <li class="news-item">あなたへのお知らせはありません。</li>
          <?php endif; ?>
        </ul>
      </div>
      <?php foreach ($select['data'] as $key => $val) : ?>
        <a href="caseDetail.php?c_id=<?php if (!empty($val['id'])) echo $val['id']; ?>&p_id=<?php echo  $now_id.'&date='.$date.'&store='.$store.'&like='.$like.'&used='.$used.'&weather='.$weather.'&category='.$c_id.'&status='.$status_id.'&segment='.$segment_id.'&arcive='.$a_id.'&word='.$word; ?>" class="panel-a">
          <div class="panel">
            <div class="icon">
              <i class="fas fa-sync used <?php if (isUsed($_SESSION['user_id'], $val['id'])) echo 'active'; ?>"><?php echo getUsed($val['id']); ?></i>
              <i class="fas fa-heart heart <?php if (isLike($_SESSION['user_id'], $val['id'])) echo 'active'; ?>"><?php echo getLike($val['id']); ?></i>
            </div>

            <p class="date">登録日：<?php echo $val['create_date']; ?></p>
            <p class="weather">氏名：<?php echo $val['user_name']; ?></p>
            <p class="store_name">店名：<?php echo $val['store']; ?></p>
            <p class="user">場所：<?php echo $val['category_name']; ?></p>
            <p class="user">顧客状態：<?php echo $val['status_name']; ?></p>


          </div>
        </a>

      <?php endforeach; ?>

      <ul class="pageNation">
      <?php
        
        $pageColNum = 5;
        $totalPage = ceil($select['total'] / 9);
        
        if($now_id== $totalPage && $totalPage >= $pageColNum){
            
            $minPageNum = $now_id - 4;
            $maxPage = $now_id;
        }elseif($now_id == ($totalPage - 1) && $totalPage >= $pageColNum){
            
            $minPageNum = $now_id - 3;
            $maxPage = $now_id + 1;
        }elseif($now_id == 2 && $totalPage >= $pageColNum){
            $minPageNum = $now_id - 1;
            $maxPage = $now_id + 3;
        }elseif($now_id == 1 && $totalPage >= $pageColNum){
            $minPageNum = $now_id;
            $maxPage = 5;
        }elseif($totalPage < $pageColNum){
            $minPageNum = 1;
            $maxPage = $totalPage;
        }else{
            $minPageNum = $now_id - 2;
            $maxPage = $now_id + 2;
        }
        
        ?>
    <?php  if($now_id != 1): ?>
    <li class="list-item"><a href="?p_id=1<?php echo '&date='.$date.'&store='.$store.'&like='.$like.'&used='.$used.'&weather='.$weather.'&category='.$c_id.'&status='.$status_id.'&segment='.$segment_id.'&arcive='.$a_id.'&word='.$word; ?>">&lt;</a></li>
    <?php endif; ?>

    <?php  for($i = $minPageNum; $i <= $maxPage; $i ++): ?>
    <li class="list-item  <?php if($now_id == $i) echo 'active';  ?>"><a href="<?php echo '?p_id='.$i.'&date='.$date.'&store='.$store.'&like='.$like.'&used='.$used.'&weather='.$weather.'&category='.$c_id.'&status='.$status_id.'&segment='.$segment_id.'&arcive='.$a_id.'&word='.$word; ?>"><?php  echo $i ?></a></li>
    <?php  endfor; ?>

    <?php  if($now_id != $maxPage): ?>
    <li class="list-item"><a href="?p_id=<?php  echo $maxPage.'&date='.$date.'&store='.$store.'&like='.$like.'&used='.$used.'&weather='.$weather.'&category='.$c_id.'&status='.$status_id.'&segment='.$segment_id.'&arcive='.$a_id.'&word='.$word; ?>">&gt;</a></li>
    <?php endif ?>

      
    </div>
  <?php endif; ?>
  <footer class="footer">
    <ul>
      <li>©︎ohta special servise webservise</li>
      <li>autor ryou chaki</li>
    </ul>
  </footer>
</body>