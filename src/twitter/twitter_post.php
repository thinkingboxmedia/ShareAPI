<?php
require_once '/src/helpers/filters.php';
use Abraham\TwitterOAuth\TwitterOAuth;

class TB_Twitter_Post {
  function PostTweet() {
    if(!isset($_POST['message'])){
      return "Missing required params";
      exit;
    }

    $final = FilterText($_POST['message']);

    $check = new TB_Twitter_Check();
    if(!$check->VerifyTweet($final)){
      return "Tweet is not valid";
      exit;
    }
    

    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $user = $connection->post("statuses/update", array("status" => $final));
    if($connection->getLastHttpCode() == 200){
      return "success";
    } else {
      return "An unnknown error occured";
    }
  }

  function PostImage() {

    if(!isset($_POST['source']) || $_POST['source'] == ""){
      return "Missing required params";
    }
    $config = include("/config.php");
    $source = $_POST['source'];
    $final = "";
    if(isset($_POST['message'])){
      $orig = $_POST['message'];
      $final = FilterText($orig);
      
    }

    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

    $check = new TB_Twitter_Check();
    if(!$check->VerifyTweet($source, $final)){
      return "Tweet is not valid";
      exit;
    }


    $media1 = $connection->upload('media/upload', array('media' => $source));
    $parameters = array(
        'status' => $final,
        'media_ids' => implode(',', array($media1->media_id_string)),
    );
    $result = $connection->post('statuses/update', $parameters);
    if($connection->getLastHttpCode() == 200){
      return "success";
    } else {
      return "An unnknown error occured";
    }

  }
} 