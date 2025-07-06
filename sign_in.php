<?php
session_start();
include 'config/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {
        $_SESSION['error'] = "Username/email dan password wajib diisi.";
    } else {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = time();
        }

        if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_attempt_time'] < 300)) {
            $_SESSION['error'] = "Terlalu banyak percobaan. Coba lagi dalam 5 menit.";
        } else {
            $stmt = $conn->prepare("SELECT id_user, username, password, nama FROM user WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $login, $login);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['id_user'] = $user['id_user'];
                    $_SESSION['pengguna'] = $user['username'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['login_time'] = time();

                    $_SESSION['login_attempts'] = 0;
                    unset($_SESSION['last_attempt_time']);

                    header("Location: index.php");
                    exit;
                } else {
                    $_SESSION['error'] = "Password salah.";
                }
            } else {
                $_SESSION['error'] = "Username atau email tidak ditemukan.";
            }

            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Whisper Blooms</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/main.css">
</head>
<body>
  <?php if (isset($_SESSION['error'])): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        alert("<?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>");
      });
    </script>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php include 'navbar.php'; ?>
<section class="hero">
    <div class="container">
      <div class="login-box">
        <h2 class="login-title">Selamat Datang Kembali!</h2>
        <p class="login-subtitle">Silakan masuk ke akun Anda</p>
        <form action="" method="POST" class="login-form">
          <label for="username">Username atau Email</label>
          <input style="z-index: 10; position:relative" type="text" id="username" name="login" placeholder="Masukkan Username atau Email" required>

          <label for="password">Password</label>
          <input style="z-index: 10; position:relative" type="password" id="password" name="password" placeholder="Masukkan Password" required>

          <button type="submit" class="cta-button">Masuk</button>
          <p class="signup-text" style="z-index: 10; position:relative">Belum punya akun? <a style="z-index: 10; position:relative" href="sign_up.php">Daftar</a></p>
        </form>
      </div>
    </div>
</section>
</body>
</html>
