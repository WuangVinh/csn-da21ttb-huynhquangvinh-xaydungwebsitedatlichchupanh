<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Xử lý thêm dịch vụ
if(isset($_POST['action']) && $_POST['action'] == 'add') {
    try {
        $stmt = $conn->prepare("INSERT INTO DichVu (TenDichVu, MoTa, GiaTien, ThoiGian, TrangThai) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([
            $_POST['tenDichVu'],
            $_POST['moTa'],
            $_POST['giaTien'],
            $_POST['thoiGian']
        ]);
        echo json_encode(['success' => true]);
        exit;
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Xử lý cập nhật dịch vụ
if(isset($_POST['action']) && $_POST['action'] == 'edit') {
    try {
        $stmt = $conn->prepare("UPDATE DichVu SET TenDichVu = ?, MoTa = ?, GiaTien = ?, ThoiGian = ?, TrangThai = ? WHERE MaDichVu = ?");
        $stmt->execute([
            $_POST['tenDichVu'],
            $_POST['moTa'],
            $_POST['giaTien'],
            $_POST['thoiGian'],
            $_POST['trangThai'],
            $_POST['id']
        ]);
        echo json_encode(['success' => true]);
        exit;
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Xử lý xóa dịch vụ
if(isset($_POST['action']) && $_POST['action'] == 'delete') {
    try {
        // Kiểm tra xem dịch vụ có đang được sử dụng không
        $stmt = $conn->prepare("SELECT COUNT(*) FROM DatLich WHERE MaDichVu = ?");
        $stmt->execute([$_POST['id']]);
        if($stmt->fetchColumn() > 0) {
            throw new Exception("Không thể xóa dịch vụ đang được sử dụng!");
        }
        
        $stmt = $conn->prepare("DELETE FROM DichVu WHERE MaDichVu = ?");
        $stmt->execute([$_POST['id']]);
        echo json_encode(['success' => true]);
        exit;
    } catch(Exception $e) {
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
                    <h1>Quản lý dịch vụ</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách dịch vụ</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                            <i class="fas fa-plus"></i> Thêm dịch vụ
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="serviceTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên dịch vụ</th>
                                <th>Mô tả</th>
                                <th>Giá tiền</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("SELECT * FROM DichVu ORDER BY MaDichVu DESC");
                            while ($row = $stmt->fetch()) {
                            ?>
                            <tr>
                                <td><?= $row['MaDichVu'] ?></td>
                                <td><?= $row['TenDichVu'] ?></td>
                                <td><?= $row['MoTa'] ?></td>
                                <td><?= number_format($row['GiaTien']) ?> VNĐ</td>
                                <td><?= $row['ThoiGian'] ?> phút</td>
                                <td>
                                    <span class="badge badge-<?= $row['TrangThai'] ? 'success' : 'danger' ?>">
                                        <?= $row['TrangThai'] ? 'Hoạt động' : 'Không hoạt động' ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm edit-service" 
                                            data-id="<?= $row['MaDichVu'] ?>"
                                            data-name="<?= $row['TenDichVu'] ?>"
                                            data-desc="<?= $row['MoTa'] ?>"
                                            data-price="<?= $row['GiaTien'] ?>"
                                            data-time="<?= $row['ThoiGian'] ?>"
                                            data-status="<?= $row['TrangThai'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete-service" 
                                            data-id="<?= $row['MaDichVu'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
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

<!-- Modal Thêm Dịch vụ -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm dịch vụ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addServiceForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">Tên dịch vụ</label>
                        <input type="text" class="form-control" name="tenDichVu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="moTa" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá tiền (VNĐ)</label>
                        <input type="number" class="form-control" name="giaTien" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời gian (phút)</label>
                        <input type="number" class="form-control" name="thoiGian" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Dịch vụ -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa dịch vụ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editServiceForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Tên dịch vụ</label>
                        <input type="text" class="form-control" name="tenDichVu" id="edit_tenDichVu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="moTa" id="edit_moTa" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá tiền (VNĐ)</label>
                        <input type="number" class="form-control" name="giaTien" id="edit_giaTien" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời gian (phút)</label>
                        <input type="number" class="form-control" name="thoiGian" id="edit_thoiGian" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-control" name="trangThai" id="edit_trangThai">
                            <option value="1">Hoạt động</option>
                            <option value="0">Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Khởi tạo DataTable
    $('#serviceTable').DataTable({
        "language": {
            "sProcessing":   "Đang xử lý...",
            "sLengthMenu":   "Xem _MENU_ mục",
            "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",
            "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
            "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
            "sInfoFiltered": "(được lọc từ _MAX_ mục)",
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

    // Xử lý thêm dịch vụ
    $('#addServiceForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'service-list.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                const data = JSON.parse(response);
                if(data.success) {
                    $('#addServiceModal').modal('hide');
                    Swal.fire('Thành công!', 'Đã thêm dịch vụ mới.', 'success')
                    .then(() => location.reload());
                } else {
                    Swal.fire('Lỗi!', data.message, 'error');
                }
            }
        });
    });

    // Hiển thị modal sửa
    $('.edit-service').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const desc = $(this).data('desc');
        const price = $(this).data('price');
        const time = $(this).data('time');
        const status = $(this).data('status');

        $('#edit_id').val(id);
        $('#edit_tenDichVu').val(name);
        $('#edit_moTa').val(desc);
        $('#edit_giaTien').val(price);
        $('#edit_thoiGian').val(time);
        $('#edit_trangThai').val(status);

        $('#editServiceModal').modal('show');
    });

    // Xử lý sửa dịch vụ
    $('#editServiceForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'service-list.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                const data = JSON.parse(response);
                if(data.success) {
                    $('#editServiceModal').modal('hide');
                    Swal.fire('Thành công!', 'Đã cập nhật dịch vụ.', 'success')
                    .then(() => location.reload());
                } else {
                    Swal.fire('Lỗi!', data.message, 'error');
                }
            }
        });
    });

    // Xử lý xóa dịch vụ
    $('.delete-service').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn không thể hoàn tác sau khi xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'service-list.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if(data.success) {
                            Swal.fire('Đã xóa!', 'Dịch vụ đã được xóa.', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi!', data.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script> 