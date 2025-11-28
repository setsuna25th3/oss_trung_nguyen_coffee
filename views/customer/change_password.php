<?php
// BẮT BUỘC phải require_once db_connect.php để có session_start() và các hàm hỗ trợ
require_once '../../db_connect.php'; 

// Yêu cầu: PHẢI ĐĂNG NHẬP MỚI ĐƯỢC VÀO TRANG NÀY
if (!isset($_SESSION['customer_id'])) {
    // Lưu thông báo yêu cầu đăng nhập và chuyển hướng về trang đăng nhập
    set_flash_message("Vui lòng đăng nhập để thay đổi mật khẩu.", "warning");
    header('Location: login.php');
    exit();
}

// BỎ việc lấy các biến session thủ công, sẽ dùng hàm display_flash_message()
?>

<?php include '../header.php'; ?>

<style>
    /* -------------------------------------------------------------------------- */
    /* CSS CHUNG VÀ THẨM MỸ TỐI ƯU                        */
    /* -------------------------------------------------------------------------- */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fcfcfc; /* Nền trắng sáng, sạch sẽ */
        color: #333;
    }

    .change-password-container {
        /* Cải thiện căn giữa theo chiều dọc */
        margin-top: 100px; /* Giữ khoảng cách với header cố định */
        margin-bottom: 60px;
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Bắt đầu từ phía trên sau header */
        padding: 20px 15px;
        min-height: calc(100vh - 100px); /* Đảm bảo đủ chiều cao */
    }

    .change-password-box {
        background: #ffffff;
        padding: 40px;
        width: 100%;
        max-width: 440px; /* Kích thước vừa phải */
        border-radius: 16px; /* Bo góc lớn hơn, hiện đại hơn */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05), 0 2px 8px rgba(0, 0, 0, 0.03); /* Bóng đổ 2 lớp tinh tế */
        border: 1px solid #eee; /* Đường viền mỏng */
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    /* Hiệu ứng nổi nhẹ khi rê chuột (UX) */
    .change-password-box:hover {
        box-shadow: 0 14px 45px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .change-password-box h2 {
        text-align: center;
        font-weight: 800; /* Đậm hơn */
        margin-bottom: 8px;
        font-size: 2.2rem;
        color: #5D4037; /* Màu nâu tối, sang trọng */
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.05);
    }

    .change-password-box p {
        color: #795548; /* Màu nâu đất nhẹ nhàng */
        font-size: 0.95rem;
        margin-bottom: 30px;
        text-align: center;
        line-height: 1.5;
    }

    .form-label {
        font-weight: 600;
        color: #4e342e;
        margin-bottom: 8px; /* Tăng khoảng cách dưới label */
        display: block;
        font-size: 0.95rem;
    }

    .form-control {
        border-radius: 10px; /* Bo góc nhẹ */
        border: 2px solid #e0e0e0; /* Đường viền rõ ràng hơn */
        padding: 14px 16px; /* Tăng padding */
        font-size: 1rem;
        transition: border-color 0.3s, box-shadow 0.3s;
        height: auto; /* Đảm bảo chiều cao linh hoạt */
    }

    
    .form-control:focus {
        border-color: #A0522D; /* Màu nâu đất chủ đạo */
        box-shadow: 0 0 0 0.3rem rgba(160, 82, 45, 0.1); /* Màu shadow mờ, hiện đại */
        background-color: #ffffff;
        outline: none;
    }

    .btn-primary {
        background-color: #A0522D;
        border-color: #A0522D;
        border-radius: 10px;
        width: 100%;
        padding: 14px;
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: background-color 0.3s, border-color 0.3s, transform 0.2s, box-shadow 0.2s;
    }

    .btn-primary:hover {
        background-color: #8D4B26; /* Màu nâu đậm hơn khi hover */
        border-color: #8D4B26;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(160, 82, 45, 0.4);
    }
    
    .btn-primary:active {
        transform: translateY(0); /* Hiệu ứng nhấn xuống (UX) */
        box-shadow: none;
        background-color: #793E1B;
        border-color: #793E1B;
    }

    /* Đảm bảo Alert hiển thị đẹp */
    .alert {
        border-radius: 10px;
        margin-bottom: 25px;
        padding: 15px;
        font-size: 1rem;
        border-left: 5px solid;
    }
    
    .alert-success {
        border-left-color: #28a745;
    }
    .alert-danger {
        border-left-color: #dc3545;
    }
    .alert-warning {
        border-left-color: #ffc107;
    }

    @media (max-width: 576px) {
        .change-password-container {
            margin-top: 80px;
            margin-bottom: 20px;
            align-items: flex-start;
            min-height: unset;
        }
        .change-password-box {
            padding: 25px 20px;
            border-radius: 0; /* Bỏ bo góc và bóng đổ trên mobile để hòa hợp với màn hình */
            box-shadow: none;
            border: none;
        }
        .change-password-box h2 {
            font-size: 1.75rem;
        }
        .form-control {
            padding: 12px 14px;
            font-size: 0.95rem;
        }
        .btn-primary {
            padding: 12px;
            font-size: 1.05rem;
        }
    }
</style>

<div class="container-fluid change-password-container">
    <div class="change-password-box">
        <h2>Đổi mật khẩu</h2>
        <p>Để đảm bảo an toàn, vui lòng nhập mật khẩu cũ và đặt mật khẩu mới có độ bảo mật cao.</p>

        <?php 
        // HIỂN THỊ THÔNG BÁO TỪ HÀM HỖ TRỢ CHUẨN TRONG db_connect.php
        display_flash_message(); 
        ?>

        <form method="post" action="change_password_process.php" id="changePasswordForm">
            <div class="mb-3">
                <label for="CurrentPassword" class="form-label">Mật khẩu cũ</label>
                <input type="password" name="CurrentPassword" id="CurrentPassword" class="form-control" placeholder="Nhập mật khẩu cũ" required>
            </div>
            
            <div class="mb-3">
                <label for="NewPassword" class="form-label">Mật khẩu mới</label>
                <input type="password" name="NewPassword" id="NewPassword" class="form-control" placeholder="Nhập mật khẩu mới" required>
            </div>
            
            <div class="mb-4">
                <label for="ConfirmPassword" class="form-label">Nhập lại mật khẩu mới</label>
                <input type="password" name="ConfirmPassword" id="ConfirmPassword" class="form-control" placeholder="Xác nhận mật khẩu mới" required>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>