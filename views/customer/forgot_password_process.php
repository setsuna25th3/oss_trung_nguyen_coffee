<?php
// BẬT HIỂN THỊ LỖI (TẠM THỜI)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cập nhật đường dẫn đã xác định
require_once '../../db_connect.php'; 
require_once '../../models/Customer.php';
$mailConfig = require_once '../../config/mail.php';

// Yêu cầu thư viện gửi mail: Cần thay đổi đường dẫn này cho đúng với vị trí file PHPMailer của bạn
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Nạp thư viện PHPMailer (đặt tại /vendo/PHPMailer/PHPMailer-master)
$phpMailerBasePath = __DIR__ . '/../../vendo/PHPMailer/PHPMailer-master/src/';
require_once $phpMailerBasePath . 'Exception.php';
require_once $phpMailerBasePath . 'PHPMailer.php';
require_once $phpMailerBasePath . 'SMTP.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgotpassword.php');
    exit();
}

$email = trim($_POST['Email'] ?? '');
$errors = [];

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Vui lòng nhập Email hợp lệ.";
}

if (empty($errors)) {
    // 1. Kiểm tra Email có tồn tại không
    $stmt = $conn->prepare("SELECT Id, FirstName, LastName FROM customer WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Thông báo thành công giả để tránh lộ thông tin email
        $_SESSION['ForgotPasswordSuccessMessage'] = "Nếu email này tồn tại trong hệ thống, một liên kết khôi phục đã được gửi.";
        header('Location: forgotpassword.php');
        exit();
    }
    
    $customerData = $result->fetch_assoc();
    $customerId = $customerData['Id'];
    $customerName = $customerData['LastName'] . ' ' . $customerData['FirstName'];
    $stmt->close();
    
    // 2. Tạo và Lưu RandomKey (Token Khôi phục)
    
    // **KHẮC PHỤC LỖI PHP VERSION CŨ:** Thay thế random_bytes()
    if (function_exists('random_bytes')) {
        $resetToken = bin2hex(random_bytes(32));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $resetToken = bin2hex(openssl_random_pseudo_bytes(32));
    } else {
        $resetToken = md5(uniqid(mt_rand(), true));
    }
    
    $expiresAt = time() + (60 * 30); // Token hết hạn sau 30 phút
    $expiresAtStr = date('Y-m-d H:i:s', $expiresAt);
    
    $updateStmt = $conn->prepare("UPDATE customer SET RandomKey = ?, TokenExpiry = ? WHERE Id = ?");
    if (!$updateStmt) {
        $_SESSION['ForgotPasswordErrorMessage'] = "Lỗi hệ thống khi chuẩn bị truy vấn: " . $conn->error;
        header('Location: forgotpassword.php');
        exit();
    }
    $updateStmt->bind_param("ssi", $resetToken, $expiresAtStr, $customerId);

    if ($updateStmt->execute()) {
        // 3. Chuẩn bị và Gửi Email bằng PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Cấu hình máy chủ SMTP
            $mail->isSMTP();
            $mail->Host       = $mailConfig['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailConfig['username'];
            $mail->Password   = $mailConfig['password'];
            $mail->SMTPSecure = $mailConfig['encryption'] === 'tls'
                ? PHPMailer::ENCRYPTION_STARTTLS
                : PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $mailConfig['port'];

            // Cấu hình người gửi và người nhận
            $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
            $mail->addAddress($email, $customerName);
            $mail->CharSet = 'UTF-8';

            // Thiết lập nội dung Email
            $separator = strpos($mailConfig['base_reset_url'], '?') === false ? '?' : '&';
            $resetLink = $mailConfig['base_reset_url'] . $separator . 'token=' . urlencode($resetToken) . "&email=" . urlencode($email);
            
            $mail->isHTML(true);
            $mail->Subject = "Yêu cầu đặt lại mật khẩu của bạn";
            $mail->Body    = "
                <p>Xin chào <strong>$customerName</strong>,</p>
                <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng nhấp vào liên kết dưới đây để tiếp tục:</p>
                <p><a href='$resetLink' style='background-color: #A0522D; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>ĐẶT LẠI MẬT KHẨU</a></p>
                <p>Liên kết này sẽ hết hạn sau **30 phút**.</p>
                <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
                <br>
                <p>Trân trọng,</p>
                <p>Đội ngũ Hỗ trợ Trung Nguyen Coffee.</p>
            ";
            $mail->AltBody = "Bạn đã yêu cầu đặt lại mật khẩu. Vui lòng nhấp vào liên kết sau: $resetLink (Liên kết hết hạn sau 30 phút).";

            $mail->send();
            
            $_SESSION['ForgotPasswordSuccessMessage'] = "Chúng tôi đã gửi một Email kèm theo hướng dẫn khôi phục mật khẩu đến $email. Vui lòng kiểm tra hộp thư (cả thư mục Spam).";
        
        } catch (Exception $e) {
            // Lỗi gửi email
            $_SESSION['ForgotPasswordErrorMessage'] = "Lỗi gửi email: Vui lòng kiểm tra cấu hình SMTP. Chi tiết lỗi: {$mail->ErrorInfo}";
            // Ghi log lỗi để kiểm tra, không hiển thị cho người dùng cuối
            // error_log("PHPMailer Error: " . $e->getMessage());
        }

    } else {
        $_SESSION['ForgotPasswordErrorMessage'] = "Lỗi hệ thống khi tạo token. Vui lòng thử lại.";
    }
    $updateStmt->close();
} else {
    $_SESSION['ForgotPasswordErrorMessage'] = implode('<br>', $errors);
}

header('Location: forgotpassword.php');
exit();
?>