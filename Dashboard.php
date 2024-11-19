<?php
// Start session to check login status
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Handle logout request
if (isset($_POST['logout'])) {
    // Destroy the session to log the user out
    session_unset();
    session_destroy();
    // Redirect to the login page after logout
    header("Location: login.php");
    exit();
}

?>
<?php
//session_start();
require 'vendor/autoload.php';

//use Google\Client;
//use Google\Service\Drive;

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

function getSharedFiles() {
    //$driveService = getDriveService(); // Get authenticated Google Drive service
     $client = getClient();
    $driveService = new Google_Service_Drive($client);
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
//echo "Shared images: $imageCount\n";
//echo "Shared videos: $videoCount\n";
//echo "Shared documents: $documentCount\n";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="Dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
           

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="deletion_script.php" >
                    <i class="fas fa-fw fa-clock"></i>
                    <span>Schedule Time</span>
                </a>
               
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="Notification.php" >
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Notification Settings</span>
                </a>
               
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            

            <!-- Nav Item - Pages Collapse Menu -->
           
            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="SharedUsers.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Shared Users</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>User Manual</span></a>
            </li>
            <!-- Nav Item - Charts -->
          

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

           

            
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    
                   <div class="sidebar-brand-text mx-3">Google Drive Storage Management </div>
                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                       

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $username; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg" id="menuIcon">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown" id="dropdownMenu">
                                <a class="dropdown-item" href="#">
                                    
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    
                                    Settings
                                </a>
                               
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"  id="logoutBtn">
                                   
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Shared (Images)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $imageCount; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-image fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Shared (Videos)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $videoCount; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-video fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Shared (Documents)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $documentCount; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <!-- Earnings (Monthly) Card Example -->
 

                    </div>

                    <!-- Content Row -->

                    
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Google Drive Storage</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">Shared Image <span
                                            class="float-right">20%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: 20%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Shared Video <span
                                            class="float-right">40%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 40%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Shared Document <span
                                            class="float-right">60%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 60%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    
                                </div>
                            </div>

                            <!-- Color System -->
                           

                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- Illustrations -->
                            <div class="card shadow mb-4">
                               
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                            src="img/undraw_posting_photo.svg" alt="...">
                                    </div>
                                   
                                    
                                </div>
                            </div>

                            

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Google Drive Storage Management Website 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    
        
    <div id = "logoutModal" style="display:None;background-color: rgba(0, 0, 0, 0.5); position: fixed;top:0%;left:50%;transform: translate(-50%, 0%);z-index: 9999;padding: 10px; text-align: center;">
        <div style="background-color: white; padding: 20px; display: inline-block; border-radius: 5px;">
            <h3>Are you sure you want to logout?</h3>
            <form method="POST">
                <button type="submit" name="logout">Yes, Logout</button>
                <button type="button" id="cancelLogout">Cancel</button>
            </form>
        </div>
    </div>

    



    <script>
    // Get elements
    var menuIcon = document.getElementById("menuIcon");
    var dropdownMenu = document.getElementById("dropdownMenu");
    var logoutBtn = document.getElementById("logoutBtn");
    var logoutModal = document.getElementById("logoutModal");
    var cancelLogout = document.getElementById("cancelLogout");

    // Toggle dropdown menu visibility when the icon is clicked
    menuIcon.onclick = function() {
        dropdownMenu.style.display = (dropdownMenu.style.display === "block") ? "none" : "block";
    };

    // When the user clicks the logout option, show the confirmation modal
    logoutBtn.onclick = function(event) {
        event.preventDefault(); // Prevent default link behavior
        logoutModal.style.display = "block";
        dropdownMenu.style.display = "none"; // Hide the dropdown menu
    };
    // When the user clicks the cancel button in the modal, close the modal
    cancelLogout.onclick = function() {
        logoutModal.style.display = "none";
    };
    // Close dropdown if clicked outside of it
    window.onclick = function(event) {
        if (!event.target.matches('#menuIcon') && !event.target.matches('#dropdownMenu') ) {
            dropdownMenu.style.display = "none";
        }
    };
</script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>