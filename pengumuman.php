<?php
$page_title = "Semua Pengumuman";
include 'header.php';

// Ambil SEMUA pengumuman aktif dari database
$all_announcements = getAllActiveAnnouncements($mysqli);
?>

<div class="mb-4">
    <a href="javascript:history.back()" class="btn btn-outline-secondary">&leftarrow; Kembali</a>
</div>

<h1 class="mb-4">Pengumuman & Info Terbaru</h1>
<hr>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php if (!empty($all_announcements)): ?>
        <?php foreach ($all_announcements as $item): ?>
            <div class="col">
                <div class="card h-100">
                    <div class="product-image-placeholder">
                        <span class="text-muted">Gambar Pengumuman</span>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-1"><?php echo date('d F Y', strtotime($item['created_at'])); ?></p>
                        <h5 class="card-title fw-bold"><?php echo e($item['title']); ?></h5>
                        <p class="card-text small"><?php echo e($item['content']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col">
            <p class="text-center text-muted">Belum ada pengumuman saat ini.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>