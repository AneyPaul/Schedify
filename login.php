<?php
// Enable error reporting
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
// Start session to store login status
session_start();

// MySQL connection details
$servername = "localhost";
$username = "root";  // Default username in XAMPP is "root"
$password = "";      // Default password is an empty string in XAMPP
$dbname = "test"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

     
    // Check if the username or email exists in the database
    $sql = "SELECT id, username, email, password FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login: Store user ID in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            

            // Redirect to the dashboard or home page
            header("Location: google.php");
            exit(); // Ensure no further code is executed
        } else {
            // Incorrect password
            $_SESSION['login_error'] = "Invalid username or password.";
        }
    } else {
        // User not found
        $_SESSION['login_error'] = "Invalid username or password.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"> 
                                <img src="data.jpg" style="height:415px; width:500px;"/>
                                </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" method="POST" action="login.php">
                                        <div class="form-group">
                                            <input type="username" class="form-control form-control-user"
                                                name="username" placeholder="Enter UserName or Email Address..." value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group" >
                                        <input type="submit" value="Login" class="btn btn-primary"></div>
                                       <?php
                                        // Display login error message if set
                                            if (isset($_SESSION['login_error'])) {
                                                echo '<div class="alert alert-danger">' . $_SESSION['login_error'] . '</div>';
                                                    unset($_SESSION['login_error']); // Clear the error message
                                            }
                                        ?>
                                    </form>
                                    <hr>
                                  
                                    <div class="text-center">
                                        <a class="small" href="Register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

</body>

</html>