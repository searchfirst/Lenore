/***************************************
 replace specified images with SVG images
Written by Alexis "Fyrd" Deveria, 11/28/2007
            Version 1.0
****************************************

Please see http://my.opera.com/Fyrd/blog/svg-image-and-background-image-replacer for details and a demo of this script
License: http://creativecommons.org/licenses/LGPL/2.1/

*/

(function SvgReplace() {

	var testImg = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNzUiIGhlaWdodD0iMjc1Ij48L3N2Zz4%3D',
		img = document.createElement('img'),
		imgExt = 'svg',
		dotted = '.'+imgExt+'.',
		regexp = new RegExp ("(\\."+imgExt+")\\.\\w*([\\W]*)","i"),
		imgs = document.getElementsByTagName('img');
	
	function bind(e,element,callback) {
		if (element.addEventListener) element.addEventListener(e,callback,false);
	    else if (element.attachEvent) element.attachEvent("on"+e,callback);
	}
	
	function activate() {
		if(document.styleSheets) {
			var ss=document.styleSheets;
			for(var i=0;i<ss.length;i++) {
				var sh=ss[i],rules = (sh.cssRules)?sh.cssRules:sh.rules;
				for(var j=0;j<rules.length;j++){
					var rule=rules[j];
					if(rule.style && rule.style.backgroundImage){
						var bg = rule.style.backgroundImage;
						if (bg.indexOf(dotted) != -1) {
							bg=bg.replace(regexp,'$1$2');
							rule.style.backgroundImage = bg;
						}
					}
				}
			}
		}
		//Go through images
		for(var i=0;i<imgs.length;i++)
			if(imgs[i].src.indexOf(dotted) != -1)
				imgs[i].src = imgs[i].src.replace(regexp,'$1$2');
	}

	img.setAttribute('src',testImg);
	if(img.complete) {
		img.style.visibility='hidden';
		img.style.position='absolute';
		document.body.appendChild(img);
		if(img.width >= 100) {
			document.body.removeChild(img);
			activate();
		} else {
			document.body.removeChild(img);
		}	
	} else {
		bind('load',img,function(){window.setTimeout(function(){activate()},1)});
	}
})();