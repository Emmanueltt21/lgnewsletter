<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Light House Newsletter | Admin Login</title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo asset_url('images/favicon.ico'); ?>" type="image/x-icon">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo asset_url('plugins/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Custom Css -->
    <link href="<?php echo asset_url('css/style.css'); ?>" rel="stylesheet">
    
    <style>
        .lighthouse-login-page {
            background: url('uploads/thumbnails/newsletter_background_login.png') center center no-repeat;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        .lighthouse-login-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #4e2f21;
            opacity: 0.7;
            z-index: 0;
        }
        
        .lighthouse-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            padding: 0;
            max-width: 420px;
            width: 90%;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        
        .lighthouse-header {
            background: linear-gradient(135deg, #4e2f21 0%, #3d241a 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .lighthouse-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        
        .lighthouse-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 8px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .lighthouse-subtitle {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
            margin: 0;
        }
        
        .lighthouse-form {
            padding: 40px 30px;
        }
        
        .lighthouse-welcome {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .lighthouse-welcome h3 {
            color: #2c3e50;
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }
        
        .lighthouse-welcome p {
            color: #7f8c8d;
            font-size: 14px;
            margin: 0;
        }
        
        .lighthouse-input-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .lighthouse-input {
            width: 100%;
            padding: 15px 20px 15px 50px;
            border: 2px solid #e8ecf0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        
        .lighthouse-input:focus {
            outline: none;
            border-color: #4e2f21;
            background: white;
            box-shadow: 0 0 0 3px rgba(78, 47, 33, 0.1);
        }
        
        .lighthouse-input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
            font-size: 20px;
            transition: color 0.3s ease;
        }
        
        .lighthouse-input:focus + .lighthouse-input-icon {
            color: #4e2f21;
        }
        
        .lighthouse-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4e2f21 0%, #3d241a 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }
        
        .lighthouse-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 47, 33, 0.3);
        }
        
        .lighthouse-btn:active {
            transform: translateY(0);
        }
        
        .lighthouse-footer {
            text-align: center;
            padding: 20px 30px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        
        .lighthouse-footer p {
            margin: 0;
            color: #6c757d;
            font-size: 12px;
        }
        
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
            border: none;
            padding: 12px 15px;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            color: white;
        }
        
        @media (max-width: 480px) {
            .lighthouse-container {
                margin: 20px;
                width: calc(100% - 40px);
            }
            
            .lighthouse-header {
                padding: 30px 20px;
            }
            
            .lighthouse-form {
                padding: 30px 20px;
            }
            
            .lighthouse-title {
                font-size: 24px;
            }
        }
    </style>
    
<script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>
</head>

<body class="lighthouse-login-page">
    <div class="lighthouse-container">
        <div class="lighthouse-header">
            <div class="lighthouse-icon">
                <i class="material-icons">lightbulb_outline</i>
            </div>
            <h1 class="lighthouse-title">Light House Newsletter</h1>
            <p class="lighthouse-subtitle">Guiding Light for Your Community</p>
        </div>
        
        <div class="lighthouse-form">
            <div class="lighthouse-welcome">
                <h3>Welcome Back</h3>
                <p>Please sign in to access the admin dashboard</p>
            </div>

            <form id="log_in" method="POST" action="<?php echo base_url(); ?>authenticate">
                <?php $this->load->helper('form'); ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if($error)
                {
                    ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $error; ?>
                    </div>
                <?php }
                $success = $this->session->flashdata('success');
                if($success)
                {
                    ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $success; ?>
                    </div>
                <?php } ?>
                
                <div class="lighthouse-input-group">
                    <input type="email" class="lighthouse-input" name="email" placeholder="Email Address" required autofocus>
                    <i class="material-icons lighthouse-input-icon">person</i>
                </div>
                
                <div class="lighthouse-input-group">
                    <input type="password" class="lighthouse-input" name="password" placeholder="Password" required>
                    <i class="material-icons lighthouse-input-icon">lock</i>
                </div>
                
                <button class="lighthouse-btn" type="submit">Sign In</button>
            </form>
        </div>
        
        <div class="lighthouse-footer">
            <p>&copy; 2024 Light House Newsletter. Spreading God's light in our community.</p>
        </div>
    </div>

    <!-- CORE PLUGIN JS -->
    <script src="<?php echo asset_url('plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo asset_url('plugins/bootstrap/js/bootstrap.js'); ?>"></script>
    <script src="<?php echo asset_url('plugins/jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- LAYOUT JS -->
    <script src="<?php echo asset_url('js/demo.js'); ?>"></script>

</body>

</html>
