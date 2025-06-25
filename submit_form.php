<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $mobile = trim($_POST["mobile"]);
    $city = trim($_POST["city"]);
    $message = trim($_POST["message"]);
    if (empty($name) || empty($mobile) || empty($city) || empty($message)) {
        echo "<script>alert('All fields are required!'); window.location.href='index.php';</script>";
        exit();
    }
    $stmt = $conn->prepare("INSERT INTO contacts (name, mobile, city, message) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        echo "<script>alert('Database error: " . $conn->error . "'); window.location.href='index.php';</script>";
        exit();
    }
    $stmt->bind_param("ssss", $name, $mobile, $city, $message);
    if ($stmt->execute()) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kandagesakshi55@gmail.com';
            $mail->Password = 'drcz zzgj mrqd dtiw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('kandagesakshi55@gmail.com', 'HomeBuild');
            $mail->addAddress('kandagesakshi55@gmail.com', 'Sakshi');
            $mail->isHTML(true);
            $mail->Subject = "New Inquiry";
            $mail->Body = "
                <h3>Contact Form Details</h3>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Mobile:</strong> $mobile</p>
                <p><strong>City:</strong> $city</p>
                <p><strong>Message:</strong> $message</p>
            ";
            $mail->send();
            echo "<script>alert('Form submitted and email sent successfully!'); window.location.href='index.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Mailer Error: {$mail->ErrorInfo}'); window.location.href='index.php';</script>";
        }

    } else {
        echo "<script>alert('Database Error: " . $stmt->error . "'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
