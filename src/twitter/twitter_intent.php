<?php

class TB_Twitter_Intent {

  function GenerateTweetIntent() {

    if(!isset($_POST['message'])){
      return "Missing required params";
      exit;
    }

    $message = FilterText($_POST['message']);

    $check = new TB_Twitter_Check();
    if(!$check->VerifyTweet($message)){
      return "Tweet is not valid";
      exit;
    }

    $url = "https://twitter.com/intent/tweet?text=" . $message;

    if(isset($_POST['url']) && $_POST['url'] != ""){
      $url .= "&url=" . $_POST['url'];
    }

    if(isset($_POST['hashtags']) && $_POST['hashtags'] != ""){
      $url .= "&hashtags=" . $_POST['hashtags'];
    }

    if(isset($_POST['via']) && $_POST['via'] != ""){
      $url .= "&via=" . $_POST['via'];
    }

    return $url;
  }

  function GenerateRetweetIntent() {
    if(!isset($_POST['tweet_id'])){
      return "Missing required params";
      exit;
    }

    $url = "https://twitter.com/intent/retweet?tweet_id=" . $_POST['tweet_id'];
    return $url;
  }
}