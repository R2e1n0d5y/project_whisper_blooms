<?php
// Tampilkan semua error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Midtrans library
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php';

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = false;
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Validasi input dari form
$total = isset($_POST['total_harga']) ? (int) $_POST['total_harga'] : 0;
$nama = isset($_POST['nama']) ? $_POST['nama'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$item_json = isset($_POST['item_detail']) ? $_POST['item_detail'] : '[]';

$item_details = json_decode($item_json, true);

// Validasi minimum
if ($total <= 0 || empty($nama) || empty($email) || empty($item_details)) {
  http_response_code(400);
  echo "Data tidak lengkap atau tidak valid.";
  exit;
}

// Buat parameter transaksi Midtrans
$params = [
  'transaction_details' => [
    'order_id' => 'WB-' . time(), // Buat ID unik
    'gross_amount' => $total,
  ],
  'item_details' => $item_details,
  'customer_details' => [
    'first_name' => $nama,
    'email' => $email,
  ],
];

try {
  $snapToken = \Midtrans\Snap::getSnapToken($params);
  echo $snapToken;
} catch (Exception $e) {
  http_response_code(500);
  echo "Gagal mendapatkan Snap Token: " . $e->getMessage();
}
?>
