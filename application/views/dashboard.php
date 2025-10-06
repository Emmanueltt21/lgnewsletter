<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>DASHBOARD</h2>
        </div>

        <!-- Widgets -->
        <div class="row clearfix">
          <!--   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">video_library</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Videos</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $videos; ?>" data-speed="15" data-fresh-interval="20"><?php echo $videos; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">library_music</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Audios</div>
                        <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20"><?php echo $audios; ?></div>
                    </div>
                </div>
            </div>
          
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">android</i>
                    </div>
                    <div class="content">
                        <div class="text">Total App Users</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo $videos; ?>" data-speed="15" data-fresh-interval="20"><?php echo $users; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">account_circle</i>
                    </div>
                    <div class="content">
                        <div class="text">Total Admin Accounts</div>
                        <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20"><?php echo $admin; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">forum</i>
                    </div>
                    <div class="content">
                        <div class="text">Total News</div>
                        <div class="number count-to" data-from="0" data-to="243" data-speed="1000" data-fresh-interval="20"><?php echo $comments; ?></div>
                    </div>
                </div> -->
            
            <!-- Newsletter Statistics -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-purple hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">email</i>
                    </div>
                    <div class="content">
                        <div class="text">Newsletter Subscribers</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo isset($newsletter_subscribers) ? $newsletter_subscribers : 0; ?>" data-speed="1000" data-fresh-interval="20"><?php echo isset($newsletter_subscribers) ? $newsletter_subscribers : 0; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">check_circle</i>
                    </div>
                    <div class="content">
                        <div class="text">Confirmed Subscribers</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo isset($newsletter_confirmed) ? $newsletter_confirmed : 0; ?>" data-speed="1000" data-fresh-interval="20"><?php echo isset($newsletter_confirmed) ? $newsletter_confirmed : 0; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">schedule</i>
                    </div>
                    <div class="content">
                        <div class="text">Pending Confirmations</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo isset($newsletter_pending) ? $newsletter_pending : 0; ?>" data-speed="1000" data-fresh-interval="20"><?php echo isset($newsletter_pending) ? $newsletter_pending : 0; ?></div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-blue hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">send</i>
                    </div>
                    <div class="content">
                        <div class="text">Newsletters Sent</div>
                        <div class="number count-to" data-from="0" data-to="<?php echo isset($newsletter_sent) ? $newsletter_sent : 0; ?>" data-speed="1000" data-fresh-interval="20"><?php echo isset($newsletter_sent) ? $newsletter_sent : 0; ?></div>
                    </div>
                </div>
            </div>
            
          <!--  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">report</i>
                    </div>
                    <div class="content">
                        <div class="text">User Reported Comments </div>
                        <div class="number count-to" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"><?php echo $reports; ?></div>
                    </div>
                </div>
            </div>-->
        </div>
        
        <!-- Newsletter Management Section -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Newsletter Management</h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="<?php echo base_url('subscribers'); ?>">Manage Subscribers</a></li>
                                    <li><a href="<?php echo base_url('newsletter/compose'); ?>">Compose Newsletter</a></li>
                                    <li><a href="<?php echo base_url('newsletter/newsletters'); ?>">View All Newsletters</a></li>
                                    <li><a href="<?php echo base_url('newsletter/email_history'); ?>">Email History</a></li>
                                    <li><a href="<?php echo base_url('newsletter/settings'); ?>">Settings</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row">
                            <!-- Recent Subscribers -->
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <h4>Recent Subscribers</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($recent_subscribers)): ?>
                                                <?php foreach ($recent_subscribers as $subscriber): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($subscriber->first_name . ' ' . $subscriber->last_name); ?></td>
                                                        <td><?php echo htmlspecialchars($subscriber->email); ?></td>
                                                        <td>
                                                            <span class="label label-<?php echo $subscriber->status == 'confirmed' ? 'success' : ($subscriber->status == 'pending' ? 'warning' : 'default'); ?>">
                                                                <?php echo ucfirst($subscriber->status); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M j, Y', strtotime($subscriber->subscribed_at)); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">No recent subscribers</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <a href="<?php echo base_url('subscribers'); ?>" class="btn btn-primary btn-sm">View All Subscribers</a>
                                </div>
                            </div>
                            
                            <!-- Recent Newsletters -->
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <h4>Recent Newsletters</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Recipients</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($recent_newsletters)): ?>
                                                <?php foreach ($recent_newsletters as $newsletter): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($newsletter->subject); ?></td>
                                                        <td><?php echo isset($newsletter->recipients_count) ? number_format($newsletter->recipients_count) : '0'; ?></td>
                                                        <td>
                                                            <span class="label label-<?php echo $newsletter->status == 'sent' ? 'success' : ($newsletter->status == 'draft' ? 'info' : 'warning'); ?>">
                                                                <?php echo ucfirst($newsletter->status); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M j, Y', strtotime($newsletter->created_at)); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">No newsletters sent yet</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <a href="<?php echo base_url('newsletter/newsletters'); ?>" class="btn btn-primary btn-sm">View All Newsletters</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-lg-12">
                                <h4>Quick Actions</h4>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                        <a href="<?php echo base_url('newsletter/compose'); ?>" class="btn btn-block btn-lg btn-primary waves-effect">
                                            <i class="material-icons">edit</i><br>
                                            Compose Newsletter
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                        <a href="<?php echo base_url('subscribers'); ?>" class="btn btn-block btn-lg btn-info waves-effect">
                                            <i class="material-icons">people</i><br>
                                            Manage Subscribers
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                        <a href="<?php echo base_url('newsletter/email_history'); ?>" class="btn btn-block btn-lg btn-warning waves-effect">
                                            <i class="material-icons">history</i><br>
                                            Email History
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                        <a href="<?php echo base_url('newsletter/settings'); ?>" class="btn btn-block btn-lg btn-default waves-effect">
                                            <i class="material-icons">settings</i><br>
                                            Newsletter Settings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
