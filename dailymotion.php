<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed


if(isset($_SERVER['HTTPS'])){
    $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
}
else{
    $protocol = 'http';
}
$baseurl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


$dlymtn_key = '';
$dlymtn_secret = '';
$dlymtn_acces = '';
$dlymtn_access_token = '';
$dlymtn_username = '';
$dlymtn_password = '';

$dm = new Dailymotion();

if (isset($_REQUEST['oauth_verifier'])) {
	$twitter = new TwitterOAuth($twttr_key, $twttr_secret_key, $_SESSION['twitter_oauth_token'], $_SESSION['twitter_oauth_token_secret'] );
} else if( $twttr_token!= '' ){
	$tw_access_token = json_decode($twttr_token,true);
	$twitter = new TwitterOAuth($twttr_key, $twttr_secret_key, $tw_access_token['oauth_token'], $tw_access_token['oauth_token_secret'] );
} else {

	$twitter = new TwitterOAuth($twttr_key, $twttr_secret_key);
}

if($_GET['auth-status'] == 'success' &&  $_GET['auth-from'] == 'dailymotion'){
	echo 'Twitter successfully!';
} 
elseif ($segments[1] == 'dailymotion') {
	$dm->setGrantType(Dailymotion::GRANT_TYPE_AUTHORIZATION, $dlymtn_key, $dlymtn_secret);

		try {
			$dm->getAccessToken();
			 header('Location: '.$baseurl.'networks/?auth-status=success&auth-from=dailymotion');	

		}catch(DailymotionAuthRequiredException $e){
			 header('Location: ' . $dm->getAuthorizationUrl());

		}catch (DailymotionAuthRefusedException $e){
		    // Handle the situation when the user refused to authorize and came back here.
		    // <YOUR CODE>
		}
}

else{
	$dailymotion_login = $baseurl."dailymotion";
	header('Location: '.$dailymotion_login);
}






