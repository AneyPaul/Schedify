<?php
require_once 'vendor/autoload.php'; // Path to the Composer autoload file

session_start();

// Initialize Google Client
$client = new Google_Client();
$client->setClientId('472966918620-4tgo5m60258vsdpa20runrt8gkct8eft.apps.googleusercontent.com'); // Replace with your client ID
$client->setClientSecret('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'); // Replace with your client secret
$client->setRedirectUri('http://localhost/GoogleDriveApi/google.php'); // Replace with your redirect URI
$client->addScope(Google_Service_Drive::DRIVE);

// If there is no authentication code, redirect to Google for authorization
if (!isset($_GET['code'])) {
    // Generate OAuth URL for user login and account selection
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
} else {
    // Exchange the authorization code for an access token
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();

    // Redirect back to the page
    //header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    header("Location: Dashboard.php");
    exit;
}
?>
