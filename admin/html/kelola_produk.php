<?php include '../config/cekAdmin.php';
include '../config/connect.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin Panel</title>
    <link rel="stylesheet" href="../css/kelola.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-container">
            <ul class="nav-menu" id="navMenu">
                <li><a href="dashboard_admin.php" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                <li><a href="kelola_user.php" class="nav-link">
                        <i class="fas fa-users"></i> Users
                    </a></li>
                <li><a href="kelola_produk.php" class="nav-link active">
                        <i class="fas fa-box-open"></i> Products
                    </a></li>
                <li><a href="pesananUser_admin.php" class="nav-link">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a></li>
                <li><a href="../config/logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a></li>
            </ul>

            <div class="nav-user">
                <div class="user-avatar">A</div>
                <span>Admin</span>
            </div>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>
    
    <div class="container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Kelola Produk</h1>
                <!-- <p class="page-subtitle">Manajemen inventory dan produk toko</p> -->
            </div>
            <a href="tambah_produk.php" class="btn btn-add">Tambah Produk Baru</a>
        </div>

        <div class="table-wrapper">
            <div class="table-header">
                <h2 class="table-title">Daftar Produk</h2>
                <div class="table-stats">
                    <div class="stat-item">
                        <span>Total Produk:</span>
                        <span class="stat-number">
                            <?php
                            $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
                            $total = mysqli_fetch_array($count);
                            echo $total['total'];
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="dataTables_wrapper">
                <table id="tabelData" class="display">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga Asli</th>
                            <th>Harga Diskon</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
                        while ($p = mysqli_fetch_array($produk)) {
                            $stokClass = $p['stok'] <= 5 ? 'stock-low' : ($p['stok'] <= 20 ? 'stock-medium' : 'stock-high');
                            $statusProduk = ($p['stok'] > 0) ? 'active' : 'inactive';
                            $statusClass = ($statusProduk == 'active') ? 'status-active' : 'status-inactive';
                            $statusText = ($statusProduk == 'active') ? 'Tersedia' : 'Habis';

                            // Cek gambar
                            $gambarPath = "../../asset/menu/" . $p['gambar'];
                            $gambarSrc = (file_exists($gambarPath) && $p['gambar'])
                                ? $gambarPath
                                : "data:image/svg+xml;base64," . base64_encode('<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect fill="#eee" width="100" height="100"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#aaa" font-size="14" font-family="Poppins">No Image</text></svg>');

                            echo "<tr>
                                <td><img src='{$gambarSrc}' class='table-image'></td>
                                <td>
                                    <strong>{$p['nama_produk']}</strong><br>
                                    <small>ID: {$p['id_produk']}</small>
                                </td>
                                <td>Rp " . number_format($p['harga'], 0, ',', '.') . "</td>
                                <td>";
                            if ($p['harga_diskon'] > 0) {
                                $diskon = round((($p['harga'] - $p['harga_diskon']) / $p['harga']) * 100);
                                echo "Rp " . number_format($p['harga_diskon'], 0, ',', '.') . "<br><small style='color: var(--danger);'>-{$diskon}%</small>";
                            } else {
                                echo "<span style='color: var(--text-light); font-style: italic;'>Tidak ada diskon</span>";
                            }
                            echo "</td>
                                <td><div class='stock-badge {$stokClass}'>{$p['stok']}</div></td>
                                <td><span class='status-badge {$statusClass}'>{$statusText}</span></td>
                                <td>
                                    <div class='table-actions'>
                                        <a href='edit_produk.php?id={$p['id_produk']}' class='btn btn-edit'>Edit</a>
                                        <a href='hapus_produk.php?id={$p['id_produk']}' class='btn btn-delete' onclick='return confirm(\"Hapus produk {$p['nama_produk']}?\")'>Hapus</a>
                                    </div>
                                </td>
                              </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        $(document).ready(function () {
            $('#tabelData').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Cari produk:",
                    lengthMenu: "Tampilkan _MENU_ produk per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ produk",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 produk",
                    infoFiltered: "(disaring dari _MAX_ total produk)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    emptyTable: "Tidak ada produk yang tersedia",
                    zeroRecords: "Tidak ada produk yang cocok dengan pencarian"
                },
                columnDefs: [
                    { orderable: false, targets: [0, 6] },
                    { className: "text-center", targets: [0, 4, 5, 6] }
                ]
            });
        });
    </script>
</body>

</html>