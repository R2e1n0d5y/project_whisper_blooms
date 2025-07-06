<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Wishper Blooms - Navbar</title>

  <!-- Tailwind + Custom Theme -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'primary-peach': '#FFB4A2',
            'dusty-pink': '#CBAACB',
            'warm-cream': '#FFF8F0',
            'text-dark': '#5C4A4A'
          }
        }
      }
    }
  </script>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .brand-font {
      font-family: 'Dancing Script', cursive;
    }
  </style>
</head>

<body class="bg-warm-cream">

<?php
session_start();
include 'config/connect.php';

// Ambil foto profil user dari DB
$avatar = 'default.jpg'; // default fallback
if (isset($_SESSION['id_user'])) {
    $id = $_SESSION['id_user'];
    $stmt = $conn->prepare("SELECT foto_profil FROM user WHERE id_user = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($foto);
    if ($stmt->fetch() && !empty($foto)) {
        $avatar = $foto;
    }
    $stmt->close();
}
$img_path = "upload/" . $avatar; // pastikan folder 'uploads' ada
?>

<!-- NAVBAR -->
<nav class="bg-warm-cream shadow-md">
  <div class="mx-auto max-w-7xl px-4">
    <div class="flex justify-between items-center h-16">

      <!-- Logo -->
      <div class="flex items-center space-x-3">
        <img class="h-20 w-auto" src="asset/logo.png" alt="Logo" />
        <span class="text-text-dark text-8xl font-semibold brand-font"></span>
      </div>

      <!-- Desktop Navigation -->
      <div class="hidden sm:flex space-x-4">
        <a href="index.php" class="nav-link px-4 py-2 rounded-md text-sm text-text-dark hover:bg-primary-peach hover:text-white">Home</a>
        <a href="about.php" class="nav-link px-4 py-2 rounded-md text-sm text-text-dark hover:bg-primary-peach hover:text-white">About Us</a>
        <a href="product.php" class="nav-link px-4 py-2 rounded-md text-sm text-text-dark hover:bg-primary-peach hover:text-white">Product</a>
        <a href="contact.php" class="nav-link px-4 py-2 rounded-md text-sm text-text-dark hover:bg-primary-peach hover:text-white">Contact</a>
        <a href="keranjang_user.php" class="nav-link px-4 py-2 rounded-md text-sm text-text-dark hover:bg-primary-peach hover:text-white">Keranjang</a>
        <a href="riwayat_pesanan.php" class="nav-link px-4 py-2 rounded-md text-sm text-text-dark hover:bg-primary-peach hover:text-white">Pemesanan</a>
      </div>

      <!-- Profile -->
      <!-- Profile or Login -->
<div class="relative ml-3">
  <?php if (isset($_SESSION['id_user'])): ?>
    <!-- Jika sudah login, tampilkan foto profil dan dropdown -->
    <button id="user-menu-button" class="flex items-center rounded-full bg-primary-peach p-1 hover:ring-2 hover:ring-dusty-pink">
      <img class="h-8 w-8 rounded-full object-cover" src="<?= htmlspecialchars($img_path) ?>" alt="Foto Profil">
    </button>
    <div id="dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black/5 z-10">
      <a href="profile.php" class="block px-4 py-2 text-sm text-text-dark hover:bg-warm-cream">Your Profile</a>
      <a href="alamat.php" class="block px-4 py-2 text-sm text-text-dark hover:bg-warm-cream">Alamat</a>
      <a href="sign_out.php" class="block px-4 py-2 text-sm text-text-dark hover:bg-warm-cream">Sign out</a>
    </div>
  <?php else: ?>
    <!-- Jika belum login, tampilkan tombol Login -->
    <a href="sign_in.php" class="bg-primary-peach text-white text-sm px-4 py-2 rounded-md hover:bg-dusty-pink">Login</a>
  <?php endif; ?>
</div>

      <!-- Mobile Menu Button -->
      <div class="sm:hidden flex items-center">
        <button id="mobile-menu-button" class="text-text-dark p-2 rounded-md focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
<div id="mobile-menu" class="hidden sm:hidden px-4 pb-4 space-y-2">
  <a href="index.php" class="block nav-link text-text-dark hover:bg-primary-peach hover:text-white px-4 py-2 rounded-md">Home</a>
  <a href="about.php" class="block nav-link text-text-dark hover:bg-primary-peach hover:text-white px-4 py-2 rounded-md">About Us</a>
  <a href="product.php" class="block nav-link text-text-dark hover:bg-primary-peach hover:text-white px-4 py-2 rounded-md">Product</a>
  <a href="contact.php" class="block nav-link text-text-dark hover:bg-primary-peach hover:text-white px-4 py-2 rounded-md">Contact</a>
  <a href="keranjang_user.php" class="block nav-link text-text-dark hover:bg-primary-peach hover:text-white px-4 py-2 rounded-md">Keranjang</a>
  <a href="riwayat_pesanan.php" class="block nav-link text-text-dark hover:bg-primary-peach hover:text-white px-4 py-2 rounded-md">Pemesanan</a>

  <?php if (isset($_SESSION['id_user'])): ?>
    <!-- Menu tambahan saat login -->
    <hr class="border-dusty-pink my-2" />
    <a href="profile.php" class="block nav-link text-text-dark hover:bg-primary-peach hover:text-white px-4 py-2 rounded-md">Your Profile</a>
    <a href="alamat.php" class="block nav-link text-text-dark hover:bg-primary-peach hover:text-white px-4 py-2 rounded-md">Alamat</a>
    <a href="sign_out.php" class="block nav-link text-text-dark hover:bg-primary-peach hover:text-white px-4 py-2 rounded-md">Sign out</a>
  <?php else: ?>
    <!-- Tombol login jika belum login -->
    <a href="sign_in.php" class="block nav-link text-white bg-primary-peach hover:bg-dusty-pink px-4 py-2 rounded-md text-center">Login</a>
  <?php endif; ?>
</div>

</nav>

<!-- Script -->
<script>
  // Mobile toggle
  const mobileBtn = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  mobileBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });

  // Profile dropdown
  const userBtn = document.getElementById('user-menu-button');
  const dropdown = document.getElementById('dropdown-menu');
  userBtn.addEventListener('click', () => {
    dropdown.classList.toggle('hidden');
  });

  // Close dropdown on outside click
  document.addEventListener('click', (e) => {
    if (!userBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });

  // Highlight current page
  document.addEventListener("DOMContentLoaded", () => {
    const currentPath = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      const href = link.getAttribute("href");
      if (href === currentPath) {
        link.classList.add("bg-dusty-pink", "text-white");
      }
    });
  });
</script>

</body>
</html>
