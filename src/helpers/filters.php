<?php
use Snipe\BanBuilder\CensorWords;

function FilterText($input){
  $config = include("/config.php");
  $output = "";

  if($config['enable_profanity_filter']){
    $censor = new CensorWords;
    $new = $censor->censorString($input);
    return $new['clean'];
  } else {
    return $input;
  }
}

