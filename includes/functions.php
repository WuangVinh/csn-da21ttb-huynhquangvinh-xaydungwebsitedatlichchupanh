<?php
function getDichVuByDanhMuc($conn, $maDanhMuc) {
    $query = "SELECT * FROM DichVu WHERE MaDanhMuc = ? AND TrangThai = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute([$maDanhMuc]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLatestGallery($conn, $limit = 8) {
    $query = "SELECT a.*, d.TenDanhMuc 
              FROM ThuVienAnh a 
              JOIN DanhMuc d ON a.MaDanhMuc = d.MaDanhMuc 
              WHERE a.TrangThai = 1 
              ORDER BY a.NgayDang DESC 
              LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createBooking($conn, $data) {
    try {
        $conn->beginTransaction();
        
        // Thêm thông tin khách hàng
        $queryKH = "INSERT INTO KhachHang (HoTen, Email, SoDienThoai) VALUES (?, ?, ?)";
        $stmtKH = $conn->prepare($queryKH);
        $stmtKH->execute([$data['hoTen'], $data['email'], $data['soDienThoai']]);
        $maKhachHang = $conn->lastInsertId();
        
        // Thêm đơn đặt lịch
        $queryDL = "INSERT INTO DatLich (MaKhachHang, MaDichVu, NgayChup, GioChup, TongTien, TrangThai) 
                    VALUES (?, ?, ?, ?, ?, 'ChoXacNhan')";
        $stmtDL = $conn->prepare($queryDL);
        $stmtDL->execute([
            $maKhachHang,
            $data['maDichVu'],
            $data['ngayChup'],
            $data['gioChup'],
            $data['tongTien']
        ]);
        
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        return false;
    }
}

function getServiceImage($service) {
    switch($service['MaDichVu']) {
        // Dịch vụ cưới
        case 1: return 'anh-cuoi-co-ban.JPG';
        case 2: return 'anh-cuoi-cao-cap.JPG';
        case 3: return 'anh-cuoi-dac-biet.JPG';
        
        // Dịch vụ gia đình
        case 4: return 'gia-dinh-co-ban.JPG';
        case 5: return 'gia-dinh-cao-cap.JPG';
        case 6: return 'gia-dinh-dac-biet.JPG';
        
        // Dịch vụ sự kiện
        case 7: return 'su-kien-2h.JPG';
        case 8: return 'su-kien-4h.JPG';
        case 9: return 'su-kien-ca-ngay.JPG';
        
        // Dịch vụ kỷ yếu
        case 10: return 'ky-yeu-co-ban.JPG';
        case 11: return 'ky-yeu-nang-cao.JPG';
        case 12: return 'ky-yeu-cao-cap.JPG';
        
        default: return 'default.jpg';
    }
}