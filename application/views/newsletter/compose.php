<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>COMPOSE NEWSLETTER</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?php echo isset($newsletter) ? 'EDIT NEWSLETTER' : 'CREATE NEW NEWSLETTER'; ?>
                            <small><?php echo isset($newsletter) ? 'Update your newsletter content' : 'Compose and send newsletter to your subscribers'; ?></small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?php echo base_url('newsletter'); ?>" class="btn btn-default waves-effect">
                                    <i class="material-icons">arrow_back</i>
                                    <span>BACK TO LIST</span>
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

                        <form method="post" action="<?php echo base_url('newsletter/compose' . (isset($newsletter) ? '/' . $newsletter->id : '')); ?>">
                            <div class="row clearfix">
                                <div class="col-sm-8">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" id="subject" name="subject" class="form-control" 
                                                   value="<?php echo isset($newsletter) ? htmlspecialchars($newsletter->subject) : set_value('subject'); ?>" required>
                                            <label class="form-label">Newsletter Subject</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="status" class="form-control show-tick">
                                                <option value="draft" <?php echo (isset($newsletter) && $newsletter->status == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                                <option value="ready" <?php echo (isset($newsletter) && $newsletter->status == 'ready') ? 'selected' : ''; ?>>Ready to Send</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" id="sender_name" name="sender_name" class="form-control" 
                                                   value="<?php echo isset($newsletter) ? htmlspecialchars($newsletter->sender_name) : (isset($settings['sender_name']) ? htmlspecialchars($settings['sender_name']) : ''); ?>" required>
                                            <label class="form-label">Sender Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="email" id="sender_email" name="sender_email" class="form-control" 
                                                   value="<?php echo isset($newsletter) ? htmlspecialchars($newsletter->sender_email) : (isset($settings['sender_email']) ? htmlspecialchars($settings['sender_email']) : ''); ?>" required>
                                            <label class="form-label">Sender Email</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="content">Newsletter Content</label>
                                        <textarea id="content" name="content" class="form-control editor" rows="15"><?php echo isset($newsletter) ? htmlspecialchars($newsletter->content) : set_value('content'); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>RECIPIENT OPTIONS</h2>
                                        </div>
                                        <div class="body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <input type="radio" id="all_subscribers" name="recipient_type" value="all" 
                                                               <?php echo (!isset($selected_recipient_type) || $selected_recipient_type === 'all') ? 'checked' : ''; ?> class="with-gap">
                                                        <label for="all_subscribers">Send to All Confirmed Subscribers</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <input type="radio" id="test_email" name="recipient_type" value="test" 
                                                               <?php echo (isset($selected_recipient_type) && $selected_recipient_type === 'test') ? 'checked' : ''; ?> class="with-gap">
                                                        <label for="test_email">Send Test Email</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="test_email_field" style="<?php echo (isset($selected_recipient_type) && $selected_recipient_type === 'test') ? 'display: block;' : 'display: none;'; ?> margin-top: 15px;">
                                                <div class="col-sm-12">
                                                    <label for="test_email_address" style="font-weight: 600; color: #444; margin-bottom: 5px; display: block;">
                                                        Test Email Address <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="email" id="test_email_address" name="test_email_address" class="form-control" 
                                                                   placeholder="Enter email to receive test message (e.g. name@domain.com)"
                                                                   value="<?php echo isset($test_email_address) ? htmlspecialchars($test_email_address) : ''; ?>">
                                                        </div>
                                                        <small class="text-muted" style="display: block; margin-top: 5px;">
                                                            <i class="material-icons" style="font-size: 14px; vertical-align: middle;">info</i>
                                                            The newsletter will only be sent to this email address for preview testing. It will NOT be sent to subscribers.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="submit" name="action" value="save" class="btn btn-primary waves-effect">
                                        <i class="material-icons">save</i>
                                        SAVE DRAFT
                                    </button>
                                    <?php if(!isset($newsletter) || $newsletter->status != 'sent'): ?>
                                        <button type="submit" name="action" value="send" class="btn btn-success waves-effect" 
                                                id="send_btn"
                                                onclick="return confirmSendNewsletter()">
                                            <i class="material-icons">send</i>
                                            SEND NOW
                                        </button>
                                    <?php endif; ?>
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
function confirmSendNewsletter() {
    var testRadio = document.getElementById('test_email');
    if (testRadio && testRadio.checked) {
        var testInput = document.getElementById('test_email_address');
        var email = testInput ? testInput.value.trim() : '';
        if (!email) {
            alert('Please enter a test email address.');
            if (testInput) testInput.focus();
            return false;
        }
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            alert('Please enter a valid test email address.');
            if (testInput) testInput.focus();
            return false;
        }
        return confirm('Send test newsletter to ' + email + '?');
    }
    return confirm('Are you sure you want to send this newsletter to ALL confirmed subscribers?');
}

(function() {
    function setupRecipientToggle() {
        var testRadio = document.getElementById('test_email');
        var allRadio = document.getElementById('all_subscribers');
        var testField = document.getElementById('test_email_field');
        var testInput = document.getElementById('test_email_address');

        function updateState() {
            if (!testRadio || !testField) return;
            if (testRadio.checked) {
                testField.style.display = 'block';
                if (testInput) testInput.setAttribute('required', 'required');
            } else {
                testField.style.display = 'none';
                if (testInput) testInput.removeAttribute('required');
            }
        }

        if (testRadio) {
            testRadio.addEventListener('change', updateState);
            testRadio.addEventListener('click', updateState);
        }
        if (allRadio) {
            allRadio.addEventListener('change', updateState);
            allRadio.addEventListener('click', updateState);
        }

        var testLabel = document.querySelector('label[for="test_email"]');
        var allLabel = document.querySelector('label[for="all_subscribers"]');
        if (testLabel) testLabel.addEventListener('click', function() { setTimeout(updateState, 50); });
        if (allLabel) allLabel.addEventListener('click', function() { setTimeout(updateState, 50); });

        updateState();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupRecipientToggle);
    } else {
        setupRecipientToggle();
    }
    window.addEventListener('load', setupRecipientToggle);
})();
</script>