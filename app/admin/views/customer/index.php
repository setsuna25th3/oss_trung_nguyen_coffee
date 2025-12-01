<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khách hàng</title>
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

        .img-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
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
        <h1>Quản lý khách hàng</h1>

        <div class="table-wrapper">
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa fa-plus"></i> Thêm khách hàng
                </button>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="Tìm kiếm khách hàng...">
                    <button class="btn btn-primary">Tìm kiếm</button>
                    <button class="btn btn-outline-dark ms-2">Quay lại danh sách</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã khách hàng</th>
                            <th>Họ và Tên</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Ngày sinh</th>
                            <th>Ảnh</th>
                            <th>Ngày đăng ký</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Demo row -->
                        <tr>
                            <td>1</td>
                            <td>Nguyen Van A</td>
                            <td>nguyenvana@gmail.com</td>
                            <td>0123456789</td>
                            <td>123 Lê Lợi, TP.HCM</td>
                            <td>1990-01-01</td>
                            <td><img src="https://via.placeholder.com/60" class="img-avatar" /></td>
                            <td>2024-01-01</td>
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

    <!-- Modals (Create, Edit, View, Delete) -->
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm khách hàng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>First Name</label>
                        <input type="text" class="form-control" placeholder="Nhập họ">
                    </div>
                    <div class="mb-3">
                        <label>Last Name</label>
                        <input type="text" class="form-control" placeholder="Nhập tên">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="Nhập email">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" placeholder="Nhập số điện thoại">
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" class="form-control" placeholder="Nhập địa chỉ">
                    </div>
                    <div class="mb-3">
                        <label>Ngày sinh</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Ảnh đại diện</label>
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
                    <h5 class="modal-title">Sửa thông tin khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>First Name</label>
                        <input type="text" class="form-control" value="Nguyen">
                    </div>
                    <div class="mb-3">
                        <label>Last Name</label>
                        <input type="text" class="form-control" value="Van A">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" value="nguyenvana@gmail.com">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" value="0123456789">
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" class="form-control" value="123 Lê Lợi, TP.HCM">
                    </div>
                    <div class="mb-3">
                        <label>Ngày sinh</label>
                        <input type="date" class="form-control" value="1990-01-01">
                    </div>
                    <div class="mb-3">
                        <label>Ảnh đại diện</label>
                        <input type="file" class="form-control">
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
                    <h5 class="modal-title">Chi tiết khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Họ và Tên:</strong> Nguyen Van A</p>
                    <p><strong>Email:</strong> nguyenvana@gmail.com</p>
                    <p><strong>Phone:</strong> 0123456789</p>
                    <p><strong>Address:</strong> 123 Lê Lợi, TP.HCM</p>
                    <p><strong>Ngày sinh:</strong> 1990-01-01</p>
                    <p><strong>Ngày đăng ký:</strong> 2024-01-01</p>
                    <p><strong>Ảnh:</strong> <img src="https://via.placeholder.com/60" class="img-avatar" /></p>
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
                    <h5 class="modal-title text-danger">Xóa khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa khách hàng này không?
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