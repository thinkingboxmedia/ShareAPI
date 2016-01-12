<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'src/social_media.php';
use Abraham\TwitterOAuth\TwitterOAuth;
use Snipe\BanBuilder\CensorWords;
session_start();
$config = include("/config.php");


////////////////BASIC SECURITY CHECK/////////////////
$headers = getallheaders();
$requester = $headers['Host'];

$u = str_replace("http://", "", $config['domain']);
$url = explode("/", $u)[0];
if($requester != $url && !$config['allow_external_requests']){
  generateErrorResponse("This API cannot be accessed from an external address");
}
//////////////////////////////////////////////////////////


//////////////////////ROUTING///////////////////////
$base_url = getCurrentUri();
$params = array();
$routes = explode('/', $base_url);
foreach($routes as $route){
  if(trim($route) != '')
    array_push($params, $route);
}
/////////////////////////////////////////////////////



////////////////////////ACTION - FACEBOOK////////////////////////

if($params[0] == "facebook"){

    if(!$config['facebook_enabled'] || !isset($config['app_id']) ||
       !isset($config['app_secret']) || $config['app_id'] == "" || $config['app_secret'] == ""){
        generateErrorResponse("Facebook is not enabled, or is missing params");
    }

    if($params[1] == "login"){
      $fb = new TB_Facebook_Verify();

      if(isset($params[2]) && $params[2] == "success"){ //Response from facebook
        $fb->HandleResponse();
      } else { //Just Login

        $url = $fb->GenerateLoginLink();
        if(isset($_GET['auto_redirect']) && $_GET['auto_redirect'] == true){
          header('location: ' . $url);
          generateSuccessResponse("");
          die();
        } else {
          generateSuccessResponse($url);
        }
        
      }
    } 
    else if($params[1] == 'post'){
       $fb = new TB_Facebook_Post();

      if(!isset($params[2])) {  die();  }

      if($params[2] == 'link'){
        $response = $fb->PostLink();
        if($response == 'success'){
          generateSuccessResponse("Sucessfully posted link");
        } else {
          generateErrorResponse($response);
        }
      } else if($params[2] == 'status'){
        $response = $fb->PostStatus();
        if($response == 'success'){
          generateSuccessResponse("Sucessfully posted status");
        } else {
          generateErrorResponse($response);
        }
      } else if($params[2] == 'photo'){
        $response = $fb->PostPhoto();
        if($response == 'success'){
          generateSuccessResponse("Sucessfully posted photo");
        } else {
          generateErrorResponse($response);
        }
      } else if($params[2] == 'video'){
        $response = $fb->PostVideo();
        if($response == 'success'){
          generateSuccessResponse("Sucessfully posted video");
        } else {
          generateErrorResponse($response);
        }
      }
      
    }
    else if($params[1] == 'user'){
      $fb = new TB_Facebook_User();
      $user = $fb->GetUserInfo();
      if(!is_string($user)){
        generateSuccessResponse(utf8ize($user));
      } else {
        generateErrorResponse($user);
      }
      
    }
    else if($params[1] == 'check'){
      if(isset($params[2]) && $params[2] == 'access_code'){
        if(isset($_SESSION['facebook_access_token'])){
          generateSuccessResponse("An access token is set for this session");
        } else {
          generateErrorResponse("No access token for this session");
        }
        
      }
    }
}

/////////////////////////////////////////////////////



///////////////////////ACTION - TWITTER////////////////////////

