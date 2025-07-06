<?php
session_start();
require_once 'config/connect.php';

if (!isset($_SESSION['id_user']) || !isset($_POST['items'])) {
  header("Location: keranjang_user.php");
  exit;
}

foreach ($_POST['items'] as $id_keranjang => $item) {
  $id_produk = intval($item['id_produk']);
  $jumlah = intval($item['jumlah']);

  $nama_penerima = $item['nama_penerima'];
  $ucapan = $item['ucapan'];
  $keterangan = $item['keterangan'];
  $warna_custom = $item['warna_custom'] ?? [];
  $tema_custom = $item['tema_custom'] ?? [];

  // Bersihkan data lama
  mysqli_query($conn, "DELETE FROM detail_keranjang WHERE id_keranjang = $id_keranjang");

  for ($i = 0; $i < $jumlah; $i++) {
    $nama = mysqli_real_escape_string($conn, $nama_penerima[$i]);
    $ucap = mysqli_real_escape_string($conn, $ucapan[$i]);
    $ket = mysqli_real_escape_string($conn, $keterangan[$i] ?? '');
    $warna = ($id_produk == 5) ? mysqli_real_escape_string($conn, $warna_custom[$i] ?? '') : null;
    $tema = ($id_produk == 5) ? mysqli_real_escape_string($conn, $tema_custom[$i] ?? '') : null;

    $stmt = $conn->prepare("INSERT INTO detail_keranjang (id_keranjang, nama_penerima, ucapan, keterangan, warna_custom, tema_custom) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $id_keranjang, $nama, $ucap, $ket, $warna, $tema);
    $stmt->execute();
    $stmt->close();
  }
}

header("Location: checkout.php");
exit;
?>
