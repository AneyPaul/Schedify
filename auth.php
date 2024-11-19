<?php
// drive.php

require_once 'vendor/autoload.php'; // Path to Composer autoload

session_start();

function getClient(){
// Initialize Google Client
$client = new Google_Client();
$client->setClientId('472966918620-4tgo5m60258vsdpa20runrt8gkct8eft.apps.googleusercontent.com'); // Replace with your Client ID
$client->setClientSecret('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'); // Replace with your Client Secret
$client->setRedirectUri('http://localhost/GoogleDriveApi/google.php'); // Replace with your Redirect URI
$client->addScope(Google_Service_Drive::DRIVE);

// Check if we have an access token
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // If the token is expired, refresh it
    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $_SESSION['access_token'] = $client->getAccessToken();
        } else {
            // If no refresh token, redirect to auth page
            header('Location: google.php');
            exit;
        }
    }
} else {
    // No valid access token, redirect to the OAuth flow
    header('Location: google.php');
    exit;
}
return $client;
}
?>
<?php
$client = getClient();
// Initialize Google Drive service
    $driveService = new Google_Service_Drive($client);

    // List files in the user's Google Drive
    $permissions = $driveService->permissions->listPermissions('1ePUCzUewhIdnU4qIbWgL0l06S37km4YE', [
                    'fields' => 'permissions(emailAddress, role, type)'
                ]);
    echo "<table cellpadding='10' align='center' border='2' cellspacing='2' width='95%'>";
                echo "<tr><th colspan='4'><p align='center'>Shared Users</p></th></tr>";
                echo "<tr><th>Email Address</th><th>Role</th><th>Permission Type</th></tr>";

                
                foreach ($permissions->getPermissions() as $permission) {
                    echo "<tr>";
                    if (isset($permission->emailAddress)) {
                        //echo '<td><input type="checkbox" value="yes" name="selected_users[]" /></td>';
                        echo "<td>" . htmlspecialchars($permission->emailAddress) . "</td>";
                        
                    } else {
                        //echo '<td><input type="checkbox" disabled /></td>';
                        echo "<td>N/A</td>";
                    }
                    echo "<td>" . htmlspecialchars($permission->role) . "</td>";
                    echo "<td>" . (isset($permission->type) ? htmlspecialchars($permission->type) : 'N/A') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
?>
