<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hóa đơn</title>
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
        <h1>Quản lý hóa đơn</h1>

        <div class="table-wrapper">
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa fa-plus"></i> Tạo hóa đơn mới
                </button>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="Tìm kiếm hóa đơn...">
                    <button class="btn btn-primary">Tìm kiếm</button>
                    <button class="btn btn-outline-dark ms-2">Quay lại danh sách</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Khách hàng</th>
                            <th>Cửa hàng</th>
                            <th>Tổng tiền</th>
                            <th>Đơn vị vận chuyển</th>
                            <th>Mã vận đơn</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dữ liệu mẫu -->
                        <tr>
                            <td>1</td>
                            <td>Nguyễn Văn A</td>
                            <td>Cửa hàng Trung Nguyên 1</td>
                            <td>1,200,000 VND</td>
                            <td>Giao hàng nhanh</td>
                            <td>GHN123456</td>
                            <td>Đang xử lý</td>
                            <td>2025-12-01</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal"><i class="fa fa-eye"></i>Chi tiết</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i>Xóa</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Trần Thị B</td>
                            <td>Cửa hàng Trung Nguyên 2</td>
                            <td>850,000 VND</td>
                            <td>VNPost</td>
                            <td>VN456789</td>
                            <td>Đã giao</td>
                            <td>2025-12-02</td>
                            <td>
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

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết hóa đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Khách hàng:</strong> Nguyễn Văn A</p>
                    <p><strong>Cửa hàng:</strong> Cửa hàng Trung Nguyên 1</p>
                    <p><strong>Tổng tiền:</strong> 1,200,000 VND</p>
                    <p><strong>Đơn vị vận chuyển:</strong> Giao hàng nhanh</p>
                    <p><strong>Mã vận đơn:</strong> GHN123456</p>
                    <p><strong>Trạng thái:</strong> Đang xử lý</p>
                    <p><strong>Ngày tạo:</strong> 2025-12-01</p>
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
                    <h5 class="modal-title text-danger">Xóa hóa đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa hóa đơn này không?
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