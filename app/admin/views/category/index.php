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
    $categoryAdmins = [];

    if ($customer && $customer->Role) {
        require_once __DIR__ . '/../../controllers/CategoryAdminController.php'; 

        $categoryAdminController = new CategoryAdminController();
        $categoryAdmins = $categoryAdminController->getAllCategories();
    } else {
        header('Location: ../../../views/home/index.php');
        exit();
    }

    $categoriesJson = json_encode($categoryAdmins, JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
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

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
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
        <h1>Quản lý danh mục</h1>

        <div class="table-wrapper">
            <button class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa fa-plus"></i> Thêm mới
            </button>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã danh mục</th>
                            <th>Tên danh mục</th>
                            <th>Nội dung</th>
                            <th>Ngày tạo</th>
                            <th>Ngày cập nhật</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categoryAdmins)): ?>
                            <?php foreach($categoryAdmins as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category->Id); ?></td>
                                    <td><?php echo htmlspecialchars($category->Title); ?></td>
                                    <td><?php echo htmlspecialchars($category->Content); ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($category->CreateAt)); ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($category->UpdateAt)); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal" data-bs-id="<?php echo $category->Id; ?>"><i class="fa fa-eye"></i>Chi tiết</button>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-bs-id="<?php echo $category->Id; ?>"><i class="fa fa-edit"></i>Sửa</button>
                                        <!-- <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="<?php echo $category->Id; ?>"><i class="fa fa-trash"></i>Xóa</button> -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có danh mục nào được tìm thấy.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="process_create_category.php">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-title">Tên danh mục</label>
                        <input type="text" class="form-control" id="create-title" name="title" placeholder="Nhập tên danh mục" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-content">Nội dung</label>
                        <textarea class="form-control" id="create-content" name="content" placeholder="Nhập mô tả"></textarea>
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
            <form class="modal-content" method="POST" action="process_edit_category.php">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="categoryId" id="edit-category-id">
                    <div class="mb-3">
                        <label for="edit-title">Tên danh mục</label>
                        <input type="text" class="form-control" id="edit-title" name="title">
                    </div>
                    <div class="mb-3">
                        <label for="edit-content">Nội dung</label>
                        <textarea class="form-control" id="edit-content" name="content"></textarea>
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
                    <h5 class="modal-title">Chi tiết danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Mã danh mục:</strong> <span id="view-id"></span></p>
                    <p><strong>Tên danh mục:</strong> <span id="view-title"></span></p>
                    <p><strong>Nội dung:</strong> <span id="view-content"></span></p>
                    <p><strong>Ngày tạo:</strong> <span id="view-create-at"></span></p>
                    <p><strong>Ngày cập nhật:</strong> <span id="view-update-at"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Xóa danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa danh mục **<span id="delete-category-title-display"></span>** (ID: <span id="delete-category-id-display"></span>) này không? Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <a id="confirmDeleteLink" class="btn btn-danger" href="#">Xóa</a>
                </div>
            </div>
        </div>
    </div> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const categoriesData = <?php echo $categoriesJson; ?>;

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
        const deleteModal = document.getElementById('deleteModal');

        const modalElements = [createModal, viewModal, editModal, deleteModal];

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
            modalElement.addEventListener('show.bs.modal', function (event) {
                showWhiteBackdrop();

                const button = event.relatedTarget; 
                const categoryId = button ? button.getAttribute('data-bs-id') : null;
                
                if (categoryId) {
                    const category = categoriesData.find(c => c.Id == categoryId);

                    if (!category) {
                        console.error('Không tìm thấy danh mục với ID:', categoryId);
                        return;
                    }

                    if (modalElement.id === 'viewModal') {
                        document.getElementById('view-id').innerText = category.Id;
                        document.getElementById('view-title').innerText = category.Title;
                        document.getElementById('view-content').innerText = category.Content;
                        document.getElementById('view-create-at').innerText = formatDate(category.CreateAt);
                        document.getElementById('view-update-at').innerText = formatDate(category.UpdateAt);
                    }

                    if (modalElement.id === 'editModal') {
                        document.getElementById('edit-category-id').value = category.Id;
                        document.getElementById('edit-title').value = category.Title;
                        document.getElementById('edit-content').value = category.Content;
                        modalElement.querySelector('form').action = 'process_edit_category.php';
                    }

                    // if (modalElement.id === 'deleteModal') {
                    //     document.getElementById('delete-category-id-display').innerText = category.Id;
                    //     document.getElementById('delete-category-title-display').innerText = category.Title;
                    //     document.getElementById('confirmDeleteLink').href = 'process_delete_category.php?id=' + category.Id;
                    // }
                }
            });

            modalElement.addEventListener('hidden.bs.modal', function () {
                removeWhiteBackdrop();
            });
        });
    </script>
</body>

</html>