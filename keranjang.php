<?php
require_once 'koneksi.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?pesan=harus_login");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $product_id_to_remove = (int)$_GET['id'];

    if (isset($_SESSION['cart'][$product_id_to_remove])) {
        unset($_SESSION['cart'][$product_id_to_remove]);
        $_SESSION['flash_message'] = "Produk berhasil dihapus dari keranjang.";
    }
    
    header("Location: keranjang.php");
    exit;
}


$page_title = "Keranjang Belanja";
include 'header.php'; 

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_items = $_SESSION['cart'];
$total_price = 0;
?>

<div class="mb-4">
    <a href="javascript:history.back()" class="btn btn-outline-secondary">
        &leftarrow; Kembali
    </a>
</div>

<h1 class="mb-4">Keranjang Belanja Anda</h1>

<?php
if (isset($_SESSION['flash_message'])):
?>
    <div class="alert alert-success">
        <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <?php if (!empty($cart_items)): ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th colspan="2">Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $product_id => $item): ?>
                            <tr>
                                <td style="width: 100px;">
                                    <img src="assets/images/<?php echo e($item['image']); ?>" width="80" class="rounded" alt="<?php echo e($item['model']); ?>">
                                </td>
                                <td>
                                    <strong><?php echo e($item['brand'] . ' ' . $item['model']); ?></strong>
                                </td>
                                <td class="text-end"><?php echo formatRupiah($item['price']); ?></td>
                                <td class="text-center">
                                    <a href="keranjang.php?action=remove&id=<?php echo $product_id; ?>" class="btn btn-sm btn-outline-danger" title="Hapus dari Keranjang">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php $total_price += $item['price']; ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end"><h4>Total</h4></th>
                            <th class="text-end"><h4><?php echo formatRupiah($total_price); ?></h4></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <a href="marketplace.php" class="btn btn-secondary me-2">Lanjut Belanja</a>
                <a href="checkout.php" class="btn btn-accent">Lanjutkan ke Pembayaran</a>
            </div>
        <?php else: ?>
            <div class="text-center p-5">
                <h4>Keranjang Anda masih kosong.</h4>
                <p class="text-muted">Ayo jelajahi marketplace dan temukan gadget impian Anda!</p>
                <a href="marketplace.php" class="btn btn-accent mt-3">Mulai Belanja</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>