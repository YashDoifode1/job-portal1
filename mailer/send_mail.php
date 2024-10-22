<?php
// Include Composer's autoloader
define('APP_URL', 'http://localhost/job/job-portal/');
require 'vendor/autoload.php'; // Correct the path to autoload.php
require '../includes/config.php'; // Ensure the config file is included for DB access

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get email and job title from the URL
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
$user_email = isset($_GET['email']) ? urldecode($_GET['email']) : '';
$job_title = isset($_GET['job_title']) ? urldecode($_GET['job_title']) : '';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';  // Specify your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'yashdoifode1439@gmail.com';            // Your Gmail address
    $mail->Password   = 'mvub juzg shso fhpa';                  // Your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('udaan@mailer.com', 'Job Recruitment');
    $mail->addAddress($user_email);     // Add the user's email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Appointment for Job: ' . htmlspecialchars($job_title);

    // Professional HTML email body
    $mail->Body    = '
    <div style="font-family: Arial, sans-serif; color: #333;">
        <h2 style="color: #4CAF50;">Job Appointment Invitation</h2>
        <p>Dear Applicant,</p>
        <p>We are pleased to invite you for an appointment regarding the job position of <strong>' . htmlspecialchars($job_title) . '</strong>.</p>
        <p>Kindly reply to this email for further information, and we will provide you with the necessary details for the appointment.</p>
        <p>Looking forward to your response.</p>
        <p>Best Regards,</p>
        <p><strong>Recruitment Team</strong></p>
        <hr>
        <p style="font-size: 12px; color: #888;">This is an automated message, please do not reply to this email.</p>
    </div>';

    // Send the email
    $mail->send();

    // Update mail status in the database
    $sql_update = "UPDATE applied SET mail_status = 'sended' WHERE user_id = (SELECT id FROM users WHERE email = ?) AND job_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $user_email, $job_id);
    $stmt_update->execute();
    $stmt_update->close();

    // JavaScript alert and redirection
    echo "<script>
        alert('Mail has been sent successfully!');
        window.location.href = '" . APP_URL . "applications/recruter.php?id=$job_id';
    </script>";

} catch (Exception $e) {
    echo "<script>
        alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');
        window.location.href = '" . APP_URL . "applications/recruter.php?id=$job_id';
    </script>";
}
?>
