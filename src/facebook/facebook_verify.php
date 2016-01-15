<?php 
class TB_Facebook_Verify {

  function HandleResponse(){
    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
    ]);

    //echo $_SESSION['FBRLH_state'];
    //$_GET['state'] = $_SESSION['FBRLH_state'];

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

        echo "<script>window.close();</script>";
        die();
       }

      // Now you can redirect to another page and use the
      // access token from $_SESSION['facebook_access_token']
    }
  

  function GenerateLoginLink() {
    $config = include("/config.php");
    $fb = new Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.4',
    ]);


    $helper = $fb->getRedirectLoginHelper();
    $permissions = array();

    foreach (explode(',', $config['facebook_permissions']) as $v) {
        array_push($permissions, $v);
    }
   
    $loginUrl = $helper->getLoginUrl($config['domain'] . '/facebook/login/success', $permissions);

    return $loginUrl;

    //echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
  }
}
  
