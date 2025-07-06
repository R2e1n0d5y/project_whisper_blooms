<?php
session_start();
require_once 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: sign_in.php");
    exit();
}

$id_user = intval($_SESSION['id_user']);
$username = $_SESSION['pengguna'];
$nama = trim($_POST['nama']);
$email = trim($_POST['email']);

$upload_folder = 'upload/';
$foto_baru = null;

// Ambil foto lama
$foto_lama = 'default.jpg';
$sql_foto = "SELECT foto_profil FROM user WHERE id_user = ?";
$stmt_foto = $conn->prepare($sql_foto);
$stmt_foto->bind_param("i", $id_user);
$stmt_foto->execute();
$result_foto = $stmt_foto->get_result();
if ($result_foto && $result_foto->num_rows > 0) {
    $row_foto = $result_foto->fetch_assoc();
    $foto_lama = $row_foto['foto_profil'];
}
$stmt_foto->close();

// Cek jika user upload file baru
if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['foto_profil']['tmp_name'];
    $file_ext = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
    $nama_file_baru = $username . '.' . strtolower($file_ext);
    $target_file = $upload_folder . $nama_file_baru;

    // Hapus file lama jika bukan default
    if ($foto_lama !== 'default.jpg') {
        $path_lama = $upload_folder . $foto_lama;
        if (file_exists($path_lama)) {
            unlink($path_lama);
        }
    }

    // Simpan file baru
    if (move_uploaded_file($file_tmp, $target_file)) {
        $foto_baru = $nama_file_baru;
    }
}

// Update ke database
if ($foto_baru) {
    $sql_update = "UPDATE user SET nama = ?, email = ?, foto_profil = ? WHERE id_user = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssi", $nama, $email, $foto_baru, $id_user);
} else {
    $sql_update = "UPDATE user SET nama = ?, email = ? WHERE id_user = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssi", $nama, $email, $id_user);
}

if ($stmt->execute()) {
    $_SESSION['nama'] = $nama; // perbarui nama di sesi juga
}
$stmt->close();
$conn->close();

header("Location: profile.php");
exit();
?>
