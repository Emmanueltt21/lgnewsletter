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

        .compose-form {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
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

        .editor-container {
            margin-bottom: 2rem;
        }

        .ck-editor__editable {
            min-height: 400px;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 2rem;
            border-top: 1px solid #e1e5e9;
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

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .preview-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin-top: 2rem;
            border: 2px dashed #dee2e6;
        }

        .preview-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
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
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
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
                    <li><a href="<?= base_url('newsletter/compose_newsletter') ?>" class="active"><i class="fas fa-edit"></i> Compose</a></li>
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
            <p class="page-subtitle">Create and send newsletters to your subscribers</p>
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

        <form method="post" class="compose-form" id="newsletterForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="sender_name">Sender Name</label>
                    <input type="text" id="sender_name" name="sender_name" class="form-control" 
                           value="<?= $newsletter ? $newsletter->sender_name : ($settings->sender_name ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="sender_email">Sender Email</label>
                    <input type="email" id="sender_email" name="sender_email" class="form-control" 
                           value="<?= $newsletter ? $newsletter->sender_email : ($settings->sender_email ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="subject">Subject Line</label>
                <input type="text" id="subject" name="subject" class="form-control" 
                       value="<?= $newsletter ? $newsletter->subject : '' ?>" 
                       placeholder="Enter newsletter subject..." required>
            </div>

            <div class="form-group editor-container">
                <label for="content">Newsletter Content</label>
                <textarea id="content" name="content" class="form-control"><?= $newsletter ? $newsletter->content : '' ?></textarea>
            </div>

            <div class="preview-section" id="previewSection" style="display: none;">
                <div class="preview-title">
                    <i class="fas fa-eye"></i> Preview
                </div>
                <div id="previewContent"></div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="togglePreview()">
                    <i class="fas fa-eye"></i> Preview
                </button>
                <button type="submit" name="action" value="draft" class="btn btn-secondary">
                    <i class="fas fa-save"></i> Save Draft
                </button>
                <button type="submit" name="action" value="send" class="btn btn-success" onclick="return confirmSend()">
                    <i class="fas fa-paper-plane"></i> Send Newsletter
                </button>
            </div>
        </form>
    </main>

    <script>
        let editor;
        
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'imageUpload', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                }
            })
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });

        function togglePreview() {
            const previewSection = document.getElementById('previewSection');
            const previewContent = document.getElementById('previewContent');
            
            if (previewSection.style.display === 'none') {
                const subject = document.getElementById('subject').value;
                const content = editor.getData();
                
                previewContent.innerHTML = `
                    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <h2 style="color: #333; margin-bottom: 1rem;">${subject}</h2>
                        <div style="line-height: 1.6; color: #555;">${content}</div>
                    </div>
                `;
                previewSection.style.display = 'block';
            } else {
                previewSection.style.display = 'none';
            }
        }

        function confirmSend() {
            const subject = document.getElementById('subject').value;
            return confirm(`Are you sure you want to send the newsletter "${subject}" to all confirmed subscribers? This action cannot be undone.`);
        }

        // Auto-save draft every 2 minutes
        setInterval(function() {
            if (editor && document.getElementById('subject').value) {
                const formData = new FormData();
                formData.append('subject', document.getElementById('subject').value);
                formData.append('content', editor.getData());
                formData.append('sender_name', document.getElementById('sender_name').value);
                formData.append('sender_email', document.getElementById('sender_email').value);
                formData.append('action', 'draft');
                
                fetch('<?= base_url('newsletter/compose_newsletter') ?>', {
                    method: 'POST',
                    body: formData
                });
            }
        }, 120000); // 2 minutes
    </script>
</body>
</html>