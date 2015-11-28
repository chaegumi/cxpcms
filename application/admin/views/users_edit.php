
			<?php 
			if(isset($info) && count($info) > 0){
				
				$header = 'Edit User';
				$id = $info->id;
				$username = $info->username;
				$company_id = $info->company_id;
				$email = $info->email;
				$status = intval($info->status);
				$password = $info->password;
				$user_type = intval($info->user_type);
			}else{
				$header = 'Add User';
				$id = '';
				$username = '';
				$company_id = 0;
				$email = '';
				$status = 1;
				$password = '';
				$user_type = 0;
			}
			?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Users
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo '#/';?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Users</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
		
		  <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li><a href="<?php echo '#/users';?>">Users</a></li>
				<?php 
				if($id == ''){
					?>
					<li class="active"><a href="<?php echo '#/users?m=add';?>"><?php echo $header;?></a></li>
					<?php 
				}else{
					?>
					<li class="active"><a href="<?php echo '#/users?m=edit&id=' . $id;?>"><?php echo $header;?></a></li>
					<li><a href="<?php echo '#/users?m=edit_password&user_id=' . $id;?>">Change Password</a></li>
					<?php 
				}
				?>
				
			</ul>
			<div class="tab-content">
				<?php 
			echo form_open('c=users&m=save', 'role="form" id="user-edit-form"');
			echo form_hidden('id', $id);
			?>
			 
              
				<p class="page-header">Basic</p>
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" name="username" id="username" value="<?php echo $username;?>">
				  <input type="hidden" id="originalusername" value="<?php echo $username;?>">
                </div>
				
                <div class="form-group">
                  <label for="tpl_directory">Email</label>
                  <input type="email" class="form-control" name="email" id="email" value="<?php echo $email;?>">
				  <input type="hidden" id="originalemail" value="<?php echo $email;?>">
                </div>
				<?php 
				if($id == ''){
				?>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" name="password" id="password" value="<?php echo $password;?>">
				  
                </div>
				<?php 
				}
				?>
                <div class="form-group">
                  <label for="status">Status</label>
                  <select class="form-control" name="status" id="status">
					<option value="1" <?php echo $status === 1 ? 'selected="selected"' : '';?>>Active</option>
					<option value="0" <?php echo $status === 0 ? 'selected="selected"' : '';?>>Diactive</option>
				  </select>
                </div>	
				<p class="page-header">User Roles</p>
				<div class="form-group">
					<label>Roles(Select Multi)</label>
					<select multiple="multiple" name="roles[]" class="form-control select2" style="width:100%;">
					<?php 
					$roles = roles_list();
					foreach($roles as $role){
						?>
						<option value="<?php echo $role->id;?>" <?php echo in_array($role->id, $userRoles) ? 'selected="selected"' : '';?>><?php echo $role->roleName;?></option>
						<?php 
					}
					?>
					</select>
					
				</div>
				
				<?php 
				if($id != ''){
					?>
					<p class="page-header">User Permissions <a href="#/users?m=set_perms&user_id=<?php echo $id;?>" title="Change Permissions"><i class="fa fa-gear"></i></a></p>
					<div class="form-group">
					<ul>
					<?php 
						$my_acl = new Member_acl($id);
						$perms = $my_acl->getPermArr();
						foreach ($perms as $k => $v)
						{
							if ($v['value'] === false) { continue; }
							echo "<li>" . $v['Name'];
							if ($v['inheritted']) { echo "  (inherit)"; }
							echo "</li>";
						}
						?></ul>
					</div>
					<?php 
					
				}
				?>
              

              <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
				<button type="reset" class="btn btn-default">Reset</button>
				<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
              </div>
            <?php echo form_close();?>
			</div>
		  </div>	
		
          
        </div>
      </div>

      

      
    </section>
    <!-- /.content -->
<script type="text/javascript">
$(function(){
	$(".select2").select2({closeOnSelect:false});
	$('#user-edit-form').validate({
		rules:{
			username:{
				required:true,
				remote:{
					param:{
						url:'<?php echo site_url('c=ajax&m=check_value&table=users&field=username');?>'
					},
					depends:function(element){
						var id = $(element).attr('id');
						return ($(element).val() !== $('#original' + id).val());
					}
				}
			},
			email:{
				required:true,
				email:true,
				remote:{
					param:{
						url:'<?php echo site_url('c=ajax&m=check_value&table=users&field=email');?>'
					},
					depends:function(element){
						var id = $(element).attr('id');
						return ($(element).val() !== $('#original' + id).val());
					}
				}
			},
			password:{
				required:true
			},
			'roles[]':{
				required:true,
				minlength:1
			}
		},
		messages:{
			username:{
				required:'Username is Required',
				remote:'Username is exists'
			},
			email:{
				required:'Email is Required',
				email:'Invalid Email',
				remote:'Email is exists'
			},
			password:{
				required:'password is required'
			},
			'roles[]':{
				required:'Choose A role',
				minlength:'Choose At least one item'
			}
		}
	});	
	$('#user-edit-form').ajaxForm({
		beforeSubmit:function(formData, jqForm, options){
			return $('#user-edit-form').valid();
		},
		success:function(responseText, statusText, xhr, form){
			var json = $.parseJSON(responseText);
			if(json.success){
				toastr.success(json.msg);
				<?php 
				if($id == ''){
					?>
					location.href = '#/users';
					<?php 
				}else{
					?>
					location.href = '#/users?m=edit&id=<?php echo $id;?>&after=edit';
					<?php 
				}
				?>				
			}else{
				toastr.error(json.msg);
			}
			return false;
		}
	});			
});
</script>  