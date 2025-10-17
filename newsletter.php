<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Lighthouse Pillar - Lighthouse Global Missions</title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="/assets/images/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon/favicon.svg">
    <link rel="shortcut icon" href="/assets/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="Lgmissions">
    <link rel="manifest" href="/assets/images/favicon/site.webmanifest">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            color: #2d3748;
            background: linear-gradient(135deg, #1a365d 0%, #2d3748 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .subscription-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
        }

        .header {
            background: linear-gradient(135deg, #1a365d 0%, #3182ce 100%);
            color: white;
            padding: 4rem 3rem;
            text-align: center;
        }

        .lighthouse-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: #ffd700;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .header .subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            font-weight: 400;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .content {
            padding: 4rem 3rem;
        }

        .intro {
            text-align: left;
            margin-bottom: 4rem;
        }

        .intro h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 600;
            color: #1a365d;
            margin-bottom: 2rem;
            letter-spacing: -0.5px;
            text-align: center;
        }

        .pillar-content {
            display: grid;
            gap: 2.5rem;
            margin-bottom: 3rem;
        }

        .pillar-section {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 2.5rem;
            border-radius: 16px;
            border-left: 5px solid #3182ce;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .pillar-section p {
            font-size: 1.1rem;
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .pillar-section p:last-child {
            margin-bottom: 0;
        }

        .scripture-quote {
            background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            font-style: italic;
            font-size: 1.1rem;
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .scripture-quote::before {
            content: '"';
            font-size: 4rem;
            position: absolute;
            top: -10px;
            left: 20px;
            opacity: 0.3;
        }

        .benefits-section {
            background: linear-gradient(135deg, #ffd700 0%, #f6ad55 100%);
            padding: 2.5rem;
            border-radius: 16px;
            margin: 2rem 0;
            text-align: center;
        }

        .benefits-section h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #1a365d;
            margin-bottom: 1.5rem;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .benefit-item {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
        }

        .benefit-item i {
            font-size: 2rem;
            color: #3182ce;
            margin-bottom: 1rem;
        }

        .benefit-item h4 {
            font-weight: 600;
            color: #1a365d;
            margin-bottom: 0.5rem;
        }

        .form-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 20px;
            padding: 3rem;
            border: 2px solid #e2e8f0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #1a365d;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.8rem;
            font-size: 1rem;
        }

        .form-group input {
            width: 100%;
            padding: 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            font-family: 'Inter', sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
            transform: translateY(-2px);
        }

        .form-group input.error {
            border-color: #e53e3e;
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
            color: white;
            border: none;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(49, 130, 206, 0.4);
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

        .success-message {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: none;
            text-align: center;
        }

        .success-message i {
            margin-right: 0.5rem;
        }

        .error-alert {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: none;
            text-align: center;
        }

        .error-alert i {
            margin-right: 0.5rem;
        }

        .footer {
            background: #1a365d;
            color: white;
            padding: 2rem;
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header {
                padding: 3rem 2rem;
            }

            .header h1 {
                font-size: 2.2rem;
            }

            .content {
                padding: 3rem 2rem;
            }

            .intro h2 {
                font-size: 2rem;
            }

            .pillar-section {
                padding: 2rem;
            }

            .form-container {
                padding: 2rem;
            }

            .benefits-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="subscription-card">
            <div class="header">
                <div class="lighthouse-icon">
                    <img src="<?php echo base_url(); ?>assets/images/lgnewsletter_logo_256.png" alt="Lighthouse Icon" class="icon-image">
                </div>
                <h1>Become a Lighthouse Pillar</h1>
                <p class="subtitle">Lighthouse Global Missions</p>
            </div>

            <div class="content">
                <div class="intro">
                    <h2>GET CONNECTED, STAY UPDATED & GET INVOLVED</h2>
                    
                    <div class="pillar-content">
                        <div class="pillar-section">
                            <p>This is your invitation to join us at Lighthouse Global Missions, as a valued member of our community of people who are committed to standing with us in the work of the Lord, as we fulfill His call to reach our world with His Word; in order to bring many people into an experience of His power; helping them to discover their purpose and to fulfill their destinies in Christ.</p>
                        </div>

                        <div class="scripture-quote">
                            The one who is victorious I will make a pillar in the temple of my God
                            <br><strong>- Revelation 3:12 NIV</strong>
                        </div>

                        <div class="pillar-section">
                            <p>Paul wrote of "James, Peter, and John, who were known as pillars of the church" (See Galatians 2:9). These men were the sustaining force of the Church through their dedicated contributions. As a Lighthouse Pillar, you join a community of people who stand with us in prayer, in giving and in volunteering for this ministry as Lighthouse Global Missions. You also get to share in the blessings, graces and glory of God as He is revealing in our lives for our act of obedience to His call.</p>
                        </div>

                        <div class="pillar-section">
                            <p>Pillars are very vital elements of support which give form and structure to an architectural edifice. They are strategically positioned columns that bear the weight of a structure to sustain it in its purpose and function. Pillars play a key role in contributing to the overall stability and beauty of the structure. And God desires to have you as a vital pillar in His plan, supporting, upholding and causing His purpose to be actualized.</p>
                        </div>

                        <div class="benefits-section">
                            <h3>Why become a Lighthouse Pillar</h3>
                            <div class="benefits-grid">
                                <div class="benefit-item">
                                    <i class="fas fa-newspaper"></i>
                                    <h4>Ministry Updates</h4>
                                    <p>Stay informed about our global missions</p>
                                </div>
                                <div class="benefit-item">
                                   <i class="fas fa-eye"></i>
                                    <h4>Prophetic Words</h4>
                                    <p>Receive timely prophetic messages</p>
                                </div>
                                <div class="benefit-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <h4>Partner in Kingdom Missions</h4>
                                    <p>Give with purpose towards God's work</p>
                                </div>
                                <div class="benefit-item">
                                    <i class="fas fa-users"></i>
                                    <h4>Exclusive Events</h4>
                                    <p>Join special partners’ events in Germany</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-container">
                    <h3 class="form-title">Sign up for Lighthouse Global Missions' newsletter</h3>
                    
                    <div class="success-message" id="successMessage">
                        <i class="fas fa-check-circle"></i> Thank you for becoming a Lighthouse Pillar! Please check your email to confirm your subscription.
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
                            <span id="btnText">Submit</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="footer">
                <p>&copy; 2025 Lighthouse Global Missions. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        let isSubmitting = false; // Flag to prevent double submission
        
        document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Prevent double submission
            if (isSubmitting) {
                return;
            }
            
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
            
            // Set submission flag and show loading state
            isSubmitting = true;
            showLoading(true);
            
            // Submit form via AJAX
            const formData = new FormData();
            formData.append('first_name', firstName);
            formData.append('last_name', lastName);
            formData.append('email', email);
            
            // Post to CodeIgniter API controller to ensure pure JSON responses
            fetch('/newsletter_api/subscribe', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                isSubmitting = false; // Reset submission flag
                showLoading(false);
                
                if (data.success) {
                    showSuccess();
                    document.getElementById('subscriptionForm').reset();
                } else {
                    showError(data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                isSubmitting = false; // Reset submission flag
                showLoading(false);
                showError('Network error. Please check your connection and try again.');
            });
        });
        
        function clearErrors() {
            const errorMessages = document.querySelectorAll('.error-message');
            const inputs = document.querySelectorAll('input');
            
            errorMessages.forEach(msg => msg.style.display = 'none');
            inputs.forEach(input => input.classList.remove('error'));
            
            document.getElementById('successMessage').style.display = 'none';
            document.getElementById('errorAlert').style.display = 'none';
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
                btnText.textContent = 'Joining...';
                submitBtn.disabled = true;
            } else {
                loading.classList.remove('active');
                btnText.textContent = 'Submit';
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