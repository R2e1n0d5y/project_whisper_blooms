<?php
include '../config/cekAdmin.php';
include '../config/connect.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - Admin Panel</title>
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
                <li><a href="dashboard_admin.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="kelola_user.php" class="nav-link"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="kelola_produk.php" class="nav-link"><i class="fas fa-box-open"></i> Products</a></li>
                <li><a href="pesananUser_admin.php" class="nav-link active"><i class="fas fa-shopping-bag"></i> Orders</a></li>
                <li><a href="../config/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
                <h1 class="page-title">Riwayat Pemesanan</h1>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="dataTables_wrapper">
                <table id="tabelData" class="display">
                    <thead>
                        <tr>
                            <th>Nama User</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "
                            SELECT 
                                u.nama AS nama_user,
                                pr.nama_produk,
                                pr.harga,
                                pd.jumlah,
                                ps.tanggal_pesanan
                            FROM pesanan ps
                            JOIN user u ON ps.id_user = u.id_user
                            JOIN pesanan_detail pd ON ps.id_pesanan = pd.id_pesanan
                            JOIN produk pr ON pd.id_produk = pr.id_produk
                            ORDER BY ps.tanggal_pesanan DESC
                        ";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $total = $row['harga'] * $row['jumlah'];
                            echo "<tr>
                                <td>{$row['nama_user']}</td>
                                <td>{$row['nama_produk']}</td>
                                <td>{$row['jumlah']}</td>
                                <td>Rp " . number_format($total, 0, ',', '.') . "</td>
                                <td>" . date('d M Y', strtotime($row['tanggal_pesanan'])) . "</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        $(document).ready(function () {
            $('#tabelData').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[4, 'desc']],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada entri",
                    infoFiltered: "(disaring dari _MAX_ total entri)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    },
                    emptyTable: "Tidak ada data pemesanan",
                    zeroRecords: "Tidak ada hasil ditemukan"
                }
            });
        });
    </script>
</body>

</html>
