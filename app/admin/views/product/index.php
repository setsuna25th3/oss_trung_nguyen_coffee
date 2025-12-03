<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['CustomerId'])) {
    header('Location: ../../../views/home/index.php');
    exit();
}

require_once __DIR__ . '/../../../controllers/CustomerController.php';
$customerController = new CustomerController();

$customer = $customerController->getCustomerById($_SESSION['CustomerId']);
$productAdmins = [];

if ($customer && $customer->Role) {
    require_once __DIR__ . '/../../controllers/ProductAdminController.php';

    $productAdminController = new ProductAdminController();
    $productAdmins = $productAdminController->getAllProducts(0);
} else {
    header('Location: ../../../views/home/index.php');
    exit();
}

$productsJson = json_encode($productAdmins, JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel-stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #343a40;
            font-weight: 700;
        }

        .table-wrapper {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #343a40;
            color: #fff;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn-add {
            margin-bottom: 15px;
        }

        .pagination .btn {
            min-width: 40px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }

        td img {
            width: 100px;
            height: 60px;
            object-fit: contain;
        }

        td span.content-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5rem;
            height: 4.5rem;
        }

        .modal-backdrop-white {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            width: 100vw;
            height: 100vh;
            background-color: #ffffff;
            opacity: 0.8;
            transition: opacity 0.15s linear;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h1>Quản lý sản phẩm</h1>

        <div class="table-wrapper">
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa fa-plus"></i> Thêm mới sản phẩm
                </button>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="Tìm kiếm sản phẩm...">
                    <button class="btn btn-primary">Tìm kiếm</button>
                    <button class="btn btn-outline-dark ms-2">Quay lại danh sách</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã SP</th>
                            <th>Tên sản phẩm</th>
                            <th>Nội dung</th>
                            <th>Hình ảnh</th>
                            <th>Giá</th>
                            <th>Đánh giá</th>
                            <th>Ngày tạo</th>
                            <th>Ngày cập nhật</th>
                            <th>Mã danh mục</th>
                            <th>Tên danh mục</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($productAdmins)): ?>
                            <?php foreach ($productAdmins as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product->Id); ?></td>
                                    <td><?php echo htmlspecialchars($product->Title); ?></td>
                                    <td>
                                        <span class="content-clamp"><?php echo htmlspecialchars($product->Content); ?></span>
                                    </td>
                                    <td>
                                        <img src="/oss_trung_nguyen_coffee/app/img/SanPham/<?php echo htmlspecialchars($product->Img); ?>" alt="<?php echo htmlspecialchars($product->Title); ?>">
                                    </td>
                                    <td><?php echo number_format($product->Price, 0, ",", "."); ?></td>
                                    <td><?php echo htmlspecialchars($product->Rate); ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($product->CreateAt)); ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($product->UpdateAt)); ?></td>
                                    <td><?php echo htmlspecialchars($product->CategoryId); ?></td>
                                    <td><?php echo htmlspecialchars($product->CategoryTitle); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal" data-bs-id="<?php echo $product->Id; ?>"><i class="fa fa-eye"></i></button>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-bs-id="<?php echo $product->Id; ?>"><i class="fa fa-edit"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center">Không có sản phẩm nào được tìm thấy.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button class="btn btn-outline-dark mx-1">&laquo;</button>
                <button class="btn btn-outline-dark active mx-1">1</button>
                <button class="btn btn-outline-dark mx-1">2</button>
                <button class="btn btn-outline-dark mx-1">3</button>
                <button class="btn btn-outline-dark mx-1">&raquo;</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm sản phẩm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tên sản phẩm</label>
                        <input type="text" class="form-control" placeholder="Nhập tên sản phẩm">
                    </div>
                    <div class="mb-3">
                        <label>Nội dung</label>
                        <textarea class="form-control" placeholder="Nhập mô tả sản phẩm"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Giá</label>
                        <input type="text" class="form-control" placeholder="Nhập giá sản phẩm">
                    </div>
                    <div class="mb-3">
                        <label>Hình ảnh</label>
                        <input type="file" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="process_edit.php">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="productId" id="edit-product-id">
                    <div class="mb-3">
                        <label for="edit-title">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="edit-title" name="title">
                    </div>
                    <div class="mb-3">
                        <label for="edit-content">Nội dung</label>
                        <textarea class="form-control" id="edit-content" name="content"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-price">Giá</label>
                        <input type="number" class="form-control" id="edit-price" name="price" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="edit-category-id">Mã danh mục</label>
                        <input type="number" class="form-control" id="edit-category-id" name="category_id">
                    </div>
                    <div class="mb-3">
                        <label for="edit-image-file">Hình ảnh (Để trống nếu không thay đổi)</label>
                        <input type="file" class="form-control" id="edit-image-file" name="image">
                        <p class="mt-2">Ảnh hiện tại: <img id="edit-current-img" src="" style="width: 50px; height: 30px; object-fit: cover;"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="view-modal-body">
                    <p><strong>Mã SP:</strong> <span id="view-id"></span></p>
                    <p><strong>Tên sản phẩm:</strong> <span id="view-title"></span></p>
                    <p><strong>Nội dung:</strong> <span id="view-content"></span></p>
                    <p><strong>Giá:</strong> <span id="view-price"></span></p>
                    <p><strong>Đánh giá:</strong> <span id="view-rate"></span></p>
                    <p><strong>Ngày tạo:</strong> <span id="view-create-at"></span></p>
                    <p><strong>Ngày cập nhật:</strong> <span id="view-update-at"></span></p>
                    <p><strong>Mã danh mục:</strong> <span id="view-category-id"></span></p>
                    <p><strong>Tên danh mục:</strong> <span id="view-category-title"></span></p>
                    <div class="text-center">
                        <img id="view-img" src="" alt="Hình ảnh sản phẩm" class="img-fluid rounded mt-3" style="max-height: 200px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const productsData = <?php echo $productsJson; ?>;

        const IMG_BASE_PATH = '/oss_trung_nguyen_coffee/app/img/SanPham/';

        function formatCurrency(number) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(number);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        const viewModal = document.getElementById('viewModal');
        const editModal = document.getElementById('editModal');
        const createModal = document.getElementById('createModal');

        const modalElements = [createModal, viewModal, editModal];

        function showWhiteBackdrop() {
            const backdrop = document.createElement('div');
            backdrop.classList.add('modal-backdrop-white');
            document.body.appendChild(backdrop);
        }

        function removeWhiteBackdrop() {
            const backdrop = document.querySelector('.modal-backdrop-white');
            if (backdrop) {
                backdrop.remove();
            }
        }

        modalElements.forEach(modalElement => {
            modalElement.addEventListener('show.bs.modal', function(event) {
                showWhiteBackdrop();

                const button = event.relatedTarget;
                if (button) {
                    const productId = button.getAttribute('data-bs-id');

                    const product = productsData.find(p => p.Id == productId);

                    if (!product) {
                        console.error('Không tìm thấy sản phẩm với ID:', productId);
                        return;
                    }

                    if (modalElement.id === 'viewModal') {
                        document.getElementById('view-id').innerText = product.Id;
                        document.getElementById('view-title').innerText = product.Title;
                        document.getElementById('view-content').innerText = product.Content;
                        document.getElementById('view-price').innerText = formatCurrency(product.Price);
                        document.getElementById('view-rate').innerText = product.Rate;
                        document.getElementById('view-create-at').innerText = formatDate(product.CreateAt);
                        document.getElementById('view-update-at').innerText = formatDate(product.UpdateAt);
                        document.getElementById('view-category-id').innerText = product.CategoryId;
                        document.getElementById('view-category-title').innerText = product.CategoryTitle;
                        document.getElementById('view-img').src = IMG_BASE_PATH + product.Img;
                    }

                    if (modalElement.id === 'editModal') {
                        document.getElementById('edit-product-id').value = product.Id;
                        document.getElementById('edit-title').value = product.Title;
                        document.getElementById('edit-content').value = product.Content;
                        document.getElementById('edit-price').value = product.Price;
                        document.getElementById('edit-category-id').value = product.CategoryId;
                        document.getElementById('edit-current-img').src = IMG_BASE_PATH + product.Img;
                    }
                }
            });

            modalElement.addEventListener('hidden.bs.modal', function() {
                removeWhiteBackdrop();
            });
        });
    </script>
</body>

</html>