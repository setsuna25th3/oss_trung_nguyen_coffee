<?php
session_start();

// Giả lập dữ liệu khách hàng (thay bằng dữ liệu thực từ DB)
$customer = [
    'Id' => 1,
    'Img' => 'default-avatar.jpg',
    'LastName' => 'Nguyễn',
    'FirstName' => 'Văn A',
    'Phone' => '0901234567',
    'Email' => 'nguyenvana@gmail.com',
    'DateOfBirth' => '1995-08-15',
    'Address' => '123 Đường Láng, Đống Đa, Hà Nội'
];

// Thông báo từ session
$success = $_SESSION['ProfileSuccessMessage'] ?? '';
$error   = $_SESSION['ProfileErrorMessage'] ?? '';
unset($_SESSION['ProfileSuccessMessage'], $_SESSION['ProfileErrorMessage']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông tin cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #ffb300;
            --primary-hover: #ff9800;
            --gray-light: #f8f9fa;
            --border: #dee2e6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, #fff8e1, #fff1e0);
            min-height: 100vh;
        }

        #headerprofile {
            background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.7)),
                url('https://images2.thanhnien.vn/528068263637045248/2024/1/25/e093e9cfc9027d6a142358d24d2ee350-65a11ac2af785880-17061562929701875684912.jpg') center/cover no-repeat;
            height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .profile-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            transition: all 0.4s;
        }

        .profile-card:hover {
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.18);
        }

        .avatar-wrapper {
            position: relative;
            width: 220px;
            height: 220px;
            margin: 0 auto;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 8px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .avatar-overlay {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: var(--primary);
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(255, 179, 0, 0.4);
            transition: all 0.3s;
        }

        .avatar-overlay:hover {
            background: var(--primary-hover);
            transform: scale(1.15);
        }

        .form-control[readonly],
        .form-control:disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 50px;
            padding: 12px 32px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-3px);
        }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999;
            background: var(--primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(255, 179, 0, 0.4);
        }

        .back-to-top:hover {
            background: var(--primary-hover);
            transform: translateY(-5px);
        }
    </style>
</head>

<body>

    <?php include '../header.php'; ?>

    <!-- Header -->
    <div class="container-fluid text-white text-center" id="headerprofile">
        <h1 class="display-4 fw-bold">Thông tin cá nhân</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-white opacity-75">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="cart.php" class="text-white opacity-75">Giỏ hàng</a></li>
                <li class="breadcrumb-item active text-warning">Hồ sơ</li>
            </ol>
        </nav>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Alert -->
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4">
                        <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show rounded-4">
                        <i class="fas fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="profile-card p-5">
                    <div class="text-end mb-4">
                        <button type="button" id="btnEdit" class="btn btn-outline-primary px-4">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin
                        </button>
                    </div>

                    <form method="post" action="update_profile_process.php" enctype="multipart/form-data" id="profileForm">
                        <div class="row g-5 align-items-start">
                            <!-- Avatar + Nút hành động -->
                            <div class="col-lg-5 text-center">
                                <div class="avatar-wrapper">
                                    <img src="../Images/KhachHang/<?= htmlspecialchars($customer['Img']) ?>"
                                        alt="Avatar" class="avatar-img" id="image_preview">
                                    <label class="avatar-overlay" title="Đổi ảnh đại diện">
                                        <i class="fas fa-camera fa-lg"></i>
                                        <input type="file" name="ImgUpload" id="image_upload" accept="image/*" style="display:none;">
                                    </label>
                                </div>
                                <input type="hidden" name="Img" value="<?= htmlspecialchars($customer['Img']) ?>">

                                <div class="mt-4 d-grid d-md-flex justify-content-center gap-3">
                                    <a href="change_password.php" class="btn btn-outline-primary">
                                        <i class="fas fa-key me-2"></i>Đổi mật khẩu
                                    </a>
                                    <a href="sign_out.php" class="btn btn-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </a>
                                </div>

                                <div class="mt-4 text-center">
                                    <a href="payment.php" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-shopping-bag me-2"></i>Xem đơn hàng
                                    </a>
                                </div>
                            </div>

                            <!-- Form thông tin -->
                            <div class="col-lg-7">
                                <input type="hidden" name="Id" value="<?= $customer['Id'] ?>">

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Họ</label>
                                        <input type="text" name="LastName" value="<?= htmlspecialchars($customer['LastName']) ?>"
                                            class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tên</label>
                                        <input type="text" name="FirstName" value="<?= htmlspecialchars($customer['FirstName']) ?>"
                                            class="form-control" readonly>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Số điện thoại</label>
                                        <input type="text" name="Phone" value="<?= htmlspecialchars($customer['Phone']) ?>"
                                            class="form-control" readonly>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" name="Email" value="<?= htmlspecialchars($customer['Email']) ?>"
                                            class="form-control" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Ngày sinh</label>
                                        <input type="date" name="DateOfBirth" value="<?= $customer['DateOfBirth'] ?>"
                                            class="form-control" readonly>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Địa chỉ nhận hàng</label>
                                        <input type="text" name="Address" value="<?= htmlspecialchars($customer['Address']) ?>"
                                            class="form-control" readonly>
                                    </div>

                                    <!-- Nút Lưu / Hủy (ẩn mặc định) -->
                                    <div class="col-12 text-end mt-4" id="actionButtons" style="display:none;">
                                        <button type="button" id="btnCancel" class="btn btn-secondary me-3 px-4">
                                            <i class="fas fa-times me-2"></i>Hủy
                                        </button>
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="fas fa-save me-2"></i>Lưu thay đổi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer.php'; ?>

    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const btnEdit = document.getElementById('btnEdit');
        const btnCancel = document.getElementById('btnCancel');
        const actionButtons = document.getElementById('actionButtons');
        const formInputs = document.querySelectorAll('#profileForm input[name]:not([type="file"]):not([type="hidden"])');

        // Bật chế độ chỉnh sửa
        btnEdit.addEventListener('click', function() {
            formInputs.forEach(input => {
                if (input.name !== 'Email') { // Email thường không cho sửa
                    input.removeAttribute('readonly');
                }
                input.classList.remove('bg-light');
            });

            actionButtons.style.display = 'block';
            btnEdit.style.display = 'none';
        });

        // Hủy chỉnh sửa
        btnCancel.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn hủy các thay đổi?')) {
                location.reload(); // Reload để quay về trạng thái ban đầu
            }
        });

        // Xem trước ảnh
        document.getElementById('image_upload').addEventListener('change', function(e) {
            if (e.target.files[0]) {
                document.getElementById('image_preview').src = URL.createObjectURL(e.target.files[0]);
            }
        });

        // Tự ẩn alert
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(a => {
                a.style.transition = 'opacity 0.6s';
                a.style.opacity = '0';
                setTimeout(() => a.remove(), 600);
            });
        }, 4000);
    </script>
</body>

</html>