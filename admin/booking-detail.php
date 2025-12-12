<?php
require_once '../config/database.php';
session_start();

if(!isset($_SESSION['admin'])) {
    die('Unauthorized');
}

if(isset($_GET['id'])) {
    $database = new Database();
    $conn = $database->getConnection();
    
    $stmt = $conn->prepare("SELECT dl.*, dv.TenDichVu, dv.GiaTien, kh.HoTen, kh.Email, kh.SoDienThoai 
                           FROM DatLich dl 
                           JOIN DichVu dv ON dl.MaDichVu = dv.MaDichVu 
                           JOIN KhachHang kh ON dl.MaKhachHang = kh.MaKhachHang 
                           WHERE dl.MaDatLich = ?");
    $stmt->execute([$_GET['id']]);
    $booking = $stmt->fetch();
    
    if($booking) {
        $trangThai = '';
        switch($booking['TrangThai']) {
            case 'ChoXacNhan': $trangThai = 'Chờ xác nhận'; break;
            case 'DaXacNhan': $trangThai = 'Đã xác nhận'; break;
            case 'DaHuy': $trangThai = 'Đã hủy'; break;
            case 'DaHoanThanh': $trangThai = 'Đã hoàn thành'; break;
        }
        ?>
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>Mã đặt lịch:</th>
                    <td><?= $booking['MaDatLich'] ?></td>
                </tr>
                <tr>
                    <th>Khách hàng:</th>
                    <td><?= $booking['HoTen'] ?></td>
                </tr>
                <tr>
                    <th>Số điện thoại:</th>
                    <td><?= $booking['SoDienThoai'] ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?= $booking['Email'] ?></td>
                </tr>
                <tr>
                    <th>Dịch vụ:</th>
                    <td><?= $booking['TenDichVu'] ?></td>
                </tr>
                <tr>
                    <th>Giá tiền:</th>
                    <td><?= number_format($booking['GiaTien']) ?> VNĐ</td>
                </tr>
                <tr>
                    <th>Ngày đặt:</th>
                    <td><?= date('d/m/Y', strtotime($booking['NgayDat'])) ?></td>
                </tr>
                <tr>
                    <th>Giờ đặt:</th>
                    <td><?= $booking['GioDat'] ?></td>
                </tr>
                <tr>
                    <th>Ghi chú:</th>
                    <td><?= $booking['GhiChu'] ?: 'Không có' ?></td>
                </tr>
                <tr>
                    <th>Trạng thái:</th>
                    <td><?= $trangThai ?></td>
                </tr>
            </table>
        </div>
        <?php
    } else {
        echo 'Không tìm thấy thông tin đặt lịch';
    }
}
?>
