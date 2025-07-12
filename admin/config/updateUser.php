<?php
include 'connect.php';

$id       = $_POST['id'];
$nama     = $_POST['nama'];
$email    = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$fotoBaru = $_FILES['foto_profil']['name'];
$lokasi   = $_FILES['foto_profil']['tmp_name'];

// Ambil data user lama
$dataLama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user=$id"));
$fotoLama = $dataLama['foto_profil'];

// Validasi email/username unik selain milik sendiri
$cekDuplikat = mysqli_query($conn, "SELECT * FROM user WHERE (email='$email' OR username='$username') AND id_user != $id");
if (mysqli_num_rows($cekDuplikat) > 0) {
    echo "<script>alert('Email atau Username sudah dipakai!'); window.history.back();</script>";
    exit;
}

// Handle upload foto jika ada file baru
if ($fotoBaru) {
    $ext = pathinfo($fotoBaru, PATHINFO_EXTENSION);
    $namaBaru = uniqid() . "." . strtolower($ext);
    $folderTujuan = "../assets/images/profil/" . $namaBaru;

    if (move_uploaded_file($lokasi, $folderTujuan)) {
        // Hapus foto lama jika bukan default
        if ($fotoLama != 'default.jpg' && file_exists("../assets/images/profil/" . $fotoLama)) {
            unlink("../assets/images/profil/" . $fotoLama);
        }
        $foto_profil = $namaBaru;
    } else {
        echo "<script>alert('Gagal upload foto!'); window.history.back();</script>";
        exit;
    }
} else {
    $foto_profil = $fotoLama;
}

// Handle password update (jika diisi)
if (!empty($password)) {
    $passwordQuery = ", password = SHA2('$password', 256)";
} else {
    $passwordQuery = "";
}

$query = "UPDATE user SET 
            nama = '$nama',
            email = '$email',
            username = '$username',
            foto_profil = '$foto_profil'
            $passwordQuery
          WHERE id_user = $id";

if (mysqli_query($conn, $query)) {
    header("Location: ../html/kelola_user.php");
} else {
    echo "Gagal update data user: " . mysqli_error($conn);
}
?>
