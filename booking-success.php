<?php
session_start();
$pageTitle = "Đặt lịch thành công";

// Kiểm tra nếu không có thông tin đặt lịch thì chuyển về trang chủ
if (!isset($_SESSION['booking_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    <h2 class="my-4">Đặt lịch thành công!</h2>
                    <p>Cảm ơn bạn đã đặt lịch chụp ảnh tại studio chúng tôi.</p>
                    <p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận lịch hẹn.</p>
                    
                    <?php if(isset($_SESSION['booking_id'])): ?>
                        <p>Mã đặt lịch của bạn: <strong><?php echo $_SESSION['booking_id']; ?></strong></p>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['booking_info'])): ?>
                        <div class="booking-details mt-4">
                            <h4>Thông tin đặt lịch:</h4>
                            <p>Họ tên: <?php echo $_SESSION['booking_info']['hoTen']; ?></p>
                            <p>Ngày chụp: <?php echo $_SESSION['booking_info']['ngayChup']; ?></p>
                            <p>Giờ chụp: <?php echo $_SESSION['booking_info']['gioChup']; ?></p>
                            <p>Tổng tiền: <?php echo number_format($_SESSION['booking_info']['tongTien']); ?>đ</p>
                        </div>
                    <?php endif; ?>
                    
                    <hr>
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-primary">Về trang chủ</a>
                        <a href="booking-history.php" class="btn btn-outline-primary">Xem lịch đã đặt</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Xóa thông tin đặt lịch khỏi session sau khi hiển thị
unset($_SESSION['booking_id']);
unset($_SESSION['booking_info']);

require_once 'includes/footer.php'; 
?> 