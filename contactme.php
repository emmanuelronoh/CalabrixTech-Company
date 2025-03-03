<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the PHPMailer autoloader (assuming Composer is used)
require 'vendor/autoload.php';

// Collect form data
$name = htmlspecialchars($_POST["name"]);
$phone = htmlspecialchars($_POST['phone']);
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars($_POST["message"]);

try {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    // Set mailer to use SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'skillsphereventures@gmail.com';  // Your Gmail address
    $mail->Password = 'CalabrixTechcompany';  // Your Gmail password or App password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS encryption
    $mail->Port = 587;  // TCP port to connect to (587 for Gmail)

    // Set the sender and recipient
    $mail->setFrom('skillsphereventures@gmail.com', 'CalabrixTech');  // Update the sender email to your Gmail
    $mail->addAddress('skillsphereventures@gmail.com');  // Recipient's email address

    // Set the email format to HTML
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission';
    $mail->Body    = "
    <ul>
        <li><strong>Name:</strong> $name</li>
        <li><strong>Phone:</strong> $phone</li>
        <li><strong>Email:</strong> $email</li>
        <li><strong>Message:</strong> $message</li>
    </ul>";

    // Send the email
    if ($mail->send()) {
        echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
    } else {
        echo '<center><h1>Error sending message! Please try again.</h1></center>';
    }

} catch (Exception $e) {
    echo '<center><h1>Error sending message! Mailer Error: ' . $mail->ErrorInfo . '</h1></center>';
}
?>
