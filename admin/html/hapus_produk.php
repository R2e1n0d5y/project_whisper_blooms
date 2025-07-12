<?php
include '../config/connect.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk = $id"));
$gambar = $data['gambar'];

if ($gambar && file_exists("../assets/images/produk/" . $gambar)) {
    unlink("../assets/images/produk/" . $gambar);
}

mysqli_query($conn, "DELETE FROM produk WHERE id_produk = $id");
header("Location: kelola_produk.php");
?>
