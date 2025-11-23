<?php

require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Thiếu mã đơn hàng");
}

$sql = "SELECT p.*,
            CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName,
            c.Email, c.Phone,
            s.StoreName, s.Address AS StoreAddress
        FROM payment p
        JOIN customer c ON p.CustomerId = c.Id
        JOIN store s    ON p.StoreId = s.Id
        WHERE p.Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Không tìm thấy đơn hàng");
}

$sql = "SELECT pd.*, pr.Title, pr.Img
        FROM paymentdetail pd
        JOIN product pr ON pd.ProductId = pr.Id
        WHERE pd.PaymentId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$items = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn <?php echo $id; ?></title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        img { max-width: 60px; }
    </style>
</head>
<body>
    <h1>Chi tiết đơn hàng <?php echo $id; ?></h1>

    <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['CustomerName']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['Email']); ?></p>
    <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['Phone']); ?></p>
    <p><strong>Cửa hàng:</strong> <?php echo htmlspecialchars($order['StoreName']); ?></p>
    <p><strong>Địa chỉ cửa hàng:</strong> <?php echo htmlspecialchars($order['StoreAddress']); ?></p>
    <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($order['Status']); ?></p>
    <p><strong>Ngày tạo:</strong> <?php echo $order['CreatedAt']; ?></p>
    <p><strong>Tổng tiền:</strong> <?php echo number_format($order['Total'], 0, ',', '.'); ?> đ</p>

    <h2>Sản phẩm trong đơn</h2>
    <table>
        <tr>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
        <?php if ($items && $items->num_rows > 0): ?>
            <?php while ($row = $items->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if (!empty($row['Img'])): ?>
                            <img src="images/<?php echo htmlspecialchars($row['Img']); ?>" alt="">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['Title']); ?></td>
                    <td><?php echo number_format($row['Price'], 0, ',', '.'); ?> đ</td>
                    <td><?php echo $row['Quantity']; ?></td>
                    <td><?php echo number_format($row['Price'] * $row['Quantity'], 0, ',', '.'); ?> đ</td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">Không có sản phẩm</td></tr>
        <?php endif; ?>
    </table>

    <p><a href="order_list.php">Quay lại danh sách đơn hàng</a></p>
</body>
</html>
