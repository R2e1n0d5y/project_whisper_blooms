<?php
session_start();
include 'config/connect.php';

$id = $_GET['id'] ?? null;
if ($id) {
  mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang = $id");
}
header("Location: keranjang_user.php");
