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
    $storeAdmins = [];

    if ($customer && $customer->Role) {
        require_once __DIR__ . '/../../controllers/StoreAdminController.php'; 

        $storeAdminController = new StoreAdminController();
        $storeAdmins = $storeAdminController->getAllStores();
    } else {
        header('Location: ../../../views/home/index.php');
        exit();
    }

    $storesJson = json_encode($storeAdmins, JSON_UNESCAPED_UNICODE);
?>
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
                        <?php if (!empty($storeAdmins)): ?>
                            <?php foreach($storeAdmins as $store): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($store->Id); ?></td>
                                    <td><?php echo htmlspecialchars($store->StoreName); ?></td>
                                    <td><?php echo htmlspecialchars($store->Address); ?></td>
                                    <td><?php echo htmlspecialchars($store->Phone); ?></td>
                                    <td><?php echo htmlspecialchars($store->OpenTime); ?></td>
                                    <td><?php echo htmlspecialchars($store->CloseTime); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-bs-id="<?php echo $store->Id; ?>">
                                            <i class="fa fa-edit"></i>Sửa
                                        </button>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal" data-bs-id="<?php echo $store->Id; ?>">
                                            <i class="fa fa-eye"></i>Chi tiết
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="<?php echo $store->Id; ?>" data-bs-name="<?php echo htmlspecialchars($store->StoreName); ?>">
                                            <i class="fa fa-trash"></i>Xóa
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có cửa hàng nào được tìm thấy.</td>
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
            <form class="modal-content" method="POST" action="process_create_store.php">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm cửa hàng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-storename">Tên cửa hàng</label>
                        <input type="text" class="form-control" id="create-storename" name="StoreName" placeholder="Nhập tên cửa hàng" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-address">Địa chỉ</label>
                        <input type="text" class="form-control" id="create-address" name="Address" placeholder="Nhập địa chỉ" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-phone">Điện thoại</label>
                        <input type="text" class="form-control" id="create-phone" name="Phone" placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-opentime">Giờ mở cửa</label>
                        <input type="time" class="form-control" id="create-opentime" name="OpenTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-closetime">Giờ đóng cửa</label>
                        <input type="time" class="form-control" id="create-closetime" name="CloseTime" required>
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
            <form class="modal-content" method="POST" action="process_edit_store.php">
                <input type="hidden" name="StoreId" id="edit-store-id">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa cửa hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-storename">Tên cửa hàng</label>
                        <input type="text" class="form-control" id="edit-storename" name="StoreName" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-address">Địa chỉ</label>
                        <input type="text" class="form-control" id="edit-address" name="Address" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-phone">Điện thoại</label>
                        <input type="text" class="form-control" id="edit-phone" name="Phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-opentime">Giờ mở cửa</label>
                        <input type="time" class="form-control" id="edit-opentime" name="OpenTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-closetime">Giờ đóng cửa</label>
                        <input type="time" class="form-control" id="edit-closetime" name="CloseTime" required>
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
                    <h5 class="modal-title">Chi tiết cửa hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Mã cửa hàng:</strong> <span id="view-id"></span></p>
                    <p><strong>Tên cửa hàng:</strong> <span id="view-storename"></span></p>
                    <p><strong>Địa chỉ:</strong> <span id="view-address"></span></p>
                    <p><strong>Điện thoại:</strong> <span id="view-phone"></span></p>
                    <p><strong>Giờ mở cửa:</strong> <span id="view-opentime"></span></p>
                    <p><strong>Giờ đóng cửa:</strong> <span id="view-closetime"></span></p>
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
                    <h5 class="modal-title text-danger">Xóa cửa hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa cửa hàng **<span id="delete-store-name-display" class="fw-bold"></span>** (Mã: <span id="delete-store-id-display"></span>) này không?
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
        const storesData = <?php echo $storesJson; ?>;

        const viewModal = document.getElementById('viewModal');
        const editModal = document.getElementById('editModal');
        const deleteModal = document.getElementById('deleteModal');

        const modalElements = [viewModal, editModal, deleteModal];

        modalElements.forEach(modalElement => {
            modalElement.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; 
                const storeId = button ? button.getAttribute('data-bs-id') : null;
                
                if (storeId) {
                    const store = storesData.find(s => s.Id == storeId);

                    if (!store) {
                        console.error('Không tìm thấy cửa hàng với ID:', storeId);
                        return;
                    }

                    if (modalElement.id === 'viewModal') {
                        document.getElementById('view-id').innerText = store.Id;
                        document.getElementById('view-storename').innerText = store.StoreName;
                        document.getElementById('view-address').innerText = store.Address;
                        document.getElementById('view-phone').innerText = store.Phone;
                        document.getElementById('view-opentime').innerText = store.OpenTime;
                        document.getElementById('view-closetime').innerText = store.CloseTime;
                    }

                    if (modalElement.id === 'editModal') {
                        document.getElementById('edit-store-id').value = store.Id;
                        document.getElementById('edit-storename').value = store.StoreName;
                        document.getElementById('edit-address').value = store.Address;
                        document.getElementById('edit-phone').value = store.Phone;
                        document.getElementById('edit-opentime').value = store.OpenTime;
                        document.getElementById('edit-closetime').value = store.CloseTime;
                    }

                    if (modalElement.id === 'deleteModal') {
                        const storeName = button.getAttribute('data-bs-name');
                        document.getElementById('delete-store-id-display').innerText = store.Id;
                        document.getElementById('delete-store-name-display').innerText = storeName;
                        document.getElementById('confirmDeleteLink').href = 'process_delete_store.php?id=' + store.Id;
                    }
                }
            });
        });
    </script>
</body>

</html>