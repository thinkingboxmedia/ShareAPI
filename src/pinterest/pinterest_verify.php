<?php
use DirkGroenen\Pinterest\Pinterest;
class TB_Pinterest_Verify {
  function GenerateLoginLink() {
    $config = include("/config.php");
    $pinterest = new Pinterest($config['pinterest_app_id'], $config['pinterest_app_secret']);

    $callback = $config['domain'] . "/pinterest/login/success";

    $url = $pinterest->auth->getLoginUrl($callback, array('read_public'));

    return $url;
  }

  function HandleResponse() {
    if(isset($_GET['code'])) {
      $config = include("/config.php");
      $pinterest = new Pinterest($config['pinterest_app_id'], $config['pinterest_app_secret']);
      $token = $pinterest->auth->getOAuthToken($_GET["code"]);
      $_SESSION['pintrest_token'] = $token->access_token;
      
      echo "<script>window.close();</script>";
      die();
    } else {
      echo "There was an error getting your access token";
      die();
    }
  }
}