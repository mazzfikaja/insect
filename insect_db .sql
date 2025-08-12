-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Agu 2025 pada 03.22
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `insect_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `image`, `is_active`, `created_at`) VALUES
(1, 'Stok iPhone 14 Pro Baru Tiba!', 'Koleksi iPhone 14 Pro dengan berbagai grade kondisi sudah tersedia. Cek sekarang di marketplace!', 'default.jpg', 1, '2025-08-09 01:59:49'),
(2, 'Promo Servis Inspeksi Selama Agustus', 'Dapatkan potongan harga 20% untuk semua jasa inspeksi gadget selama bulan Agustus. Hubungi kami via WhatsApp.', 'default.jpg', 1, '2025-08-09 01:59:49'),
(3, 'Selamat Datang di Website Insect!', 'Website baru kami hadir untuk memberikan Anda kepercayaan dalam jual-beli gadget bekas. Jelajahi sekarang!', 'default.jpg', 1, '2025-08-09 01:59:49'),
(4, 'Tips Memilih Laptop Bekas untuk Mahasiswa', 'Jangan salah pilih! Baca panduan singkat kami dalam memilih laptop bekas yang sesuai dengan budget dan kebutuhan kuliah.', 'default.jpg', 1, '2025-08-09 01:59:49'),
(5, 'Kami Juga Menerima Jasa Inspeksi Macbook', 'Punya incaran MacBook bekas? Biarkan tim kami yang melakukan pengecekan menyeluruh untuk Anda.', 'default.jpg', 1, '2025-08-09 01:59:49'),
(6, 'Stok Google Pixel Baru', 'Bagi para pecinta fotografi, stok Google Pixel 7 dan 7a kini tersedia dengan harga spesial.', 'default.jpg', 1, '2025-08-09 01:59:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `grade` enum('Sempurna','Baik','Cukup') NOT NULL,
  `price` decimal(12,0) NOT NULL,
  `status` enum('Tersedia','Terjual') NOT NULL DEFAULT 'Tersedia',
  `description` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `brand`, `model`, `grade`, `price`, `status`, `description`, `main_image`, `created_at`) VALUES
(13, 'Sony', 'Xperia 5 IV 128GB', 'Baik', 9800000, 'Tersedia', 'HP compact dengan fitur pro. Cocok untuk videografer. Bodi mulus, hanya ada lecet halus di port charger.', 'default.jpg', '2025-08-05 19:50:34'),
(14, 'OnePlus', '10T 5G 8/128GB', 'Sempurna', 7500000, 'Tersedia', 'Performa super kencang dengan Snapdragon 8+ Gen 1. Charging 150W super cepat. Kondisi seperti baru.', 'default.jpg', '2025-08-05 19:50:34'),
(15, 'Microsoft', 'Surface Pro 7 i5/8/128', 'Cukup', 8200000, 'Tersedia', 'Tablet Windows serbaguna. Ada goresan di bagian belakang bodi (kickstand). Layar aman. Keyboard include.', 'default.jpg', '2025-08-05 19:50:34'),
(16, 'Realme', 'GT Neo 3 150W 12/256GB', 'Baik', 5900000, 'Tersedia', 'HP gaming dengan charging tercepat. Desain strip balap yang ikonik. Kondisi mulus terawat.', 'default.jpg', '2025-08-05 19:50:34'),
(17, 'Apple', 'iPad Air 4 64GB Wifi', 'Baik', 6800000, 'Tersedia', 'Warna Sky Blue. Ada lecet kecil di salah satu sudut, hampir tidak terlihat. Layar aman, Touch ID di tombol power normal.', 'default.jpg', '2025-08-05 19:50:35'),
(18, 'Asus', 'Zenfone 9 8/128GB', 'Sempurna', 7900000, 'Tersedia', 'HP flagship compact terbaik. Sangat nyaman digenggam. Kondisi seperti baru, jarang dipakai.', 'default.jpg', '2025-08-05 19:50:35'),
(19, 'HP', 'Pavilion Gaming 15 Ryzen 5', 'Cukup', 8500000, 'Tersedia', 'Laptop gaming entry-level. Keyboard backlight hijau. Ada stiker yang dilepas di cover atas, meninggalkan bekas.', 'default.jpg', '2025-08-05 19:50:35'),
(20, 'Oppo', 'Reno 8T 5G 8/128GB', 'Baik', 4200000, 'Tersedia', 'Desain premium dengan layar lengkung. Kamera portrait 108MP. Kondisi mulus, pemakaian wanita.', 'default.jpg', '2025-08-05 19:50:35'),
(21, 'Acer', 'Aspire 5 Slim i5 Gen 11', 'Baik', 6500000, 'Tersedia', 'Laptop kerja yang tipis dan ringan. Cocok untuk mahasiswa dan pekerja kantoran. Keyboard sudah ada backlight.', 'default.jpg', '2025-08-05 19:50:35'),
(22, 'Vivo', 'V27 5G 8/256GB', 'Sempurna', 5100000, 'Tersedia', 'HP dengan Aura Light Portrait, hasil foto malam jadi bagus. Kondisi seperti baru, plastik belakang masih menempel.', 'default.jpg', '2025-08-05 19:50:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_details`
--

