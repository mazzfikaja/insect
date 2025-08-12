<?php
require_once 'koneksi.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? e($page_title) . ' - Insect' : 'Insect - Lihat Detailnya, Dapatkan Kualitasnya.'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
    <div class="container">
        <a class="navbar-brand"><strong>INSECT</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="marketplace.php">Marketplace</a></li>
                <li class="nav-item"><a class="nav-link" href="jasa_inspeksi.php">Jasa Inspeksi</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="keranjang.php" title="Keranjang Belanja (Segera Hadir)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.49.402h-9.995a.5.5 0 0 1-.49-.598l-1-5A.5.5 0 0 1 3 1.5H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2.5h8.22l.5-2.5H3.14zM5 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                            </svg>
                        </a>
                    </li>

                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link fw-bold" href="admin_dashboard.php">Admin Panel</a></li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="akun.php" title="Akun Saya">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                </svg>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>

                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a href="register.php" class="btn btn-accent btn-sm ms-2">Daftar</a></li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
<main class="container my-5">