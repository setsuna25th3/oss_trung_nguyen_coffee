<?php
session_start();
include '../../models/Customer.php';
include '../../controllers/CustomerController.php';

$lastName = $_POST['LastName'] ?? ' ';
$firstName = $_POST['FirstName'] ?? ' ';
$email = $_POST['Email'] ?? ' ';
$phone = $_POST['Phone'] ?? ' ';
$address = $_POST['Address'] ?? ' ';

$UPLOAD_DIR = '../../img/KhachHang/';

function printVar($var)
{
    if (isset($var)) echo $var;
}

function uploadImage($uploadDir, $identifier)
{
    if (!isset($_FILES['ImgUpload']) || $_FILES['ImgUpload']['error'] !== UPLOAD_ERR_OK) {
        return 'avatar-default.png';
    }

    $file = $_FILES['ImgUpload'];
    $temp_path = $file['tmp_name'];
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $folder_name = md5(trim($identifier));
    $target_folder = $uploadDir . $folder_name . '/';

    if (!is_dir($target_folder)) {
        if (!mkdir($target_folder, 0755, true)) {
            error_log('Failed to create directory: ' . $target_folder);
            return false;
        }
    }

    $file_name = 'avatar_' . time() . '.' . $file_extension;
    $target_file = $target_folder . $file_name;

    if (move_uploaded_file($temp_path, $target_file)) {
        return $file_name;
    } else {
        error_log('Failed to move uploaded file to: ' . $target_file);
        return false;
    }
}

