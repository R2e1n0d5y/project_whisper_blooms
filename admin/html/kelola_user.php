<?php include '../config/cekAdmin.php';
include '../config/connect.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Admin Panel</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
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
                <li><a href="kelola_user.php" class="nav-link active">
                        <i class="fas fa-users"></i> Users
                    </a></li>
                <li><a href="kelola_produk.php" class="nav-link">
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
                <h1 class="page-title">Kelola User</h1>
                <!-- <p class="page-subtitle">Manajemen data pengguna sistem</p> -->
            </div>
            <a href="tambah_user.php" class="btn btn-add">Tambah User Baru</a>
        </div>

        <div class="table-wrapper">
            <div class="table-header">
                <h2 class="table-title">Data User</h2>
                <div class="table-stats">
                    <div class="stat-item">
                        <span>Total User:</span>
                        <span class="stat-number">
                            <?php
                            $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM user");
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
                            <th>Foto Profil</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Bergabung</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data = mysqli_query($conn, "SELECT * FROM user WHERE username != 'admin' ORDER BY created_at DESC");
                        while ($d = mysqli_fetch_array($data)) {
                            $inisial = strtoupper(substr(trim($d['nama']), 0, 1));
                            $tanggal = date('d M Y', strtotime($d['created_at']));
                            $status = 'Aktif';
                            $statusClass = 'status-active';

                            echo "<tr>
                                <td>
                                    <div style=\"
                                        width: 48px;
                                        height: 48px;
                                        border-radius: 50%;
                                        background-color: #FFB4A2;
                                        color: #5C3D2E;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-weight: 600;
                                        font-size: 20px;
                                        font-family: 'Poppins', sans-serif;
                                        box-shadow: 0 0 4px rgba(0,0,0,0.1);
                                    \">
                                        {$inisial}
                                    </div>
                                </td>
                                <td><strong>{$d['nama']}</strong></td>
                                <td><code>@{$d['username']}</code></td>
                                <td><a href='mailto:{$d['email']}' style='color: var(--primary-peach); text-decoration: none;'>{$d['email']}</a></td>
                                <td><span style='color: var(--text-light); font-size: 0.9rem;'>{$tanggal}</span></td>
                                <td><span class='status-badge {$statusClass}'>{$status}</span></td>
                                <td>
                                    <div class='table-actions'>
                                        <a href='edit_user.php?id={$d['id_user']}' class='btn btn-edit'>Edit</a>
                                        <a href='hapus_user.php?id={$d['id_user']}' class='btn btn-delete' onclick='return confirm(\"Yakin ingin menghapus user {$d['nama']}?\")'>Hapus</a>
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
                order: [[4, 'desc']],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    emptyTable: "Tidak ada data yang tersedia",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian"
                },
                columnDefs: [
                    { orderable: false, targets: [0, 6] },
                    { className: "text-center", targets: [0, 5, 6] }
                ]
            });
        });
    </script>
</body>

</html>