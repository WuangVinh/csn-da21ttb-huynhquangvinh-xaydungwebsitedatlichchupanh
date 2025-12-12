<?php
session_start();
require_once '../config/database.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Kết nối database
$database = new Database();
$conn = $database->getConnection();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Lấy thống kê
$stats = [
    'totalBookings' => $conn->query("SELECT COUNT(*) FROM DatLichOnline")->fetchColumn(),
    'pendingBookings' => $conn->query("SELECT COUNT(*) FROM DatLichOnline WHERE TrangThai = 'ChoXacNhan'")->fetchColumn(),
    'confirmedBookings' => $conn->query("SELECT COUNT(*) FROM DatLichOnline WHERE TrangThai = 'DaXacNhan'")->fetchColumn(),
    'totalServices' => $conn->query("SELECT COUNT(*) FROM DichVu")->fetchColumn()
];
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $stats['totalBookings'] ?></h3>
                            <p>Tổng số đơn đặt lịch</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="booking-list.php" class="small-box-footer">
                            Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= $stats['pendingBookings'] ?></h3>
                            <p>Đơn chờ xác nhận</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="booking-list.php" class="small-box-footer">
                            Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $stats['confirmedBookings'] ?></h3>
                            <p>Đơn đã xác nhận</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="booking-list.php" class="small-box-footer">
                            Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?= $stats['totalServices'] ?></h3>
                            <p>Dịch vụ</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <a href="service-list.php" class="small-box-footer">
                            Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>
