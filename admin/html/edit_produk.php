<?php
include '../config/cekAdmin.php';
include '../config/connect.php';

if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$query = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "Produk tidak ditemukan.";
    exit;
}

$produk = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Produk</title>
    <link rel="stylesheet" href="../css/edit.css">
</head>

<body>
    <div class="container">
        <h2>Edit Produk</h2>
        <form action="../config/updateProduk.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_produk" value="<?= htmlspecialchars($produk['id_produk']) ?>">

            <label>Nama Produk:</label>
            <input type="text" name="nama_produk" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>

            <label>Harga:</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($produk['harga']) ?>" required>

            <label>Deskripsi:</label>
            <textarea name="deskripsi"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>

            <label>Stok:</label>
            <input type="number" name="stok" value="<?= htmlspecialchars($produk['stok']) ?>" required>

            <label>Gambar Baru (Opsional):</label>
            <input type="file" name="gambar">

            <button type="submit" name="update">Update Produk</button>
        </form>
    </div>
</body>

</html>