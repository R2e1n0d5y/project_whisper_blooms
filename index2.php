<?php 
include 'config/connect.php';
session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Whisper Blooms</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet">

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- My Style -->
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <!-- Navbar start -->
  <nav class="navbar">
    <a href="#" class="navbar-logo">Whisper<span>Blooms</span>.</a>

    <div class="navbar-nav">
      <a href="#home">Home</a>
      <a href="#about">Tentang Kami</a>
      <a href="#products">Produk</a>
      <a href="#contact">Kontak</a>
    </div>

    <div class="navbar-extra">
      <a href="#" id="search-button"><i data-feather="search"></i></a>
      <a href="keranjang_user.php" id="shopping-cart-button"><i data-feather="shopping-cart"></i></a>
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>

  </nav>
  <!-- Navbar end -->

  <!-- Hero Section start -->
  <section class="hero" id="home">
    <div class="mask-container">
      <main class="content">
        <h1>Greeting Card Whisper<span>Blooms</span></h1>
        <p style="color: #FDF3ED;">Wishper Blooms adalah brand lokal greeting card handmade lukis unik. Cocok untuk: ujian proposal, seminar hasil, yudisium, wisuda, ultah, wedding. Membawa pesan penuh makna dalam bentuk seni.</p>

      </main>
    </div>
  </section>
  <!-- Hero Section end -->

  <!-- About Section start -->
  <section id="about" class="about">
    <h2><span>Tentang</span> Kami</h2>

    <div class="row">
      <div class="about-img">
        <img src="img/tentang_kami.jpg" alt="Tentang Kami">
      </div>
      <div class="content">
        <h3>Kenapa memilih Whisper Blooms?</h3>
        <p>Greeting card handmade Whisper Blooms adalah pembuatan kartu ucapan dengan desain bunga yang dilukis sendiri, menghadirkan sentuhan unik dan personal untuk setiap momen special dengan bahan-bahan yang ramah lingkungan, menghadirkan sentuhan unik dan personal untuk setiap momen special.</p>
        <p>Handmade greeting card hadir sebagai solusi bagi orang-orang yang ingin memberikan ucapan yang lebih personal, estetik, dan bermakna dibandingkan kartu ucapan massal. Dengan desain bunga yang dilukis tangan, kartu ini memberi kesan hangat, tulus, dan eksklusif.</p>
        </div>
    </div>
  </section>
  <!-- About Section end -->

  <section class="products" id="products">
  <h2><span>Produk</span> Kami</h2>
  <p>Whisper Blooms menghadirkan greeting card handmade berbentuk lingkaran bunga dengan cat air, cocok untuk momen wisuda, ulang tahun, wedding, dan lainnya. Kartu kami membawa pesan penuh makna dalam bentuk seni yang personal dan eksklusif.</p>

  <div class="row">
    <?php
    $query = mysqli_query($conn, "SELECT * FROM produk");
    while ($row = mysqli_fetch_assoc($query)) :
    ?>
      <div class="product-card">
        <div class="product-icons">
          <a href="#" class="add-cart-direct" data-id="<?= $row['id_produk']; ?>"><i data-feather="shopping-cart"></i></a>
          <a href="#" class="item-detail-button" data-id="<?= $row['id_produk']; ?>"><i data-feather="eye"></i></a>
        </div>
        <div class="product-image">
          <img src="<?= $row['gambar']; ?>" alt="<?= $row['nama_produk']; ?>">
        </div>
        <div class="product-content">
          <h3><?= $row['nama_produk']; ?></h3>
          <div class="product-stars">
            <?php
            $rating = $row['rating'];
            for ($i = 1; $i <= 5; $i++) {
              if ($i <= $rating) {
                echo '<i data-feather="star" class="star-full"></i>';
              } else {
                echo '<i data-feather="star"></i>';
              }
            }
            ?>
          </div>
          <div class="product-price">
            IDR <?= number_format($row['harga'], 0, ',', '.'); ?>
            <?php if ($row['harga_diskon']): ?>
              <span>IDR <?= number_format($row['harga_diskon'], 0, ',', '.'); ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>


  <!-- Contact Section start -->
  <section id="contact" class="contact">
    <h2><span>Kontak</span> Kami</h2>
    <p>Hubungi kami untuk pemesanan atau pertanyaan seputar produk kartu ucapan lukis Whisper Blooms. Kami siap membantu menghadirkan seni dalam setiap ucapan Anda.</p>

    <div class="row">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63384.28173931124!2d116.0447415!3d-8.5833009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd69f153e7d3d71%3A0xabcd1234!2sMataram%2C%20Nusa%20Tenggara%20Barat!5e0!3m2!1sen!2sid!4v0000000000000!5m2!1sen!2sid"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>

      <form action="simpan_kontak.php" method="POST">
        <div class="input-group">
          <i data-feather="user"></i>
          <input type="text" name="nama" placeholder="nama" required>
        </div>
        <div class="input-group">
          <i data-feather="mail"></i>
          <input type="email" name="email" placeholder="email" required>
        </div>
        <div class="input-group">
          <i data-feather="phone"></i>
          <input type="text" name="no_hp" placeholder="no hp" required>
        </div>
        <div class="input-group">
          <textarea name="pesan" placeholder="pesan anda..." rows="4" required></textarea>
        </div>
        <button id="btn-kirim" type="submit" class="btn">kirim pesan</button>
      </form>
      <!-- Notifikasi sukses kontak -->
    </div>
    <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'success') : ?>
      <div id="notif-kontak" style="margin-top: 1rem; color: green; font-weight: bold; text-align: center;">
        ✅ Pesan berhasil dikirim!
      </div>
    <?php endif; ?>

  </section>
  <!-- Contact Section end -->

  <!-- Footer start -->
  <footer>
    <div class="socials">
      <a href="#"><i data-feather="instagram"></i></a>
      <a href="#"><i data-feather="twitter"></i></a>
      <a href="#"><i data-feather="facebook"></i></a>
    </div>

    <div class="links">
      <a href="#home">Home</a>
      <a href="#about">Tentang Kami</a>
      <a href="#menu">Menu</a>
      <a href="#contact">Kontak</a>
    </div>

    <div class="credit">
      <p>Created by <a href="">rendi</a>. | &copy; 2025.</p>
    </div>
  </footer>
  <!-- Footer end -->

