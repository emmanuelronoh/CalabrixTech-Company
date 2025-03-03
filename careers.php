<?php
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// If you're using Composer, this will autoload PHPMailer
require 'vendor/autoload.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture the form data
    $name = $_POST["name"];
    $phone = $_POST['phone'];
    $email = $_POST["email"];
    $applyfor = $_POST["status"];
    $experience = $_POST["experience"];
    $otherdetails = $_POST["details"];

    // Handle the file upload
    if (isset($_FILES["fileToUpload"])) {
        $filename = $_FILES["fileToUpload"]["name"];
        $filetype = $_FILES["fileToUpload"]["type"];
        $filesize = $_FILES["fileToUpload"]["size"];
        $tempfile = $_FILES["fileToUpload"]["tmp_name"];

        // Check if the uploaded file is a PDF (optional validation)
        $allowedFileTypes = ["application/pdf"];
        if (!in_array($filetype, $allowedFileTypes)) {
            echo "<center><h1>Only PDF files are allowed! Please upload a valid PDF resume.</h1></center>";
            exit;
        }

        // Check if the file size is below the maximum limit (e.g., 5MB)
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($filesize > $maxFileSize) {
            echo "<center><h1>File size exceeds the limit of 5MB! Please upload a smaller file.</h1></center>";
            exit;
        }

        // Define the directory where the uploaded file will be saved
        $uploadDirectory = "./tmp-uploads/"; // Make sure the tmp-uploads folder exists and is writable
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true); // Create the directory if it doesn't exist
        }

        // Ensure the file is not overwritten by giving it a unique name
        $filenameWithDirectory = $uploadDirectory . $name . "_" . time() . ".pdf"; // Save the file with the name and .pdf extension

        // Prepare the email body
        $body = "<ul>
                    <li><strong>Name:</strong> ".$name."</li>
                    <li><strong>Phone:</strong> ".$phone."</li>
                    <li><strong>Email:</strong> ".$email."</li>
                    <li><strong>Apply For:</strong> ".$applyfor."</li>
                    <li><strong>Experience:</strong> ".$experience." Yrs.</li>
                    <li><strong>Other Details:</strong> ".$otherdetails."</li>
                    <li><strong>Resume (Attached Below):</strong></li>
                 </ul>";

        // Move the uploaded file to the target directory
        if (move_uploaded_file($tempfile, $filenameWithDirectory)) {
            // Initialize PHPMailer and send the email
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Set the SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'skillsphereventures@gmail.com';  // Your Gmail address
                $mail->Password = 'CalabrixTech';  // Gmail App password (if 2FA enabled)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;  // TCP port for TLS

                // Recipients
                $mail->setFrom('skillsphereventures@gmail.com', 'CalabrixTech');
                $mail->addAddress('skillsphereventures@gmail.com');  // Recipient email

                // Content
                $mail->isHTML(true);
                $mail->Subject = "New Job Application: " . $name;
                $mail->Body    = $body;

                // Attach file
                $mail->addAttachment($filenameWithDirectory);

                // Send email
                if ($mail->send()) {
                    echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
                } else {
                    echo '<center><h1>Error sending message! Please try again.</h1></center>';
                }

            } catch (Exception $e) {
                echo '<center><h1>Error sending message! Mailer Error: ' . $mail->ErrorInfo . '</h1></center>';
            }
        } else {
            echo "<center><h1>Error uploading file! Please try again.</h1></center>";
        }
    } else {
        echo "<center><h1>No file uploaded! Please upload your resume.</h1></center>";
    }
}
?>