CREATE TABLE `product_details` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `battery_health` int(3) DEFAULT NULL,
  `screen_condition` varchar(255) DEFAULT NULL,
  `body_condition` varchar(255) DEFAULT NULL,
  `camera_status` varchar(255) DEFAULT NULL,
  `speaker_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_details`
--

INSERT INTO `product_details` (`id`, `product_id`, `battery_health`, `screen_condition`, `body_condition`, `camera_status`, `speaker_status`) VALUES
(13, 13, 94, 'Layar 21:9 CinemaWide mulus', 'Lecet halus di port charger', 'Kamera Zeiss berfungsi normal', 'Speaker stereo depan normal'),
(14, 14, 98, 'Layar Fluid AMOLED 120Hz mulus', 'Tidak ada lecet sama sekali', 'Kamera utama 50MP Sony IMX766 normal', 'Speaker stereo jernih'),
(15, 15, 89, 'Layar sentuh PixelSense normal, tidak ada dead pixel', 'Goresan di area kickstand belakang', 'Webcam depan dan belakang normal', 'Speaker normal'),
(16, 16, 93, 'Layar AMOLED mulus', 'Bodi belakang mulus', 'Kamera utama dengan OIS normal', 'Dual speaker normal'),
(17, 17, 91, 'Layar Liquid Retina mulus', 'Lecet kecil di satu sudut', 'Kamera depan dan belakang normal', 'Speaker stereo landscape normal'),
(18, 18, 99, 'Layar 120Hz Samsung AMOLED mulus', 'Bodi mulus tanpa cacat', 'Kamera dengan 6-Axis Hybrid Gimbal Stabilizer normal', 'Speaker stereo normal'),
(19, 19, 87, 'Layar 144Hz normal, tidak ada dead pixel', 'Bekas stiker di cover atas', 'Webcam normal', 'Speaker B&O normal'),
(20, 20, 96, 'Layar lengkung mulus', 'Bodi mulus', 'Kamera portrait dan mikrolens normal', 'Speaker stereo normal'),
(21, 21, 92, 'Layar IPS Full HD normal', 'Lecet halus di cover atas', 'Webcam normal', 'Speaker normal'),
(22, 22, 99, 'Layar lengkung mulus', 'Bodi mulus seperti baru', 'Kamera dengan Aura Light berfungsi normal', 'Speaker normal');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_price` decimal(12,0) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `product_id`, `purchase_price`, `purchase_date`) VALUES
(1, 2, 17, 6800000, '2025-08-07 22:24:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`) VALUES
(1, 'admin', '', '$2y$10$5akYOVtOb1YjyhgZRvTRReNttDj2YLQrigDxUCIzI2s8IHSZQksVy', 'Pemilik Toko', 'admin'),
(2, 'tester', 'tester@gmail.com', '$2y$10$ongu9KcAj2qzR6hyan9oDuy13KFog0yR16HybRxfIXlgBb3utghHi', 'tester', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_image_product_id` (`product_id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `product_details`
--
ALTER TABLE `product_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `product_details`
--
ALTER TABLE `product_details`
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_image_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_trans_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_trans_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
