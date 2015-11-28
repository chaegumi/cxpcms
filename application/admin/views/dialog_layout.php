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

  <link href="<?php echo base_url();?>resource/artdialog/css/ui-dialog.css" rel="stylesheet">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/dist/css/skins/_all-skins.min.css">
  <style type="text/css">
	body{font-size:13px;}
  </style>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script type="text/javascript">
  var BASE_URL = '<?php echo base_url();?>';
  </script>
	<!-- jQuery 2.1.4 -->
	<script src="<?php echo base_url();?>resource/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<!-- Bootstrap 3.3.5 -->
	<script src="<?php echo base_url();?>resource/adminlte/bootstrap/js/bootstrap.min.js"></script>
	
	<script src="<?php echo base_url();?>resource/artdialog/dialog-plus-min.js"></script>
	<script src="<?php echo base_url();?>resource/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url();?>resource/js/jquery.cookie.js"></script>
    <script src="<?php echo base_url();?>resource/js/jquery.form.js"></script>
    <script src="<?php echo base_url();?>resource/js/jquery.validate.min.js"></script>
	<!-- FastClick -->
	<script src="<?php echo base_url();?>resource/adminlte/plugins/fastclick/fastclick.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url();?>resource/adminlte/dist/js/app.min.js"></script>
	<!-- SlimScroll 1.3.0 -->
	<script src="<?php echo base_url();?>resource/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script>  
	
	<script src="<?php echo base_url();?>resource/js/bootbox.js"></script>
	<script src="<?php echo base_url();?>resource/server/js/server.js"></script>
</head>
<body>
<?php $this->load->view($main);?>
</body>
</html>

