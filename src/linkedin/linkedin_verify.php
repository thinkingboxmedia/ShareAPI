<?php
require 'LinkedIn.php';

class TB_LinkedIn_Verify {

  function GenerateLoginURL() {
    $config = include("/config.php");
    $li = new LinkedIn(array(
      'api_key' => $config['linkedin_client_id'],
      'api_secret' => $config['linkedin_client_secret'],
      'callback_url' => $config['domain'] . "/linkedin/login/success"
    ));

    $url = $li->getLoginUrl(
      array(
        LinkedIn::SCOPE_BASIC_PROFILE, 
        LinkedIn::SCOPE_EMAIL_ADDRESS, 
        'w_share'
      )
    );

    $_SESSION['linkedin_state'] = $li->getState();

    return $url;

  }

  function HandleResponse() {
    $config = include("/config.php");
    $li = new LinkedIn(array(
      'api_key' => $config['linkedin_client_id'],
      'api_secret' => $config['linkedin_client_secret'],
      'callback_url' => $config['domain'] . "/linkedin/login/success"
    ));

    $token = $li->getAccessToken($_REQUEST['code']);
    $_SESSION['linkedin_token'] = $token;


    echo "<script>window.close();</script>";
    die();
  }
}