<?php
session_start();
include 'config/connect.php';

$user_id = intval($_SESSION['id_user']);

$sql = "SELECT username, nama, email, foto_profil FROM user WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $nama = $row['nama'];
    $email = $row['email'];
    $foto_profil = $row['foto_profil'] ?? 'default.jpg';
} else {
    echo "User tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profil - Whisper Blooms</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/checkout.css" />
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <?php include 'navbar.php'; ?>
<section class="hero">
  <section class="section-padding">
    <div class="container">
      <div class="profile-box">
        <form action="update_profile.php" method="post" enctype="multipart/form-data" class="profile-form">
          <div class="profile-picture-container">
            <img src="../upload/<?= htmlspecialchars($foto_profil) ?>" alt="Foto Profil" id="profileImage" class="profile-image">
            <input type="file" name="foto_profil" accept="image/*" id="fileInput" style="display: none;">
            <p id="saveNotice" class="save-notice">Tekan <strong>Simpan</strong> untuk menyimpan foto.</p>

            <div class="modal" id="profileModal">
              <div class="modal-content">
                <button type="button" class="btn-secondary" id="editBtn">Ubah Foto</button>
              </div>
            </div>
          </div>

          <h2 class="profile-username"><?= htmlspecialchars($username) ?></h2>
          <p class="profile-email"><?= htmlspecialchars($email) ?></p>

          <div class="profile-info">
            <label>Username</label>
            <input type="text" name="username" class="input-field" value="<?= htmlspecialchars($username) ?>" required>

            <label>Nama</label>
            <input type="text" name="nama" class="input-field" value="<?= htmlspecialchars($nama) ?>" required>

            <label>Email</label>
            <input type="email" name="email" class="input-field" value="<?= htmlspecialchars($email) ?>" required>
          </div>

          <button type="submit" class="btn-primary" style="margin-top: 1rem;">Simpan</button>
        </form>
      </div>
    </div>
  </section>
</section>

  <script>
    feather.replace();

    const profileImage = document.getElementById('profileImage');
    const modal = document.getElementById('profileModal');
    const editBtn = document.getElementById('editBtn');
    const fileInput = document.getElementById('fileInput');
    const saveNotice = document.getElementById('saveNotice');

    profileImage.addEventListener('click', () => {
      modal.style.display = 'flex';
    });

    editBtn.addEventListener('click', () => {
      fileInput.click();
      modal.style.display = 'none';
    });

    window.addEventListener('click', function (e) {
      if (e.target === modal) modal.style.display = 'none';
    });

    fileInput.addEventListener('change', function () {
      saveNotice.style.display = fileInput.files.length > 0 ? 'block' : 'none';
    });
  </script>
</body>
</html>
