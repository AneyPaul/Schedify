<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input from the form
    $fileid = $_POST['fileId'];
    $delay = (int)$_POST['delay'];
    $unit = $_POST['timeUnit'];

    //echo $unit;
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



     // Create URL for delete_task.php
$url = "http://localhost/GoogleDriveApi/delete_task.php?fileid=$fileid&delayInSeconds=$delayInSeconds";

// Run the task in the background
//$command = "php -f delete_task.php fileid=$fileid delayInSeconds=$delayInSeconds > /dev/null 2>&1 &";
//exec($command);
//file_put_contents('log.txt', "command: $command File $fileid after $delayInSeconds seconds\n", FILE_APPEND);

$command = "cmd /c start php -f C:/xampp/htdocs/GoogleDriveApi/delete_task.php fileid=$fileid delayInSeconds=$delayInSeconds";
    exec($command);
//$backgroundUrl = "http://localhost/GoogleDriveApi/delete_task.php?fileid=$fileid&delayInSeconds=$delayInSeconds";
//error_log("Calling URL: " . $backgroundUrl);

// Call file_get_contents
//file_get_contents($backgroundUrl);

    // Run the deletion script in the background
//$command = "php C:\xampp\htdocs\GoogleDriveApi\deletion_script.php {$folderId} {$delayInSeconds} > NUL 2>&1 &";
    //$command = "start /B php C:\\xampp\\htdocs\\GoogleDriveApi\\deletion_script.php {$folderId} {$delayInSeconds} > NUL 2>&1";
/*
    date_default_timezone_set('America/Boise');

// Get the current date and time
$currentTime = new DateTime();
// Add 30 seconds to the current time
$currentTime = $currentTime->modify('+' . $delayInSeconds . ' seconds');
$startTime = $currentTime->format('H:i:s');

// Create the scheduled task using schtasks
$command = "schtasks /create /tn \"Delete Google Drive Folder\" /tr \"php C:\\xampp\\php\\php.exe C:\\xampp\\htdocs\\GoogleDriveApi\\deletion_script.php {$folderId} {$delayInSeconds}\" /sc once /st {$startTime} /f";
exec($command);

echo "Scheduled task has been created to run at {$startTime}.\n";
*/
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

    <title>Shared Users</title>

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
    <a class="nav-link collapsed" href="ScheduleTime.php" >
        <i class="fas fa-fw fa-clock"></i>
        <span>Set Time</span>
    </a>
   
</li>

<!-- Nav Item - Utilities Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
        aria-expanded="true" aria-controls="collapseUtilities">
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

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Aney Paul</span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
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
                     <form method="POST" action="ScheduleTime.php">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Set Time for Auto Deletion of Google Drive File/Folder</h6>
                        </div>
                       
                        <div class="card-body">
                        
                           <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-1 col-md-6 mb-4">
                            <label for="fileId" Style="float: right;">File ID:</label>
        </div>
         <div class="col-xl-3 col-md-6 mb-4">
                            <input type="text" name="fileId" required>
        </div>
                               <div class="col-xl-2 col-md-6 mb-4">
                             <label for="time" Style="float: right;">Delete After:</label>
                                   </div>
                                    <div class="col-xl-3 col-md-6 mb-4">
                            		<input type="number" name="delay" min="1" required>
        
        						<select name="timeUnit">
            					<option value="sec">Seconds</option>
            					<option value="min">Minutes</option>
            					<option value="hr">Hours</option>
            					<option value="day">Days</option>
        						</select>
                          </div>
                                  
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <button type="submit">Schedule Time for Deletion</button>
                                </div>
                        </div>
                         
                            </div>
                         </div>
                            <div class="card shadow mb-4">
                                                <div class="card-body">
                    
                       <div class="row">

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-2 col-md-6 mb-4">
                        <asp:Label ID="Label2" runat="server" Text="Drive Folder ID:" Style="float: right; "/>
    </div>
     <div class="col-xl-3 col-md-6 mb-4">
                        <asp:TextBox ID="txtFolderId" runat="server" class="form-control form-control-user" />
    </div>
                           <div class="col-xl-1 col-md-6 mb-4">
                         <asp:Label ID="Label3" runat="server" Text="Time:" Style="float: right;"/>
                               </div>
                                <div class="col-xl-2 col-md-6 mb-4">
                        <asp:TextBox ID="txtTimeFolder" runat="server" class="form-control form-control-user" />
                               </div>
                              
                            <div class="col-xl-3 col-md-6 mb-4">
                                <asp:Button ID ="Button2" runat="server" Text="Schedule Time" OnClick="BtnSetTimeClickForFolder"/>
                            </div>
                    </div>
                     
                        </div>
                                </div>
                            
                        
                   
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

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