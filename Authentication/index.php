<?php
// BẮT BUỘC: Tải file kết nối DB (chứa session_start(), $conn, và các hàm Flash Message)
require_once 'db_connect.php'; 

// Hàm kiểm tra trạng thái đăng nhập
$is_logged_in = isset($_SESSION['customer_id']);
$customer_name = $is_logged_in ? $_SESSION['customer_name'] : 'Khách hàng';

// Nếu bạn có định nghĩa display_flash_message() TẠI ĐÂY (như file cũ), HÃY XÓA NÓ ĐI.
// Hàm này đã được chuyển sang db_connect.php để tránh lỗi.
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ & Đăng Nhập Modal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* GIỮ NGUYÊN CSS CỦA BẠN */
        .modal-content {
            border-radius: 10px;
        }
        /* ... CSS khác ... */
        .btn-custom {
            background-color: #8b4513;
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
    
    <?php display_flash_message(); ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Trung Nguyên Coffee</a>
            <ul class="navbar-nav ml-auto">
                <?php if ($is_logged_in): ?>
                    <li class="nav-item">
                        <span class="nav-link text-success">Xin chào, <?php echo htmlspecialchars($customer_name); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-outline-danger" href="logout.php">Đăng Xuất</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal" data-auth-mode="login">Đăng Nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal" data-auth-mode="register">Đăng Ký</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="alert alert-info text-center">
            <h1>Chào mừng đến với Trang Chủ</h1>
            <p>Đây là nội dung chính của website.</p>
        </div>
    </div>


    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="authContent">
                    <div class="text-center">Đang tải form...</div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        /**
         * Hàm tải form Đăng nhập/Đăng ký vào Modal
         * @param {string} mode - 'login' hoặc 'register'
         */
        function loadAuthForm(mode) {
            const authContent = document.getElementById('authContent');
            const url = mode === 'login' ? 'form_login.php' : 'form_register.php';

            authContent.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Đang tải...</p></div>';

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Không thể tải form ' + mode);
                    }
                    return response.text();
                })
                .then(html => {
                    authContent.innerHTML = html;
                })
                .catch(error => {
                    authContent.innerHTML = `<div class="alert alert-danger">Lỗi: ${error.message}. Vui lòng thử lại.</div>`;
                });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const authModalElement = document.getElementById('authModal');
            const authModal = new bootstrap.Modal(authModalElement);
            const urlParams = new URLSearchParams(window.location.search);
            const authContent = document.getElementById('authContent');
            
            // --- 1. XỬ LÝ TỰ ĐỘNG MỞ MODAL KHI CÓ CỜ TỪ URL (QUÊN MẬT KHẨU/ĐĂNG KÝ THÀNH CÔNG) ---
            const isRegisteredSuccess = urlParams.get('registered') === 'true';
            const authErrorMode = urlParams.get('auth_error'); // Chứa 'login' từ reset_password.php/login.php
            
            if (isRegisteredSuccess || authErrorMode) {
                 // Luôn tải Form Đăng nhập để người dùng đăng nhập lại
                 if (typeof loadAuthForm === 'function') {
                     loadAuthForm('login'); 
                 } 
                 
                 // Tự động hiển thị Modal
                 authModal.show();
                 
                 // Xóa cờ khỏi URL để tránh modal tự mở lại khi refresh
                 const newUrl = location.pathname + location.search
                     .replace(/(&|\?)auth_error=[^&]*/, '')
                     .replace(/(&|\?)registered=[^&]*/, '');
                     
                 // Kiểm tra nếu chỉ còn dấu '?' lẻ loi thì xóa nốt
                 const finalUrl = newUrl.endsWith('?') ? newUrl.slice(0, -1) : newUrl;
                 history.replaceState(null, '', finalUrl);
            }

            // --- 2. SỰ KIỆN KÍCH HOẠT MODAL BAN ĐẦU (TỪ NAVBAR) ---
            authModalElement.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                // Lấy data-auth-mode: login hoặc register
                const mode = button.getAttribute('data-auth-mode');
                
                // Tải form dựa trên nút được nhấn
                loadAuthForm(mode);
            });

            // --- 3. EVENT DELEGATION (Xử lý chuyển đổi form trong Modal) ---
            // Đảm bảo click vào link "auth-switch" sẽ chuyển đổi form
            authContent.addEventListener('click', function(e) {
                if (e.target.classList.contains('auth-switch')) {
                    e.preventDefault();
                    const newMode = e.target.getAttribute('data-target-mode');
                    
                    // Tải form mới
                    loadAuthForm(newMode);
                }
                
                // Xử lý link Quên Mật Khẩu (nếu bạn đặt nó trong form_login.php)
                if (e.target.id === 'forgotPasswordLink') {
                     // Nếu người dùng click vào link quên mật khẩu trong modal
                     e.preventDefault();
                     window.location.href = 'forgot_password.php';
                }
            });

        });
    </script>
</body>
</html>