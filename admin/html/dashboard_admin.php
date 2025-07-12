<?php
include '../config/cekAdmin.php';
include '../config/connect.php';

// Fetch data for stats cards
$userCount = $conn->query("SELECT COUNT(*) FROM user")->fetch_row()[0];
$productCount = $conn->query("SELECT COUNT(*) FROM produk")->fetch_row()[0];
$orderCount = $conn->query("SELECT COUNT(*) FROM pesanan")->fetch_row()[0];
$revenue = $conn->query("SELECT SUM(total_harga) FROM pesanan WHERE status = 'completed'")->fetch_row()[0] ?? 0;

// Get monthly order data for the chart
$monthlyOrders = $conn->query("
    SELECT 
        DATE_FORMAT(tanggal_pesanan, '%Y-%m') AS month, 
        COUNT(*) AS order_count,
        SUM(total_harga) AS revenue
    FROM pesanan
    WHERE tanggal_pesanan >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY month
    ORDER BY month
")->fetch_all(MYSQLI_ASSOC);

// Get product distribution data
$products = $conn->query("
    SELECT nama_produk, COUNT(pd.id_produk) AS sold_count
    FROM produk p
    LEFT JOIN pesanan_detail pd ON p.id_produk = pd.id_produk
    GROUP BY p.id_produk
    ORDER BY sold_count DESC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Get user registration data for new chart
$userRegistrations = $conn->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') AS month,
        COUNT(*) AS user_count
    FROM user
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY month
    ORDER BY month
")->fetch_all(MYSQLI_ASSOC);

// Get order status distribution
$orderStatus = $conn->query("
    SELECT status, COUNT(*) AS count
    FROM pesanan
    GROUP BY status
    ORDER BY count DESC
")->fetch_all(MYSQLI_ASSOC);

// Get daily sales for the current month
$dailySales = $conn->query("
    SELECT 
        DATE_FORMAT(tanggal_pesanan, '%Y-%m-%d') AS date,
        COUNT(*) AS order_count,
        SUM(total_harga) AS revenue
    FROM pesanan
    WHERE MONTH(tanggal_pesanan) = MONTH(CURDATE())
    AND YEAR(tanggal_pesanan) = YEAR(CURDATE())
    GROUP BY date
    ORDER BY date
")->fetch_all(MYSQLI_ASSOC);

// Handle status updates
if (isset($_POST['action']) && $_POST['action'] == 'update_status' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'];

    // Update order status
    $stmt = $conn->prepare("UPDATE pesanan SET status = ? WHERE id_pesanan = ?");
    $stmt->bind_param("si", $newStatus, $orderId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
    exit;
}

// Convert data to JSON for JavaScript
$monthlyOrdersJson = json_encode($monthlyOrders);
$productsJson = json_encode($products);
$userRegistrationsJson = json_encode($userRegistrations);
$orderStatusJson = json_encode($orderStatus);
$dailySalesJson = json_encode($dailySales);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin - Whisper Blooms</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-container">
            <ul class="nav-menu" id="navMenu">
                <li><a href="dashboard_admin.php" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a></li>
                <li><a href="kelola_user.php" class="nav-link">
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

    <!-- Main Content -->
    <div class="main-container">
        <div class="page-header">
            <h1 class="page-title">Dashboard Overview</h1>
            <p class="page-subtitle">Welcome back! Here's what's happening with your store today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card" onclick="navigateToPage('kelola_user.php')">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?= $userCount ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: 75%;"></div>
                </div>
            </div>

            <div class="stat-card" onclick="navigateToPage('kelola_produk.php')">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?= $productCount ?></div>
                        <div class="stat-label">Total Products</div>
                    </div>
                    <div class="stat-icon products">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: 60%;"></div>
                </div>
            </div>

            <div class="stat-card" onclick="navigateToPage('pesananUser_admin.php')">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?= $orderCount ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: 85%;"></div>
                </div>
            </div>

            <div class="stat-card" onclick="showRevenueDetails()">
                <div class="stat-header">
                    <div>
                        <div class="stat-value">Rp<?= number_format($revenue, 0, ',', '.') ?></div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                    <div class="stat-icon revenue">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: 90%;"></div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section" style="display: flex; justify-content: space-between;">
            <!-- Primary Chart with Controls -->
            <div class="chart-container chart-section" style="width: 60%;">
                <div class="chart-header">
                    <h2 class="chart-title">Analytics Dashboard</h2>
                    <select id="time-period" class="form-select">
                        <option value="12">Last 12 Months</option>
                        <option value="6">Last 6 Months</option>
                        <option value="3">Last 3 Months</option>
                        <option value="1">Current Month</option>
                    </select>
                </div>

                <div class="chart-controls">
                    <button class="chart-btn active" onclick="showChart('sales')">
                        <i class="fas fa-chart-line"></i> Sales Overview
                    </button>
                    <button class="chart-btn" onclick="showChart('users')">
                        <i class="fas fa-user-plus"></i> User Registrations
                    </button>
                    <button class="chart-btn" onclick="showChart('status')">
                        <i class="fas fa-pie-chart"></i> Order Status
                    </button>
                    <button class="chart-btn" onclick="showChart('daily')">
                        <i class="fas fa-calendar-day"></i> Daily Sales
                    </button>
                </div>

                <div style="height: 400px;">
                    <canvas id="primaryChart"></canvas>
                </div>
            </div>

            <!-- Secondary Chart -->
            <div class="chart-container" style="width: 40%;">
                <div class="chart-header">
                    <h2 class="chart-title">Top Products</h2>
                    <div class="chart-controls">
                        <button class="chart-btn active" onclick="toggleProductChart('doughnut')">
                            <i class="fas fa-chart-pie"></i> Doughnut
                        </button>
                        <button class="chart-btn" onclick="toggleProductChart('bar')">
                            <i class="fas fa-chart-bar"></i> Bar
                        </button>
                    </div>
                </div>
                <div style="height: 350px;">
                    <canvas id="productsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Enhanced Orders Table -->
        <div class="table-container">
            <div class="table-controls">
                <div>
                    <h2 class="chart-title">Order Management</h2>
                </div>
                <div class="sort-controls">
                    <span>Sort by:</span>
                    <button class="sort-btn active" onclick="sortTable('date', 'desc')">
                        <i class="fas fa-sort-amount-down"></i> Latest
                    </button>
                    <button class="sort-btn" onclick="sortTable('date', 'asc')">
                        <i class="fas fa-sort-amount-up"></i> Oldest
                    </button>
                    <button class="sort-btn" onclick="sortTable('amount', 'desc')">
                        <i class="fas fa-sort-amount-down"></i> Amount High
                    </button>
                    <button class="sort-btn" onclick="sortTable('amount', 'asc')">
                        <i class="fas fa-sort-amount-up"></i> Amount Low
                    </button>
                    <button class="export-btn" onclick="exportTable()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>

            <table class="table" id="ordersTable">
                <thead>
                    <tr>
                        <th>Order Details</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Enhanced query with customer information
                    $recentOrdersQuery = $conn->query("
    SELECT 
        p.id_pesanan, 
        p.tanggal_pesanan, 
        p.total_harga, 
        p.status,
        u.nama AS nama_user,
        u.email
    FROM pesanan p
    LEFT JOIN user u ON p.id_user = u.id_user
    ORDER BY p.tanggal_pesanan ASC
    LIMIT 15
");

                    if (!$recentOrdersQuery) {
                        die("Query recentOrders gagal: " . $conn->error);
                    }

                    $recentOrders = $recentOrdersQuery->fetch_all(MYSQLI_ASSOC);


                    foreach ($recentOrders as $order) {
                        $statusClass = '';
                        switch ($order['status']) {
                            case 'completed':
                                $statusClass = 'status-completed';
                                break;
                            case 'processing':
                                $statusClass = 'status-processing';
                                break;
                            case 'shipped':
                                $statusClass = 'status-shipped';
                                break;
                            case 'delivered':
                                $statusClass = 'status-delivered';
                                break;
                            case 'cancelled':
                                $statusClass = 'status-cancelled';
                                break;
                            default:
                                $statusClass = 'status-pending';
                        }

                        $customerName = $order['nama_user'] ?? 'Unknown';
                        $customerEmail = $order['email'] ?? 'No email';
                        $customerInitial = strtoupper(substr($customerName, 0, 1));

                        echo "
                        <tr id='order-row-{$order['id_pesanan']}'>
                            <td>
                                <div class='order-details'>
                                    <div class='order-id'>#WB{$order['id_pesanan']}</div>
                                    <div class='order-date'>" . date('d M Y, H:i', strtotime($order['tanggal_pesanan'])) . "</div>
                                </div>
                            </td>
                            <td>
                                <div class='customer-info'>
                                    <div class='customer-avatar'>{$customerInitial}</div>
                                    <div class='customer-details'>
                                        <div class='customer-name'>{$customerName}</div>
                                        <div class='customer-email'>{$customerEmail}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class='order-amount'>Rp" . number_format($order['total_harga'], 0, ',', '.') . "</div>
                            </td>
                            <td>
                                <span class='status-badge {$statusClass}' id='status-{$order['id_pesanan']}'>" . ucfirst($order['status']) . "</span>
                            </td>
                            <td>
                                <div class='action-buttons' id='actions-{$order['id_pesanan']}'>
                                    ";

                        // Show different action buttons based on current status
                        if ($order['status'] == 'pending') {
                            echo "<button class='action-btn btn-ship' onclick='updateStatus({$order['id_pesanan']}, \"processing\")'>
                                    <i class='fas fa-play'></i> Process
                                  </button>";
                        } elseif ($order['status'] == 'processing') {
                            echo "<button class='action-btn btn-ship' onclick='updateStatus({$order['id_pesanan']}, \"shipped\")'>
                                    <i class='fas fa-shipping-fast'></i> Ship
                                  </button>";
                        } elseif ($order['status'] == 'shipped') {
                            echo "<button class='action-btn btn-deliver' onclick='updateStatus({$order['id_pesanan']}, \"delivered\")'>
                                    <i class='fas fa-check-circle'></i> Deliver
                                  </button>";
                        } elseif ($order['status'] == 'delivered') {
                            echo "<button class='action-btn btn-complete' onclick='updateStatus({$order['id_pesanan']}, \"completed\")'>
                                    <i class='fas fa-star'></i> Complete
                                  </button>";
                        }

                        echo "
                                    <button class='action-btn btn-view' onclick='showOrderDetails({$order['id_pesanan']})'>
                                        <i class='fas fa-eye'></i> View
                                    </button>
                                </div>
                            </td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function updateStatus(id, newStatus) {
  fetch('update_status.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}&status=${newStatus}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // Update status badge di UI
      const statusSpan = document.getElementById(`status-${id}`);
      statusSpan.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
      statusSpan.className = 'status-badge status-' + newStatus;

      // Ganti action button sesuai status baru
      const actionsDiv = document.getElementById(`actions-${id}`);
      let nextButton = '';

      if (newStatus === 'pending') {
        nextButton = `<button class='action-btn btn-ship' onclick='updateStatus(${id}, "processing")'>
                        <i class='fas fa-play'></i> Process
                      </button>`;
      } else if (newStatus === 'processing') {
        nextButton = `<button class='action-btn btn-ship' onclick='updateStatus(${id}, "shipped")'>
                        <i class='fas fa-shipping-fast'></i> Ship
                      </button>`;
      } else if (newStatus === 'shipped') {
        nextButton = `<button class='action-btn btn-deliver' onclick='updateStatus(${id}, "delivered")'>
                        <i class='fas fa-check-circle'></i> Deliver
                      </button>`;
      } else if (newStatus === 'delivered') {
        nextButton = `<button class='action-btn btn-complete' onclick='updateStatus(${id}, "completed")'>
                        <i class='fas fa-star'></i> Complete
                      </button>`;
      }

      // Always include View button
      const viewBtn = `<button class='action-btn btn-view' onclick='showOrderDetails(${id})'>
                          <i class='fas fa-eye'></i> View
                        </button>`;

      actionsDiv.innerHTML = nextButton + viewBtn;
    } else {
      alert("Gagal memperbarui status: " + (data.error || 'Unknown error'));
    }
  })
  .catch(err => {
    console.error('Error:', err);
    alert("Terjadi kesalahan saat mengupdate status.");
  });
}

    </script>
    <script>
        // Store original orders data for sorting
        let originalOrders = [];

        // Get table data on page load
        document.addEventListener('DOMContentLoaded', function () {
            const table = document.getElementById('ordersTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                originalOrders.push({
                    element: row,
                    id: cells[0].textContent,
                    date: cells[1].textContent,
                    amount: parseFloat(cells[2].textContent.replace(/[^\d]/g, '')),
                    status: cells[3].textContent,
                    dateObj: new Date(cells[1].textContent)
                });
            }
        });

        // Update order status function
        async function updateStatus(orderId, newStatus) {
            const row = document.getElementById(`order-row-${orderId}`);
            const statusBadge = document.getElementById(`status-${orderId}`);
            const actionsDiv = document.getElementById(`actions-${orderId}`);

            // Add loading state
            row.classList.add('loading');

            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=update_status&order_id=${orderId}&status=${newStatus}`
                });

                const result = await response.json();

                if (result.success) {
                    // Update status badge
                    statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    statusBadge.className = `status-badge status-${newStatus}`;

                    // Update action buttons
                    updateActionButtons(orderId, newStatus);

                    // Show success message
                    showNotification('Status updated successfully!', 'success');
                } else {
                    showNotification('Failed to update status: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                showNotification('An error occurred while updating status', 'error');
            } finally {
                // Remove loading state
                row.classList.remove('loading');
            }
        }

        // Update action buttons based on status
        function updateActionButtons(orderId, status) {
            const actionsDiv = document.getElementById(`actions-${orderId}`);
            let buttonsHtml = '';

            switch (status) {
                case 'pending':
                    buttonsHtml = `
                        <button class='action-btn btn-ship' onclick='updateStatus(${orderId}, "processing")'>
                            <i class='fas fa-play'></i> Process
                        </button>
                    `;
                    break;
                case 'processing':
                    buttonsHtml = `
                        <button class='action-btn btn-ship' onclick='updateStatus(${orderId}, "shipped")'>
                            <i class='fas fa-shipping-fast'></i> Ship
                        </button>
                    `;
                    break;
                case 'shipped':
                    buttonsHtml = `
                        <button class='action-btn btn-deliver' onclick='updateStatus(${orderId}, "delivered")'>
                            <i class='fas fa-check-circle'></i> Deliver
                        </button>
                    `;
                    break;
                case 'delivered':
                    buttonsHtml = `
                        <button class='action-btn btn-complete' onclick='updateStatus(${orderId}, "completed")'>
                            <i class='fas fa-star'></i> Complete
                        </button>
                    `;
                    break;
                case 'completed':
                    buttonsHtml = `
                        <span style='color: #28a745; font-weight: 600;'>
                            <i class='fas fa-check-circle'></i> Order Completed
                        </span>
                    `;
                    break;
            }

            // Always add view button
            buttonsHtml += `
                <button class='action-btn btn-view' onclick='showOrderDetails(${orderId})'>
                    <i class='fas fa-eye'></i> View
                </button>
            `;

            actionsDiv.innerHTML = buttonsHtml;
        }

        // Show notification
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class='fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}'></i>
                ${message}
            `;

            // Add notification styles
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#28a745' : '#dc3545'};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                z-index: 1000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                animation: slideIn 0.3s ease;
            `;

            document.body.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        // Card Navigation Functions
        function navigateToPage(page) {
            window.location.href = page;
        }

        function showRevenueDetails() {
            alert('Revenue Details:\n\nTotal Revenue: Rp<?= number_format($revenue, 0, ',', '.') ?>\n\nClick OK to view detailed revenue reports.');
        }

        function showOrderDetails(orderId) {
            alert(`Order Details for Order #WB${orderId}\n\nClick OK to view full order details.`);
            window.location.href = `pesananUser_admin.php?id=${orderId}`;
        }

        // Sort table function
        function sortTable(column, order) {
            const tbody = document.getElementById('ordersTable').getElementsByTagName('tbody')[0];

            // Remove active class from all sort buttons
            document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            event.target.classList.add('active');

            let sortedOrders = [...originalOrders];

            if (column === 'date') {
                sortedOrders.sort((a, b) => {
                    return order === 'asc' ? a.dateObj - b.dateObj : b.dateObj - a.dateObj;
                });
            } else if (column === 'amount') {
                sortedOrders.sort((a, b) => {
                    return order === 'asc' ? a.amount - b.amount : b.amount - a.amount;
                });
            }

            // Clear and repopulate table
            tbody.innerHTML = '';
            sortedOrders.forEach(order => {
                tbody.appendChild(order.element.cloneNode(true));
            });
        }

        // Export table function
        function exportTable() {
            const table = document.getElementById('ordersTable');
            let csv = [];

            // Get headers
            const headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent);
            csv.push(headers.join(','));

            // Get data rows
            const rows = Array.from(table.querySelectorAll('tr')).slice(1);
            rows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td')).map(td => td.textContent.replace(/,/g, ''));
                csv.push(cells.join(','));
            });

            // Download CSV file
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'orders.csv';
            a.click();
            URL.revokeObjectURL(url);
        }

        // Chart Management Script for Admin Dashboard
        // Add this script after the existing JavaScript in your PHP file

        // Chart instances
        let primaryChart = null;
        let productsChart = null;
        let currentChartType = 'sales';
        let currentProductChartType = 'doughnut';

        // Chart data from PHP
        const chartData = {
            monthlyOrders: <?php echo $monthlyOrdersJson; ?>,
            products: <?php echo $productsJson; ?>,
            userRegistrations: <?php echo $userRegistrationsJson; ?>,
            orderStatus: <?php echo $orderStatusJson; ?>,
            dailySales: <?php echo $dailySalesJson; ?>
        };

        // Chart colors
        const colors = {
            primary: '#4f46e5',
            secondary: '#06b6d4',
            success: '#10b981',
            warning: '#f59e0b',
            danger: '#ef4444',
            info: '#3b82f6',
            light: '#f1f5f9',
            dark: '#1e293b'
        };

        // Chart color palettes
        const colorPalettes = {
            gradient: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'],
            vibrant: ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7', '#dda0dd'],
            professional: ['#2c3e50', '#3498db', '#e74c3c', '#f39c12', '#9b59b6', '#1abc9c']
        };

        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            initializeCharts();
        });

        // Initialize all charts
        function initializeCharts() {
            createPrimaryChart();
            createProductsChart();
        }

        // Create primary chart (sales overview by default)
        function createPrimaryChart() {
            const ctx = document.getElementById('primaryChart').getContext('2d');

            if (primaryChart) {
                primaryChart.destroy();
            }

            const data = getPrimaryChartData(currentChartType);
            const config = getPrimaryChartConfig(currentChartType, data);

            primaryChart = new Chart(ctx, config);
        }

        // Get data for primary chart based on type
        function getPrimaryChartData(type) {
            switch (type) {
                case 'sales':
                    return {
                        labels: chartData.monthlyOrders.map(item => {
                            const date = new Date(item.month + '-01');
                            return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                        }),
                        datasets: [{
                            label: 'Orders',
                            data: chartData.monthlyOrders.map(item => item.order_count),
                            backgroundColor: colors.primary,
                            borderColor: colors.primary,
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }, {
                            label: 'Revenue (in thousands)',
                            data: chartData.monthlyOrders.map(item => Math.round(item.revenue / 1000)),
                            backgroundColor: colors.success,
                            borderColor: colors.success,
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    };

                case 'users':
                    return {
                        labels: chartData.userRegistrations.map(item => {
                            const date = new Date(item.month + '-01');
                            return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                        }),
                        datasets: [{
                            label: 'New Users',
                            data: chartData.userRegistrations.map(item => item.user_count),
                            backgroundColor: colors.info,
                            borderColor: colors.info,
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    };

                case 'status':
                    return {
                        labels: chartData.orderStatus.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
                        datasets: [{
                            label: 'Order Status',
                            data: chartData.orderStatus.map(item => item.count),
                            backgroundColor: colorPalettes.vibrant.slice(0, chartData.orderStatus.length),
                            borderColor: '#fff',
                            borderWidth: 3
                        }]
                    };

                case 'daily':
                    return {
                        labels: chartData.dailySales.map(item => {
                            const date = new Date(item.date);
                            return date.getDate();
                        }),
                        datasets: [{
                            label: 'Daily Orders',
                            data: chartData.dailySales.map(item => item.order_count),
                            backgroundColor: colors.warning,
                            borderColor: colors.warning,
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }, {
                            label: 'Daily Revenue (in thousands)',
                            data: chartData.dailySales.map(item => Math.round(item.revenue / 1000)),
                            backgroundColor: colors.danger,
                            borderColor: colors.danger,
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    };

                default:
                    return { labels: [], datasets: [] };
            }
        }

        // Get configuration for primary chart
        function getPrimaryChartConfig(type, data) {
            const isStatusChart = type === 'status';

            return {
                type: isStatusChart ? 'doughnut' : 'bar',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                font: {
                                    size: 12,
                                    weight: '600'
                                },
                                color: '#64748b'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: colors.primary,
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true
                        }
                    },
                    scales: isStatusChart ? {} : {
                        x: {
                            display: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            display: true,
                            grid: {
                                color: '#e2e8f0',
                                lineWidth: 1
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animation: {
                        duration: 750,
                        easing: 'easeInOutQuart'
                    }
                }
            };
        }

        // Create products chart
        function createProductsChart() {
            const ctx = document.getElementById('productsChart').getContext('2d');

            if (productsChart) {
                productsChart.destroy();
            }

            const data = {
                labels: chartData.products.map(item => item.nama_produk),
                datasets: [{
                    label: 'Products Sold',
                    data: chartData.products.map(item => item.sold_count),
                    backgroundColor: colorPalettes.gradient.slice(0, chartData.products.length),
                    borderColor: '#fff',
                    borderWidth: 3
                }]
            };

            const config = {
                type: currentProductChartType,
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                font: {
                                    size: 11,
                                    weight: '600'
                                },
                                color: '#64748b'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: colors.primary,
                            borderWidth: 1,
                            cornerRadius: 8
                        }
                    },
                    scales: currentProductChartType === 'bar' ? {
                        x: {
                            display: true,
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            display: true,
                            grid: {
                                color: '#e2e8f0',
                                lineWidth: 1
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    } : {},
                    animation: {
                        duration: 750,
                        easing: 'easeInOutQuart'
                    }
                }
            };

            productsChart = new Chart(ctx, config);
        }

        // Show different chart types
        function showChart(type) {
            // Update active button
            document.querySelectorAll('.chart-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Update current chart type
            currentChartType = type;

            // Recreate chart with new data
            createPrimaryChart();
        }

        // Toggle product chart type
        function toggleProductChart(type) {
            // Update active button
            document.querySelectorAll('.chart-container:nth-child(2) .chart-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Update current product chart type
            currentProductChartType = type;

            // Recreate chart with new type
            createProductsChart();
        }

        // Handle time period changes
        document.getElementById('time-period').addEventListener('change', function (e) {
            const selectedPeriod = e.target.value;
            // You can implement filtering logic here based on the selected period
            console.log('Time period changed to:', selectedPeriod);
            // For now, just refresh the current chart
            createPrimaryChart();
        });

        // Add CSS for chart animations and loading states
        const chartStyles = `
    .chart-container {
        position: relative;
        overflow: hidden;
    }
    
    .chart-container.loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
    }
    
    .chart-btn {
        transition: all 0.3s ease;
        transform: translateY(0);
    }
    
    .chart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .chart-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;

        // Add styles to head
        const styleSheet = document.createElement('style');
        styleSheet.textContent = chartStyles;
        document.head.appendChild(styleSheet);
    </script>
</body>

</html>