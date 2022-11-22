<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('classes/log.php');
include_once('../src/verifyAuth.php');


include_once __DIR__ . '/vendor/autoload.php';
/**
 * Load .env 
 * Read Base root , ... from .env
 * The  env var using in UI ,..
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


/**
 * if cookie is expired
 * redirect to 404
 * */
if(!isset($_COOKIE['authenticationToken']) || empty($_COOKIE['authenticationToken'])) {
    $url = $_ENV['PROJECT_SERVER_ADDRESS'].$_ENV['PROJECT_BASE_ROOT'].$_ENV['PROJECT_404_PAGE'];
    header("Location: $url");
    exit;
}

/**
 * Define verifyAuth class
 * Set the parameters
 * the "apiKey","isLive" can be set static or read from DB, File, ...
 * you have the "authenticationToken","ntpID" from response of start action 
 */
$verifyAuth = new VerifyAuth();
$verifyAuth->apiKey              = '51oB4pn7IRFU_o87KUfOabzNiQeLyR0zfPsEvwnUW9GovP4BqywnS1suQQ=='; // Your Api key here
$verifyAuth->authenticationToken = $_COOKIE['authenticationToken'];
$verifyAuth->ntpID               = $_COOKIE['ntpID'];
$verifyAuth->paRes               = $_POST['paRes'];
$verifyAuth->isLive              = false;


/**
 * Set params for /payment/card/verify-auth
 * @return 
 * - a Json string
 */
$jsonAuthParam = $verifyAuth->setVerifyAuth();


/**
 * Send request to /payment/card/verify-auth
 */
$paymentResult = $verifyAuth->sendRequestVerifyAuth($jsonAuthParam);
$paymentResultArr = json_decode($paymentResult);
?>
<!doctype html>
<html lang="en">
    <?php include_once("theme/inc/header.inc"); ?>
    <body class="bg-light">
        <div class="container">
            <?php include_once("theme/inc/topNav.inc"); ?>
            <div class="row">
                <?php include_once("theme/backAuthForm.php"); ?>
            </div>
        </div>
        <?php include_once("theme/inc/footer.inc"); ?>
    </body>
</html>
