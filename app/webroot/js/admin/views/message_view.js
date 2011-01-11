var MessagePageView = Backbone.View.extend({
	events: {
		'click a': 'test'
	},
	test: function(e) {
		var new_page = $(e.target).data('page'); 
		this.collection.page = new_page;
		this.collection.fetch();
		return false;
	},
	redrawPageLinks: function() {
		var pl_tpl = _.template("<li><a href=\"/admin/messages/index/<%= page %>\" data-page=<%= page %>><%= page %></a></li>"),
			cpl_tpl = _.template("<li><%= page %></li>"),
			page_info = this.collection.pageInfo(),
			data = '';
			_(page_info.pages).times(function(i){
				var x=i+1;
				data=data+(x==page_info.page?cpl_tpl({page:x}):pl_tpl({page:x}))
			});
		$(this.el).html(data);	
		return this;
	}
});

var MessageView = Backbone.View.extend({
	events: {
		'click a':'viewModal'
	},
	render: function() {
		console.log('render');
		this.redrawLists();
	},
	redrawLists: function() {
		var item_tpl= _.template("<li><a href=\"/admin/messages/view/<%= id %>\" data-id=\"<%= id %>\"><%= subject %></a><ul class=\"hook_menu\"><li><a href=\"/admin/messages/delete/<%= id %>\" class=\"ajax-modal delete\">Delete Message</a></li></ul></li>"),
			data = this.collection
				.map(function(message){return item_tpl(message.attributes)})
				.reduce(function(x,y){return x+y},'');
		$(this.el).html(data).find('a').hookMenu();			
		this.options.pageView.redrawPageLinks();
		return this;
	},
	viewModal: function(e) {
		if($(e.target).data('id')) {
			var message = this.collection.get($(e.target).data('id')),
					item_tpl = _.template("<article><h1><%= subject %></h1><%= info %><p><%= content %></p></article>"),
					info_tpl = {
						m: _.template("<li><%= key %>: <%= value %></li>"),
						mwrap: _.template("<ul><%= value %></ul>"),
						s: _.template("<p class=\"info\"><%= key %>: <%= value %></p>"),
						swrap: _.template("<%= value %>")
					},
					info = [], 
					msg = {},
					add_params = message.get("additional_parameters");
			info.push({
				"key": "From",
				"value": (message.get('name')+(message.get('email')?' <'+message.get('email')+'>':''))
			});
			_(add_params).each(function(value,key){info.push({'key':key.replace('_',' '),'value':value})});
			msg.info = info.map(function(i){return info_tpl[info.length>1?"m":"s"](i)}).reduce(function(m,i){return m + i});
			msg.info = info_tpl[info.length>1?"mwrap":"swrap"]({value:msg.info});
			msg.subject = message.get('subject');
			msg.content = message.get('content');
			if(message) {
				$(item_tpl(msg)).dialog({
					title:msg.subject,
					modal:true,
					close: function(e,u){$(this).dialog('destroy').remove()}
				});
				return false;
			}
		}
	}
});
