<html>
<head>
    <title>Upload Excel</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <script src="public/js/bootstrap.min.js"></script>
</head>
<body>

<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
session_start();
require_once '../vendor/autoload.php';
include 'PHPExcel/PHPExcel/IOFactory.php';
require 'db.php';

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
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
if (isset($accessToken)) {
    if (isset($_SESSION['facebook_access_token'])) {
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
        $_SESSION['facebook_access_token'] = (string)$accessToken;
        $oAuth2Client = $fb->getOAuth2Client();
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    if (isset($_GET['code'])) {
        header('Location: ./');
    }
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
        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

//$profile = array('id' => 123, 'email' => 'a@b.c', 'name' => 'a');

    include 'file_upload.php';
    include 'form_file.php';
    include 'data_status.php';

} else { ?>
    <div class="container">
        <h3>Not login yet!</h3>
        <a class="btn btn-info" href="<?php echo $loginUrl; ?>">Log in with Facebook</a>
    </div>
<?php } ?>

</body>
</html>
