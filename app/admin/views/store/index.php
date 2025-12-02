<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý cửa hàng</title>
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
    </style>
</head>

<body>
    <div class="container my-5">
        <h1>Quản lý cửa hàng</h1>

        <div class="table-wrapper">
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa fa-plus"></i> Thêm cửa hàng mới
                </button>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="Tìm kiếm cửa hàng...">
                    <button class="btn btn-primary">Tìm kiếm</button>
                    <button class="btn btn-outline-dark ms-2">Quay lại danh sách</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã cửa hàng</th>
                            <th>Tên cửa hàng</th>
                            <th>Địa chỉ</th>
                            <th>Điện thoại</th>
                            <th>Giờ mở cửa</th>
                            <th>Giờ đóng cửa</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dữ liệu mẫu -->
                        <tr>
                            <td>1</td>
                            <td>Cửa hàng Trung Nguyên 1</td>
                            <td>123 Lê Lợi, TP.HCM</td>
                            <td>0123456789</td>
                            <td>07:00</td>
                            <td>22:00</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-edit"></i>Sửa</button>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal"><i class="fa fa-eye"></i>Chi tiết</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i>Xóa</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Cửa hàng Trung Nguyên 2</td>
                            <td>456 Hai Bà Trưng, Hà Nội</td>
                            <td>0987654321</td>
                            <td>08:00</td>
                            <td>21:00</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-edit"></i>Sửa</button>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal"><i class="fa fa-eye"></i>Chi tiết</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i>Xóa</button>
                            </td>
                        </tr>
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
                    <h5 class="modal-title">Thêm cửa hàng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tên cửa hàng</label>
                        <input type="text" class="form-control" placeholder="Nhập tên cửa hàng">
                    </div>
                    <div class="mb-3">
                        <label>Địa chỉ</label>
                        <input type="text" class="form-control" placeholder="Nhập địa chỉ">
                    </div>
                    <div class="mb-3">
                        <label>Điện thoại</label>
                        <input type="text" class="form-control" placeholder="Nhập số điện thoại">
                    </div>
                    <div class="mb-3">
                        <label>Giờ mở cửa</label>
                        <input type="text" class="form-control" placeholder="Nhập giờ mở cửa">
                    </div>
                    <div class="mb-3">
                        <label>Giờ đóng cửa</label>
                        <input type="text" class="form-control" placeholder="Nhập giờ đóng cửa">
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
                    <h5 class="modal-title">Sửa cửa hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tên cửa hàng</label>
                        <input type="text" class="form-control" value="Cửa hàng Trung Nguyên 1">
                    </div>
                    <div class="mb-3">
                        <label>Địa chỉ</label>
                        <input type="text" class="form-control" value="123 Lê Lợi, TP.HCM">
                    </div>
                    <div class="mb-3">
                        <label>Điện thoại</label>
                        <input type="text" class="form-control" value="0123456789">
                    </div>
                    <div class="mb-3">
                        <label>Giờ mở cửa</label>
                        <input type="text" class="form-control" value="07:00">
                    </div>
                    <div class="mb-3">
                        <label>Giờ đóng cửa</label>
                        <input type="text" class="form-control" value="22:00">
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
                    <h5 class="modal-title">Chi tiết cửa hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Tên cửa hàng:</strong> Cửa hàng Trung Nguyên 1</p>
                    <p><strong>Địa chỉ:</strong> 123 Lê Lợi, TP.HCM</p>
                    <p><strong>Điện thoại:</strong> 0123456789</p>
                    <p><strong>Giờ mở cửa:</strong> 07:00</p>
                    <p><strong>Giờ đóng cửa:</strong> 22:00</p>
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
                    <h5 class="modal-title text-danger">Xóa cửa hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa cửa hàng này không?
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