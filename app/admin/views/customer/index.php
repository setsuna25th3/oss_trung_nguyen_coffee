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
$customerAdmins = [];

if ($customer && $customer->Role) {
    require_once __DIR__ . '/../../controllers/CustomerAdminController.php';

    $customerAdminController = new CustomerAdminController();
    $customerAdmins = $customerAdminController->getAllCustomers();
} else {
    header('Location: ../../../views/home/index.php');
    exit();
}

$customersJson = json_encode($customerAdmins, JSON_UNESCAPED_UNICODE);
?>
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
                            <th>Mã KH</th>
                            <th>Họ và Tên</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Ngày sinh</th>
                            <th>Ảnh</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng ký</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customerAdmins)): ?>
                            <?php foreach ($customerAdmins as $cust):
                                $fullName = htmlspecialchars($cust->LastName . ' ' . $cust->FirstName);
                                $statusClass = $cust->IsActive ? 'text-success' : 'text-danger';
                                $statusText = $cust->IsActive ? 'Hoạt động' : 'Đã khóa';
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cust->Id); ?></td>
                                    <td><?php echo $fullName; ?></td>
                                    <td><?php echo htmlspecialchars($cust->Email); ?></td>
                                    <td><?php echo htmlspecialchars($cust->Phone); ?></td>
                                    <td><?php echo htmlspecialchars($cust->Address); ?></td>
                                    <td><?php echo htmlspecialchars($cust->DateOfBirth); ?></td>
                                    <td>
                                        <img src="/oss_trung_nguyen_coffee/app/img/KhachHang/<?php echo md5($cust->Email) . "/" . htmlspecialchars($cust->Img); ?>"
                                            alt="<?php echo $fullName; ?>" class="img-avatar" />
                                    </td>
                                    <td class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($cust->RegisteredAt)); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal" data-bs-id="<?php echo $cust->Id; ?>"><i class="fa fa-eye"></i></button>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-bs-id="<?php echo $cust->Id; ?>"><i class="fa fa-edit"></i></button>
                                        <!-- <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="<?php echo $cust->Id; ?>"><i class="fa fa-lock"></i> Khóa</button> -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Không có khách hàng nào được tìm thấy.</td>
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
            <form class="modal-content" method="POST" action="process_create_customer.php">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm khách hàng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-first-name">First Name</label>
                        <input type="text" class="form-control" id="create-first-name" name="FirstName" placeholder="Nhập họ">
                    </div>
                    <div class="mb-3">
                        <label for="create-last-name">Last Name</label>
                        <input type="text" class="form-control" id="create-last-name" name="LastName" placeholder="Nhập tên">
                    </div>
                    <div class="mb-3">
                        <label for="create-email">Email</label>
                        <input type="email" class="form-control" id="create-email" name="Email" placeholder="Nhập email">
                    </div>
                    <div class="mb-3">
                        <label for="create-phone">Phone</label>
                        <input type="text" class="form-control" id="create-phone" name="Phone" placeholder="Nhập số điện thoại">
                    </div>
                    <div class="mb-3">
                        <label for="create-address">Address</label>
                        <input type="text" class="form-control" id="create-address" name="Address" placeholder="Nhập địa chỉ">
                    </div>
                    <div class="mb-3">
                        <label for="create-dob">Ngày sinh</label>
                        <input type="date" class="form-control" id="create-dob" name="DateOfBirth">
                    </div>
                    <div class="mb-3">
                        <label for="create-img">Ảnh đại diện</label>
                        <input type="file" class="form-control" id="create-img" name="Img">
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
            <form class="modal-content" method="POST" action="process_edit_customer.php">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa thông tin khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="CustomerId" id="edit-customer-id">
                    <div class="mb-3">
                        <label for="edit-first-name">First Name</label>
                        <input type="text" class="form-control" id="edit-first-name" name="FirstName">
                    </div>
                    <div class="mb-3">
                        <label for="edit-last-name">Last Name</label>
                        <input type="text" class="form-control" id="edit-last-name" name="LastName">
                    </div>
                    <div class="mb-3">
                        <label for="edit-email">Email</label>
                        <input type="email" class="form-control" id="edit-email" name="Email">
                    </div>
                    <div class="mb-3">
                        <label for="edit-phone">Phone</label>
                        <input type="text" class="form-control" id="edit-phone" name="Phone">
                    </div>
                    <div class="mb-3">
                        <label for="edit-address">Address</label>
                        <input type="text" class="form-control" id="edit-address" name="Address">
                    </div>
                    <div class="mb-3">
                        <label for="edit-dob">Ngày sinh</label>
                        <input type="date" class="form-control" id="edit-dob" name="DateOfBirth">
                    </div>
                    <div class="mb-3">
                        <label for="edit-img">Ảnh đại diện (Để trống nếu không thay đổi)</label>
                        <input type="file" class="form-control" id="edit-img" name="Img">
                        <p class="mt-2">Ảnh hiện tại: <img id="edit-current-img" src="" class="img-avatar" /></p>
                    </div>
                    <div class="mb-3">
                        <label for="edit-is-active">Trạng thái hoạt động</label>
                        <select class="form-select" id="edit-is-active" name="IsActive">
                            <option value="1">Hoạt động</option>
                            <option value="0">Khóa</option>
                        </select>
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
                    <h5 class="modal-title">Chi tiết khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Mã KH:</strong> <span id="view-id"></span></p>
                    <p><strong>Họ và Tên:</strong> <span id="view-full-name"></span></p>
                    <p><strong>Email:</strong> <span id="view-email"></span></p>
                    <p><strong>Phone:</strong> <span id="view-phone"></span></p>
                    <p><strong>Address:</strong> <span id="view-address"></span></p>
                    <p><strong>Ngày sinh:</strong> <span id="view-dob"></span></p>
                    <p><strong>Trạng thái:</strong> <span id="view-is-active" class="fw-bold"></span></p>
                    <p><strong>Ngày đăng ký:</strong> <span id="view-registered-at"></span></p>
                    <div class="text-center">
                        <img id="view-img" src="" class="img-avatar my-3" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Khóa khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn **khóa** khách hàng **<span id="delete-full-name-display" class="fw-bold"></span>** (Mã KH: <span id="delete-id-display"></span>) này không? Việc khóa sẽ ngăn họ đăng nhập.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <a id="confirmDeleteLink" class="btn btn-danger" href="#">Khóa</a>
                </div>
            </div>
        </div>
    </div> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const customersData = <?php echo $customersJson; ?>;

        const IMG_BASE_PATH = '/oss_trung_nguyen_coffee/app/img/Avatar/';

        function formatDate(dateString) {
            const date = new Date(dateString);
            if (dateString.length > 10) {
                return date.toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }
            return date.toLocaleDateString('vi-VN', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        }

        function formatStatus(isActive) {
            return isActive == 1 ? 'Hoạt động' : 'Đã khóa';
        }

        const viewModal = document.getElementById('viewModal');
        const editModal = document.getElementById('editModal');
        const createModal = document.getElementById('createModal');
        // const deleteModal = document.getElementById('deleteModal');

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
            modalElement.addEventListener('show.bs.modal', function(event) {
                showWhiteBackdrop();

                const button = event.relatedTarget;
                const customerId = button ? button.getAttribute('data-bs-id') : null;

                if (customerId) {
                    const customer = customersData.find(c => c.Id == customerId);

                    if (!customer) {
                        console.error('Không tìm thấy khách hàng với ID:', customerId);
                        return;
                    }

                    const fullName = customer.LastName + ' ' + customer.FirstName;

                    if (modalElement.id === 'viewModal') {
                        document.getElementById('view-id').innerText = customer.Id;
                        document.getElementById('view-full-name').innerText = fullName;
                        document.getElementById('view-email').innerText = customer.Email;
                        document.getElementById('view-phone').innerText = customer.Phone;
                        document.getElementById('view-address').innerText = customer.Address;
                        document.getElementById('view-dob').innerText = formatDate(customer.DateOfBirth);
                        document.getElementById('view-registered-at').innerText = formatDate(customer.RegisteredAt);

                        const statusSpan = document.getElementById('view-is-active');
                        statusSpan.innerText = formatStatus(customer.IsActive);
                        statusSpan.classList.toggle('text-success', customer.IsActive == 1);
                        statusSpan.classList.toggle('text-danger', customer.IsActive == 0);

                        document.getElementById('view-img').src = IMG_BASE_PATH + customer.Img;
                    }

                    if (modalElement.id === 'editModal') {
                        document.getElementById('edit-customer-id').value = customer.Id;
                        document.getElementById('edit-first-name').value = customer.FirstName;
                        document.getElementById('edit-last-name').value = customer.LastName;
                        document.getElementById('edit-email').value = customer.Email;
                        document.getElementById('edit-phone').value = customer.Phone;
                        document.getElementById('edit-address').value = customer.Address;
                        document.getElementById('edit-dob').value = customer.DateOfBirth;
                        document.getElementById('edit-current-img').src = IMG_BASE_PATH + customer.Img;
                        document.getElementById('edit-is-active').value = customer.IsActive;
                    }

                    // if (modalElement.id === 'deleteModal') {
                    //     document.getElementById('delete-id-display').innerText = customer.Id;
                    //     document.getElementById('delete-full-name-display').innerText = fullName;
                    //     document.getElementById('confirmDeleteLink').href = 'process_lock_customer.php?id=' + customer.Id;
                    // }
                }
            });

            modalElement.addEventListener('hidden.bs.modal', function() {
                removeWhiteBackdrop();
            });
        });
    </script>
</body>

</html>