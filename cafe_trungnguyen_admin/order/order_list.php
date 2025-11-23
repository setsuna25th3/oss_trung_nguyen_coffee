<?php

require 'config.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : "";
$status  = isset($_GET['status']) ? trim($_GET['status']) : "";
$from    = isset($_GET['from']) ? trim($_GET['from']) : "";
$to      = isset($_GET['to']) ? trim($_GET['to']) : "";

$sql = "SELECT p.Id,
            CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName,
            s.StoreName,
            p.Total,
            p.Status,
            p.CreatedAt
        FROM payment p
        JOIN customer c ON p.CustomerId = c.Id
        JOIN store s    ON p.StoreId = s.Id
        WHERE 1=1";

$params = [];
$types  = "";

if ($keyword !== "") {
    $sql   .= " AND (p.Id = ? OR CONCAT(c.FirstName, ' ', c.LastName) LIKE CONCAT('%', ?, '%'))";
    $types .= "is";
    $params[] = (int)$keyword;
    $params[] = $keyword;
}

if ($status !== "") {
    $sql   .= " AND p.Status = ?";
    $types .= "s";
    $params[] = $status;
}

if ($from !== "") {
    $sql   .= " AND DATE(p.CreatedAt) >= ?";
    $types .= "s";
    $params[] = $from;
}
if ($to !== "") {
    $sql   .= " AND DATE(p.CreatedAt) <= ?";
    $types .= "s";
    $params[] = $to;
}

$sql .= " ORDER BY p.CreatedAt DESC";

$stmt = $conn->prepare($sql);
if ($types !== "") {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách đơn hàng</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Danh sách đơn hàng</h1>

    <form method="get" action="order_list.php">
        <input type="text" name="q" placeholder="Mã đơn hoặc tên khách"
            value="<?php echo htmlspecialchars($keyword); ?>">

        <input type="date" name="from" value="<?php echo htmlspecialchars($from); ?>">
        <input type="date" name="to"   value="<?php echo htmlspecialchars($to); ?>">

        <input type="text" name="status" placeholder="Trạng thái"
            value="<?php echo htmlspecialchars($status); ?>">

        <button type="submit">Lọc</button>
        <a href="order_list.php">Reset</a>
    </form>

    <table>
        <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Cửa hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Chi tiết</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Id']; ?></td>
                    <td><?php echo htmlspecialchars($row['CustomerName']); ?></td>
                    <td><?php echo htmlspecialchars($row['StoreName']); ?></td>
                    <td><?php echo number_format($row['Total'], 0, ',', '.'); ?> đ</td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                    <td><?php echo $row['CreatedAt']; ?></td>
                    <td><a href="order_detail.php?id=<?php echo $row['Id']; ?>">Xem</a></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Không có đơn hàng</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
