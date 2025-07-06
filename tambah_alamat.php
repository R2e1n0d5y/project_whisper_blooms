<?php
session_start();
require_once 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  header("Location: sign_in.php");
  exit;
}

$id_user = $_SESSION['id_user'];
$jalan = $_POST['jalan'];
$rt = $_POST['rt'];
$rw = $_POST['rw'];
$desa = $_POST['desa'];
$kecamatan = $_POST['kecamatan'];
$kota = $_POST['kota'];
$provinsi = $_POST['provinsi'];

$sql = "INSERT INTO alamat (id_user, jalan, rt, rw, desa, kecamatan, kota, provinsi) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssss", $id_user, $jalan, $rt, $rw, $desa, $kecamatan, $kota, $provinsi);
$stmt->execute();
$stmt->close();

header("Location: alamat.php");
exit;
