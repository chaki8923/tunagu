<?php

require('function.php');
require('head.php');
require('auth.php');

$_SESSION['newsLike'] = 0;

$getUser = getUser($_SESSION['user_id']);
debug('ユーザーID' . $_SESSION['user_id'], true);

$getNewsLike = newsLike($_SESSION['user_id']);
debug('newsLike中身' . print_r($getNewsLike, true));


?>

<body>
<div id="loading">
  <img src="img/ohta-load.gif" alt="" class="load-ohta">
</div>
  <?php require('header.php'); ?>
  <main class="news-container">
  <div class="news-inner">
      <?php foreach ($getNewsLike as $key => $val) : ?>
        <a class="news-panel" href="caseDetail.php?c_id=<?php if (!empty($val['id'])) echo $val['id']; ?>">
          <div class="news-label">
            <p><?php echo $val['title'] ?></p>
            <div>
              <i class="fas fa-2x fa-heart  heart active"></i><span class="like_num beta"><?php echo $val['like_number']; ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
 
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