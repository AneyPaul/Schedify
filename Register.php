<?php
// Enable error reporting
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
$error_message = "";
// MySQL connection details
$servername = "localhost";
$username = "root";  // Default username in XAMPP is "root"
$password = "";      // Default password is an empty string in XAMPP
$dbname = "test"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Connection failed, display the error
    die("Connection failed: " . $conn->connect_error);
} else {
    // Connection successful
    //$error_message =  "Connected successfully to the database!";
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //echo "Valid email address.";
    
    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        $error_message =  "Passwords do not match!";
    } else {

         // Check if the username or email already exists in the database
        $sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        // If username or email already exists
        if ($stmt_check->num_rows > 0) {
            $error_message =  "Username or Email already taken. Please choose another one.";
        } else {
        // Password Hashing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL to insert user into database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            // Execute query
            if ($stmt->execute()) {
                //echo "Registration successful!";
                 header("Location: login.php");
                exit(); // Ensure that no further code is executed
            } else {
                $error_message =  "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message =  "Error: " . $conn->error;
        }
    }
    }
    } else {
        $error_message = "Invalid email address.";
    }
}

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

    <title>Register</title>

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

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="POST">
                                <div class="form-group">
                                    
                                        <input type="text" class="form-control form-control-user" name="username"
                                            placeholder="User Name" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
                                    
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email"
                                        placeholder="Email Address" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            name="password" placeholder="Password"  required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            name="confirm_password" placeholder="Confirm Password" required>
                                    </div>
                                </div>
                                 
                                  <div class="form-group" >
                                    <input type="submit" value="Register Account" class="btn btn-primary">                               
                                  </div>
                                  <?php if (isset($error_message) && $error_message!="") { ?>
                            <div class="alert alert-danger">
                                <strong><?php echo $error_message; ?></strong> 
                            </div>
                            <?php } ?>
                              
                            </form>


                             
                            <hr>
                            
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
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