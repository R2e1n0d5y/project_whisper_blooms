<?php
session_start();
require_once 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  header("Location: sign_in.php");
  exit;
}

$id_user = $_SESSION['id_user'];
$id_alamat = intval($_POST['id_alamat']);

$sql = "UPDATE alamat SET 
          jalan=?, rt=?, rw=?, desa=?, kecamatan=?, kota=?, provinsi=?
        WHERE id_alamat=? AND id_user=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssii", 
  $_POST['jalan'], $_POST['rt'], $_POST['rw'], $_POST['desa'], 
  $_POST['kecamatan'], $_POST['kota'], $_POST['provinsi'],
  $id_alamat, $id_user
);
$stmt->execute();
$stmt->close();

header("Location: alamat.php");
exit;
