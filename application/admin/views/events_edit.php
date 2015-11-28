		  <script type="text/javascript">
		 function del_confirm(title, msg, link) {
	var args1 = arguments;
	bootbox.dialog({
		message : msg,
		title : title,
		buttons : {
			main : {
				label : "Confirm",
				className : "btn-default",
				callback : function () {

					$.get(link, {
						/*csrf_test_name:$.cookie(CSRF_COOKIE_NAME)*/
					}, function (data) {
						var dialog = top.dialog.get(window);
						dialog.remove();
					});
				}
			},
			success : {
				label : "Cancel",
				className : "btn-primary",
				callback : function () {
					// nothing to do
				}
			}
		}
	});
} 
		  </script>
		  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/plugins/datepicker/datepicker3.css">
		  <?php 
		  if(isset($info) && count($info)>0){
			$action = 'edit';
			$header = 'Edit Event';
			$title = $info->title;
			$start = $info->start;
			$end = $info->end;
			$url = $info->url;
			$backgroundColor = $info->backgroundColor;
			$borderColor = $info->borderColor;
			$allDay = $info->allDay;
			$id = $info->id;
		  }else{
			$action = 'add';
			$header = 'Add Event';
			$title = '';
			$start = $start;
			$end = $end;
			$url = '';
			$backgroundColor = '#3c8dbc';
			$borderColor = '#3c8dbc';
			$allDay = 0;
			$id = '';
		  }
		  ?>
			 
			<?php 
		  echo form_open('c=calendar&m=save', 'class="bs-docs-example" id="event-edit-form"');
		  echo form_hidden('id', $id);
		  ?>
		  
		  
            <div class="form-group">
              <label class="control-label">Title</label>
              <input type="text" name="title" id="title" value="<?php echo $title;?>" class="form-control" />
            </div>		
			
			<div class="form-group">
				<label>All Day</label>
				
				<div class="radio">
					<label><input type="radio" name="allDay" value="1" <?php echo $allDay ? 'checked="checked"' : ''; ?>>Yes</label>
				</div>
				<div class="radio">
					<label><input type="radio" name="allDay" value="0" <?php echo $allDay ? '' : 'checked="checked"';?>>No</label>
				</div>
			</div>

            <div class="form-group">
              <label class="control-label">Start</label>
              <input type="text" name="start" id="start" value="<?php echo $start;?>" class="form-control" />
            </div>	
			
			<div class="form-group">
				<label>end</label>
				<input type="text" name="end" id="end" value="<?php echo $end;?>" class="form-control"/>
			</div>
			<!--
			<div class="form-group">
				<label>跳转URL</label>
				<input type="text" name="url" id="url" value="<?php echo $url;?>" class="form-control">
			</div>-->
			
			<div class="form-group">
				<label>Background Color</label>
				<div class="row">
					<input type="hidden" id="backgroundColor" name="backgroundColor" value="<?php echo $backgroundColor;?>" />
					
					<div class="col-lg-12">
						<button id="add-new-event" type="button" class="btn btn-primary btn-flat" style="border-color: <?php echo $backgroundColor;?>; background-color: <?php echo $backgroundColor;?>;">Event</button>
						<ul class="fc-color-picker" id="color-chooser">
						  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
						  <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
			
            		
            <div class="form-group">
              <div class="controls">
                <button type="submit" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-default cancel-form">cancel</button>
				<?php 
				if($id != ''){
					?>
					<button type="button" class="btn btn-danger" onclick="del_confirm('Notice', 'Are you sure？', '<?php echo site_url('c=calendar&m=delete&id=' . $id);?>')">delete</button>
					<?php 
				}
				?>
              </div>
            </div>
          <?php echo form_close();?>		
				</div>
			</div>	
        </div>
      </div>

      

      
    </section>
    <!-- /.content -->
<script type="text/javascript" src="<?php echo base_url();?>resource/adminlte/plugins/datepicker/bootstrap-datepicker.js"></script>	
<script type="text/javascript">
$(function(){
	
	$('#start').datepicker();
	$('#end').datepicker();
	
  	/* ADDING EVENTS */
  	var currColor = "#3c8dbc"; //Red by default
  	//Color chooser button
  	var colorChooser = $("#color-chooser-btn");
  	$("#color-chooser > li > a").click(function (e) {
  		e.preventDefault();
  		//Save color
  		currColor = $(this).css("color");
  		//Add color effect to button
  		$('#add-new-event').css({
  			"background-color" : currColor,
  			"border-color" : currColor
  		});
		$('#backgroundColor').val(currColor);
  	});
	
	$('#event-edit-form').validate({
		rules:{
			title:{
				required:true
			}
		},
		messages:{
			title:{
				required:'Title Is Required'
			}
		}
	});	
	$('#event-edit-form').ajaxForm({
		beforeSubmit:function(formData, jqForm, options){
			return $('#event-edit-form').valid();
		},
		success:function(responseText, statusText, xhr, form){
			var json = $.parseJSON(responseText);
			var dialog = top.dialog.get(window);
			dialog.remove();
			// if(json.success){
				// var dialog = top.dialog.get(window);
				// dialog.close();
			// }else{
				// toastr.error(json.msg);
				
			// }
			return false;
		}
	});			
});
</script>		  					  