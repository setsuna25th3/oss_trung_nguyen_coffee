<?php
require_once "models/ProductModel.php";

class ProductController {

    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    // Danh sách sản phẩm + search + filter
    public function index() {
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';

        $products = $this->model->getAll($search, $category);
        $categories = $this->model->getCategories();

        include "views/products/index.php";
    }

    // Chi tiết sản phẩm
    public function detail($id) {
        if (!$id) die("ID không hợp lệ");

        $product = $this->model->getById($id);
        include "views/products/detail.php";
    }

    // Sản phẩm bán chạy
    public function bestSeller() {
        $products = $this->model->getBestSeller();
        include "views/products/best_seller.php";
    }
}
