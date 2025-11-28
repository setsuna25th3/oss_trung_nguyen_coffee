<?php
// Tải file kết nối DB (Đảm bảo file này chứa session_start(), $conn VÀ HÀM set_flash_message() nếu cần)
require_once 'db_connect.php'; 

// --- Bắt buộc: Định nghĩa tạm hàm set_flash_message nếu nó chưa có ---
if (!function_exists('set_flash_message')) {
    function set_flash_message($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_message_type'] = $type;
    }
}
// --- Hết phần định nghĩa tạm ---\r\n

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $error_message = '';

    // 1. Dùng Prepared Statements
    $stmt = $conn->prepare("SELECT Id, FirstName, LastName, Password, IsActive FROM customer WHERE Email = ?");
    
    if ($stmt === false) {
        $error_message = "Lỗi hệ thống CSDL.";
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $customer = $result->fetch_assoc();
            
            // 2. Xác thực mật khẩu đã hash
            if (password_verify($password, $customer['Password'])) {
                
                // 3. Kiểm tra trạng thái hoạt động
                if ($customer['IsActive'] == 1) {
                    // Đăng nhập THÀNH CÔNG: Chuyển hướng về trang chủ
                    $_SESSION['customer_id'] = $customer['Id'];
                    $_SESSION['customer_name'] = $customer['FirstName'] . " " . $customer['LastName'];
                    set_flash_message("Đăng nhập thành công! Chào mừng trở lại.", "success");
                    
                    header("Location: index.php");
                    exit();
                } else {
                    // Lỗi: Tài khoản chưa kích hoạt
                    $error_message = "Tài khoản của bạn chưa được kích hoạt. Vui lòng kiểm tra email.";
                }
            } else {
                // Lỗi: Sai mật khẩu
                $error_message = "Email hoặc mật khẩu không đúng.";
            }
        } else {
            // Lỗi: Email không tồn tại
            $error_message = "Email hoặc mật khẩu không đúng.";
        }
        $stmt->close();
    }

    // --- XỬ LÝ KHI ĐĂNG NHẬP THẤT BẠI ---
    if (!empty($error_message)) {
        // Lưu thông báo lỗi và email cũ vào Session Flash
        $_SESSION['login_error'] = $error_message;
        $_SESSION['old_email'] = $email; 

        // CHUYỂN HƯỚNG BẮT BUỘC
        header("Location: index.php?auth_error=login");
        exit();
    }
}
// >>> XÓA HẾT MÃ HTML CÒN LẠI DƯỚI DÒNG NÀY <<<
?>