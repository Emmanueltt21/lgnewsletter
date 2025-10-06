<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Newsletter Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .newsletters-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .newsletters-header {
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .newsletters-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .newsletters-table {
            width: 100%;
            border-collapse: collapse;
        }

        .newsletters-table th,
        .newsletters-table td {
            padding: 1rem 2rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .newsletters-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .newsletters-table tbody tr:hover {
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

        .status-draft {
            background: #ffeaa7;
            color: #d63031;
        }

        .status-sent {
            background: #00b894;
            color: white;
        }

        .status-sending {
            background: #74b9ff;
            color: white;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
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

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .nav-menu {
                display: none;
            }
            
            .newsletters-table {
                font-size: 0.875rem;
            }
            
            .newsletters-table th,
            .newsletters-table td {
                padding: 0.75rem 1rem;
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
                    <li><a href="<?= base_url('newsletter/newsletters') ?>" class="active"><i class="fas fa-newspaper"></i> Newsletters</a></li>
                    <li><a href="<?= base_url('newsletter/compose_newsletter') ?>"><i class="fas fa-edit"></i> Compose</a></li>
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
            <div>
                <h1 class="page-title"><?= $title ?></h1>
                <p class="page-subtitle">Manage your newsletter campaigns</p>
            </div>
            <a href="<?= base_url('newsletter/compose_newsletter') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Newsletter
            </a>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="newsletters-container">
            <div class="newsletters-header">
                <div class="newsletters-title">
                    <i class="fas fa-newspaper"></i> All Newsletters
                </div>
            </div>

            <?php if (empty($newsletters)): ?>
                <div class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <h3>No newsletters yet</h3>
                    <p>Create your first newsletter to get started</p>
                    <a href="<?= base_url('newsletter/compose_newsletter') ?>" class="btn btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Create Newsletter
                    </a>
                </div>
            <?php else: ?>
                <table class="newsletters-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Recipients</th>
                            <th>Created</th>
                            <th>Sent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($newsletters as $newsletter): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($newsletter->subject) ?></strong>
                                    <br>
                                    <small style="color: #666;">by <?= htmlspecialchars($newsletter->created_by_username ?? 'Unknown') ?></small>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $newsletter->status ?>">
                                        <?= ucfirst($newsletter->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= number_format($newsletter->recipients_count ?? 0) ?>
                                </td>
                                <td>
                                    <?= date('M j, Y', strtotime($newsletter->created_at)) ?>
                                    <br>
                                    <small style="color: #666;"><?= date('g:i A', strtotime($newsletter->created_at)) ?></small>
                                </td>
                                <td>
                                    <?php if ($newsletter->sent_at): ?>
                                        <?= date('M j, Y', strtotime($newsletter->sent_at)) ?>
                                        <br>
                                        <small style="color: #666;"><?= date('g:i A', strtotime($newsletter->sent_at)) ?></small>
                                    <?php else: ?>
                                        <span style="color: #999;">Not sent</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <?php if ($newsletter->status === 'draft'): ?>
                                            <a href="<?= base_url('newsletter/compose_newsletter/' . $newsletter->id) ?>" 
                                               class="btn btn-secondary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="sendNewsletter(<?= $newsletter->id ?>)" 
                                                    class="btn btn-success btn-sm" title="Send">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($newsletter->status === 'sent'): ?>
                                            <a href="<?= base_url('newsletter/newsletter_analytics/' . $newsletter->id) ?>" 
                                               class="btn btn-secondary btn-sm" title="Analytics">
                                                <i class="fas fa-chart-bar"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <button onclick="deleteNewsletter(<?= $newsletter->id ?>)" 
                                                class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function sendNewsletter(id) {
            if (confirm('Are you sure you want to send this newsletter to all confirmed subscribers? This action cannot be undone.')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url('newsletter/send_newsletter') ?>/' + id;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'send_newsletter';
                csrfInput.value = '1';
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function deleteNewsletter(id) {
            if (confirm('Are you sure you want to delete this newsletter? This action cannot be undone.')) {
                window.location.href = '<?= base_url('newsletter/delete_newsletter') ?>/' + id;
            }
        }
    </script>
</body>
</html>