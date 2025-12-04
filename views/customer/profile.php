<?php
// Fallback dữ liệu nếu $customer chưa được định nghĩa
if (!isset($customer) || !is_array($customer)) {
    $customer = [];
}

$firstName = $customer['FirstName'] ?? '';
$lastName  = $customer['LastName'] ?? '';
$email     = $customer['Email'] ?? '-';
$phone     = $customer['Phone'] ?? '-';
$address   = $customer['Address'] ?? '-';
$dob       = $customer['DateOfBirth'] ?? '-';
$registered= $customer['RegisteredAt'] ?? '-';
$img       = $customer['Img'] ?? 'default.jpg';

$imgPath = "images/Customer/" . $img;
if (!file_exists($imgPath)) {
    $imgPath = "images/Customer/default.jpg";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang cá nhân</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f2f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 950px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            padding: 30px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 40px;
        }
        .profile-img {
            flex: 0 0 200px;
        }
        .profile-img img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ff6600;
        }
        .profile-info {
            flex: 1;
            min-width: 250px;
        }
        .profile-info h2 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 26px;
        }
        .profile-info p {
            margin: 6px 0;
            font-size: 16px;
            color: #555;
        }
        .edit-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #ff6600;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
        }
        .edit-btn:hover {
            background-color: #e65500;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media(max-width: 700px) {
            .container {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .profile-info h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-img">
        <img src="<?= htmlspecialchars($imgPath) ?>" alt="Ảnh đại diện">
    </div>
    <div class="profile-info">
        <h2><?= htmlspecialchars($firstName . ' ' . $lastName) ?></h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($address) ?></p>
        <p><strong>Ngày sinh:</strong> <?= htmlspecialchars($dob) ?></p>
        <p><strong>Ngày đăng ký:</strong> <?= htmlspecialchars($registered) ?></p>
        <a href="index.php?page=profile_update">Chỉnh sửa thông tin</a>
        <a href="index.php?page=products" class="back-link">← Quay lại danh sách</a>
    </div>
</div>

</body>
</html>
