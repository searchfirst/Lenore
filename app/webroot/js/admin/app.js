var AppView = Backbone.View.extend({
	initialize: function() {
		var messages = new MessageList,
			msg_page_view = new MessagePageView({
				el: $('.messages ul.paginate').get(0),
				collection: messages
			}),
			view = new MessageView({
				el: $('.messages ul:first-of-type').get(0),
				collection: messages,
				pageView: msg_page_view
			});
		messages.bind('fetched',function(en){view.render(this)});
		messages.fetch();
	}
});

$(function(){
	var LenoreApp = new AppView;
});