if($params[0] == 'twitter'){
  if(!$config['twitter_enabled'] || !isset($config['twitter_consumer_key']) ||
     !isset($config['twitter_consumer_secret']) || $config['twitter_consumer_key'] == "" || $config['twitter_consumer_secret'] == ""){
      generateErrorResponse("Twitter is not enabled, or is missing params");
  }

  define('CONSUMER_KEY', $config['twitter_consumer_key']);
  define('CONSUMER_SECRET', $config['twitter_consumer_secret']);
  define('OAUTH_CALLBACK', $config['domain'] . "/twitter/login/success");

  if(isset($params[1])){
    if($params[1] == 'login'){
      $tw = new TB_Twitter_Login();
      if(isset($params[2]) && $params[2] == 'success'){
        $resp = $tw->HandleResponse();
        if($resp == "success"){
          generateSuccessResponse("User has signed in");
        } else {
          generateErrorResponse($resp);
        }
      } else {
        $url = $tw->GenerateLoginLink();
        if($url != "error"){
          if(isset($_GET['auto_redirect']) && $_GET['auto_redirect'] == true){
            header('location: ' . $url);
            generateSuccessResponse("");
          } else {
            generateSuccessResponse($url);
          }
        } else {
          generateErrorResponse("An error occured generating a login link");
        }
        
      }
    } else if($params[1] == "post"){
      if(isset($params[2])){
        if($params[2] == "tweet"){
          $tw = new TB_Twitter_Post();
          $r = $tw->PostTweet();
          if($r == 'success'){
            generateSuccessResponse("Successfully posted tweet");
          } else {
            generateErrorResponse($r);
          }
        } else if($params[2] == "image"){
          $tw = new TB_Twitter_Post();
          $r = $tw->PostImage();
          if($r == 'success'){
            generateSuccessResponse("Successfully posted photo");
          } else {
            generateErrorResponse($r);
          }
        }
      }
    } else if($params[1] == 'search'){
      $tw = new TB_Twitter_Search();
      $r = $tw->SearchTweets();
      if(!is_string($r)){
        generateSuccessResponse(utf8ize($r));
      } else {
        generateErrorResponse($r);
      }
    }
  }
} 

/////////////////////////////////////////////////////



///////////////////////ACTION - TUMBLR////////////////////////

if($params[0] == 'tumblr'){
  if(!$config['tumblr_enabled'] || !isset($config['tumblr_consumer_key']) ||
    !isset($config['tumblr_consumer_secret']) || $config['tumblr_consumer_key'] == "" || $config['tumblr_consumer_secret'] == ""){
    generateErrorResponse("Tumblr is not enabled, or is missing params");
  }

  if(isset($params[1])){
    if($params[1] == "login"){
      $t = new TB_Tumblr_Verify();
      if(isset($params[2])){
        if($params[2] == "success"){
          $r = $t->HandleResponse();
          if($r == "success"){
              generateSuccessResponse("User has logged in");
          } else {
              generateErrorResponse($r);
          }
        } 
      } else {
        $r = $t->GenerateLoginLink();
        if(isset($_GET['auto_redirect']) && $_GET['auto_redirect'] == true){
          header('location: ' . $r);
          die();
        } else {
          generateSuccessResponse($r);
        }
      }
    } else if($params[1] == 'post'){
      if(isset($params[2])){
        $t = new TB_Tumblr_Post();
        if($params[2] == 'text'){
          $r = $t->CreateTextPost();
          if($r == "success"){
            generateSuccessResponse("Successfully posted to tumblr");
          } else {
            generateErrorResponse($r);
          }
        } else if($params[2] == 'photo'){
          $r = $t->CreatePhotoPost();
          if($r == "success"){
            generateSuccessResponse("Successfully posted to tumblr");
          } else {
            generateErrorResponse($r);
          }
        } else if($params[2] == 'link'){
          $r = $t->CreateLinkPost();
          if($r == "success"){
            generateSuccessResponse("Successfully posted to tumblr");
          } else {
            generateErrorResponse($r);
          }
        }
      }
    } else if($params[1] == 'user'){
      if(isset($params[2])){
        $t = new TB_Tumblr_User();
        if($params[2] == 'blogs'){
          $r = $t->GetUserBlogs();
          if(!is_string($r)){
            generateSuccessResponse($r);
          } else {
            generateErrorResponse("Cannot get user blogs");
          }
        }
      }
    }
  }
}

/////////////////////////////////////////////////////


////////////////////////INDEX HELPER FUNCTIONS////////////////////////

function getCurrentUri(){
  $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
  $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
  if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
  $uri = '/' . trim($uri, '/');
  return $uri;
}

function utf8ize($d) {
    if (is_array($d)) 
        foreach ($d as $k => $v) 
            $d[$k] = utf8ize($v);

     else if(is_object($d))
        foreach ($d as $k => $v) 
            $d->$k = utf8ize($v);

     else 
        return utf8_encode($d);

    return $d;
}

function generateSuccessResponse($response){
  $response = array(
    "status" => "success",
    "response" => $response
  );

  echo json_encode($response);
  die();
}

function generateErrorResponse($response){
  $response = array(
    "status" => "error",
    "response" => $response
  );

  echo json_encode($response);
  die();
}
///////////////////////////////////////////////////////////////

 
