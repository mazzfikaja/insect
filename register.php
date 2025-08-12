<?php
require_once 'koneksi.php';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);

    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $errors[] = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password minimal harus 6 karakter.";
    } else {
        $sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
        if ($stmt_check = $mysqli->prepare($sql_check)) {
            $stmt_check->bind_param("ss", $username, $email);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $errors[] = "Username atau Email sudah terdaftar.";
            }
            $stmt_check->close();
        }

        if (empty($errors)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO users (username, email, password_hash, full_name) VALUES (?, ?, ?, ?)";
            if ($stmt_insert = $mysqli->prepare($sql_insert)) {
                $stmt_insert->bind_param("ssss", $username, $email, $password_hash, $full_name);
                if ($stmt_insert->execute()) {
                    $_SESSION['flash_message'] = "Registrasi berhasil! Silakan login.";
                    header("Location: login.php");
                    exit;
                } else {
                    $errors[] = "Terjadi kesalahan. Silakan coba lagi.";
                }
                $stmt_insert->close();
            }
        }
    }
}

$page_title = "Register";
include 'header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3>Registrasi Akun Baru</h3></div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error) echo "<p class='mb-0'>" . e($error) . "</p>"; ?>
                    </div>
                <?php endif; ?>
                <form action="register.php" method="post">
                    <div class="mb-3"><label for="full_name" class="form-label">Nama Lengkap</label><input type="text" name="full_name" id="full_name" class="form-control" required></div>
                    <div class="mb-3"><label for="username" class="form-label">Username</label><input type="text" name="username" id="username" class="form-control" required></div>
                    <div class="mb-3"><label for="email" class="form-label">Email</label><input type="email" name="email" id="email" class="form-control" required></div>
                    <div class="mb-3"><label for="password" class="form-label">Password</label><input type="password" name="password" id="password" class="form-control" required></div>
                    <button type="submit" class="btn btn-accent w-100">Register</button>
                </form>
            </div>
            <div class="card-footer text-center">Sudah punya akun? <a href="login.php">Login di sini</a></div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>