<?php
// Bắt buộc phải có session_start() để đọc lỗi. 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Lấy thông báo lỗi và email cũ từ Session
$error_message = $_SESSION['login_error'] ?? null;
$old_email = $_SESSION['old_email'] ?? '';

// 2. Xóa session ngay sau khi lấy để chỉ hiển thị 1 lần (Flash)
unset($_SESSION['login_error']); 
unset($_SESSION['old_email']);
?>

<div class="text-center mb-3">
    <h5>Vui lòng đăng nhập</h5>
</div>

<?php if ($error_message): ?>
    <div class="alert alert-danger mb-3"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>

<form method="POST" action="login.php">
    <div class="mb-3">
        <input type="email" class="form-control" name="email" placeholder="Email" 
               value="<?php echo htmlspecialchars($old_email); ?>" required>
    </div>
    <div class="mb-3">
        <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>
    </div>
    <div class="d-flex justify-content-end mb-3">
    <a href="forgot_password.php" class="text-decoration-none">Quên Mật Khẩu?</a>
</div>
    
    <button type="submit" class="btn btn-lg btn-custom">Đăng Nhập</button>
    <div class="text-center mt-3">
        Chưa có tài khoản? 
        <a href="#" class="auth-switch" data-target-mode="register">Đăng ký ngay</a>
    </div>
</form>