<?php
session_start();
require_once 'config/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password_raw = trim($_POST['password']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);

    if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) {
        $_SESSION['error'] = "Username harus 5-20 karakter, tanpa spasi atau simbol aneh.";
    } elseif (strlen($password_raw) < 6) {
        $_SESSION['error'] = "Password minimal 6 karakter.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid.";
    } else {
        $stmt = $conn->prepare("SELECT id_user FROM user WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Username atau email sudah digunakan.";
        } else {
            $password = password_hash($password_raw, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO user (username, password, nama, email) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("ssss", $username, $password, $nama, $email);

            if ($insert_stmt->execute()) {
                $_SESSION['success'] = "Pendaftaran berhasil. Silakan login.";
                header("Location: sign_in.php");
                exit;
            } else {
                $_SESSION['error'] = "Gagal menyimpan data user.";
            }
            $insert_stmt->close();
        }

        $stmt->close();
    }
    $conn->close();
    header("Location: sign_up.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar - Whisper Blooms</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/main.css">
</head>
<body>
  <?php if (isset($_SESSION['error'])): ?>
    <script>
      alert("<?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>");
    </script>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php include 'navbar.php'; ?>

  <section class="hero">
    <div class="container">
      <div class="login-box">
        <h2 class="login-title">Buat Akun Baru</h2>
        <p class="login-subtitle">Isi formulir di bawah untuk mendaftar</p>
        <form action="" method="POST" class="login-form">
          <label for="username">Username</label>
          <input  style="z-index: 10; position:relative" type="text" id="username" name="username" placeholder="Masukkan Username" required>

          <label for="password">Password</label>
          <input  style="z-index: 10; position:relative" type="password" id="password" name="password" placeholder="Masukkan Password" required>

          <label for="nama">Nama</label>
          <input  style="z-index: 10; position:relative" type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" required>

          <label for="email">Email</label>
          <input  style="z-index: 10; position:relative" type="email" id="email" name="email" placeholder="Masukkan Email Aktif" required>

          <button  style="z-index: 10; position:relative" type="submit" class="cta-button">Daftar</button>
          <p class="signup-text">Sudah punya akun? <a  style="z-index: 10; position:relative" href="sign_in.php">Masuk</a></p>
        </form>
      </div>
    </div>
  </section>
</body>
</html>
