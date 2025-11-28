<?php
// File này chỉ chứa form HTML để load vào Modal
// Logic xử lý form nên được chuyển sang file action.php hoặc tự xử lý tại đây
// Lưu ý: Nếu xử lý file upload (image) trong modal bằng AJAX sẽ phức tạp hơn, 
// nên nếu dùng PHP thuần, bạn có thể để form POST sang file register_action.php
?>

<div class="text-center mb-3">
    <h5>Tạo tài khoản mới</h5>
</div>
<form method="POST" action="register.php" enctype="multipart/form-data">
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="firstName" class="form-label visually-hidden">Tên</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Tên *" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="lastName" class="form-label visually-hidden">Họ</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Họ *" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label visually-hidden">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Địa chỉ Email *" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label visually-hidden">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu *" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="confirmPassword" class="form-label visually-hidden">Xác nhận Mật khẩu</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Xác nhận Mật khẩu *" required>
        </div>
    </div>
    
    <div class="mb-3">
        <label for="dateOfBirth" class="form-label">Ngày Sinh</label>
        <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth">
    </div>

    <div class="mb-3">
        <label for="address" class="form-label visually-hidden">Địa Chỉ</label>
        <textarea class="form-control" id="address" name="address" rows="2" placeholder="Địa Chỉ"></textarea>
    </div>
    
    <div class="mb-3">
        <label for="Img" class="form-label">Ảnh Đại Diện (tùy chọn)</label>
        <input type="file" class="form-control" id="Img" name="Img">
    </div>
    
    <button type="submit" class="btn btn-lg btn-custom mt-3">Đăng Ký Tài Khoản</button>

    <div class="text-center mt-3">
    Đã có tài khoản? 
    <a href="#" class="auth-switch" data-target-mode="login">Đăng nhập ngay</a>
</div>
</form>