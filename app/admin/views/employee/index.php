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
    $employeeAdmins = [];

    if ($customer && $customer->Role) {
        require_once __DIR__ . '/../../controllers/EmployeeAdminController.php'; 

        $employeeAdminController = new EmployeeAdminController();
        $employeeAdmins = $employeeAdminController->getAllEmployees();
    } else {
        header('Location: ../../../views/home/index.php');
        exit();
    }

    $employeesJson = json_encode($employeeAdmins, JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhân viên</title>
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
        <h1>Quản lý nhân viên</h1>

        <div class="table-wrapper">
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa fa-plus"></i> Thêm mới nhân viên
                </button>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="Tìm kiếm nhân viên...">
                    <button class="btn btn-primary">Tìm kiếm</button>
                    <button class="btn btn-outline-dark ms-2">Quay lại danh sách</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã nhân viên</th>
                            <th>Họ và tên</th>
                            <th>Mã cửa hàng</th>
                            <th>Mã chức vụ</th>
                            <th>Lương</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($employeeAdmins)): ?>
                            <?php foreach($employeeAdmins as $employee): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($employee->Id); ?></td>
                                    <td><?php echo htmlspecialchars($employee->Name); ?></td>
                                    <td><?php echo htmlspecialchars($employee->StoreId); ?></td>
                                    <td><?php echo htmlspecialchars($employee->RoleId); ?></td>
                                    <td><?php echo number_format($employee->Salary, 0, ',', '.'); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-bs-id="<?php echo $employee->Id; ?>"><i class="fa fa-edit"></i>Sửa</button>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal" data-bs-id="<?php echo $employee->Id; ?>"><i class="fa fa-eye"></i>Chi tiết</button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="<?php echo $employee->Id; ?>" data-bs-name="<?php echo htmlspecialchars($employee->Name); ?>"><i class="fa fa-trash"></i>Xóa</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có nhân viên nào được tìm thấy.</td>
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

    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="process_create_employee.php">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm nhân viên mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Họ và tên</label>
                        <input type="text" class="form-control" id="create-name" name="Name" placeholder="Nhập họ tên nhân viên" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-storeid">Mã cửa hàng</label>
                        <input type="number" class="form-control" id="create-storeid" name="StoreId" placeholder="Nhập StoreId" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-roleid">Mã chức vụ</label>
                        <input type="number" class="form-control" id="create-roleid" name="RoleId" placeholder="Nhập RoleId" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-salary">Lương</label>
                        <input type="number" class="form-control" id="create-salary" name="Salary" placeholder="Nhập lương (VD: 15000000)" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="process_edit_employee.php">
                <input type="hidden" name="EmployeeId" id="edit-employee-id">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name">Họ và tên</label>
                        <input type="text" class="form-control" id="edit-name" name="Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-storeid">Mã cửa hàng</label>
                        <input type="number" class="form-control" id="edit-storeid" name="StoreId" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-roleid">Mã chức vụ</label>
                        <input type="number" class="form-control" id="edit-roleid" name="RoleId" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-salary">Lương</label>
                        <input type="number" class="form-control" id="edit-salary" name="Salary" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Mã nhân viên:</strong> <span id="view-id"></span></p>
                    <p><strong>Họ và tên:</strong> <span id="view-name"></span></p>
                    <p><strong>Mã cửa hàng:</strong> <span id="view-storeid"></span></p>
                    <p><strong>Mã chức vụ:</strong> <span id="view-roleid"></span></p>
                    <p><strong>Lương:</strong> <span id="view-salary"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Xóa nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa nhân viên **<span id="delete-employee-name-display" class="fw-bold"></span>** (Mã NV: <span id="delete-employee-id-display"></span>) này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <a id="confirmDeleteLink" class="btn btn-danger" href="#">Xóa</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const employeesData = <?php echo $employeesJson; ?>;

        function formatSalary(number) {
            return new Intl.NumberFormat('vi-VN').format(number);
        }

        const viewModal = document.getElementById('viewModal');
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');

        const modalElements = [viewModal, editModal, deleteModal];

        modalElements.forEach(modalElement => {
            modalElement.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; 
                const employeeId = button ? button.getAttribute('data-bs-id') : null;
                
                if (employeeId) {
                    const employee = employeesData.find(e => e.Id == employeeId);

                    if (!employee) {
                        console.error('Không tìm thấy nhân viên với ID:', employeeId);
                        return;
                    }

                    if (modalElement.id === 'viewModal') {
                        document.getElementById('view-id').innerText = employee.Id;
                        document.getElementById('view-name').innerText = employee.Name;
                        document.getElementById('view-storeid').innerText = employee.StoreId;
                        document.getElementById('view-roleid').innerText = employee.RoleId;
                        document.getElementById('view-salary').innerText = formatSalary(employee.Salary);
                    }

                    if (modalElement.id === 'editModal') {
                        document.getElementById('edit-employee-id').value = employee.Id;
                        document.getElementById('edit-name').value = employee.Name;
                        document.getElementById('edit-storeid').value = employee.StoreId;
                        document.getElementById('edit-roleid').value = employee.RoleId;
                        document.getElementById('edit-salary').value = employee.Salary; 
                    }

                    if (modalElement.id === 'deleteModal') {
                        const employeeName = button.getAttribute('data-bs-name');
                        document.getElementById('delete-employee-id-display').innerText = employee.Id;
                        document.getElementById('delete-employee-name-display').innerText = employeeName;
                        document.getElementById('confirmDeleteLink').href = 'process_delete_employee.php?id=' + employee.Id;
                    }
                }
            });
        });
    </script>
</body>

</html>