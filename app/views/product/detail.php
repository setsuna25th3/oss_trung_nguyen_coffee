<?php
    session_start();
    require_once '../../controllers/ProductController.php';
    require_once '../../controllers/CategoryController.php';
    
    $productController = new ProductController();
    $categoryController = new CategoryController();
    $categoryId = isset($_GET['category']) ? intval($_GET['category']) : 0;
    $storeId = 0;
    $productId = $_GET['id'];

    $product = $productController->getProductById($productId);
    $featuredProducts = $productController->getFeaturedProducts($_SESSION['currentStoreId'] ?? 0, 3);
    $relatedProducts = $productController->getRelatedProducts($_SESSION['currentStoreId'] ?? 0, $productId);
    $categories = $categoryController->getAllCategories();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm - Trung Nguyên Cà Phê</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #fff1e0;
        }

        .page-header {
            margin-top: 80px;

            padding: 120px 0 60px;
            background-size: cover;
            background-position: center;
            position: relative;
            background-image: url('https://thesaigontimes.vn/wp-content/uploads/2022/07/Dungdequatre.jpeg.jpg');
        }

        .page-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .page-header h1,
        .page-header .breadcrumb {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
        }

        .page-header h1 {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .breadcrumb {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
            font-size: 16px;
            padding: 0;
        }

        .breadcrumb a {
            color: #fff1e0;
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb a:hover {
            color: #ffb300;
        }

        .breadcrumb .active {
            color: #ffb300;
            font-weight: bold;
        }

        .breadcrumb span.separator {
            color: white;
        }

        .breadcrumb-item a {
            color: white;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: yellow;
            transform: scale(1.2);
            transition: 0.3s;
        }

        .img-thumbnail {
            object-fit: cover;
        }

        .tab-content p {
            font-size: 14px;
        }


        .input-group.quantity input#quantity {
            width: 35px;
            padding: 5px 10px;
            font-size: 16px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #ccc;

            /* Ẩn spinner mặc định */
            -moz-appearance: textfield;
        }

        .input-group.quantity input#quantity::-webkit-inner-spin-button,
        .input-group.quantity input#quantity::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .input-group.quantity button {
            width: 35px;
            /* tăng size nút +/- */
            height: 35px;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <?php include '../header.php';?>
    <!-- Page Header -->
    <div class="container-fluid page-header">
        <h1>Chi tiết sản phẩm</h1>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
            <span class="separator">/</span>
            <li class="breadcrumb-item"><a href="product.php">Cửa hàng</a></li>
            <span class="separator">/</span>
            <li class="breadcrumb-item active">Chi tiết sản phẩm</li>
        </ul>
    </div>


    <!-- Single Product -->
    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <!-- Product Detail -->
                <div class="col-lg-8 col-xl-9">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                <img src="../../img/SanPham/<?php echo $product->Img; ?>" class="img-thumbnail rounded" alt="<?php echo $product->Title; ?>" style="width: 100%;">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="fw-bold mb-3"><?php echo $product->Title; ?></h4>
                            <p class="mb-3">Danh mục: <?php echo $product->CategoryTitle; ?></p>
                            <h5 class="fw-bold mb-3"><?php echo number_format($product->Price, 0, ",", "."); ?> VND</h5>

                            <div class="d-flex mb-4">
                                <p class="me-2"><?php echo $product->Rate; ?></p>
                                <i class="fa fa-star text-secondary"></i>
                            </div>

                            <p class="mb-4"><?php echo $product->Content; ?></p>

                            <div class="input-group quantity mb-5" style="width: 120px;">
                                <button class="btn btn-light border rounded-circle" id="decreaseQuantity"><i class="fa fa-minus"></i></button>
                                <input type="number" id="quantity" class="form-control text-center" value="1" min="1">
                                <button class="btn btn-light border rounded-circle" id="increaseQuantity"><i class="fa fa-plus"></i></button>
                            </div>

                            <form method="post" action="add_to_cart.php?id=<?php echo $product->Id; ?>">
                                <input type="hidden" id="hiddenQuantity" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-primary rounded-pill px-4 py-2 mb-4">
                                    <i class="fa fa-shopping-bag me-2"></i>Thêm
                                </button>
                            </form>

                            <script>
                                const decreaseBtn = document.getElementById('decreaseQuantity');
                                const increaseBtn = document.getElementById('increaseQuantity');
                                const quantityInput = document.getElementById('quantity');
                                const hiddenInput = document.getElementById('hiddenQuantity');

                                decreaseBtn.addEventListener('click', () => {
                                    let q = parseInt(quantityInput.value);
                                    if (q > 1) quantityInput.value = q - 1;
                                    hiddenInput.value = quantityInput.value;
                                });
                                increaseBtn.addEventListener('click', () => {
                                    quantityInput.value = parseInt(quantityInput.value) + 1;
                                    hiddenInput.value = quantityInput.value;
                                });
                                quantityInput.addEventListener('input', () => hiddenInput.value = quantityInput.value);
                            </script>

                        </div>

                        <!-- Tabs -->
                        <div class="col-lg-12 mt-4">
                            <ul class="nav nav-tabs mb-3" id="productTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab">Mô tả sản phẩm</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button" role="tab">Nhận xét</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="productTabContent">
                                <div class="tab-pane fade show active" id="desc" role="tabpanel">
                                    <p><?php echo $product->Content; ?></p>
                                </div>
                                <div class="tab-pane fade" id="review" role="tabpanel">
                                    <div class="d-flex mb-3">
                                        <img src="img/avatar-trang-4.jpg" class="rounded-circle p-3" style="width:100px; height:100px;">
                                        <div>
                                            <p class="mb-1" style="font-size:14px">April 12, 2024</p>
                                            <h5>Chí Trường</h5>
                                            <div class="d-flex mb-2">
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <p>Sản phẩm chất lượng, giá rẻ</p>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <img src="img/avatar-trang-4.jpg" class="rounded-circle p-3" style="width:100px; height:100px;">
                                        <div>
                                            <p class="mb-1" style="font-size:14px">April 12, 2024</p>
                                            <h5>Ngọc Trinh</h5>
                                            <div class="d-flex mb-2">
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <p>Sản phẩm phù hợp với giá tiền nhưng hương vị chưa ngon lắm.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4 col-xl-3">
                    <!-- Sidebar Search -->
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="search" class="form-control" placeholder="Tìm kiếm" aria-label="Search">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h5>Danh mục</h5>
                        <ul>
                            <?php if (!empty($categories)): ?>
                                <li><a href="index.php<?php echo $storeId > 0 ? '?store=' . $storeId : ''; ?>" style="text-decoration: none";>Tất cả danh mục</a></li>
                                <?php foreach ($categories as $cat): ?>
                                    <?php
                                        $c_Id = is_object($cat) ? $cat->Id : (isset($cat['Id']) ? $cat['Id'] : 0);
                                        $c_Title = is_object($cat) ? $cat->Title : (isset($cat['Title']) ? $cat['Title'] : '');
                                    ?>
                                    <li>
                                        <a href="index.php?category=<?php echo $c_Id; ?><?php echo $storeId > 0 ? '&store=' . $storeId : ''; ?>"
                                            <?php echo $categoryId == $c_Id ? 'style="color: #ffb300; font-weight: bold;"' : ''; ?>
                                            style="text-decoration: none;">
                                            <?php echo htmlspecialchars($c_Title); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><span>Không có danh mục</span></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div>
                        <h5>Sản phẩm nổi bật</h5>
                        <?php foreach ($featuredProducts as $fp): ?>
                            <a href="detail.php?id=<?php echo $fp->Id; ?>" style="text-decoration: none; color: inherit;">
                                <div class="d-flex mb-2">
                                    <img src="../../img/SanPham/<?php echo $fp->Img; ?>" style="width:60px; height:60px; object-fit:cover; margin-right:10px;">
                                    <div>
                                        <h6><?php echo $fp->Title; ?></h6>
                                        <p><?php echo number_format($fp->Price, 0, ",", "."); ?> VND</p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- Related Products -->
            <h4 class="fw-bold mb-3">Sản phẩm liên quan</h4>
            <div class="row g-3">
                <?php foreach ($relatedProducts as $rp): ?>
                    <div class="col-md-4">
                        <a href="detail.php?id=<?php echo $rp->Id; ?>" style="text-decoration: none; color: inherit;">
                            <div class="border p-2">
                                <img src="../../img/SanPham/<?php echo $rp->Img; ?>" class="img-fluid">
                                <h6 class="mt-2"><?php echo $rp->Title; ?></h6>
                                <p><?php echo number_format($rp->Price, 0, ",", "."); ?> VND</p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <?php include '../footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>