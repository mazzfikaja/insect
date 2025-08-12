<?php
require_once 'koneksi.php';
require_once 'functions.php';

$product_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    $page_title = "Error";
    include 'header.php';
    echo "<div class='alert alert-danger'>ID Produk tidak valid.</div>";
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    if (isset($_POST['add_to_cart'])) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (!isset($_SESSION['cart'][$product_id])) {
            $stmt = $mysqli->prepare("SELECT brand, model, price, main_image FROM products WHERE id = ? AND status = 'Tersedia'");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $product = $result->fetch_assoc();
                $_SESSION['cart'][$product_id] = [
                    'brand' => $product['brand'], 'model' => $product['model'],
                    'price' => $product['price'], 'image' => $product['main_image']
                ];
            }
        }
        header("Location: produk.php?id=" . $product_id);
        exit;
    }

    if (isset($_POST['buy_now'])) {
        $stmt = $mysqli->prepare("SELECT brand, model, price, main_image FROM products WHERE id = ? AND status = 'Tersedia'");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $product = $result->fetch_assoc();
            $_SESSION['cart'] = []; 
            $_SESSION['cart'][$product_id] = [ 
                'brand' => $product['brand'], 'model' => $product['model'],
                'price' => $product['price'], 'image' => $product['main_image']
            ];
            header("Location: checkout.php");
            exit;
        }
    }
}

$product = getProductDetailsById($mysqli, $product_id);

if ($product === null) {
    $page_title = "Error";
    include 'header.php';
    echo "<div class='alert alert-danger'>Produk tidak ditemukan atau sudah terjual.</div>";
    include 'footer.php';
    exit;
}

$gallery_images = getGalleryImages($mysqli, $product_id);
$all_images = [];
if (!empty($product['main_image']) && $product['main_image'] !== 'default.jpg' && file_exists('assets/images/' . $product['main_image'])) {
    $all_images[] = $product['main_image'];
}
foreach ($gallery_images as $image_filename) {
    if (file_exists('assets/images/' . $image_filename)) {
        $all_images[] = $image_filename;
    }
}

$GLOBALS['page_title'] = e($product['brand'] . ' ' . $product['model']);
include 'header.php';
?>

<div class="mb-4">
    <a href="javascript:history.back()" class="btn btn-outline-secondary">&leftarrow; Kembali</a>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="product-gallery-container">
            <?php if (!empty($all_images)): ?>
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        <?php foreach ($all_images as $index => $image): ?>
                            <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                                <img src="assets/images/<?php echo e($image); ?>" class="d-block w-100" style="height: 450px; object-fit: contain; background-color: #f8f9fa;" alt="Foto Produk <?php echo $index + 1; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($all_images) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></button>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center border rounded bg-light" style="min-height: 450px;">
                    <span class="text-muted fs-4">[ Tidak Ada Foto ]</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-5 d-flex flex-column">
        <div>
            <h1><?php echo e($product['brand'] . ' ' . $product['model']); ?></h1>
            <p class="display-6 text-accent fw-bold"><?php echo formatRupiah($product['price']); ?></p>
            <span class="badge bg-secondary fs-6 mb-3">Kondisi: <?php echo e($product['grade']); ?></span>
            <h4>Deskripsi</h4>
            <p><?php echo nl2br(e($product['description'])); ?></p>
        </div>
        
        <div class="mt-auto">
            <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan produk <?php echo urlencode($product['brand'] . ' ' . $product['model']); ?> di website Insect." target="_blank" class="btn btn-success btn-lg w-100">
                Hubungi via WhatsApp
            </a>

            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
                <div class="d-flex gap-2 mt-2">
                    <form action="produk.php?id=<?php echo e($product['id']); ?>" method="POST" class="w-100">
                        <button type="submit" name="add_to_cart" class="btn btn-outline-accent btn-lg w-100">
                            + Keranjang
                        </button>
                    </form>
                    
                    <form action="produk.php?id=<?php echo e($product['id']); ?>" method="POST" class="w-100">
                        <button type="submit" name="buy_now" class="btn btn-accent btn-lg w-100">
                            Beli Sekarang
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center mt-3 small">
                    Silakan <a href="login.php" class="alert-link">Login</a> atau <a href="register.php" class="alert-link">Daftar</a> untuk mulai berbelanja.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<hr class="my-5">

<div class="card">
    <div class="card-header bg-light"><h3 class="mb-0">Laporan Inspeksi Detail</h3></div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Kesehatan Baterai: <strong><?php echo e($product['battery_health']); ?>%</strong></li>
            <li class="list-group-item">Kondisi Layar: <strong><?php echo e($product['screen_condition']); ?></strong></li>
            <li class="list-group-item">Kondisi Bodi: <strong><?php echo e($product['body_condition']); ?></strong></li>
            <li class="list-group-item">Status Kamera: <strong><?php echo e($product['camera_status']); ?></strong></li>
            <li class="list-group-item">Status Speaker: <strong><?php echo e($product['speaker_status']); ?></strong></li>
        </ul>
    </div>
</div>

<?php include 'footer.php'; ?>