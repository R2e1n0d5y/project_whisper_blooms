<?php
session_start();
include 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  echo "<script>alert('Anda harus login terlebih dahulu.'); window.location='sign_in.php';</script>";
  exit;
}

$id_user = $_SESSION['id_user'];

$query_alamat = mysqli_query($conn, "SELECT * FROM alamat WHERE id_user = $id_user");
$alamat_count = mysqli_num_rows($query_alamat);
$alamat_default = $alamat_count > 0 ? mysqli_fetch_assoc($query_alamat) : null;
mysqli_data_seek($query_alamat, 0);

$query_keranjang = mysqli_query($conn, "
  SELECT k.*, p.harga 
  FROM keranjang k
  JOIN produk p ON k.id_produk = p.id_produk
  WHERE k.id_user = $id_user
");

$total = 0;
while ($row = mysqli_fetch_assoc($query_keranjang)) {
  $total += $row['harga'] * $row['jumlah'];
}
mysqli_data_seek($query_keranjang, 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - Whisper Blooms</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/checkout.css">
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <?php include 'navbar.php'; ?>
<section class="hero">
  <section class="checkout-page section-padding">
    <div class="container">
      <h2 class="section-title">Checkout</h2>

      <!-- Alamat -->
      <div class="checkout-box">
        <h3 class="box-title">Alamat Pengiriman</h3>

        <?php if ($alamat_count > 0): ?>
          <p id="alamat-terpilih" class="alamat-terpilih"><?= 
            $alamat_default['jalan'] . ', RT ' . $alamat_default['rt'] . '/' . $alamat_default['rw'] . ', ' .
            $alamat_default['desa'] . ', ' . $alamat_default['kecamatan'] . ', ' . $alamat_default['kota'] . ', ' . $alamat_default['provinsi']
          ?></p>
          <button type="button" class="btn-secondary mt-1" onclick="document.getElementById('popupAlamat').style.display='flex'">Ganti Alamat</button>
          <a href="alamat.php" class="btn-primary mt-1">Tambah Alamat</a>
        <?php else: ?>
          <p id="alamat-terpilih" class="text-danger">Anda belum memiliki alamat pengiriman.</p>
          <a href="alamat.php" class="btn-primary mt-1">Tambah Alamat</a>
        <?php endif; ?>
      </div>

      <!-- Metode Pembayaran Section -->
        <div class="checkout-box mt-2">
          <h3 style="margin-bottom: 1rem; color: var(--text-dark);">Pilih Metode Pembayaran</h3>

          <form id="form-metode" style="display: flex; flex-direction: column; gap: 1rem;">
            <label style="display: flex; align-items: center; gap: 1rem;">
              <input type="radio" name="metode" value="ambil" checked>
              <span>Ambil di Tempat (Tanpa Ongkir)</span>
            </label>

            <label style="display: flex; align-items: center; gap: 1rem;">
              <input type="radio" name="metode" value="dana-kirim">
              <span>DANA - Dikirim (+Rp5.000 Ongkir)</span>
            </label>

            <label style="display: flex; align-items: center; gap: 1rem;">
              <input type="radio" name="metode" value="cod">
              <span>COD - Bayar di Tempat (+Rp5.000 Ongkir)</span>
            </label>

            <div class="total-ongkir" style="margin-top: 1rem; color: var(--text-dark);">
              <strong>Ongkir:</strong> <span id="ongkir-display">Rp0</span>
            </div>
          </form>
        </div>


      <!-- Popup Pilih Alamat -->
      <div class="popup-bg" id="popupAlamat">
        <div class="popup-box">
          <h4 class="box-title">Pilih Alamat</h4>
          <form id="formPilihAlamat">
            <?php while ($a = mysqli_fetch_assoc($query_alamat)) : 
              $alamat_full = $a['jalan'] . ', RT ' . $a['rt'] . '/' . $a['rw'] . ', ' .
                            $a['desa'] . ', ' . $a['kecamatan'] . ', ' . $a['kota'] . ', ' . $a['provinsi'];
            ?>
              <label class="alamat-radio">
                <input type="radio" name="alamat" value="<?= htmlspecialchars($alamat_full) ?>" <?= $a['id_alamat'] == $alamat_default['id_alamat'] ? 'checked' : '' ?>>
                <?= htmlspecialchars($alamat_full) ?>
              </label>
            <?php endwhile; ?>
            <div class="popup-actions">
              <button type="button" class="btn-primary" onclick="konfirmasiAlamat()">OK</button>
              <button type="button" class="btn-secondary" onclick="document.getElementById('popupAlamat').style.display='none'">Batal</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Total -->
<div class="checkout-box mt-2">
  <h3 class="box-title">Total Pembayaran:</h3>
  <p class="total-harga">IDR <?= number_format($total, 0, ',', '.') ?></p>

  <form action="proses_checkout.php" method="POST" id="formCheckout">
    <input type="hidden" name="total_pembayaran" id="inputTotalPembayaran" value="<?= $total ?>">
    <input type="hidden" name="alamat_pengiriman" id="inputAlamat" value="<?= isset($alamat_default) ? htmlspecialchars(
      $alamat_default['jalan'] . ', RT ' . $alamat_default['rt'] . '/' . $alamat_default['rw'] . ', ' .
      $alamat_default['desa'] . ', ' . $alamat_default['kecamatan'] . ', ' . $alamat_default['kota'] . ', ' . $alamat_default['provinsi']
    ) : '' ?>">
    <input type="hidden" name="metode_pembayaran" id="inputMetodePembayaran" value="ambil">

    <div class="checkout-actions">
      <button type="submit" class="btn-primary" <?= $alamat_count == 0 ? 'disabled' : '' ?>>Checkout</button>
      <a href="keranjang.php" class="btn-secondary">Batal</a>
    </div>
  </form>
</div>

    </div>
  </section>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const metodeRadios = document.querySelectorAll('input[name="metode"]');
    const ongkirDisplay = document.getElementById('ongkir-display');

    function updateOngkir() {
  const selected = document.querySelector('input[name="metode"]:checked').value;
  const ongkir = (selected === 'dana-kirim' || selected === 'cod') ? 5000 : 0;
  const baseTotal = <?= $total ?>;
  const totalWithOngkir = baseTotal + ongkir;

  // Update tampilan ongkir
  ongkirDisplay.textContent = `Rp${ongkir.toLocaleString('id-ID')}`;

  // Update value hidden input
  document.getElementById("inputTotalPembayaran").value = totalWithOngkir;

  // Update tampilan total harga yang terlihat
  const totalHargaElement = document.querySelector(".total-harga");
  totalHargaElement.textContent = `IDR ${totalWithOngkir.toLocaleString('id-ID')}`;
}


    metodeRadios.forEach(radio => {
      radio.addEventListener('change', updateOngkir);
    });

    updateOngkir(); // initial
  });
