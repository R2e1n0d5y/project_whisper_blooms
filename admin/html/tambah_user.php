<?php include '../config/cekAdmin.php'; include '../config/connect.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link rel="stylesheet" href="../css/tambahUser.css">
</head>
<body>
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah User</h1>
        </div>
        <a href="kelola_user.php" class="btn btn-view">Kembali</a>
    </div>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Nama Lengkap</label>
        <input name="nama" type="text" required>

        <label>Username</label>
        <input name="username" type="text" required>

        <label>Email</label>
        <input name="email" type="email" required>

        <label>Password</label>
        <input name="password" type="password" required>

        <label>Status</label>
        <select name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>

        <label>Foto Profil</label>
        <input name="foto_profil" type="file" accept="image/*">

        <button type="submit" class="btn btn-add">Simpan User</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $status = $_POST['status'];
        $created_at = date("Y-m-d H:i:s");
        $foto_nama = "";

        // Cek apakah email sudah ada
        $cek_email = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
        if (mysqli_num_rows($cek_email) > 0) {
            echo "<p style='color:red;'>Email sudah digunakan!</p>";
            exit;
        }

        // Upload gambar jika ada
        if (!empty($_FILES['foto_profil']['name'])) {
            $file = $_FILES['foto_profil'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($ext, $allowed_ext)) {
                $foto_nama = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $file['name']);
                $target = "../assets/images/profil/" . $foto_nama;
                move_uploaded_file($file['tmp_name'], $target);
            } else {
                echo "<p style='color:red;'>Ekstensi file tidak valid. Gunakan JPG, JPEG, PNG, atau GIF.</p>";
                exit;
            }
        }

        // Hash password
        $hashedPassword = hash("sha256", $password);

        // Insert user
        $query = "INSERT INTO user (nama, username, email, password, status, foto_profil, created_at) 
                  VALUES ('$nama', '$username', '$email', '$hashedPassword', '$status', '$foto_nama', '$created_at')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('User berhasil ditambahkan.'); window.location='kelola_user.php';</script>";
        } else {
            echo "<p style='color:red;'>Gagal menambahkan user: " . mysqli_error($conn) . "</p>";
        }
    }
    ?>
</div>
</body>
</html>
