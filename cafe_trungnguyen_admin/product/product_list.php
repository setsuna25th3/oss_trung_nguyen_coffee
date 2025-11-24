<?php
require 'config.php';

$keyword    = isset($_GET['q']) ? trim($_GET['q']) : "";
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$catsResult = $conn->query("SELECT Id, Title FROM category ORDER BY Title ASC");

$sql = "SELECT p.*, c.Title AS CategoryTitle
        FROM product p
        JOIN category c ON p.CategoryId = c.Id
        WHERE 1 = 1";

$params = [];
$types  = "";

if ($keyword !== "") {
    $sql   .= " AND p.Title LIKE CONCAT('%', ?, '%')";
    $types .= "s";
    $params[] = $keyword;
}

if ($categoryId > 0) {
    $sql   .= " AND p.CategoryId = ?";
    $types .= "i";
    $params[] = $categoryId;
}

$sql .= " ORDER BY p.Id ASC";

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
    <title>Quản lý mặt hàng</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        img { max-width: 60px; }
        .toolbar { margin-bottom: 10px; }
        .toolbar form { display: inline-block; margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Quản lý mặt hàng</h1>

    <div class="toolbar">
        <form method="get" action="product_list.php">
            <input type="text" name="q" placeholder="Tìm theo tên sản phẩm"
                value="<?php echo htmlspecialchars($keyword); ?>">

            <select name="category">
                <option value="0">-- Tất cả loại hàng --</option>
                <?php if ($catsResult && $catsResult->num_rows > 0): ?>
                    <?php while ($cat = $catsResult->fetch_assoc()): ?>
                        <option value="<?php echo $cat['Id']; ?>"
                            <?php echo $categoryId == $cat['Id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['Title']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>

            <button type="submit">Lọc</button>
            <a href="product_list.php">Reset</a>
        </form>

        <a href="product_form.php">+ Thêm sản phẩm mới</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Loại</th>
            <th>Giá</th>
            <th>Ảnh</th>
            <th>Thao tác</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Id']; ?></td>
                    <td><?php echo htmlspecialchars($row['Title']); ?></td>
                    <td><?php echo htmlspecialchars($row['CategoryTitle']); ?></td>
                    <td><?php echo number_format($row['Price'], 0, ',', '.'); ?> đ</td>
                    <td>
                    <?php if (!empty($row['Img'])): ?>
                        <img src="images/SanPham/<?php echo htmlspecialchars($row['Img']); ?>" alt="">
                    <?php endif; ?>
                    </td>
                    <td>
                        <a href="product_form.php?id=<?php echo $row['Id']; ?>">Sửa</a> |
                        <a href="product_delete.php?id=<?php echo $row['Id']; ?>"
                        onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Không có sản phẩm</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
