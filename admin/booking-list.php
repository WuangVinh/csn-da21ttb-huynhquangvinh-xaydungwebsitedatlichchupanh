<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Xử lý cập nhật trạng thái đặt lịch
if(isset($_POST['action']) && $_POST['action'] == 'updateStatus') {
    try {
        // Lấy thông tin đặt lịch
        $stmt = $conn->prepare("SELECT dl.*, dv.TenDichVu, kh.HoTen, kh.Email 
                               FROM DatLich dl 
                               JOIN DichVu dv ON dl.MaDichVu = dv.MaDichVu 
                               JOIN KhachHang kh ON dl.MaKhachHang = kh.MaKhachHang 
                               WHERE dl.MaDatLich = ?");
        $stmt->execute([$_POST['id']]);
        $booking = $stmt->fetch();

        // Cập nhật trạng thái
        $stmt = $conn->prepare("UPDATE DatLich SET TrangThai = ? WHERE MaDatLich = ?");
        $stmt->execute([
            $_POST['trangThai'],
            $_POST['id']
        ]);

        // Gửi email thông báo
        $mail = new PHPMailer(true);
        try {
            // Cấu hình email
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vvinh2905@gmail.com'; // Thay bằng email của bạn
            $mail->Password = 'Vinh0378995096@'; // Thay bằng mật khẩu ứng dụng Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // Người gửi và người nhận
            $mail->setFrom('vvinh2905@gmail.com', 'Studio Quang Vinh'); // Thay email và tên studio
            $mail->addAddress($booking['Email'], $booking['HoTen']);

            // Nội dung email
            $mail->isHTML(true);
            if($_POST['trangThai'] == 'DaXacNhan') {
                $mail->Subject = 'Xác nhận đặt lịch thành công';
                $mail->Body = '
                    <h2>Xác nhận đặt lịch thành công</h2>
                    <p>Xin chào ' . $booking['HoTen'] . ',</p>
                    <p>Chúng tôi xác nhận lịch hẹn của bạn đã được chấp nhận với thông tin như sau:</p>
                    <ul>
                        <li>Dịch vụ: ' . $booking['TenDichVu'] . '</li>
                        <li>Ngày: ' . date('d/m/Y', strtotime($booking['NgayChup'])) . '</li>
                        <li>Giờ: ' . $booking['GioChup'] . '</li>
                    </ul>
                    <p>Vui lòng đến đúng giờ. Nếu có thay đổi, hãy liên hệ với chúng tôi.</p>
                    <p>Xin cảm ơn!</p>';
            } else {
                $mail->Subject = 'Thông báo hủy lịch';
                $mail->Body = '
                    <h2>Thông báo hủy lịch</h2>
                    <p>Xin chào ' . $booking['HoTen'] . ',</p>
                    <p>Chúng tôi rất tiếc phải thông báo rằng lịch hẹn của bạn đã bị hủy:</p>
                    <ul>
                        <li>Dịch vụ: ' . $booking['TenDichVu'] . '</li>
                        <li>Ngày: ' . date('d/m/Y', strtotime($booking['NgayChup'])) . '</li>
                        <li>Giờ: ' . $booking['GioChup'] . '</li>
                    </ul>
                    <p>Vui lòng đặt lịch lại vào thời gian khác.</p>
                    <p>Xin lỗi vì sự bất tiện này!</p>';
            }

            $mail->send();
        } catch (Exception $e) {
            error_log("Lỗi gửi mail: {$mail->ErrorInfo}");
        }

        echo json_encode(['success' => true]);
        exit;
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản lý đặt lịch</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách đặt lịch</h3>
                </div>
                <div class="card-body">
                    <table id="bookingTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Khách hàng</th>
                                <th>Dịch vụ</th>
                                <th>Ngày đặt</th>
                                <th>Giờ đặt</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("SELECT dl.*, dv.TenDichVu, kh.HoTen, kh.SoDienThoai 
                                                FROM DatLich dl 
                                                JOIN DichVu dv ON dl.MaDichVu = dv.MaDichVu 
                                                JOIN KhachHang kh ON dl.MaKhachHang = kh.MaKhachHang 
                                                ORDER BY dl.MaDatLich DESC");
                            while ($row = $stmt->fetch()) {
                                $trangThai = '';
                                $badgeClass = '';
                                switch($row['TrangThai']) {
                                    case 'ChoXacNhan':
                                        $trangThai = 'Chờ xác nhận';
                                        $badgeClass = 'warning';
                                        break;
                                    case 'DaXacNhan':
                                        $trangThai = 'Đã xác nhận';
                                        $badgeClass = 'success';
                                        break;
                                    case 'DaHuy':
                                        $trangThai = 'Đã hủy';
                                        $badgeClass = 'danger';
                                        break;
                                    case 'DaHoanThanh':
                                        $trangThai = 'Đã hoàn thành';
                                        $badgeClass = 'info';
                                        break;
                                }
                            ?>
                            <tr>
                                <td><?= $row['MaDatLich'] ?></td>
                                <td>
                                    <?= $row['HoTen'] ?><br>
                                    <small><?= $row['SoDienThoai'] ?></small>
                                </td>
                                <td><?= $row['TenDichVu'] ?></td>
                                <td><?= date('d/m/Y', strtotime($row['NgayChup'])) ?></td>
                                <td><?= $row['GioChup'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $badgeClass ?>">
                                        <?= $trangThai ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm view-booking" 
                                            data-id="<?= $row['MaDatLich'] ?>"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewBookingModal">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if($row['TrangThai'] == 'ChoXacNhan'): ?>
                                    <button type="button" class="btn btn-success btn-sm update-status" 
                                            data-id="<?= $row['MaDatLich'] ?>"
                                            data-status="DaXacNhan">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm update-status" 
                                            data-id="<?= $row['MaDatLich'] ?>"
                                            data-status="DaHuy">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Xem chi tiết -->
<div class="modal fade" id="viewBookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đặt lịch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="bookingDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Khởi tạo DataTable
    if (!$.fn.DataTable.isDataTable('#bookingTable')) {
        $('#bookingTable').DataTable({
            "language": {
                "sProcessing":   "Đang xử lý...",
                "sLengthMenu":   "Xem _MENU_ mục",
                "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",
                "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
                "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
                "sInfoFiltered": "(đã lọc từ _MAX_ mục)",
                "sInfoPostFix":  "",
                "sSearch":       "Tìm:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "Đầu",
                    "sPrevious": "Trước",
                    "sNext":     "Tiếp",
                    "sLast":     "Cuối"
                }
            }
        });
    }

    // Xem chi tiết đặt lịch
    $('.view-booking').click(function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'booking-detail.php',
            type: 'GET',
            data: {id: id},
            success: function(response) {
                $('#bookingDetails').html(response);
            }
        });
    });

    // Cập nhật trạng thái
    $('.update-status').click(function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        let title, text, confirmButtonText, successMessage;

        if(status === 'DaXacNhan') {
            title = 'Xác nhận lịch hẹn?';
            text = 'Bạn có chắc muốn xác nhận lịch hẹn này?';
            confirmButtonText = 'Xác nhận';
            successMessage = 'Đã xác nhận lịch h���n và gửi email thông báo cho khách hàng.';
        } else {
            title = 'Hủy lịch hẹn?';
            text = 'Bạn có chắc muốn hủy lịch hẹn này?';
            confirmButtonText = 'Hủy lịch';
            successMessage = 'Đã hủy lịch hẹn và gửi email thông báo cho khách hàng.';
        }
        
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: status === 'DaXacNhan' ? '#28a745' : '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Đóng'
        }).then((result) => {
            if (result.isConfirmed) {
                // Hiển thị loading
                const loadingAlert = Swal.fire({
                    title: 'Đang xử lý...',
                    html: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: 'booking-list.php',
                    type: 'POST',
                    data: {
                        action: 'updateStatus',
                        id: id,
                        trangThai: status
                    },
                    success: function(response) {
                        // Đóng loading
                        loadingAlert.close();
                        
                        const data = JSON.parse(response);
                        if(data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: successMessage,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: data.message || 'Có lỗi xảy ra, vui lòng thử lại sau.'
                            });
                        }
                    },
                    error: function() {
                        // Đóng loading khi có lỗi
                        loadingAlert.close();
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Không thể kết nối đến máy chủ.'
                        });
                    }
                });
            }
        });
    });
});
</script>
