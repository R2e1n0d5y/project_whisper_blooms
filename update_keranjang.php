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

  $nama_penerima = $item['nama_penerima']; // array
  $ucapan = $item['ucapan'];               // array
  $keterangan = $item['keterangan'];       // array

  $tema_custom = $item['tema_custom'] ?? []; // array [i] => id_tema
  $warna_custom = $item['warna_custom'] ?? []; // array[i] => array[id_warna]
  $desain_custom = $item['desain_custom'] ?? []; // array[i] => array[id_desain]

  // Hapus data lama
  mysqli_query($conn, "DELETE FROM detail_keranjang WHERE id_keranjang = $id_keranjang");

  for ($i = 0; $i < $jumlah; $i++) {
    $nama = mysqli_real_escape_string($conn, $nama_penerima[$i] ?? '');
    $ucap = mysqli_real_escape_string($conn, $ucapan[$i] ?? '');
    $ket  = mysqli_real_escape_string($conn, $keterangan[$i] ?? '');

    // Ambil id_tema
    $id_tema = isset($tema_custom[$i]) ? intval($tema_custom[$i]) : null;

    if ($id_produk == 5) {
      // Custom product: proses multi warna & desain
      $warna_arr = $warna_custom[$i] ?? [];
      $desain_arr = $desain_custom[$i] ?? [];

      // Loop desain dan warna maksimal 4 kombinasi
      $loop_count = max(count($warna_arr), count($desain_arr), 1);
      $loop_count = min($loop_count, 4); // maksimal 4

      for ($j = 0; $j < $loop_count; $j++) {
        $id_warna = isset($warna_arr[$j]) ? intval($warna_arr[$j]) : null;
        $id_desain = isset($desain_arr[$j]) ? intval($desain_arr[$j]) : null;

        $stmt = $conn->prepare("INSERT INTO detail_keranjang 
          (id_keranjang, nama_penerima, ucapan, keterangan, id_warna, id_desain, id_tema) 
          VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssiii", $id_keranjang, $nama, $ucap, $ket, $id_warna, $id_desain, $id_tema);
        $stmt->execute();
        $stmt->close();
      }
    } else {
      // Produk non-custom: hanya 1 tema, tidak perlu warna/desain
      $stmt = $conn->prepare("INSERT INTO detail_keranjang 
        (id_keranjang, nama_penerima, ucapan, keterangan, id_tema) 
        VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("isssi", $id_keranjang, $nama, $ucap, $ket, $id_tema);
      $stmt->execute();
      $stmt->close();
    }
  }
}

header("Location: checkout.php");
exit;
?>
