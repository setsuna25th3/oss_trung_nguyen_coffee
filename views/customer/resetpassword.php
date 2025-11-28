<?php
session_start();

$tokenFromUrl = $_GET['token'] ?? '';
$emailFromUrl = $_GET['email'] ?? '';

// Giả lập dữ liệu thông báo nếu có
$resetSuccessMessage = $_SESSION['ResetPasswordSuccessMessage'] ?? '';
unset($_SESSION['ResetPasswordSuccessMessage']);

$resetErrorMessage = $_SESSION['ResetPasswordErrorMessage'] ?? '';
unset($_SESSION['ResetPasswordErrorMessage']);

// Sử dụng email từ URL hoặc session
$email = $emailFromUrl ?: ($_SESSION['ResetEmail'] ?? '');
unset($_SESSION['ResetEmail']); // Xóa sau khi dùng

// Nếu không có token và email từ URL, và không có lỗi trong session, chuyển hướng về trang quên mật khẩu
if (empty($tokenFromUrl) && empty($resetErrorMessage)) {
    // Nếu có token và email từ URL, sử dụng nó làm giá trị ẩn
    // Nếu không có, form sẽ không hợp lệ nếu chưa qua forgot_password_process.php
    // Cần kiểm tra token và email trước khi cho người dùng nhập mật khẩu
    
    // Tạm thời, dùng token từ URL hoặc rỗng nếu không có
    $token = $tokenFromUrl; 
    
    // **Trong môi trường thực, cần thêm logic kiểm tra token/email hợp lệ ở đây bằng DB**
}
?>


<?php include '../header.php'; ?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fff1e0;
        color: #333;
    }

    .reset-container {
        margin-top: 180px;
        margin-bottom: 40px;
        display: flex;
        justify-content: center;
    }

    .reset-box {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        padding: 30px;
        width: 100%;
        max-width: 380px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .reset-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
    }

    .reset-box h2 {
        text-align: center;
        font-weight: 700;
        margin-bottom: 25px;
        color: #343a40;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 12px;
        border: 1px solid #ced4da;
        width: 100%;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
        border-color: #ffb300;
        box-shadow: 0 0 0 0.25rem rgba(255, 179, 0, 0.3);
    }

    .btn-primary {
        background-color: #ffb300;
        border-color: #ffb300;
        padding: 12px;
        width: 100%;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background-color: #ff9800;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.35);
    }

    .alert {
        border-radius: 10px;
        margin-bottom: 15px;
        padding: 12px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

<div class="container-fluid reset-container">
    <div class="reset-box">
        <h2>Cấp lại mật khẩu</h2>

        <?php if (!empty($resetSuccessMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <strong>Thành công!</strong> <?= htmlspecialchars($resetSuccessMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($resetErrorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Lỗi!</strong> <?= htmlspecialchars($resetErrorMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="reset_password_process.php">
            <table>
                <tr>
                    <td><label for="NewPassword">Mật khẩu mới</label></td>
                    <td><input type="password" name="NewPassword" id="NewPassword" class="form-control" required></td>
                </tr>
                <tr>
                    <td><label for="ConfirmPassword">Nhập lại mật khẩu</label></td>
                    <td><input type="password" name="ConfirmPassword" id="ConfirmPassword" class="form-control" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="Email" value="<?= htmlspecialchars($email) ?>">
                        <button type="submit" class="btn btn-primary mt-3">Xác nhận</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>