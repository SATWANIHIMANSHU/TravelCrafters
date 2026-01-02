<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . "/config/mail.php";


require 'vendor/autoload.php'; // Load PHPMailer
include 'database/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Email not found.");
    }

    // Generate a token
    $token = bin2hex(random_bytes(50));
    
    // Store the token in the database
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token=?");
    $stmt->bind_param("sss", $email, $token, $token);
    $stmt->execute();

    // Send the reset link via email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME; // Your Gmail address
        $mail->Password = MAIL_PASSWORD; // Use App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;

        $mail->setFrom(MAIL_USERNAME, 'TravelCrafters');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body = "Click the link below to reset your password:<br><br>
                       <a href='http://localhost/TravelCrafters/reset_password.php?token=$token'>Reset Password</a>";

        $mail->send();
        echo "<script>alert('Password reset link sent to your email.'); window.location.href='Login.php';</script>";
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}
?>
