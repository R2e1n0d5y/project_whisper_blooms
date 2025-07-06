<?php
session_start();
include 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  echo "<script>alert('Anda harus login terlebih dahulu.'); window.location='sign_in.php';</script>";
  exit;
}

$id_user = $_SESSION['id_user'];
$id_pesanan = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan = $id_pesanan AND id_user = $id_user");
if (mysqli_num_rows($query_pesanan) === 0) {
  echo "<script>alert('Pesanan tidak ditemukan.'); window.location='riwayat_pesanan.php';</script>";
  exit;
}

$pesanan = mysqli_fetch_assoc($query_pesanan);

$query_detail = mysqli_query($conn, "
  SELECT d.*, p.nama_produk, d.id_produk,
         w.nama_warna, w.gambar_warna,
         t.nama_tema, t.gambar_tema,
         s.nama_desain, s.gambar_desain
  FROM pesanan_detail d
  JOIN produk p ON d.id_produk = p.id_produk
  LEFT JOIN warna_custom w ON d.id_warna = w.id_warna
  LEFT JOIN tema_custom t ON d.id_tema = t.id_tema
  LEFT JOIN desain_custom s ON d.id_desain = s.id_desain
  WHERE d.id_pesanan = $id_pesanan
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detail Pesanan - Whisper Blooms</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/keranjang.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
<section class="hero">
  <section class="container detail-pesanan-section">
    <h2 class="section-title">Detail <span>Pesanan Anda</span></h2>

    <div class="info-box">
      <p><strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($pesanan['tanggal_pesanan'])) ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars($pesanan['status']) ?></p>
      <p><strong>Total:</strong> IDR <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
      <p><strong>Alamat Kirim:</strong> <?= nl2br(htmlspecialchars($pesanan['alamat_kirim'])) ?></p>
    </div>

    <h3 class="sub-title">Item Pesanan:</h3>
    <div class="table-wrapper">
      <table class="tabel-detail">
        <thead>
          <tr>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Penerima</th>
            <th>Ucapan</th>
            <th>Keterangan</th>
            <th>Custom</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($query_detail)): ?>
            <tr>
              <td><?= htmlspecialchars($row['nama_produk']) ?></td>
              <td><?= (int)$row['jumlah'] ?></td>
              <td>IDR <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
              <td><?= nl2br(htmlspecialchars($row['nama_penerima'])) ?></td>
              <td><?= nl2br(htmlspecialchars($row['ucapan'])) ?></td>
              <td><?= $row['keterangan'] ? htmlspecialchars($row['keterangan']) : '-' ?></td>
              <td>
                <?php if ($row['id_produk'] == 5): ?>
                  <strong>Warna:</strong> <?= htmlspecialchars($row['nama_warna']) ?><br>
                  <?php if ($row['gambar_warna']): ?>
                    <img src="<?= htmlspecialchars($row['gambar_warna']) ?>" class="preview-img"><br>
                  <?php endif; ?>
                  <strong>Tema:</strong> <?= htmlspecialchars($row['nama_tema']) ?><br>
                  <?php if ($row['gambar_tema']): ?>
                    <img src="<?= htmlspecialchars($row['gambar_tema']) ?>" class="preview-img"><br>
                  <?php endif; ?>
                  <strong>Desain:</strong> <?= htmlspecialchars($row['nama_desain']) ?><br>
                  <?php if ($row['gambar_desain']): ?>
                    <img src="<?= htmlspecialchars($row['gambar_desain']) ?>" class="preview-img"><br>
                  <?php endif; ?>
                <?php else: ?>
                  <em>Tidak ada</em>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="action-buttons">
      <a href="riwayat_pesanan.php" class="btn btn-secondary">Kembali</a>
    </div>
  </section>
</section>
</body>
</html>
