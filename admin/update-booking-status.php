<?php
require_once '../config/database.php';
session_start();

if(!isset($_SESSION['admin'])) {
    die('Unauthorized');
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $conn = $database->getConnection();
    
    $maDatLich = $_POST['id'];
    $trangThai = $_POST['status'];
    
    try {
        $stmt = $conn->prepare("UPDATE DatLich SET TrangThai = ? WHERE MaDatLich = ?");
        $stmt->execute([$trangThai, $maDatLich]);
        
        // Gửi email thông báo cho khách
        $stmt = $conn->prepare("
            SELECT dl.*, kh.Email, kh.HoTen 
            FROM DatLich dl 
            JOIN KhachHang kh ON dl.MaKhachHang = kh.MaKhachHang 
            WHERE dl.MaDatLich = ?
        ");
        $stmt->execute([$maDatLich]);
        $booking = $stmt->fetch();
        
        require_once 'includes/send-mail.php';
        sendStatusUpdateEmail($booking['Email'], [
            'hoTen' => $booking['HoTen'],
            'maDatLich' => $booking['MaDatLich'],
            'trangThai' => $trangThai
        ]);
        
        echo json_encode(['success' => true]);
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
