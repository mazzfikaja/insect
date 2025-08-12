<?php
require_once 'koneksi.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$product_id = $_GET['id'];

$sql = "DELETE FROM products WHERE id = ?";

if ($stmt = $mysqli->prepare($sql)) {

    $stmt->bind_param("i", $product_id);

    $stmt->execute();
    $stmt->close();
}

header("Location: admin_dashboard.php");
exit;
?>