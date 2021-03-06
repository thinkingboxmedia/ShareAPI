<?php

class TB_Tumblr_Verify {
  function GenerateLoginLink() {
    $config = include("/config.php");
    $client = new Tumblr\API\Client($config['tumblr_consumer_key'], $config['tumblr_consumer_secret']);
    $requestHandler = $client->getRequestHandler();
    $requestHandler->setBaseUrl('https://www.tumblr.com/');

    $resp = $requestHandler->request('POST', 'oauth/request_token', array());

    $out = $result = $resp->body;
    $data = array();
    parse_str($out, $data);

    $callback = $config['domain'] . "/tumblr/login/success/";
    $_SESSION['tumblr_temp_token'] = $data['oauth_token'];
    $_SESSION['tumblr_temp_secret'] = $data['oauth_token_secret'];
    
    return 'https://www.tumblr.com/oauth/authorize?oauth_token=' . $data['oauth_token'] . "&oauth_callback=" . $callback;
    
  }

  function HandleResponse() {
    $config = include("/config.php");
    $client = new Tumblr\API\Client($config['tumblr_consumer_key'], $config['tumblr_consumer_secret']);
    $requestHandler = $client->getRequestHandler();
    $requestHandler->setBaseUrl('https://www.tumblr.com/');
    $client->setToken($_SESSION['tumblr_temp_token'],  $_SESSION['tumblr_temp_secret']);
    // exchange the verifier for the keys
    $verifier = $_GET['oauth_verifier'];

// exchange the verifier for the keys
  
    $resp = $requestHandler->request('POST', 'oauth/access_token', array('oauth_verifier' => $verifier));
    $out = $result = $resp->body;
    $data = array();
    parse_str($out, $data);

    // and print out our new keys
    $token = $data['oauth_token'];
    $secret = $data['oauth_token_secret'];
    $_SESSION['tumblr_token'] = $token;
    $_SESSION['tumblr_secret'] = $secret;

    echo "<script>window.close();</script>";
    die();
  }
  

}