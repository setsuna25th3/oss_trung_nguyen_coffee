<?php
require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$catsResult = $conn->query("SELECT Id, Title FROM category ORDER BY Title ASC");

$title = $content = $img = "";
$price = 0;
$categoryId = 0;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM product WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res  = $stmt->get_result();
    $row  = $res->fetch_assoc();
    $stmt->close();

    if ($row) {
        $title      = $row['Title'];
        $content    = $row['Content'];
        $img        = $row['Img'];
        $price      = $row['Price'];
        $categoryId = $row['CategoryId'];
    } else {
        die("Không tìm thấy sản phẩm");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title      = trim($_POST['Title']);
    $content    = trim($_POST['Content']);
    $img        = trim($_POST['Img']);
    $price      = (float)$_POST['Price'];
    $categoryId = (int)$_POST['CategoryId'];

    if ($id > 0) {
        $sql = "UPDATE product
                SET Title = ?, Content = ?, Img = ?, Price = ?, CategoryId = ?, UpdateAt = NOW()
                WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssddi", $title, $content, $img, $price, $categoryId, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $sql = "INSERT INTO product (Title, Content, Img, Price, CategoryId, CreateAt, UpdateAt)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdi", $title, $content, $img, $price, $categoryId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: product_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $id > 0 ? "Sửa sản phẩm" : "Thêm sản phẩm"; ?></title>
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

        form .grid {
            display:grid;
            grid-template-columns: 1fr 340px;
            gap:18px;
        }
        @media (max-width:900px){
            form .grid { grid-template-columns: 1fr; }
        }

        label {
            display:block;
            font-size:13px;
            margin-bottom:8px;
            color:#111827;
        }
        input[type="text"], input[type="number"], textarea, select {
            width:100%;
            padding:12px 14px;
            font-size:15px;
            border-radius:10px;
            border:1px solid rgba(15,23,42,0.06);
            background: linear-gradient(180deg,#fff,#fbfdff);
            outline:none;
            transition: box-shadow .12s, border-color .12s;
        }
        input[type="text"]:focus, textarea:focus, select:focus, input[type="number"]:focus{
            border-color: var(--accent);
            box-shadow: 0 8px 20px rgba(37,99,235,0.12);
        }
        textarea{ min-height:140px; resize:vertical; }

        .hint { font-size:13px; color:var(--muted); margin-top:8px; }

        .actions {
            margin-top:18px;
            display:flex;
            gap:10px;
            justify-content:flex-end;
            align-items:center;
        }
        .btn {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            padding:10px 14px;
            border-radius:10px;
            border:0;
            cursor:pointer;
            font-weight:600;
            font-size:14px;
        }
        .btn-primary{ background:var(--accent); color:#fff; box-shadow: 0 8px 18px rgba(37,99,235,0.18); }
        .btn-secondary{ background:transparent; color:var(--muted); border:1px solid rgba(15,23,42,0.06); }
        .btn-danger{ background:var(--danger); color:#fff; }

        .panel {
            display:flex;
            flex-direction:column;
            gap:12px;
        }
        .preview {
            border-radius:10px;
            padding:16px;
            min-height:180px;
            background: linear-gradient(180deg, rgba(245,247,255,0.6), rgba(255,255,255,0.6));
            border:1px solid rgba(15,23,42,0.03);
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
        }
        .preview img{ max-width:100%; max-height:180px; border-radius:8px; display:block; }
        .preview .name { font-weight:700; font-size:16px; margin-bottom:8px; }
        .preview .desc { color:var(--muted); font-size:14px; line-height:1.4; white-space:pre-line; }

        .meta {
            font-size:13px;
            color:var(--muted);
            background:rgba(255,255,255,0.6);
            padding:12px;
            border-radius:8px;
            border:1px dashed rgba(15,23,42,0.03);
        }

        footer.small {
            text-align:center;
            color:var(--muted);
            margin-top:14px;
            font-size:13px;
        }

        .field-row { margin-bottom:14px; }
        .small-muted { font-size:13px; color:var(--muted); margin-top:6px; }
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
            <div class="breadcrumb">
                <a class="link-back" href="product_list.php" style="color:var(--accent); text-decoration:none; font-weight:600">&larr; Danh sách sản phẩm</a>
            </div>
        </header>

        <main class="card" role="main" aria-labelledby="pageTitle">
            <div class="card-head">
                <div class="title">
                    <h1 id="pageTitle"><?php echo $id > 0 ? "Sửa sản phẩm" : "Thêm sản phẩm"; ?></h1>
                    <p><?php echo $id > 0 ? "Chỉnh sửa thông tin sản phẩm" : "Tạo sản phẩm mới để quản lý trong cửa hàng"; ?></p>
                </div>

                <div style="margin-left:auto; display:flex; gap:10px; align-items:center;">
                    <a class="btn btn-secondary" href="product_list.php">Quay về</a>
                </div>
            </div>

            <form method="post" enctype="multipart/form-data" onsubmit="return validateForm(event)" novalidate>
                <div class="grid">
                    <div>
                        <div class="field-row">
                            <label for="Title">Tên sản phẩm <span style="color:var(--danger)">*</span></label>
                            <input id="Title" name="Title" type="text" required
                                value="<?php echo htmlspecialchars($title); ?>" placeholder="VD: Sữa tươi, Cà phê...">
                        </div>

                        <div class="field-row">
                            <label for="Content">Mô tả</label>
                            <textarea id="Content" name="Content" placeholder="Mô tả ngắn về sản phẩm..."><?php echo htmlspecialchars($content); ?></textarea>
                        </div>

                        <div class="field-row" style="display:flex;gap:12px;align-items:center;">
                            <div style="flex:1">
                                <label for="Price">Giá (VNĐ)</label>
                                <input id="Price" name="Price" type="number" min="0" step="1000" required value="<?php echo htmlspecialchars($price); ?>">
                            </div>

                            <div style="width:220px">
                                <label for="CategoryId">Loại hàng</label>
                                <select id="CategoryId" name="CategoryId" required>
                                    <option value="">-- Chọn loại --</option>
                                    <?php if ($catsResult && $catsResult->num_rows > 0): ?>
                                        <?php while ($cat = $catsResult->fetch_assoc()): ?>
                                            <option value="<?php echo $cat['Id']; ?>"
                                                <?php echo ($categoryId == $cat['Id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat['Title']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="field-row">
                            <label>Ảnh (chọn file)</label>
                            <input type="file" name="ImageFile" accept="image/*">
                            <input type="hidden" name="ExistingImg" value="<?php echo htmlspecialchars($img); ?>">
                            <div class="small-muted">Nếu không chọn file, ảnh hiện tại sẽ được giữ. (Backend cần xử lý upload để lưu file.)</div>
                        </div>

                        <div class="actions">
                            <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? "Lưu thay đổi" : "Tạo sản phẩm"; ?></button>
                        </div>
                    </div>

                    <aside class="panel" aria-label="Thông tin nhanh">
                        <div class="preview" id="livePreview" aria-hidden="true">
                            <div>
                                <?php
                                    $preview = '';
                                    if (!empty($img)) {
                                        if (strpos($img, '/') === false && strpos($img, '\\') === false) {
                                            $preview = 'images/SanPham/' . $img;
                                        } else {
                                            $preview = str_replace('\\','/',$img);
                                        }
                                    }
                                ?>
                                <?php if ($preview !== ''): ?>
                                    <img id="previewImg" src="<?php echo htmlspecialchars($preview); ?>" alt="Ảnh sản phẩm">
                                <?php else: ?>
                                    <div id="previewNoImg" style="max-width:200px;">
                                        <div class="name" id="previewName"><?php echo $title !== "" ? htmlspecialchars($title) : "Tên sản phẩm"; ?></div>
                                        <div class="desc" id="previewDesc"><?php
                                            $c = trim($content);
                                            echo $c !== "" ? htmlspecialchars($c) : "Ảnh sẽ hiển thị ở đây khi chọn.";
                                        ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="meta">
                            <div><strong>Ghi chú nhanh</strong></div>
                            <ul style="margin:8px 0 0 18px; padding:0; color:var(--muted);">
                                <li>Giá nhập theo VNĐ, làm tròn 1.000 nếu cần.</li>
                                <li>Chọn loại hàng phù hợp để dễ quản lý.</li>
                                <li>Ảnh nên có kích thước vừa phải (≤ 5MB) và định dạng jpg/png/webp.</li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </form>
        </main>
    </div>

    <script>
        (function(){
            var titleEl = document.getElementById('Title');
            var contentEl = document.getElementById('Content');
            var priceEl = document.getElementById('Price');
            var previewName = document.getElementById('previewName');
            var previewDesc = document.getElementById('previewDesc');
            var previewImg = document.getElementById('previewImg');
            var fileInput = document.querySelector('input[type="file"][name="ImageFile"]');

            function updateTextPreview(){
                if(previewName) previewName.textContent = titleEl.value.trim() || 'Tên sản phẩm';
                if(previewDesc) previewDesc.textContent = contentEl.value.trim() || 'Mô tả ngắn sẽ hiện ở đây khi bạn nhập.';
            }
            if(titleEl) titleEl.addEventListener('input', updateTextPreview);
            if(contentEl) contentEl.addEventListener('input', updateTextPreview);

            if(fileInput){
                fileInput.addEventListener('change', function(e){
                    var f = e.target.files && e.target.files[0];
                    if(!f) return;
                    if(!f.type.startsWith('image/')) {
                        alert('Vui lòng chọn file ảnh.');
                        return;
                    }
                    var reader = new FileReader();
                    reader.onload = function(ev){
                        // nếu có vùng img hiện có -> thay src, ngược lại tạo img
                        if(previewImg){
                            previewImg.src = ev.target.result;
                        } else {
                            var noImg = document.getElementById('previewNoImg');
                            if(noImg) noImg.innerHTML = '<img src="'+ ev.target.result +'" style="max-width:200px; border-radius:8px;">';
                        }
                    };
                    reader.readAsDataURL(f);
                });
            }

            window.validateForm = function(e){
                var t = titleEl.value.trim();
                if(t.length < 2){
                    alert('Vui lòng nhập tên sản phẩm (ít nhất 2 ký tự).');
                    titleEl.focus();
                    return false;
                }
                var p = parseFloat(priceEl.value);
                if(isNaN(p) || p < 0){
                    alert('Vui lòng nhập giá hợp lệ.');
                    priceEl.focus();
                    return false;
                }
                return true;
            };

            window.confirmDelete = function(id){
                if(!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
                window.location.href = 'product_delete.php?id=' + encodeURIComponent(id);
            };
        })();
    </script>
</body>
</html>