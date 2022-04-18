
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
					<li class="active"><a href="<?php echo '#/roles';?>">Roles</a></li>
					
					<li><a href="<?php echo '#/roles?m=add';?>">Add Role</a></li>
						
					
				</ul>
				<div class="tab-content table-responsive no-padding"><div class="col-xs-12">
              <table class="table table-striped table-bordered table-hover" id="roles-datatable" width="100%">
												<thead>
													<tr>
														<th nowrap>#</th>
														<th nowrap>Role Name</th>
														<th nowrap>Operation</th>
													</tr>
												</thead>
												<tbody>
												   
												</tbody>
											</table></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>

      

      
    </section>
    <!-- /.content -->

<script type="text/javascript">
$(function(){
	
	var table = $('#roles-datatable').DataTable({
		deferRender: true,
		select:{
			style:'single',
			blurable: true
		},	
		ajax:{
			url:'<?php echo site_url('c=roles&m=data');?>',
			type:'post',
			data:function(d){
				//d.csrf_test_name = $.cookie(CSRF_COOKIE_NAME);
				d.<?php echo config_item('csrf_token_name');?>=$.cookie('<?php echo config_item('csrf_cookie_name');?>')
			}
		},
		columns:[
			{
				data:'id',
				className:'select-checkbox',
				render:function(data, type, row){
					return data;
				}
			},
			{data:'roleName'},
			{
				data:'id',
				sortable:false,
				render:function(data, type, row){
					var html = '';
					html += '<div class="btn-group">';
						  html += '<a href="<?php echo '#/roles?m=edit&id=';?>' + data + '" title="edit" class="btn btn-default btn-xs"><i class="fa fa-pencil icon-pencil"></i></a><a href="javascript:;" onclick="del_confirm(\'Notice\', \'Are you sure delete\', \'<?php echo site_url('c=roles&m=delete&id=');?>' + data + '\',\'roles-datatable\');" title=" delete" class="btn btn-default btn-xs"><i class="fa fa-trash icon-trash"></i></a>';
					html += '</div>';
					return html;
				}
			}
		]
	});
});
</script>    