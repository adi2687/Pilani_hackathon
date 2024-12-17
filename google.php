<?php
session_start();
require 'vendor/autoload.php';
require "dbh.inc.php"; // Ensure your database connection is correct

// Clear any existing session
session_unset();
session_destroy();
session_start();

// Init Google OAuth configuration
$clientID = '640759964072-psg30h5snvv6klr38i6v15rafrh69bcv.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/MedPulse-main/google.php';

// Create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Generate a unique user ID
function generateUniqueID($pdo) {

    $randomNumber = rand(100000, 999999);

    return $randomNumber;
}

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

    // Generate a secure placeholder password (if needed)
    $password ='default_password';

    // Insert or update user information in the database
    $uniqueID = generateUniqueID($pdo);
    $query = "INSERT INTO users (unique_id, fname, lname, email, password) 
              VALUES (:unique_id, :fname, :lname, :email, :password)";
    $stmt = $pdo->prepare($query);
    
    try {
        $stmt->execute([
            ':unique_id' => $uniqueID,
            ':fname' => $_SESSION['fname'],
            ':lname' => $_SESSION['lname'],
            ':email' => $_SESSION['email_address'],
            ':password' => $password,
        ]);
    
        if ($stmt->rowCount() > 0) {
            echo "Data inserted successfully!";
        } else {
            echo "Insert failed. No rows affected.";
        }
    } catch (PDOException $e) {
        echo "SQL Error: " . $e->getMessage();
    }
    

    if ($stmt->rowCount() > 0) {
        // Store unique ID in the session
        $_SESSION['unique_id'] = $uniqueID;
        header('Location: ../MedPulse-main/profile'); // Redirect to your profile page
        exit;
    } else {
        // Fetch existing user information if insertion fails
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $_SESSION['email_address']);
        $stmt->execute();

        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['unique_id'] = $result['unique_id'];
            header('Location: ../MedPulse-main/profile'); // Redirect to your profile page
            exit;
        } else {
            echo "User not found after insertion attempt. Please try logging in again.";
            exit;
        }
    }
} else {
    // Show Google Login link with button styling
    header('Location: ' . $client->createAuthUrl());
}
?>

