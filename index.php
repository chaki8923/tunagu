<?php

$site_title = 'ホーム';
require('function.php');
require('head.php');
require('auth.php');


$getUser = getUser($_SESSION['user_id']);
debug('ユーザーID'.$_SESSION['user_id'],true);

?>
<body>
<div id="loading">
  <img src="img/ohta-load.gif" alt="" class="load-ohta">
</div>
  <?php require('header.php'); ?>
  
  <main>
    <div class="container">
      <div class="help">
        <h1 class="help-title">『当サービス』は組織エンゲージメントを<br>高める為のオータ専用プラットホーム </h1>
      </div>
     
      <h2 class="container-slider-title">Service Star's　歴代接客スター</h2>
      <div class="pc-slider">
        <div class="container-slider-img">
          <div class="img-frame">
            <img src="img/arai_resize.JPG" alt="" class="container-slider-inner">
            <p>荒井　梨紗</p>
          </div>
          <div class="img-frame">
            <img src="img/simazaki_resize.png" alt="" class="container-slider-inner">
            <p>島崎　奈美</p>
          </div>
          <div class="img-frame">
            <img src="img/ikeda_resize.jpg" alt="" class="container-slider-inner">
            <p>池田　梨乃</p>
          </div>
          <div class="img-frame">
            <img src="img/isizawa_resize.png" alt="" class="container-slider-inner">
            <p>石澤　優輝</p>
          </div>
          <div class="img-frame">
            <img src="img/maehara_resize.JPG" alt="" class="container-slider-inner">
            <p>前原　裕美</p>
          </div>
          <div class="img-frame">
            <img src="img/nakati_resize.JPG" alt="" class="container-slider-inner">
            <p>中地　瑞貴</p>
          </div>
          <div class="img-frame">
            <img src="img/onodera_resize.jpg" alt="" class="container-slider-inner">
            <p>小野寺　和人</p>
          </div>
          <div class="img-frame">
            <img src="img/nabe_resize.jpg" alt="" class="container-slider-inner">
            <p>渡辺　美咲</p>
          </div>
          
         
          
        </div>
      </div>
      <div class="sp-slider">
        <div class="container-slider-sp">
          <div class="img-frame">
            <img src="img/lady1.jpg" alt="" class="container-slider-inner">
            <p>島崎　和歌子</p>
          </div>
          <div class="img-frame">
            <img src="img/lady2.jpg" alt="" class="container-slider-inner">
            <p>島崎　和歌子</p>
          </div>
          <div class="img-frame">
            <img src="img/lady3.jpg" alt="" class="container-slider-inner">
            <p>島崎　和歌子</p>
          </div>
          <div class="img-frame">
            <img src="img/lady4.jpg" alt="" class="container-slider-inner">
            <p>島崎　和歌子</p>
          </div>
          <div class="img-frame">
            <img src="img/lady6.jpg" alt="" class="container-slider-inner">
            <p>島崎　和歌子</p>
          </div>
          <div class="img-frame">
            <img src="img/lady7.jpg" alt="" class="container-slider-inner">
            <p>島崎　和歌子</p>
          </div>
          <div class="img-frame">
            <img src="img/lady8.jpg" alt="" class="container-slider-inner">
            <p>島崎　和歌子</p>
          </div>    
        </div>
      </div>
      
    </div>
   
  </main>
  <footer class="footer">
    <ul>
      <li>©︎ohta special servise webservise</li>
      <li>autor ryou chaki</li>
    </ul>
  </footer>
</body>
</html>