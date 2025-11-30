<?php

require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Thiếu mã đơn hàng");
}

$sql = "SELECT p.*,
            CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName,
            c.Email, c.Phone,
            s.StoreName, s.Address AS StoreAddress
        FROM payment p
        JOIN customer c ON p.CustomerId = c.Id
        JOIN store s    ON p.StoreId = s.Id
        WHERE p.Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Không tìm thấy đơn hàng");
}

$sql = "SELECT pd.*, pr.Title, pr.Img
        FROM paymentdetail pd
        JOIN product pr ON pd.ProductId = pr.Id
        WHERE pd.PaymentId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$items = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Chi tiết đơn #<?php echo $id; ?></title>
    <style>
        :root{
            --bg: #f4f7fb;
            --card: #ffffff;
            --accent: #2563eb;
            --muted: #6b7280;
            --success: #16a34a;
            --danger: #ef4444;
            --radius: 12px;
            --maxw: 1000px;
        }
        *{box-sizing:border-box}
        body{
            margin:0;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: linear-gradient(180deg, #eef2ff 0%, var(--bg) 40%);
            color:#0f172a;
            -webkit-font-smoothing:antialiased;
            padding:24px 16px;
        }
        .shell{ max-width:var(--maxw); margin:0 auto; }
        .card {
            background: linear-gradient(180deg, rgba(255,255,255,0.95), var(--card));
            border-radius: var(--radius);
            padding:22px;
            box-shadow: 0 10px 30px rgba(2,6,23,0.06);
            border: 1px solid rgba(15,23,42,0.04);
        }
        header.appbar{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin-bottom:18px;
        }
        .brand{ display:flex; gap:12px; align-items:center; color:var(--accent); font-weight:700; text-decoration:none; }
        .brand svg{width:40px;height:40px; flex-shrink:0}
        .title { display:flex; flex-direction:column; text-align:right; }
        .title h1{ margin:0; font-size:20px; }
        .title p{ margin:6px 0 0; color:var(--muted); font-size:13px; }
        .card-head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:14px; }

        .row { display:flex; gap:18px; flex-wrap:wrap; align-items:flex-start; }
        .col { flex:1; min-width:240px; }
        .panel { background:rgba(255,255,255,0.6); padding:12px; border-radius:8px; border:1px dashed rgba(15,23,42,0.03); color:var(--muted); }

        table { width:100%; border-collapse:collapse; margin-top:12px; font-size:14px; }
        thead th { background: linear-gradient(90deg,var(--accent), #1f6fd9); color:#fff; text-align:left; padding:10px; font-weight:700; }
        tbody td { padding:10px; border-bottom:1px solid rgba(15,23,42,0.04); vertical-align:middle; }

        img.thumb { max-width:100px; max-height:80px; object-fit:cover; border-radius:8px; border:1px solid rgba(15,23,42,0.04); }

        .meta-list { list-style:none; padding:0; margin:0; }
        .meta-list li { margin-bottom:6px; }

        .status-pill { display:inline-block; padding:6px 10px; border-radius:999px; font-weight:700; color:#fff; }
        .status-pending { background:#f59e0b; }
        .status-paid { background:#10b981; }
        .status-cancel { background:#ef4444; }

        .actions { margin-top:12px; display:flex; gap:8px; justify-content:flex-end; }
        .btn { padding:8px 12px; border-radius:8px; border:0; cursor:pointer; font-weight:700; color:#fff; background:var(--accent); text-decoration:none; }
        .btn-ghost { background:transparent; color:var(--muted); border:1px solid rgba(15,23,42,0.06); }

        .totals { margin-top:12px; display:flex; justify-content:flex-end; gap:12px; align-items:center; font-weight:700; }
        .totals .label { color:var(--muted); font-weight:600; margin-right:8px; }

        @media (max-width:840px){
            .card-head, .row { flex-direction:column; }
            .title { text-align:left; margin-top:8px; }
        }
    </style>
</head>
<body>
    <div class="shell">
        <header class="appbar">
            <a class="brand" href="order_list.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                Đơn hàng
            </a>
            <div class="title">
                <h1>Chi tiết đơn #<?php echo $id; ?></h1>
                <p>Thông tin khách hàng, cửa hàng và sản phẩm</p>
            </div>
        </header>

        <main class="card" role="main" aria-labelledby="pageTitle">
            <div class="card-head">
                <div>
                    <h2 id="pageTitle" style="margin:0;font-size:16px">Chi tiết đơn hàng</h2>
                    <p style="margin:6px 0 0;color:var(--muted)">Mã đơn: <strong>#<?php echo $id; ?></strong></p>
                </div>
                <div class="actions">
                    <a class="btn-ghost" href="order_list.php">← Quay về</a>
                    <a class="btn" href="order_print.php?id=<?php echo $id; ?>" target="_blank">In phiếu</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="panel">
                        <ul class="meta-list">
                            <li><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['CustomerName']); ?></li>
                            <li><strong>Email:</strong> <?php echo htmlspecialchars($order['Email']); ?></li>
                            <li><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['Phone']); ?></li>
                            <li><strong>Cửa hàng:</strong> <?php echo htmlspecialchars($order['StoreName']); ?></li>
                            <li><strong>Địa chỉ cửa hàng:</strong> <?php echo htmlspecialchars($order['StoreAddress']); ?></li>
                        </ul>
                    </div>
                </div>

                <div class="col" style="min-width:260px;">
                    <div class="panel">
                        <div style="display:flex;justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <div><strong>Trạng thái</strong></div>
                            <?php
                                $st = strtolower(trim($order['Status']));
                                $cls = 'status-pending';
                                if ($st === 'paid' || $st === 'completed' || $st === 'done') { $cls = 'status-paid'; }
                                elseif ($st === 'cancel' || $st === 'canceled' || $st === 'cancelled') { $cls = 'status-cancel'; }
                            ?>
                            <div><span class="status-pill <?php echo $cls; ?>"><?php echo htmlspecialchars($order['Status']); ?></span></div>
                        </div>

                        <div style="margin-top:6px; color:var(--muted)">
                            <div><strong>Ngày tạo:</strong> <?php echo $order['CreatedAt']; ?></div>
                            <div style="margin-top:6px"><strong>Tổng tiền:</strong> <?php echo number_format($order['Total'],0,',','.'); ?> đ</div>
                        </div>
                    </div>
                </div>
            </div>

            <h3 style="margin-top:18px; margin-bottom:8px;">Sản phẩm trong đơn</h3>
            <div style="overflow:auto;">
                <table>
                    <thead>
                        <tr>
                            <th style="min-width:120px">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th style="min-width:120px">Đơn giá</th>
                            <th style="min-width:100px">Số lượng</th>
                            <th style="min-width:140px">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($items && $items->num_rows > 0): ?>
                            <?php while ($row = $items->fetch_assoc()): ?>
                                <?php
                                    $imgPath = '';
                                    if (!empty($row['Img'])) {
                                        if (strpos($row['Img'], '/') === false && strpos($row['Img'], '\\') === false) {
                                            $imgPath = 'images/SanPham/' . $row['Img'];
                                        } else {
                                            $imgPath = str_replace('\\','/',$row['Img']);
                                        }
                                    }
                                    $lineTotal = $row['Price'] * $row['Quantity'];
                                ?>
                                <tr>
                                    <td class="center">
                                        <?php if ($imgPath !== ''): ?>
                                            <img class="thumb" src="<?php echo htmlspecialchars($imgPath); ?>" alt="">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['Title']); ?></td>
                                    <td><?php echo number_format($row['Price'],0,',','.'); ?> đ</td>
                                    <td><?php echo (int)$row['Quantity']; ?></td>
                                    <td><?php echo number_format($lineTotal,0,',','.'); ?> đ</td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="no-data">Không có sản phẩm</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="totals">
                <div class="label">Tổng đơn:</div>
                <div style="font-size:18px;"><?php echo number_format($order['Total'],0,',','.'); ?> đ</div>
            </div>

        </main>

        <footer style="text-align:center; color:var(--muted); margin-top:14px; font-size:13px;">
            © <?php echo date('Y'); ?> Hệ thống quản trị
        </footer>
    </div>
</body>
</html>