</script>

  <script>
    feather.replace();

    function konfirmasiAlamat() {
      const selected = document.querySelector("input[name='alamat']:checked");
      if (selected) {
        document.getElementById('alamat-terpilih').innerText = selected.value;
        document.getElementById('inputAlamat').value = selected.value;
      }
      document.getElementById('popupAlamat').style.display = 'none';
    }

    document.getElementById("formCheckout").addEventListener("submit", function(e) {
      const alamat = document.getElementById("inputAlamat").value.trim();
      if (!alamat) {
        e.preventDefault();
        alert("Silakan tambahkan alamat terlebih dahulu sebelum checkout.");
      }
    });

    document.addEventListener('DOMContentLoaded', function () {
  const metodeRadios = document.querySelectorAll('input[name="metode"]');
  const ongkirDisplay = document.getElementById('ongkir-display');
  const metodeInput = document.getElementById('inputMetodePembayaran');

  function updateOngkir() {
    const selected = document.querySelector('input[name="metode"]:checked').value;
    const ongkir = (selected === 'dana-kirim' || selected === 'cod') ? 5000 : 0;
    const baseTotal = <?= $total ?>;
    const totalWithOngkir = baseTotal + ongkir;

    // Update tampilan ongkir
    ongkirDisplay.textContent = `Rp${ongkir.toLocaleString('id-ID')}`;

    // Update value hidden input
    document.getElementById("inputTotalPembayaran").value = totalWithOngkir;

    // Update metode pembayaran
    metodeInput.value = selected;

    // Update tampilan total harga
    const totalHargaElement = document.querySelector(".total-harga");
    totalHargaElement.textContent = `IDR ${totalWithOngkir.toLocaleString('id-ID')}`;
  }

  metodeRadios.forEach(radio => {
    radio.addEventListener('change', updateOngkir);
  });

  updateOngkir(); // initial run
});

  </script>
</body>
</html>
