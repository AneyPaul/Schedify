<?php
session_start();
require 'vendor/autoload.php';

use Google\Client;

function getClient() {
    $client = new Client();
    $client->setAuthConfig('client_secret_2.json'); // Path to your credentials file
    $client->setRedirectUri('http://localhost/GoogleDriveApi/authorize.php'); // Redirect URI back to this page
    $client->setScopes([
        "https://www.googleapis.com/auth/drive",
        "https://www.googleapis.com/auth/drive.metadata.readonly"
    ]);
    $client->setAccessType('offline');
    $client->setPrompt('select_account');
    
    return $client;
}

$client = getClient();

// Check if we already have a token
$tokenPath = 'token.json';
if (file_exists($tokenPath)) {
    $_SESSION['access_token'] = json_decode(file_get_contents($tokenPath), true);
    // Redirect to the original page that requested authorization
    if (isset($_SESSION['redirect_after_auth'])) {
        header('Location: ' . $_SESSION['redirect_after_auth']);
        exit;
    }
} else {
    // If no token exists, initiate the OAuth flow
    if (!isset($_GET['code'])) {
        // Generate and redirect to the authorization URL
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit;
    } else {
        // Exchange authorization code for an access token
        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($accessToken);

        // Save token to a file
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        $_SESSION['access_token'] = $accessToken;

        // Redirect back to the original page
        if (isset($_SESSION['redirect_after_auth'])) {
            header('Location: ' . $_SESSION['redirect_after_auth']);
            exit;
        }
    }
}
?>