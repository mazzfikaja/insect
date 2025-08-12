<?php
require_once 'koneksi.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit;
}
$announcement_id = (int)$_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $current_image = $_POST['current_image'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if ($current_image !== 'default.jpg' && file_exists('assets/images/' . $current_image)) {
            unlink('assets/images/' . $current_image);
        }
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_info = pathinfo($_FILES['image']['name']);
        $file_ext = strtolower($file_info['extension']);
        $new_image_name = uniqid('ann_') . time() . '.' . $file_ext;
        move_uploaded_file($tmp_name, 'assets/images/' . $new_image_name);
    } else {
        $new_image_name = $current_image;
    }

    $stmt = $mysqli->prepare("UPDATE announcements SET title=?, content=?, image=?, is_active=? WHERE id=?");
    $stmt->bind_param("sssii", $title, $content, $new_image_name, $is_active, $announcement_id);
    $stmt->execute();
    header("Location: admin_pengumuman.php"); exit;
}

$stmt = $mysqli->prepare("SELECT * FROM announcements WHERE id = ?");
$stmt->bind_param("i", $announcement_id);
$stmt->execute();
$announcement = $stmt->get_result()->fetch_assoc();

$page_title = "Edit Pengumuman";
include 'header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'admin_sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
             <h3>Edit Pengumuman</h3>
             <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary">
                &leftarrow; Kembali
            </a>
        </div>
        <hr>
        <form action="admin_edit_pengumuman.php?id=<?php echo $announcement_id; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="current_image" value="<?php echo e($announcement['image']); ?>">
            <div class="mb-3"><label for="title" class="form-label">Judul</label><input type="text" name="title" id="title" class="form-control" value="<?php echo e($announcement['title']); ?>" required></div>
            <div class="mb-3"><label for="content" class="form-label">Konten</label><textarea name="content" id="content" rows="5" class="form-control"><?php echo e($announcement['content']); ?></textarea></div>
            <div class="mb-3">
                <label for="image" class="form-label">Ganti Gambar (Opsional)</label>
                <p><img src="assets/images/<?php echo e($announcement['image']); ?>" width="150" class="img-thumbnail"></p>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <div class="form-check mb-3"><input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" <?php echo ($announcement['is_active'] == 1) ? 'checked' : ''; ?>><label for="is_active" class="form-check-label">Tampilkan di website</label></div>
            <button type="submit" class="btn btn-accent">Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>