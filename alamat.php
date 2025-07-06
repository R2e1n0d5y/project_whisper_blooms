<?php
session_start();
include 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  header("Location: sign_in.php");
  exit;
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT * FROM alamat WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$daftar_alamat = [];
while ($row = $result->fetch_assoc()) {
  $daftar_alamat[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Alamat Saya - Whisper Blooms</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
<section class="hero">
  <section class="section-padding">
    <div class="container">
      <h2 class="section-title">Alamat Saya</h2>

      <div class="alamat-box">
        <table class="alamat-table">
          <thead>
            <tr>
              <th>Alamat Lengkap</th>
              <th>Kota</th>
              <th>Provinsi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($daftar_alamat) === 0): ?>
              <tr><td colspan="4">Belum ada alamat.</td></tr>
            <?php else: ?>
              <?php foreach ($daftar_alamat as $alamat): ?>
              <tr>
                <td>
                  <?= htmlspecialchars($alamat['jalan']) ?>,
                  RT <?= htmlspecialchars($alamat['rt']) ?>/RW <?= htmlspecialchars($alamat['rw']) ?>,
                  Desa <?= htmlspecialchars($alamat['desa']) ?>, Kecamatan <?= htmlspecialchars($alamat['kecamatan']) ?>
                </td>
                <td><?= htmlspecialchars($alamat['kota']) ?></td>
                <td><?= htmlspecialchars($alamat['provinsi']) ?></td>
                <td>
                  <a href="edit_alamat.php?id=<?= $alamat['id_alamat'] ?>" class="btn-edit">Edit</a>
                  <a href="hapus_alamat.php?id=<?= $alamat['id_alamat'] ?>" class="btn-danger" onclick="return confirm('Hapus alamat ini?')">Hapus</a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="form-box">
        <h3 class="box-title">Tambah Alamat Baru</h3>
        <form action="tambah_alamat.php" method="POST" class="alamat-form">
          <input type="text" name="jalan" placeholder="Jalan" required>
          <input type="text" name="rt" placeholder="RT" required>
          <input type="text" name="rw" placeholder="RW" required>
          <input type="text" name="desa" placeholder="Desa" required>
          <input type="text" name="kecamatan" placeholder="Kecamatan" required>
          <input type="text" name="kota" placeholder="Kota" required>
          <input type="text" name="provinsi" placeholder="Provinsi" required>
          <button type="submit" class="btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </section>
</section>
</body>
</html>
