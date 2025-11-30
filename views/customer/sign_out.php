<?php
// Bắt đầu Output Buffering để tránh lỗi Header đã được gửi.
// DÒNG NÀY PHẢI Ở ĐẦU TIÊN
ob_start(); 

require_once '../../db_connect.php'; 
require_once '../../models/Customer.php';

global $conn;

// --- Xóa Cookie Remember Me và Cập nhật DB ---
if (isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_key'])) {
    $userId = $_COOKIE['remember_user'];
    
    // Xóa randomKey trong CSDL 
    // Chỉ cập nhật nếu user tồn tại trong cookie
    if (!empty($userId) && is_numeric($userId)) {
        $updateStmt = $conn->prepare("UPDATE customer SET RandomKey = NULL WHERE Id = ?");
        $updateStmt->bind_param("i", $userId);
        $updateStmt->execute();
        $updateStmt->close();
    }
    
    // Xóa cookie bằng cách đặt thời gian hết hạn trong quá khứ
    setcookie('remember_user', '', time() - 3600, "/");
    setcookie('remember_key', '', time() - 3600, "/");
}

// --- Hủy Session Hiện tại ---
$_SESSION = array(); // Xóa tất cả dữ liệu session

// Xóa cookie session (tùy chọn)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // Hủy session

// Chuyển hướng về trang chủ
set_flash_message("Bạn đã đăng xuất thành công.", "success");
header('Location: ../home/index.php'); 
ob_end_flush(); // Kết thúc và gửi output buffer
exit(); // Dừng thực thi script ngay lập tức