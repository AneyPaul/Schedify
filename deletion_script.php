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
$successMessage = "";

function deleteFolder($driveService, $folderId) {
    try {
        $driveService->files->delete($folderId);
        //echo "Folder deleted successfully.\n";
        $successMessage = "File/Folder Deleted successfully!";
    } catch (Exception $e) {
        $successMessage = "An error occurred: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input from the form
    $folderId = $_POST['folderId'];
    $delay = (int)$_POST['delay'];
    $unit = $_POST['unit'];

    // Convert delay to seconds based on the selected unit
    switch (strtolower($unit)) {
        case 'sec':
            $delayInSeconds = $delay;
            break;
        case 'min':
            $delayInSeconds = $delay * 60;
            break;
        case 'hr':
            $delayInSeconds = $delay * 3600;
            break;
        case 'day':
            $delayInSeconds = $delay * 86400;
            break;
        default:
            echo "Invalid time unit entered.";
            exit;
    }

    //echo "Waiting for $delayInSeconds seconds before deleting the folder...<br>";
    sleep($delayInSeconds);

   // Initialize the Google Drive service and delete the folder
   try{
     //$driveService = getDriveService();
     $client = getClient();
    $driveService = new Google_Service_Drive($client);
    deleteFolder($driveService, $folderId);
    $successMessage = "File/Folder Deleted successfully!";
   } catch (Exception $e) {
        $successMessage = "An error occured: " . $e->getMessage();
    }
   


}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Schedule Time</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

          
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
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
            

            <!-- Nav Item - Charts -->
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
                   
                    <!-- DataTales Example -->
                     <form method="POST" action="deletion_script.php">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Set Time for Auto Deletion of Google Drive File/Folder</h6>
                        </div>
                       
                        <div class="card-body">
                        
                           <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-2 col-md-6 mb-4">
                            <label for="fileId" Style="float: right;">File/Folder ID:</label>
        </div>
         <div class="col-xl-2 col-md-6 mb-4">
                            <input type="text" name="folderId" required>
        </div>
                               <div class="col-xl-3 col-md-6 mb-4">
                             <label for="time" Style="float: right;">Delete After:</label>
                                   </div>
                                    <div class="col-xl-3 col-md-6 mb-4">
                                    <input type="number" name="delay" min="1" required>
        
                                <select name="unit">
                                <option value="sec">Seconds</option>
                                <option value="min">Minutes</option>
                                <option value="hr">Hours</option>
                                <option value="day">Days</option>
                                </select>
                          </div>
                                  
                                <div class="col-xl-2 col-md-6 mb-4">
                                    <button type="submit" class="btn btn-primary">Schedule Time</button>
                                </div>
                        </div>
                         
                            </div>
                         </div>
                            
                    <?php if ($successMessage): ?>
            <div Style="color: green; font-weight: bold; margin-top: 10x;">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
                   </form>
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>