<?php
// THÔNG TIN KẾT NỐI CSDL
DEFINE('DB_USER','root');
DEFINE('DB_PASSWORD','');
DEFINE('DB_HOST','localhost');
DEFINE('DB_NAME','cafe_trungnguyen');
DEFINE('DB_PORT',3307);

// KẾT NỐI
$conn=@mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT)
    or die ('khong the ket noi'.@mysqli_connect_error());

mysqli_set_charset($conn,'utf8');    

// BẮT ĐẦU SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// HÀM HỖ TRỢ LƯU FLASH MESSAGE
if (!function_exists('set_flash_message')) {
    function set_flash_message($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_message_type'] = $type;
    }
}

// HÀM HỖ TRỢ HIỂN THỊ FLASH MESSAGE (chỉ cần gọi 1 lần ở phần hiển thị HTML)
if (!function_exists('display_flash_message')) {
    function display_flash_message() {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            $type = $_SESSION['flash_message_type'] ?? 'info';
            
            // Hiển thị thông báo bằng Bootstrap Alert
            echo "<div class='container mt-3'>";
            echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>";
            echo "<strong>Thông báo:</strong> " . htmlspecialchars($message);
            echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
            echo "</div>";
            echo "</div>";
            
            // Xóa thông báo khỏi session sau khi hiển thị
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_message_type']);
        }
    }
}
?>