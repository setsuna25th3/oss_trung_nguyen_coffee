<?php
session_start();
// require_once __DIR__ .'/../../controllers/CategoryController.php';
require_once __DIR__ . '/../../controllers/CartController.php';
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../controllers/StoreController.php';

$customerId = isset($_SESSION['CustomerId']) ? $_SESSION['CustomerId'] : 0;
$total = 0;

// $categoryController = new CategoryController();
$cartController = new CartController();
$productController = new ProductController();
$storeController = new StoreController();

// $categories = $categoryController->getAllCategories();
$carts = $cartController->getCartByCustomerId($customerId, 0); // Giả sử 0 trả về tất cả chi nhánh
$stores = $storeController->getAllStores();

// Nhóm giỏ hàng theo chi nhánh
$groupedCarts = [];
foreach ($carts as $cart) {
    $groupedCarts[$cart->StoreId][] = $cart;
}

// Map tên chi nhánh
$storeMap = [];
foreach ($stores as $store) {
    $s_Id = is_object($store) ? $store->Id : (isset($store['Id']) ? $store['Id'] : 0);
    $s_Name = is_object($store) ? $store->StoreName : (isset($store['StoreName']) ? $store['StoreName'] : '');
    $storeMap[$s_Id] = $s_Name;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Trung Nguyên Cà Phê</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-p1CmGhqPj4sA2Z3h2y+7y/YQq/1B82Jm3cLzU8/U5p8C1xPGGh8gZsyD+36ZhRZc3/1cqKJ4JzjCe2o6ULYtCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff1e0;
            padding-top: 150px;
        }

        .page-header {
            padding: 120px 0 60px;
            background-size: cover;
            background-position: center;
            position: relative;
            background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20230718/pngtree-digital-retailing-illustration-laptop-keyboard-with-shopping-basket-and-e-commerce-image_3903657.jpg');
        }

        .page-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .page-header h1,
        .page-header .breadcrumb {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #fff;
        }

        .page-header h1 {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .breadcrumb {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
            font-size: 16px;
        }

        .breadcrumb a {
            color: #fff1e0;
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb a:hover {
            color: #ffb300;
        }

        .breadcrumb .active {
            color: #ffb300;
            font-weight: bold;
        }

        .breadcrumb span.separator {
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: flex;
            gap: 30px;
        }

        .main-content {
            width: 100%;
        }

        .sidebar-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            padding: 20px;
        }

        .sidebar-section h3 {
            font-size: 20px;
            color: #37474f;
            margin-bottom: 15px;
            border-bottom: 2px solid #ffb300;
            padding-bottom: 10px;
        }

        .sidebar-section ul {
            list-style: none;
        }

        .sidebar-section li {
            margin-bottom: 10px;
        }

        .sidebar-section a {
            color: #555;
            text-decoration: none;
            transition: color 0.3s;
        }

        .sidebar-section a:hover {
            color: #ffb300;
        }

        .sidebar-section .featured-product {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .sidebar-section .featured-product img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
        }

        .sidebar-section .featured-product div {
            flex: 1;
        }

        .sidebar-section .featured-product h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .sidebar-section .featured-product p {
            font-size: 14px;
            color: #ffb300;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #ffb300;
            color: white;
            font-weight: 700;
        }

        tr:last-child td {
            border-bottom: none;
        }

        img.product-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            background: #ffb300;
            color: white;
            transition: 0.3s;
        }

        .btn:hover {
            background: #ff9800;
        }

        .checkout-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: right;
            margin-top: 30px;
        }

        .checkout-box h4 {
            margin-bottom: 15px;
            color: #37474f;
        }

        .checkout-box p {
            font-size: 18px;
            color: #ffb300;
            font-weight: 700;
        }

        .store-section {
            margin-bottom: 40px;
        }

        .store-section h2 {
            font-size: 24px;
            color: #37474f;
            margin-bottom: 20px;
        }

        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #37474f;
            margin-top: 20px;
        }

        .quantity-input {
            width: 70px;
            padding: 6px 10px;
            text-align: center;
            font-size: 16px;
            font-weight: 500;
            border: 2px solid #ffb300;
            border-radius: 25px;
            outline: none;
            transition: all 0.3s;
            color: #37474f;
            background-color: #fff;

        }

        /* .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        } */

        .quantity-input:focus {
            border-color: #ff9800;
            box-shadow: 0 0 5px rgba(255, 152, 0, 0.5);
        }
    </style>
