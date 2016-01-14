<?php
require_once '/src/helpers/filters.php';

class TB_Tumblr_Post {
  function CreateTextPost(){
    $config = include("/config.php");
    $client = new Tumblr\API\Client($config['tumblr_consumer_key'], $config['tumblr_consumer_secret']);
    $client->setToken($_SESSION['tumblr_token'], $_SESSION['tumblr_secret']);

    if(!isset($_POST['message']) || $_POST['message'] == ""){
      return "Missing required params";
      exit;
    }

    if(!isset($_POST['blogName']) || $_POST['blogName'] == ""){
      return "Missing required params";
      exit;
    }

    $blogName = $_POST['blogName'];

    $data = array(
      "body"=> FilterText($_POST['message'])
    );

    if(isset($_POST['title'])){
        $data['title'] = FilterText($_POST['title']);
    }

    try {
      $client->createPost($blogName, $data);
    } catch (Exception $ex) {
      return "That blog does not exist";
    }

    return "success";
  }

  function CreatePhotoPost() {
    $config = include("/config.php");
    $client = new Tumblr\API\Client($config['tumblr_consumer_key'], $config['tumblr_consumer_secret']);
    $client->setToken($_SESSION['tumblr_token'], $_SESSION['tumblr_secret']);

    if(!isset($_POST['source']) || $_POST['source'] == ""){
      return "Missing required param: source";
      exit;
    }

    if(!isset($_POST['blogName']) || $_POST['blogName'] == ""){
      return "Missing required param: blogName";
      exit;
    }

    $blogName = $_POST['blogName'];

    $check = new TB_Tumblr_Check();
    if(!$check->VerifyImagePost($_POST['source'])){
      return "Post is not valid";
      exit;
    }

    $data = array(
      "type" => "photo",
      "source" => $_POST['source'],
    );

    if(isset($_POST['link']) && $_POST['link'] != ""){
      $data['link'] = $_POST['link'];
    }

    if(isset($_POST['caption']) && $_POST['caption'] != ""){
      $data['caption'] = FilterText($_POST['caption']);
    }

    try {
      $client->createPost($blogName, $data);
    } catch (Exception $ex) {
      return "That blog does not exist";
    }
    

    return "success";
  }

  function CreateLinkPost() {
    $config = include("/config.php");
    $client = new Tumblr\API\Client($config['tumblr_consumer_key'], $config['tumblr_consumer_secret']);
    $client->setToken($_SESSION['tumblr_token'], $_SESSION['tumblr_secret']);

    if(!isset($_POST['url']) || $_POST['url'] == ""){
      return "Missing required param: url";
      exit;
    }

    if(!isset($_POST['blogName']) || $_POST['blogName'] == ""){
      return "Missing required param: blogName";
      exit;
    }

    $blogName = $_POST['blogName'];

    $data = array(
      "type" => "link",
      "url" => $_POST['url'],
    );

    if(isset($_POST['description']) && $_POST['description'] != ""){
      $data['description'] = FilterText($_POST['description']);
    }

    if(isset($_POST['thumbnail']) && $_POST['thumbnail'] != ""){
      $data['thumbnail'] = $_POST['thumbnail'];
    }

    if(isset($_POST['author']) && $_POST['author'] != ""){
      $data['author'] = FilterText($_POST['author']);
    }

    try {
      $client->createPost($blogName, $data);
    } catch (Exception $ex) {
      return "That blog does not exist";
    }

    return "success";
  }
}