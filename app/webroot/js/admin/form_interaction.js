$(document).ready(function() {
	$('button').click(function(){
		$(this).parent().submit();
	});
	$('.delete_form button').click(function(){
		var yesdelete = confirm('Really ' + $(this).attr('title') + '?');
		if(yesdelete) {
			var hiddeninput = $(this).siblings("input[name*='[id]']").get(0);
			var checkvalue = $(this).siblings("input[name*='check_id']").get(0);
			alert(checkvalue);
			$(hiddeninput).attr('value',$(checkvalue).attr("value"));
			//hiddeninput = $(this).parent().children('input[@name=data[Article][id]][@type=hidden]');
			$(this).parent().submit();
			return true;
		}
		else {
			return false;
		}
	});
});
