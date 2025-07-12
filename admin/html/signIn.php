<!-- html/signin.php -->
<?php session_start();
if (isset($_SESSION['admin']))
  header("Location: dashboard_admin.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Login Admin - Wishper Blooms</title>
  <link rel="stylesheet" href="../css/sign.css">
</head>

<body>
  <form method="post" action="../config/signIn.php">
    <h2>Login Admin</h2>
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
  </form>
</body>

</html>