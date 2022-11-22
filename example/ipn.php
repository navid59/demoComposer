<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('classes/log.php');
include_once('../src/ipn.php');

require_once 'vendor/autoload.php';

/**
 * Load .env 
 * To read Logo , ... from .env
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Log
if($_ENV['DEBUGGING_MODE']){
    $setRealTimeLog = ["IPN"    =>  "IPN Is hitting"];
    log::setRealTimeLog($setRealTimeLog);
    log::logHeader();
}


/**
 * get defined keys
 */
$ntpIpn = new IPN();

$ntpIpn->activeKey         = 'AAAA-BBBB-CCCC-DDDD-EEEE';        // activeKey or posSignature
$ntpIpn->posSignatureSet[] = 'AAAA-BBBB-CCCC-DDDD-EEEE';        // The active key should be in posSignatureSet as well
$ntpIpn->posSignatureSet[] = 'EEEE-AAAA-BBBB-CCCC-DDDD';
$ntpIpn->posSignatureSet[] = 'FFFF-DDDD-AAAA-BBBB-CCCC'; 
$ntpIpn->posSignatureSet[] = 'DDDD-FFFF-EEEE-AAAA-BBBB'; 
$ntpIpn->posSignatureSet[] = 'FFFF-GGGG-HHHH-EEEE-AAAA';
$ntpIpn->hashMethod        = 'SHA512';
$ntpIpn->alg               = 'RS512';

$ntpIpn->publicKeyStr = "-----BEGIN PUBLIC KEY-----\nYOUR-PUBLIC-KEY-YOUR-PUBLIC-KEY\n-----END PUBLIC KEY-----\n";

$ipnResponse = $ntpIpn->verifyIPN();


/**
 * IPN Output
 */
echo json_encode($ipnResponse);
