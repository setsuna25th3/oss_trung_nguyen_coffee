<?php
// Bắt buộc phải có file kết nối DB
require_once 'db_connect.php'; 

$page_title = "Đăng Ký Tài Khoản Mới";
$message = '';
$message_type = '';
$errors = [];

// Khởi tạo các biến để giữ lại giá trị người dùng đã nhập
$firstName = $lastName = $email = $address = $dateOfBirth = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- Lấy và làm sạch dữ liệu ---
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $address = trim($_POST['address'] ?? '');
    $dateOfBirth = trim($_POST['dateOfBirth'] ?? null);
    
    $Img = null; // Khởi tạo biến lưu đường dẫn ảnh

    // --- 1. Validation Bắt Buộc ---
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $errors['general'] = "Vui lòng điền đầy đủ các trường bắt buộc (Họ, Tên, Email, Mật khẩu).";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Địa chỉ email không hợp lệ.";
    }
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Xác nhận mật khẩu không khớp.";
    }
    if (strlen($password) < 6) {
        $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự.";
    }

    // --- 2. Kiểm tra Email đã tồn tại ---
    if (!isset($errors['email']) && !isset($errors['general'])) {
        $check_email_stmt = $conn->prepare("SELECT Id FROM customer WHERE Email = ?");
        $check_email_stmt->bind_param("s", $email);
        $check_email_stmt->execute();
        if ($check_email_stmt->get_result()->num_rows > 0) {
            $errors['email'] = "Email này đã được đăng ký.";
        }
        $check_email_stmt->close();
    }

    // --- 3. Xử lý upload ảnh (Nếu không có lỗi) ---
    if (empty($errors) && isset($_FILES['Img']) && $_FILES['Img']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/profiles/";
        $file_extension = strtolower(pathinfo(basename($_FILES["Img"]["name"]), PATHINFO_EXTENSION));
        $new_file_name = uniqid() . "." . $file_extension;
        $target_file = $target_dir . $new_file_name;
        $uploadOk = true;

        if ($_FILES["Img"]["size"] > 2000000) { // 2MB
            $errors['Img'] = "Kích thước ảnh quá lớn (max 2MB).";
            $uploadOk = false;
        }
        if(!in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $errors['Img'] = "Chỉ chấp nhận JPG, JPEG, PNG & GIF.";
            $uploadOk = false;
        }

        if ($uploadOk) {
            if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
            if (move_uploaded_file($_FILES["Img"]["tmp_name"], $target_file)) {
                $Img = $target_file; // Lưu đường dẫn
            } else {
                $errors['Img'] = "Có lỗi xảy ra khi tải ảnh lên.";
            }
        }
    }


    // --- 4. Thực hiện INSERT nếu không có lỗi ---
    if (empty($errors)) {
        // Hash mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // RandomKey có thể dùng cho việc xác thực email (tạm thời để NULL hoặc giá trị khởi tạo)
        $randomKey = bin2hex(random_bytes(16)); 
        $isActive = 1; // Mặc định kích hoạt

        // Prepared Statement cho INSERT
        $sql = "INSERT INTO customer (FirstName, LastName, Email, Password, Address, DateOfBirth, Img, RandomKey, IsActive) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $insert_stmt = $conn->prepare($sql);
        $insert_stmt->bind_param("ssssssssi", 
            $firstName, $lastName, $email, $hashed_password, $address, $dateOfBirth, $Img, $randomKey, $isActive
        );

        if ($insert_stmt->execute()) {
            // Đặt thông báo thành công vào session để hiển thị trên trang chủ
            $_SESSION['message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
            $_SESSION['message_type'] = "success";
            
            // --- THAY ĐỔI TẠI ĐÂY ---
            // Chuyển hướng về index.php kèm theo tham số 'registered=true'
            header("Location: index.php?registered=true"); 
            exit();
        } else {
            $message = "Lỗi CSDL khi đăng ký: " . $conn->error;
            $message_type = "danger";
        }
        $insert_stmt->close();
    } 
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .auth-container { max-width: 600px; margin: auto; padding: 30px; background: #fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        body { background-color: #f7f7f7; padding-top: 40px; }
        .bg-custom { background-color: #8b4513; }
        .btn-custom { background-color: #8b4513; border-color: #8b4513; color: white; width: 100%; }
        .btn-custom:hover { background-color: #a0522d; border-color: #a0522d; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container mt-5">
            <div class="text-center mb-4">
                <h2 class="text-dark">📝 Đăng Ký Tài Khoản Mới</h2>
                <p>Cung cấp đầy đủ thông tin để nhận ưu đãi từ Trung Nguyên</p>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName" class="form-label">Tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php echo isset($errors['firstName']) ? 'is-invalid' : ''; ?>" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName" class="form-label">Họ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php echo isset($errors['lastName']) ? 'is-invalid' : ''; ?>" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <?php if (isset($errors['email'])): ?><div class="invalid-feedback d-block"><?php echo $errors['email']; ?></div><?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                        <?php if (isset($errors['password'])): ?><div class="invalid-feedback d-block"><?php echo $errors['password']; ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirmPassword" class="form-label">Xác nhận Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control <?php echo isset($errors['confirmPassword']) ? 'is-invalid' : ''; ?>" id="confirmPassword" name="confirmPassword" required>
                        <?php if (isset($errors['confirmPassword'])): ?><div class="invalid-feedback d-block"><?php echo $errors['confirmPassword']; ?></div><?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="dateOfBirth" class="form-label">Ngày Sinh</label>
                    <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($dateOfBirth); ?>">
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Địa Chỉ</label>
                    <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($address); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="Img" class="form-label">Ảnh Đại Diện (tùy chọn)</label>
                    <input type="file" class="form-control <?php echo isset($errors['Img']) ? 'is-invalid' : ''; ?>" id="Img" name="Img">
                    <?php if (isset($errors['Img'])): ?><div class="invalid-feedback d-block"><?php echo $errors['Img']; ?></div><?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn-lg btn-custom mt-3">Đăng Ký Tài Khoản</button>
                
                <div class="text-center mt-3">
                    Đã có tài khoản? <a href="login.php" class="text-decoration-none">Đăng nhập ngay</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>