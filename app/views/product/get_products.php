<style>
    .add_to_cart {
        display: inline-block;
        width: 100%;
        padding: 12px 0;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        color: white;
        background: linear-gradient(45deg, #ffb300, #ff9800);
        border: none;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .add_to_cart:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        background: linear-gradient(45deg, #ff9800, #ffb300);
    }

    .add_to_cart:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }
</style>
<?php
require_once '../../controllers/ProductController.php';

$productController = new ProductController();

$storeId = intval($_GET['store'] ?? 0);
$categoryId = intval($_GET['category'] ?? 0);
$searchString = $_GET['searchString'] ?? '';
$sort = $_GET['sort'] ?? '';

$products = $productController->getAllProducts($storeId, $categoryId, $sort, $searchString);

if (empty($products)) {
    echo '<p>Không có sản phẩm nào.</p>';
    exit;
}

foreach ($products as $product):
    $p_Id = is_object($product) ? $product->Id : $product['Id'];
    $p_Title = is_object($product) ? $product->Title : $product['Title'];
    $p_Content = is_object($product) ? $product->Content : $product['Content'];
    $p_Price = is_object($product) ? $product->Price : $product['Price'];
    $p_Img = is_object($product) ? $product->Img : $product['Img'];
?>
    <div class="card">
        <a href="detail.php?id=<?= $p_Id ?>">
            <img src="../../img/SanPham/<?= $p_Img ?: 'sample.jpg' ?>" alt="<?= htmlspecialchars($p_Title) ?>">
        </a>
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($p_Title) ?></h5>
            <p class="card-text"><?= htmlspecialchars(substr($p_Content, 0, 100)) ?></p>
            <span class="price"><?= number_format($p_Price, 0, ',', '.') ?> VND</span>
            <!-- Bỏ form đi, dùng button AJAX -->
            <button class="add_to_cart" data-id="<?= $p_Id ?>">Thêm vào giỏ</button>
        </div>
    </div>
<?php endforeach; ?>