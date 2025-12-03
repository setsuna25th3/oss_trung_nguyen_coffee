<?php
session_start();
require_once '../../env.php'; // chứa $hostname, $username, $password, $dbname
include '../header.php'; // Đưa header lên đầu để tiện quản lý HTML

// Kiểm tra session
if (!isset($_SESSION['CustomerId'])) {
    header("Location: ../customer/sign_in.php");
    exit();
}

$currentCustomerId = $_SESSION['CustomerId'];

// Kết nối DB
$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy danh sách payment của khách hàng
$stmt = $conn->prepare("
    SELECT p.Id, c.FirstName, c.LastName, c.Phone, c.Email, p.CreatedAt, p.Total, p.CustomerId
    FROM payment p
    JOIN customer c ON p.CustomerId = c.Id
    WHERE p.CustomerId = ?
    ORDER BY p.CreatedAt DESC
");
$stmt->bind_param("i", $currentCustomerId);
$stmt->execute();
$result = $stmt->get_result();
$payments = [];
while ($row = $result->fetch_assoc()) {
    $payments[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách hóa đơn</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            /* Nền nhẹ nhàng */
        }

        .page-header {
            padding: 80px 0;
            /* Gradient hiện đại */
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #fff;
            text-align: center;
            /* Góc bo lớn hơn */
            border-radius: 0 0 40px 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 42px;
            font-weight: 800;
            margin: 0;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Container cho bảng */
        .payment-list-container {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        /* Style Bảng */
        .payment-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .payment-table th {
            background: #343a40;
            color: #ffc107;
            font-weight: 600;
            padding: 15px 12px;
            text-align: center;
            border: none !important;
        }

        .payment-table tbody tr {
            background: #ffffff;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s ease;
        }

        .payment-table tbody tr:last-child {
            border-bottom: none;
        }

        .payment-table tbody tr:hover {
            background-color: #fffaf0;
            /* Hiệu ứng hover nhẹ */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .payment-table td {
            vertical-align: middle !important;
            padding: 15px 12px;
            color: #333;
            font-weight: 500;
            border: none;
            text-align: center;
        }

        .payment-table td:nth-child(2),
        .payment-table td:nth-child(3) {
            text-align: left;
            /* Căn trái cho tên */
        }

        /* Màu cho Tổng tiền */
        .total-amount {
            color: #e74c3c;
            /* Màu đỏ nổi bật cho tiền */
            font-weight: 700;
        }

        /* Nút chi tiết */
        .btn-detail {
            background: #3498db;
            color: #fff;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        /* Tối ưu hóa cho mobile */
        @media (max-width: 768px) {
            .page-header {
                padding: 60px 0 30px;
                border-radius: 0 0 20px 20px;
            }

            .page-header h1 {
                font-size: 30px;
            }

            .payment-list-container {
                padding: 15px;
                border-radius: 15px;
            }

            .payment-table thead {
                display: none;
                /* Ẩn tiêu đề cột */
            }

            .payment-table,
            .payment-table tbody,
            .payment-table tr,
            .payment-table td {
                display: block;
                width: 100%;
            }

            .payment-table tr {
                margin-bottom: 15px;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .payment-table td {
                text-align: right;
                padding: 10px 15px;
                border: none;
                position: relative;
            }

            .payment-table td::before {
                /* Hiển thị lại tiêu đề cột dưới dạng label */
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 50%;
                padding-right: 10px;
                font-weight: 600;
                text-align: left;
                color: #555;
            }
        }
    </style>
</head>

<body>

    <div class="page-header">
        <h1 class="fw-bold">Lịch Sử Mua Hàng 🧾</h1>
    </div>
    <div class="container my-5">
        <div class="payment-list-container">
            <div class="table-responsive">
                <table class="payment-table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Mã HĐ</th>
                            <th style="width: 15%;">Họ</th>
                            <th style="width: 15%;">Tên</th>
                            <th style="width: 15%;">SĐT</th>
                            <th style="width: 15%;">Ngày tạo</th>
                            <th style="width: 15%;">Tổng tiền</th>
                            <th style="width: 15%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($payments)): ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td data-label="Mã HĐ"><?= $payment['Id'] ?></td>
                                    <td data-label="Họ"><?= htmlspecialchars($payment['FirstName']) ?></td>
                                    <td data-label="Tên"><?= htmlspecialchars($payment['LastName']) ?></td>
                                    <td data-label="Số điện thoại"><?= htmlspecialchars($payment['Phone']) ?></td>
                                    <td data-label="Ngày tạo"><?= date("d/m/Y H:i", strtotime($payment['CreatedAt'])) ?></td>
                                    <td data-label="Tổng tiền" class="total-amount"><?= number_format($payment['Total'], 0, ',', '.') ?> VND</td>
                                    <td data-label="Thao tác">
                                        <a href="detail.php?id=<?= $payment['Id'] ?>" class="btn-detail">Xem chi tiết</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <h4 class="text-muted">Bạn chưa có hóa đơn nào được ghi nhận.</h4>
                                    <p>Hãy tiếp tục mua sắm để xem lịch sử đơn hàng của bạn tại đây!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include '../footer.php'; ?>
</body>

</html>