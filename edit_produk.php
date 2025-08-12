<?php
require_once 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

require_once 'functions.php';
$product_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['product_id']) ? (int)$_POST['product_id'] : null);

if (!$product_id) {
    $page_title = "Error";
    include 'header.php';
    echo "<div class='alert alert-danger'>ID produk tidak valid.</div>";
    include 'footer.php';
    exit;
}

$current_product_status_result = $mysqli->query("SELECT status FROM products WHERE id = $product_id");
$current_product_status = $current_product_status_result ? $current_product_status_result->fetch_assoc()['status'] : 'Tersedia';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['delete_images'])) {
        foreach ($_POST['delete_images'] as $image_id) {
            $image = getImageById($mysqli, $image_id);
            if ($image) {
                $file_path = 'assets/images/' . $image['filename'];
                if (file_exists($file_path) && $image['filename'] !== 'default.jpg') {
                    unlink($file_path);
                }
                $mysqli->query("DELETE FROM product_images WHERE id = " . (int)$image_id);
            }
        }
    }
    if (isset($_FILES['new_images'])) {
        foreach ($_FILES['new_images']['name'] as $key => $name) {
            if ($_FILES['new_images']['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['new_images']['tmp_name'][$key];
                $file_info = pathinfo($name);
                $file_ext = strtolower($file_info['extension']);
                $unique_name = uniqid('gallery_') . '_' . time() . '.' . $file_ext;
                $target_file = 'assets/images/' . $unique_name;
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $stmt_image = $mysqli->prepare("INSERT INTO product_images (product_id, filename) VALUES (?, ?)");
                    $stmt_image->bind_param("is", $product_id, $unique_name);
                    $stmt_image->execute();
                }
            }
        }
    }

    $status = $_POST['status'];
    $price = $_POST['price'];
    $buyer_id = $_POST['buyer_id'];
    $main_image_filename = $_POST['set_main_image'];
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $grade = $_POST['grade'];
    $description = trim($_POST['description']);
    $battery_health = $_POST['battery_health'];
    $screen_condition = trim($_POST['screen_condition']);
    $body_condition = trim($_POST['body_condition']);
    $camera_status = trim($_POST['camera_status']);
    $speaker_status = trim($_POST['speaker_status']);

    $sql_products = "UPDATE products SET brand = ?, model = ?, price = ?, status = ?, grade = ?, description = ?, main_image = ? WHERE id = ?";
    $stmt_products = $mysqli->prepare($sql_products);
    $stmt_products->bind_param("ssdssssi", $brand, $model, $price, $status, $grade, $description, $main_image_filename, $product_id);
    $stmt_products->execute();
    
    $sql_details = "UPDATE product_details SET battery_health = ?, screen_condition = ?, body_condition = ?, camera_status = ?, speaker_status = ? WHERE product_id = ?";
    $stmt_details = $mysqli->prepare($sql_details);
    $stmt_details->bind_param("issssi", $battery_health, $screen_condition, $body_condition, $camera_status, $speaker_status, $product_id);
    $stmt_details->execute();

    if ($status === 'Terjual' && $current_product_status === 'Tersedia' && !empty($buyer_id)) {
        $stmt_trans = $mysqli->prepare("INSERT INTO transactions (user_id, product_id, purchase_price) VALUES (?, ?, ?)");
        $stmt_trans->bind_param("iid", $buyer_id, $product_id, $price);
        $stmt_trans->execute();
    }
    
    header("Location: admin_produk.php");
    exit;
}

$product = getAdminProductDetailsById($mysqli, $product_id);
if (!$product) {
    $page_title = "Error";
    include 'header.php';
    echo "<div class='alert alert-danger'>Produk dengan ID $product_id tidak ditemukan.</div>";
    include 'footer.php';
    exit;
}

