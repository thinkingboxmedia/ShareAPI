<?php
require_once '/src/helpers/filters.php';

class TB_Facebook_Post {

  function PostStatus(){

    if(!isset($_POST['message']) || $_POST['message'] == ""){
      return "Missing required param";
      exit;
    }

    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
    ]);

    $linkData = [
      'message' => FilterText($_POST['message']),
      ];

    try {
      // Returns a `Facebook\FacebookResponse` object
      $response = $fb->post('/me/feed', $linkData, $_SESSION['facebook_access_token'] );
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      return 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      return 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

    $graphNode = $response->getGraphNode();

    return 'success';
  }
  
  function PostLink(){

    if(!isset($_POST['link']) || $_POST['link'] == ""){
      return "Missing required param";
      exit;
    }

    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
    ]);

    $linkData = [
      'link' => $_POST['link'],
      ];

    if(isset($_POST['message']) && $_POST['message'] != ""){
        $linkData['message'] = FilterText($_POST['message']);
    }

    try {
      // Returns a `Facebook\FacebookResponse` object
      $response = $fb->post('/me/feed', $linkData, $_SESSION['facebook_access_token'] );
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      return 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      return 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

    $graphNode = $response->getGraphNode();

    return 'success';
  }

  function PostPhoto(){
    if(!isset($_POST['source']) || $_POST['source'] == ""){
      return "Missing required param";
      exit;
    }

    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
    ]);

    $check = new TB_Facebook_Check();
    if(!$check->VerifyImagePost($_POST['source'])){
      return "Post is not valid";
    }

    $data = [
      'source' => $fb->fileToUpload($_POST['source']),
    ];

    if(isset($_POST['message']) && $_POST['message'] != ""){
        $data['message'] = FilterText($_POST['message']);
    }


    try {
      // Returns a `Facebook\FacebookResponse` object
      $response = $fb->post('/me/photos', $data, $_SESSION['facebook_access_token']);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      return 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      return 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

    return 'success';
  }

  function PostVideo(){
    if(!isset($_POST['source']) ||  $_POST['source'] == ""){
      return "Missing required param";
      exit;
    }

    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
    ]);

    $data = [
      'source' => $fb->videoToUpload($_POST['source']),
    ];

    if(isset($_POST['description']) && $_POST['description'] != ""){
        $data['description'] = FilterText($_POST['description']);
    }

    if(isset($_POST['title']) && $_POST['title'] != ""){
        $data['title'] = FilterText($_POST['title']);
    }

    try {
      $response = $fb->post('/me/videos', $data, $_SESSION['facebook_access_token']);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

    return 'success';
  }
}