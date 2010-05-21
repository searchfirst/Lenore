$(document).ready(function(){
	$("textarea:not(.plain)").jTagEditor({	
		tagSet: '/admin/js/jquery/jtageditor/markdown/jtageditor_markdown.js',
		tagMask:"",
		insertOnShiftEnter:"",
		insertOnCtrlEnter:""
	});
});