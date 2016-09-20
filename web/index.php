<?php
require_once '../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$fb = new \Facebook\Facebook([
  'app_id' => '307509092945291',
  'app_secret' => '3f12b5853b84ac765c9d56ecf2e95c43',
  'default_graph_version' => 'v2.7'
]);

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();

$helper = $fb->getRedirectLoginHelper();

