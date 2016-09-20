<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
require_once '../vendor/autoload.php';

$app_id = getenv('APP_ID');
$app_secret = getenv('APP_SECRET');
$fb = new Facebook\Facebook([
    'app_id' => $app_id ? $app_id : '307509092945291',
    'app_secret' => $app_secret ? $app_secret : '3f12b5853b84ac765c9d56ecf2e95c43',
    'default_graph_version' => 'v2.4',
]);
$helper = $fb->getRedirectLoginHelper();

try {
    if (isset($_SESSION['facebook_access_token'])) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
if (isset($accessToken)) {
    if (isset($_SESSION['facebook_access_token'])) {
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
        // getting short-lived access token
        $_SESSION['facebook_access_token'] = (string)$accessToken;
        // OAuth 2.0 client handler
        $oAuth2Client = $fb->getOAuth2Client();
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;
        // setting default access token to be used in script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    // redirect the user back to the same page if it has "code" GET variable
    if (isset($_GET['code'])) {
        header('Location: ./');
    }
    // getting basic info about user
    try {
        $profile_request = $fb->get('/me?fields=id,name,first_name,last_name,email');
        $profile = $profile_request->getGraphNode()->asArray();

        $PROFILE_ID = 'id';
        $PROFILE_EMAIL = 'email';
        $PROFILE_NAME = 'name';
        if (isset($profile[$PROFILE_ID]) && isset($profile['email']) && isset($profile['name'])) {
            require_once 'db.php';
            $db = connect();
            if ($db) {
                insertUser($db, $profile[$PROFILE_ID], $profile[$PROFILE_EMAIL], $profile[$PROFILE_NAME]);
                $db->close();
            }
        }
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        // redirecting user back to app login page
        header("Location: ./");
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    // printing $profile array on the screen which holds the basic info about user
//    print_r($profile);
    // Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
//    echo '<br/>';
//    echo '<a href="logout.php">Logout</a>';
    include 'file_upload.php';
    include 'form_file.php';
    include 'data_status.php';
} else {
    // replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
    $permissions = ['email']; // optional
    $loginUrl = $helper->getLoginUrl('http://up-excel.herokuapp.com/index.php', $permissions);
    echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}