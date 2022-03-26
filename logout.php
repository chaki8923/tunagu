<?php

require('function.php');



debug('ログアウト画面です。');


session_destroy();


debug('ログイン画面に行きます。');

header("Location:login.php");

?>