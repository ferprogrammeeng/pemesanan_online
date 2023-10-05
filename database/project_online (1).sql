-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 05, 2023 at 05:52 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail_pesanan` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `id_menu_makanan` int DEFAULT NULL,
  `id_menu_minuman` int DEFAULT NULL,
  `jumlah` int NOT NULL,
  `total_harga` decimal(10,3) DEFAULT NULL,
  `status_pesanan` enum('belum konfirmasi','sudah konfirmasi') NOT NULL DEFAULT 'belum konfirmasi',
  `gambar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_makanan`
--

CREATE TABLE `menu_makanan` (
  `id_menu_makanan` int NOT NULL,
  `makanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `harga` decimal(10,3) NOT NULL,
  `sisa_stok` varchar(100) NOT NULL,
  `jenis_menu` varchar(50) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu_makanan`
--

INSERT INTO `menu_makanan` (`id_menu_makanan`, `makanan`, `harga`, `sisa_stok`, `jenis_menu`, `gambar`) VALUES
(1, 'Ayam Bakar', '11.000', '60', NULL, 'ayambakar.jpg'),
(2, 'Bakso Ayam', '18.000', '20', NULL, 'baksoayam.png'),
(6, 'Nasi Goreng', '10.000', '30', 'makanan', 'nasgor.jpg'),
(8, 'Indommie Rebus', '10.000', '20', NULL, 'indomierebus.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `menu_minuman`
--

CREATE TABLE `menu_minuman` (
  `id_menu_minuman` int NOT NULL,
  `minuman` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `harga` decimal(10,3) NOT NULL,
  `sisa_stok` varchar(100) NOT NULL,
  `jenis_menu` varchar(50) DEFAULT NULL,
  `gambar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu_minuman`
--

INSERT INTO `menu_minuman` (`id_menu_minuman`, `minuman`, `harga`, `sisa_stok`, `jenis_menu`, `gambar`) VALUES
(2, 'Jus Alpukat', '15.000', '90', 'minuman', 'jusalpukat.jpg'),
(3, 'Jus Jeruk', '10.000', 'habis', 'minuman', 'jusjeruk.jpg'),
(4, 'Teh Es', '7.000', '99', 'minuman', 'tehes.jpg'),
(5, 'Capuccino Cincau', '8.000', '15', 'minuman', 'capucinocincau.jpg'),
(6, 'Teh Obeng Tok Aba', '10.000', '20', 'minuman', 'tehobeng.jpg'),
(10, 'Jus Tomat', '10.000', '11', NULL, 'jus tomat.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `alamat`) VALUES
(1, 'budi', 'jakarta'),
(2, 'sahrul', 'pekanbaru');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int NOT NULL,
  `id_pelanggan` int NOT NULL,
  `waktu_pemesanan` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total_biaya` decimal(10,2) NOT NULL,
  `pembayaran` enum('dana','ovo','gopay','tunai') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `meja` int DEFAULT NULL,
  `id_menu_makanan` int DEFAULT NULL,
  `id_menu_minuman` int DEFAULT NULL,
  `status_pesanan` enum('belum konfirmasi','sudah konfirmasi') NOT NULL DEFAULT 'belum konfirmasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrasi`
--

CREATE TABLE `registrasi` (
  `id_pelanggan` int NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `asal` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `registrasi`
--

INSERT INTO `registrasi` (`id_pelanggan`, `nama_lengkap`, `asal`, `email`, `username`, `kata_sandi`) VALUES
(11, 'nank hudi', 'pekanbaru, kubang', 'nankhudi@gmail.com', 'user', '$2y$10$tTP6nAtCkclHNsxyKSBsuuq.odb/kPGBIkzlVs1FePNUW6/Wl.z7.'),
(12, 'zulfikar', 'Jl. Simpang Perawang - Minas', 'zulfikar@gmail.com', 'user2', '$2y$10$HyZ31x4QX97dZ.XoN5k6B.Cbz8y0UC5zYYJPq/jz2OHcoP1oYJp/S');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail_pesanan`),
  ADD KEY `id_menu_makanan` (`id_menu_makanan`),
  ADD KEY `id_menu_minuman` (`id_menu_minuman`),
  ADD KEY `detail_pesanan_ibfk_1` (`id_pesanan`);

--
-- Indexes for table `menu_makanan`
--
ALTER TABLE `menu_makanan`
  ADD PRIMARY KEY (`id_menu_makanan`);

--
-- Indexes for table `menu_minuman`
--
ALTER TABLE `menu_minuman`
  ADD PRIMARY KEY (`id_menu_minuman`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `fk_pesanan_menu_makanan` (`id_menu_makanan`),
  ADD KEY `fk_pesanan_menu_minuman` (`id_menu_minuman`);

--
-- Indexes for table `registrasi`
--
ALTER TABLE `registrasi`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `menu_makanan`
--
ALTER TABLE `menu_makanan`
  MODIFY `id_menu_makanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_minuman`
--
ALTER TABLE `menu_minuman`
  MODIFY `id_menu_minuman` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `registrasi`
--
ALTER TABLE `registrasi`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_menu_makanan`) REFERENCES `menu_makanan` (`id_menu_makanan`),
  ADD CONSTRAINT `detail_pesanan_ibfk_3` FOREIGN KEY (`id_menu_minuman`) REFERENCES `menu_minuman` (`id_menu_minuman`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pesanan_menu_makanan` FOREIGN KEY (`id_menu_makanan`) REFERENCES `menu_makanan` (`id_menu_makanan`),
  ADD CONSTRAINT `fk_pesanan_menu_minuman` FOREIGN KEY (`id_menu_minuman`) REFERENCES `menu_minuman` (`id_menu_minuman`),
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
