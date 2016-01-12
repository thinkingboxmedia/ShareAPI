<?php

class TB_Tumblr_User {
  function GetUserBlogs() {
    $config = include("/config.php");
    $client = new Tumblr\API\Client($config['tumblr_consumer_key'], $config['tumblr_consumer_secret']);
    $client->setToken($_SESSION['tumblr_token'], $_SESSION['tumblr_secret']);
    $r = array();

    foreach ($client->getUserInfo()->user->blogs as $blog) {
      array_push($r, $blog->name);
    }

    return $r;

  }
}
