<?php

// DÒNG NÀY PHẢI Ở ĐẦU TIÊN
// Bắt đầu Output Buffering để tránh lỗi Header đã được gửi.
ob_start(); 

// BẮT BUỘC: Khởi động session nếu chưa có để thao tác với $_SESSION/session_destroy()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../db_connect.php'; 
require_once '../../models/Customer.php';

// Giả định hàm set_flash_message đã được định nghĩa ở đâu đó

// Kéo biến $conn về phạm vi cục bộ (dù đã là global trong db_connect nhưng nên khai báo)
global $conn; 

// --- 1. Xóa Cookie Remember Me và Cập nhật DB ---
if (isset($_COOKIE['remember_user']) || isset($_COOKIE['remember_key'])) {
    // Lấy userId một cách an toàn
    $userId = $_COOKIE['remember_user'] ?? null; 
    
    // Nếu có ID người dùng từ cookie, cập nhật RandomKey = NULL trong CSDL
    if (!empty($userId) && is_numeric($userId)) {
        
        $updateStmt = $conn->prepare("UPDATE customer SET RandomKey = NULL WHERE Id = ?");
        
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
    // Đặt thời gian hết hạn trong quá khứ
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // Hủy session hoàn toàn

// --- 3. Chuyển hướng ---
// Đảm bảo hàm set_flash_message đã được định nghĩa và có thể truy cập được
set_flash_message("Bạn đã đăng xuất thành công.", "success"); 
header('Location: ../home/index.php'); 
ob_end_flush(); 
exit(); 

?>