<?php
session_start();
include 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  echo "<script>alert('Anda harus login terlebih dahulu.'); window.location='sign_in.php';</script>";
  exit;
}

$id_user = $_SESSION['id_user'];
$query = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_user = $id_user ORDER BY tanggal_pesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Riwayat Pesanan - Whisper Blooms</title>
  <meta name="description" content="Lihat riwayat pesanan greeting card handmade Anda di Whisper Blooms.">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/keranjang.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <section class="hero">
    <section class="container riwayat-section">
      <h2 class="section-title">Riwayat <span>Pesanan Anda</span></h2>

      <?php if (mysqli_num_rows($query) > 0): ?>
        <div class="table-wrapper">
          <table class="tabel-riwayat">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total</th>
                <th>Alamat Kirim</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= date('d M Y, H:i', strtotime($row['tanggal_pesanan'])) ?></td>
                  <td><?= htmlspecialchars($row['status']) ?></td>
                  <td>IDR <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                  <td><?= nl2br(htmlspecialchars($row['alamat_kirim'])) ?></td>
                  <td>
                    <a href="detail_pesanan.php?id=<?= $row['id_pesanan'] ?>" class="btn btn-detail">Lihat Detail</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="empty-message">Belum ada pesanan.</p>
      <?php endif; ?>
    </section>
  </section>

  <script src="js/script.js"></script>
</body>
</html>
