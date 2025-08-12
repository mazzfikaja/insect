<?php
require_once 'koneksi.php';
$errors = [];

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errors[] = "Username dan password wajib diisi.";
    } else {
        $sql = "SELECT id, username, password_hash, role FROM users WHERE username = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $user, $hashed_password, $role);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                           
                            $_SESSION['user_id'] = $id;
                            $_SESSION['username'] = $user;
                            $_SESSION['role'] = $role;
                            
                          
                            if ($role === 'admin') {
                             
                                header("Location: admin_dashboard.php");
                            } else {
                               
                                header("Location: index.php");
                            }
                            exit; 
                          
                        }
                    }
                }
                $errors[] = "Username atau password salah.";
            } else {
                $errors[] = "Terjadi kesalahan, silakan coba lagi.";
            }
            $stmt->close();
        }
    }
}

$page_title = "Login";
include 'header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h3>Login</h3></div>
            <div class="card-body">
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="alert alert-success">
                        <?php
                            echo $_SESSION['flash_message'];
                            unset($_SESSION['flash_message']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error) echo "<p class='mb-0'>" . e($error) . "</p>"; ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="post">
                    <div class="mb-3"><label for="username" class="form-label">Username</label><input type="text" name="username" id="username" class="form-control" required></div>
                    <div class="mb-3"><label for="password" class="form-label">Password</label><input type="password" name="password" id="password" class="form-control" required></div>
                    <button type="submit" class="btn btn-accent w-100">Login</button>
                </form>
            </div>
             <div class="card-footer text-center">Belum punya akun? <a href="register.php">Daftar di sini</a></div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>