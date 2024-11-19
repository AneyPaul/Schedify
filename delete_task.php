<?php
session_start();
require 'vendor/autoload.php';

function getClient() {
    $client = new Google_Client();
    $client->setClientId('472966918620-4tgo5m60258vsdpa20runrt8gkct8eft.apps.googleusercontent.com');  // Replace with your Client ID
    $client->setClientSecret('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');  // Replace with your Client Secret
    $client->setRedirectUri('http://localhost/GoogleDriveApi/google.php');  // Replace with your Redirect URI
    $client->addScope(Google_Service_Drive::DRIVE);

    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $client->setAccessToken($_SESSION['access_token']);
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $_SESSION['access_token'] = $client->getAccessToken();
            } else {
                header('Location: google.php');
                exit;
            }
        }
    } else {
        header('Location: google.php');
        exit;
    }
    return $client;
}

function deleteFolder($driveService, $fileid) {
    try {
        // Log the attempt to delete the file/folder
        file_put_contents('log.txt', "Attempting to delete file/folder with ID: $fileid\n", FILE_APPEND);
        
        // Attempt to delete the file/folder from Google Drive
        $driveService->files->delete($fileid);
        
        // Log success
        file_put_contents('log.txt', "File/Folder with ID: $fileid deleted successfully!\n", FILE_APPEND);
        return "File/Folder Deleted successfully!";
    } catch (Exception $e) {
        // Log error message
        file_put_contents('log.txt', "Error deleting file/folder with ID: $fileid - " . $e->getMessage() . "\n", FILE_APPEND);
        return "An error occurred: " . $e->getMessage();
    }
}

 file_put_contents('log.txt', "Received request to delete file/folder with ID:\n", FILE_APPEND);
$fileid = $_GET['fileid'];
file_put_contents('log.txt', "Received request to delete file/folder with ID: $fileid\n", FILE_APPEND);
if (isset($_GET['fileid']) && isset($_GET['delayInSeconds'])) {
    $fileid = $_GET['fileid'];
    $delayInSeconds = (int)$_GET['delayInSeconds'];

    // Log incoming request
    file_put_contents('log.txt', "Received request to delete file/folder with ID: $fileid after $delayInSeconds seconds\n", FILE_APPEND);

    // Delay for the specified number of seconds
    sleep($delayInSeconds);

    try {
        $client = getClient();
        $driveService = new Google_Service_Drive($client);
        deleteFolder($driveService, $fileid);
        //echo $message;
    } catch (Exception $e) {
        file_put_contents('log.txt', "Error: " . $e->getMessage() . "\n", FILE_APPEND);
        echo "An error occurred: " . $e->getMessage();
    }
//}
?>
