<?php include '../config/connect.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM user WHERE id_user=$id");
header("Location: kelola_user.php");
?>
