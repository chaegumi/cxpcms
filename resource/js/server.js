//

if (typeof toastr != 'undefined') {
	// 提示组件配置
	toastr.options = {
		"closeButton" : true,
		"debug" : false,
		"newestOnTop" : false,
		"progressBar" : true,
		"positionClass" : "toast-top-center",
		"preventDuplicates" : false,
		"onclick" : null,
		"showDuration" : "300",
		"hideDuration" : "1000",
		"timeOut" : "5000",
		"extendedTimeOut" : "1000",
		"showEasing" : "swing",
		"hideEasing" : "linear",
		"showMethod" : "fadeIn",
		"hideMethod" : "fadeOut"
	}
}
// 删除确认框
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
						var json = $.parseJSON(data);
						if (json.success) {
							if (args1.length > 3) {
								
								toastr.options.onShown = function () {
									var table = $('#' + args1[3]).DataTable();
									table.draw();
								}
								location.href = window.location.hash + '&_t=' + Math.random();
							}else{
								// location.href = '';
								location.href = window.location.hash + '&after=del';
							}
							toastr.success(json.msg);
						} else {
							toastr.error(json.msg);
						}
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

function changefieldvalue(rowid, tbname, tbfieldname) {
	$.post(BASE_URL + "?c=ajax&m=setfieldvalue", {
		tbname : tbname,
		tbfield : tbfieldname,
		tbfieldvalue : $("#" + tbfieldname + rowid).val(),
		id : rowid,
		csrf_test_name : $.cookie('csrf_cookie_name')
	},
		function (data) {});
}

function reset_dialog() {
	var dialog = top.dialog.get(window);
	// var dialog = top.dialog.get('dialog1');
	if (dialog) {
		dialog.width(768);
		dialog.height($('body').height() + 25);
		dialog.reset();
	}
}

function art_open(title, url, tableid) {
	var d = dialog({
			id : 'dialog2',
			title : title,
			url : url,
			width : 768,
			fixed : true,
			onremove : function () {
				var table = $('#' + tableid).DataTable();
				table.draw(false);
			}
		});
	d.showModal();
}


$(function () {

	$.validator.setDefaults({
		ignore : "",
		highlight : function (element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			reset_dialog();
		},
		unhighlight : function (element) {
			$(element).closest('.form-group').removeClass('has-error');
			reset_dialog();
		}
	});

	$(document).on('click', '.cancel-form', function () {
		var dialog = top.dialog.get(window);
		if (typeof dialog != 'undefined') {
			dialog.remove();
		}
	});
});

$(function () {

	$(document).on('click', '.sidebar-menu a', function () {
		$(this).parent('li').addClass('active');
		$(this).parent('li').siblings('li').removeClass('active');
	});
}); ;
(function ($) {
	/*
	 *  javascript复杂对象转url参数字符串
	 */
	var parseParam = function (param, key) {
		var paramStr = "";
		if (param instanceof String || param instanceof Number || param instanceof Boolean) {
			paramStr += "&" + key + "=" + encodeURIComponent(param);
		} else {
			$.each(param, function (i) {
				var k = key == null ? i : key + (param instanceof Array ? "[" + i + "]" : "." + i);
				paramStr += '&' + parseParam(this, k);
			});
		}
		return paramStr.substr(1);
	};
	var loadURL = function (uri) {
		$('.content-wrapper').load(BASE_URL + uri, function (response, status, xhr) {
			var source = '<section class="content-header">\
								      <h1>\
								        Msg\
								        <small></small>\
								      </h1>\
								      <ol class="breadcrumb">\
								        <li><a href="#/"><i class="fa fa-dashboard"></i> Dashboard</a></li>\
								        <li class="active">Message</li>\
								      </ol>\
								    </section>\
								    <section class="content">\
										<div class="alert alert-<%= status %> alert-dismissible">\
								            <h4><i class="icon fa fa-<%= icon %>"></i> Notice</h4>\
								            <%= msg %>\
								        </div>\
								    </section>';

			if (status == 'error') {
				var msg = "Sorry but there was an error: ";
				msg = msg + xhr.status + " " + xhr.statusText;
				var render = template.compile(source);
				var html = render({
						icon : 'ban',
						status : 'danger',
						msg : msg
					});
				$('.content-wrapper').html(html);
			} else {
				if (response.indexOf('{"success":') !== -1) {
					var json = $.parseJSON(response);
					if (json.success) {}
					else {
						var msg = json.msg;
						var render = template.compile(source);
						var html = render({
								icon : 'info',
								status : 'info',
								msg : msg
							});
						$('.content-wrapper').html(html);
					}
				}
			}
		});
	};
	var app = $.sammy(function () {
			this.disable_push_state = true;
			this.get('#/', function () {
				loadURL('?c=welcome&m=dashboard');
			});
			this.get('#/(.*)', function () {
				var splat = this.params['splat'];
				var request_uri = parseParam(this.params);
				request_uri = request_uri.substring(0, request_uri.indexOf('&splat'));
				if (request_uri) {
					var request_url = '?c=' + splat + '&' + request_uri;
				} else {
					var request_url = '?c=' + splat;
				}
				loadURL(request_url);
			});
		});
	$(function () {
		app.run('#/');
	});
})(jQuery);