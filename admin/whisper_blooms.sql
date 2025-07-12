-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 06, 2025 at 02:33 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `whisper_blooms`
--

-- --------------------------------------------------------

--
-- Table structure for table `alamat`
--

CREATE TABLE `alamat` (
  `id_alamat` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jalan` varchar(255) DEFAULT NULL,
  `rt` varchar(10) DEFAULT NULL,
  `rw` varchar(10) DEFAULT NULL,
  `desa` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `desain_custom`
--

CREATE TABLE `desain_custom` (
  `id_desain` int(11) NOT NULL,
  `nama_desain` varchar(100) DEFAULT NULL,
  `gambar_desain` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `desain_custom`
--

INSERT INTO `desain_custom` (`id_desain`, `nama_desain`, `gambar_desain`) VALUES
(1, 'Desain Custom 1', 'asset/custom/1.png'),
(2, 'Desain Custom 2', 'asset/custom/2.png'),
(3, 'Desain Custom 3', 'asset/custom/3.png'),
(4, 'Desain Custom 4', 'asset/custom/4.png'),
(5, 'Desain Custom 5', 'asset/custom/5.png');

-- --------------------------------------------------------

--
-- Table structure for table `detail_keranjang`
--

CREATE TABLE `detail_keranjang` (
  `id_detail_keranjang` int(11) NOT NULL,
  `id_keranjang` int(11) DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `ucapan` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `id_desain` int(11) DEFAULT NULL,
  `id_warna` int(11) DEFAULT NULL,
  `id_tema` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT 1,
  `tanggal_ditambahkan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_pesanan` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pending',
  `total_harga` int(11) DEFAULT NULL,
  `alamat_kirim` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_detail`
--

CREATE TABLE `pesanan_detail` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga_satuan` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `id_desain` int(11) DEFAULT NULL,
  `id_warna` int(11) DEFAULT NULL,
  `id_tema` int(11) DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `ucapan` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` int(11) NOT NULL,
  `harga_diskon` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT 0,
  `stok` int(11) NOT NULL DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `deskripsi`, `harga`, `harga_diskon`, `gambar`, `rating`, `stok`) VALUES
(1, 'Paket Bunga 1', 'Kartu ucapan handmade bunga biru dengan pesan “Happy Graduation”.', 30000, 55000, 'asset/menu/2.png', 4, 50),
(2, 'Paket Bunga 2', 'Kartu ucapan handmade dengan nuansa pink romantis.', 30000, 55000, 'asset/menu/3.png', 3, 50),
(3, 'Paket Bunga 3', 'Kartu ucapan handmade dengan hiasan bunga lavender.', 30000, 55000, 'asset/menu/4.png', 4, 50),
(4, 'Paket Bunga 4', 'Kartu ucapan handmade dengan detail bunga penuh warna.', 30000, 55000, 'asset/menu/5.png', 5, 50),
(5, 'Paket Bunga Custom', 'Kartu ucapan custom sesuai permintaan tema dan warna.', 30000, 55000, 'asset/header.jpg', 3, 50);

-- --------------------------------------------------------

--
-- Table structure for table `tema_custom`
--

CREATE TABLE `tema_custom` (
  `id_tema` int(11) NOT NULL,
  `nama_tema` varchar(100) DEFAULT NULL,
  `gambar_tema` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tema_custom`
--

INSERT INTO `tema_custom` (`id_tema`, `nama_tema`, `gambar_tema`) VALUES
(1, 'Wisuda', 'asset/tema/romantis.png'),
(2, 'Ulang Tahun', 'asset/tema/minimalis.png'),
(3, 'Wedding', 'asset/tema/vintage.png'),
(4, 'Akademik', 'asset/tema/tropical.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_profil` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warna_custom`
--

CREATE TABLE `warna_custom` (
  `id_warna` int(11) NOT NULL,
  `nama_warna` varchar(100) DEFAULT NULL,
  `gambar_warna` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warna_custom`
--

INSERT INTO `warna_custom` (`id_warna`, `nama_warna`, `gambar_warna`) VALUES
(1, 'Biru', 'asset/warna/biru.png'),
(2, 'Merah', 'asset/warna/merah.png'),
(3, 'Pink', 'asset/warna/pink.png'),
(4, 'Oren', 'asset/warna/oren.png'),
(5, 'Kuning', 'asset/warna/kuning.png'),
(6, 'Ungu', 'asset/warna/ungu.png'),
(7, 'Hijau', 'asset/warna/hijau.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alamat`
--
ALTER TABLE `alamat`
  ADD PRIMARY KEY (`id_alamat`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `desain_custom`
--
ALTER TABLE `desain_custom`
  ADD PRIMARY KEY (`id_desain`);

--
-- Indexes for table `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  ADD PRIMARY KEY (`id_detail_keranjang`),
  ADD KEY `id_keranjang` (`id_keranjang`),
  ADD KEY `id_desain` (`id_desain`),
  ADD KEY `id_warna` (`id_warna`),
  ADD KEY `id_tema` (`id_tema`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_desain` (`id_desain`),
  ADD KEY `id_warna` (`id_warna`),
  ADD KEY `id_tema` (`id_tema`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `tema_custom`
--
ALTER TABLE `tema_custom`
  ADD PRIMARY KEY (`id_tema`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `warna_custom`
--
ALTER TABLE `warna_custom`
  ADD PRIMARY KEY (`id_warna`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alamat`
--
ALTER TABLE `alamat`
  MODIFY `id_alamat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `desain_custom`
--
ALTER TABLE `desain_custom`
  MODIFY `id_desain` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  MODIFY `id_detail_keranjang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tema_custom`
--
ALTER TABLE `tema_custom`
  MODIFY `id_tema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warna_custom`
--
ALTER TABLE `warna_custom`
  MODIFY `id_warna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alamat`
--
ALTER TABLE `alamat`
  ADD CONSTRAINT `alamat_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  ADD CONSTRAINT `detail_keranjang_ibfk_1` FOREIGN KEY (`id_keranjang`) REFERENCES `keranjang` (`id_keranjang`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_keranjang_ibfk_2` FOREIGN KEY (`id_desain`) REFERENCES `desain_custom` (`id_desain`),
  ADD CONSTRAINT `detail_keranjang_ibfk_3` FOREIGN KEY (`id_warna`) REFERENCES `warna_custom` (`id_warna`),
  ADD CONSTRAINT `detail_keranjang_ibfk_4` FOREIGN KEY (`id_tema`) REFERENCES `tema_custom` (`id_tema`);

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  ADD CONSTRAINT `pesanan_detail_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_detail_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `pesanan_detail_ibfk_3` FOREIGN KEY (`id_desain`) REFERENCES `desain_custom` (`id_desain`),
  ADD CONSTRAINT `pesanan_detail_ibfk_4` FOREIGN KEY (`id_warna`) REFERENCES `warna_custom` (`id_warna`),
  ADD CONSTRAINT `pesanan_detail_ibfk_5` FOREIGN KEY (`id_tema`) REFERENCES `tema_custom` (`id_tema`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
