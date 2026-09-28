<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>NEWSLETTER MANAGEMENT</h2>
        </div>

        <!-- Newsletter Statistics -->
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">email</i>
                    </div>
                    <div class="content">
                        <div class="text">TOTAL NEWSLETTERS</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $stats->total_newsletters; ?>" data-speed="15" data-fresh-interval="20"><?php echo $stats->total_newsletters; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">drafts</i>
                    </div>
                    <div class="content">
                        <div class="text">DRAFT NEWSLETTERS</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $stats->draft_newsletters; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $stats->draft_newsletters; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">send</i>
                    </div>
                    <div class="content">
                        <div class="text">SENT NEWSLETTERS</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $stats->sent_newsletters; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $stats->sent_newsletters; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">schedule</i>
                    </div>
                    <div class="content">
                        <div class="text">RECENT (30 DAYS)</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $stats->recent_newsletters; ?>" data-speed="1000" data-fresh-interval="20"><?php echo $stats->recent_newsletters; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter Management -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            NEWSLETTER LIST
                            <small>Manage your newsletter campaigns</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <a href="<?php echo base_url('newsletter/compose'); ?>" class="btn btn-primary waves-effect">
                                    <i class="material-icons">add</i>
                                    <span>NEW NEWSLETTER</span>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="<?php echo base_url('newsletter/export/csv'); ?>">Export CSV</a></li>
                                    <li><a href="<?php echo base_url('newsletter/settings'); ?>">Settings</a></li>
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

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Recipients</th>
                                        <th>Created Date</th>
                                        <th>Sent Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($newsletters)): ?>
                                        <?php foreach($newsletters as $newsletter): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($newsletter->subject); ?></td>
                                                <td>
                                                    <?php if($newsletter->status == 'sent'): ?>
                                                        <span class="label label-success">Sent</span>
                                                    <?php elseif($newsletter->status == 'draft'): ?>
                                                        <span class="label label-warning">Draft</span>
                                                    <?php else: ?>
                                                        <span class="label label-default"><?php echo ucfirst($newsletter->status); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $newsletter->recipients_count ?? 0; ?></td>
                                                <td data-order="<?php echo strtotime($newsletter->created_at); ?>"><?php echo date('M j, Y g:i A', strtotime($newsletter->created_at)); ?></td>
                                                <td data-order="<?php echo $newsletter->sent_at ? strtotime($newsletter->sent_at) : 0; ?>">
                                                    <?php if($newsletter->sent_at): ?>
                                                        <?php echo date('M j, Y g:i A', strtotime($newsletter->sent_at)); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not sent</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?php echo base_url('newsletter/compose/' . $newsletter->id); ?>" 
                                                           class="btn btn-xs btn-info waves-effect" title="Edit">
                                                            <i class="material-icons">edit</i>
                                                        </a>
                                                        <?php if($newsletter->status == 'draft'): ?>
                                                            <a href="<?php echo base_url('newsletter/send/' . $newsletter->id); ?>" 
                                                               class="btn btn-xs btn-success waves-effect" 
                                                               title="Send Newsletter"
                                                               onclick="return confirm('Are you sure you want to send this newsletter?')">
                                                                <i class="material-icons">send</i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="<?php echo base_url('newsletter/delete/' . $newsletter->id); ?>" 
                                                           class="btn btn-xs btn-danger waves-effect" 
                                                           title="Delete"
                                                           onclick="return confirm('Are you sure you want to delete this newsletter?')">
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
                                                    No newsletters found. <a href="<?php echo base_url('newsletter/compose'); ?>">Create your first newsletter</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>