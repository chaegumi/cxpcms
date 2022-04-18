<!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>resource/adminlte/plugins/fullcalendar/fullcalendar.print.css" media="print">
    <section class="content-header">
      <h1>
        Calendar
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Calendar</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
<!-- fullCalendar 2.2.5 -->
<!-- Page specific script -->
<script>

  $(function () {

  	/* initialize the external events
  	-----------------------------------------------------------------*/
  	function ini_events(ele) {
  		ele.each(function () {

  			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
  			// it doesn't need to have a start or end
  			var eventObject = {
  				title : $.trim($(this).text()) // use the element's text as the event title
  			};

  			// store the Event Object in the DOM element so we can get to it later
  			$(this).data('eventObject', eventObject);

  			// make the event draggable using jQuery UI
  			$(this).draggable({
  				zIndex : 1070,
  				revert : true, // will cause the event to go back to its
  				revertDuration : 0 //  original position after the drag
  			});

  		});
  	}

  	ini_events($('#external-events div.external-event'));

  	/* initialize the calendar
  	-----------------------------------------------------------------*/
  	//Date for the calendar events (dummy data)
  	var date = new Date();
  	var d = date.getDate(),
  	m = date.getMonth(),
  	y = date.getFullYear();
  	$('#calendar').fullCalendar({
  		lang : 'zh-CN',
  		defaultView : 'agendaWeek',
  		header : {
  			left : 'prev,next today',
  			center : 'title',
  			right : 'month,agendaWeek,agendaDay'
  		},
		buttonText: {
			today: '今天',
			month: '月',
			week: '周',
			day: '日'
		},
  		//Random default events
  		events : {
  			url : '<?php echo site_url('c=calendar&m=data');?>',
			data:function(){
				return {
					dynamic_value:Math.random()
				}
			},
  			error : function () {
  				toastr.error('数据加载失败');
  			}
  		},
		eventClick:function(calEvent, jsEvent, view){
			art_open1('修改事件', BASE_URL + '?c=calendar&m=edit&id=' + calEvent.id);
		},
		selectable : true,
		selectHelper : true,
		select : function(start, end){
			art_open1('Add Event', BASE_URL + '?c=calendar&m=add&start=' + start.format('YYYY-MM-DD HH:mm:ss') + '&end=' + end.format('YYYY-MM-DD HH:mm:ss') + '&_t=' + Math.random());
			$('#calendar').fullCalendar( 'rerenderEvents' );
			$('#calendar').fullCalendar('unselect');
		},
  		editable : true,
  		droppable : true, // this allows things to be dropped onto the calendar !!!
  		drop : function (date, allDay) { // this function is called when something is dropped

  			// retrieve the dropped element's stored Event Object
  			var originalEventObject = $(this).data('eventObject');

  			// we need to copy it, so that multiple events don't have a reference to the same object
  			var copiedEventObject = $.extend({}, originalEventObject);

  			// assign it the date that was reported
  			copiedEventObject.start = date;
  			copiedEventObject.allDay = allDay;
  			copiedEventObject.backgroundColor = $(this).css("background-color");
  			copiedEventObject.borderColor = $(this).css("border-color");

  			// render the event on the calendar
  			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
  			$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

  			// is the "remove after drop" checkbox checked?
  			if ($('#drop-remove').is(':checked')) {
  				// if so, remove the element from the "Draggable Events" list
  				$(this).remove();
  			}

  		}
  	});
	
	function art_open1(title, url, tableid) {
		var d = dialog({
				id : 'dialog2',
				title : title,
				url : url,
				width : 768,
				fixed : true,
				onremove : function () {
					$('#calendar').fullCalendar('refetchEvents');
				}
			});
		d.showModal();
	}	

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
  	});
  	$("#add-new-event").click(function (e) {
  		e.preventDefault();
  		//Get value and make sure it is not null
  		var val = $("#new-event").val();
  		if (val.length == 0) {
  			return;
  		}

  		//Create events
  		var event = $("<div />");
  		event.css({
  			"background-color" : currColor,
  			"border-color" : currColor,
  			"color" : "#fff"
  		}).addClass("external-event");
  		event.html(val);
  		$('#external-events').prepend(event);

  		//Add draggable funtionality
  		ini_events(event);

  		//Remove event from text input
  		$("#new-event").val("");
  	});
  });
</script>	
