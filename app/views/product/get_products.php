<?php
require_once '../../controllers/ProductController.php';

$store = $_GET['store'] ?? 0;
$category = $_GET['category'] ?? 0;
$search = $_GET['searchString'] ?? '';
$sort = $_GET['sort'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 6;

$productController = new ProductController();
$products = $productController->getProducts($store, $category, $search, $sort, ($page - 1) * $limit, $limit);
$totalProducts = $productController->countProducts($store, $category, $search);
$totalPages = ceil($totalProducts / $limit);

// Bao container
echo '<div class="products-container">';

// Grid chứa các card
echo '<div class="products-grid">';
if (!empty($products)) {
    foreach ($products as $product) {
        echo '<div class="card">';
        echo '<img src="/oss_trung_nguyen_coffee/app/img/SanPham/' . htmlspecialchars($product->Img) . '" alt="' . htmlspecialchars($product->Title) . '">';
        echo '<div class="card-body">';
        echo '<h4 class="card-title">' . htmlspecialchars($product->Title) . '</h4>';
        echo '<p class="card-text">' . htmlspecialchars($product->Content) . '</p>';
        echo '<span class="price">' . number_format($product->Price) . '₫</span>';
        echo '<button class="btn add_to_cart" data-id="' . $product->Id . '">Thêm vào giỏ</button>';
        echo '</div></div>';
    }
} else {
    echo '<p>Không có sản phẩm nào.</p>';
}
echo '</div>'; // end .products-grid

// Pagination nằm dưới grid
if ($totalPages > 1) {
    echo '<div class="pagination-container">';
    echo '<ul class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i == $page ? 'active' : '';
        echo '<li><a href="#" class="page-link ' . $active . '" data-page="' . $i . '">' . $i . '</a></li>';
    }
    echo '</ul>';
    echo '</div>'; // end .pagination-container
}

echo '</div>'; // end .products-container
