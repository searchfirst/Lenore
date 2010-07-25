$(document).ready(function() {
	$('#content > h2:first-child, span').hookMenu();
	$('ul.tab_hooks').duxTab();
	loadSortableLists();
	loadSortableTables();
	loadAJAXDialogLinks();
	
	$('img.flag').each(function(i){$(this).after(" ["+$(this).attr('alt')+"]");});
	
	$('nav#menu li').filter(function(index){ return $('div',this).length==1; }).bind({
		mouseenter: function(e){
			var leftpoint = $(this).position().left + 'px';
			$(this).children('div').css('left',leftpoint).fadeIn(100);
		},
		mouseleave: function(e){
			$(this).children('div').fadeOut('fast');
		}
	});

	$('.message').dialog({modal:true});
});


function loadAJAXDialogLinks() {
	$('a.ajax-modal').click(function(e) {
		var uri = $(this).attr('href');
		var dialog_title = $(this).attr('title');
		if(!$('div#ajax-modal-dialog').length) {
			var dialog = $('<div id="ajax-modal-dialog"></div>').appendTo('body');
		} else {
			var dialog = $('div#ajax-modal-dialog');
			dialog.html('');
		}
		dialog.load(uri,{},function(r,s,xHR){
			dialog.dialog({modal:true,minHeight:0,title:dialog_title});
		});
		e.preventDefault();
	});
}

function loadSortableLists() {
	var sortable_lists = $('#content ul.sortable');
	sortable_lists.each(function(i){
		var current_s_list = this;
		$(current_s_list).data('controller',$(current_s_list).attr('class').split(" ")[1]);
		$(current_s_list).sortable({
			stop: function(e,ui){
				$(current_s_list).data('Final',$(this).sortable('serialize',{key:'data[Final][]'}));
				ajax_data_string = $(current_s_list).data('Initial') + '&' + $(current_s_list).data('Final');
				ajax_url = '/admin/' + $(current_s_list).data('controller') + '/reorder';
				$.ajax({
					url:ajax_url,
					data:ajax_data_string,
					type:'POST',
					success:function(d,s,xHR){
						if(d=='Fail'){
							window.location.reload();
						} else {
							$(current_s_list).data('Initial',$(current_s_list).sortable('serialize',{key:'data[Initial][]'}));
						}
					},
					fail:function(d,s,xHR) {
						window.location.reload();
					}
				});
			},
			helper: function(e,ui) {
				ui.children().each(function() {$(this).width($(this).width());});
				return ui;
			},
			placeholder: 'sortable-placeholder'
		});
		$(current_s_list).data('Initial',$(current_s_list).sortable('serialize',{key:'data[Initial][]'}));
	});	
}

function loadSortableTables() {
	var sortable_tables = $('#content table.sortable');
	sortable_tables.each(function(i){
		var current_s_table = this;
		$(current_s_table).data('controller',$('tbody',current_s_table).attr('class'));
		$('tbody',current_s_table).sortable({
			stop: function(e,ui){
				$(current_s_table).data('Final',$(this).sortable('serialize',{key:'data[Final][]'}));
				ajax_data_string = $(current_s_table).data('Initial') + '&' + $(current_s_table).data('Final');
				ajax_url = '/admin/' + $(current_s_table).data('controller') + '/reorder';
				$.ajax({
					url:ajax_url,
					data:ajax_data_string,
					type:'POST',
					success:function(d,s,xHR){
						if(d=='Fail'){
							window.location.reload();
						} else {
							$(current_s_table).data('Initial',$('tbody',current_s_table).sortable('serialize',{key:'data[Initial][]'}));
						}
					},
					fail:function(d,s,xHR) {
						window.location.reload();
					}
				});
			},
			helper: function(e,ui) {
				ui.children().each(function() {$(this).width($(this).width());});
				return ui;
			},
			placeholder: 'sortable-placeholder'
		});
		$(current_s_table).data('Initial',$('tbody',current_s_table).sortable('serialize',{key:'data[Initial][]'}));
	});
}