$gallery_images = $mysqli->query("SELECT * FROM product_images WHERE product_id = $product_id");
$all_users = $mysqli->query("SELECT id, username, full_name FROM users WHERE role = 'user'");
$page_title = "Edit Produk";
include 'header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'admin_sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Edit Produk: <?php echo e($product['brand'] . ' ' . $product['model']); ?></h3>
            <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary">
                &leftarrow; Kembali
            </a>
        </div>
        <hr>
        
        <form action="edit_produk.php" method="post" class="card p-4" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo e($product['id']); ?>">
            <h4 class="mb-3">Informasi Utama</h4>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Merek</label><input type="text" name="brand" class="form-control" value="<?php echo e($product['brand']); ?>" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Model</label><input type="text" name="model" class="form-control" value="<?php echo e($product['model']); ?>" required></div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3"><label class="form-label">Harga (Rp)</label><input type="number" name="price" class="form-control" value="<?php echo e($product['price']); ?>" required></div>
                <div class="col-md-4 mb-3"><label class="form-label">Tingkat Kondisi</label><select name="grade" class="form-select"><option value="Sempurna" <?php echo ($product['grade'] == 'Sempurna') ? 'selected' : ''; ?>>Sempurna</option><option value="Baik" <?php echo ($product['grade'] == 'Baik') ? 'selected' : ''; ?>>Baik</option><option value="Cukup" <?php echo ($product['grade'] == 'Cukup') ? 'selected' : ''; ?>>Cukup</option></select></div>
                <div class="col-md-4 mb-3"><label class="form-label">Status</label><select name="status" class="form-select" id="status-select"><option value="Tersedia" <?php echo ($product['status'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option><option value="Terjual" <?php echo ($product['status'] == 'Terjual') ? 'selected' : ''; ?>>Terjual</option></select></div>
            </div>
            <div id="buyer-selection" class="mb-3" style="<?php echo ($product['status'] == 'Tersedia') ? 'display: none;' : ''; ?>">
                <label for="buyer_id" class="form-label">Catat Transaksi untuk Pengguna (Opsional)</label>
                <select name="buyer_id" id="buyer_id" class="form-select">
                    <option value="">-- Pembeli dari Luar / Cukup Tandai Terjual --</option>
                    <?php while($user = $all_users->fetch_assoc()): ?>
                        <option value="<?php echo e($user['id']); ?>"><?php echo e($user['full_name'] . ' (@' . $user['username'] . ')'); ?></option>
                    <?php endwhile; ?>
                </select>
                <small class="form-text text-muted">Pilih pengguna jika ingin transaksi ini tercatat di riwayat akunnya. Biarkan kosong jika terjual di luar web.</small>
            </div>
            <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" rows="3" class="form-control"><?php echo e($product['description']); ?></textarea></div>
            <hr class="my-4">
            <h4 class="mb-3">Kelola Foto Produk</h4>
            <p class="text-muted small">Pilih salah satu foto untuk dijadikan "Foto Utama" (thumbnail).</p>
            <div class="row">
                <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <div class="card">
                        <img src="assets/images/<?php echo e($product['main_image']); ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body text-center p-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="set_main_image" id="main_<?php echo e($product['id']); ?>" value="<?php echo e($product['main_image']); ?>" <?php echo 'checked'; ?>>
                                <label class="form-check-label small" for="main_<?php echo e($product['id']); ?>">Foto Utama</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $gallery_images->data_seek(0); ?>
                <?php while($image = $gallery_images->fetch_assoc()): ?>
                <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <div class="card">
                        <img src="assets/images/<?php echo e($image['filename']); ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="set_main_image" id="img_<?php echo e($image['id']); ?>" value="<?php echo e($image['filename']); ?>">
                                <label class="form-check-label small" for="img_<?php echo e($image['id']); ?>">Jadikan Utama</label>
                            </div>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="delete_images[]" value="<?php echo e($image['id']); ?>" id="del_<?php echo e($image['id']); ?>">
                                <label class="form-check-label small text-danger" for="del_<?php echo e($image['id']); ?>">Hapus</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <hr class="my-4">
            <h4 class="mb-3">Tambah Foto Baru</h4>
            <div id="gallery-inputs">
                <div class="mb-2">
                    <input class="form-control" type="file" name="new_images[]">
                </div>
            </div>
            <button type="button" id="add-image-btn" class="btn btn-secondary btn-sm mt-2">+ Tambah Slot Foto</button>
            <hr class="my-4">
            <h4 class="mb-3">Detail Laporan Inspeksi</h4>
            <div class="mb-3"><label class="form-label">Kesehatan Baterai (%)</label><input type="number" name="battery_health" class="form-control" value="<?php echo e($product['battery_health']); ?>"></div>
            <div class="mb-3"><label class="form-label">Kondisi Layar</label><input type="text" name="screen_condition" class="form-control" value="<?php echo e($product['screen_condition']); ?>"></div>
            <div class="mb-3"><label class="form-label">Kondisi Bodi</label><input type="text" name="body_condition" class="form-control" value="<?php echo e($product['body_condition']); ?>"></div>
            <div class="mb-3"><label class="form-label">Status Kamera</label><input type="text" name="camera_status" class="form-control" value="<?php echo e($product['camera_status']); ?>"></div>
            <div class="mb-3"><label class="form-label">Status Speaker</label><input type="text" name="speaker_status" class="form-control" value="<?php echo e($product['speaker_status']); ?>"></div>
            <button type="submit" class="btn btn-primary w-100 mt-4">Simpan Semua Perubahan</button>
        </form>
    </div>
</div>

<script>
    const statusSelect = document.getElementById('status-select');
    const buyerSelection = document.getElementById('buyer-selection');
    function toggleBuyerSelection() {
        if (statusSelect.value === 'Terjual') {
            buyerSelection.style.display = 'block';
        } else {
            buyerSelection.style.display = 'none';
        }
    }
    toggleBuyerSelection();
    statusSelect.addEventListener('change', toggleBuyerSelection);
    document.getElementById('add-image-btn').addEventListener('click', function() {
        const newDiv = document.createElement('div');
        newDiv.classList.add('mb-2');
        const newInput = document.createElement('input');
        newInput.type = 'file';
        newInput.name = 'new_images[]';
        newInput.classList.add('form-control');
        newDiv.appendChild(newInput);
        document.getElementById('gallery-inputs').appendChild(newDiv);
    });
</script>

<?php include 'footer.php'; ?>