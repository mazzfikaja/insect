<?php
require_once 'koneksi.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $image_name = 'default.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_info = pathinfo($_FILES['image']['name']);
        $file_ext = strtolower($file_info['extension']);
        $image_name = uniqid('ann_') . time() . '.' . $file_ext;
        move_uploaded_file($tmp_name, 'assets/images/' . $image_name);
    }

    $stmt = $mysqli->prepare("INSERT INTO announcements (title, content, image, is_active) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $content, $image_name, $is_active);
    $stmt->execute();
    header("Location: admin_pengumuman.php"); exit;
}

$page_title = "Buat Pengumuman Baru";
include 'header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'admin_sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Buat Pengumuman Baru</h3>
            <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary">
                &leftarrow; Kembali
            </a>
        </div>
        <hr>
        <form action="admin_tambah_pengumuman.php" method="post" enctype="multipart/form-data">
            <div class="mb-3"><label for="title" class="form-label">Judul</label><input type="text" name="title" id="title" class="form-control" required></div>
            <div class="mb-3"><label for="content" class="form-label">Konten</label><textarea name="content" id="content" rows="5" class="form-control"></textarea></div>
            <div class="mb-3"><label for="image" class="form-label">Gambar (Opsional)</label><input type="file" name="image" id="image" class="form-control"></div>
            <div class="form-check mb-3"><input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked><label for="is_active" class="form-check-label">Tampilkan di website</label></div>
            <button type="submit" class="btn btn-accent">Simpan Pengumuman</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>