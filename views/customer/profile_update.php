<?php
// Fallback dữ liệu
if (!isset($customer) || !is_array($customer)) {
    $customer = [];
}

$firstName = $customer['FirstName'] ?? '';
$lastName  = $customer['LastName'] ?? '';
$email     = $customer['Email'] ?? '';
$phone     = $customer['Phone'] ?? '';
$address   = $customer['Address'] ?? '';
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
    <title>Chỉnh sửa thông tin cá nhân</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f2f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h2 { text-align:center; color:#333; margin-bottom:20px; }
        form label { display:block; margin:12px 0 4px; font-weight:bold; }
        form input, form textarea { width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-size:14px; }
        .submit-btn {
            margin-top:20px;
            padding:12px 25px;
            background-color:#ff6600;
            color:#fff;
            border:none;
            border-radius:8px;
            font-weight:bold;
            cursor:pointer;
            transition: background 0.3s;
        }
        .submit-btn:hover { background-color:#e65500; }
        .back-link { display:inline-block; margin-top:15px; color:#333; text-decoration:none; }
        .profile-img { text-align:center; margin-bottom:15px; }
        .profile-img img { width:150px; height:150px; border-radius:50%; object-fit:cover; border:2px solid #ff6600; }
        .submit-btn {
            padding:12px 25px;
            background-color:#ff6600;
            color:#fff;
            border:none;
            border-radius:8px;
            font-weight:bold;
            cursor:pointer;
            transition: background 0.3s;

            /* Thêm 2 dòng này để căn giữa */
            display: block;
            margin: 20px auto 0 auto;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Chỉnh sửa thông tin cá nhân</h2>

    <div class="profile-img">
        <img src="<?= htmlspecialchars($imgPath) ?>" alt="Ảnh đại diện">
    </div>

    <form method="post" action="index.php?page=profile-update" enctype="multipart/form-data">
        <label>Họ</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($firstName) ?>">

        <label>Tên</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($lastName) ?>">

        <label>Email (không thể sửa)</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>">

        <label>Địa chỉ</label>
        <textarea name="address"><?= htmlspecialchars($address) ?></textarea>

        <button type="submit" class="submit-btn" style="display:block; margin:20px auto 0 auto;">Cập nhật thông tin</button>
    </form>

    <a href="index.php?page=profile" class="back-link">← Quay lại trang cá nhân</a>
</div>

</body>
</html>
