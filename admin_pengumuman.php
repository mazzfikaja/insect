<?php
$page_title = "Manajemen Pengumuman";
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = (int)$_GET['id'];
    $stmt_delete = $mysqli->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt_delete->bind_param("i", $id_to_delete);
    $stmt_delete->execute();
    header("Location: admin_pengumuman.php");
    exit;
}

$announcements = $mysqli->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'admin_sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Manajemen Pengumuman</h3>
            <a href="tambah_pengumuman.php" class="btn btn-accent">+ Buat Pengumuman</a>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Judul</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    <?php if ($announcements->num_rows > 0): ?>
                        <?php while($item = $announcements->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo e($item['title']); ?></td>
                            <td><?php echo ($item['is_active'] == 1) ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>'; ?></td>
                            <td class="text-end">
                                <a href="edit_pengumuman.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="admin_pengumuman.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pengumuman ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">Belum ada pengumuman.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>
