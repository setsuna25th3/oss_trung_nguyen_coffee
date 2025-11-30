<?php
require 'config.php';

$keyword    = isset($_GET['q']) ? trim($_GET['q']) : "";
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$catsResult = $conn->query("SELECT Id, Title FROM category ORDER BY Title ASC");

$sql = "SELECT p.*, c.Title AS CategoryTitle
        FROM product p
        JOIN category c ON p.CategoryId = c.Id
        WHERE 1 = 1";

$params = [];
$types  = "";

if ($keyword !== "") {
    $sql   .= " AND p.Title LIKE CONCAT('%', ?, '%')";
    $types .= "s";
    $params[] = $keyword;
}

if ($categoryId > 0) {
    $sql   .= " AND p.CategoryId = ?";
    $types .= "i";
    $params[] = $categoryId;
}

$sql .= " ORDER BY p.Id ASC";

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
    <title>Quản lý sản phẩm</title>
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
        .search-box select {
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

        .table-wrap { overflow:auto; margin-top:12px; }
        table {
            width:100%;
            border-collapse:collapse;
            font-size:14px;
            min-width:700px;
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

        img.thumb {
            max-width:80px;
            max-height:60px;
            object-fit:cover;
            border-radius:8px;
            border:1px solid rgba(15,23,42,0.04);
            display:block;
        }

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

        @media (max-width:840px){
            .controls { flex-direction:column; align-items:stretch; }
            table { display:block; overflow:auto; white-space:nowrap;}
        }
    </style>
</head>
<body>
    <div class="shell">
        <header class="appbar">
            <a class="brand" href="product_list.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
                </svg>
                Sản phẩm
            </a>

            <div class="title" style="text-align:right">
                <h1>Quản lý sản phẩm</h1>
                <p style="margin:4px 0 0; font-size:13px; color:var(--muted)">Danh sách & quản lý sản phẩm</p>
            </div>
        </header>

        <main class="card" role="main" aria-labelledby="pageTitle">
            <div class="card-head" style="align-items:flex-start;">
                <div>
                    <h2 id="pageTitle" style="margin:0; font-size:18px;">Danh sách sản phẩm</h2>
                    <p style="margin:6px 0 0; color:var(--muted); font-size:13px;">Tìm theo tên hoặc lọc theo loại</p>
                </div>

                <div style="margin-left:auto; display:flex; gap:10px; align-items:center;">
                    <a class="btn btn-add" href="product_form.php">+ Thêm sản phẩm mới</a>
                </div>
            </div>

            <div class="controls">
                <form class="search-box" method="get" action="product_list.php" style="flex:1;">
                    <input type="text" name="q" placeholder="Tìm theo tên sản phẩm..." value="<?php echo htmlspecialchars($keyword); ?>">
                    <select name="category">
                        <option value="0">-- Tất cả loại hàng --</option>
                        <?php if ($catsResult && $catsResult->num_rows > 0): ?>
                            <?php while ($cat = $catsResult->fetch_assoc()): ?>
                                <option value="<?php echo $cat['Id']; ?>"
                                    <?php echo $categoryId == $cat['Id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['Title']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>

                    <button class="btn btn-primary" type="submit">Lọc</button>
                    <a class="btn btn-ghost" href="product_list.php">Reset</a>
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="min-width:60px">ID</th>
                            <th style="min-width:240px">Tên sản phẩm</th>
                            <th style="min-width:160px">Loại</th>
                            <th style="min-width:120px">Giá</th>
                            <th style="min-width:120px">Ảnh</th>
                            <th style="min-width:160px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="center"><?php echo $row['Id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['Title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['CategoryTitle']); ?></td>
                                    <td><?php echo number_format($row['Price'], 0, ',', '.'); ?> đ</td>
                                    <td class="center">
                                        <?php if (!empty($row['Img'])):
                                            $imgPath = (strpos($row['Img'],'/') === false && strpos($row['Img'],'\\')===false)
                                                    ? 'images/SanPham/' . $row['Img']
                                                    : str_replace('\\','/',$row['Img']);
                                        ?>
                                            <img class="thumb" src="<?php echo htmlspecialchars($imgPath); ?>" alt="">
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a class="edit" href="product_form.php?id=<?php echo $row['Id']; ?>">Sửa</a>
                                        <a class="del" href="product_delete.php?id=<?php echo $row['Id']; ?>"
                                        onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này? Hành động không thể hoàn tác.');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="no-data">Không có sản phẩm</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

