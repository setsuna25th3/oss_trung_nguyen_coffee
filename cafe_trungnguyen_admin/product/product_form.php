<?php
require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$catsResult = $conn->query("SELECT Id, Title FROM category ORDER BY Title ASC");

$title = $content = $img = "";
$price = 0;
$categoryId = 0;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM product WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res  = $stmt->get_result();
    $row  = $res->fetch_assoc();
    $stmt->close();

    if ($row) {
        $title      = $row['Title'];
        $content    = $row['Content'];
        $img        = $row['Img'];
        $price      = $row['Price'];
        $categoryId = $row['CategoryId'];
    } else {
        die("Không tìm thấy sản phẩm");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['Title']);
    $content    = trim($_POST['Content']);
    $img        = trim($_POST['Img']);
    $price      = (float)$_POST['Price'];
    $categoryId = (int)$_POST['CategoryId'];

    if ($id > 0) {
        $sql = "UPDATE product
                SET Title = ?, Content = ?, Img = ?, Price = ?, CategoryId = ?, UpdateAt = NOW()
                WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssddi", $title, $content, $img, $price, $categoryId, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $sql = "INSERT INTO product (Title, Content, Img, Price, CategoryId, CreateAt, UpdateAt)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdi", $title, $content, $img, $price, $categoryId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: product_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? "Sửa sản phẩm" : "Thêm sản phẩm"; ?></title>
</head>
<body>
    <h1><?php echo $id > 0 ? "Sửa sản phẩm" : "Thêm sản phẩm"; ?></h1>

    <form method="post">
        <p>
            <label>Tên sản phẩm:</label><br>
            <input type="text" name="Title" required
                value="<?php echo htmlspecialchars($title); ?>">
        </p>

        <p>
            <label>Mô tả:</label><br>
            <textarea name="Content" rows="4" cols="50"><?php
                echo htmlspecialchars($content);
            ?></textarea>
        </p>

        <p>
            <label>Giá:</label><br>
            <input type="number" name="Price" min="0" step="1000" required
                value="<?php echo htmlspecialchars($price); ?>">
        </p>

        <p>
            <label>Ảnh (chọn file):</label>
            <input type="file" name="ImageFile" accept="image/*">
            <br>
            <input type="hidden" name="ExistingImg" value="<?php echo htmlspecialchars($img); ?>">
        </p>

        <p>
            <label>Loại hàng (Category):</label><br>
            <select name="CategoryId" required>
                <option value="">-- Chọn loại --</option>
                <?php if ($catsResult && $catsResult->num_rows > 0): ?>
                    <?php while ($cat = $catsResult->fetch_assoc()): ?>
                        <option value="<?php echo $cat['Id']; ?>"
                            <?php echo ($categoryId == $cat['Id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['Title']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </p>

        <button type="submit">Lưu</button>
        <a href="product_list.php">Quay lại</a>
    </form>
</body>
</html>
