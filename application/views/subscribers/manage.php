<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>MANAGE SUBSCRIBERS</h2>
        </div>

        <?php
            $total_subscribers = isset($stats['total']) ? $stats['total'] : (isset($stats['all']) ? $stats['all'] : (isset($stats['total_subscribers']) ? $stats['total_subscribers'] : 0));
            $confirmed_subscribers = isset($stats['confirmed']) ? $stats['confirmed'] : (isset($stats['confirmed_subscribers']) ? $stats['confirmed_subscribers'] : 0);
            $pending_subscribers = isset($stats['pending']) ? $stats['pending'] : (isset($stats['pending_confirmations']) ? $stats['pending_confirmations'] : 0);
            $unsubscribed_subscribers = isset($stats['unsubscribed']) ? $stats['unsubscribed'] : (isset($stats['unsubscribed_subscribers']) ? $stats['unsubscribed_subscribers'] : 0);
        ?>

        <!-- Statistics Cards -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo base_url('subscribers'); ?>" style="display: block; text-decoration: none; color: inherit;">
                    <div class="info-box bg-purple hover-expand-effect" style="cursor: pointer;">
                        <div class="icon">
                            <i class="material-icons">people</i>
                        </div>
                        <div class="content">
                            <div class="text">Total Subscribers</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total_subscribers; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $total_subscribers; ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo base_url('subscribers?status=confirmed'); ?>" style="display: block; text-decoration: none; color: inherit;">
                    <div class="info-box bg-green hover-expand-effect" style="cursor: pointer;">
                        <div class="icon">
                            <i class="material-icons">check_circle</i>
                        </div>
                        <div class="content">
                            <div class="text">Confirmed</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $confirmed_subscribers; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $confirmed_subscribers; ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo base_url('subscribers?status=pending'); ?>" style="display: block; text-decoration: none; color: inherit;">
                    <div class="info-box bg-orange hover-expand-effect" style="cursor: pointer;">
                        <div class="icon">
                            <i class="material-icons">schedule</i>
                        </div>
                        <div class="content">
                            <div class="text">Pending</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $pending_subscribers; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $pending_subscribers; ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo base_url('subscribers?status=unsubscribed'); ?>" style="display: block; text-decoration: none; color: inherit;">
                    <div class="info-box bg-red hover-expand-effect" style="cursor: pointer;">
                        <div class="icon">
                            <i class="material-icons">unsubscribe</i>
                        </div>
                        <div class="content">
                            <div class="text">Unsubscribed</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $unsubscribed_subscribers; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $unsubscribed_subscribers; ?></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>FILTER & SEARCH</h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);" onclick="exportSubscribers()">Export All Subscribers</a></li>
                                    <li><a href="javascript:void(0);" onclick="selectAll()">Select All</a></li>
                                    <li><a href="javascript:void(0);" onclick="deselectAll()">Deselect All</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <form method="GET" action="<?php echo base_url('subscribers'); ?>">
                            <div class="row clearfix">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="search" 
                                                   value="<?php echo htmlspecialchars($this->input->get('search') ?? ''); ?>"
                                                   placeholder="Search by name or email...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <select class="form-control show-tick" name="status">
                                            <option value="all" <?php echo ($this->input->get('status') == 'all') ? 'selected' : ''; ?>>All Status</option>
                                            <option value="confirmed" <?php echo ($this->input->get('status') == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="pending" <?php echo ($this->input->get('status') == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="unsubscribed" <?php echo ($this->input->get('status') == 'unsubscribed') ? 'selected' : ''; ?>>Unsubscribed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <select class="form-control show-tick" name="date_range">
                                            <option value="all" <?php echo ($this->input->get('date_range') == 'all') ? 'selected' : ''; ?>>All Time</option>
                                            <option value="today" <?php echo ($this->input->get('date_range') == 'today') ? 'selected' : ''; ?>>Today</option>
                                            <option value="week" <?php echo ($this->input->get('date_range') == 'week') ? 'selected' : ''; ?>>This Week</option>
                                            <option value="month" <?php echo ($this->input->get('date_range') == 'month') ? 'selected' : ''; ?>>This Month</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-primary waves-effect">
                                        <i class="material-icons">search</i> FILTER
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <!-- <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>BULK ACTIONS</h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);" onclick="exportSubscribers()">Export All Subscribers</a></li>
                                    <li><a href="javascript:void(0);" onclick="selectAll()">Select All</a></li>
                                    <li><a href="javascript:void(0);" onclick="deselectAll()">Deselect All</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" id="bulk-action">
                                    <option value="">Select Bulk Action</option>
                                    <option value="delete">Delete Selected</option>
                                    <option value="resend">Resend Confirmation</option>
                                    <option value="export">Export Selected</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <button type="button" class="btn btn-warning waves-effect" onclick="executeBulkAction()">
                                    <i class="material-icons">play_arrow</i> EXECUTE
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Subscribers Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>SUBSCRIBERS LIST</h2>
                        <div class="pull-right">
                            <button type="button" class="btn btn-success waves-effect" data-toggle="modal" data-target="#addSubscriberModal">
                                <i class="material-icons">person_add</i> ADD SUBSCRIBER
                            </button>
                        </div>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);" onclick="refreshTable()">Refresh</a></li>
                                    <li><a href="javascript:void(0);" onclick="exportSubscribers()">Export</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <?php if (!empty($subscribers)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all" class="filled-in" /></th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Subscribed Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($subscribers as $subscriber): ?>
                                            <tr>
                                                <td><input type="checkbox" class="subscriber-checkbox filled-in" value="<?php echo $subscriber->id; ?>" /></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($subscriber->first_name . ' ' . $subscriber->last_name); ?></strong>
                                                </td>
                                                <td><?php echo htmlspecialchars($subscriber->email); ?></td>
                                                <td>
                                                    <?php if ($subscriber->status == 'confirmed'): ?>
                                                        <span class="label label-success">Confirmed</span>
                                                    <?php elseif ($subscriber->status == 'pending'): ?>
                                                        <span class="label label-warning">Pending</span>
                                                    <?php else: ?>
                                                        <span class="label label-danger">Unsubscribed</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('M j, Y', strtotime($subscriber->created_at)); ?></td>
                                                <td>
                                                    <?php if ($subscriber->status == 'pending'): ?>
                                                        <button type="button" class="btn btn-xs btn-info waves-effect" title="Resend Confirmation" onclick="resendConfirmation(<?php echo $subscriber->id; ?>)">
                                                            <i class="material-icons">send</i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-xs btn-danger waves-effect" onclick="deleteSubscriber(<?php echo $subscriber->id; ?>)">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <strong>No subscribers found!</strong> No subscribers match your current filters.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Add Subscriber Modal -->
    <div class="modal fade" id="addSubscriberModal" tabindex="-1" role="dialog" aria-labelledby="addSubscriberModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addSubscriberModalLabel">Add Subscriber</h4>
                </div>
                <div class="modal-body">
                    <form id="addSubscriberForm" method="POST" action="<?php echo base_url('subscribers/add'); ?>">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <div class="form-line">
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <div class="form-line">
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="form-line">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">CANCEL</button>
                    <button type="submit" form="addSubscriberForm" class="btn btn-success waves-effect">ADD</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Select all functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

function selectAll() {
    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    document.getElementById('select-all').checked = true;
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('select-all').checked = false;
}

function getSelectedSubscribers() {
    const checkboxes = document.querySelectorAll('.subscriber-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function executeBulkAction() {
    const action = document.getElementById('bulk-action').value;
    const selected = getSelectedSubscribers();
    
    if (!action) {
        alert('Please select a bulk action.');
        return;
    }
    
    if (selected.length === 0) {
        alert('Please select at least one subscriber.');
        return;
    }
    
    switch(action) {
        case 'delete':
            if (confirm(`Are you sure you want to delete ${selected.length} subscribers?`)) {
                bulkDelete(selected);
            }
            break;
        case 'resend':
            if (confirm(`Resend confirmation emails to ${selected.length} subscribers?`)) {
                bulkResend(selected);
            }
            break;
        case 'export':
            exportSelected(selected);
            break;
    }
    
    document.getElementById('bulk-action').value = '';
}

function deleteSubscriber(id) {
    if (confirm('Are you sure you want to delete this subscriber?')) {
        fetch('<?php echo base_url("newsletter/delete_subscriber"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({id: id})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting subscriber: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the subscriber.');
        });
    }
}

