<?php
require_once '../../db_connect.php'; 
require_once '../../models/Customer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Nếu là GET, người dùng đến từ email. Logic này sẽ được đặt trong resetpassword.php.
    
    // Nếu là POST, xử lý đặt lại mật khẩu.
    $newPassword = $_POST['NewPassword'] ?? '';
    $confirmPassword = $_POST['ConfirmPassword'] ?? '';
    $email = $_POST['Email'] ?? '';
    // Thường token sẽ được truyền qua input hidden từ resetpassword.php
    $token = $_POST['Token'] ?? ''; 

    $errors = [];

    // --- 1. Xác thực mật khẩu mới ---
    if (strlen($newPassword) < 6) {
        $errors[] = "Mật khẩu mới phải có ít nhất 6 ký tự.";
    }
    if ($newPassword !== $confirmPassword) {
        $errors[] = "Mật khẩu mới và Nhập lại mật khẩu không khớp.";
    }

    if (empty($errors)) {
        // 2. Lấy dữ liệu người dùng và kiểm tra token/thời gian hết hạn
        // CẦN LƯU Ý: Lấy email và token từ input hidden của form
        $stmt = $conn->prepare("SELECT Id, RandomKeyExpiresAt FROM customer WHERE Email = ? AND RandomKey = ? AND IsActive = 1");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $customerData = $result->fetch_assoc();
        $stmt->close();
        
        if (!$customerData) {
            $errors[] = "Token khôi phục không hợp lệ.";
        } else if ($customerData['RandomKeyExpiresAt'] < time()) {
            $errors[] = "Liên kết khôi phục đã hết hạn. Vui lòng yêu cầu lại.";
        }
        
        // 3. Đặt lại mật khẩu
        if (empty($errors)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Xóa token sau khi sử dụng
            $updateStmt = $conn->prepare("UPDATE customer SET Password = ?, RandomKey = NULL, RandomKeyExpiresAt = NULL WHERE Id = ?");
            $updateStmt->bind_param("si", $hashedPassword, $customerData['Id']);
            
            if ($updateStmt->execute()) {
                $_SESSION['SignInSuccessMessage'] = "Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập bằng mật khẩu mới.";
                header('Location: login.php');
                exit();
            } else {
                $errors[] = "Lỗi hệ thống khi cập nhật mật khẩu.";
            }
            $updateStmt->close();
        }
    }
    
    // Báo lỗi và quay lại form reset password
    if (!empty($errors)) {
        $_SESSION['ResetPasswordErrorMessage'] = implode('<br>', $errors);
        $_SESSION['ResetEmail'] = $email; // Giữ lại email để tiện cho người dùng
        header('Location: resetpassword.php?token=' . urlencode($token) . '&email=' . urlencode($email));
        exit();
    }
} else {
     // Đảm bảo truy cập bằng POST
    header('Location: forgotpassword.php');
    exit();
}
?>