<?php 
include 'config/connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Wishper Blooms - Greeting Card Handmade Lukis</title>
  <meta name="description" content="Wishper Blooms menyediakan greeting card handmade dengan lukisan tangan untuk berbagai momen spesial. Custom nama, warna, dan tema sesuai keinginan Anda.">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/main.css">
  <script src="https://unpkg.com/feather-icons"></script>
  <style>
    /* Modal Zoom Image */
.zoom-modal {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.7);
  backdrop-filter: blur(4px);
}

.zoom-modal-content {
  max-width: 90%;
  max-height: 90%;
  box-shadow: 0 8px 20px rgba(0,0,0,0.3);
  border-radius: 10px;
  animation: zoomIn 0.3s ease-in-out;
}

.zoom-close {
  position: absolute;
  top: 20px;
  right: 30px;
  color: white;
  font-size: 40px;
  font-weight: bold;
  cursor: pointer;
  z-index: 10000;
}

.zoom-close:hover {
  color: var(--primary-peach);
}

.hidden {
  display: none;
}

@keyframes zoomIn {
  from { transform: scale(0.7); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

  </style>
</head>
<body class="bg-gray-100">
  <!-- Header -->
  <?php include 'navbar.php'; ?>
  <!-- Hero Section -->
  <section id="home" class="hero">
    <div class="pad"></div>
      <section class="products" id="products">
        <h2><span>Produk</span> Kami</h2>
        <p>Whisper Blooms menghadirkan greeting card handmade berbentuk lingkaran bunga dengan cat air, cocok untuk momen wisuda, ulang tahun, wedding, dan lainnya. Kartu kami membawa pesan penuh makna dalam bentuk seni yang personal dan eksklusif.</p>

        <div class="row">
          <?php
          $query = mysqli_query($conn, "SELECT * FROM produk");
          while ($row = mysqli_fetch_assoc($query)) :
          ?>
            <div class="product-card" >
              <div class="product-icons">
                <a href="#" class="add-cart-direct" data-id="<?= $row['id_produk']; ?>"><i data-feather="shopping-cart"></i></a>
                <a href="#" class="item-detail-button" data-id="<?= $row['id_produk']; ?>"><i data-feather="eye"></i></a>
              </div>
              <div class="product-image">
                <img src="/asset/menu/<?= $row['gambar']; ?>" alt="<?= $row['nama_produk']; ?>">
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

      <!-- Modal Zoom Image -->
<div id="image-zoom-modal" class="zoom-modal hidden">
  <span class="zoom-close" id="zoom-close">&times;</span>
  <img class="zoom-modal-content" id="zoom-image">
</div>

  
  </section>

  <script src="js/script.js" defer></script>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const zoomModal = document.getElementById("image-zoom-modal");
    const zoomImg = document.getElementById("zoom-image");
    const closeZoom = document.getElementById("zoom-close");

    document.querySelectorAll(".product-image img").forEach(img => {
      img.style.cursor = "pointer";
      img.addEventListener("click", () => {
        const fileName = img.src.split('/').pop(); // Ambil hanya nama file dari src
zoomImg.src = '/asset/menu/' + fileName;
        // zoomImg.src = '/asset/menu/'+img.src;
        zoomModal.classList.remove("hidden");
      });
    });

    closeZoom.addEventListener("click", () => {
      zoomModal.classList.add("hidden");
    });

    // Optional: close modal if background is clicked
    zoomModal.addEventListener("click", (e) => {
      if (e.target === zoomModal) {
        zoomModal.classList.add("hidden");
      }
    });
  });
</script>

</body>
</html>
