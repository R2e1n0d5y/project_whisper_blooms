<?php
include '../config/cekAdmin.php';
include '../config/connect.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user=$id"));
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/edit.css">
</head>

<body>
<div class="container">
    <h2>Edit User</h2>
    
    <form action="../config/updateUser.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Nama</label>
        <input type="text" name="nama" value="<?= $data['nama'] ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= $data['email'] ?>" required>

        <label>Username</label>
        <input type="text" name="username" value="<?= $data['username'] ?>" required>

        <label>Password (kosongkan jika tidak diubah)</label>
        <input type="password" name="password">

        <label>Foto Profil Sekarang</label><br>
        <img src="../assets/images/profil/<?= $data['foto_profil'] ?>" width="100" style="border-radius:50%; margin-bottom:10px;"><br>

        <label>Ganti Foto Profil</label>
        <input type="file" name="foto_profil" accept="image/*">

        <button type="submit">Update</button>
    </form>
</div>
</body>

</html>
