<?php

require 'vendor/autoload.php';
require "dbh.inc.php";

$clientID = '640759964072-psg30h5snvv6klr38i6v15rafrh69bcv.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/MedPulse-main/google.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        echo "Error fetching token: " . htmlspecialchars($token['error']);
        exit;
    }

    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();

    $_SESSION['fname'] = htmlspecialchars($google_account_info->given_name);
    $_SESSION['lname'] = htmlspecialchars($google_account_info->family_name);
    $_SESSION['email_address'] = htmlspecialchars($google_account_info->email);


}

else {
    // Show Google Login link with button styling
    header('Location: ' . $client->createAuthUrl());
}