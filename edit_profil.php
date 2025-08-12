<?php
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'functions.php';
$user_id = $_SESSION['user_id'];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);

        if(empty($full_name) || empty($email)) {
            $errors[] = "Nama dan Email tidak boleh kosong.";
        } else {
            $stmt = $mysqli->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $full_name, $email, $user_id);
            $stmt->execute();
            $_SESSION['flash_message'] = "Profil berhasil diperbarui!";
            header("Location: akun.php");
            exit;
        }
    }

    if (isset($_POST['change_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (strlen($new_password) < 6) {
            $errors[] = "Password baru minimal harus 6 karakter.";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "Konfirmasi password tidak cocok.";
        } else {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->bind_param("si", $password_hash, $user_id);
            $stmt->execute();
            $_SESSION['flash_message'] = "Password berhasil diubah!";
            header("Location: akun.php");
            exit;
        }
    }
}

$stmt = $mysqli->prepare("SELECT username, email, full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$page_title = "Edit Profil";
include 'header.php';
?>

<div class="mb-4">
    <a href="javascript:history.back()" class="btn btn-outline-secondary">
        &leftarrow; Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="akun.php" class="list-group-item list-group-item-action">Profil & Riwayat</a>
            <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
        </div>
    </div>

    <div class="col-md-9">
        <h3>Edit Profil</h3>
        <hr>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) echo "<p class='mb-0'>" . e($error) . "</p>"; ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <form action="edit_profil.php" method="post">
                    <div class="row mb-3">
                        <div class="col-sm-3"><label class="form-label">Username</label></div>
                        <div class="col-sm-9"><input type="text" class="form-control" value="<?php echo e($user['username']); ?>" disabled></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><label for="full_name" class="form-label">Nama Lengkap</label></div>
                        <div class="col-sm-9"><input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo e($user['full_name']); ?>"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><label for="email" class="form-label">Email</label></div>
                        <div class="col-sm-9"><input type="email" class="form-control" name="email" id="email" value="<?php echo e($user['email']); ?>"></div>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-accent">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <h3 class="mt-5">Ubah Password</h3>
        <hr>
        <div class="card">
            <div class="card-body">
                <form action="edit_profil.php" method="post">
                    <div class="row mb-3">
                        <div class="col-sm-3"><label for="new_password" class="form-label">Password Baru</label></div>
                        <div class="col-sm-9"><input type="password" class="form-control" name="new_password" id="new_password"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><label for="confirm_password" class="form-label">Konfirmasi Password</label></div>
                        <div class="col-sm-9"><input type="password" class="form-control" name="confirm_password" id="confirm_password"></div>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-accent">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>