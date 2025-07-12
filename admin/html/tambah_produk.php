<?php include '../config/cekAdmin.php'; include '../config/connect.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="../css/tambahProduk.css">
</head>
<body>
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Produk</h1>
        </div>
        <a href="kelola_produk.php" class="btn btn-view">Kembali</a>
    </div>

    <form method="post" enctype="multipart/form-data">
        <label>Nama Produk</label>
        <input name="nama_produk" type="text" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" required></textarea>

        <label>Harga</label>
        <input type="number" name="harga" required>

        <label>Harga Diskon</label>
        <input type="number" name="harga_diskon">

        <label>Stok</label>
        <input type="number" name="stok" value="50" required>

        <label>Gambar Produk</label>
        <input type="file" name="gambar" accept="image/*" required>

        <button type="submit" class="button-submit">Simpan Produk</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
        $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']);
        $harga = $_POST['harga'];
        $diskon = $_POST['harga_diskon'] ?: 0;
        $stok = $_POST['stok'];

        $gambar = $_FILES['gambar']['name'];
        $tmp    = $_FILES['gambar']['tmp_name'];
        $namabaru = basename($gambar); // pakai nama asli file
        $tujuan = "../../asset/menu/" . $namabaru;


        if (move_uploaded_file($tmp, $tujuan)) {
            $query = "INSERT INTO produk (nama_produk, deskripsi, harga, harga_diskon, stok, gambar) 
                      VALUES ('$nama', '$desc', $harga, $diskon, $stok, '$namabaru')";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Produk berhasil ditambahkan.'); window.location='kelola_produk.php';</script>";
            } else {
                echo "<p style='color:red;'>Gagal menyimpan ke database: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<script>alert('Gagal upload gambar!');</script>";
        }
    }
    ?>
</div>
</body>
</html>
