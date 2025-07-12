<?php
include '../config/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id'] ?? 0);
  $status = $_POST['status'] ?? '';

  $allowed = ['pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'];

  if ($id > 0 && in_array($status, $allowed)) {
    $stmt = $conn->prepare("UPDATE pesanan SET status = ? WHERE id_pesanan = ?");
    $stmt->bind_param('si', $status, $id);
    $success = $stmt->execute();

    if ($success) {
      echo json_encode(['success' => true, 'status' => $status]);
    } else {
      http_response_code(500);
      echo json_encode(['success' => false, 'error' => 'Gagal update status']);
    }
  } else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Data tidak valid']);
  }
} else {
  http_response_code(405);
  echo json_encode(['success' => false, 'error' => 'Metode tidak diizinkan']);
}
