<?php
$page_title = "Admin Dashboard";
include 'header.php';

// Proteksi & ambil data admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$admin = null;
$stmt = $mysqli->prepare("SELECT username, email, full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
}
$stmt->close();
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'admin_sidebar.php'; // Panggil sidebar baru kita ?>
    </div>

    <div class="col-md-9">
        <h3>Profil Admin</h3>
        <hr>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person-workspace text-secondary mb-3" viewBox="0 0 16 16"><path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.947a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/><path d="M13.854 8.414a.5.5 0 0 1 .146.353V14a.5.5 0 0 1-.5.5H11.5a.5.5 0 0 1 0-1h2v-4.512a.5.5 0 0 1 .146-.353l.5-.5a.5.5 0 0 1 .708 0l.5.5Z"/><path d="M2.146 8.414a.5.5 0 0 0-.146.353V14a.5.5 0 0 0 .5.5h2.5a.5.5 0 0 0 0-1h-2v-4.512a.5.5 0 0 0-.146-.353l-.5-.5a.5.5 0 0 0-.708 0l-.5.5Z"/><path d="M11.5 6.432V4.5a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5v1.932a.5.5 0 0 1-.288.465l-1.148.574a.5.5 0 0 0 0 .866l1.148.574a.5.5 0 0 1 .288.465V11.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V9.068a.5.5 0 0 1 .288-.465l1.148-.574a.5.5 0 0 0 0-.866l-1.148-.574a.5.5 0 0 1-.288-.465Z"/></svg>
                    <div class="mt-3">
                        <h4><?php echo e($admin['full_name']); ?></h4>
                        <p class="text-secondary mb-1"><?php echo e('@' . $admin['username']); ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0">Nama Lengkap</h6></div>
                    <div class="col-sm-9 text-secondary"><?php echo e($admin['full_name']); ?></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3"><h6 class="mb-0">Email</h6></div>
                    <div class="col-sm-9 text-secondary"><?php echo e($admin['email']); ?></div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
             <a class="btn btn-accent" href="edit_profil.php">Edit Profil</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>