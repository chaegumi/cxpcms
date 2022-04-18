<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CXPCMS | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?php echo site_url('');?>">CXPCMS</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in</p>
	
	<?php echo form_open('c=login', 'id="loginform"');?>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" required name="username" id="username" placeholder="Username or Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		<span id="usernameerror"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" required name="password" id="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
		<span id="passworderror"></span>
      </div>
	  <div class="form-group">
		<div class="row">
			<div class="col-lg-7">
				<input type="text" class="form-control" required name="captcha_code" id="captcha_code" placeholder="Captcha"><span id="captcha_codeerror"></span>
			</div>
			<div class="col-lg-5">
				<img src="<?php echo base_url();?>api/captcha" id="captcha_img" onclick="this.src='<?php echo base_url();?>api/captcha?_t=' + Math.random();" style="width:100%;height:35px;">
			</div>	
		</div>
	  </div>	  
      <div class="row">
        <div class="col-xs-8">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign in</button>
        </div>
        <!-- /.col -->
      </div>
    <?php echo form_close();?>

    <div class="social-auth-links text-center">
      <p>- or -</p>
	  <!--<a href="<?php echo site_url('c=oauth&m=login&from=coop');?>" class="btn btn-block btn-social btn-twitter btn-flat"><i class="fa fa-qq"></i> 使用COOP登录</a>-->
	  <!--<a href="<?php echo site_url('c=oauth&m=login&from=qq');?>" class="btn btn-block btn-social btn-twitter btn-flat"><i class="fa fa-qq"></i> 使用QQ登录</a>-->
      <!--<a href="<?php echo site_url('c=oauth&m=login&from=facebook');?>" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> 使用Facebook登录</a>
      <a href="<?php echo site_url('c=oauth&m=login&from=google');?>" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> 使用Google+登录</a>-->
    </div>
    <!-- /.social-auth-links -->

    <a href="<?php echo site_url('c=login&m=findpassword');?>">Forget Password?</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url();?>resource/adminlte/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>resource/adminlte/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>resource/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>resource/js/jquery.form.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>resource/js/bootbox.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>resource/js/jquery.validate.min.js" type="text/javascript"></script>
<script>
  $(function () {
	$.validator.setDefaults({
		ignore : "",
		errorPlacement : function (error, element) {
			if ($(document).find('#' + element.attr('id') + 'error')) {
				error.appendTo($('#' + element.attr('id') + 'error'));
			}
		},
		highlight : function (element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		unhighlight : function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		}
	});
	$('#loginform').validate({
		rules:{
			username:{
				required:true
			},
			password:{
				required:true
			},
			captcha_code:{
				required:true
			}			
		},
		messages:{
			username:{
				required:'Please Enter Username or Email'
			},
			password:{
				required:'Please Enter Password'
			},
			captcha_code:{
				required:'Pleace Enter Captcha Code'
			}
		}

	});
	$('#loginform').ajaxForm({
	    beforeSerialize:function(jqForm, options) {
		    $('input[name="<?php echo config_item('csrf_token_name');?>"]').val($.cookie('<?php echo config_item('csrf_cookie_name');?>'));
		},
		beforeSubmit:function(formData, jqForm, options){
			return $('#loginform').valid();
		},
		success:function(responseText, statusText, xhr, form){
			var json = $.parseJSON(responseText);
			if(json.success){
				window.location.href = '<?php echo base_url() . $this->config->item('index_page');?>';
			}else{
				$('#captcha_img').attr('src', '<?php echo base_url();?>api/captcha?_t=' + Math.random());
				bootbox.alert(json.msg);
			}
			return false;
		}
	});
  });
</script>
</body>
</html>
