<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Newsletter Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
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

        .settings-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
        }

        .settings-nav {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 0;
            height: fit-content;
        }

        .settings-nav-item {
            display: block;
            padding: 1rem 1.5rem;
            color: #666;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .settings-nav-item:hover,
        .settings-nav-item.active {
            background: #f8f9fa;
            color: #667eea;
            border-left-color: #667eea;
        }

        .settings-nav-item i {
            margin-right: 0.75rem;
            width: 20px;
        }

        .settings-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .settings-section {
            display: none;
            padding: 2rem;
        }

        .settings-section.active {
            display: block;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control[type="number"] {
            max-width: 200px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .form-check input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #667eea;
        }

        .btn {
            padding: 0.75rem 2rem;
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

        .help-text {
            font-size: 0.875rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .template-preview {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .template-preview h4 {
            margin-bottom: 1rem;
            color: #333;
        }

        .editor-container {
            margin-bottom: 1.5rem;
        }

        .ck-editor__editable {
            min-height: 300px;
        }

        @media (max-width: 768px) {
            .settings-container {
                grid-template-columns: 1fr;
            }
            
            .settings-nav {
                display: flex;
                overflow-x: auto;
                padding: 1rem;
            }
            
            .settings-nav-item {
                white-space: nowrap;
                border-left: none;
                border-bottom: 3px solid transparent;
            }
            
            .settings-nav-item:hover,
            .settings-nav-item.active {
                border-left: none;
                border-bottom-color: #667eea;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .nav-menu {
                display: none;
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
                    <li><a href="<?= base_url('newsletter/settings') ?>" class="active"><i class="fas fa-cog"></i> Settings</a></li>
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
            <p class="page-subtitle">Configure email settings and templates</p>
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

        <div class="settings-container">
            <nav class="settings-nav">
                <a href="#email-settings" class="settings-nav-item active" onclick="showSection('email-settings')">
                    <i class="fas fa-envelope"></i> Email Settings
                </a>
                <a href="#smtp-settings" class="settings-nav-item" onclick="showSection('smtp-settings')">
                    <i class="fas fa-server"></i> SMTP Settings
                </a>
                <a href="#templates" class="settings-nav-item" onclick="showSection('templates')">
                    <i class="fas fa-file-alt"></i> Email Templates
                </a>
                <a href="#general" class="settings-nav-item" onclick="showSection('general')">
                    <i class="fas fa-cog"></i> General Settings
                </a>
            </nav>

            <div class="settings-content">
                <form method="post" id="settingsForm">
                    <!-- Email Settings Section -->
                    <div id="email-settings" class="settings-section active">
                        <h2 class="section-title">Email Settings</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="sender_name">Default Sender Name</label>
                                <input type="text" id="sender_name" name="sender_name" class="form-control" 
                                       value="<?= $settings->sender_name ?? '' ?>" required>
                                <div class="help-text">This name will appear as the sender in emails</div>
                            </div>
                            <div class="form-group">
                                <label for="sender_email">Default Sender Email</label>
                                <input type="email" id="sender_email" name="sender_email" class="form-control" 
                                       value="<?= $settings->sender_email ?? '' ?>" required>
                                <div class="help-text">This email will be used as the from address</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="reply_to_email">Reply-To Email</label>
                                <input type="email" id="reply_to_email" name="reply_to_email" class="form-control" 
                                       value="<?= $settings->reply_to_email ?? '' ?>">
                                <div class="help-text">Where replies should be sent (optional)</div>
                            </div>
                            <div class="form-group">
                                <label for="company_name">Company/Organization Name</label>
                                <input type="text" id="company_name" name="company_name" class="form-control" 
                                       value="<?= $settings->company_name ?? '' ?>">
                                <div class="help-text">Used in email footers and unsubscribe pages</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company_address">Company Address</label>
                            <textarea id="company_address" name="company_address" class="form-control" rows="3"><?= $settings->company_address ?? '' ?></textarea>
                            <div class="help-text">Physical address for compliance (required for commercial emails)</div>
                        </div>
                    </div>

                    <!-- SMTP Settings Section -->
                    <div id="smtp-settings" class="settings-section">
                        <h2 class="section-title">SMTP Settings</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="smtp_host">SMTP Host</label>
                                <input type="text" id="smtp_host" name="smtp_host" class="form-control" 
                                       value="<?= $settings->smtp_host ?? '' ?>" placeholder="smtp.gmail.com">
                                <div class="help-text">Your SMTP server hostname</div>
                            </div>
                            <div class="form-group">
                                <label for="smtp_port">SMTP Port</label>
                                <input type="number" id="smtp_port" name="smtp_port" class="form-control" 
                                       value="<?= $settings->smtp_port ?? '587' ?>" placeholder="587">
                                <div class="help-text">Usually 587 for TLS or 465 for SSL</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="smtp_username">SMTP Username</label>
                                <input type="text" id="smtp_username" name="smtp_username" class="form-control" 
                                       value="<?= $settings->smtp_username ?? '' ?>">
                                <div class="help-text">Your SMTP authentication username</div>
                            </div>
                            <div class="form-group">
                                <label for="smtp_password">SMTP Password</label>
                                <input type="password" id="smtp_password" name="smtp_password" class="form-control" 
                                       value="<?= $settings->smtp_password ?? '' ?>">
                                <div class="help-text">Your SMTP authentication password</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="smtp_encryption">Encryption</label>
                            <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                                <option value="tls" <?= ($settings->smtp_encryption ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                <option value="ssl" <?= ($settings->smtp_encryption ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                <option value="" <?= empty($settings->smtp_encryption) ? 'selected' : '' ?>>None</option>
                            </select>
                            <div class="help-text">Encryption method for secure connection</div>
                        </div>
                    </div>

                    <!-- Email Templates Section -->
                    <div id="templates" class="settings-section">
                        <h2 class="section-title">Email Templates</h2>
                        
                        <div class="form-group">
                            <label for="welcome_template">Welcome Email Template</label>
                            <div class="editor-container">
                                <textarea id="welcome_template" name="welcome_template" class="form-control"><?= $settings->welcome_template ?? '' ?></textarea>
                            </div>
                            <div class="help-text">Template for welcome emails sent to new subscribers. Use {{first_name}}, {{last_name}}, {{email}} as placeholders.</div>
                        </div>

                        <div class="form-group">
                            <label for="confirmation_template">Confirmation Email Template</label>
                            <div class="editor-container">
                                <textarea id="confirmation_template" name="confirmation_template" class="form-control"><?= $settings->confirmation_template ?? '' ?></textarea>
                            </div>
                            <div class="help-text">Template for email confirmation. Use {{confirmation_link}} as placeholder for the confirmation link.</div>
                        </div>

                        <div class="form-group">
                            <label for="unsubscribe_template">Unsubscribe Confirmation Template</label>
                            <div class="editor-container">
                                <textarea id="unsubscribe_template" name="unsubscribe_template" class="form-control"><?= $settings->unsubscribe_template ?? '' ?></textarea>
                            </div>
                            <div class="help-text">Template for unsubscribe confirmation emails.</div>
                        </div>
                    </div>

                    <!-- General Settings Section -->
                    <div id="general" class="settings-section">
                        <h2 class="section-title">General Settings</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="emails_per_batch">Emails per Batch</label>
                                <input type="number" id="emails_per_batch" name="emails_per_batch" class="form-control" 
                                       value="<?= $settings->emails_per_batch ?? '50' ?>" min="1" max="1000">
                                <div class="help-text">Number of emails to send in each batch (to avoid server limits)</div>
                            </div>
                            <div class="form-group">
                                <label for="batch_delay">Batch Delay (seconds)</label>
                                <input type="number" id="batch_delay" name="batch_delay" class="form-control" 
                                       value="<?= $settings->batch_delay ?? '5' ?>" min="0" max="300">
                                <div class="help-text">Delay between batches to prevent rate limiting</div>
                            </div>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" id="double_opt_in" name="double_opt_in" value="1" 
                                   <?= ($settings->double_opt_in ?? '1') === '1' ? 'checked' : '' ?>>
                            <label for="double_opt_in">Enable Double Opt-in</label>
                        </div>
                        <div class="help-text">Require email confirmation before adding subscribers</div>

                        <div class="form-check">
                            <input type="checkbox" id="track_opens" name="track_opens" value="1" 
                                   <?= ($settings->track_opens ?? '0') === '1' ? 'checked' : '' ?>>
                            <label for="track_opens">Track Email Opens</label>
                        </div>
                        <div class="help-text">Add tracking pixels to monitor email opens</div>

                        <div class="form-check">
                            <input type="checkbox" id="track_clicks" name="track_clicks" value="1" 
                                   <?= ($settings->track_clicks ?? '0') === '1' ? 'checked' : '' ?>>
                            <label for="track_clicks">Track Link Clicks</label>
                        </div>
                        <div class="help-text">Track clicks on links in newsletters</div>
                    </div>

                    <div style="padding: 2rem; border-top: 1px solid #dee2e6; background: #f8f9fa;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="testEmailSettings()">
                            <i class="fas fa-paper-plane"></i> Test Email Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        let welcomeEditor, confirmationEditor, unsubscribeEditor;

        // Initialize CKEditor for templates
        ClassicEditor.create(document.querySelector('#welcome_template'))
            .then(editor => { welcomeEditor = editor; })
            .catch(error => console.error(error));

        ClassicEditor.create(document.querySelector('#confirmation_template'))
            .then(editor => { confirmationEditor = editor; })
            .catch(error => console.error(error));

        ClassicEditor.create(document.querySelector('#unsubscribe_template'))
            .then(editor => { unsubscribeEditor = editor; })
            .catch(error => console.error(error));

        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.settings-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all nav items
            document.querySelectorAll('.settings-nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            
            // Add active class to clicked nav item
            document.querySelector(`[href="#${sectionId}"]`).classList.add('active');
        }

        function testEmailSettings() {
            const formData = new FormData();
            formData.append('test_email', '1');
            formData.append('smtp_host', document.getElementById('smtp_host').value);
            formData.append('smtp_port', document.getElementById('smtp_port').value);
            formData.append('smtp_username', document.getElementById('smtp_username').value);
            formData.append('smtp_password', document.getElementById('smtp_password').value);
            formData.append('smtp_encryption', document.getElementById('smtp_encryption').value);
            formData.append('sender_email', document.getElementById('sender_email').value);
            formData.append('sender_name', document.getElementById('sender_name').value);

            fetch('<?= base_url('newsletter/test_email_settings') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Test email sent successfully! Check your inbox.');
                } else {
                    alert('Test email failed: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error testing email settings: ' + error.message);
            });
        }

        // Handle form submission
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            // Update editor content before submission
            if (welcomeEditor) {
                document.getElementById('welcome_template').value = welcomeEditor.getData();
            }
            if (confirmationEditor) {
                document.getElementById('confirmation_template').value = confirmationEditor.getData();
            }
            if (unsubscribeEditor) {
                document.getElementById('unsubscribe_template').value = unsubscribeEditor.getData();
            }
        });
    </script>
</body>
</html>