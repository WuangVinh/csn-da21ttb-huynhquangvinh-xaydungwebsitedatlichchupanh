<?php
function getBookingEmailTemplate($bookingInfo) {
    return '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2 style="color: #333;">Xác nhận đặt lịch chụp ảnh</h2>
        
        <p>Xin chào ' . $bookingInfo['hoTen'] . ',</p>
        
        <p>Cảm ơn bạn đã đặt lịch tại studio chúng tôi. Dưới đây là thông tin đặt lịch của bạn:</p>
        
        <div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Mã đặt lịch:</strong> ' . $bookingInfo['booking_id'] . '</p>
            <p><strong>Ngày chụp:</strong> ' . date('d/m/Y', strtotime($bookingInfo['ngayChup'])) . '</p>
            <p><strong>Giờ chụp:</strong> ' . $bookingInfo['gioChup'] . '</p>
            <p><strong>Tổng tiền:</strong> ' . number_format($bookingInfo['tongTien']) . ' VNĐ</p>
        </div>
        
        <p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận lịch hẹn.</p>
        
        <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ:</p>
        <ul>
            <li>Hotline: 090 123 4567</li>
            <li>Email: support@studio.com</li>
        </ul>
        
        <p>Trân trọng,<br>Studio Team</p>
    </div>
    ';
}
?>
