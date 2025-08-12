<?php
require_once 'koneksi.php';
require_once 'functions.php';

if (isset($_GET['ajax'])) {
    $searchTerm = $_GET['query'] ?? '';
    $products = getAllProducts($mysqli, $searchTerm);

    if (!empty($products)) {
        foreach ($products as $product) {
            echo '<div class="col">';
            echo '    <div class="card h-100 product-card">';
            
            if (!empty($product['main_image']) && $product['main_image'] !== 'default.jpg' && file_exists('assets/images/' . $product['main_image'])) {
                echo '        <img src="assets/images/' . e($product['main_image']) . '" class="card-img-top" alt="' . e($product['model']) . '">';
            } else {
                echo '        <div class="product-image-placeholder"><span class="text-muted">Tidak Ada Gambar</span></div>';
            }
            
            echo '        <div class="card-body d-flex flex-column">';
            echo '            <h5 class="card-title">' . e($product['brand'] . ' ' . $product['model']) . '</h5>';
            echo '            <p class="card-text text-accent fw-bold fs-5">' . formatRupiah($product['price']) . '</p>';
            echo '            <a href="produk.php?id=' . e($product['id']) . '" class="btn btn-outline-accent mt-auto">Lihat Detail</a>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-12"><p class="text-center text-muted">Tidak ada produk yang cocok dengan pencarian Anda.</p></div>';
    }

    exit;
}

$page_title = "Marketplace";
include 'header.php';

$products = getAllProducts($mysqli, '');
?>

<h1 class="mb-4">Katalog Produk</h1>

<div class="mb-4">
    <input type="text" class="form-control" id="search-input" placeholder="Ketik untuk mencari merek atau model...">
</div>

<hr>

<div id="product-list" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    <?php  ?>
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card h-100 product-card">
                    <?php if (!empty($product['main_image']) && $product['main_image'] !== 'default.jpg' && file_exists('assets/images/' . $product['main_image'])): ?>
                        <img src="assets/images/<?php echo e($product['main_image']); ?>" class="card-img-top" alt="<?php echo e($product['model']); ?>">
                    <?php else: ?>
                        <div class="product-image-placeholder">
                            <span class="text-muted">Tidak Ada Gambar</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo e($product['brand'] . ' ' . $product['model']); ?></h5>
                        <p class="card-text text-accent fw-bold fs-5"><?php echo formatRupiah($product['price']); ?></p>
                        <a href="produk.php?id=<?php echo e($product['id']); ?>" class="btn btn-outline-accent mt-auto">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12"><p class="text-center text-muted">Belum ada produk yang dijual saat ini.</p></div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const searchInput = document.getElementById('search-input');
    const productList = document.getElementById('product-list');

    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value;

        fetch(`marketplace.php?ajax=1&query=${encodeURIComponent(query)}`)
            .then(response => response.text()) 
            .then(data => {
                productList.innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
                productList.innerHTML = '<div class="col-12"><p class="text-center text-danger">Terjadi kesalahan saat mencari.</p></div>';
            });
    });

});
</script>

<?php include 'footer.php'; ?>