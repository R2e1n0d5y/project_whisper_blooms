<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../html/index.php"); // arahkan ke halaman login
    exit;
}
?>