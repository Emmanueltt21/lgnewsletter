<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Newsletter Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-menu a {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: #667eea;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.sent {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .stat-icon.failed {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
        }

        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .history-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .history-header {
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .filter-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .filter-select {
            padding: 0.5rem 1rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: white;
            font-size: 0.875rem;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th,
        .history-table td {
            padding: 1rem 2rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .history-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .history-table tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-sent {
            background: #d4edda;
            color: #155724;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        @media (max-width: 768px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .nav-menu {
                display: none;
            }
            
            .history-table {
                font-size: 0.875rem;
            }
            
            .history-table th,
            .history-table td {
                padding: 0.75rem 1rem;
            }
            
            .filter-controls {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-envelope"></i> Newsletter Admin
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="<?= base_url('newsletter/subscribers') ?>"><i class="fas fa-users"></i> Subscribers</a></li>
                    <li><a href="<?= base_url('newsletter/newsletters') ?>"><i class="fas fa-newspaper"></i> Newsletters</a></li>
                    <li><a href="<?= base_url('newsletter/email_history') ?>" class="active"><i class="fas fa-history"></i> Email History</a></li>
                </ul>
            </nav>
            <div class="user-menu">
                <div class="user-avatar">A</div>
                <a href="<?= base_url('newsletter/admin_logout') ?>" class="btn btn-secondary">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title"><?= $title ?></h1>
            <p class="page-subtitle">Track email delivery and performance analytics</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-number" id="totalEmails">
                    <?= count($emails) ?>
                </div>
                <div class="stat-label">Total Emails</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon sent">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-number" id="sentEmails">
                    <?= count(array_filter($emails, function($email) { return $email->status === 'sent'; })) ?>
                </div>
                <div class="stat-label">Successfully Sent</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon failed">
                    <i class="fas fa-times"></i>
                </div>
                <div class="stat-number" id="failedEmails">
                    <?= count(array_filter($emails, function($email) { return $email->status === 'failed'; })) ?>
                </div>
                <div class="stat-label">Failed Deliveries</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number" id="pendingEmails">
                    <?= count(array_filter($emails, function($email) { return $email->status === 'pending'; })) ?>
                </div>
                <div class="stat-label">Pending</div>
            </div>
        </div>

        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-title">Email Delivery Trends (Last 30 Days)</div>
                <canvas id="deliveryChart" width="400" height="200"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-title">Delivery Status Distribution</div>
                <canvas id="statusChart" width="300" height="300"></canvas>
            </div>
        </div>

        <div class="history-container">
            <div class="history-header">
                <div class="history-title">
                    <i class="fas fa-history"></i> Email History
                </div>
                <div class="filter-controls">
                    <select class="filter-select" id="statusFilter" onchange="filterEmails()">
                        <option value="all">All Status</option>
                        <option value="sent">Sent</option>
                        <option value="failed">Failed</option>
                        <option value="pending">Pending</option>
                    </select>
                    <select class="filter-select" id="typeFilter" onchange="filterEmails()">
                        <option value="all">All Types</option>
                        <option value="newsletter">Newsletter</option>
                        <option value="confirmation">Confirmation</option>
                        <option value="welcome">Welcome</option>
                    </select>
                </div>
            </div>

            <?php if (empty($emails)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No email history</h3>
                    <p>Email history will appear here once you start sending newsletters</p>
                </div>
            <?php else: ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Recipient</th>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="emailTableBody">
                        <?php foreach ($emails as $email): ?>
                            <tr data-status="<?= $email->status ?>" data-type="<?= $email->email_type ?>">
                                <td>
                                    <strong><?= htmlspecialchars($email->recipient_name) ?></strong>
                                    <br>
                                    <small style="color: #666;"><?= htmlspecialchars($email->recipient_email) ?></small>
                                </td>
                                <td><?= htmlspecialchars($email->subject) ?></td>
                                <td>
                                    <span style="text-transform: capitalize;">
                                        <?= htmlspecialchars($email->email_type) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $email->status ?>">
                                        <?= ucfirst($email->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('M j, Y g:i A', strtotime($email->sent_at)) ?>
                                </td>
                                <td>
                                    <?php if ($email->status === 'failed' && $email->error_message): ?>
                                        <button onclick="showError('<?= htmlspecialchars($email->error_message, ENT_QUOTES) ?>')" 
                                                class="btn btn-secondary" title="View Error">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Delivery trends chart
        const deliveryCtx = document.getElementById('deliveryChart').getContext('2d');
        const deliveryChart = new Chart(deliveryCtx, {
            type: 'line',
            data: {
                labels: getLast30Days(),
                datasets: [{
                    label: 'Emails Sent',
                    data: getEmailCountsByDay(),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Status distribution chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Sent', 'Failed', 'Pending'],
                datasets: [{
                    data: [
                        <?= count(array_filter($emails, function($email) { return $email->status === 'sent'; })) ?>,
                        <?= count(array_filter($emails, function($email) { return $email->status === 'failed'; })) ?>,
                        <?= count(array_filter($emails, function($email) { return $email->status === 'pending'; })) ?>
                    ],
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        function getLast30Days() {
            const days = [];
            for (let i = 29; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                days.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            }
            return days;
        }

        function getEmailCountsByDay() {
            // This would typically come from the server
            // For now, return sample data
            const counts = new Array(30).fill(0);
            <?php foreach ($emails as $email): ?>
                const emailDate = new Date('<?= $email->sent_at ?>');
                const today = new Date();
                const diffTime = Math.abs(today - emailDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                if (diffDays <= 30) {
                    counts[30 - diffDays]++;
                }
            <?php endforeach; ?>
            return counts;
        }

        function filterEmails() {
            const statusFilter = document.getElementById('statusFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const rows = document.querySelectorAll('#emailTableBody tr');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const type = row.getAttribute('data-type');
                
                const statusMatch = statusFilter === 'all' || status === statusFilter;
                const typeMatch = typeFilter === 'all' || type === typeFilter;
                
                if (statusMatch && typeMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function showError(message) {
            alert('Error Details:\n\n' + message);
        }
    </script>
</body>
</html>