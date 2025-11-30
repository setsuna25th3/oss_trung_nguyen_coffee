<?php
require 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$title = "";
$content = "";

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM category WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cat = $result->fetch_assoc();
    $stmt->close();

    if ($cat) {
        $title = $cat['Title'];
        $content = $cat['Content'];
    } else {
        die("Không tìm thấy loại hàng");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['Title']);
    $content = trim($_POST['Content']);

    if ($id > 0) {
        $sql = "UPDATE category
                SET Title = ?, Content = ?, UpdateAt = NOW()
                WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $sql = "INSERT INTO category (Title, Content, CreateAt, UpdateAt)
                VALUES (?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: category_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $id > 0 ? "Sửa loại hàng" : "Thêm loại hàng"; ?></title>

    <style>
        :root{
            --bg: #f4f7fb;
            --card: #ffffff;
            --accent: #2563eb;
            --muted: #6b7280;
            --success: #16a34a;
            --danger: #ef4444;
            --radius: 12px;
            --maxw: 920px;
            --glass: rgba(255,255,255,0.6);
        }
        *{box-sizing:border-box}
        body{
            margin:0;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: linear-gradient(180deg, #eef2ff 0%, var(--bg) 40%);
            color:#0f172a;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
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
            box-shadow: 0 10px 30px rgba(2,6,23,0.08);
            border: 1px solid rgba(15,23,42,0.04);
        }

        .card-head {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin-bottom:18px;
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
        input[type="text"], textarea {
            width:100%;
            padding:12px 14px;
            font-size:15px;
            border-radius:10px;
            border:1px solid rgba(15,23,42,0.06);
            background: linear-gradient(180deg,#fff,#fbfdff);
            outline:none;
            transition: box-shadow .12s, border-color .12s;
        }
        input[type="text"]:focus, textarea:focus{
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
            min-height:140px;
            background: linear-gradient(180deg, rgba(245,247,255,0.6), rgba(255,255,255,0.6));
            border:1px solid rgba(15,23,42,0.03);
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
        }
        .preview .name { font-weight:700; font-size:17px; margin-bottom:8px; }
        .preview .desc { color:var(--muted); font-size:14px; line-height:1.4; white-space:pre-line; }

        .meta {
            font-size:13px;
            color:var(--muted);
            background:rgba(255,255,255,0.6);
            padding:12px;
            border-radius:8px;
            border:1px dashed rgba(15,23,42,0.03);
        }

        .breadcrumb {
            font-size:13px;
            color:var(--muted);
        }
        .link-back { color:var(--accent); text-decoration:none; font-weight:600; }

        footer.small {
            text-align:center;
            color:var(--muted);
            margin-top:14px;
            font-size:13px;
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
            <div class="breadcrumb">
                <a class="link-back" href="category_list.php">&larr; Danh sách loại hàng</a>
            </div>
        </header>

        <main class="card" role="main" aria-labelledby="pageTitle">
            <div class="card-head">
                <div class="title">
                    <h1 id="pageTitle"><?php echo $id > 0 ? "Sửa loại hàng" : "Thêm loại hàng"; ?></h1>
                    <p><?php echo $id > 0 ? "Chỉnh sửa thông tin loại hàng hiện tại" : "Tạo loại hàng mới để phân loại sản phẩm"; ?></p>
                </div>
                <div style="display:flex;gap:12px;align-items:center;">
                    <?php if ($id > 0): ?>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $id; ?>)">Xóa</button>
                    <?php endif; ?>
                    <a class="btn btn-secondary" href="category_list.php">Quay về</a>
                </div>
            </div>

            <form method="post" onsubmit="return validateForm(event)" novalidate>
                <div class="grid">
                    <div>
                        <label for="Title">Tên loại hàng <span style="color:var(--danger)">*</span></label>
                        <input id="Title" name="Title" type="text" required
                            value="<?php echo htmlspecialchars($title); ?>" placeholder="VD: Sữa, Đồ ăn, Đồ uống...">

                        <label for="Content" style="margin-top:14px">Mô tả</label>
                        <textarea id="Content" name="Content" placeholder="Mô tả ngắn (không bắt buộc)"><?php echo htmlspecialchars($content); ?></textarea>

                        <p class="hint">Mô tả sẽ giúp quản trị viên nhận diện loại hàng nhanh hơn. Có thể thêm vài từ mô tả.</p>

                        <div class="actions">
                            <button type="submit" class="btn btn-primary"><?php echo $id > 0 ? "Lưu thay đổi" : "Tạo loại hàng"; ?></button>
                        </div>
                    </div>

                    <aside class="panel" aria-label="Thông tin nhanh">
                        <div class="preview" id="livePreview" aria-hidden="true">
                            <div>
                                <div class="name" id="previewName"><?php echo $title !== "" ? htmlspecialchars($title) : "Tên loại hàng"; ?></div>
                                <div class="desc" id="previewDesc"><?php
                                    $c = trim($content);
                                    echo $c !== "" ? htmlspecialchars($c) : "Mô tả ngắn sẽ hiện ở đây khi bạn nhập.";
                                ?></div>
                            </div>
                        </div>

                        <div class="meta">
                            <div><strong>Ghi chú nhanh</strong></div>
                            <ul style="margin:8px 0 0 18px; padding:0; color:var(--muted);">
                                <li>Tên loại hàng là bắt buộc và nên ngắn gọn.</li>
                                <li>Không nên tạo trùng tên để tránh nhầm lẫn.</li>
                                <li>Bạn có thể chỉnh sau khi tạo.</li>
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
            var pName = document.getElementById('previewName');
            var pDesc = document.getElementById('previewDesc');

            function updatePreview(){
                var t = titleEl.value.trim();
                var c = contentEl.value.trim();
                pName.textContent = t === '' ? 'Tên loại hàng' : t;
                pDesc.textContent = c === '' ? 'Mô tả ngắn sẽ hiện ở đây khi bạn nhập.' : c;
            }
            titleEl.addEventListener('input', updatePreview);
            contentEl.addEventListener('input', updatePreview);

            window.validateForm = function(e){
                var t = titleEl.value.trim();
                if(t.length < 2){
                    alert('Vui lòng nhập tên loại hàng (ít nhất 2 ký tự).');
                    titleEl.focus();
                    return false;
                }
                return true;
            };

            window.confirmDelete = function(id){
                if(!confirm('Bạn có chắc muốn xóa loại hàng này? Hành động không thể hoàn tác.')) return;
                window.location.href = 'category_delete.php?id=' + encodeURIComponent(id);
            };
        })();
    </script>
</body>
</html>
