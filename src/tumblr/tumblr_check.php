<?php

class TB_Tumblr_Check {

  function VerifyImagePost($file) {
    $config = require('./social_limits.php');

    if(strpos($file, "http://") === false){
      $fileSize = filesize($file);

      //Check if file exists
      if($filesize == FALSE){
        return false;
      }

      //Check to make sure file is small enough
      if($filesize > $config['tumblr_max_image_size']){
        return false;
      } 

    }

    $arr = explode('.', $file);

    //Make sure file type is supported
    if(!in_array(end($arr), explode(',', $config['tumblr_supported_image_types']))){
      return false;
    }

    return true;
  }
}