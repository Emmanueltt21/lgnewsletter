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
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                                            <label for="content" style="margin: 0; font-size: 14px; font-weight: 600;">Newsletter Content</label>
                                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                                <button type="button" class="btn btn-xs bg-orange waves-effect" id="btn_insert_firstname" onclick="insertTag('{{first_name}}')" style="padding: 4px 10px;">
                                                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">person</i>
                                                    <span>Insert {{first_name}}</span>
                                                </button>
                                                <button type="button" class="btn btn-xs bg-orange waves-effect" id="btn_insert_lastname" onclick="insertTag('{{last_name}}')" style="padding: 4px 10px;">
                                                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">person_outline</i>
                                                    <span>Insert {{last_name}}</span>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-success waves-effect" id="btn_direct_upload_img" onclick="triggerDirectImageUpload()" style="padding: 4px 10px;">
                                                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">add_photo_alternate</i>
                                                    <span>Upload & Insert Image</span>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-primary waves-effect" id="btn_sample_template" onclick="loadSampleTemplate()" style="padding: 4px 10px;">
                                                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">format_shapes</i>
                                                    <span>Insert Sample Template</span>
                                                </button>
                                            </div>
                                            <input type="file" id="direct_image_file_input" accept="image/*" style="display: none;" onchange="handleDirectImageUpload(this)">
                                        </div>
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

function loadSampleTemplate() {
    var templateHtml = '<!-- Personal Greeting -->\n' +
'<p style="font-size: 16px; color: #2c3e50; line-height: 1.6; margin-bottom: 16px;">\n' +
'    Dear <strong>[FIRST_NAME]</strong>,\n' +
'</p>\n\n' +
'<!-- Intro Paragraph -->\n' +
'<p style="font-size: 15px; color: #4a5568; line-height: 1.7; margin-bottom: 20px;">\n' +
'    We pray this message finds you in good health and high spirits. As we step into this new season of ministry, we are thrilled to share what God has been doing across our communities, missions, and outreach programs. Your faithful partnership and prayers continue to bear fruit around the globe.\n' +
'</p>\n\n' +
'<!-- Highlight / Scripture Callout Box -->\n' +
'<div style="background-color: #f0f7ff; border-left: 4px solid #1da2f0; padding: 18px 22px; border-radius: 6px; margin: 25px 0;">\n' +
'    <p style="font-style: italic; color: #1a365d; font-size: 15px; line-height: 1.6; margin: 0 0 8px 0;">\n' +
'        "For where your treasure is, there your heart will be also. Let your light shine before others, that they may see your good deeds and glorify your Father in heaven."\n' +
'    </p>\n' +
'    <p style="font-size: 13px; font-weight: bold; color: #2b6cb0; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">\n' +
'        &mdash; Matthew 5:16 &amp; Luke 12:34\n' +
'    </p>\n' +
'</div>\n\n' +
'<!-- Section Heading -->\n' +
'<h2 style="font-size: 20px; color: #1a202c; border-bottom: 2px solid #edf2f7; padding-bottom: 8px; margin: 30px 0 16px 0;">\n' +
'    Highlights of the Month\n' +
'</h2>\n\n' +
'<!-- Feature Story Section -->\n' +
'<div style="background-color: #ffffff; border: 1px solid #edf2f7; border-radius: 8px; padding: 20px; margin-bottom: 24px;">\n' +
'    <h3 style="font-size: 17px; color: #2d3748; margin: 0 0 10px 0; font-weight: 600;">Community Outreach &amp; Food Drive</h3>\n' +
'    <p style="font-size: 14px; color: #4a5568; line-height: 1.6; margin: 0;">\n' +
'        Thanks to your generosity, our team was able to provide essential food supplies and spiritual encouragement to over 150 families this past week. Every meal shared was a tangible opportunity to demonstrate the unconditional love of Christ in action.\n' +
'    </p>\n' +
'</div>\n\n' +
'<!-- Key Bullet Points / Accomplishments -->\n' +
'<div style="background-color: #fafbfc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 25px;">\n' +
'    <h3 style="font-size: 16px; color: #2d3748; margin: 0 0 12px 0;">Key Ministry Updates</h3>\n' +
'    <ul style="margin: 0; padding-left: 20px; color: #4a5568; font-size: 14px; line-height: 1.8;">\n' +
'        <li><strong>Youth Mentorship Program:</strong> Over 40 youth enrolled in our weekly discipleship classes.</li>\n' +
'        <li><strong>Global Missions Expansion:</strong> New mission stations established in remote rural centers.</li>\n' +
'        <li><strong>Medical Outreach Week:</strong> Free health screenings scheduled for the upcoming weekend.</li>\n' +
'    </ul>\n' +
'</div>\n\n' +
'<!-- Announcement / Event Banner Box -->\n' +
'<div style="background: linear-gradient(135deg, #1da2f0 0%, #203550 100%); color: #ffffff; padding: 24px; border-radius: 8px; text-align: center; margin: 30px 0;">\n' +
'    <h3 style="font-size: 18px; color: #ffffff; margin: 0 0 10px 0; font-weight: bold;">\n' +
'        Upcoming Annual Thanksgiving &amp; Missions Conference\n' +
'    </h3>\n' +
'    <p style="font-size: 14px; color: #e2e8f0; line-height: 1.5; margin: 0 0 18px 0;">\n' +
'        Join us in person or online via livestream as we gather to celebrate God\'s faithfulness.\n' +
'    </p>\n' +
'    <a href="https://lighthouseglobalmissions.org" target="_blank" style="background-color: #ffffff; color: #1da2f0; padding: 12px 28px; border-radius: 25px; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">\n' +
'        RSVP / Learn More\n' +
'    </a>\n' +
'</div>\n\n' +
'<!-- Closing & Sign-off -->\n' +
'<p style="font-size: 15px; color: #4a5568; line-height: 1.6; margin-top: 25px;">\n' +
'    Thank you for standing with us as a vital pillar in God\'s kingdom work. Together, we continue to shine the light of the gospel far and wide.\n' +
'</p>\n' +
'<p style="font-size: 15px; color: #2d3748; margin-top: 15px; line-height: 1.5;">\n' +
'    Warm regards in Christ,<br>\n' +
'    <strong>The Lighthouse Global Missions Team</strong>\n' +
'</p>';

    var subjectInput = document.getElementById('subject');
    if (subjectInput && (!subjectInput.value || subjectInput.value.trim() === '')) {
        subjectInput.value = 'A New Season of Faith & Ministry Highlights';
        if (subjectInput.parentElement) {
            subjectInput.parentElement.classList.add('focused');
        }
    }

    if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
        var current = tinymce.get('content').getContent();
        if (current && current.trim().length > 0) {
            if (!confirm('This will replace the current content in the editor with the sample template. Do you want to continue?')) {
                return;
            }
        }
        tinymce.get('content').setContent(templateHtml);
    } else {
        var textarea = document.getElementById('content');
        if (textarea) {
            if (textarea.value && textarea.value.trim().length > 0) {
                if (!confirm('This will replace the current content in the editor with the sample template. Do you want to continue?')) {
                    return;
                }
            }
            textarea.value = templateHtml;
        }
    }
}

