<?php
session_start();
require 'vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

function getDriveService() {
    $client = new Client();
    $client->setAuthConfig('client_1.json'); // Path to your credentials file
    $client->setScopes([
        "https://www.googleapis.com/auth/drive",
        "https://www.googleapis.com/auth/drive.metadata.readonly"
    ]);

    // Load token from session or file
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $client->setAccessToken(json_decode(file_get_contents($tokenPath), true));
    } elseif (isset($_SESSION['access_token'])) {
        $client->setAccessToken($_SESSION['access_token']);
    } else {
        // No access token, so redirect to the authorization page
        $_SESSION['redirect_after_auth'] = 'http://localhost/GoogleDriveApi/Drive.php';
        header('Location: http://localhost/GoogleDriveApi/authorize.php');
        exit;
    }

    // Refresh the token if expired
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }

    return new Drive($client);
}

function getSharedFiles() {
    $driveService = getDriveService(); // Get authenticated Google Drive service

    // Update the query to correctly filter files that are shared and owned by you
    $optParams = [
        'q' => "sharedWithMe=true and 'me' in owners",  // Files shared by you
        'fields' => "nextPageToken, files(id, name, mimeType, shared, owners)",
        'pageSize' => 1000  // Adjust page size as needed
    ];

    $files = [];
    $pageToken = null;
    
    do {
        if ($pageToken) {
            $optParams['pageToken'] = $pageToken;
        }
        // List the files
        $response = $driveService->files->listFiles($optParams);
        $files = array_merge($files, $response->files);

        // Check if there is a next page token for pagination
        $pageToken = $response->nextPageToken;
    } while ($pageToken);

    return $files;
}

// Get the list of files shared by you
$sharedFiles = getSharedFiles();

// Count files by type
$imageCount = 0;
$videoCount = 0;
$documentCount = 0;

foreach ($sharedFiles as $file) {
    if ($file->shared) {
        // Check MIME type for images
        if (strpos($file->mimeType, 'image/') === 0) {
            $imageCount++;
        }
        // Check MIME type for videos
        elseif (strpos($file->mimeType, 'video/') === 0) {
            $videoCount++;
        }
        // Check MIME type for documents (e.g., PDFs, Word, etc.)
        elseif (in_array($file->mimeType, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            $documentCount++;
        }
    }
}

// Output the counts
echo "Shared images: $imageCount\n";
echo "Shared videos: $videoCount\n";
echo "Shared documents: $documentCount\n";

?>
