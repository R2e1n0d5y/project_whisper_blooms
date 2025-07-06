<?php
include 'config/connect.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id");

  if ($row = mysqli_fetch_assoc($query)) {
    echo json_encode($row);
  } else {
    echo json_encode(['error' => 'Produk tidak ditemukan']);
  }
}
?>
