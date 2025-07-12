<?php
include '../config/cekAdmin.php';
include '../config/connect.php';

if (isset($_POST['update'])) {
    $id = intval($_POST['id_produk']);
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    // Cek jika ada file baru
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $path = "../img/produk/" . $gambar;

        if (move_uploaded_file($tmp, $path)) {
            // Update termasuk gambar
            $query = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, deskripsi=?, stok=?, gambar=? WHERE id_produk=?");
            $query->bind_param("sdsisi", $nama, $harga, $deskripsi, $stok, $gambar, $id);
        } else {
            echo "Gagal upload gambar.";
            exit;
        }
    } else {
        // Update tanpa gambar
        $query = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, deskripsi=?, stok=? WHERE id_produk=?");
        $query->bind_param("sdsii", $nama, $harga, $deskripsi, $stok, $id);
    }

    if ($query->execute()) {
        header("Location: ../html/kelola_produk.php?success=1");
        exit;
    } else {
        echo "Gagal update produk.";
    }
}
?>