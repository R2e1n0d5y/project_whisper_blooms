-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 06, 2025 at 10:17 AM
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

--
-- Dumping data for table `alamat`
--

INSERT INTO `alamat` (`id_alamat`, `id_user`, `jalan`, `rt`, `rw`, `desa`, `kecamatan`, `kota`, `provinsi`, `created_at`) VALUES
(1, 1, 'Jl. Mawar No.1', '001', '002', 'Desa A', 'Kecamatan A', 'Bandung', 'Jawa Barat', '2025-07-06 02:27:06'),
(2, 2, 'Jl. Melati No.2', '003', '004', 'Desa B', 'Kecamatan B', 'Yogyakarta', 'DI Yogyakarta', '2025-07-06 02:27:06'),
(3, 3, 'Jl. Kenanga No.3', '005', '006', 'Desa C', 'Kecamatan C', 'Semarang', 'Jawa Tengah', '2025-07-06 02:27:06'),
(4, 4, 'Jl. Anggrek No.4', '007', '008', 'Desa D', 'Kecamatan D', 'Surabaya', 'Jawa Timur', '2025-07-06 02:27:06'),
(5, 5, 'Jl. Kamboja No.5', '009', '010', 'Desa E', 'Kecamatan E', 'Jakarta Selatan', 'DKI Jakarta', '2025-07-06 02:27:06'),
(6, 6, 'Jl. Bougenville No.6', '011', '012', 'Desa F', 'Kecamatan F', 'Bekasi', 'Jawa Barat', '2025-07-06 02:27:06'),
(7, 7, 'Jl. Dahlia No.7', '013', '014', 'Desa G', 'Kecamatan G', 'Bogor', 'Jawa Barat', '2025-07-06 02:27:06'),
(8, 8, 'Jl. Cempaka No.8', '015', '016', 'Desa H', 'Kecamatan H', 'Depok', 'Jawa Barat', '2025-07-06 02:27:06'),
(9, 9, 'Jl. Teratai No.9', '017', '018', 'Desa I', 'Kecamatan I', 'Tangerang', 'Banten', '2025-07-06 02:27:06'),
(10, 10, 'Jl. Sakura No.10', '019', '020', 'Desa J', 'Kecamatan J', 'Cilegon', 'Banten', '2025-07-06 02:27:06'),
(11, 11, 'Jl. Mawar No.11', '021', '022', 'Desa K', 'Kecamatan K', 'Bandung', 'Jawa Barat', '2025-07-06 02:27:06'),
(12, 12, 'Jl. Melati No.12', '023', '024', 'Desa L', 'Kecamatan L', 'Yogyakarta', 'DI Yogyakarta', '2025-07-06 02:27:06'),
(13, 13, 'Jl. Kenanga No.13', '025', '026', 'Desa M', 'Kecamatan M', 'Semarang', 'Jawa Tengah', '2025-07-06 02:27:06'),
(14, 14, 'Jl. Anggrek No.14', '027', '028', 'Desa N', 'Kecamatan N', 'Surabaya', 'Jawa Timur', '2025-07-06 02:27:06'),
(15, 15, 'Jl. Kamboja No.15', '029', '030', 'Desa O', 'Kecamatan O', 'Jakarta Selatan', 'DKI Jakarta', '2025-07-06 02:27:06'),
(16, 16, 'Jl. Bougenville No.16', '031', '032', 'Desa P', 'Kecamatan P', 'Bekasi', 'Jawa Barat', '2025-07-06 02:27:06'),
(17, 17, 'Jl. Dahlia No.17', '033', '034', 'Desa Q', 'Kecamatan Q', 'Bogor', 'Jawa Barat', '2025-07-06 02:27:06'),
(18, 18, 'Jl. Cempaka No.18', '035', '036', 'Desa R', 'Kecamatan R', 'Depok', 'Jawa Barat', '2025-07-06 02:27:06'),
(19, 19, 'Jl. Teratai No.19', '037', '038', 'Desa S', 'Kecamatan S', 'Tangerang', 'Banten', '2025-07-06 02:27:06'),
(20, 20, 'Jl. Sakura No.20', '039', '040', 'Desa T', 'Kecamatan T', 'Cilegon', 'Banten', '2025-07-06 02:27:06');

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
(1, 'Viola', 'd1.png'),
(2, 'Gerbera', 'd2.png'),
(3, 'Lilium', 'd3.png'),
(4, 'Azura', 'd4.png'),
(5, 'Camelia', 'd5.png');

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

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_user`, `tanggal_pesanan`, `status`, `total_harga`, `alamat_kirim`) VALUES
(1, 1, '2025-07-06 02:27:47', 'completed', 60000, 'Jl. Mawar No.1'),
(2, 2, '2025-07-06 02:27:47', 'processing', 30000, 'Jl. Melati No.2'),
(3, 3, '2025-07-06 02:27:47', 'cancelled', 90000, 'Jl. Kenanga No.3'),
(4, 4, '2025-07-06 02:27:47', 'pending', 30000, 'Jl. Anggrek No.4'),
(5, 5, '2025-07-06 02:27:47', 'completed', 60000, 'Jl. Kamboja No.5'),
(6, 6, '2025-07-06 02:27:47', 'processing', 30000, 'Jl. Bougenville No.6'),
(7, 7, '2025-07-06 02:27:47', 'completed', 60000, 'Jl. Dahlia No.7'),
(8, 8, '2025-07-06 02:27:47', 'completed', 30000, 'Jl. Cempaka No.8'),
(9, 9, '2025-07-06 02:27:47', 'pending', 60000, 'Jl. Teratai No.9'),
(10, 10, '2025-07-06 02:27:47', 'completed', 90000, 'Jl. Sakura No.10'),
(11, 11, '2025-07-06 02:27:47', 'completed', 30000, 'Jl. Mawar No.11'),
(12, 12, '2025-07-06 02:27:47', 'completed', 30000, 'Jl. Melati No.12'),
(13, 13, '2025-07-06 02:27:47', 'cancelled', 60000, 'Jl. Kenanga No.13'),
(14, 14, '2025-07-06 02:27:47', 'completed', 60000, 'Jl. Anggrek No.14'),
(15, 15, '2025-07-06 02:27:47', 'completed', 30000, 'Jl. Kamboja No.15'),
(16, 16, '2025-07-06 02:27:47', 'completed', 60000, 'Jl. Bougenville No.16'),
(17, 17, '2025-07-06 02:27:47', 'completed', 90000, 'Jl. Dahlia No.17'),
(18, 18, '2025-07-06 02:27:47', 'completed', 60000, 'Jl. Cempaka No.18'),
(19, 19, '2025-07-06 02:27:47', 'completed', 30000, 'Jl. Teratai No.19'),
(20, 20, '2025-07-06 02:27:47', 'completed', 30000, 'Jl. Sakura No.20');

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

--
-- Dumping data for table `pesanan_detail`
--

INSERT INTO `pesanan_detail` (`id_detail`, `id_pesanan`, `id_produk`, `jumlah`, `harga_satuan`, `total_harga`, `id_desain`, `id_warna`, `id_tema`, `nama_penerima`, `ucapan`, `keterangan`) VALUES
(1, 1, 1, 2, 30000, 60000, 1, 1, 1, 'Ibu', 'Selamat Hari Ibu', ''),
(2, 2, 2, 1, 30000, 30000, 2, 2, 2, 'Teman', 'Semangat terus!', ''),
(3, 3, 3, 3, 30000, 90000, 3, 3, 3, 'Ayah', 'Happy Birthday', ''),
(4, 4, 4, 1, 30000, 30000, 4, 4, 4, 'Pacar', 'I love you', ''),
(5, 5, 5, 2, 30000, 60000, 5, 5, 1, 'Guru', 'Terima kasih banyak', ''),
(6, 6, 1, 1, 30000, 30000, 1, 6, 2, 'Adik', 'Sukses ya', ''),
(7, 7, 2, 2, 30000, 60000, 2, 7, 3, 'Kakak', 'Selamat wisuda!', ''),
(8, 8, 3, 1, 30000, 30000, 3, 1, 4, 'Sahabat', 'Good luck!', ''),
(9, 9, 4, 2, 30000, 60000, 4, 2, 1, 'Pasangan', 'Kangen kamu', ''),
(10, 10, 5, 3, 30000, 90000, 5, 3, 2, 'Bos', 'Selamat atas promosi', ''),
(11, 11, 1, 1, 30000, 30000, 1, 4, 3, 'Istri', 'Terima kasih selalu', ''),
(12, 12, 2, 1, 30000, 30000, 2, 5, 4, 'Suami', 'Kamu luar biasa', ''),
(13, 13, 3, 2, 30000, 60000, 3, 6, 1, 'Teman SMA', 'Semangat!', ''),
(14, 14, 4, 2, 30000, 60000, 4, 7, 2, 'Rekan', 'Selamat Ulang Tahun', ''),
(15, 15, 5, 1, 30000, 30000, 5, 1, 3, 'Nenek', 'Sehat selalu', ''),
(16, 16, 1, 2, 30000, 60000, 1, 2, 4, 'Kakek', 'Doa terbaik untukmu', ''),
(17, 17, 2, 3, 30000, 90000, 2, 3, 1, 'Tetangga', 'Selamat!', ''),
(18, 18, 3, 2, 30000, 60000, 3, 4, 2, 'Sahabat SD', 'Jangan lupakan aku', ''),
(19, 19, 4, 1, 30000, 30000, 4, 5, 3, 'Mantan', 'Semoga bahagia', ''),
(20, 20, 5, 1, 30000, 30000, 5, 6, 4, 'Atasan', 'Sukses selalu!', '');

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
(1, 'Azura Biru', 'Kartu ucapan handmade bunga biru dengan pesan “Happy Graduation”.', 25000, 55000, '2.png', 4, 50),
(2, 'Azura Pink', 'Kartu ucapan handmade dengan nuansa pink romantis.', 25000, 55000, '3.png', 3, 50),
(3, 'Azura Merah', 'Kartu ucapan handmade dengan hiasan bunga lavender.', 25000, 55000, '4.png', 4, 50),
(4, 'Azura Kuning', 'Kartu ucapan handmade dengan detail bunga penuh warna.', 25000, 55000, '5.png', 5, 50),
(5, 'Paket Bunga Custom', 'Kartu ucapan custom sesuai permintaan tema dan warna.', 30000, 55000, 'header.jpg', 3, 50),
(6, 'Azura Ungu', 'Kartu ucapan handmade dengan nuansa ungu romantis.', 25000, 55000, '6.png', 5, 45),
(7, 'Azura Orange', 'Kartu ucapan handmade dengan hiasan bunga orange.', 25000, 55000, '7.png', 4, 40);

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

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `email`, `username`, `password`, `created_at`, `foto_profil`) VALUES
(1, 'Andi Wijaya', 'andi.wijaya@email.com', 'andiwijaya', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(2, 'Siti Aminah', 'siti.aminah@email.com', 'sitiaminah', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(3, 'Budi Santoso', 'budi.santoso@email.com', 'budisantoso', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(4, 'Lisa Permata', 'lisa.permata@email.com', 'lisapermata', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(5, 'Dewi Sartika', 'dewi.sartika@email.com', 'dewisartika', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(6, 'Rudi Gunawan', 'rudi.gunawan@email.com', 'rudigunawan', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(7, 'Nina Larasati', 'nina.larasati@email.com', 'ninalarasati', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(8, 'Dian Prasetyo', 'dian.prasetyo@email.com', 'dianprasetyo', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(9, 'Rina Oktaviani', 'rina.oktaviani@email.com', 'rinaoktaviani', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(10, 'Tono Suharto', 'tono.suharto@email.com', 'tonosuharto', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(11, 'Mira Andayani', 'mira.andayani@email.com', 'miraandayani', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(12, 'Danu Purnomo', 'danu.purnomo@email.com', 'danupurnomo', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(13, 'Selvi Ramadhani', 'selvi.ramadhani@email.com', 'selviramadhani', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(14, 'Imam Mulyana', 'imam.mulyana@email.com', 'imammulyana', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(15, 'Lala Sari', 'lala.sari@email.com', 'lalasari', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(16, 'Rio Nugroho', 'rio.nugroho@email.com', 'rionugroho', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(17, 'Tari Wulandari', 'tari.wulandari@email.com', 'tariwulandari', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(18, 'Yoga Firmansyah', 'yoga.firmansyah@email.com', 'yogafirmansyah', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(19, 'Putri Ayu', 'putri.ayu@email.com', 'putriayu', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(20, 'Ari Setiawan', 'ari.setiawan@email.com', 'arisetiawan', '123456', '2025-07-06 02:27:01', 'default.jpg'),
(21, 'tes123', 'tes123@gmail.com', 'tes123', '$2y$10$0363AtqpxinOUe/MNIzmweLRKO92uT8Erdzid85X2fjiG4Js1Io5u', '2025-07-06 02:48:43', 'default.jpg');

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
  MODIFY `id_alamat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `desain_custom`
--
ALTER TABLE `desain_custom`
  MODIFY `id_desain` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tema_custom`
--
ALTER TABLE `tema_custom`
  MODIFY `id_tema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