if (isset($_POST['SignUp'])) {
    $customer = new Customer();
    $customerController = new CustomerController();

    $customer->LastName = $lastName;
    $customer->FirstName = $firstName;
    $customer->Email = trim($email);
    $customer->Phone = trim($phone);
    $customer->Address = $address;
    $customer->DateOfBirth = null;
    $customer->IsActive = 1;
    $customer->Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
    $confirmPass = $_POST['ConfirmPassword'] ?? ' ';
    $customer->RandomKey = '';
    $email_exists = $customerController->checkDuplicateByEmail($customer);
    $customer->ProvinceId = (int)($_POST['ProvinceId'] ?? 0);
    $customer->DistrictId = (int)($_POST['DistrictId'] ?? 0);
    $customer->WardCode   = $_POST['WardCode'] ?? '';

    $tempErrorMessage = '';
    if ($email_exists) {
        $_SESSION['SignUpErrorMessage'] = 'Email đã tồn tại. Vui lòng sử dụng email khác.';
    } else {
        if (!password_verify($confirmPass, $customer->Password)) {
            $_SESSION['SignUpErrorMessage'] = 'Mật khẩu xác nhận không đúng.';
        } else {
            $img_path = uploadImage($UPLOAD_DIR, $customer->Email);
            if ($img_path === false) {
                $_SESSION['SignUpErrorMessage'] = 'Đăng ký thất bại do lỗi tải ảnh. Vui lòng thử lại.';
            } else {
                $customer->Img = $img_path;
                $isSuccess = $customerController->signUp($customer);
                if ($isSuccess) {
                    $_SESSION['SignUpSuccessMessage'] = 'Đăng ký thành công!';
                    header('Location: sign_in.php');
                    exit();
                } else {
                    $_SESSION['SignUpErrorMessage'] = 'Đăng ký thất bại. Vui lòng thử lại.';
                }
            }
        }
    }
    $signUpErrorMessage = $_SESSION['SignUpErrorMessage'] ?? '';
    unset($_SESSION['SignUpErrorMessage']);
}
echo '<pre>';
print_r($_POST);
echo '</pre>';

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

    .hidden-file-input {
        display: none !important;
    }

    .avatar-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .btn-upload-custom {
        margin-top: 10px;
        background-color: #ffb300;
        padding: 8px 20px;
        border-radius: 25px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-upload-custom:hover {
        background-color: #ff9800;
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid signup-container">
    <div class="signup-table">
        <h2>Đăng ký tài khoản</h2>

        <?php if (!empty($signUpErrorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><?= htmlspecialchars($signUpErrorMessage) ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="sign_up.php" enctype="multipart/form-data">
            <div class="avatar-container mb-3">
                <img src="../img/avatar-default.jpg" id="image_preview">
                <label for="image_upload" class="btn btn-upload-custom">Chọn ảnh đại diện</label>
                <input type="file" id="image_upload" name="ImgUpload" accept="image/*" class="hidden-file-input">
            </div>

            <table>
                <tr>
                    <td><label for="LastName">Họ</label></td>
                    <td><input type="text" name="LastName" id="LastName" class="form-control" placeholder="Nhập họ" required value="<?php printVar($lastName) ?>"></td>
                </tr>
                <tr>
                    <td><label for="FirstName">Tên</label></td>
                    <td><input type="text" name="FirstName" id="FirstName" class="form-control" placeholder="Nhập tên" required value="<?php printVar($firstName) ?>"></td>
                </tr>
                <tr>
                    <td><label for="Email">Email</label></td>
                    <td><input type="email" name="Email" id="Email" class="form-control" placeholder="Nhập email" required value="<?php printVar($email) ?>"></td>
                </tr>
                <tr>
                    <td><label for="Password">Mật khẩu</label></td>
                    <td><input type="password" name="Password" id="Password" class="form-control" placeholder="Nhập mật khẩu" required></td>
                </tr>
                <tr>
                    <td><label for="ConfirmPassword">Nhập lại mật khẩu</label></td>
                    <td><input type="password" name="ConfirmPassword" id="ConfirmPassword" class="form-control" placeholder="Nhập lại mật khẩu" required></td>
                </tr>
                <tr>
                    <td><label for="Phone">Số điện thoại</label></td>
                    <td><input type="tel" name="Phone" id="Phone" class="form-control" placeholder="Nhập số điện thoại" required value="<?php printVar($phone) ?>"></td>
                </tr>
                <tr>
                    <td><label for="Address">Địa chỉ</label></td>
                    <td><input type="text" name="Address" id="Address" class="form-control" placeholder="Nhập địa chỉ" required value="<?php printVar($address) ?>"></td>
                </tr>
                <tr>
                    <td><label for="ProvinceId">Tỉnh/Thành phố</label></td>
                    <td>
                        <select name="ProvinceId" id="ProvinceId" class="form-control" required>
                            <option value="">-- Chọn Tỉnh/Thành phố --</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="DistrictId">Quận/Huyện</label></td>
                    <td>
                        <select name="DistrictId" id="DistrictId" class="form-control" required>
                            <option value="">-- Chọn Quận/Huyện --</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="WardCode">Phường/Xã</label></td>
                    <td>
                        <select name="WardCode" id="WardCode" class="form-control" required>
                            <option value="">-- Chọn Phường/Xã --</option>
                        </select>
                    </td>
                </tr>


                <tr>
                    <td colspan="2"><button type="submit" name="SignUp" class="btn btn-primary mt-3">Đăng ký</button></td>
                </tr>
            </table>

            <div class="text-center mt-3">
                Đã có tài khoản? <a href="sign_in.php">Đăng nhập</a>
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
    const GHN_TOKEN = "ed799cbf-cfee-11f0-84c8-a649637e7c2d";
    const provinceSelect = document.getElementById('ProvinceId');
    const districtSelect = document.getElementById('DistrictId');
    const wardSelect = document.getElementById('WardCode');

    window.addEventListener('DOMContentLoaded', () => {
        provinceSelect.innerHTML = '<option value="">Đang tải Tỉnh/Thành...</option>';

        fetch('https://online-gateway.ghn.vn/shiip/public-api/master-data/province', {
                headers: {
                    "Token": GHN_TOKEN
                }
            })
            .then(res => res.json())
            .then(data => {
                provinceSelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành --</option>';
                data.data.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.ProvinceID;
                    opt.textContent = p.ProvinceName;
                    provinceSelect.appendChild(opt);
                });
            });
    });

    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        districtSelect.innerHTML = '<option value="">Đang tải Quận/Huyện...</option>';
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

        if (!provinceId) {
            districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
            return;
        }

        fetch(`https://online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id=${provinceId}`, {
                headers: {
                    "Token": GHN_TOKEN
                }
            })
            .then(res => res.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                data.data.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.DistrictID;
                    opt.textContent = d.DistrictName;
                    districtSelect.appendChild(opt);
                });
            });
    });

    // 3️⃣ Khi chọn quận → load phường/xã
    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        wardSelect.innerHTML = '<option value="">Đang tải Phường/Xã...</option>';

        if (!districtId) {
            wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
            return;
        }

        fetch(`https://online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id=${districtId}`, {
                headers: {
                    "Token": GHN_TOKEN
                }
            })
            .then(res => res.json())
            .then(data => {
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                data.data.forEach(w => {
                    const opt = document.createElement('option');
                    opt.value = w.WardCode;
                    opt.textContent = w.WardName;
                    wardSelect.appendChild(opt);
                });
            });
    });
</script>

<?php include '../footer.php'; ?>