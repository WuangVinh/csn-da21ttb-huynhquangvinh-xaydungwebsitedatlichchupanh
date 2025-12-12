<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendBookingEmail($to, $bookingInfo) {
    $mail = new PHPMailer(true);

    try {
        // Tắt debug mode
        $mail->SMTPDebug = 0;
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'luongtamcanrut75@gmail.com';
        $mail->Password = 'obpo qfbe toyf phfy';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom('luongtamcanrut75@gmail.com', 'Studio Bích Trâm');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Xác nhận đặt lịch chụp ảnh';
        $mail->Body = getBookingEmailTemplate($bookingInfo);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}
?>
