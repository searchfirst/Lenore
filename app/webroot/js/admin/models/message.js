var Message = Backbone.Model.extend({
	
	//	Message Model
	//		Fields:
	//			id,name,email,subject
	//		
	
	EMPTY_SUBJECT: "[No Subject]",
	
	initialize: function(opt) {
		if (!opt.subject) {
			this.set({"subject":this.EMPTY_SUBJECT});
		}
		/*if(!opt || !opt.id || !opt.name) {
			throw "InvalidConstructArgs";
		}*/
	}
});
