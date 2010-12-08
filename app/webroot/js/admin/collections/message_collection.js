var MessageList = PaginatedCollection.extend({
	model: Message,
	base_url: '/admin/messages'
});
