<?php
require 'config.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : "";

if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM category WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: category_list.php");
    exit;
}

if ($keyword !== "") {
    $sql = "SELECT * FROM category
        WHERE Title LIKE CONCAT('%', ?, '%')
        ORDER BY Id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM category ORDER BY Id ASC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý loại hàng</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .actions a { margin-right: 8px; }
    </style>
</head>
<body>
    <h1>Quản lý loại hàng</h1>

    <form method="get" action="category_list.php">
        <input type="text" name="q" placeholder="Tìm theo tên loại hàng"
            value="<?php echo htmlspecialchars($keyword); ?>">
        <button type="submit">Tìm kiếm</button>
        <a href="category_list.php">Reset</a>
    </form>

    <p>
        <a href="category_form.php">Thêm loại hàng mới</a>
    </p>

    <table>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Mô tả</th>
            <th>Ngày tạo</th>
            <th>Ngày cập nhật</th>
            <th>Thao tác</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Id']; ?></td>
                    <td><?php echo htmlspecialchars($row['Title']); ?></td>
                    <td><?php echo htmlspecialchars($row['Content']); ?></td>
                    <td><?php echo $row['CreateAt']; ?></td>
                    <td><?php echo $row['UpdateAt']; ?></td>
                    <td class="actions">
                        <a href="category_form.php?id=<?php echo $row['Id']; ?>">Sửa</a>
                        <a href="category_list.php?delete_id=<?php echo $row['Id']; ?>"
                        onclick="return confirm('Xóa loại hàng này?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Không có dữ liệu</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
