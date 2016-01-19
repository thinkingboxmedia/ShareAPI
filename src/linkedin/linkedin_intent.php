<?php

class TB_LinkedIn_Intent {
  function GenerateIntentURL() {
    $baseURL = 'https://www.linkedin.com/shareArticle?mini=true';

    if(!isset($_POST['url']) || $_POST['url'] == ""){
      return "Missing required param: 'url'";
      exit;
    }

    $extend = "&url=" . $_POST['url'];

    if(isset($_POST['title']) && $_POST['title'] != "") {
      $extend .= "&title=" . $_POST['title'];
    }

    if(isset($_POST['summary']) && $_POST['summary'] != "") {
      $extend .= "&summary=" . $_POST['summary'];
    }

    if(isset($_POST['source']) && $_POST['source'] != "") {
      $extend .= "&source=" . $source;
    }

    return $baseURL . $extend;
    
  }
}