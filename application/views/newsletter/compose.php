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

                        <form method="post" action="<?php echo isset($newsletter) ? base_url('newsletter/update/' . $newsletter->id) : base_url('newsletter/create'); ?>">
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
                                        <textarea id="content" name="content" class="form-control" rows="15" required><?php echo isset($newsletter) ? htmlspecialchars($newsletter->content) : set_value('content'); ?></textarea>
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
                                                               <?php echo (!isset($newsletter) || $newsletter->recipient_type == 'all') ? 'checked' : ''; ?> class="with-gap">
                                                        <label for="all_subscribers">Send to All Confirmed Subscribers</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <input type="radio" id="test_email" name="recipient_type" value="test" 
                                                               <?php echo (isset($newsletter) && $newsletter->recipient_type == 'test') ? 'checked' : ''; ?> class="with-gap">
                                                        <label for="test_email">Send Test Email</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="test_email_field" style="display: none;">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="email" id="test_email_address" name="test_email_address" class="form-control" 
                                                                   value="<?php echo isset($newsletter) ? htmlspecialchars($newsletter->test_email_address) : ''; ?>">
                                                            <label class="form-label">Test Email Address</label>
                                                        </div>
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
                                                onclick="return confirm('Are you sure you want to send this newsletter?')">
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
$(document).ready(function() {
    // Initialize CKEditor for content
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('content', {
            height: 300,
            toolbar: [
                { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
                { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
                { name: 'insert', items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
                '/',
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
            ]
        });
    }

    // Handle recipient type change
    $('input[name="recipient_type"]').change(function() {
        if ($(this).val() === 'test') {
            $('#test_email_field').show();
            $('#test_email_address').attr('required', true);
        } else {
            $('#test_email_field').hide();
            $('#test_email_address').attr('required', false);
        }
    });

    // Initialize on page load
    if ($('input[name="recipient_type"]:checked').val() === 'test') {
        $('#test_email_field').show();
        $('#test_email_address').attr('required', true);
    }
});
</script>