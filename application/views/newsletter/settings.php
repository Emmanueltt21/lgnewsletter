<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>NEWSLETTER SETTINGS</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            NEWSLETTER CONFIGURATION
                            <small>Configure your newsletter settings and preferences</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?php echo base_url('newsletter'); ?>" class="btn btn-default waves-effect">
                                    <i class="material-icons">arrow_back</i>
                                    <span>BACK TO NEWSLETTERS</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(validation_errors()): ?>
                            <div class="alert alert-danger">
                                <strong>Validation Errors:</strong>
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?php echo base_url('newsletter/save_settings'); ?>">
                            <!-- Email Configuration -->
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>EMAIL CONFIGURATION</h2>
                                        </div>
                                        <div class="body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="email" id="from_email" name="from_email" class="form-control" 
                                                                   value="<?php echo isset($settings['from_email']) ? htmlspecialchars($settings['from_email']) : ''; ?>" required>
                                                            <label class="form-label">From Email Address</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" id="from_name" name="from_name" class="form-control" 
                                                                   value="<?php echo isset($settings['from_name']) ? htmlspecialchars($settings['from_name']) : ''; ?>" required>
                                                            <label class="form-label">From Name</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="email" id="reply_to" name="reply_to" class="form-control" 
                                                                   value="<?php echo isset($settings['reply_to']) ? htmlspecialchars($settings['reply_to']) : ''; ?>">
                                                            <label class="form-label">Reply-To Email (Optional)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" id="company_name" name="company_name" class="form-control" 
                                                                   value="<?php echo isset($settings['company_name']) ? htmlspecialchars($settings['company_name']) : ''; ?>">
                                                            <label class="form-label">Company Name</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SMTP Configuration -->
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>SMTP CONFIGURATION</h2>
                                        </div>
                                        <div class="body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" id="smtp_host" name="smtp_host" class="form-control" 
                                                                   value="<?php echo isset($settings['smtp_host']) ? htmlspecialchars($settings['smtp_host']) : ''; ?>">
                                                            <label class="form-label">SMTP Host</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="number" id="smtp_port" name="smtp_port" class="form-control" 
                                                                   value="<?php echo isset($settings['smtp_port']) ? $settings['smtp_port'] : '587'; ?>">
                                                            <label class="form-label">SMTP Port</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <select name="smtp_encryption" class="form-control show-tick">
                                                            <option value="">No Encryption</option>
                                                            <option value="tls" <?php echo (isset($settings['smtp_encryption']) && $settings['smtp_encryption'] == 'tls') ? 'selected' : ''; ?>>TLS</option>
                                                            <option value="ssl" <?php echo (isset($settings['smtp_encryption']) && $settings['smtp_encryption'] == 'ssl') ? 'selected' : ''; ?>>SSL</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" id="smtp_username" name="smtp_username" class="form-control" 
                                                                   value="<?php echo isset($settings['smtp_username']) ? htmlspecialchars($settings['smtp_username']) : ''; ?>">
                                                            <label class="form-label">SMTP Username</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="password" id="smtp_password" name="smtp_password" class="form-control" 
                                                                   value="<?php echo isset($settings['smtp_password']) ? htmlspecialchars($settings['smtp_password']) : ''; ?>">
                                                            <label class="form-label">SMTP Password</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Newsletter Options -->
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>NEWSLETTER OPTIONS</h2>
                                        </div>
                                        <div class="body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="double_opt_in" name="double_opt_in" value="1" 
                                                               <?php echo (isset($settings['double_opt_in']) && $settings['double_opt_in']) ? 'checked' : ''; ?> class="filled-in">
                                                        <label for="double_opt_in">Enable Double Opt-in</label>
                                                        <small class="help-block">Require subscribers to confirm their email address</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="track_opens" name="track_opens" value="1" 
                                                               <?php echo (isset($settings['track_opens']) && $settings['track_opens']) ? 'checked' : ''; ?> class="filled-in">
                                                        <label for="track_opens">Track Email Opens</label>
                                                        <small class="help-block">Track when subscribers open your newsletters</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="number" id="batch_size" name="batch_size" class="form-control" 
                                                                   value="<?php echo isset($settings['batch_size']) ? $settings['batch_size'] : '50'; ?>" min="1" max="1000">
                                                            <label class="form-label">Email Batch Size</label>
                                                        </div>
                                                        <small class="help-block">Number of emails to send per batch</small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="number" id="batch_delay" name="batch_delay" class="form-control" 
                                                                   value="<?php echo isset($settings['batch_delay']) ? $settings['batch_delay'] : '5'; ?>" min="0" max="60">
                                                            <label class="form-label">Batch Delay (seconds)</label>
                                                        </div>
                                                        <small class="help-block">Delay between email batches</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Unsubscribe Settings -->
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>UNSUBSCRIBE SETTINGS</h2>
                                        </div>
                                        <div class="body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="unsubscribe_text">Unsubscribe Text</label>
                                                        <textarea id="unsubscribe_text" name="unsubscribe_text" class="form-control" rows="3"><?php echo isset($settings['unsubscribe_text']) ? htmlspecialchars($settings['unsubscribe_text']) : 'If you no longer wish to receive these emails, you can unsubscribe here: {unsubscribe_link}'; ?></textarea>
                                                        <small class="help-block">Use {unsubscribe_link} placeholder for the unsubscribe URL</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="unsubscribe_success_message">Unsubscribe Success Message</label>
                                                        <textarea id="unsubscribe_success_message" name="unsubscribe_success_message" class="form-control" rows="2"><?php echo isset($settings['unsubscribe_success_message']) ? htmlspecialchars($settings['unsubscribe_success_message']) : 'You have been successfully unsubscribed from our newsletter.'; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">save</i>
                                        SAVE SETTINGS
                                    </button>
                                    <button type="button" class="btn btn-info waves-effect" onclick="testEmailSettings()">
                                        <i class="material-icons">email</i>
                                        TEST EMAIL SETTINGS
                                    </button>
                                    <a href="<?php echo base_url('newsletter'); ?>" class="btn btn-default waves-effect">
                                        <i class="material-icons">cancel</i>
                                        CANCEL
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function testEmailSettings() {
    var testEmail = prompt('Enter email address to send test email:');
    if (testEmail && testEmail.trim() !== '') {
        window.location.href = '<?php echo base_url('newsletter/test_email_settings'); ?>?email=' + encodeURIComponent(testEmail);
    }
}
</script>