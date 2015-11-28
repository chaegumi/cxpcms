
$(function(){
	var searchbox = $(".search");
	var searchtxt = $("#keyword");
	var searchbtn = $('#sbtn');
	var tiptext = searchtxt.val();
	if(searchtxt.val() == "" || searchtxt.val() == tiptext) {
		searchtxt.val(tiptext);
	}
	searchtxt.focus(function(e) {
		if(searchtxt.val() == tiptext) {
			searchtxt.val('');
		}
	});
	searchtxt.blur(function(e) {
		if(searchtxt.val() == "") {
			searchtxt.val(tiptext);
		}
	});
	searchbtn.click(function(e) {
		if(searchtxt.val() == "" || searchtxt.val() == tiptext) {
			return false;
		}
	});
});