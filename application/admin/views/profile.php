
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Profile
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo '#/';?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url();?>resource/adminlte/dist/img/user4-128x128.jpg" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo $user->username;?></h3>

              <p class="text-muted text-center"></p>

              <ul class="list-group list-group-unbordered">
				<li class="list-group-item">
				  <b>Join time</b> <a class="pull-right"><?php echo $user->reg_time;?></a>
				</li>			  
				<li class="list-group-item">
				  <b>Login times</b> <a class="pull-right"><?php echo $user->login_times;?></a>
				</li>
				<li class="list-group-item">
				  <b>Cur Login time</b> <a class="pull-right"><?php echo $user->cur_login_time;?></a>
				</li>
				<li class="list-group-item">
				  <b>Cur login ip</b> <a class="pull-right"><?php echo $user->cur_login_ip;?></a>
				</li>	
				<li class="list-group-item">
				  <b>cur login area</b> <a class="pull-right"><?php echo $user->cur_login_area;?></a>
				</li>	
				<li class="list-group-item">
				  <b>last login time</b> <a class="pull-right"><?php echo $user->last_login_time;?></a>
				</li>
				<li class="list-group-item">
				  <b>last login ip</b> <a class="pull-right"><?php echo $user->last_login_ip;?></a>
				</li>	
				<li class="list-group-item">
				  <b>last login area</b> <a class="pull-right"><?php echo $user->last_login_area;?></a>
				</li>				
                

              </ul>

              <a href="<?php echo site_url('c=logout');?>" class="btn btn-danger btn-block"><b>Logout</b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
			  <li><a href="#password1" data-toggle="tab">Change Password</a></li>
            </ul>
            <div class="tab-content">
              

              <div class="active tab-pane" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Username</label>

                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo $user->username;?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <p class="form-control-static"><?php echo $user->email;?></p>
                    </div>
                  </div>
				  <div class="form-group">
					<label for="" class="col-sm-2 control-label">Title</label> 
					<div class="col-sm-10">
						<input type="text" class="form-control" id="">
					</div>
				  </div>
				  
				  <div class="form-group">
					<label for="" class="col-sm-2 control-label">Photo</label>
					<div class="col-sm-10">
						<div class="row">
								<div class="col-xs-12 col-md-3 col-lg-2">
									<img src="" id="tpl_screenshot_img" style="width:150px;">
								</div>
								<div class="col-xs-12 col-md-7 col-lg-7">
								 <input type="text" class="form-control" name="tpl_screenshot" id="tpl_screenshot" value="">
								 <a class="btn btn-default" href="javascript:;" onclick="BrowseServer('', 'tpl_screenshot');return false;"><i class="fa fa-folder-open"></i> Choose File</a>
								</div>
							  </div>
					</div>
				  </div>
                  
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                       <button type="submit" class="btn btn-primary">Save</button>
				<button type="reset" class="btn btn-default">Reset</button>
				<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
			  
			  <div class="tab-pane" id="password1">
				<?php echo form_open('c=profile&m=update_password', 'class="form-horizontal" id="password-edit-form"');?>
                  <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">New Password</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="password" id="password" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Confirm Password</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" name="confirm_password" id="confirm_password" >
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                       <button type="submit" class="btn btn-primary">Save</button>
				<button type="reset" class="btn btn-default">Reset</button>
				<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
                    </div>
                  </div>				
				<?php echo form_close(); ?>
				<script type="text/javascript">
$(function(){
	$('#password-edit-form').validate({
		rules:{
			password:{
				required:true,
				minlength:6
			},
			confirm_password:{
				required:true,
				equalTo:'#password'
			}
		},
		messages:{
			password:{
				required:'New Password is Required',
				minlength:'At least 6 chars'
			},
			confirm_password:{
				required:'Confirm New Password',
				equalTo:'Two Password do not match'
			}
		}
	});	
	$('#password-edit-form').ajaxForm({
	    beforeSerialize:function(jqForm, options) {
		    $('input[name="<?php echo config_item('csrf_token_name');?>"]').val($.cookie('<?php echo config_item('csrf_cookie_name');?>'));
		},
		beforeSubmit:function(formData, jqForm, options){
			return $('#password-edit-form').valid();
		},
		success:function(responseText, statusText, xhr, form){
			var json = $.parseJSON(responseText);
			if(json.success){
				toastr.success(json.msg);
			}else{
				toastr.error(json.msg);
			}
			return false;
		}
	});			
});
</script> 
			  </div>
			  
			 
			  
			 
			  
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
