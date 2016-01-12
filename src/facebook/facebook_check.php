<?php

class TB_Facebook_Check {
  function VerifyImagePost($file) {
    $config = require('./social_limits.php');

    if(strpos($file, "http://") === false){
      $fileSize = filesize($file);
      $image = getimagesize($file);

      if($image['width'] < $config['facebook_min_image_width']){
        return false;
      }

      if($image['height'] < $config['facebook_min_image_height']){
        return false;
      }

      //Check if file exists
      if($filesize == FALSE){
        return false;
      }

      //Check to make sure file is small enough
      if($filesize > $config['facebook_max_image_size']){
        return false;
      }

    }
  
    $arr = explode('.', $file);
    
    //Make sure file type is supported
    if(!in_array(end($arr), explode(',', $config['facebook_supported_image_types']))){
      return false;
    }

    return true;
  }

}