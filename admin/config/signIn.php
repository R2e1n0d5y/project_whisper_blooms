<?php
session_start();
include 'connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

// $query = "SELECT * FROM user WHERE username='$username' AND password=SHA2('$password', 256)";
// $result = mysqli_query($conn, $query);

if ($username ='admin'&& $password='admin123') {
    $_SESSION['admin'] = $username;
    header("Location: ../html/dashboard_admin.php");
} else {
    echo "<script>alert('Login gagal!'); window.location='../html/signIn.php';</script>";
}
?>
