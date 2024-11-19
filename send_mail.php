<?php
if (isset($_GET['email'])) {
    $encodedEmail = $_GET['email'];
    $email = base64_decode($encodedEmail);
    sendEmail($email);
}
//echo $email;
else if (isset($_GET['emails'])) {
    // Get the Base64-encoded email list
    $encodedEmailList = $_GET['emails'];

    // Decode the Base64 email list
    $emailList = base64_decode($encodedEmailList);
    echo $encodedEmailList;
    // Split the string back into an array
    $emailArray = explode(',', $emailList);

    // Display the email list (or process it further)
    echo "<h2>Email Addresses:</h2>";
    echo "<ul>";
    foreach ($emailArray as $email) {
        echo "<li>" . htmlspecialchars($email) . "</li>";
        sendEmail($email);
    }
    echo "</ul>";
} else {
    echo "No email list found in the URL.";
}
?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//require 'vendor/autoload.php'; // Make sure this path is correct

 // Create a new PHPMailer instance

function sendEmail($emailTo) {

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure this path is correct

 // Create a new PHPMailer instance
 $mail = new PHPMailer(true);
  $emailTo = $emailTo;
  //echo $emailTo;
try {
    //Server settings
    
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'schedify24@gmail.com'; // Your Gmail email
    $mail->Password = 'pvqyuxibqeltdukl'; // Your Gmail password (use App Password if 2FA is enabled)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587; // Use port 587 for TLS

    //Recipients
    $mail->setFrom('schedify24@gmail.com', '');
    $mail->addAddress($emailTo, $emailTo); // Add a recipient

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the plain text version of the email content';

    $mail->send();
    echo "Email has been sent to the email address: {$emailTo}";
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
}
}
?>

