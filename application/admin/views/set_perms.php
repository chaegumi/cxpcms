<style type="text/css">
ul.ztree {margin-top: 10px;overflow-y:none;overflow-x:auto;}
.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>resource/ztree/css/zTreeStyle/zTreeStyle.css" />
<script type="text/javascript" src="<?php echo base_url();?>resource/ztree/js/jquery.ztree.core-3.5.js"></script>
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
				<li><a href="<?php echo '#/users?m=edit&id=' . $user_id;?>">Edit User</a></li>
				<li><a href="<?php echo '#/users?m=edit_password&user_id=' . $user_id;?>">Change Password</a></li>
				<li class="active"><a href="<?php echo '#/users?m=set_perms&user_id=' . $user_id;?>">Change Permission</a></li>
					
			</ul>
			<div class="tab-content">
				<?php 
			echo form_open('c=users&m=set_perms', 'role="form" id="user-edit-form"');
			echo form_hidden('user_id', $user_id);
			?>
			 
              
				
                <div class="form-group">
                  <label for="password">Username</label>
                  <p class="form-static-control"><?php echo $info->username;?></p>
                </div>
				
                <div class="form-group">
                  <label for="confirm_password">User Permissions</label>
                  All Permissions <a href="javascript:;" id="expandAllBtn">expand</a> | <a href="javascript:;" id="collapseAllBtn">collapse</a>
					<ul id="treeDemo" class="ztree"></ul>
                </div>
				
              

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
		<!--
		var IDMark_Switch = "_switch",
		IDMark_Icon = "_ico",
		IDMark_Span = "_span",
		IDMark_Input = "_input",
		IDMark_Check = "_check",
		IDMark_Edit = "_edit",
		IDMark_Remove = "_remove",
		IDMark_Ul = "_ul",
		IDMark_A = "_a";
		
		var setting = {
			check:{
				// enable:true
			},
			view:{
				addDiyDom:addDiyDom
			},
			edit:{
			},
			async: {
				enable:true,
				url:'<?php echo site_url('c=users&m=perm_data');?>',
				autoParam:[],
				otherParam:{'user_id':'<?php echo $user_id;?>'}
			},
			callback:{
			}
		};

		function addDiyDom(treeId, treeNode){
			var aObj = $('#' + treeNode.tId + IDMark_A);
			var diyStr = treeNode.select;
			aObj.after(diyStr);
		}
		
		$(document).ready(function(){
			$.fn.zTree.init($("#treeDemo"), setting);
			$('#expandAllBtn').on('click', function(){
				var zTree = $.fn.zTree.getZTreeObj('treeDemo');
				zTree.expandAll(true);
			});
			$('#collapseAllBtn').on('click', function(){
				var zTree = $.fn.zTree.getZTreeObj('treeDemo');
				zTree.expandAll(false);
			});
		});
		
		
		//-->
</script>  
<script type="text/javascript">
$(function(){
	// $('#category-table').treetable({ expandable: true, initialState:'expanded' });
	 $('#user-edit-form').ajaxForm({
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
	