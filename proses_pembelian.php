<?php
require_once 'koneksi.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?pesan=harus_login");
    exit;
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: marketplace.php"); 
    exit;
}

$product_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$stmt_check = $mysqli->prepare("SELECT price, status FROM products WHERE id = ?");
$stmt_check->bind_param("i", $product_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
$product = $result->fetch_assoc();
$stmt_check->close();
if (!$product || $product['status'] !== 'Tersedia') {
    $_SESSION['flash_message'] = "Maaf, produk ini sudah tidak tersedia atau sudah terjual.";
    header("Location: produk.php?id=" . $product_id); 
    exit;
}

$stmt_update = $mysqli->prepare("UPDATE products SET status = 'Terjual' WHERE id = ?");
$stmt_update->bind_param("i", $product_id);
$stmt_update->execute();
$stmt_update->close();
$purchase_price = $product['price'];
$stmt_trans = $mysqli->prepare("INSERT INTO transactions (user_id, product_id, purchase_price) VALUES (?, ?, ?)");
$stmt_trans->bind_param("iid", $user_id, $product_id, $purchase_price);
$stmt_trans->execute();
$stmt_trans->close();
$_SESSION['flash_message'] = "Pembelian berhasil! Terima kasih telah berbelanja.";
header("Location: riwayat_transaksi.php");
exit;

?>