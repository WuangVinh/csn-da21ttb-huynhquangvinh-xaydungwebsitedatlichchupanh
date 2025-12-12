<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $conn = $database->getConnection();
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM NguoiDung WHERE (TenDangNhap = ? OR Email = ?) AND MatKhau = ?");
        $stmt->execute([$username, $username, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['user'] = [
                'MaNguoiDung' => $user['MaNguoiDung'],
                'HoTen' => $user['HoTen'],
                'Email' => $user['Email'],
                'VaiTro' => $user['VaiTro']
            ];
            
            // Kiểm tra vai trò và chuyển hướng
            if ($user['VaiTro'] == 'Admin') {
                // Nếu là admin, chuyển đến trang admin
                header('Location: admin/index.php');
            } else {
                // Nếu là khách hàng, chuyển về trang chủ
                header('Location: index.php');
            }
            exit;
        } else {
            $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
?>
