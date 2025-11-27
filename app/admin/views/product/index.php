<?php
    include '../../controllers/ProductController.php';
    include '../../controllers/CategoryController.php';
    include '../../controllers/StoreController.php';

    $categoryId = isset($_GET['category']) ? intval($_GET['category']) : 0;
    $storeId = isset($_GET['store']) ? intval($_GET['store']) : 0;

    $categoryController = new CategoryController();
    $categories = $categoryController->getAllCategories();

    $storeController = new StoreController();
    $stores = $storeController->getAllStores();

    $productController = new ProductController();
    $products = $productController->getAllProducts($storeId, $categoryId);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách mặt hàng</title>
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
    <h1>Danh sách mặt hàng</h1>

    <div class="toolbar">
        <form method="get" action="index.php">

            <select name="store">
                <option value="0">-- Cửa hàng --</option>
                <?php if (!empty($stores)): ?>
                    <?php foreach ($stores as $s): ?>
                        <?php
                            $s_Id = is_object($s) ? $s->Id : (isset($s['Id']) ? $s['Id'] : 0);
                            $s_Name = is_object($s) ? $s->StoreName : (isset($s['StoreName']) ? $s['StoreName'] : '');
                        ?>
                        <option value="<?php echo $s_Id; ?>" <?php echo $storeId == $s_Id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s_Name); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <select name="category">
                <option value="0">-- Loại hàng --</option>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <?php
                            $c_Id = is_object($cat) ? $cat->Id : (isset($cat['Id']) ? $cat['Id'] : 0);
                            $c_Title = is_object($cat) ? $cat->Title : (isset($cat['Title']) ? $cat['Title'] : '');
                        ?>
                        <option value="<?php echo $c_Id; ?>" <?php echo $categoryId == $c_Id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c_Title); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <input type="submit" value="Lọc">
            <a href="index.php">Reset</a>
        </form>
    </div>

    <table>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Ảnh</th>
        </tr>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $row): ?>
                <?php
                    $id = is_object($row) ? $row->Id : ($row['Id'] ?? '');
                    $title = is_object($row) ? $row->Title : ($row['Title'] ?? '');
                    $price = is_object($row) ? ($row->Price ?? 0) : ($row['Price'] ?? 0);
                    $img = is_object($row) ? ($row->Img ?? '') : ($row['Img'] ?? '');
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($title); ?></td>
                    <td><?php echo number_format($price, 0, ',', '.'); ?> đ</td>
                    <td>
                    <?php if (!empty($img)): ?>
                        <img src="../img/SanPham/<?php echo htmlspecialchars($img); ?>" alt="">
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Không có sản phẩm</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>