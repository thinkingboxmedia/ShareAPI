<?php
use DirkGroenen\Pinterest\Pinterest;
class TB_Pinterest_User {
  function GetUserBoards() {
    $config = include("/config.php");
    $pinterest = new Pinterest($config['pinterest_app_id'], $config['pinterest_app_secret']);
    $pinterest->auth->setOAuthToken($_SESSION['pintrest_token']);

    $r = $pinterest->users->getMeBoards();
    echo $r;
  }
}