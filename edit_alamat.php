<?php
session_start();
include 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  header("Location: sign_in.php");
  exit;
}

$id_alamat = intval($_GET['id']);
$id_user = $_SESSION['id_user'];

$stmt = $conn->prepare("SELECT * FROM alamat WHERE id_alamat = ? AND id_user = ?");
$stmt->bind_param("ii", $id_alamat, $id_user);
$stmt->execute();
$result = $stmt->get_result();
$alamat = $result->fetch_assoc();

if (!$alamat) {
  echo "Data tidak ditemukan.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Alamat - Whisper Blooms</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
<section class="hero">
  <section class="section-padding">
    <div class="container">
      <h2 class="section-title">Edit Alamat</h2>

      <div class="form-box">
        <form action="update_alamat.php" method="POST" class="alamat-form">
         <input type="hidden" name="id_alamat" value="<?= htmlspecialchars($alamat['id_alamat']) ?>">

<label for="jalan">Jalan</label>
<input type="text" id="jalan" name="jalan" value="<?= htmlspecialchars($alamat['jalan']) ?>" placeholder="Jalan" required>

<label for="rt">RT</label>
<input type="text" id="rt" name="rt" value="<?= htmlspecialchars($alamat['rt']) ?>" placeholder="RT" required>

<label for="rw">RW</label>
<input type="text" id="rw" name="rw" value="<?= htmlspecialchars($alamat['rw']) ?>" placeholder="RW" required>

<label for="desa">Desa</label>
<input type="text" id="desa" name="desa" value="<?= htmlspecialchars($alamat['desa']) ?>" placeholder="Desa" required>

<label for="kecamatan">Kecamatan</label>
<input type="text" id="kecamatan" name="kecamatan" value="<?= htmlspecialchars($alamat['kecamatan']) ?>" placeholder="Kecamatan" required>

<label for="kota">Kota</label>
<input type="text" id="kota" name="kota" value="<?= htmlspecialchars($alamat['kota']) ?>" placeholder="Kota" required>

<label for="provinsi">Provinsi</label>
<input type="text" id="provinsi" name="provinsi" value="<?= htmlspecialchars($alamat['provinsi']) ?>" placeholder="Provinsi" required>


          <button type="submit" class="btn-primary">Update</button>
          <a href="alamat.php" class="btn-secondary" style="margin-left: 1rem;">Batal</a>
        </form>
      </div>
    </div>
  </section>
</section>
</body>
</html>
