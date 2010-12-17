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

var AppController = Backbone.Controller.extend({
	routes: {
		"^$":"app_index"
	},
	initialize: function() {
		this.messages = new MessageList;
		this.messagesPageView = new MessagePageView({
			el: $('.messages ul.paginate').get(0),
			collection: this.messages
		});
		this.messagesView = new MessageView({
			el: $('.messages ul:first-of-type').get(0),
			collection: this.messages,
			pageView: this.messagesPageView
		});
		this.messages.bind('fetched',_.bind(this.messagesView.render,this.messagesView));
	},
	app_index: function() {
		this.messages.fetch();
	}
});
$(function(){
	var LenoreApp = new AppView,
			LenoreAppController = new AppController;
	Backbone.history.start();
});
