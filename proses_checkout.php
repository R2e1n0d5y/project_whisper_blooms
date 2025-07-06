<?php
session_start();
include 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
  echo "<script>alert('Anda harus login terlebih dahulu.'); window.location='sign_in.php';</script>";
  exit;
}

$metode = $_POST['metode_pembayaran'] ?? '';

$id_user = $_SESSION['id_user'];
$alamat_kirim = mysqli_real_escape_string($conn, $_POST['alamat_pengiriman'] ?? '');
$total_pembayaran_post = (int) ($_POST['total_pembayaran'] ?? 0);

if (empty($alamat_kirim)) {
  echo "<script>alert('Alamat pengiriman tidak boleh kosong.'); history.back();</script>";
  exit;
}

// Ambil data user
$q_user = mysqli_query($conn, "SELECT nama, email FROM user WHERE id_user = $id_user");
$data_user = mysqli_fetch_assoc($q_user);
$nama_user = $data_user['nama'];
$email_user = $data_user['email'];

// Ambil isi keranjang
$query_keranjang = mysqli_query($conn, "
  SELECT k.id_keranjang, k.id_produk, k.jumlah, p.nama_produk, p.harga
  FROM keranjang k
  JOIN produk p ON k.id_produk = p.id_produk
  WHERE k.id_user = $id_user
");

if (mysqli_num_rows($query_keranjang) === 0) {
  echo "<script>alert('Keranjang Anda kosong.'); window.location='keranjang_user.php';</script>";
  exit;
}

$total_harga = $total_pembayaran_post;
$detail_all = [];
$item_details = []; // untuk Midtrans

while ($row = mysqli_fetch_assoc($query_keranjang)) {
  $id_keranjang = $row['id_keranjang'];
  $id_produk = $row['id_produk'];
  $jumlah = $row['jumlah'];
  $harga_satuan = $row['harga'];
  $nama_produk = $row['nama_produk'];

  // Item untuk Midtrans
  $item_details[] = [
    "id" => $id_produk,
    "price" => $harga_satuan,
    "quantity" => $jumlah,
    "name" => $nama_produk
  ];

  // Detail keranjang
  $q_detail = mysqli_query($conn, "SELECT * FROM detail_keranjang WHERE id_keranjang = $id_keranjang");
  while ($d = mysqli_fetch_assoc($q_detail)) {
    $detail_all[] = [
      'id_produk' => $id_produk,
      'jumlah' => 1,
      'harga_satuan' => $harga_satuan,
      'total_harga' => $harga_satuan,
      'nama_penerima' => $d['nama_penerima'],
      'ucapan' => $d['ucapan'],
      'keterangan' => $d['keterangan'],
      'id_warna' => $d['id_warna'] ?? 'NULL',
      'id_desain' => $d['id_desain'] ?? 'NULL',
      'id_tema' => $d['id_tema'] ?? 'NULL'
    ];
  }
}

// Simpan pesanan
mysqli_query($conn, "INSERT INTO pesanan (id_user, total_harga, alamat_kirim) 
                     VALUES ($id_user, $total_harga, '$alamat_kirim')");
$id_pesanan = mysqli_insert_id($conn);

// Simpan detail pesanan
foreach ($detail_all as $item) {
  $nama = mysqli_real_escape_string($conn, $item['nama_penerima']);
  $ucapan = mysqli_real_escape_string($conn, $item['ucapan']);
  $ket = mysqli_real_escape_string($conn, $item['keterangan']);

  $id_warna = is_numeric($item['id_warna']) ? $item['id_warna'] : 'NULL';
  $id_desain = is_numeric($item['id_desain']) ? $item['id_desain'] : 'NULL';
  $id_tema = is_numeric($item['id_tema']) ? $item['id_tema'] : 'NULL';

  mysqli_query($conn, "
    INSERT INTO pesanan_detail (
      id_pesanan, id_produk, jumlah, harga_satuan, total_harga,
      id_desain, id_warna, id_tema,
      nama_penerima, ucapan, keterangan
    ) VALUES (
      $id_pesanan, {$item['id_produk']}, {$item['jumlah']}, {$item['harga_satuan']}, {$item['total_harga']},
      $id_desain, $id_warna, $id_tema,
      '$nama', '$ucapan', '$ket'
    )
  ");
}

// Bersihkan keranjang
mysqli_query($conn, "DELETE FROM detail_keranjang WHERE id_keranjang IN (SELECT id_keranjang FROM keranjang WHERE id_user = $id_user)");
mysqli_query($conn, "DELETE FROM keranjang WHERE id_user = $id_user");

if ($metode !== 'dana-kirim') {
  // Bukan metode DANA - kirim, langsung redirect
  header("Location: riwayat_pesanan.php");
  exit;
}

// Encode data untuk Midtrans
$item_detail_json = htmlspecialchars(json_encode($item_details));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pembayaran - Whisper Blooms</title>
  <meta name="description" content="Pembayaran greeting card handmade Whisper Blooms.">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/main.css">
  <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-e7hDwUCBBaR1HqYI"></script>
</head>
<body class="bg-gray-100">
  
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- Section Pembayaran -->
  <section class="hero">
    <div class="hero-background-only"></div>
    <div class="pad"></div>
    <div class="container" style="text-align: center;">
      <br>
      <h2>Menyiapkan Pembayaran Anda...</h2>
      <br>
      <form action="" method="POST" id="formCheckout" style="margin-top: 2rem;">
        <input type="hidden" name="total_harga" value="<?= $total_harga ?>">
        <input type="hidden" name="nama" value="<?= $nama_user ?>">
        <input type="hidden" name="email" value="<?= $email_user ?>">
        <input type="hidden" name="item_detail" value='<?= $item_detail_json ?>'>
        <button type="submit" id="checkoutButton" class="cta-button">Bayar Sekarang</button>
      </form>
    </div>
    <p style='padding: 20rem;' ></p>
  </section>

  <script>
    const checkoutButton = document.getElementById("checkoutButton");

    checkoutButton.addEventListener("click", async function (e) {
      e.preventDefault();

      const form = document.getElementById("formCheckout");
      const data = new FormData(form);

      try {
        const response = await fetch('midtrans/pembayaran.php', {
          method: "POST",
          body: data,
        });

        const token = await response.text();
        console.log("Snap Token:", token);

        window.snap.pay(token, {
          onSuccess: function(result) {
            alert("Pembayaran berhasil!");
            console.log(result);
            window.location = 'riwayat_pesanan.php';
          },
          onPending: function(result) {
            alert("Pembayaran menunggu konfirmasi.");
            console.log(result);
            window.location = 'riwayat_pesanan.php';
          },
          onError: function(result) {
            alert("Pembayaran gagal. Silakan coba lagi.");
            console.log(result);
          },
          onClose: function() {
            alert("Anda menutup pop-up pembayaran.");
          }
        });
      } catch (error) {
        console.error("Gagal memproses pembayaran:", error);
      }
    });
  </script>

  <script src="/js/script.js"></script>
</body>
</html>
