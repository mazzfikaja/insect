<?php
$page_title = "Akun Saya";
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$user = null;
$stmt_user = $mysqli->prepare("SELECT username, email, full_name, role FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($result_user->num_rows === 1) {
    $user = $result_user->fetch_assoc();
} else {
    session_destroy();
    header("Location: login.php");
    exit;
}
$stmt_user->close();

$sql_trx = "SELECT t.purchase_price, t.purchase_date, p.brand, p.model, p.main_image
            FROM transactions t
            JOIN products p ON t.product_id = p.id
            WHERE t.user_id = ?
            ORDER BY t.purchase_date DESC";
$stmt_trx = $mysqli->prepare($sql_trx);
$stmt_trx->bind_param("i", $user_id);
$stmt_trx->execute();
$transactions = $stmt_trx->get_result();
$stmt_trx->close();
?>

<div class="mb-4">
    <a href="javascript:history.back()" class="btn btn-outline-secondary">
        &leftarrow; Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="akun.php" class="list-group-item list-group-item-action active" aria-current="page">Profil & Riwayat</a>
            <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
        </div>
    </div>

    <div class="col-md-9">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success mb-4">
                <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person-circle text-secondary mb-3" viewBox="0 0 16 16"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/></svg>
                    <div class="mt-3">
                        <h4><?php echo e($user['full_name']); ?></h4>
                        <p class="text-secondary mb-1"><?php echo e('@' . $user['username']); ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0">Nama Lengkap</h6></div>
                    <div class="col-sm-9 text-secondary"><?php echo e($user['full_name']); ?></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0">Email</h6></div>
                    <div class="col-sm-9 text-secondary"><?php echo e($user['email']); ?></div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
             <a class="btn btn-accent" href="edit_profil.php">Edit Profil</a>
        </div>


        <h3 class="mt-5 mb-3">Riwayat Transaksi Anda</h3>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th colspan="2">Produk</th>
                                <th>Tanggal Pembelian</th>
                                <th class="text-end">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($transactions->num_rows > 0): ?>
                                <?php while($trx = $transactions->fetch_assoc()): ?>
                                    <tr>
                                        <td style="width: 80px;">
                                            <img src="assets/images/<?php echo e($trx['main_image']); ?>" width="60" class="rounded" alt="">
                                        </td>
                                        <td><?php echo e($trx['brand'] . ' ' . $trx['model']); ?></td>
                                        <td><?php echo date('d F Y', strtotime($trx['purchase_date'])); ?></td>
                                        <td class="text-end"><?php echo formatRupiah($trx['purchase_price']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Anda belum memiliki riwayat transaksi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>