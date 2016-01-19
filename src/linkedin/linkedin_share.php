<?php

class TB_LinkedIn_Share {
  function Share() {
    $config = include("/config.php");
    $li = new LinkedIn(array(
      'api_key' => $config['linkedin_client_id'],
      'api_secret' => $config['linkedin_client_secret'],
      'callback_url' => $config['domain'] . "/linkedin/login/success"
    ));

    $li->setAccessToken($_SESSION['linkedin_token']);
    $li->setState($_SESSION['linkedin_state']);
    $hasParams = false;

    
    $comment = "";
    $title = "";
    $description = "";
    $url = "";
    $source = "";
    $visiblity = "";

    if(isset($_POST['title']) && $_POST['title'] != ""){
      $hasParams = true;
      $title = $_POST['title'];
    }

    if(isset($_POST['description']) && $_POST['description'] != ""){
      $hasParams = true;
      $description = $_POST['description'];
    }

    if(isset($_POST['url']) && $_POST['url'] != ""){
      $hasParams = true;
      $url = $_POST['url'];
    }

    if(isset($_POST['source']) && $_POST['source'] != ""){
      $hasParams = true;
      $source = $_POST['source'];
    }

    if(isset($_POST['comment']) && $_POST['comment'] != ""){
      $comment = $_POST['comment'];
    }

    if(isset($_POST['visiblity']) && $_POST['visiblity'] != "") {
      $visiblity = $_POST['visiblity'];
    } else {
      $visiblity = "anyone";
    }

    if(!$hasParams) {
      return "Missing params";
      exit;
    }

   $post = array(
      'comment' => $comment,
      'content' => array(
      'title' => $title,
      'description' => $description, //Maxlen(255)
      'submitted_url' => $url,
      'submitted_image_url' => $source,
      ),
        'visibility' => array(
        'code' => $visiblity
      )
      );
    //echo $li->getAccessToken();
    

    try{
      $li->post('/people/~/shares', $post);
    }
    catch (Exception $ex){
      return $ex->getMessage();
    }

    return 'success';
    

  }
}