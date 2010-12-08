$(document).ready(function() {
	$('#content > h2:first-child, #content h3, span, a').hookMenu();
	$('ul.tab_hooks').duxTab();
	$('div.flags').flagToggle();
	LenoreCore
		.ckEditorInit()
		.loadAJAXDialogLinks()
		.loadFlashMessages()
		.loadSortableLists()
		.loadEditableMeta();
	
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
});

var LenoreCore = function($) {return {
	ckEditorInit: function() {
		$('textarea.rich').ckeditor({
			customConfig:'/js/lib/ckeditor/config.js'
		});
		return this;
	},
	loadFlashMessages: function() {
		$('.message')
			.css('display','block')
			.prepend('<span class="msg_close"></span>');
		$('.message').find('span.msg_close').bind('click',function(e){
				$(this).parent('div.message').slideUp('fast');
		});
		return this;
	},
	loadAJAXDialogLinks: function() {
		$('a.ajax-modal').live('click',function(e) {
			e.preventDefault();
			var uri = $(this).attr('href'),
				dialog_title = $(this).attr('title'),
				dialog;
			if(!$('div#ajax-modal-dialog').length) {
				dialog = $('<div id="ajax-modal-dialog"></div>').appendTo('body');
			} else {
				dialog = $('div#ajax-modal-dialog');
				dialog.html('');
			}
			dialog.load(uri,{},function(r,s,xHR){
				dialog.dialog({modal:true,minHeight:0,title:dialog_title});
			});
		});
		return this;
	},
	loadSortableLists: function() {
		var sortable_lists = $('#content ul.sortable');
		sortable_lists.each(function(i){
			var current_s_list = $(this);
			current_s_list.children('li').prepend('<span class="mover"></span>');
			current_s_list.find('li span.mover').disableSelection().attr('tabindex','0').addTouch();
			current_s_list
				.data('controller',current_s_list.attr('class').split(" ")[1])
				.sortable({
					stop: function(e,ui){
						current_s_list.data('Final',$(this).sortable('serialize',{key:'data[Final][]'}));
						ajax_data_string = current_s_list.data('Initial') + '&' + current_s_list.data('Final');
						ajax_url = '/admin/' + current_s_list.data('controller') + '/reorder';
						$.ajax({
							url:ajax_url,
							data:ajax_data_string,
							type:'POST',
							success:function(d,s,xHR){
								if(d=='Fail'){
									window.location.reload();
								} else {
									current_s_list.data('Initial',current_s_list.sortable('serialize',{key:'data[Initial][]'}));
								}
							},
							fail:function(d,s,xHR) {
								window.location.reload();
							}
						});
					},
					helper: function(e,ui) {
						ui.children().each(function(){$(this).width($(this).width());});
						return ui;
					},
					placeholder: 'sortable-placeholder',
					handle: 'span.mover',
					axis: 'y'
				})
				.data('Initial',current_s_list.sortable('serialize',{key:'data[Initial][]'}));
		});
		return this;
	},
	loadEditableMeta: function() {
		var editable = $('div.meta span.editable');
		if(editable.length > 0) {
		 	var editable_form = $('div.meta form'),
				editable_url = editable_form.attr('action'),
				e_id = editable_url.split('/')[4],
				e_controller = editable.parents('ul').eq(0).attr('class');

			editable
				.bind('click',function(e){})
				.editableText({newlinesEnabled:false})
				.bind('change',function(e){
					var eThis = $(this),
						changedText = eThis.html(),
						e_method = eThis.attr('class').split(' ')[1],
						post_data = new Object();
					post_data["data[" + e_controller + "][" + e_method + "]"] = changedText;
					post_data["data[" + e_controller + "][id]"] = e_id;
					$.ajax({type:'POST',url:editable_url,dataType:'text',data:post_data});
				});
			editable_form.remove();
		}
		return this;
	}
}}(jQuery);

