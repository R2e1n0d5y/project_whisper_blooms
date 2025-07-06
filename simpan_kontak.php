<?php
include 'config/connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama = htmlspecialchars($_POST['nama']);
  $email = htmlspecialchars($_POST['email']);
  $no_hp = htmlspecialchars($_POST['no_hp']);
  $pesan = htmlspecialchars($_POST['pesan']);

  $query = "INSERT INTO kontak (nama, email, no_hp, pesan) 
            VALUES ('$nama', '$email', '$no_hp', '$pesan')";

  if (mysqli_query($conn, $query)) {
    // Redirect dengan notifikasi sukses
    header("Location: contact.php?pesan=success#contact");
    exit;
  } else {
    echo "Gagal menyimpan pesan: " . mysqli_error($conn);
  }
}
?>
