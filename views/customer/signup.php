<?php
session_start();

// Giả lập dữ liệu lỗi nếu có
$signUpErrorMessage = $_SESSION['SignUpErrorMessage'] ?? '';
unset($_SESSION['SignUpErrorMessage']); // Xóa sau khi hiển thị
?>

<?php include '../header.php'; ?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fff1e0;
        color: #333;
    }

    .signup-container {
        margin-top: 180px;
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
    }

    .signup-table {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        padding: 30px;
        width: 100%;
        max-width: 380px;
        margin: 0 auto;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .signup-table:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .signup-table h2 {
        text-align: center;
        margin-bottom: 25px;
        font-weight: 700;
        color: #343a40;
    }

    .signup-table table {
        width: 100%;
    }

    .signup-table td {
        padding: 12px;
        vertical-align: middle;
    }

    .signup-table label {
        font-weight: 600;
        color: #37474f;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
        border-color: #ffb300;
        box-shadow: 0 0 0 0.25rem rgba(255, 179, 0, 0.25);
    }

    .btn-primary {
        background-color: #ffb300;
        border-color: #ffb300;
        border-radius: 30px;
        padding: 12px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background-color: #ff9800;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }

    .alert {
        border-radius: 10px;
        margin-bottom: 15px;
        padding: 12px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    #image_preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-upload {
        border-radius: 50px;
        padding: 5px 20px;
        font-size: 14px;
    }

    .forgot-link {
        color: #ffb300;
        text-decoration: none;
    }

    .forgot-link:hover {
        color: #ff9800;
        text-decoration: underline;
    }

    .social-btn {
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        margin: 0 5px;
        border-width: 2px;
        transition: transform 0.3s, border-color 0.3s;
    }

    .social-btn:hover {
        transform: scale(1.15);
    }

    .btn-outline-facebook {
        color: #3b5998;
        border-color: #3b5998;
    }

    .btn-outline-facebook:hover {
        background-color: #3b5998;
        color: white;
    }

    .btn-outline-google {
        color: #db4437;
        border-color: #db4437;
    }

    .btn-outline-google:hover {
        background-color: #db4437;
        color: white;
    }

    .btn-outline-twitter {
        color: #1da1f2;
        border-color: #1da1f2;
    }

    .btn-outline-twitter:hover {
        background-color: #1da1f2;
        color: white;
    }

    .btn-outline-github {
        color: #333;
        border-color: #333;
    }

    .btn-outline-github:hover {
        background-color: #333;
        color: white;
    }

    @media (max-width: 576px) {
        .signup-table {
            max-width: 90%;
            padding: 20px;
        }
    }
</style>

<div class="container-fluid signup-container">
    <div class="signup-table">
        <h2>Đăng ký tài khoản</h2>

        <?php if (!empty($signUpErrorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Error!</strong> <?= htmlspecialchars($signUpErrorMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="sign_up_process.php" enctype="multipart/form-data">
            <div class="text-center mb-3">
                <img src="../img/avatar-default.jpg" id="image_preview">
                <div>
                    <label for="image_upload" class="btn btn-outline-primary btn-upload">Chọn ảnh đại diện</label>
                    <input type="file" name="ImgUpload" id="image_upload" class="d-none" accept="image/*">
                </div>
            </div>

            <table>
                <tr>
                    <td><label for="LastName">Họ</label></td>
                    <td><input type="text" name="LastName" id="LastName" class="form-control" placeholder="Nhập họ" required></td>
                </tr>
                <tr>
                    <td><label for="FirstName">Tên</label></td>
                    <td><input type="text" name="FirstName" id="FirstName" class="form-control" placeholder="Nhập tên" required></td>
                </tr>
                <tr>
                    <td><label for="Email">Email</label></td>
                    <td><input type="email" name="Email" id="Email" class="form-control" placeholder="Nhập email" required></td>
                </tr>
                <tr>
                    <td><label for="Password">Mật khẩu</label></td>
                    <td><input type="password" name="Password" id="Password" class="form-control" placeholder="Nhập mật khẩu" required></td>
                </tr>
                <tr>
                    <td><label for="Phone">Số điện thoại</label></td>
                    <td><input type="tel" name="Phone" id="Phone" class="form-control" placeholder="Nhập số điện thoại" required></td>
                </tr>
                <tr>
                    <td colspan="2"><button type="submit" class="btn btn-primary mt-3">Đăng ký</button></td>
                </tr>
            </table>

            <div class="text-center mt-3">
                Đã có tài khoản? <a href="../customer/login.php">Đăng nhập</a>
            </div>

            <div class="text-center mt-3">
                <p>Hoặc đăng ký với:</p>
                <button type="button" class="btn btn-outline-facebook social-btn"><i class="fab fa-facebook-f"></i></button>
                <button type="button" class="btn btn-outline-google social-btn"><i class="fab fa-google"></i></button>
                <button type="button" class="btn btn-outline-twitter social-btn"><i class="fab fa-twitter"></i></button>
                <button type="button" class="btn btn-outline-github social-btn"><i class="fab fa-github"></i></button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById("image_upload").addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file) {
            const imagePreview = document.getElementById("image_preview");
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src);
        }
    });
</script>

<?php include '../footer.php'; ?>