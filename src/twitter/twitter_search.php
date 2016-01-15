<?php
use Abraham\TwitterOAuth\TwitterOAuth;
class TB_Twitter_Search {
  function SearchTweets() {

    // References -  https://dev.twitter.com/rest/reference/get/search/tweets
    //               https://dev.twitter.com/rest/public/search


    if(!isset($_GET['search'])){
      return "Missing required params";
    }

    $searchTerms = $_GET['search'];

    $type = "mixed";
    $count = "15";
    $geo = "";
    $since = "";

    if(isset($_GET['type']) && $_GET['type'] != ""){
      $type = $_GET['type'];
    }

    if(isset($_GET['count']) && $_GET['count'] != ""){
      $count = $_GET['count'];
    }

    if(isset($_GET['geo']) && $_GET['geo'] != ""){
      $geo = $_GET['geo'];
    }

    if(isset($_GET['since']) && $_GET['since'] != ""){
      $since = $_GET['since'];
    }

    $requestData = array();
    $requestData['q'] = $searchTerms;
    $requestData['count'] = $count;
    if($geo != "")
      $requestData['geocode'] = $geo;

    if($since != "")
      $requestData['since_id'] = $since;

    $requestData['result_type'] = $type;

    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $result = $connection->get("search/tweets", $requestData);
    if($connection->getLastHttpCode() == 200){
      return $result;
    } else {
      return "An unnknown error occured";
    }
  }
}