<!-- Modal Box Item Detail start -->
<div class="modal" id="item-detail-modal">
  <div class="modal-container">
    <a href="#" class="close-icon"><i data-feather="x"></i></a>
    <div class="modal-content" id="modal-detail-content">
      <!-- Akan diisi via JavaScript -->
    </div>
  </div>
</div>
<!-- Modal Box Item Detail end -->


  <!-- tambahan -->
   <div id="cart-toast" class="cart-toast">Ditambahkan ke keranjang!</div>
  <!-- Feather Icons -->
   <!-- Modal Tambah ke Keranjang -->
<div class="modal" id="add-cart-modal">
  <div class="modal-container">
    <a href="#" class="close-cart-icon"><i data-feather="x"></i></a>
    <div class="modal-content" id="add-cart-content">
      <!-- Konten akan diisi via JavaScript -->
    </div>
  </div>
</div>

  <script>
    feather.replace()
  </script>

  <!-- My Javascript -->
  <script src="js/script.js"></script>
</body>


  <?php if (isset($_GET['pesan'])): ?>
    <script>
      const pesan = '<?= $_GET['pesan'] ?>';
      switch (pesan) {
        case 'produk_ada':
          alert('Produk sudah ada di keranjang.');
          break;
        case 'sukses_keranjang':
          alert('Produk berhasil ditambahkan ke keranjang.');
          break;
        case 'gagal_keranjang':
          alert('Gagal menambahkan ke keranjang.');
          break;
        case 'produk_invalid':
          alert('Produk tidak valid.');
          break;
      }
    </script>
  <?php endif; ?>


</html>