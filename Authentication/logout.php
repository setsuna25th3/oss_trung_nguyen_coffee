<?php
// 1. TẢI CÁC FILE CẦN THIẾT
// require_once 'db_connect.php' để: 
// a) Khởi tạo Session (nhờ vào session_start() ở cuối db_connect.php)
// b) Khắc phục lỗi "Trying to destroy uninitialized session"
require_once 'db_connect.php'; 

// 2. ĐỊNH NGHĨA TẠM HÀM set_flash_message()
// Vì bạn chưa có file functions.php chung, ta định nghĩa tạm ở đây.
// LƯU Ý: Đây là phương pháp tạm thời. Bạn nên tạo file functions.php và đưa hàm này vào đó.
if (!function_exists('set_flash_message')) {
    function set_flash_message($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_message_type'] = $type;
    }
}

// --- BẮT ĐẦU QUÁ TRÌNH ĐĂNG XUẤT AN TOÀN ---

// 1. Hủy tất cả các biến Session hiện có
$_SESSION = array();

// 2. Xóa Session Cookie trên trình duyệt
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hủy Session hoàn toàn trên server
session_destroy(); 

// 4. Thiết lập Flash Message thông báo thành công
set_flash_message("Bạn đã đăng xuất thành công.", "success");

// 5. Chuyển hướng người dùng về trang chủ (index.php)
// Đã sửa đường dẫn thành "index.php"
header("Location: index.php"); 
exit();
?>