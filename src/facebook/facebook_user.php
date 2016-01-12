<?php

class TB_Facebook_User {

  function GetUserInfo() {
    $fields = "";
    $o = null;
    $n = null;
    if(isset($_GET['fields'])){
      $o = $_GET['fields'] . ",id";
      $n = explode(',', $o);


      foreach ($n as $field) {
        $fields .= $field . ",";
      }

      //remove last comma
      $fields = rtrim($fields, ",");

    } else {
      return "Missing required param";
      exit;
    }

    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
    ]);


    try {
        $response = $fb->get('/me?fields=' . $fields, $_SESSION['facebook_access_token'] );
      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        return 'Graph returned an error: ' . $e->getMessage();
        exit;
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        return 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }

     $user = $response->getGraphUser();

     return $user;

     //return $return;
  }
}