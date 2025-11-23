<?php
// ========== 1. BẬT HIỂN THỊ LỖI ĐỂ DEBUG (Giữ nguyên) ==========
error_reporting(E_ALL);
ini_set('display_errors', 1);
// =======================================================

// BẮT BUỘC: Khởi tạo SESSION, kết nối DB và định nghĩa Flash Message Helpers
require_once 'db_connect.php'; 

// --- 2. TÍCH HỢP PHPMailer ---
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


// --- 3. HÀM HỖ TRỢ GỬI EMAIL VÀ TẠO OTP (Giữ nguyên) ---
function generate_otp() {
    return strval(mt_rand(100000, 999999));
}

function sendOtpEmail($recipientEmail, $otpCode) {
    // ⚠️⚠️ THÔNG TIN GMAIL SMTP CỦA BẠN (Đã thay thế) ⚠️⚠️
    $smtp_user = 'kindeptraiq@gmail.com';     
    $smtp_pass = 'ruryjqktstcecmcr'; // Mật khẩu ứng dụng 16 ký tự
    // ---------------------------------------------------
    
    $mail = new PHPMailer(true);
    try {
        // Cấu hình Server Gmail SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_user; 
        $mail->Password = $smtp_pass; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($smtp_user, 'Hệ thống Coffee Support');
        $mail->addAddress($recipientEmail);

        // Cấu hình Nội dung
        $mail->isHTML(true);
        $mail->Subject = '[TRUNG NGUYEN] Mã OTP Đặt Lại Mật Khẩu';
        $mail->Body     = "Mã OTP của bạn là: <strong>$otpCode</strong>. Mã này hết hạn sau 10 phút. Nếu bạn không yêu cầu, vui lòng bỏ qua email này.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // ⚠️ BẮT LỖI GMAIL SMTP VÀ LƯU CHI TIẾT VÀO SESSION ⚠️
        $_SESSION['email_error_detail'] = $mail->ErrorInfo;
        return false; // Gửi mail thất bại
    }
}

// --- 4. LOGIC XỬ LÝ CHÍNH (3 BƯỚC) ---

$step = $_SESSION['reset_step'] ?? 1; // Mặc định là bước 1
$email_to_reset = $_SESSION['reset_email'] ?? ''; // Lấy email đang xử lý

global $conn;

// --- XỬ LÝ BƯỚC 1: NHẬP EMAIL VÀ GỬI OTP ---
if (isset($_POST['submit_email'])) {
    $email = trim($_POST['email'] ?? '');
    
    // 1. Kiểm tra Email tồn tại
    $stmt = $conn->prepare("SELECT Id FROM customer WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        set_flash_message("Email không tồn tại trong hệ thống.", "danger");
        header("Location: forgot_password.php"); exit();
    } 
    
    // 2. Tạo OTP và Expiry Time (10 phút)
    $random_key = generate_otp();
    $expiry_time = date("Y-m-d H:i:s", time() + (10 * 60)); 
    
    // 3. Cập nhật RandomKey và TokenExpiry vào CSDL
    $stmt_update = $conn->prepare("UPDATE customer SET RandomKey = ?, TokenExpiry = ? WHERE Email = ?");
    $stmt_update->bind_param("sss", $random_key, $expiry_time, $email);
    
    if ($stmt_update->execute()) {
        // 4. Gửi Email
        if (sendOtpEmail($email, $random_key)) {
            // Gửi thành công, chuyển sang bước 2
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_step'] = 2;
            set_flash_message("Mã OTP đã được gửi đến email **" . htmlspecialchars($email) . "**. Vui lòng kiểm tra hộp thư.", "success");
        } else {
            // ⚠️ BẮT LỖI GỬI MAIL THẤT BẠI ⚠️
            $error_detail = $_SESSION['email_error_detail'] ?? "Không rõ lỗi SMTP.";
            unset($_SESSION['email_error_detail']);

            set_flash_message("Lỗi: Không thể gửi email! Vui lòng kiểm tra cấu hình SMTP. Chi tiết lỗi: **{$error_detail}**", "danger");
        }
    } else {
        // ⚠️ BẮT LỖI CSDL THẤT BẠI ⚠️
        set_flash_message("Lỗi CSDL khi lưu OTP. Chi tiết lỗi SQL: **" . $stmt_update->error . "**", "danger");
    }
    header("Location: forgot_password.php"); 
    exit();
}

// --- XỬ LÝ BƯỚC 2: XÁC THỰC OTP ---
// Chỉ xử lý nếu đang ở bước 2 và có email trong session
if (isset($_POST['otp_submit']) && $step == 2 && !empty($email_to_reset)) {
    $otp_input = trim($_POST['otp'] ?? '');

    // 1. Kiểm tra OTP, Email và THỜI GIAN HẾT HẠN
    $stmt = $conn->prepare("SELECT Id FROM customer WHERE Email = ? AND RandomKey = ? AND TokenExpiry > NOW()");
    $stmt->bind_param("ss", $email_to_reset, $otp_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // OTP hợp lệ, chuyển sang bước 3 (đổi mật khẩu)
        $_SESSION['reset_step'] = 3; 
        set_flash_message("Mã OTP hợp lệ. Vui lòng đặt mật khẩu mới.", "success");
    } else {
        // OTP không hợp lệ hoặc hết hạn
        set_flash_message("Mã OTP không chính xác hoặc đã hết hạn (10 phút). Vui lòng yêu cầu lại OTP.", "danger");
        
        // GIỮ ở bước 2 nếu lỗi xác thực, cho phép người dùng nhập lại OTP
        $_SESSION['reset_step'] = 2; 
    }
    header("Location: forgot_password.php");
    exit();
}

