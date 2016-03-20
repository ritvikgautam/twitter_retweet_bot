<?php
require_once("twitteroauth-master/twitteroauth/twitteroauth.php");

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}

$sincethen = 0;

while(1)
{
  $twitteruser = ""; //Twitter user account here
  $notweets = 10;
  $consumerkey = ""; //Enter consumer key here
  $consumersecret = ""; //Enter secret consumer key here
  $accesstoken = ""; //Enter access token here
  $accesstokensecret = ""; //Enter secret access token here

  $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

  if($sincethen == 0)
    $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets."&include_rts=true");
  else
    $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets."&include_rts=true&since_id=".$sincethen);

  if(!$tweets)
  {
    sleep(300);
    continue;
  }

  $decode_json = json_decode(json_encode($tweets), true);

  $sincethen = $decode_json[0]["id"];

  foreach($decode_json as $i)
  {
    $retweet = $connection->post("https://api.twitter.com/1.1/statuses/retweet/".$i["id"].".json");
  }
}
?>
