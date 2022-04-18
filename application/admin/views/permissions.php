<style type="text/css">
ul.ztree {margin-top: 10px;overflow-y:none;overflow-x:auto;}
.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>resource/ztree/css/zTreeStyle/zTreeStyle.css" />
<script type="text/javascript" src="<?php echo base_url();?>resource/ztree/js/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>resource/ztree/js/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>resource/ztree/js/jquery.ztree.exedit-3.5.js"></script>
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
				<li class="active"><a href="<?php echo '#/permissions';?>">Permissions</a></li>
				<li><a href="<?php echo '#/permissions?m=add';?>">Add Permissions</a></li>
			</ul>
			<div class="tab-content table-responsive no-padding"><div class="col-xs-12">
				All nodes <a href="javascript:;" id="expandAllBtn">expand</a> | <a href="javascript:;" id="collapseAllBtn">collapse</a>
              <ul id="treeDemo" class="ztree"></ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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
			view:{
				addHoverDom: addHoverDom,
				removeHoverDom:removeHoverDom,
				addDiyDom:addDiyDom,
				selectedMulti:false
				// showIcon:false
			},
			edit:{
				enable:true,
				editNameSelectAll:true,
				showRemoveBtn:showRemoveBtn,
				showRenameBtn:showRenameBtn,
				removeTitle:'delete permission',
				renameTitle:'edit pemission'
			},
			async: {
				enable:true,
				url:'<?php echo site_url('c=permissions&m=data');?>',
				autoParam:[],
				otherParam:{<?php echo config_item('csrf_token_name');?>:$.cookie('<?php echo config_item('csrf_cookie_name');?>')}
			},
			callback:{
				beforeRemove:beforeRemove,
				beforeEditName:beforeEditName,
				onRemove:onRemove
			}
		};
		
		function beforeRemove(treeId, treeNode){
			var zTree = $.fn.zTree.getZTreeObj(treeId);
			zTree.selectNode(treeNode);
			return confirm('确认删除 节点 -- ' + treeNode.name + "吗？");
		}
		
		function onRemove(event, treeId, treeNode){
			console.log(treeNode);
			$.get('<?php echo site_url('c=permissions&m=delete');?>', {id:treeNode.id}, function(data){
				var json = $.parseJSON(data);
				if(json.success){
					toastr.success(json.msg);
				}else{
					toastr.error(json.msg);
				}
			});
		}
		
		function beforeEditName(treeId, treeNode){
			location.href = '#/permissions?m=edit&id=' + treeNode.id;
			return false;
		}
		
		function showRemoveBtn(treeId, treeNode){
			if(treeNode.hasOwnProperty('children')){
				return false;
			}else{
				return true;
			}
		}
		
		function showRenameBtn(treeId, treeNode){
			return true;
		}
		
		var newCount = 1;
		
		function addHoverDom(treeId, treeNode){
			var sObj = $('#' + treeNode.tId + '_span');
			if(treeNode.editNameFlag || $('#addBtn_' + treeNode.tId).length>0) return;
			var addStr = '<span class="button add" id="addBtn_' + treeNode.tId + '" title="添加权限" onfocus="this.blur();"></span>';
			sObj.after(addStr);
			var btn = $('#addBtn_' + treeNode.tId);
			if(btn){
				btn.bind('click', function(){
					location.href = '#/permissions?m=add&parent_id=' + treeNode.id;
					return false;
				});
			} 
		}
		
		function removeHoverDom(treeId, treeNode){
			$('#addBtn_' + treeNode.tId).unbind().remove();
		}
		
		function addDiyDom(treeId, treeNode){
			var aObj = $('#' + treeNode.tId + IDMark_A);
			var diyStr = '(' + treeNode.permKey + ')';
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
