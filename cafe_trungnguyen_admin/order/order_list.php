<?php

require 'config.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : "";
$status  = isset($_GET['status']) ? trim($_GET['status']) : "";
$from    = isset($_GET['from']) ? trim($_GET['from']) : "";
$to      = isset($_GET['to']) ? trim($_GET['to']) : "";

$sql = "SELECT p.Id,
            CONCAT(c.FirstName, ' ', c.LastName) AS CustomerName,
            s.StoreName,
            p.Total,
            p.Status,
            p.CreatedAt
        FROM payment p
        JOIN customer c ON p.CustomerId = c.Id
        JOIN store s    ON p.StoreId = s.Id
        WHERE 1=1";

$params = [];
$types  = "";

if ($keyword !== "") {
    $sql   .= " AND (p.Id = ? OR CONCAT(c.FirstName, ' ', c.LastName) LIKE CONCAT('%', ?, '%'))";
    $types .= "is";
    $params[] = (int)$keyword;
    $params[] = $keyword;
}

if ($status !== "") {
    $sql   .= " AND p.Status = ?";
    $types .= "s";
    $params[] = $status;
}

if ($from !== "") {
    $sql   .= " AND DATE(p.CreatedAt) >= ?";
    $types .= "s";
    $params[] = $from;
}
if ($to !== "") {
    $sql   .= " AND DATE(p.CreatedAt) <= ?";
    $types .= "s";
    $params[] = $to;
}

$sql .= " ORDER BY p.CreatedAt DESC";

$stmt = $conn->prepare($sql);
if ($types !== "") {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Danh sách đơn hàng</title>
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
        .shell{
            max-width:var(--maxw);
            margin:0 auto;
        }
        header.appbar{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin-bottom:18px;
        }
        .brand{
            display:flex;
            gap:12px;
            align-items:center;
            text-decoration:none;
            color:var(--accent);
            font-weight:700;
            font-size:18px;
        }
        .brand svg{width:40px; height:40px; flex-shrink:0}

        .card {
            background: linear-gradient(180deg, rgba(255,255,255,0.95), var(--card));
            border-radius: var(--radius);
            padding:22px;
            box-shadow: 0 10px 30px rgba(2,6,23,0.06);
            border: 1px solid rgba(15,23,42,0.04);
        }

        .card-head {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin-bottom:14px;
        }
        .title {
            display:flex;
            flex-direction:column;
        }
        .title h1{ margin:0; font-size:20px; letter-spacing:-0.2px; }
        .title p{ margin:6px 0 0; color:var(--muted); font-size:13px; }

        .controls {
            display:flex;
            gap:12px;
            align-items:center;
            margin:12px 0 6px;
            flex-wrap:wrap;
        }
        .search-box {
            flex:1;
            display:flex;
            gap:8px;
            align-items:center;
        }
        .search-box input[type="text"],
        .search-box input[type="date"],
        .search-box select {
            padding:10px 12px;
            border-radius:10px;
            border:1px solid rgba(15,23,42,0.06);
            background: #fff;
            outline:none;
            font-size:14px;
        }
        .btn {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:10px 14px;
            border-radius:10px;
            border:0;
            cursor:pointer;
            font-weight:600;
            font-size:14px;
        }
        .btn-primary{ background:var(--accent); color:#fff; box-shadow: 0 8px 18px rgba(37,99,235,0.14); }
        .btn-ghost{ background:transparent; color:var(--muted); border:1px solid rgba(15,23,42,0.06); }
        .btn-view{ background:#10b981; color:#fff; } /* view/detail button */

        .table-wrap { overflow:auto; margin-top:12px; }
        table {
            width:100%;
            border-collapse:collapse;
            font-size:14px;
            min-width:720px;
        }
        thead th {
            background: linear-gradient(90deg,var(--accent), #1f6fd9);
            color: #fff;
            text-align:left;
            padding:12px;
            font-weight:700;
        }
        tbody td {
            padding:12px;
            border-bottom:1px solid rgba(15,23,42,0.04);
            vertical-align:middle;
            color:#0f172a;
        }
        tbody tr:hover { background: rgba(59,130,246,0.03); }

        td.center { text-align:center; }

        .status-pill {
            display:inline-block;
            padding:6px 10px;
            border-radius:999px;
            font-size:13px;
            font-weight:600;
            color:#fff;
        }
        .status-pending { background:#f59e0b; } /* đang chờ */
        .status-paid    { background:#10b981; } /* đã thanh toán */
        .status-cancel  { background:#ef4444; } /* hủy */

        .no-data {
            text-align:center;
            padding:20px;
            color:var(--muted);
            font-style:italic;
        }

        @media (max-width:840px){
            .controls { flex-direction:column; align-items:stretch; }
            table { display:block; overflow:auto; white-space:nowrap;}
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

            <div class="title" style="text-align:right">
                <h1>Danh sách đơn hàng</h1>
                <p style="margin:4px 0 0; font-size:13px; color:var(--muted)">Tìm kiếm, lọc theo trạng thái và khoảng ngày</p>
            </div>
        </header>

        <main class="card" role="main" aria-labelledby="pageTitle">
            <div class="card-head" style="align-items:flex-start;">
                <div>
                    <h2 id="pageTitle" style="margin:0; font-size:18px;">Đơn hàng</h2>
                    <p style="margin:6px 0 0; color:var(--muted); font-size:13px;">Quản lý đơn đặt hàng khách</p>
                </div>
            </div>

            <div class="controls">
                <form class="search-box" method="get" action="order_list.php" style="flex:1; align-items:center;">
                    <input type="text" name="q" placeholder="Mã đơn hoặc tên khách..." value="<?php echo htmlspecialchars($keyword); ?>" style="min-width:180px;">
                    <input type="date" name="from" value="<?php echo htmlspecialchars($from); ?>">
                    <input type="date" name="to" value="<?php echo htmlspecialchars($to); ?>">
                    <input type="text" name="status" placeholder="Trạng thái (VD: pending, paid, cancel)" value="<?php echo htmlspecialchars($status); ?>" style="width:180px;">
                    <button class="btn btn-primary" type="submit">Lọc</button>
                    <a class="btn btn-ghost" href="order_list.php">Reset</a>
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="min-width:80px">Mã đơn</th>
                            <th style="min-width:220px">Khách hàng</th>
                            <th style="min-width:180px">Cửa hàng</th>
                            <th style="min-width:120px">Tổng tiền</th>
                            <th style="min-width:140px">Trạng thái</th>
                            <th style="min-width:160px">Ngày tạo</th>
                            <th style="min-width:120px">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="center"><?php echo $row['Id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['CustomerName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['StoreName']); ?></td>
                                    <td><?php echo number_format($row['Total'], 0, ',', '.'); ?> đ</td>
                                    <td>
                                        <?php
                                            $st = strtolower(trim($row['Status']));
                                            $cls = 'status-pending';
                                            $label = htmlspecialchars($row['Status']);
                                            if ($st === 'paid' || $st === 'completed' || $st === 'done') { $cls = 'status-paid'; }
                                            elseif ($st === 'cancel' || $st === 'canceled' || $st === 'cancelled') { $cls = 'status-cancel'; }
                                        ?>
                                        <span class="status-pill <?php echo $cls; ?>"><?php echo $label; ?></span>
                                    </td>
                                    <td><?php echo $row['CreatedAt']; ?></td>
                                    <td class="center">
                                        <a class="btn btn-view" href="order_detail.php?id=<?php echo $row['Id']; ?>">Xem</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="no-data">Không có đơn hàng</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

    </div>
</body>
</html>