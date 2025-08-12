<?php
$page_title = "Checkout";
include 'header.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: keranjang.php");
    exit;
}

$cart_items = $_SESSION['cart'];
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'];
}
?>

<div class="mb-4">
    <a href="javascript:history.back()" class="btn btn-outline-secondary">
        &leftarrow; Kembali
    </a>
</div>

<h1 class="mb-4">Checkout</h1>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h4>Rincian Pesanan</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <?php foreach ($cart_items as $product_id => $item): ?>
                            <tr>
                                <td>
                                    <img src="assets/images/<?php echo e($item['image']); ?>" width="50" class="rounded">
                                </td>
                                <td>
                                    <?php echo e($item['brand'] . ' ' . $item['model']); ?>
                                </td>
                                <td class="text-end">
                                    <?php echo formatRupiah($item['price']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h4>Ringkasan Belanja</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <strong><?php echo formatRupiah($total_price); ?></strong>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span>Biaya Pengiriman</span>
                    <strong>Gratis</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5>Total Pembayaran</h5>
                    <h5 class="text-accent"><?php echo formatRupiah($total_price); ?></h5>
                </div>
                <div class="d-grid mt-4">
                    <a href="pembayaran.php" class="btn btn-accent btn-lg">
                        Pilih Metode Pembayaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>