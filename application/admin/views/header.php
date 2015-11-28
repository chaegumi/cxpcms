<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CXPCMS</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/artdialog/css/ui-dialog.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/toastr/toastr.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/dist/css/skins/_all-skins.min.css">

  <style type="text/css">
	body{font-size:13px;}
	.no-padding{padding:10px 0!important;}
  </style>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script type="text/javascript">
  var BASE_URL = '<?php echo base_url() . $this->config->item('index_page');?>';
  var RELA_PATH = './';
  </script>
	<!-- jQuery 2.1.4 -->
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/arttemplate/template-native.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/artdialog/dialog-plus-min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/js/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/js/bootbox.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/toastr/toastr.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/fastclick/fastclick.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/autosize/autosize.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/momentjs/moment.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/momentjs/locales.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/select2/select2.full.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/fullcalendar/fullcalendar.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/fullcalendar/lang-all.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/datatables/dataTables.bootstrap.min.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/dist/js/app.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/sammy/lib/min/sammy-latest.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>resource/js/server.js"></script>
</head>
<body class="hold-transition skin-blue-light fixed sidebar-mini">
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="<?php echo site_url('');?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">CXP</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">CXPCMS</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="javascript:;" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
	 
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
         
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url();?>resource/adminlte/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $user->username;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url();?>resource/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $user->username;?>
                  <small>Join Time:<?php echo $user->reg_time;?></small>
                </p>
              </li>
              
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo '#/profile';?>" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo site_url('c=logout');?>" class="btn btn-default btn-flat">Logout</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- search form -->
      <form action="#/search" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
		<li>
			<a href="<?php echo site_url('');?>#/">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span>
			</a>
			
		</li>
		<li>
			<a href="<?php echo '#/calendar';?>">
				<i class="fa fa-calendar"></i> 
				<span>Calendar</span>
			</a>
		</li>
		
				<li>
			<a href="#/clear_cache">
				<i class="fa fa-cubes"></i>
				<span>Clear Clear</span>
			</a>
		</li>
		
		
		<li class="header">User Management</li>
		<li><a href="<?php echo '#/users';?>"><i class="fa fa-user"></i> <span>Users</span></a></li>
		<li><a href="<?php echo '#/roles';?>"><i class="fa fa-users"></i> <span>Roles</span></a></li>
		<li><a href="<?php echo '#/permissions';?>"><i class="fa fa-users"></i> <span>Permissions</span></a></li>
		<li><a href="<?php echo '#/login_log';?>"><i class="fa fa-history"></i> <span>Login Log</span></a></li>
		<li><a href="<?php echo '#/operation_log';?>"><i class="fa fa-history"></i> <span>Operation Log</span></a></li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

