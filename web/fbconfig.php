<?php
session_start();
// added in v4.0.0
require_once 'autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

$helper = new FacebookRedirectLoginHelper('https://up-excel.herokuapp.com/fbconfig.php', 
  '307509092945291', '3f12b5853b84ac765c9d56ecf2e95c43');

try {
    $session = $helper->getSessionFromRedirect();
} catch(FacebookSDKException $e) {
    $session = null;
}

var_dump($session);

if ($session) {
  
  $accessToken = $session->getAccessToken();
  $longLivedAccessToken = $accessToken->extend();

} else {
  echo '<a href="' . $helper->getLoginUrl() . '">Login with Facebook</a>';
}