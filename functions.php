<?php
require_once 'koneksi.php';

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

function getAllProducts($mysqli, $searchTerm = '') { 
    $products = [];

    $sql = "SELECT id, brand, model, price, main_image, grade FROM products WHERE status = 'Tersedia'";

    if (!empty($searchTerm)) {
        $sql .= " AND (brand LIKE ? OR model LIKE ?)";
    }

    $sql .= " ORDER BY created_at DESC";

    if ($stmt = $mysqli->prepare($sql)) {
        if (!empty($searchTerm)) {
            $wildcardSearch = "%" . $searchTerm . "%";
            $stmt->bind_param("ss", $wildcardSearch, $wildcardSearch);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()){
            $products[] = $row;
        }
        $stmt->close();
    }
    return $products;
}

function getProductDetailsById($mysqli, $id) {
    $product = null;
    $sql = "SELECT p.*, d.* FROM products p 
            LEFT JOIN product_details d ON p.id = d.product_id
            WHERE p.id = ? AND p.status = 'Tersedia'";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $product = $result->fetch_assoc();
        }
        $stmt->close();
    }
    return $product;
}

function getGalleryImages($mysqli, $product_id) {
    $images = [];
    $sql = "SELECT filename FROM product_images WHERE product_id = ?";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $images[] = $row['filename'];
        }
        $stmt->close();
    }
    return $images;
}

function getImageById($mysqli, $image_id) {
    $image = null;
    $sql = "SELECT * FROM product_images WHERE id = ?";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $image = $result->fetch_assoc();
        }
        $stmt->close();
    }
    return $image;
}

function getAdminProductDetailsById($mysqli, $id) {
    $product = null;
    $sql = "SELECT p.*, d.* FROM products p 
            LEFT JOIN product_details d ON p.id = d.product_id
            WHERE p.id = ?";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $product = $result->fetch_assoc();
        }
        $stmt->close();
    }
    return $product;
}

function getActiveAnnouncements($mysqli, $limit = 5) {
    $announcements = [];
    $sql = "SELECT * FROM announcements WHERE is_active = 1 ORDER BY created_at DESC LIMIT ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
    return $announcements;
}

function getAllActiveAnnouncements($mysqli) {
    $announcements = [];
    $sql = "SELECT * FROM announcements WHERE is_active = 1 ORDER BY created_at DESC";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
    return $announcements;
}