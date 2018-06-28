/*
Description: Opens external links in a new window based on open in new window plugin Version: 2.4 by Keith P. Graham
*/
function myc_open_tab_action(event) {
	try {
		var b=document.getElementsByTagName("a");
		var ksrv=window.location.hostname;
		ksrv=ksrv.toLowerCase();
		for (var i = 0; i < b.length; i++) {
			/* IE 6 bug - the anchor might not be a link and might not support hostname */
			if (b[i] && b[i].href) {
				if (!(b[i].title)) {
					var ih=b[i].innerHTML;
					if (ih.indexOf('<img')==-1) { /* check for img tag */
						b[i].title=MyCremoveHTMLTags(b[i].innerHTML);
					}
				}
				var khref=b[i].href;
				khref=khref.toLowerCase();
				if ( b[i].target==null || b[i].target=='')  {
					if (khref.indexOf('//')!=-1) { /* check to see if target is on this domain.*/
						var no=b[i].rel;
						if (no==null||no=='') {
							no="noopener noreferrer";
						} else {
							no+=" noopener noreferrer";
						}
						if (b[i].hostname && location.hostname) {
							if (b[i].hostname.toLowerCase() != location.hostname.toLowerCase()) {
								b[i].target="_blank";
								b[i].rel=no;
							}
						} 
						if (b[i].target!="_blank"&&khref.indexOf(ksrv)==-1) { 					
							b[i].target="_blank";
							b[i].rel=no;
						}
					}
				}
			}
		}
	} catch (ee) {}
}
/* set the onload event */
if (document.addEventListener) {
	document.addEventListener("DOMContentLoaded", function(event) { myc_open_tab_action(event); }, false);
} else if (window.attachEvent) {
	window.attachEvent("onload", function(event) { myc_open_tab_action(event); });
} else {
	var oldFunc = window.onload;
	window.onload = function() {
		if (oldFunc) {
			oldFunc();
		}
			myc_open_tab_action('load');
		};
}
function MyCremoveHTMLTags(ihtml){
	try {
		ihtml = ihtml.replace(/&(lt|gt);/g, function (strMatch, p1){
			return (p1 == "lt")? "<" : ">";
		});
		return ihtml.replace(/<\/?[^>]+(>|$)/g, "");
	} catch (eee) {
		return '';
	}
}	