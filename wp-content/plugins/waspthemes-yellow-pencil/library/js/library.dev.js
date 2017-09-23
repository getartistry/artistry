/*
	A Helper javascript function for Yellow Pencil Editor;
	CSS Animation trigger and Custom CSS Engine.
	Visit the plugin website to the details: waspthemes.com/yellow-pencil

	By WaspThemes / All Rights Reserved.
*/
(function($) {

    "use strict";

    /* jQuery Parallax Scrolling plugin */
	!function(a){"use strict";a.fn.simple_parallax=function(b){var c={speed:1,x:50},d=a.extend(c,b);return this.each(function(){var b=a(this);if("none"==b.css("background-image")||1==b.hasClass("yp-parallax-disabled"))return!1;d.speed<1&&(d.speed=1);var c=-(a(window).scrollTop()/10*d.speed),e=""+d.x+"% "+c+"px";b.css({backgroundPosition:e}),a(window).scroll(function(){if("none"==b.css("background-image")||1==b.hasClass("yp-parallax-disabled"))return!1;d.speed<1&&(d.speed=1);var c=-(a(window).scrollTop()/10*d.speed),e=""+d.x+"% "+c+"px";b.css({backgroundPosition:e})})})}}(jQuery);


    // jQuery Visible Plugin
    !function(t){var i=t(window);t.fn.visible=function(t,e,o){if(!(this.length<1)){var r=this.length>1?this.eq(0):this,n=r.get(0),f=i.width(),h=i.height(),o=o?o:"both",l=e===!0?n.offsetWidth*n.offsetHeight:!0;if("function"==typeof n.getBoundingClientRect){var g=n.getBoundingClientRect(),u=g.top>=0&&g.top<h,s=g.bottom>0&&g.bottom<=h,c=g.left>=0&&g.left<f,a=g.right>0&&g.right<=f,v=t?u||s:u&&s,b=t?c||a:c&&a;if("both"===o)return l&&v&&b;if("vertical"===o)return l&&v;if("horizontal"===o)return l&&b}else{var d=i.scrollTop(),p=d+h,w=i.scrollLeft(),m=w+f,y=r.offset(),z=y.top,B=z+r.height(),C=y.left,R=C+r.width(),j=t===!0?B:z,q=t===!0?z:B,H=t===!0?R:C,L=t===!0?C:R;if("both"===o)return!!l&&p>=q&&j>=d&&m>=L&&H>=w;if("vertical"===o)return!!l&&p>=q&&j>=d;if("horizontal"===o)return!!l&&m>=L&&H>=w}}}}(jQuery);


    // Reverse prototype
    String.prototype.reverse = function () {
		return this.split('').reverse().join('');
	};

	// Replace last reverse item
	String.prototype.replaceLast = function (what, replacement) {
		return this.reverse().replace(new RegExp(what.reverse()), replacement.reverse()).reverse();
	};


    // Check if this is editor page.
    function is_yellow_pencil() {

        if ($("body").hasClass("yp-yellow-pencil")) {

            return true;

        } else {

            if ($(document).find(".yp-select-bar").length > 0) {

                return true;

            } else {

                return false;

            }

        }

    }


    // Getting All Selectors from CSS Output
    function get_all_selectors(source){

        // if no source, stop.
        if (source == '') {
            return false;
        }

        // if have a problem in source, stop.
        if (source.split('{').length != source.split('}').length) {
            return false;
        }

        source = source.toString().replace(/\}\,/g, "}");

        // Getting All CSS Selectors.
        var allSelectors = array_cleaner(source.replace(/\{(.*?)\}/g, '|BREAK|').split("|BREAK|"));

        return allSelectors;

    }


    // Minify Output CSS
    function get_minimized_css(data, media){

        // Clean.
        data = data.replace(/(\r\n|\n|\r)/g, "").replace(/\t/g, '');

        // Don't care rules in comment.
        data = data.replace(/\/\*(.*?)\*\//g, "");

        // clean.
        data = data.replace(/\}\s+\}/g, '}}').replace(/\s+\{/g, '{');

        // clean.
        data = data.replace(/\s+\}/g, '}').replace(/\{\s+/g, '{');
        data = filter_bad_queries(data);

        // Don't care rules in media query
        if (media === true) {
            data = data.replace(/@media(.*?)\}\}/g, '').replace(/@?([a-zA-Z0-9_-]+)?keyframes(.*?)\}\}/g, '').replace(/@font-face(.*?)\}\}/g, '').replace(/@import(.*?)\;/g, '').replace(/@charset(.*?)\;/g, '');
        }

        // data
        return data;

    }


    // Filtering bad queries
    function filter_bad_queries(data) {
        return data.replace(/[\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]/g, '');
    }


    // Delete the empty array items
    function array_cleaner(actual) {

        var uniqueArray = [];
        $.each(actual, function(i, el) {
            if ($.inArray(el, uniqueArray) === -1) uniqueArray.push(el);
        });

        return uniqueArray;

    }


    // Loads CSS once.
    window.cacheCSS = false;

    // Getting CSS Data from Live Preview, external CSS etc.
    function get_css_data(){

    	// Stop if not CSS Output.
        if ($("style#yellow-pencil,style#yp-live-preview,link#yp-custom-css,style#yellow-pencil-backend").length === 0) {
            return false;
        }

        // CSS Data
        var data = '';

        // Check if not external CSS
        if (window.externalCSS == false) {

            // Adds backend CSS
            if ($("#yellow-pencil-backend").length > 0) {
                data = data + $("#yellow-pencil-backend").html();
            }

            // Adds Default CSS
            if ($("style#yellow-pencil").length > 0) {
                data = data + $("style#yellow-pencil").html();
            }

            // Adds live preivew CSS
            if ($("style#yp-live-preview").length > 0) {
                data = data + $("style#yp-live-preview").html();
            }

        } else { // else external

        	// Adds Default CSS
            data = window.externalData;

            // Adds live preivew CSS
            if ($("style#yp-live-preview").length > 0) {
                data = data + $("style#yp-live-preview").html();
            }

            // Adds backend CSS
            if ($("#yellow-pencil-backend").length > 0) {
                data = data + $("#yellow-pencil-backend").html();
            }

        }

        // Cache
        if(window.cacheCSS == false){
        	window.cacheCSS = data;
        }

        return window.cacheCSS;

    }


    // Search and finds All selectors by filter
    function get_matches_selectors(filter) {

    	// CSS Data
    	var data = get_css_data();

    	// minData
    	var minData = get_minimized_css(data,true);

    	// Getting all selectors by data
    	var selectors = get_all_selectors(minData);

    	// Array
        var array = [];

        // Each all selectors
        $.each(selectors, function(i, v) {

        	// Skip if not valid
            if (v.match(/\:|\}|\{|\;/)) {
                return true;
            }

            // if filter has and selector valid empty
            if(v.indexOf(filter) >= 0 && v != '') {

            	// Replace filter and push the selector to array.
                array.push(v.replace(filter, ""));

            }

        });


        // Getting all CSSOut, not filtering media queries.
        var dataWithMedia = get_minimized_css(data,false);

        // Getting all media contents
        var mediaAll = dataWithMedia.match(/@media(.*?){(.*?)}/g);

        // Variables
        var content = '';
        var condition = '';
        var mediaSelectors = '';

        // Each all media Queries
        $.each(mediaAll, function(index, media) {

        	// Media condition
        	condition = media.match(/@media(.*?){/g).toString().replace(/\{/g, '').replace(/@media /g, '').replace(/@media/g, '');

        	// Media Content
        	content = media.toString().replace(/@media(.*?){/g,'');

        	// All selectors of media
        	mediaSelectors = get_all_selectors(content);

        	// Eaching all selectors of media
        	$.each(mediaSelectors, function(childIndex, v) {

	        	// Skip if not valid
	            if (v.match(/\:|\}|\{|\;/)) {
	                return true;
	            }

	        	// if media works current screen size and selector has the filter
	        	if(window.matchMedia(condition).matches && v.indexOf(filter) >= 0 && v != ''){

	        		// Replace filter and push the selector to array.
					array.push(v.replace(filter,""));

				}

			});

        });


        // Return
        return array.toString();

    }


    // Click event support for animations
    function click_detect() {

        // Each all
        $(get_matches_selectors(".yp_click")).each(function() {

        	// Adds event
            $(this).click(function() {

            	// yp_click class will trigger the defined animation.
                $(this).addClass("yp_click");
            });

        });

    }


    // Visible event support for animations
    function onscreen_detect() {

    	// Finds all onScreen elements
        $(get_matches_selectors(".yp_onscreen")).each(function() {

        	// Add visible event
            if ($(this).visible(true)) {

            	// yp_onscreen will trigger the defined animation.
                $(this).addClass("yp_onscreen");

            }

        });

    }




	/* ---------------------------------------------------- */
	/* CSS Engine Function		                            */
	/* ---------------------------------------------------- */
	function cssEngine(rule,scriptMarkup,defaults,data){

		// Minify data
		data = get_minimized_css(data,true);

		// get all matches
		var matches = data.match(new RegExp(rule+"(\s+)?:", "gi"));

		if(!matches){
			return false;
		}

		// Check if has matches
		if(matches.length > 0){
		
			// Each all matches
			for (var ix = 0; ix < matches.length; ix++){
				
				var output = scriptMarkup;
				var selector = '';
				
				// ruleData : Selector, rule, value
				var ruleData = data.match(new RegExp('}(.*){(.*)'+rule+'(\s+)?:(.*);'));

				// Delete proccessed rule.
				data = data.replaceLast(rule+":","processed-rule:");
				
				// check
				if(ruleData == null){
					return true;
				}

				// Get Selector
				selector = $.trim(ruleData[1]);

				// Clean selector
				if(selector.indexOf("}") != -1){
					selector = $.trim(ruleData[1].split("}")[ruleData[1].split("}").length-1]);
				}
				
				// Check selector. Support just nth-child. (don't support ex: hover, focus etc.)
				if(selector.indexOf(":") >= 0 && selector.indexOf(":nth") == -1){
					return true;
				}
				
				// Getting rule value
				var value = $.trim(ruleData[4].split(";")[0]);
				

				// Getting other properties for current selector.
				// Need for getting params of the custom CSS rule.
				var otherProperties = ruleData[4];
				
				// Clean
				if(ruleData[4].indexOf("}") != -1){
					otherProperties = ruleData[4].split("}")[0];
				}

				// Getting all rules of selector
				var allRules = (ruleData[2]+otherProperties).replace(value,"").split(";");

				var allRulesArray = [];
				var item,ruleName;
				for (var i = 0; i < allRules.length; i++){

					// loop item
					item = $.trim(allRules[i]);

					// Adds if valid
					if(item != null && item != '' && item != ',' && item != undefined){

						// Push.
						allRulesArray.push(allRules[i]);

					}


				}
				
				// All other properties in an array.
				allRules = allRulesArray;
				
				
				// Getting Default values
				if(defaults != undefined){

					// Each properties
					for (var e = 0; e < Object.keys(defaults).length; e++){
						
						// Checks
						if(allRules.join(",").indexOf(Object.keys(defaults)[e]+":") == -1){

							// Update Keys as rule name.
							allRules.push(Object.keys(defaults)[e]+":"+defaults[Object.keys(defaults)[e]]);

						}
						
					}

				}
				


				// Replace default rule names to the rules value in script output
				for (var s = 0; s < allRules.length; s++){

					// Getting Rule Name
					ruleName = allRules[s].replace(/\"\)*$/, '').split(":")[0];

					// Replace with rule value
					output = output.replace(ruleName,allRules[s].replace(/\"\)*$/, '').split(":")[1]);

				}
				


				// USE: $selector  ->  rule address. (string)
				output = output.replace(/\$selector/g,selector);
				
				// USE: $value  ->  rule value. (string)
				output = output.replace(/\$value/g,value);

				// USE: $rule  ->  rule. (string)
				if(rule.indexOf("jquery-") != -1){
					output = output.replace(/\$rule/g,rule.split("-")[1]);
				}else{
					output = output.replace(/\$rule/g,rule);
				}
				
				// USE: $self  ->  rule address. (object)
				output = output.replace(/\$self/g,"$('"+selector+"')");
				
				// Replacing..
				output = output.replace(/undefined/g,"0");
				
				// If main rule value is active.
				if(value != 'none' && value != '0'){
					
					// If is the editor page
					if(is_yellow_pencil()){

						var ifrm = $("#iframe")[0],iwind = ifrm.contentWindow;

						iwind.eval("(function($){"+output+"}(jQuery));");
					
					}else{
							
						// eval scripts for website.
						eval(output);
							
					}
					
				}
				
			}
			
		}
	
	}
	
	
	// A helper jQuery plugin for calling the function
	// easily from the main editor javascript file.
	$.fn.CallCSSEngine = function(source){

		// Calls all defined custom rules
		customRules(source);

	}
	
	
	// List of custom CSS Rules. We configure it here.
	function customRules(source){

		// Getting all other CSS
		var cssData = get_css_data();

		// Adds if avaiable.
		if(cssData != false){
			source = source + cssData;
		}

		// Update
		source = "#yellow-pencil{-yp-engine:true;}" + source;


		// Parallax Background CSS Engine.
		cssEngine(
			
			// Rule name.
			"background-parallax",
			
			// Markup of jquery api.
			"$self.simple_parallax({speed: background-parallax-speed, x: background-parallax-x});",
			
			{ // Defaults
				'background-parallax-speed': '1',
				'background-parallax-x': '50'
			},
			
			source
			
		);

		if (!is_yellow_pencil()) {

			// Check after resize
			$(window).resize(function() {
				onscreen_detect();
			});

			// Check after document ready
			$(document).ready(function() {
				onscreen_detect();
				click_detect();
			});

			// Check while scroll for onScreen event
			$(document).scroll(function() {
				onscreen_detect();
			});

		}
	
	}



	/* ---------------------------------------------------- */
	/* Setup   							                    */
	/* ---------------------------------------------------- */
    if (!is_yellow_pencil()) {

    	// Checks if has external CSS File
        if ($("link#yp-custom-css").length > 0) {

        	// Define
            window.externalCSS = true;
            window.externalData = true;

            // Getting URL
            var href = $("link#yp-custom-css").attr("href");

            // Load the CSS output from custom CSS file
            $.when($.get(href)).done(function(data) {

            	// Cache custom CSS Data
                window.externalData = data;

                // Calls CSS Engine
				customRules('');

            });

        } else {

        	// Define
            window.externalCSS = false;

            // Call CSS Engine
            customRules('');

        }

    }else{

    	// Calling CSS Engine after iframe loaded in Editor.
    	$('#iframe').on("load", function(){
			customRules('');
		});

    }


}(jQuery));