<?php
require_once '../../db_connect.php'; 
require_once '../../models/Customer.php';

// --- Xóa Cookie Remember Me ---
if (isset($_COOKIE['remember_user']) || isset($_COOKIE['remember_key'])) {
    // Xóa randomKey trong CSDL (tùy chọn, để tăng tính bảo mật)
    $userId = $_COOKIE['remember_user'] ?? null;
    if ($userId) {
        $updateStmt = $conn->prepare("UPDATE customer SET RandomKey = NULL WHERE Id = ?");
        $updateStmt->bind_param("i", $userId);
        $updateStmt->execute();
        $updateStmt->close();
    }
    
    // Xóa cookie bằng cách đặt thời gian hết hạn trong quá khứ
    setcookie('remember_user', '', time() - 3600, "/");
    setcookie('remember_key', '', time() - 3600, "/");
}

// --- Hủy Session ---
$_SESSION = array(); // Xóa tất cả dữ liệu session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // Hủy session

// Chuyển hướng về trang chủ hoặc đăng nhập
set_flash_message("Bạn đã đăng xuất thành công.", "success");
header('Location: ../home/index.php'); 
exit();
?>