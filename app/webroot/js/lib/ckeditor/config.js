CKEDITOR.editorConfig = function( config )
{
	config.skin = 'ob';
	config.toolbar = 'Lenore';
	config.toolbar_Lenore =
	[
		['Cut','Copy','Paste'],
		['Bold','Italic','-','Subscript','Superscript'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['NumberedList','BulletedList','Blockquote','CreateDiv'],
		['Link','Unlink','Anchor'],
		['Image','Table','HorizontalRule'],
		['Format'],['Font'],['FontSize'],['TextColor'],
		['Source']
	];
	config.htmlEncodeOutput = false;
	config.entities = false;
};
