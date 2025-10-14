<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Admin Dashboard</title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="<?php echo asset_url('images/favicon/favicon-96x96.png'); ?>" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="<?php echo asset_url('images/favicon/favicon.svg'); ?>">
    <link rel="shortcut icon" href="<?php echo asset_url('images/favicon/favicon.ico'); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset_url('images/favicon/apple-touch-icon.png'); ?>">
    <meta name="apple-mobile-web-app-title" content="Lgmissions">
    <link rel="manifest" href="<?php echo asset_url('images/favicon/site.webmanifest'); ?>">

    <!--REQUIRED PLUGIN CSS-->

    <link href="<?php echo asset_url('plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset_url('plugins/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">
     <link href="<?php echo asset_url('plugins/sweetalert/sweetalert.css'); ?>" rel="stylesheet">
   <link href="<?php echo asset_url('plugins/alertify/css/alertify.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset_url('plugins/bootstrap-select/css/bootstrap-select.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset_url('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset_url('plugins/bootstrap-daterange/daterangepicker.css'); ?>" rel="stylesheet">


        <!--THIS PAGE LEVEL CSS-->
        <link href="<?php echo asset_url('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo asset_url('plugins/jquery-datatable/skin/bootstrap/css/responsive.bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo asset_url('plugins/jquery-datatable/skin/bootstrap/css/scroller.bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo asset_url('plugins/jquery-datatable/skin/bootstrap/css/fixedHeader.bootstrap.min.css'); ?>" rel="stylesheet">

    <!--THIS PAGE LEVEL CSS-->
    <link href="<?php echo asset_url('plugins\alertify\css\alertify.css'); ?>" rel="stylesheet">

    <link href="<?php echo asset_url('plugins/jquery-datatable/skin/bootstrap/css/scroller.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo asset_url('plugins/jquery-datatable/skin/bootstrap/css/fixedHeader.bootstrap.min.css'); ?>" rel="stylesheet">

    <!--REQUIRED THEME CSS -->
    <link href="<?php echo asset_url('plugins/dropify/dist/css/dropify.min.css'); ?>" rel="stylesheet">


    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Animation Css -->
    <link href="<?php echo asset_url('plugins/animate-css/animate.css'); ?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo asset_url('css/style.css'); ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo asset_url('css/themes/all-themes.css'); ?>" rel="stylesheet" />
    <script src="https://cdn.dashjs.org/latest/dash.all.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.0.0/video-js.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.0.0/video.min.js"></script>
  <script src='<?= base_url() ?>assets/tinymce/tinymce.js'></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>

</head>

<body class="light layout-fixed theme-brown">
  <!-- #END# Search Bar -->
  <!-- Top Bar -->
  <nav class="navbar">
      <div class="container-fluid">
          <div class="navbar-header">
              <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
              <a href="javascript:void(0);" class="bars"></a>
              <a class="navbar-brand" href="<?php echo site_url(); ?>">Lighthouse Newsletter</a>
          </div>

          <div class="collapse navbar-collapse" id="navbar-collapse">
              <ul class="nav navbar-nav navbar-right">
                  <!-- #END# Call Search -->
                  <!-- Notifications -->

                  <!-- #END# Tasks -->
                  <li class="pull-right" title="logout"><a href="<?php echo base_url(); ?>logout" class="js-right-sidebar" data-close="true"><i class="material-icons">logout</i></a></li>

                  <li class="pull-right" title="Admin Accounts"><a href="<?php echo base_url(); ?>adminListing" class="js-right-sidebar" data-close="true"><i class="material-icons">account_circle</i></a></li>
                  <li class="pull-right" title="update settings"><a href="<?php echo base_url(); ?>settings" class="js-right-sidebar" data-close="true"><i class="material-icons">settings</i></a></li>
               <!--    <li class="pull-right" title="update livestreams"><a href="<?php echo base_url(); ?>livestreams" class="js-right-sidebar" data-close="true"><i class="material-icons">live_tv</i></a></li> <!--
               <!--   <li class="pull-right" title="update radio"><a href="<?php echo base_url(); ?>radio" class="js-right-sidebar" data-close="true"><i class="material-icons">radio</i></a></li> -->
                <!--  <li class="pull-right" title="Coupons"><a href="<?php echo base_url(); ?>coupons" class="js-right-sidebar" data-close="true"><i class="material-icons">money</i></a></li> -->
                 <!-- <li class="pull-right" title="Donations"><a href="<?php echo base_url(); ?>donations" class="js-right-sidebar" data-close="true"><i class="material-icons">attach_money</i></a></li> -->

                </ul>
          </div>

      </div>
  </nav>


  <!-- #Top Bar -->
  <section>
      <!-- Left Sidebar -->
      <aside id="leftsidebar" class="sidebar">


          <!-- Menu -->
          <div class="menu">
              <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                  <li <?php if (strpos($url,'dashboard') !== false && strpos($url,'newsletter') === false){ ?> class="active" <?php } ?>>
                      <a href="<?php echo base_url(); ?>dashboard">
                          <i class="material-icons">home</i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                  
                  <li <?php if (strpos($url,'subscribers') !== false){ ?> class="active" <?php } ?>>
                            <a href="<?php echo base_url(); ?>subscribers">
                          <i class="material-icons">people</i>
                          <span>Subscribers</span>
                      </a>
                  </li>

                  <li <?php if (strpos($url,'newsletter') !== false && strpos($url,'newsletter/email_history') === false && strpos($url,'newsletter/settings') === false){ ?> class="active" <?php } ?>>
                      <a href="<?php echo base_url(); ?>newsletter/index">
                          <i class="material-icons">email</i>
                          <span>Newsletter</span>
                      </a>
                  </li>

                  <li <?php if (strpos($url,'newsletter/email_history') !== false){ ?> class="active" <?php } ?>>
                      <a href="<?php echo base_url(); ?>newsletter/email_history">
                          <i class="material-icons">history</i>
                          <span>Email History</span>
                      </a>
                  </li>

                  <li <?php if (strpos($url,'newsletter/settings') !== false){ ?> class="active" <?php } ?>>
                      <a href="<?php echo base_url(); ?>newsletter/settings">
                          <i class="material-icons">settings</i>
                          <span>Newsletter Settings</span>
                      </a>
                  </li>

                  <li <?php if (strpos($url,'settings') !== false && strpos($url,'newsletter/settings') === false){ ?> class="active" <?php } ?>>
                      <a href="<?php echo base_url(); ?>settings">
                          <i class="material-icons">tune</i>
                          <span>Settings</span>
                      </a>
                  </li>

                  <li <?php if (strpos($url,'admin_users') !== false){ ?> class="active" <?php } ?>>
                      <a href="<?php echo base_url(); ?>admin_users">
                          <i class="material-icons">admin_panel_settings</i>
                          <span>Admin Users Listing</span>
                      </a>
                  </li>


                  
              </ul>
          </div>
          <!-- #Menu -->
          <!-- Footer -->
          <div class="legal">
              <div class="copyright">
                  &copy; <?php echo date('Y'); ?> <a href="javascript:void(0);">Lighthouse Newsletter</a>.
              </div>

          </div>
          <!-- #Footer -->
      </aside>
      <!-- #END# Left Sidebar -->
  </section>
