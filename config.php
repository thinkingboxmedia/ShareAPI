<?php

return array(
  "domain" => "http://localhost/ShareAPI", //leave out trailing '/' (Path to this app)
  "facebook_enabled" => true,
  "twitter_enabled" => true,
  "tumblr_enabled" => true,
  "pinterest_enabled" => true,
  "enable_profanity_filter" => true, //If set to true, any profanity will be replaced with ***'s
  "cross_domain" => true,
  "requesting_domain" => "http://localhost:3000",
  "app_id" => "452484801614654",  //Facebook App ID
  "app_secret" => "f9f08583bbdff3037128a0c7a7531d3e", //Facebook Secret Key
  "facebook_permissions" => "email,user_likes,publish_actions,user_photos,user_events", //comma seperated list (1)
  "twitter_consumer_key" => "M7PF0ZXzcHbZvsQ5O2pyBECwu", //Twitter consumer key
  "twitter_consumer_secret" => "EZqh9lkHqeKWYmT18TFDfNJFw0hUhwGXCLMra7c7nQkyMrtIAS", //Twitter consumer secret key,
  "tumblr_consumer_key" => "OsuIR0LI9BqY9X6KUOxLW5oebscFst1Nt7dInTpYa0Yv3UA5th",
  "tumblr_consumer_secret" => "7BkohpSuKhfsi96ljPpi8z1L68dOGKMVoH3nDFPCoGZ1zyVRnY",
  "pinterest_app_id" => "4812795452487387075",
  "pinterest_app_secret" => "e393fe6b2912909e9c739ca4172ed64df68e656150428e320cf031b00b0df7a5"
);

//REFERENCES
// 1) https://developers.facebook.com/docs/facebook-login/permissions