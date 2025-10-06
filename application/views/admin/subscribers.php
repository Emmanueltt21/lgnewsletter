<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subscribers - Newsletter Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1f2937;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 600;
        }

        .nav-menu {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }

        .nav-menu a:hover {
            opacity: 0.8;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
        }

        .filters-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .filters-row {
            display: grid;
            grid-template-columns: 1fr 200px 200px 150px;
            gap: 20px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-group input,
        .form-group select {
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .filter-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .filter-btn:hover {
            transform: translateY(-1px);
        }

        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-item {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .subscribers-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .bulk-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .bulk-select {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .bulk-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .bulk-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .bulk-btn.delete:hover {
            background: #fecaca;
        }

        .bulk-btn.export {
            background: #f0f9ff;
            color: #0284c7;
        }

        .bulk-btn.export:hover {
            background: #e0f2fe;
        }

        .subscribers-table {
            width: 100%;
            border-collapse: collapse;
        }

        .subscribers-table th,
        .subscribers-table td {
            padding: 15px 25px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }

        .subscribers-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .subscribers-table td {
            font-size: 14px;
            color: #6b7280;
        }

        .subscribers-table tr:hover {
            background: #f9fafb;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-unsubscribed {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn.resend {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .action-btn.resend:hover {
            background: #bfdbfe;
        }

        .action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-btn.delete:hover {
            background: #fecaca;
        }

        .pagination {
            padding: 20px 25px;
            display: flex;
            justify-content: between;
            align-items: center;
            border-top: 1px solid #e5e7eb;
        }

        .pagination-info {
            color: #6b7280;
            font-size: 14px;
        }

        .pagination-buttons {
            display: flex;
            gap: 5px;
        }

        .page-btn {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .page-btn:hover {
            background: #f3f4f6;
        }

        .page-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .filters-row {
                grid-template-columns: 1fr;
            }
            
            .stats-bar {
                grid-template-columns: 1fr;
            }
            
            .subscribers-table {
                font-size: 12px;
            }
            
            .subscribers-table th,
            .subscribers-table td {
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-lighthouse"></i>
                Newsletter Admin
            </div>
            <nav class="nav-menu">
                <a href="<?php echo base_url('dashboard'); ?>">Dashboard</a>
                <a href="<?php echo base_url('newsletter/subscribers'); ?>" style="opacity: 1;">Subscribers</a>
                <a href="<?php echo base_url('newsletter/compose'); ?>">Compose</a>
                <a href="<?php echo base_url('newsletter/email_history'); ?>">History</a>
                <a href="<?php echo base_url('newsletter/settings'); ?>">Settings</a>
                <a href="<?php echo base_url('newsletter/admin_logout'); ?>" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Manage Subscribers</h1>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <form method="GET" action="<?php echo base_url('newsletter/subscribers'); ?>">
                <div class="filters-row">
                    <div class="form-group">
                        <label for="search">Search Subscribers</label>
                        <input type="text" id="search" name="search" 
                               value="<?php echo htmlspecialchars($this->input->get('search') ?? ''); ?>"
                               placeholder="Search by name or email...">
                    </div>
                    <div class="form-group">
                        <label for="status">Status Filter</label>
                        <select id="status" name="status">
                            <option value="all" <?php echo ($this->input->get('status') == 'all') ? 'selected' : ''; ?>>All Status</option>
                            <option value="confirmed" <?php echo ($this->input->get('status') == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="pending" <?php echo ($this->input->get('status') == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="unsubscribed" <?php echo ($this->input->get('status') == 'unsubscribed') ? 'selected' : ''; ?>>Unsubscribed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_range">Date Range</label>
                        <select id="date_range" name="date_range">
                            <option value="all" <?php echo ($this->input->get('date_range') == 'all') ? 'selected' : ''; ?>>All Time</option>
                            <option value="today" <?php echo ($this->input->get('date_range') == 'today') ? 'selected' : ''; ?>>Today</option>
                            <option value="week" <?php echo ($this->input->get('date_range') == 'week') ? 'selected' : ''; ?>>This Week</option>
                            <option value="month" <?php echo ($this->input->get('date_range') == 'month') ? 'selected' : ''; ?>>This Month</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number"><?php echo isset($stats['total']) ? number_format($stats['total']) : '0'; ?></div>
                <div class="stat-label">Total Subscribers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo isset($stats['confirmed']) ? number_format($stats['confirmed']) : '0'; ?></div>
                <div class="stat-label">Confirmed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo isset($stats['pending']) ? number_format($stats['pending']) : '0'; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo isset($stats['unsubscribed']) ? number_format($stats['unsubscribed']) : '0'; ?></div>
                <div class="stat-label">Unsubscribed</div>
            </div>
        </div>

        <!-- Subscribers Table -->
        <div class="subscribers-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-users"></i>
                    Subscribers List
                </h2>
                <div class="bulk-actions">
                    <select class="bulk-select" id="bulk-action">
                        <option value="">Bulk Actions</option>
                        <option value="delete">Delete Selected</option>
                        <option value="resend">Resend Confirmation</option>
                        <option value="export">Export Selected</option>
                    </select>
                    <button class="bulk-btn export" onclick="exportSubscribers()">
                        <i class="fas fa-download"></i> Export All
                    </button>
                </div>
            </div>

            <?php if (!empty($subscribers)): ?>
                <table class="subscribers-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Subscribed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscribers as $subscriber): ?>
                            <tr>
                                <td><input type="checkbox" class="subscriber-checkbox" value="<?php echo $subscriber->id; ?>"></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($subscriber->first_name . ' ' . $subscriber->last_name); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($subscriber->email); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $subscriber->status; ?>">
                                        <?php echo ucfirst($subscriber->status); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($subscriber->created_at)); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($subscriber->status == 'pending'): ?>
                                            <button class="action-btn resend" onclick="resendConfirmation(<?php echo $subscriber->id; ?>)">
                                                <i class="fas fa-paper-plane"></i> Resend
                                            </button>
                                        <?php endif; ?>
                                        <button class="action-btn delete" onclick="deleteSubscriber(<?php echo $subscriber->id; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <div class="pagination-info">
                        Showing <?php echo count($subscribers); ?> subscribers
                    </div>
                    <div class="pagination-buttons">
                        <!-- Pagination buttons would be implemented here -->
                        <button class="page-btn active">1</button>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>No Subscribers Found</h3>
                    <p>No subscribers match your current filters.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Select all functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.subscriber-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Bulk actions
        document.getElementById('bulk-action').addEventListener('change', function() {
            const action = this.value;
            const selected = getSelectedSubscribers();
            
            if (!action || selected.length === 0) return;
            
            switch(action) {
                case 'delete':
                    if (confirm(`Are you sure you want to delete ${selected.length} subscribers?`)) {
                        bulkDelete(selected);
                    }
                    break;
                case 'resend':
                    if (confirm(`Resend confirmation emails to ${selected.length} subscribers?`)) {
                        bulkResend(selected);
                    }
                    break;
                case 'export':
                    exportSelected(selected);
                    break;
            }
            
            this.value = '';
        });

        function getSelectedSubscribers() {
            const checkboxes = document.querySelectorAll('.subscriber-checkbox:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        function deleteSubscriber(id) {
            if (confirm('Are you sure you want to delete this subscriber?')) {
                fetch('<?php echo base_url("newsletter/delete_subscriber"); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({id: id})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting subscriber: ' + data.message);
                    }
                });
            }
        }

        function resendConfirmation(id) {
            fetch('<?php echo base_url("newsletter/resend_confirmation"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id: id})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Confirmation email sent successfully!');
                } else {
                    alert('Error sending confirmation: ' + data.message);
                }
            });
        }

        function bulkDelete(ids) {
            fetch('<?php echo base_url("newsletter/bulk_delete_subscribers"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ids: ids})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting subscribers: ' + data.message);
                }
            });
        }

        function bulkResend(ids) {
            fetch('<?php echo base_url("newsletter/bulk_resend_confirmation"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ids: ids})
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            });
        }

        function exportSubscribers() {
            window.location.href = '<?php echo base_url("newsletter/export_subscribers"); ?>';
        }

        function exportSelected(ids) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo base_url("newsletter/export_subscribers"); ?>';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids';
            input.value = JSON.stringify(ids);
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</body>
</html>