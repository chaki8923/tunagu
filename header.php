<?php $allUsers = getAllUsers(); ?>
<header class="header <?php if (basename($_SERVER['PHP_SELF']) == 'profEdit.php' || basename($_SERVER['PHP_SELF']) == 'caseEdit.php') {
                        echo 'edit-color';
                      } elseif (basename($_SERVER['PHP_SELF']) == 'caseList.php' || basename($_SERVER['PHP_SELF']) == 'caseDetail.php' || basename($_SERVER['PHP_SELF']) == 'login.php' || basename($_SERVER['PHP_SELF']) == 'newsUse.php' || basename($_SERVER['PHP_SELF']) == 'newLike.php')  echo 'list-header';  ?>">

  <ul class="header-ul">
    <span class="get-users">WEBサービス参加人数: <?php echo $allUsers; ?> 人</span>
    <li class="header-list"><a href="index.php" class="header-list-inner">ホーム</a></li>
    <li class="header-list"><a href="login.php" class="header-list-inner">ログイン</a></li>
    <li class="header-list"><a href="caseList.php" class="header-list-inner">エピソード一覧へ</a></li>
    <li class="header-list"><a href="profEdit.php" class="header-list-inner">プロフィール編集</a></li>
    <li class="header-list"><a href="logout.php" class="header-list-inner">ログアウト</a></li>
    <li class="header-list"><a href="caseEdit.php" class="header-list-inner">新規登録</a></li>
  </ul>

</header>

  <div class="bar">
    <span class="bar-top"></span>
    <span class="bar-middle"></span>
    <span class="bar-bottom"></span>
  </div>
  <?php if(basename($_SERVER['PHP_SELF']) === 'caseList.php'): ?>
  <div class="search-fix">
   <img src="img/search.png" alt="">
  </div>
  <?php endif; ?>
  <div class="sp-header">
  <li class="header-list"><a href="index.php" class="header-list-inner">ホーム</a></li>
    <li class="header-list"><a href="login.php" class="header-list-inner">ログイン</a></li>
    <li class="header-list"><a href="caseList.php" class="header-list-inner">エピソード一覧へ</a></li>
    <li class="header-list"><a href="profEdit.php" class="header-list-inner">プロフィール編集</a></li>
    <li class="header-list"><a href="logout.php" class="header-list-inner">ログアウト</a></li>
    <li class="header-list"><a href="signup.php" class="header-list-inner">新規登録</a></li>
  </div>