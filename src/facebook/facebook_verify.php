<?php 

class TB_Facebook_Verify {

  function HandleResponse(){
    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
    ]);

    $helper = $fb->getRedirectLoginHelper();
    try {
      $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

    if (isset($accessToken)) {
      // Logged in!
       $_SESSION['facebook_access_token'] = (string) $accessToken;
      // Now you can redirect to another page and use the
      // access token from $_SESSION['facebook_access_token']
    }
  }

  function GenerateLoginLink() {
    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
    ]);

    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email', 'user_likes', 'publish_actions', 'user_photos', 'user_events']; // optional
    $loginUrl = $helper->getLoginUrl($config['domain'] . '/facebook/login/success', $permissions);
    return $loginUrl;

    //echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
  }
  
  function GetAccessToken(){
    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
      ]);

    $helper = $fb->getCanvasHelper();

    try {
      $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

    if (! isset($accessToken)) {
      echo 'No OAuth data could be obtained from the signed request. User has not authorized your app yet.';
      exit;
    }

    // Logged in
    echo '<h3>Signed Request</h3>';
    var_dump($helper->getSignedRequest());

    echo '<h3>Access Token</h3>';
    var_dump($accessToken->getValue());
  }
}