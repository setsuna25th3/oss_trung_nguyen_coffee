<?php
require_once __DIR__ . '/../../controllers/ProductAdminController.php';


$productAdminController = new ProductAdminController();
$productAdmins = $productAdminController->getAllProducts(0);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
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
                        <?php foreach ($productAdmins as $product): ?>
                            <tr>
                                <td><?php echo $product->Id; ?></td>
                                <td><?php echo $product->Title; ?></td>
                                <td>
                                    <span class="content-clamp"><?php echo $product->Content; ?></span>
                                </td>
                                <td>
                                    <img src="/oss_trung_nguyen_coffee/app/img/SanPham/<?php echo $product->Img; ?>"
                                        alt="<?php echo $product->Title; ?>">
                                </td>
                                <td><?php echo number_format($product->Price, 0, ",", "."); ?></td>
                                <td><?php echo $product->Rate; ?></td>
                                <td><?php echo $product->CreateAt; ?></td>
                                <td><?php echo $product->UpdateAt; ?></td>
                                <td><?php echo $product->CategoryId; ?></td>
                                <td><?php echo $product->CategoryTitle; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-4">
                <button class="btn btn-outline-dark mx-1">&laquo;</button>
                <button class="btn btn-outline-dark active mx-1">1</button>
                <button class="btn btn-outline-dark mx-1">2</button>
                <button class="btn btn-outline-dark mx-1">3</button>
                <button class="btn btn-outline-dark mx-1">&raquo;</button>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tên sản phẩm</label>
                        <input type="text" class="form-control" value="Cà phê Arabica">
                    </div>
                    <div class="mb-3">
                        <label>Nội dung</label>
                        <textarea class="form-control">Mô tả ngắn sản phẩm cà phê Arabica chất lượng cao...</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Giá</label>
                        <input type="text" class="form-control" value="100,000 VND">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Tên sản phẩm:</strong> Cà phê Arabica</p>
                    <p><strong>Nội dung:</strong> Mô tả ngắn sản phẩm cà phê Arabica chất lượng cao...</p>
                    <p><strong>Giá:</strong> 100,000 VND</p>
                    <p><strong>Ngày tạo:</strong> 2024-01-01</p>
                    <p><strong>Ngày cập nhật:</strong> 2024-01-05</p>
                    <p><strong>Mã danh mục:</strong> 1</p>
                    <p><strong>Tên danh mục:</strong> Cà phê rang</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Xóa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa sản phẩm này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>