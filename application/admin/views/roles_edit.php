<style type="text/css">
ul.ztree {margin-top: 10px;overflow-y:none;overflow-x:auto;}
.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>resource/ztree/css/zTreeStyle/zTreeStyle.css" />
<script type="text/javascript" src="<?php echo base_url();?>resource/ztree/js/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>resource/ztree/js/jquery.ztree.excheck-3.5.js"></script>
			<?php 
			if(isset($info)){
				$header = 'Edit Role';
				$id = $info->id;
				$roleName = $info->roleName;
				$action = 'edit';
			}else{
				$header = 'Add Role';
				$id = '';
				$roleName = '';
				$rPerms = array();
				$action = 'add';
			}
			?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Roles
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo '#/';?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Roles</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
					<li><a href="<?php echo '#/roles';?>">Roles</a></li>
					<?php 
					if($id == ''){
						?>
						<li class="active"><a href="<?php echo '#/roles?m=add';?>"><?php echo $header;?></a></li>
						<?php 
					}else{
						?>
						<li class="active"><a href="<?php echo '#/roles?m=edit&id=' . $id;?>"><?php echo $header;?></a></li>
						<?php 
					}
					?>
					
				</ul>
				<div class="tab-content">
			<?php 
			echo form_open('c=roles&m=save', 'role="form" id="role-edit-form"');
			echo form_hidden('id', $id);
			?>
             
                <div class="form-group">
                  <label for="roleName">Role Name</label>
                  <input type="text" class="form-control" name="roleName" id="roleName" value="<?php echo $roleName;?>">
                </div>			
				
				<div class="form-group">
				  <label for="">Role Permissions</label>
					All Node <a href="javascript:;" id="expandAllBtn">expand</a> | <a href="javascript:;" id="collapseAllBtn">collapse</a> | <a href="javascript:;" class="checkall" data-type="1">All Allow</a> | <a href="javascript:;" class="checkall" data-type="0">All Deny</a> | <a href="javascript:;" class="checkall" data-type="x">All Ignore</a>
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
				url:'<?php echo site_url('c=roles&m=perm_data');?>',
				autoParam:[],
				otherParam:{'action':'<?php echo $action;?>', 'roleid':'<?php echo $id;?>'}
			},
			callback:{
			}
		};

		function addDiyDom(treeId, treeNode){
			var aObj = $('#' + treeNode.tId + IDMark_A);
			var diyStr = "<select name=\"perm_" + treeNode.id + "\"><option value=\"1\" " + (treeNode.chk == '1' ? 'selected="selected"' : '') + ">Allow</option><option value=\"0\" " + (treeNode.chk == '0' ? 'selected="selected"' : '') + ">Deny</option><option value=\"x\" " + (treeNode.chk == 'x' ? 'selected="selected"' : '') + ">Ignore</option></select>";
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
	
	// 全选
	$(document).on('click', '.checkall', function(){
		var type = $(this).attr('data-type');
		// console.log(type);
		// $('.checkall').each(function(i, n){
			// if($(n).attr('data-type') != type){
				// $(n).prop('selected', false);
			// }
		// });
		// $(this).siblings('.checkall').prop('selected', false);
		$('select[name*="perm_"]').each(function(i, n){
			// console.log(n);
			switch(type){
				case '1':
					$(n).val('1');
					break;
				case '0':
					$(n).val('0');				
					break;
				case 'x':
					$(n).val('x');				
					break;
			}
		});
	});	
	$('#role-edit-form').validate({
		rules:{
			roleName:{
				required:true
			}
		},
		messages:{
			roleName:{
				required:'Role Name Is required'
			}
		}
	});	
	$('#role-edit-form').ajaxForm({
		beforeSubmit:function(formData, jqForm, options){
			return $('#role-edit-form').valid();
		},
		success:function(responseText, statusText, xhr, form){
			var json = $.parseJSON(responseText);
			if(json.success){
				toastr.success(json.msg);
				<?php 
				if($id == ''){
					?>
					location.href = '#/roles';
					<?php 
				}else{
					?>
					location.href = '#/roles?m=edit&id=<?php echo $id;?>&after=edit';
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