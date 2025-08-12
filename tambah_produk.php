<?php
require_once 'koneksi.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

$page_title = "Tambah Produk Baru";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $grade = $_POST['grade'];
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $battery_health = $_POST['battery_health'];
    $screen_condition = trim($_POST['screen_condition']);
    $body_condition = trim($_POST['body_condition']);
    $camera_status = trim($_POST['camera_status']);
    $speaker_status = trim($_POST['speaker_status']);

    if (empty($brand) || empty($model) || empty($price)) {
        $errors[] = "Merek, Model, dan Harga wajib diisi.";
    }

    if (empty($errors)) {
        $sql_product = "INSERT INTO products (brand, model, grade, price, description, main_image) VALUES (?, ?, ?, ?, ?, 'default.jpg')";
        if ($stmt_product = $mysqli->prepare($sql_product)) {
            $stmt_product->bind_param("sssis", $brand, $model, $grade, $price, $description);
            
            if ($stmt_product->execute()) {
                $product_id = $mysqli->insert_id;

                $sql_details = "INSERT INTO product_details (product_id, battery_health, screen_condition, body_condition, camera_status, speaker_status) VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt_details = $mysqli->prepare($sql_details)) {
                    $stmt_details->bind_param("iissss", $product_id, $battery_health, $screen_condition, $body_condition, $camera_status, $speaker_status);
                    $stmt_details->execute();
                    $stmt_details->close();
                }

                $main_image_filename = 'default.jpg';
                if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['main_image']['tmp_name'];
                    $name = basename($_FILES['main_image']['name']);
                    $file_info = pathinfo($name);
                    $file_ext = strtolower($file_info['extension']);
                    $main_image_filename = uniqid('prod_') . '_' . time() . '.' . $file_ext;
                    move_uploaded_file($tmp_name, 'assets/images/' . $main_image_filename);
                }

                if (isset($_FILES['secondary_images'])) {
                    foreach ($_FILES['secondary_images']['name'] as $key => $name) {
                        if ($_FILES['secondary_images']['error'][$key] === UPLOAD_ERR_OK) {
                            $tmp_name = $_FILES['secondary_images']['tmp_name'][$key];
                            $file_info = pathinfo($name);
                            $file_ext = strtolower($file_info['extension']);
                            $unique_name = uniqid('gallery_') . '_' . time() . '.' . $file_ext;
                            $target_file = 'assets/images/' . $unique_name;
                            if (move_uploaded_file($tmp_name, $target_file)) {
                                $sql_image = "INSERT INTO product_images (product_id, filename) VALUES (?, ?)";
                                if ($stmt_image = $mysqli->prepare($sql_image)) {
                                    $stmt_image->bind_param("is", $product_id, $unique_name);
                                    $stmt_image->execute();
                                    $stmt_image->close();
                                }
                            }
                        }
                    }
                }
                
                if ($main_image_filename !== 'default.jpg') {
                    $sql_update_main = "UPDATE products SET main_image = ? WHERE id = ?";
                    if ($stmt_update = $mysqli->prepare($sql_update_main)) {
                        $stmt_update->bind_param("si", $main_image_filename, $product_id);
                        $stmt_update->execute();
                        $stmt_update->close();
                    }
                }
                
                header("Location: admin_produk.php");
                exit;
            }
        }
        $errors[] = "Gagal menyimpan data ke database.";
    }
}
include 'header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'admin_sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Tambah Produk Baru</h3>
            <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary">
                &leftarrow; Kembali
            </a>
        </div>
        <hr>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) echo "<p class='mb-0'>" . e($error) . "</p>"; ?>
            </div>
        <?php endif; ?>

        <form action="tambah_produk.php" method="post" class="card p-4" enctype="multipart/form-data">
            <h4 class="mb-3">Informasi Utama</h4>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="brand" class="form-label">Merek</label><input type="text" name="brand" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label for="model" class="form-label">Model</label><input type="text" name="model" class="form-control" required></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label for="price" class="form-label">Harga (Rp)</label><input type="number" name="price" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label for="grade" class="form-label">Tingkat Kondisi</label><select name="grade" class="form-select"><option value="Sempurna">Sempurna</option><option value="Baik">Baik</option><option value="Cukup">Cukup</option></select></div>
            </div>
            <div class="mb-3"><label for="description" class="form-label">Deskripsi</label><textarea name="description" rows="3" class="form-control"></textarea></div>
            <hr class="my-4">
            <h4 class="mb-3">Foto Produk</h4>
            <div class="mb-3">
                <label for="main_image" class="form-label">Foto Utama (Untuk Etalase)</label>
                <input class="form-control" type="file" name="main_image" id="main_image">
            </div>
            <div id="gallery-inputs">
                <label class="form-label">Galeri Foto Detail</label>
                <div class="mb-2">
                    <input class="form-control" type="file" name="secondary_images[]">
                </div>
            </div>
            <button type="button" id="add-image-btn" class="btn btn-secondary btn-sm mt-2">+ Tambah Slot Foto</button>
            <hr class="my-4">
            <h4 class="mb-3">Detail Laporan Inspeksi</h4>
            <div class="mb-3"><label for="battery_health" class="form-label">Kesehatan Baterai (%)</label><input type="number" name="battery_health" class="form-control" placeholder="Contoh: 85"></div>
            <div class="mb-3"><label for="screen_condition" class="form-label">Kondisi Layar</label><input type="text" name="screen_condition" class="form-control" placeholder="Contoh: Mulus, tidak ada goresan"></div>
            <div class="mb-3"><label for="body_condition" class="form-label">Kondisi Bodi</label><input type="text" name="body_condition" class="form-control" placeholder="Contoh: Lecet halus di sudut"></div>
            <div class="mb-3"><label for="camera_status" class="form-label">Status Kamera</label><input type="text" name="camera_status" class="form-control" placeholder="Contoh: Normal, jernih"></div>
            <div class="mb-3"><label for="speaker_status" class="form-label">Status Speaker</label><input type="text" name="speaker_status" class="form-control" placeholder="Contoh: Normal, tidak pecah"></div>
            <button type="submit" class="btn btn-accent w-100 mt-3">Simpan Produk</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('add-image-btn').addEventListener('click', function() {
        const newDiv = document.createElement('div');
        newDiv.classList.add('mb-2');
        const newInput = document.createElement('input');
        newInput.type = 'file';
        newInput.name = 'secondary_images[]';
        newInput.classList.add('form-control');
        newDiv.appendChild(newInput);
        document.getElementById('gallery-inputs').appendChild(newDiv);
    });
</script>

<?php include 'footer.php'; ?>