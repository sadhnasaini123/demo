<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendCredentialsEmail($to_email, $username, $password, $account_id) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sadhnasaini20024@gmail.com'; // Your Gmail
        $mail->Password = 'your-app-password'; // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('sadhnasaini20024@gmail.com', 'MLM System');
        $mail->addAddress($to_email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your MLM Account Credentials';
        $mail->Body = "
            <h2>Welcome to MLM System</h2>
            <p>Your account has been created successfully.</p>
            <p><strong>Account Details:</strong></p>
            <ul>
                <li>Account ID: {$account_id}</li>
                <li>Username: {$username}</li>
                <li>Password: {$password}</li>
            </ul>
            <p>Please keep these credentials safe and change your password after first login.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
