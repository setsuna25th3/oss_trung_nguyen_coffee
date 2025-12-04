<?php
// Giả sử đây là dữ liệu mẫu, thay bằng dữ liệu từ DB sau
$payments = [
    [
        'Id' => 1,
        'FirstName' => 'Nguyen',
        'LastName' => 'Chi Truong',
        'Phone' => '0909123456',
        'Email' => 'truong@example.com',
        'CreateAt' => '2025-11-24 10:30:00',
        'Total' => 120000,
        'CustomerId' => 101
    ],
    [
        'Id' => 2,
        'FirstName' => 'Tran',
        'LastName' => 'Van A',
        'Phone' => '0912345678',
        'Email' => 'tran@example.com',
        'CreateAt' => '2025-11-23 15:20:00',
        'Total' => 85000,
        'CustomerId' => 102
    ]
];

// Giả sử CustomerId lấy từ session (thay cho User.Claims)
$currentCustomerId = 101;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách hóa đơn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .page-header {
            margin-top: 80px;
            padding: 120px 20px 40px;
            background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNRm0D6QVOC7HmWVXXHkgLzAwnrCRs2V8qvw&s');
            background-size: cover;
            background-position: center;
            color: red;
            text-align: center;
        }

        table th,
        table td {
            vertical-align: middle !important;
        }

        table th {
            background: #343a40;
            color: #ffc107;
        }
    </style>
</head>

<body>
    <?php include '../header.php'; ?>

    <div class="page-header">
        <h1 class="fw-bold">Danh sách hóa đơn</h1>
    </div>

    <div class="container my-5">
        <table class="table table-bordered table-striped text-black fw-bold">
            <thead>
                <tr>
                    <th>Mã hóa đơn</th>
                    <th>Họ</th>
                    <th>Tên</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th>Ngày tạo</th>
                    <th>Tổng tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <?php if ($payment['CustomerId'] == $currentCustomerId): ?>
                        <tr>
                            <td><?= $payment['Id'] ?></td>
                            <td><?= $payment['FirstName'] ?></td>
                            <td><?= $payment['LastName'] ?></td>
                            <td><?= $payment['Phone'] ?></td>
                            <td><?= $payment['Email'] ?></td>
                            <td><?= date("Y-m-d H:i:s", strtotime($payment['CreateAt'])) ?></td>
                            <td><?= number_format($payment['Total'], 0, ',', '.') ?> VND</td>
                            <td>
                                <a href="payment_details.php?id=<?= $payment['Id'] ?>" class="btn btn-primary">Xem chi tiết</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include '../footer.php'; ?>
</body>

</html>