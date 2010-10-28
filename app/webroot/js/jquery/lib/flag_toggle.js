(function($){
	jQuery.fn.flagToggle = function(settings) {
		settings = $.extend({},settings);
		this.each(function(i) {
			var tgl_info = {
					postUri:$(this).data('tgl-uri'),
					model:$(this).data('tgl-mdl'),
					idValue:$(this).data('tgl-id'),
					idKey:'data['+$(this).data('tgl-mdl')+'][id]'
				},
				tgl_items = $(this).find('li[role=checkbox]');
			tgl_items.bind('click.flagToggle',function(e){
				var tgl_item = $(this),
					current_state = tgl_item.attr('aria-checked')=='true'?1:0,
					field_name = tgl_item.data('tgl-fld'),
					field_key = 'data['+tgl_info.model+']['+tgl_item.data('tgl-fld')+']',
					field_state = current_state?0:1,
					post_data = {};
					post_data[tgl_info.idKey] = tgl_info.idValue;
					post_data[field_key] = field_state;
					$.ajax({
						data: post_data,
						type: 'POST',
						url: tgl_info.postUri,
						context: tgl_item.get(),
						success: function(data,status,xHR) {
							if(data.status=='success' && data[tgl_info.model]) {
								var checked_state = data[tgl_info.model][field_name]=='1'?'true':'false',
									new_html = data.flag_text[field_name];
								$(this).attr('aria-checked',checked_state).html(new_html);
							} else {/*not sure about this yet*/}
						}
					});
			});
		});
		return this;
	};
})(jQuery);