</head>

<body>
    <?php include '../header.php'; ?>

    <div class="container-fluid page-header">
        <h1>Giỏ hàng</h1>
        <ul class="breadcrumb">
            <li><a href="../home/index.php">Trang chủ</a></li><span class="separator">/</span>
            <li><a href="../contact/index.php">Liên hệ</a></li><span class="separator">/</span>
            <li class="active">Giỏ hàng</li>
        </ul>
    </div>
    <?php if (isset($_SESSION['CustomerId']) && $_SESSION['CustomerId'] > 0): ?>
        <div class="order-icon-container" style="text-align: right; margin: 20px 50px;">
            <a href="../payment/index.php"
                style="font-size: 28px; text-decoration: none; color: #37474f; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-box"></i>
                <span>Đơn hàng</span>
            </a>
        </div>

    <?php endif; ?>


    <div class="container">
        <main class="main-content">
            <?php if (empty($carts)): ?>
                <p class="empty-cart">Giỏ hàng của bạn đang trống.</p>
            <?php else: ?>
                <?php foreach ($groupedCarts as $storeId => $storeCarts): ?>
                    <?php if (!empty($storeCarts)): ?>
                        <div class="store-section" data-store-id="<?php echo $storeId; ?>">
                            <h2><?php echo htmlspecialchars($storeMap[$storeId] ?? 'Chi nhánh không xác định'); ?></h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Chọn</th>
                                        <th>Ảnh sản phẩm</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $storeTotal = 0; ?>
                                    <?php foreach ($storeCarts as $cart): ?>
                                        <tr class="product-row">
                                            <?php
                                            $product = $productController->getProductById($cart->ProductId);
                                            $itemTotal = $product->Price * $cart->Quantity;
                                            $storeTotal += $itemTotal;
                                            ?>
                                            <td>
                                                <input type="checkbox" class="product-checkbox" value="<?php echo $product->Id; ?>" data-item-total="<?php echo $itemTotal; ?>" checked>
                                            </td>
                                            <td>
                                                <img src="../../img/SanPham/<?php echo htmlspecialchars($product->Img); ?>" class="product-img" alt="Sản phẩm">
                                            </td>
                                            <td><?php echo htmlspecialchars($product->Title); ?></td>
                                            <td><?php echo number_format($product->Price, 0, ',', '.'); ?> VNĐ</td>

                                            <td>
                                                <input type="number" class="quantity-input"
                                                    data-product-id="<?php echo $product->Id; ?>"
                                                    data-store-id="<?php echo $cart->StoreId; ?>"
                                                    value="<?php echo $cart->Quantity; ?>"
                                                    min="1" style="width:60px; text-align:center;">
                                            </td>
                                            <td class="item-total"><?php echo number_format($itemTotal, 0, ',', '.'); ?> VNĐ</td>
                                            <td>
                                                <button type="button" class="btn remove-btn"
                                                    data-product-id="<?php echo $product->Id; ?>"
                                                    data-store-id="<?php echo $cart->StoreId; ?>">
                                                    Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <tr>
                                        <td colspan="7">
                                            <form style="text-align: right;" method="GET" action="checkout.php" class="cartForm">

                                                <p class="totalAmount" style="font-weight: bold;"><?php echo 'Tổng tiền: ' . number_format($storeTotal, 0, ',', '.'); ?> VNĐ</p>
                                                <button type="submit" class="btn checkoutBtn" <?php echo $storeTotal <= 0 ? 'disabled' : ''; ?>>Thanh toán</button>
                                                <input type="hidden" name="storeId" value="<?php echo $storeId; ?>">
                                                <input type="hidden" name="customerId" value="<?php echo $customerId; ?>">
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>


                        </div>

                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>

    <?php include '../footer.php'; ?>

    <script>
        function updateHeaderCart() {
            let totalQty = 0;
            // Duyệt tất cả sản phẩm trong giỏ
            document.querySelectorAll('.store-section tbody tr').forEach(row => {
                const checkbox = row.querySelector('.product-checkbox');
                if (checkbox && checkbox.checked) {
                    const qty = parseInt(row.querySelector('.quantity-input').value);
                    totalQty += qty;
                }
            });

            // Cập nhật số lượng lên header
            const cartCount = document.getElementById('cartCount');
            if (cartCount) cartCount.textContent = totalQty;

            // Kiểm tra nếu không còn store-section nào -> hiển thị giỏ hàng trống
            const mainContent = document.querySelector('.main-content');
            if (!document.querySelectorAll('.store-section').length) {
                if (!document.querySelector('.empty-cart')) {
                    const emptyCart = document.createElement('p');
                    emptyCart.className = 'empty-cart';
                    emptyCart.textContent = 'Giỏ hàng của bạn đang trống.';
                    mainContent.appendChild(emptyCart);
                }
            }

        }

        document.addEventListener('DOMContentLoaded', function() {

            function updateStoreTotal(storeId) {
                const section = document.querySelector(`.store-section[data-store-id='${storeId}']`);
                if (!section) return;

                let total = 0;
                section.querySelectorAll('tbody tr').forEach(row => {
                    const checkbox = row.querySelector('.product-checkbox');
                    const itemTotalCell = row.querySelector('.item-total');
                    if (checkbox && checkbox.checked && itemTotalCell) {
                        total += parseInt(itemTotalCell.textContent.replace(/\D/g, ''));
                    }
                });

                const totalAmountElement = section.querySelector('.totalAmount');
                if (totalAmountElement) totalAmountElement.textContent = total.toLocaleString('vi-VN') + ' VNĐ';

                const checkoutBtn = section.querySelector('.checkoutBtn');
                if (checkoutBtn) checkoutBtn.disabled = total <= 0;
            }

            // Khi tick checkbox, cập nhật tổng tiền
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const storeId = this.closest('.store-section').dataset.storeId;
                    updateStoreTotal(storeId);
                    updateHeaderCart();
                });
            });

            // Thay đổi số lượng
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    let quantity = parseInt(this.value);
                    if (quantity < 1) quantity = 1;
                    this.value = quantity;

                    const row = this.closest('tr');
                    const storeId = row.closest('.store-section').dataset.storeId;
                    const productId = this.dataset.productId;
                    const price = parseInt(row.querySelector('td:nth-child(4)').textContent.replace(/\D/g, ''));
                    const newTotal = price * quantity;

                    row.querySelector('.item-total').textContent = newTotal.toLocaleString('vi-VN') + ' VNĐ';
                    const checkbox = row.querySelector('.product-checkbox');
                    if (checkbox) checkbox.setAttribute('data-item-total', newTotal);

                    updateStoreTotal(storeId);
                    updateHeaderCart();

                    fetch('ajax_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                action: 'update_quantity',
                                productId: productId,
                                storeId: storeId,
                                quantity: quantity
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) {
                                alert('Cập nhật giỏ hàng thất bại!');
                            }
                        });
                });
            });

            // Xóa sản phẩm
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const storeId = row.closest('.store-section').dataset.storeId;
                    const productId = this.dataset.productId;

                    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

                    fetch('ajax_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                action: 'remove',
                                productId,
                                storeId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const section = row.closest('.store-section');
                                row.remove();

                                const tbody = section.querySelector('tbody');
                                if (!tbody.querySelectorAll('tr.product-row').length) {
                                    section.remove();
                                }


                                updateStoreTotal(storeId);
                                updateHeaderCart();
                            }
                        });
                });
            });

        });
    </script>

</body>

</html>