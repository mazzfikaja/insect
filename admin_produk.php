<?php
$page_title = "Manajemen Produk";
include 'header.php';

// Proteksi
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil semua produk
$result = $mysqli->query("SELECT id, brand, model, price, status FROM products ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'admin_sidebar.php'; ?>
    </div>

    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Manajemen Produk</h3>
            <a href="tambah_produk.php" class="btn btn-accent">+ Tambah Produk</a>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($product = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo e($product['id']); ?></td>
                                    <td><?php echo e($product['brand'] . ' ' . $product['model']); ?></td>
                                    <td><?php echo formatRupiah($product['price']); ?></td>
                                    <td><span class="badge bg-<?php echo ($product['status'] == 'Tersedia') ? 'success' : 'secondary'; ?>"><?php echo e($product['status']); ?></span></td>
                                    <td class="text-end">
                                        <a href="edit_produk.php?id=<?php echo e($product['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="hapus_produk.php?id=<?php echo e($product['id']); ?>" class="btn btn-sm btn-danger btn-hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">Belum ada produk.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>