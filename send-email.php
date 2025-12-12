<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Cần cài đặt PHPMailer qua composer

function sendBookingConfirmation($customerEmail, $bookingDetails) {
    $mail = new PHPMailer(true);

    try {
        // Cấu hình server
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Thay bằng SMTP server của bạn
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Email của bạn
        $mail->Password = 'your-password'; // Mật khẩu email
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Người nhận
        $mail->setFrom('your-email@gmail.com', 'Studio Name');
        $mail->addAddress($customerEmail);

        // Nội dung
        $mail->isHTML(true);
        $mail->Subject = 'Xác nhận đặt lịch chụp ảnh';
        $mail->Body = '
            <h2>Xác nhận đặt lịch chụp ảnh</h2>
            <p>Cảm ơn bạn đã đặt lịch tại studio chúng tôi.</p>
            <p>Thông tin đặt lịch:</p>
            <ul>
                <li>Mã đặt lịch: '.$bookingDetails['MaDatLich'].'</li>
                <li>Ngày chụp: '.$bookingDetails['NgayChup'].'</li>
                <li>Giờ chụp: '.$bookingDetails['GioChup'].'</li>
                <li>Dịch vụ: '.$bookingDetails['TenDichVu'].'</li>
                <li>Tổng tiền: '.number_format($bookingDetails['TongTien']).' VNĐ</li>
            </ul>
            <p>Chúng tôi sẽ liên hệ với bạn sớm để xác nhận lịch hẹn.</p>
        ';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: ".$mail->ErrorInfo);
        return false;
    }
}
