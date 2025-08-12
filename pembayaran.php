<?php
$page_title = "Instruksi Pembayaran";
include 'header.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: keranjang.php");
    exit;
}

$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'];
}
?>

<div class="mb-4">
    <a href="javascript:history.back()" class="btn btn-outline-secondary">
        &leftarrow; Kembali 
    </a>
</div>


<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="text-center">
            <h1 class="mb-3">Selesaikan Pembayaran</h1>
            <p class="lead text-muted">Satu langkah lagi! Silakan lakukan transfer sejumlah total pembayaran di bawah ini.</p>
        </div>

        <div class="card my-4">
            <div class="card-body text-center">
                <p class="mb-2">Total Pembayaran:</p>
                <h2 class="text-accent fw-bold"><?php echo formatRupiah($total_price); ?></h2>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Metode Pembayaran</h4>
            </div>
            <div class="card-body">
                <h5>Transfer Bank</h5>
                <ul>
                    <li>BCA: <strong>1234567890</strong> (a/n Nama Anda)</li>
                    <li>Mandiri: <strong>0987654321</strong> (a/n Nama Anda)</li>
                </ul>

                <h5>E-Wallet</h5>
                <ul>
                    <li>GoPay / Dana / OVO: <strong>081234567890</strong> (a/n Nama Anda)</li>
                </ul>

                <h5>QRIS</h5>
                <p>Anda juga bisa scan QR Code di bawah ini (mendukung semua aplikasi pembayaran).</p>
                <div class="text-center">
                    <img src="assets/images/qris.jpg" class="img-fluid" style="max-width: 250px;" alt="QRIS Code">
                </div>
            </div>
            <div class="card-footer text-center bg-light">
                <h5 class="mt-3">Langkah Terakhir: Konfirmasi</h5>
                <p>Setelah melakukan pembayaran, mohon kirimkan bukti transfer melalui WhatsApp agar pesanan Anda bisa segera kami proses.</p>
                <a href="https://wa.me/6281234567890?text=Halo, saya sudah melakukan pembayaran untuk pesanan saya." target="_blank" class="btn btn-success">
                    Konfirmasi via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>