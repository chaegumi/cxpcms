		  <?php 
		  if(isset($info)){
			$action = 'edit';
			$header = 'Edit Permission';
			$parent_id = $info->parent_id;
			$permKey = $info->permKey;
			$permName = $info->permName;
			$id = $info->id;
		  }else{
			$action = 'add';
			$header = 'Add Permission';
			$parent_id = $parent_id;
			$permKey = '';
			$permName = '';
			$id = '';
		  }
		  ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Permissions
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo '#/';?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Permissions</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li><a href="<?php echo '#/permissions';?>">Permissions</a></li>
				<?php 
				if($id == ''){
				?>
				<li class="active"><a href="<?php echo '#/permissions?m=add';?>"><?php echo $header;?></a></li>
				<?php 
				}else{
				?>
				<li class="active"><a href="<?php echo '#/permissions?m=edit&id=' . $id;?>"><?php echo $header;?></a></li>
				<?php 
				}
				?>
				
			</ul>
			<div class="tab-content">			
     
			<?php 
		  echo form_open('c=permissions&m=save', 'class="bs-docs-example" id="permission-edit-form"');
		  echo form_hidden('id', $id);
		  ?>
			<div class="form-group">
				<label class="control-label">Parent</label>
				<div class="controls">
					<select name="parent_id" class="form-control">
						<option value="0">top</option>
						<?php 
						  $permissions = permissions_list();
						  $perm_parr = array();
						  foreach($permissions as $row){
							$perm_parr[$row->parent_id][] = $row;
						  }
						 
						  function loop_parent($perm_parr, $parent_id, $curloop, $curid, $id){
							$CI = &get_instance();
							if(isset($perm_parr[$parent_id]) && count($perm_parr[$parent_id])>0){
							  if($id == ''){
								  foreach($perm_parr[$parent_id] as $row){
									echo '<option value="' . $row->id . '" ' . ($row->id == $curid ? 'selected="selected"' : '') . '>' . str_repeat(' - ', $curloop) . $row->permName . '</option>';
									loop_parent($perm_parr, $row->id, $curloop + 1, $curid, $id);
								  }								  
							  }else{
								  foreach($perm_parr[$parent_id] as $row){
									if($row->id == $id) continue;
									echo '<option value="' . $row->id . '" ' . ($row->id == $curid ? 'selected="selected"' : '') . '>' . str_repeat(' - ', $curloop) . $row->permName . '</option>';
									loop_parent($perm_parr, $row->id, $curloop + 1, $curid, $id);
								  }								  
							  }

							}
						  }
						  loop_parent($perm_parr, 0, 0, $parent_id, $id);
						?>
					</select>
				</div>
			</div>
		  
            <div class="form-group">
              <label class="control-label">Permission Name</label>
              
                <input type="text" name="permName" value="<?php echo $permName;?>" class="form-control" />
             
            </div>		

            <div class="form-group">
              <label class="control-label">Permission KEY</label>
              
                <input type="text" name="permKey" value="<?php echo $permKey;?>" class="form-control auto-slug" />
              
            </div>		

			
           
            		
            <div class="form-group">
              <div class="controls">
                <button type="submit" class="btn btn-primary">Save</button>
				<button type="reset" class="btn btn-default">Reset</button>
				<button type="button" class="btn btn-default" onclick="history.go(-1);">Cancel</button>
              </div>
            </div>
          <?php echo form_close();?>		
		</div>
		</div>
		</div>
		</div>
		</section>
<script type="text/javascript">
$(function(){
	$('#permission-edit-form').validate({
		rules:{
			permName:{
				required:true
			},
			permKey:{
				required:true
			}
		},
		messages:{
			permName:{
				required:'Permission Name is Required'
			},
			permKey:{
				required:'Permission Key is Required'
			}
		}
	});	
	$('#permission-edit-form').ajaxForm({
		beforeSubmit:function(formData, jqForm, options){
			return $('#permission-edit-form').valid();
		},
		success:function(responseText, statusText, xhr, form){
			var json = $.parseJSON(responseText);
			if(json.success){
				toastr.success(json.msg);
				<?php 
				if($id == ''){
					?>
					location.href = '#/permissions';
					<?php 
				}else{
					?>
					location.href = '#/permissions?m=edit&id=<?php echo $id;?>&after=edit';
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