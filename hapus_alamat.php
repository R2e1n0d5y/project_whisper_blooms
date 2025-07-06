<?php
session_start();
require_once 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  header("Location: sign_in.php");
  exit;
}

$id_alamat = intval($_GET['id']);
$id_user = $_SESSION['id_user'];

$sql = "DELETE FROM alamat WHERE id_alamat = ? AND id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_alamat, $id_user);
$stmt->execute();
$stmt->close();

header("Location: alamat.php");
exit;