function resendConfirmation(id) {
    // Submit via standard form POST to align with controller behavior
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo base_url("subscribers/resend_confirmation"); ?>';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'subscriber_ids[]';
    input.value = id;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
    // Do not remove form immediately to prevent cancelling the submission
    setTimeout(() => { document.body.removeChild(form); }, 500);
}

function bulkDelete(ids) {
    fetch('<?php echo base_url("newsletter/bulk_delete_subscribers"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ids: ids})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error deleting subscribers: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting subscribers.');
    });
}

function bulkResend(ids) {
    // Submit selected IDs via form POST to the bulk handler
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo base_url("subscribers/resend_confirmation"); ?>';

    ids.forEach(function(id) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'subscriber_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
    // Do not remove form immediately to prevent cancelling the submission
    setTimeout(() => { document.body.removeChild(form); }, 500);
}

function exportSelected(ids) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo base_url("newsletter/export_subscribers"); ?>';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'subscriber_ids';
    input.value = JSON.stringify(ids);
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
    // Do not remove form immediately to prevent cancelling the submission
    setTimeout(() => { document.body.removeChild(form); }, 500);
}

function exportSubscribers() {
    window.location.href = '<?php echo base_url("newsletter/export_subscribers"); ?>';
}

function refreshTable() {
    location.reload();
}
</script>