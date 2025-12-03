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
    $roleAdmins = [];

    if ($customer && $customer->Role) {
        require_once __DIR__ . '/../../controllers/RoleAdminController.php'; 

        $roleAdminController = new RoleAdminController();
        $roleAdmins = $roleAdminController->getAllRoles();
    } else {
        header('Location: ../../../views/home/index.php');
        exit();
    }

    $rolesJson = json_encode($roleAdmins, JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý chức vụ</title>
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
        <h1>Quản lý chức vụ</h1>

        <div class="table-wrapper">
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa fa-plus"></i> Thêm chức vụ
                </button>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="Tìm kiếm chức vụ...">
                    <button class="btn btn-primary">Tìm kiếm</button>
                    <button class="btn btn-outline-dark ms-2">Quay lại danh sách</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Tên chức vụ</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($roleAdmins)): ?>
                            <?php foreach($roleAdmins as $role): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($role->Id); ?></td>
                                    <td><?php echo htmlspecialchars($role->RoleName); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-bs-id="<?php echo $role->Id; ?>">
                                            <i class="fa fa-edit"></i>Sửa
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="<?php echo $role->Id; ?>" data-bs-name="<?php echo htmlspecialchars($role->RoleName); ?>">
                                            <i class="fa fa-trash"></i>Xóa
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">Không có chức vụ nào được tìm thấy.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button class="btn btn-outline-dark mx-1">&laquo;</button>
                <button class="btn btn-outline-dark active mx-1">1</button>
                <button class="btn btn-outline-dark mx-1">2</button>
                <button class="btn btn-outline-dark mx-1">&raquo;</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="process_create_role.php">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm chức vụ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name">Tên chức vụ</label>
                        <input type="text" class="form-control" id="create-name" name="RoleName" placeholder="Nhập tên chức vụ" required>
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
            <form class="modal-content" method="POST" action="process_edit_role.php">
                <input type="hidden" name="RoleId" id="edit-role-id">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa chức vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name">Tên chức vụ</label>
                        <input type="text" class="form-control" id="edit-name" name="RoleName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Xóa chức vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa chức vụ **<span id="delete-role-name-display" class="fw-bold"></span>** (Mã: <span id="delete-role-id-display"></span>) này không?
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
        const rolesData = <?php echo $rolesJson; ?>;

        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');

        const modalElements = [editModal, deleteModal];

        modalElements.forEach(modalElement => {
            modalElement.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; 
                const roleId = button ? button.getAttribute('data-bs-id') : null;
                
                if (roleId) {
                    const role = rolesData.find(r => r.Id == roleId);

                    if (!role) {
                        console.error('Không tìm thấy chức vụ với ID:', roleId);
                        return;
                    }

                    if (modalElement.id === 'editModal') {
                        document.getElementById('edit-role-id').value = role.Id;
                        document.getElementById('edit-name').value = role.RoleName;
                    }

                    if (modalElement.id === 'deleteModal') {
                        const roleName = button.getAttribute('data-bs-name');
                        document.getElementById('delete-role-id-display').innerText = role.Id;
                        document.getElementById('delete-role-name-display').innerText = roleName;
                        document.getElementById('confirmDeleteLink').href = 'process_delete_role.php?id=' + role.Id;
                    }
                }
            });
        });
    </script>
</body>

</html>