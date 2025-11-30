<?php
require 'config.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : "";

if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM category WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: category_list.php");
    exit;
}

if ($keyword !== "") {
    $sql = "SELECT * FROM category
        WHERE Title LIKE CONCAT('%', ?, '%')
        ORDER BY Id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM category ORDER BY Id ASC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Quản lý loại hàng</title>
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
            margin-bottom:22px;
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
            background: linear-gradient(180deg, rgba(255,255,255,0.9), var(--card));
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
        .title h1{ margin:0; font-size:22px; letter-spacing:-0.4px; }
        .title p{ margin:6px 0 0; color:var(--muted); font-size:13px; }

        /* Search + controls */
        .controls {
            display:flex;
            gap:12px;
            align-items:center;
            margin:16px 0 6px;
            flex-wrap:wrap;
        }
        .search-box {
            flex:1;
            display:flex;
            gap:8px;
            align-items:center;
        }
        .search-box input[type="text"]{
            flex:1;
            padding:10px 12px;
            border-radius:10px;
            border:1px solid rgba(15,23,42,0.06);
            background: #fff;
            outline:none;
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
        .btn-add{ background:#2ecc71; color:#fff; }
        .btn-danger{ background:var(--danger); color:#fff; }
        .btn-edit{ background:#f1c40f; color:#111; }
        
        table {
            width:100%;
            border-collapse:collapse;
            margin-top:12px;
            font-size:14px;
        }
        thead th {
            background: linear-gradient(90deg,var(--accent), #1f6fd9);
            color: #fff;
            text-align:left;
            padding:12px;
            font-weight:700;
        }
        thead th:not(:last-child){ padding-right:18px; }
        tbody td {
            padding:12px;
            border-bottom:1px solid rgba(15,23,42,0.04);
            vertical-align:middle;
            color:#0f172a;
        }
        tbody tr:hover { background: rgba(59,130,246,0.03); }

        .actions a, .actions button {
            display:inline-block;
            padding:6px 10px;
            border-radius:8px;
            text-decoration:none;
            font-size:13px;
            margin-right:6px;
            cursor:pointer;
            border:0;
        }
        .actions .edit { background:#f59e0b; color:#fff; }
        .actions .del { background:#ef4444; color:#fff; }

        .no-data {
            text-align:center;
            padding:20px;
            color:var(--muted);
            font-style:italic;
        }

        /* Responsive table: horizontal scroll on narrow screens */
        @media (max-width:840px){
            .controls { flex-direction:column; align-items:stretch; }
            table { display:block; overflow:auto; white-space:nowrap; }
        }
    </style>
</head>
<body>
    <div class="shell">
        <header class="appbar">
            <a class="brand" href="category_list.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                </svg>
                Loại hàng
            </a>

            <div class="title" style="text-align:right">
                <h1>Quản lý loại hàng</h1>
                <p style="margin:4px 0 0; font-size:13px; color:var(--muted)">Danh sách, tìm kiếm và quản lý loại hàng</p>
            </div>
        </header>

        <main class="card" role="main" aria-labelledby="pageTitle">
            <div class="card-head" style="align-items:flex-start; gap:18px;">
                <div>
                    <h2 id="pageTitle" style="margin:0; font-size:18px;">Danh sách loại hàng</h2>
                    <p style="margin:6px 0 0; color:var(--muted); font-size:13px;">Tìm kiếm nhanh theo tiêu đề</p>
                </div>
                <div style="margin-left:auto; display:flex; gap:10px; align-items:center;">
                    <a class="btn btn-add" href="category_form.php">+ Thêm loại hàng mới</a>
                </div>
            </div>

            <div class="controls">
                <form class="search-box" method="get" action="category_list.php" style="flex:1;">
                    <input type="text" name="q" placeholder="Tìm theo tên loại hàng..." value="<?php echo htmlspecialchars($keyword); ?>">
                    <button class="btn btn-primary" type="submit">Tìm</button>
                    <a class="btn btn-ghost" href="category_list.php">Reset</a>
                </form>
            </div>

            <div style="overflow:auto; margin-top:8px;">
                <table>
                    <thead>
                        <tr>
                            <th style="min-width:60px;">ID</th>
                            <th style="min-width:100px;">Tiêu đề</th>
                            <th style="min-width:300px;">Mô tả</th>
                            <th style="min-width:160px;">Ngày tạo</th>
                            <th style="min-width:160px;">Ngày cập nhật</th>
                            <th style="min-width:140px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['Id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['Title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Content']); ?></td>
                                    <td><?php echo $row['CreateAt']; ?></td>
                                    <td><?php echo $row['UpdateAt']; ?></td>
                                    <td class="actions">
                                        <a class="edit" href="category_form.php?id=<?php echo $row['Id']; ?>">Sửa</a>
                                        <a class="del" href="category_list.php?delete_id=<?php echo $row['Id']; ?>"
                                        onclick="return confirm('Bạn có chắc muốn xóa loại hàng này? Hành động không thể hoàn tác.');">
                                        Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="no-data">Không có dữ liệu</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>