// --- XỬ LÝ BƯỚC 3: ĐỔI MẬT KHẨU MỚI ---
// Chỉ xử lý nếu đang ở bước 3 và có email trong session
if (isset($_POST['password_submit']) && $step == 3 && !empty($email_to_reset)) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($new_password) < 6) {
        set_flash_message("Mật khẩu phải có ít nhất 6 ký tự.", "danger");
        $_SESSION['reset_step'] = 3; // Giữ ở bước 3
    } elseif ($new_password != $confirm_password) {
        set_flash_message("Mật khẩu mới và Xác nhận mật khẩu không khớp.", "danger");
        $_SESSION['reset_step'] = 3; // Giữ ở bước 3
    } else {
        // Hash mật khẩu và Cập nhật
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Cập nhật mật khẩu và reset RandomKey/TokenExpiry về NULL
        $stmt_update = $conn->prepare("UPDATE customer SET Password = ?, RandomKey = NULL, TokenExpiry = NULL WHERE Email = ?");
        $stmt_update->bind_param("ss", $hashed_password, $email_to_reset);
        
        if ($stmt_update->execute()) {
            // Đổi mật khẩu thành công
            set_flash_message("Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập.", "success");
            
            // Xóa session reset và chuyển hướng về trang chủ để mở Modal Đăng nhập
            unset($_SESSION['reset_email']); 
            unset($_SESSION['reset_step']);
            header("Location: index.php?auth_error=login"); 
            exit();
        } else {
            set_flash_message("Lỗi CSDL khi cập nhật mật khẩu: " . $stmt_update->error, "danger");
            $_SESSION['reset_step'] = 3; // Giữ ở bước 3
        }
    }
    header("Location: forgot_password.php"); 
    exit();
}

// Cập nhật lại $step và $email_to_reset sau khi xử lý POST
$step = $_SESSION['reset_step'] ?? 1;
$email_to_reset = $_SESSION['reset_email'] ?? '';

// --- 5. PHẦN HTML/GIAO DIỆN (Giữ nguyên) ---
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu | Đặt Lại</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .auth-container { 
            max-width: 600px; 
            margin: 50px auto; 
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background-color: #8b4513; /* Màu nâu cà phê */
            border-color: #8b4513;
            color: white;
            width: 100%;
        }
        .btn-custom:hover {
            background-color: #a0522d;
            border-color: #a0522d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="card p-4 ">
                <div class="text-center mb-4">
                    <h2 class="mb-3">
                        <?php 
                            if ($step == 1) echo "Quên Mật Khẩu"; 
                            elseif ($step == 2) echo "Xác Thực OTP"; 
                            else echo "Đặt Mật Khẩu Mới"; 
                        ?>
                    </h2>
                    <?php if (!empty($email_to_reset) && $step > 1): ?>
                        <p class="text-muted">Tài khoản: **<?php echo htmlspecialchars($email_to_reset); ?>**</p>
                    <?php endif; ?>
                </div>
                
                <?php 
                // Hiển thị thông báo
                display_flash_message(); 
                ?>

                <?php if ($step == 1): ?>
                    <form method="POST" action="forgot_password.php">
                        <p class="text-info text-center">Nhập email của bạn để nhận Mã OTP.</p>
                        <div class="mb-3">
                            <label for="email" class="form-label">Địa chỉ Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" name="submit_email" class="btn btn-lg btn-custom mt-3">Gửi Mã OTP</button>
                    </form>
                
                <?php elseif ($step == 2): ?>
                    <form method="POST" action="forgot_password.php">
                        <p class="text-info text-center">Mã OTP đã được gửi. Vui lòng nhập mã 6 chữ số.</p>
                        <div class="mb-3">
                            <label for="otp" class="form-label">Mã OTP (6 chữ số)</label>
                            <input type="text" class="form-control text-center" id="otp" name="otp" required pattern="\d{6}" title="Mã OTP phải là 6 chữ số" maxlength="6">
                        </div>
                        <button type="submit" name="otp_submit" class="btn btn-lg btn-custom mt-3">Xác Nhận OTP</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="?action=resend" onclick="event.preventDefault(); window.location.href='forgot_password.php?action=resend';" class="text-decoration-none">Yêu cầu lại Mã OTP</a>
                    </div>

                <?php elseif ($step == 3): ?>
                    <form method="POST" action="forgot_password.php">
                        <p class="text-success text-center">Mã OTP hợp lệ. Vui lòng nhập mật khẩu mới.</p>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới (Tối thiểu 6 ký tự)</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                        </div>
                        <button type="submit" name="password_submit" class="btn btn-lg btn-custom mt-3">Đổi Mật Khẩu</button>
                    </form>
                <?php endif; ?>
                
                <div class="text-center mt-3">
                    <a href="index.php" class="text-decoration-none">Quay lại Trang Chủ</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>