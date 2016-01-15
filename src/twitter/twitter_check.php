<?php

class TB_Twitter_Check {

  function VerifyTweet($msg, $withImage = false){
    $config = require('./social_limits.php');
    $length = strlen($msg);

    //Check that tweet is short enough
    if($withImage){
      if($length > $config['twitter_character_limit_with_image']){
        return false;       
      }
    } else{
      if($length > $config['twitter_character_limit']){
        return false;       
      }
    }
   
    return true;
  }

  function VerifyImage($file, $msg){
    $config = require('./social_limits.php');

    if(strpos($file, "http://") === false){

      $fileSize = filesize($file);

      //Check if file exists
      if($filesize == FALSE){
        return false;
      }

      //Check to make sure file is small enough
      if($filesize > $config['twitter_max_image_size']){
        return false;
      } 

    }

    $arr = explode('.', $file);

    //Make sure file type is supported
    if(!in_array(end($arr), explode(',', $config['twitter_supported_image_types']))){
      return false;
    }

    //Check tweet contents
    if(!VerifyTweet($msg, true)){
      return false;
    }

    //return true if made it here
    return true;
  }
}