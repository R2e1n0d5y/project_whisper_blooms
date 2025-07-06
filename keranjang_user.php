<?php
session_start();
include 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  header('Location: sign_in.php');
  exit;
}

$id_user = $_SESSION['id_user'];

// Ambil isi keranjang user
$query = "SELECT k.*, p.nama_produk, p.gambar, p.harga, p.id_produk 
          FROM keranjang k 
          JOIN produk p ON k.id_produk = p.id_produk 
          WHERE k.id_user = $id_user";
$result = mysqli_query($conn, $query);

// Ambil data tema, warna, dan desain
$tema_result = mysqli_query($conn, "SELECT * FROM tema_custom");
$warna_result = mysqli_query($conn, "SELECT * FROM warna_custom");
$desain_result = mysqli_query($conn, "SELECT * FROM desain_custom");

$tema_list = mysqli_fetch_all($tema_result, MYSQLI_ASSOC);
$warna_list = mysqli_fetch_all($warna_result, MYSQLI_ASSOC);
$desain_list = mysqli_fetch_all($desain_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Whisper Blooms - Keranjang</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/keranjang.css">
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
<body>
  <?php include 'navbar.php'; ?>
<section id="home" class="hero">
  <section class="container keranjang-section">
    <h2 class="section-title">Keranjang Anda</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <form action="update_keranjang.php" method="POST" id="checkoutForm" >
        <?php 
        $grandTotal = 0;
        while ($row = mysqli_fetch_assoc($result)) : 
          $jumlah = $row['jumlah'];
          $is_custom = ($row['id_produk'] == 5);
          $total = $row['harga'] * $jumlah;
          $grandTotal += $total;
        ?>
          <div class="keranjang-card">
            <div class="keranjang-image">
              <img src="/asset/menu/<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>">
            </div>
            <div class="keranjang-info">
              <h3><?= $row['nama_produk'] ?></h3>
              <p>Harga: IDR <?= number_format($row['harga'], 0, ',', '.') ?></p>
              <p>Jumlah: <?= $jumlah ?></p>
              <p class="keranjang-total">Total: IDR <?= number_format($total, 0, ',', '.') ?></p>

              <?php for ($i = 0; $i < $jumlah; $i++): ?>
                <div class="detail-penerima" style="z-index: 10; position: relative;">
                  <p><strong>Detail Penerima #<?= $i + 1 ?></strong></p>

                  <input type="hidden" name="items[<?= $row['id_keranjang'] ?>][id_produk]" value="<?= $row['id_produk'] ?>">
                  <input type="hidden" name="items[<?= $row['id_keranjang'] ?>][jumlah]" value="<?= $jumlah ?>">

                  <label>Nama Penerima:</label>
                  <input type="text" name="items[<?= $row['id_keranjang'] ?>][nama_penerima][]" required>

                  <label>Ucapan:</label>
                  <input type="text" name="items[<?= $row['id_keranjang'] ?>][ucapan][]" required>

                  <?php if ($is_custom): ?>
                    <label>Warna Custom (max 4):</label>
                    <div class="grid-check">
                      <?php foreach ($warna_list as $warna): ?>
                        <label style="background: white; border-radius : 10px;  display: flex; flex-direction:column; padding: 5px; border: 1px solid #ccc;"><input type="checkbox" name="items[<?= $row['id_keranjang'] ?>][warna_custom][<?= $i ?>][]" value="<?= $warna['id_warna'] ?>"> <?= $warna['nama_warna'] ?></label>
                      <?php endforeach; ?>
                    </div>


                    <label>Desain Custom (max 4):</label>
                    <div class="grid-check">
                      <?php foreach ($desain_list as $desain): ?>
  <label style="background: white; border-radius: 10px; display: flex; flex-direction: column; align-items: center; padding: 10px; border: 1px solid #ccc; text-align: center;">
    <img src="/asset/menu/<?= $desain['gambar_desain'] ?>" alt="<?= htmlspecialchars($desain['nama_desain']) ?>" style="width: 80px; height: auto; margin-bottom: 5px; border-radius: 6px;">
    <span style="font-size: 0.9rem;"><?= htmlspecialchars($desain['nama_desain']) ?></span>
    <input type="checkbox" name="items[<?= $row['id_keranjang'] ?>][desain_custom][<?= $i ?>][]" value="<?= $desain['id_desain'] ?>">
  </label>
<?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                  <label>Tema:</label>
                  <div class="grid-radio">
                    <?php foreach ($tema_list as $tema): ?>
                      <label style="background: white; border-radius : 10px;  display: flex; flex-direction:column; padding: 5px; border: 1px solid #ccc;">
                        <input type="radio" name="items[<?= $row['id_keranjang'] ?>][tema_custom][<?= $i ?>]" value="<?= $tema['id_tema'] ?>" required> <?= $tema['nama_tema'] ?>
                      </label>
                    <?php endforeach; ?>
                  </div>

                  <label>Keterangan (opsional):</label>
                  <textarea name="items[<?= $row['id_keranjang'] ?>][keterangan][]"></textarea>
                </div>
              <?php endfor; ?>

              <div class="button-group-keranjang" style="z-index: 10; position: relative;">
                <a href="hapus_keranjang.php?id=<?= $row['id_keranjang'] ?>" class="btn btn-secondary" onclick="return confirm('Hapus item ini?')">Hapus</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
        <div class="keranjang-summary">      
          <div class="summary">
            <h3>Total Belanja: IDR <?= number_format($grandTotal, 0, ',', '.') ?></h3>
          </div>

          <div class="action-buttons-keranjang" style="z-index: 10; position: relative;">
            <button type="submit" class="btn-keranjang">Checkout</button>
            <a href="batal_checkout.php" class="btn btn-secondary">Batal</a>
          </div>
        </div> 
      </form>
    <?php else: ?>
      <p class="keranjang-empty">Keranjang Anda kosong.</p>
    <?php endif; ?>
  </section>
  <!-- Modal Zoom Image -->
<div id="image-zoom-modal" class="zoom-modal hidden">
  <span class="zoom-close" id="zoom-close">&times;</span>
  <img class="zoom-modal-content" id="zoom-image">
</div>


</section>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.getElementById("checkoutForm");
      if (!form) return;

      form.addEventListener("submit", function(e) {
        let valid = true;
        const inputs = form.querySelectorAll("input[required], textarea[required]");

        inputs.forEach(input => {
          if (input.value.trim() === "") {
            input.classList.add("invalid");
            valid = false;
          } else {
            input.classList.remove("invalid");
          }
        });

        if (!valid) {
          e.preventDefault();
          alert("Harap isi semua kolom yang wajib.");
        }
      });

      document.querySelectorAll(".grid-check").forEach(grid => {
        grid.addEventListener("change", function(e) {
          const checked = this.querySelectorAll("input[type='checkbox']:checked");
          if (checked.length > 4) {
            e.target.checked = false;
            alert("Maksimal pilih 4 item.");
          }
        });
      });
    });


  document.addEventListener("DOMContentLoaded", function () {
    const zoomModal = document.getElementById("image-zoom-modal");
    const zoomImg = document.getElementById("zoom-image");
    const closeZoom = document.getElementById("zoom-close");

    document.querySelectorAll(".keranjang-image img").forEach(img => {
      img.style.cursor = "pointer";
      img.addEventListener("click", () => {
        const fileName = img.src.split('/').pop(); // Ambil hanya nama file dari src
zoomImg.src = '/asset/menu/' + fileName;
        // zoomImg.src = img.src;
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
