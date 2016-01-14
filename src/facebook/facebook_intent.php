<?php

class TB_Facebook_Intent {
  function GenerateIntentLink() {
    $config = include("/config.php");
    $baseUrl = "http://facebook.com/dialog/feed?app_id=" . $config['app_id'];
    $extender = "";
    $redirect = "&redirect_uri=http://facebook.com";
  
    if(isset($_POST['link']) && $_POST['link'] != ""){
      $extender .= "&link=" . $_POST['link'];
    }

    if(isset($_POST['picture']) && $_POST['picture'] != ""){
      $extender .= "&picture=" . $_POST['picture'];
    }

    if(isset($_POST['name']) && $_POST['name'] != ""){
      $extender .= "&name=" . $_POST['name'];
    }

    if(isset($_POST['caption']) && $_POST['caption'] != ""){
      $extender .= "&caption=" . $_POST['caption'];
    }

    if(isset($_POST['description']) && $_POST['description'] != ""){
      $extender .= "&description=" . $_POST['description'];
    }

    if(isset($_POST['message']) && $_POST['message'] != ""){
      $extender .= "&message=" . $_POST['message'];
    }

    if(isset($_POST['redirect_uri']) && $_POST['redirect_uri'] != ""){
      $redirect = "&redirect_uri=" . $_POST['redirect_uri'];
    } 

    if($extender == ""){
      return "You must provide at least one parameter";
    } else {
      return $baseUrl . $extender . $redirect;
    }
  }
}