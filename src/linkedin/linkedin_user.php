<?php

class TB_LinkedIn_User {
  function GetUserInfo() {

    //Comma seperated list of fields you wish returned
    // https://developer.linkedin.com/docs/fields/basic-profile

    if(!isset($_GET['fields']) || $_GET['fields'] == "") {
      return "Missing required params";
      exit;
    }

    $config = include("/config.php");
    $li = new LinkedIn(array(
      'api_key' => $config['linkedin_client_id'],
      'api_secret' => $config['linkedin_client_secret'],
      'callback_url' => $config['domain'] . "/linkedin/login/success"
    ));

    $li->setAccessToken($_SESSION['linkedin_token']);

    try{
      $info = $li->get('/people/~:(' . $_GET['fields'] . ')');
    }
    catch (Exception $ex) {
      return $ex->getMessage();
    }
    

    return $info;
  }
}