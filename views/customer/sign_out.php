<?php

// Bắt đầu Output Buffering để tránh lỗi Header đã được gửi.
// DÒNG NÀY PHẢI Ở ĐẦU TIÊN
ob_start(); 

require_once '../../db_connect.php'; 
require_once '../../models/Customer.php';

// Giả định hàm set_flash_message đã được định nghĩa ở đâu đó
// và SESSION đã được khởi tạo (session_start()) trước khi chạy ob_start()

global $conn;

// --- 1. Xóa Cookie Remember Me và Cập nhật DB ---
if (isset($_COOKIE['remember_user']) || isset($_COOKIE['remember_key'])) {
    $userId = $_COOKIE['remember_user'] ?? null;
    
    // Nếu có ID người dùng từ cookie, cập nhật RandomKey = NULL trong CSDL
    if (!empty($userId) && is_numeric($userId)) {
        // Sử dụng $conn từ db_connect.php
        $updateStmt = $conn->prepare("UPDATE customer SET RandomKey = NULL WHERE Id = ?");
        // Giả định $updateStmt thành công, nếu không sẽ báo lỗi
        if ($updateStmt) { 
             $updateStmt->bind_param("i", $userId);
             $updateStmt->execute();
             $updateStmt->close();
        }
    }
    
    // Xóa cookie bằng cách đặt thời gian hết hạn trong quá khứ
    setcookie('remember_user', '', time() - 3600, "/");
    setcookie('remember_key', '', time() - 3600, "/");
}

// --- 2. Hủy Session Hiện tại ---
$_SESSION = array(); // Xóa tất cả dữ liệu session

// Xóa cookie session (nếu đang dùng)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // Hủy session

// --- 3. Chuyển hướng ---
set_flash_message("Bạn đã đăng xuất thành công.", "success");
header('Location: ../home/index.php'); 
ob_end_flush(); // Kết thúc và gửi output buffer
exit(); // Dừng thực thi script ngay lập tức

?>