/***************************************
 replace specified images with SVG images
Written by Alexis "Fyrd" Deveria, 11/28/2007
            Version 1.0
****************************************

Please see http://my.opera.com/Fyrd/blog/svg-image-and-background-image-replacer for details and a demo of this script
License: http://creativecommons.org/licenses/LGPL/2.1/

*/

//window.onload = SvgReplace;

function SvgReplace() {
	
	
	var testImg = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNzUiIGhlaWdodD0iMjc1Ij48L3N2Zz4%3D';
	var img = document.createElement('img')
	img.setAttribute('src',testImg);

	if(img.complete) {
		img.style.visibility='hidden';
		img.style.position='absolute';
		document.body.appendChild(img);
		
		window.setTimeout(function() {
			if(img.width >= 100) {
				document.body.removeChild(img);
				setCSS();
			} else {
				document.body.removeChild(img);
			}	
		},500);
		
	} else {
		console.log('here')
		$(img).load(function() {window.setTimeout(function(){setCSS();},500);});
	}
}

function setCSS() {
	var imgExt = 'svg';
	var dotted = '.'+imgExt+'.';
	var regexp = new RegExp ("(\\."+imgExt+")\\.\\w*([\\W]*)","i");

	if(document.styleSheets) {
		var ss=document.styleSheets;
		
		console.log(document.styleSheets);
		
		for(var i=0;i<ss.length;i++) {
			var sh=ss[i];
			var rules = (sh.cssRules)?sh.cssRules:sh.rules;
		  
			for(var j=0;j<rules.length;j++){
				var curRule=rules[j];
				if(curRule.style){
					if(curRule.style.backgroundImage){
						var bg = curRule.style.backgroundImage;

						if (bg.indexOf(dotted) != -1) {
							bg=bg.replace(regexp,'$1$2');
							curRule.style.backgroundImage = bg;
						}
					}
				}
			}
		}
	}
	
	
	//Go through images
	var imgs = document.getElementsByTagName('img');
	for(var i=0;i<imgs.length;i++) {
		if(imgs[i].src.indexOf(dotted) != -1) {
			imgs[i].src = imgs[i].src.replace(regexp,'$1$2');
		}
	}
}
