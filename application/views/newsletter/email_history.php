<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>EMAIL HISTORY</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            EMAIL DELIVERY LOG
                            <small>Track all newsletter email deliveries and their status</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?php echo base_url('newsletter'); ?>" class="btn btn-default waves-effect">
                                    <i class="material-icons">arrow_back</i>
                                    <span>BACK TO NEWSLETTERS</span>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="<?php echo base_url('newsletter/email_history/export'); ?>">Export CSV</a></li>
                                    <li><a href="javascript:void(0);" onclick="clearHistory()">Clear History</a></li>
                                </ul>
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

                        <!-- Filter Options -->
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="header">
                                        <h2>FILTER OPTIONS</h2>
                                    </div>
                                    <div class="body">
                                        <form method="get" action="<?php echo base_url('newsletter/email_history'); ?>">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" name="search" class="form-control" 
                                                                   value="<?php echo $this->input->get('search'); ?>" placeholder="Search email...">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <select name="status" class="form-control show-tick">
                                                            <option value="">All Status</option>
                                                            <option value="sent" <?php echo ($this->input->get('status') == 'sent') ? 'selected' : ''; ?>>Sent</option>
                                                            <option value="failed" <?php echo ($this->input->get('status') == 'failed') ? 'selected' : ''; ?>>Failed</option>
                                                            <option value="pending" <?php echo ($this->input->get('status') == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="date" name="date_from" class="form-control" 
                                                                   value="<?php echo $this->input->get('date_from'); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="date" name="date_to" class="form-control" 
                                                                   value="<?php echo $this->input->get('date_to'); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <button type="submit" class="btn btn-primary waves-effect">
                                                        <i class="material-icons">search</i> FILTER
                                                    </button>
                                                    <a href="<?php echo base_url('newsletter/email_history'); ?>" class="btn btn-default waves-effect">
                                                        <i class="material-icons">clear</i> CLEAR
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>Newsletter</th>
                                        <th>Recipient</th>
                                        <th>Status</th>
                                        <th>Sent Date</th>
                                        <th>Error Message</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($email_history)): ?>
                                        <?php foreach($email_history as $email): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($email->newsletter_subject); ?></strong>
                                                    <br>
                                                    <small class="text-muted">ID: <?php echo $email->newsletter_id; ?></small>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($email->recipient_email); ?>
                                                    <?php if($email->recipient_name): ?>
                                                        <br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($email->recipient_name); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($email->status == 'sent'): ?>
                                                        <span class="label label-success">Sent</span>
                                                    <?php elseif($email->status == 'failed'): ?>
                                                        <span class="label label-danger">Failed</span>
                                                    <?php elseif($email->status == 'pending'): ?>
                                                        <span class="label label-warning">Pending</span>
                                                    <?php else: ?>
                                                        <span class="label label-default"><?php echo ucfirst($email->status); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($email->sent_at): ?>
                                                        <?php echo date('M j, Y g:i A', strtotime($email->sent_at)); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not sent</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($email->error_message): ?>
                                                        <span class="text-danger" title="<?php echo htmlspecialchars($email->error_message); ?>">
                                                            <?php echo substr(htmlspecialchars($email->error_message), 0, 50) . (strlen($email->error_message) > 50 ? '...' : ''); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <?php if($email->status == 'failed'): ?>
                                                            <a href="<?php echo base_url('newsletter/resend_email/' . $email->id); ?>" 
                                                               class="btn btn-xs btn-warning waves-effect" 
                                                               title="Resend Email"
                                                               onclick="return confirm('Are you sure you want to resend this email?')">
                                                                <i class="material-icons">refresh</i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="<?php echo base_url('newsletter/delete_email_log/' . $email->id); ?>" 
                                                           class="btn btn-xs btn-danger waves-effect" 
                                                           title="Delete Log"
                                                           onclick="return confirm('Are you sure you want to delete this log entry?')">
                                                            <i class="material-icons">delete</i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="alert alert-info">
                                                    <i class="material-icons">info</i>
                                                    No email history found.
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if(isset($pagination)): ?>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <?php echo $pagination; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function clearHistory() {
    if (confirm('Are you sure you want to clear all email history? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('newsletter/clear_email_history'); ?>';
    }
}
</script>