<?php
session_start();
require_once 'config/connect.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
  header('Location: sign_in.php');
  exit;
}

$id_user = $_SESSION['id_user'];
$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;
$jumlah = isset($_GET['jumlah']) ? intval($_GET['jumlah']) : 1;

// Validasi ID dan jumlah
if ($id_produk <= 0 || $jumlah <= 0) {
  header('Location: product.php?pesan=produk_invalid');
  exit;
}

// Cek stok produk
$stmt = $conn->prepare("SELECT stok FROM produk WHERE id_produk = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$stmt->bind_result($stok);
if (!$stmt->fetch()) {
  $stmt->close();
  header('Location: product.php?pesan=produk_invalid');
  exit;
}
$stmt->close();

// Cek jika melebihi stok
if ($jumlah > $stok) {
  header('Location: product.php?pesan=melebihi_stok');
  exit;
}

// Cek apakah produk sudah ada di keranjang user
$cek = $conn->prepare("SELECT id_keranjang FROM keranjang WHERE id_user = ? AND id_produk = ?");
$cek->bind_param("ii", $id_user, $id_produk);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
  $cek->close();
  header('Location: product.php?pesan=produk_ada');
  exit;
}
$cek->close();

// Tambahkan ke keranjang
$insert = $conn->prepare("INSERT INTO keranjang (id_user, id_produk, jumlah) VALUES (?, ?, ?)");
$insert->bind_param("iii", $id_user, $id_produk, $jumlah);

if ($insert->execute()) {
  header('Location: product.php?pesan=sukses_keranjang');
} else {
  header('Location: product.php?pesan=gagal_keranjang');
}

$insert->close();
$conn->close();
?>