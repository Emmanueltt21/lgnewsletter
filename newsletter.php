<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Subscription - Lighthouse Global Missions</title>
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
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .subscription-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
        }

        .header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .content {
            padding: 3rem 2rem;
        }

        .intro {
            text-align: center;
            margin-bottom: 3rem;
        }

        .intro h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            letter-spacing: -0.3px;
        }

        .intro p {
            font-size: 1rem;
            color: #6b7280;
            line-height: 1.7;
            max-width: 500px;
            margin: 0 auto;
        }

        .form-container {
            background: #f8fafc;
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1.2rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .loading {
            display: none;
        }

        .loading.active {
            display: inline-block;
            margin-right: 0.5rem;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff40;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .success-message, .error-alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: none;
        }

        .success-message {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .error-alert {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .footer {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header {
                padding: 2rem 1.5rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .content {
                padding: 2rem 1.5rem;
            }

            .intro h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="subscription-card">
            <div class="header">
                <h1><i class="fas fa-lighthouse"></i> Lighthouse Global Missions</h1>
                <p>Stay connected with our ministry</p>
            </div>

            <div class="content">
                <div class="intro">
                    <h2>GET CONNECTED, STAY UPDATED & GET INVOLVED</h2>
                    <p>Join our community of believers and stay updated with ministry news, prophetic messages, upcoming events, and opportunities to get involved in God's work around the world.</p>
                </div>

                <div class="form-container">
                    <div class="success-message" id="successMessage">
                        <i class="fas fa-check-circle"></i> Thank you for subscribing! Please check your email to confirm your subscription.
                    </div>

                    <div class="error-alert" id="errorAlert">
                        <i class="fas fa-exclamation-triangle"></i> <span id="errorText"></span>
                    </div>

                    <form id="subscriptionForm" method="POST" action="process_subscription.php">
                        <div class="form-group">
                            <label for="firstName">First Name *</label>
                            <input type="text" id="firstName" name="first_name" required>
                            <div class="error-message" id="firstNameError">Please enter your first name</div>
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last Name *</label>
                            <input type="text" id="lastName" name="last_name" required>
                            <div class="error-message" id="lastNameError">Please enter your last name</div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required>
                            <div class="error-message" id="emailError">Please enter a valid email address</div>
                        </div>

                        <button type="submit" class="submit-btn" id="submitBtn">
                            <div class="loading" id="loading">
                                <div class="spinner"></div>
                            </div>
                            <span id="btnText">Subscribe Now</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="footer">
                <p>&copy; 2024 Lighthouse Global Missions. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset previous errors
            clearErrors();
            
            // Get form data
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('email').value.trim();
            
            // Validate form
            let isValid = true;
            
            if (!firstName) {
                showFieldError('firstName', 'Please enter your first name');
                isValid = false;
            }
            
            if (!lastName) {
                showFieldError('lastName', 'Please enter your last name');
                isValid = false;
            }
            
            if (!email) {
                showFieldError('email', 'Please enter your email address');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showFieldError('email', 'Please enter a valid email address');
                isValid = false;
            }
            
            if (!isValid) {
                return;
            }
            
            // Show loading state
            showLoading(true);
            
            // Submit form via AJAX
            const formData = new FormData();
            formData.append('first_name', firstName);
            formData.append('last_name', lastName);
            formData.append('email', email);
            
            fetch('process_subscription.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                
                if (data.success) {
                    showSuccess();
                    document.getElementById('subscriptionForm').reset();
                } else {
                    showError(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                showLoading(false);
                showError('Network error. Please check your connection and try again.');
            });
        });
        
        function clearErrors() {
            const errorMessages = document.querySelectorAll('.error-message');
            const inputs = document.querySelectorAll('input');
            const alerts = document.querySelectorAll('.success-message, .error-alert');
            
            errorMessages.forEach(msg => msg.style.display = 'none');
            inputs.forEach(input => input.classList.remove('error'));
            alerts.forEach(alert => alert.style.display = 'none');
        }
        
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorMsg = document.getElementById(fieldId + 'Error');
            
            field.classList.add('error');
            errorMsg.textContent = message;
            errorMsg.style.display = 'block';
        }
        
        function showSuccess() {
            document.getElementById('successMessage').style.display = 'block';
            document.getElementById('successMessage').scrollIntoView({ behavior: 'smooth' });
        }
        
        function showError(message) {
            document.getElementById('errorText').textContent = message;
            document.getElementById('errorAlert').style.display = 'block';
            document.getElementById('errorAlert').scrollIntoView({ behavior: 'smooth' });
        }
        
        function showLoading(show) {
            const loading = document.getElementById('loading');
            const btnText = document.getElementById('btnText');
            const submitBtn = document.getElementById('submitBtn');
            
            if (show) {
                loading.classList.add('active');
                btnText.textContent = 'Subscribing...';
                submitBtn.disabled = true;
            } else {
                loading.classList.remove('active');
                btnText.textContent = 'Subscribe Now';
                submitBtn.disabled = false;
            }
        }
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</body>
</html>