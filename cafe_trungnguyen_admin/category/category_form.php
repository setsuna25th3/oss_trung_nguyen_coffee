<?php
require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$title = "";
$content = "";

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM category WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cat = $result->fetch_assoc();
    $stmt->close();

    if ($cat) {
        $title = $cat['Title'];
        $content = $cat['Content'];
    } else {
        die("Không tìm thấy loại hàng");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['Title']);
    $content = trim($_POST['Content']);

    if ($id > 0) {
        $sql = "UPDATE category
                SET Title = ?, Content = ?, UpdateAt = NOW()
                WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $sql = "INSERT INTO category (Title, Content, CreateAt, UpdateAt)
                VALUES (?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: category_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id > 0 ? "Sửa loại hàng" : "Thêm loại hàng"; ?></title>
</head>
<body>
    <h1><?php echo $id > 0 ? "Sửa loại hàng" : "Thêm loại hàng"; ?></h1>

    <form method="post">
        <p>
            <label>Tên loại hàng:</label><br>
            <input type="text" name="Title"
                value="<?php echo htmlspecialchars($title); ?>" required>
        </p>
        <p>
            <label>Mô tả:</label><br>
            <textarea name="Content" rows="4" cols="50"><?php
                echo htmlspecialchars($content);
            ?></textarea>
        </p>
        <button type="submit">Lưu</button>
        <a href="category_list.php">Quay lại</a>
    </form>
</body>
</html>