function triggerDirectImageUpload() {
    var input = document.getElementById('direct_image_file_input');
    if (input) {
        input.value = '';
        input.click();
    }
}

function handleDirectImageUpload(input) {
    if (!input || !input.files || input.files.length === 0) return;
    var file = input.files[0];

    var btn = document.getElementById('btn_direct_upload_img');
    var originalHtml = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="material-icons" style="font-size: 14px; vertical-align: middle;">hourglass_empty</i> <span>Uploading...</span>';
    }

    var formData = new FormData();
    formData.append('file', file);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo base_url("newsletter/upload_image"); ?>');

    xhr.onload = function() {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }

        if (xhr.status === 200) {
            var json;
            try {
                json = JSON.parse(xhr.responseText);
            } catch(e) {
                alert('Upload response error: ' + xhr.responseText);
                return;
            }

            if (!json || typeof json.location !== 'string') {
                alert('Upload error: ' + (json && (json.error || json.message) ? (json.error || json.message) : 'Invalid server response'));
                return;
            }

            var safeTitle = file.name ? file.name.replace(/"/g, '&quot;') : 'Newsletter Image';
            var imgTag = '<p style="text-align: center; margin: 20px 0;"><img src="' + json.location + '" alt="' + safeTitle + '" style="max-width: 100%; height: auto; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);" /></p><p></p>';

            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                tinymce.get('content').insertContent(imgTag);
            } else {
                var textarea = document.getElementById('content');
                if (textarea) {
                    textarea.value += '\n' + imgTag + '\n';
                }
            }
        } else {
            var errMsg = 'HTTP Error ' + xhr.status;
            try {
                var errJson = JSON.parse(xhr.responseText);
                if (errJson && (errJson.error || errJson.message)) errMsg = errJson.error || errJson.message;
            } catch(e) {}
            alert('Failed to upload image: ' + errMsg);
        }
    };

    xhr.onerror = function() {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
        alert('Network error while uploading image. Please check your connection and try again.');
    };

    xhr.send(formData);
}

function insertTag(tag) {
    if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
        tinymce.get('content').insertContent(tag);
    } else {
        var textarea = document.getElementById('content');
        if (textarea) {
            var startPos = textarea.selectionStart;
            var endPos = textarea.selectionEnd;
            if (startPos !== undefined && endPos !== undefined) {
                textarea.value = textarea.value.substring(0, startPos) + tag + textarea.value.substring(endPos, textarea.value.length);
                textarea.selectionStart = startPos + tag.length;
                textarea.selectionEnd = startPos + tag.length;
                textarea.focus();
            } else {
                textarea.value += tag;
            }
        }
    }
}
</script>