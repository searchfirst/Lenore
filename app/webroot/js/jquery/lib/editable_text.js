/**
 * editableText plugin that uses contentEditable property (FF2 is not supported)
 * Project page - http://github.com/valums/editableText
 * Copyright (c) 2009 Andris Valums, http://valums.com
 * Licensed under the MIT license (http://valums.com/mit-license/)
 */
(function(){
    /**
     * The dollar sign could be overwritten globally,
     * but jQuery should always stay accesible
     */
    var $ = jQuery;
	/**
     * Extending jQuery namespace, we
     * could add public methods here
     */
	$.editableText = {};
    $.editableText.defaults = {		 
		/**
		 * Pass true to enable line breaks.
		 * Useful with divs that contain paragraphs.
		 */
		newlinesEnabled : false,
		/**
		 * Event that is triggered when editable text is changed
		 */
		changeEvent : 'change'
	};   		
	/**
	 * Usage $('selector).editableText(optionArray);
	 * See $.editableText.defaults for valid options 
	 */		
    $.fn.editableText = function(options){
        var options = $.extend({}, $.editableText.defaults, options);
        
        return this.each(function(){
             // Add jQuery methods to the element
			var editable = $(this);
			editable
				.attr({tabindex:0,contentEditable:true,role:'textbox'}).bind('keypress keydown',startEditing)
				.wrap('<div role="form"></div>');
			/**
			 * Save value to restore if user presses cancel
			 */
			var prevValue = editable.html();
			
			// Create edit/save buttons
            var buttons = $(
				'<div class="editableToolbar">' +
            		'<a href="#" class="save" role="button" aria-hidden="true"></a>' +
            		'<a href="#" class="cancel" role="button" aria-hidden="true"></a>' +
            	'</div>')
				.insertBefore(editable);

			// Save references and attach events

			buttons.find('.save').click(function(){
				stopEditing();
				editable.trigger(options.changeEvent);
				return false;
			});

			buttons.find('.cancel').click(function(){
				stopEditing();
				editable.html(prevValue);
				return false;
			});		
			
			// Hide controls
			buttons.children().css('display', 'none');
			
			if (!options.newlinesEnabled){
				// Prevents user from adding newlines to headers, links, etc.
				editable.keypress(function(event){
					// event is cancelled if enter is pressed
					return event.which != 13;
				});
			}
			
			/**
			 * Makes element editable
			 */
			function startEditing(e){
				if(!$(this).data('alreadyEditing')) {
					buttons.children().fadeIn().attr('aria-hidden',false);
					$(this).data('alreadyEditing',true);
				}
				if(e.keyCode == 13) return false;
			}
			/**
			 * Makes element non-editable
			 */
			function stopEditing(e){
				buttons.children().fadeOut().attr('aria-hidden',true);
				editable.data('alreadyEditing',false);
			}
        });
    }
})();