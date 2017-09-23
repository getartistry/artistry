;(function($) {

    "use strict";

    /* ---------------------------------------------------- */
    /* Setup Ace Editor.                                    */
    /* ---------------------------------------------------- */
    ace.config.set("basePath",window.aceEditorBase);
    ace.require("ace/ext/language_tools");
    var editor = ace.edit("cssData");
    editor.getSession().setMode("ace/mode/css");
    editor.setTheme("ace/theme/twilight");
    editor.getSession().setUseWrapMode(true);
    editor.$blockScrolling = Infinity;


    // enable autocompletion and snippets
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: false,
        enableLiveAutocompletion: true
    });


    // Set font size to editor
    if ($(window).height() > 790) {
        editor.setOptions({
            fontSize: "17px"
        });
    } else {
        editor.setOptions({
            fontSize: "15px"
        });
    }


    // All Yellow Pencil Functions.
    window.yellow_pencil_main = function() {

            // https://github.com/rafaelcaricio/gradient-parser/
            // Copyright (c) 2014 Rafael Caricio. All rights reserved.
            var GradientParser=GradientParser||{};GradientParser.parse=function(){var result;function c(a){var c=new Error(b+": "+a);throw c.source=b,c}function d(){var a=e();return b.length>0&&c("Invalid input not EOF"),a}function e(){return t(f)}function f(){return g("linear-gradient",a.linearGradient,i)||g("repeating-linear-gradient",a.repeatingLinearGradient,i)||g("radial-gradient",a.radialGradient,l)||g("repeating-radial-gradient",a.repeatingRadialGradient,l)}function g(b,d,e){return h(d,function(d){var f=e();return f&&(F(a.comma)||c("Missing comma before color stops")),{type:b,orientation:f,colorStops:t(u)}})}function h(b,d){var e=F(b);if(e)return F(a.startCall)||c("Missing ("),result=d(e),F(a.endCall)||c("Missing )"),result}function i(){return j()||k()}function j(){return E("directional",a.sideOrCorner,1)}function k(){return E("angular",a.angleValue,1)}function l(){var c,e,d=m();return d&&(c=[],c.push(d),e=b,F(a.comma)&&(d=m(),d?c.push(d):b=e)),c}function m(){var a=n()||o();if(a)a.at=q();else{var b=p();if(b){a=b;var c=q();c&&(a.at=c)}else{var d=r();d&&(a={type:"default-radial",at:d})}}return a}function n(){var a=E("shape",/^(circle)/i,0);return a&&(a.style=D()||p()),a}function o(){var a=E("shape",/^(ellipse)/i,0);return a&&(a.style=B()||p()),a}function p(){return E("extent-keyword",a.extentKeywords,1)}function q(){if(E("position",/^at/,0)){var a=r();return a||c("Missing positioning value"),a}}function r(){var a=s();if(a.x||a.y)return{type:"position",value:a}}function s(){return{x:B(),y:B()}}function t(b){var d=b(),e=[];if(d)for(e.push(d);F(a.comma);)d=b(),d?e.push(d):c("One extra comma");return e}function u(){var a=v();return a||c("Expected color definition"),a.length=B(),a}function v(){return x()||z()||y()||w()}function w(){return E("literal",a.literalColor,0)}function x(){return E("hex",a.hexColor,1)}function y(){return h(a.rgbColor,function(){return{type:"rgb",value:t(A)}})}function z(){return h(a.rgbaColor,function(){return{type:"rgba",value:t(A)}})}function A(){return F(a.number)[1]}function B(){return E("%",a.percentageValue,1)||C()||D()}function C(){return E("position-keyword",a.positionKeywords,1)}function D(){return E("px",a.pixelValue,1)||E("em",a.emValue,1)}function E(a,b,c){var d=F(b);if(d)return{type:a,value:d[c]}}function F(a){var c,d;return d=/^[\n\r\t\s]+/.exec(b),d&&G(d[0].length),c=a.exec(b),c&&G(c[0].length),c}function G(a){b=b.substr(a)}var a={linearGradient:/^(\-(webkit|o|ms|moz)\-)?(linear\-gradient)/i,repeatingLinearGradient:/^(\-(webkit|o|ms|moz)\-)?(repeating\-linear\-gradient)/i,radialGradient:/^(\-(webkit|o|ms|moz)\-)?(radial\-gradient)/i,repeatingRadialGradient:/^(\-(webkit|o|ms|moz)\-)?(repeating\-radial\-gradient)/i,sideOrCorner:/^to (left (top|bottom)|right (top|bottom)|left|right|top|bottom)/i,extentKeywords:/^(closest\-side|closest\-corner|farthest\-side|farthest\-corner|contain|cover)/,positionKeywords:/^(left|center|right|top|bottom)/i,pixelValue:/^(-?(([0-9]*\.[0-9]+)|([0-9]+\.?)))px/,percentageValue:/^(-?(([0-9]*\.[0-9]+)|([0-9]+\.?)))\%/,emValue:/^(-?(([0-9]*\.[0-9]+)|([0-9]+\.?)))em/,angleValue:/^(-?(([0-9]*\.[0-9]+)|([0-9]+\.?)))deg/,startCall:/^\(/,endCall:/^\)/,comma:/^,/,hexColor:/^\#([0-9a-fA-F]+)/,literalColor:/^([a-zA-Z]+)/,rgbColor:/^rgb/i,rgbaColor:/^rgba/i,number:/^(([0-9]*\.[0-9]+)|([0-9]+\.?))/},b="";return function(a){return b=a.toString(),d()}}();


            /* ---------------------------------------------------- */
            /* Fix multiple load problem.                           */
            /* ---------------------------------------------------- */
            if ($("body").hasClass("yp-yellow-pencil-loaded")){
                return false;
            }


            /* ---------------------------------------------------- */
            /* Windows                                              */
            /* ---------------------------------------------------- */
            window.setSelector = false;
            window.leftbarWidth = 46;
            window.separator = ' ';
            window.minCroppedSelector = false;


            /* ---------------------------------------------------- */
            /* Setup Default Varriables                             */
            /* ---------------------------------------------------- */
            var iframe = $('#iframe').contents();
            var iframeBody = iframe.find("body");
            var body = $(document.body).add(iframeBody);
            var mainDocument = $(document).add(iframe);
            var mainBody = $(document.body);

            // puse js
            var iframejs = document.getElementById('iframe');
            iframejs = (iframejs.contentWindow || iframejs.contentDocument);
            iframejs = iframejs.document;


            /* ---------------------------------------------------- */
            /* Adding yp-animating class to all animating elements  */
            /* ---------------------------------------------------- */
            iframe.find(window.basic_not_selector).on('animationend webkitAnimationEnd oanimationend MSAnimationEnd',function(){

                // Stop if any yp animation tool works
                if(body.hasClass("yp-anim-creator") || body.hasClass("yp-animate-manager-active")){
                    return false;
                }

                var element = $(this);

                // remove animating class.
                if(element.hasClass("yp-animating")){
                    element.removeClass("yp-animating");
                }

                // Set outline selected style if selected element has animating.
                if(element.hasClass("yp-selected") && is_content_selected()){
                    body.removeClass("yp-has-transform");
                    draw();
                }

                return false;

            });


            /* ---------------------------------------------------- */
            /* Animation Manager: Scroll                            */
            /* ---------------------------------------------------- */
            $(".yp-animate-manager-inner").on("scroll",function(){

                var l = $(this).scrollLeft();
                $(".yp-anim-control-right").css("left",-Math.abs(l));

                $(".yp-anim-left-part-column").css("left",l);

            });


            /* ---------------------------------------------------- */
            /* Wireframe Button                                     */
            /* ---------------------------------------------------- */
            $(".yp-wireframe-btn").click(function(){
                body.toggleClass("yp-wireframe-mode");
                $(".yp-editor-list > li.active > h3").trigger("click");
                gui_update();
            });


            /* ---------------------------------------------------- */
            /* Rotate the logo every 1 min                          */
            /* ---------------------------------------------------- */
            setInterval(function(){
                $(".yellow-pencil-logo").toggleClass("yp-logo-play");
            },80000);


            /* ---------------------------------------------------- */
            /* Check Undoable / Redoable                            */
            /* ---------------------------------------------------- */
            check_undoable_history();


            /* ---------------------------------------------------- */
            /* Animation Manager: Play                              */
            /* ---------------------------------------------------- */
            $(".yp-anim-control-play").on("click",function(){

                if($(this).hasClass("active")){
                    return false;
                }

                body.addClass("yp-animate-manager-playing yp-clean-look yp-hide-borders-now");

                // Find largest line for play/stop.
                var maxWidth = Math.max.apply( null, $( '.yp-anim-process-inner' ).map( function (){
                    return $( this ).outerWidth( true );
                }).get() );


                var s = (parseFloat(maxWidth)/100);
                $("#yp-animate-helper").html("@-webkit-keyframes playingBorder{from{left: 0px;}to{left:"+maxWidth+"px;}}@keyframes playingBorder{from{left: 0px;}to{left:"+maxWidth+"px;}}");

                $(".yp-anim-playing-border").css("animation-duration",s+"s").css("-webkit-animation-duration",s+"s").addClass("active");

                $(this).addClass("active");

                var S_inMS = (s*1000);
                clear_animation_timer();

                window.animationTimer3 = setTimeout(function(){
                    $(".yp-anim-control-pause").trigger("click");
                },S_inMS);


                // Playing over width
                $(".yp-anim-playing-over").css("width",maxWidth+$(window).width());


                // Play animations
                iframe.find('[data-rule="animation-name"]').each(function(i){

                    var data = $(this).html().replace(/\/\*(.*?)\*\//g, "");

                    // Variables
                    var selector = data.split("{")[0];

                    // Get Selector
                    if(selector.indexOf("@media") != -1){
                        selector = data.split("{")[1].split("{")[0];
                    }

                    selector = selector.replace(".yp_hover","").replace(".yp_focus","").replace(".yp_click","").replace(".yp_onscreen","");

                    iframe.find(selector).each(function(){
                        $(this).addClass("yp_hover yp_focus yp_click yp_onscreen");
                    });

                });

                // Counter
                //yp-counter-min
                //yp-counter-second
                //yp-counter-ms
                var min = 0;
                window.animMinC = setInterval(function(){

                   // min
                   min = min+1;if(ms == 59){ms = 0;}
                   
                   var result = min;
                   if(min < 10){
                   result = "0"+min;
                   }

                   $(".yp-counter-min").text(result);

                },60000);

                var second = 0;
                window.animSecC = setInterval(function(){

                   // Sc
                   second = second+1;
                   
                   var result = second;
                   if(second < 10){
                   result = "0"+second;
                   }
                   $(".yp-counter-second").text(result);

                },1000);

                var ms = 0;
                window.animMsC = setInterval(function(){

                   // Ms
                   ms = ms+1;if(ms == 99){ms = 0;}

                   var result = ms;
                   if(ms < 10){
                   result = "0"+ms;
                   }
                   $(".yp-counter-ms").text(result);

                },1);

            });

        
            /* ---------------------------------------------------- */
            /* Animation Manager: Pause                             */
            /* ---------------------------------------------------- */
            $(".yp-anim-control-pause").on("click",function(){

                clearTimeout(window.yp_anim_player);

                $(".yp-anim-playing-border").removeClass("active");
                $(".yp-anim-control-play").removeClass("active");

                    // Pause animations
                    iframe.find('[data-rule="animation-name"]').each(function(i){

                        // Variables
                        var data = $(this).html().replace(/\/\*(.*?)\*\//g, "");
                        var array = data.split("{");
                        var selector = array[0];

                        // Get Selector
                        if(selector.indexOf("@media") != -1){
                            
                            selector = array[1].split("{")[0];
                        }

                        selector = selector.replace(".yp_hover","").replace(".yp_focus","").replace(".yp_click","").replace(".yp_onscreen","");

                    iframe.find(selector).each(function(){
                        $(this).removeClass("yp_hover yp_focus yp_click yp_onscreen");
                    });

                });

                body.removeClass("yp-animate-manager-playing yp-clean-look yp-hide-borders-now");

                //yp-counter-min
                //yp-counter-second
                //yp-counter-ms
                $(".yp-counter-min").text("00");
                $(".yp-counter-second").text("00");
                $(".yp-counter-ms").text("00");
                clearInterval(window.animMinC);
                clearInterval(window.animSecC);
                clearInterval(window.animMsC);

            });

            
            /* ---------------------------------------------------- */
            /* Close Animation Manager                          */
            /* ---------------------------------------------------- */
            $(".yp-anim-control-close,.yp-visual-editor-link").on("click",function(){
                $(".animation-manager-btn").trigger("click");
            });


            /* ---------------------------------------------------- */
            /* Open Animation Manager                               */
            /* ---------------------------------------------------- */
            $(".animation-manager-btn").on("click",function(){

                 body.toggleClass("yp-animate-manager-active");
                 $(".yp-animate-manager").toggle();
                 $(".yp-anim-control-pause").trigger("click");
                 if(!$(this).hasClass("active")){

                    // CSS To Data.
                    if (mainBody.hasClass("yp-need-to-process")) {
                        process(false, false);
                    }

                    animation_manager();

                    // Find largest line for play/stop.
                    var maxWidth = Math.max.apply( null, $( '.yp-anim-process-inner' ).map( function (){
                        return $( this ).outerWidth( true );
                    }).get() );

                    // Always add +$(window).width() to animate bar width on start.
                    $(".yp-anim-process-bar-area").width(maxWidth+$(window).width());

                 }

                if($(".animation-option.active").length > 0){
                    $(".animation-option.active h3").trigger("click");
                    $(".animation-option.active").removeAttr("data-loaded");
                }

                insert_default_options();

                draw();

            });


            /* ---------------------------------------------------- */
            /* Animation Manager: delete animate                    */
            /* ---------------------------------------------------- */
            $(document).on("mouseenter", ".yp-control-trash", function() {
                $(this).parent().tooltip('hide');
            });


            /* ---------------------------------------------------- */
            /* Animation manager: delete animate                    */
            /* ---------------------------------------------------- */
            $(document).on("click", ".yp-control-trash", function() {

                var that = $(this);

                swal({
                  title: "You are sure?",
                  showCancelButton: true,
                  confirmButtonText: "Delete Animate",
                  closeOnConfirm: true,
                  animation: false
                },function(){

                    that.parent(".yp-anim-process-bar").prev(".yp-anim-process-bar-delay").remove();
                    that.parent(".yp-anim-process-bar").remove();

                    body.addClass("yp-anim-removing");

                    $(".yp-delay-zero").each(function(){

                        var allLeft = $(".yp-anim-process-inner").offset().left-5;
                        var left = $(this).next(".yp-anim-process-bar").offset().left-allLeft;
                        $(this).css("left",left);

                        $(this).next(".yp-anim-process-bar").addClass("yp-anim-has-zero-delay");

                    });

                    update_animation_manager();

                    body.removeClass("yp-anim-removing");
                    
                    animation_manager();

                });

            });

    
            /* ---------------------------------------------------- */
            /* get selected element object                          */
            /* ---------------------------------------------------- */
            function get_selected_element(){

                return iframe.find(".yp-selected");

            }


            /* ---------------------------------------------------- */
            /* IS Functions List                                    */
            /* ---------------------------------------------------- */
            function is_content_selected(){

                return mainBody.hasClass("yp-content-selected");

            }

            function is_dragging(){

                return mainBody.hasClass("yp-dragging");

            }

            function is_resizing(){

                return mainBody.hasClass("yp-element-resizing");

            }

            function is_visual_editing(){

                return mainBody.hasClass("yp-visual-editing");

            }

            function is_responsive_mod(){

                return mainBody.hasClass("yp-responsive-device-mode");

            }

            function is_animate_creator(){

                return mainBody.hasClass("yp-anim-creator");

            }
        

            /* ---------------------------------------------------- */
            /* Delete the editor classes by class List              */
            /* ---------------------------------------------------- */
            function get_cleaned_classes(el,oldArray){

                var resultArray = [];

                // Element Classes
                var classes = el.attr("class");

                // Is defined?
                if(isDefined(classes)){

                    // Cleaning all Yellow Pencil classes.
                    classes = class_cleaner(classes);

                    // Clean spaces
                    classes = space_cleaner(classes);

                    // If not empty
                    if(classes.length >= 1){

                        var classesArray = get_classes_array(classes);

                        // Be sure there have more class then one.
                        if(classesArray.length > 0){

                            // Each
                            for(var io = 0;io < classesArray.length; io++){

                                // Clean spaces
                                var that = space_cleaner(classesArray[io]);

                                // continue If not have this class in data
                                if(resultArray.indexOf(that) == -1 && oldArray.indexOf(that) == -1 && that.length >= 1){

                                    // Push.
                                    resultArray.push(that);

                                }

                            }

                        }else{

                            // continue If not have this class in data
                            if(resultArray.match(classes) == -1 && oldArray.indexOf(classes) == -1){

                                // Push
                                resultArray.push(classes);

                            } // If

                        } // else

                    } // not empty.

                } // IsDefined

                // return.
                return resultArray;

            }

    
            /* ---------------------------------------------------- */
            /* Updating Design information                          */
            /* ---------------------------------------------------- */
            function update_design_information(type){


                // Was wireframe?
                var washaveWireFrame = false;


                // Check wireframe
                if(body.hasClass("yp-wireframe-mode")){
                    washaveWireFrame = true;
                    body.removeClass("yp-wireframe-mode");
                }


                // Cache elements
                var elementMain = $(".info-element-general"),
                elementClasseslist = $(".info-element-class-list"),
                elementSelectorList = $(".info-element-selector-list");


                // Clean Old data
                $(".info-element-general,.info-element-class-list,.info-element-selector-list").empty();


                // Updating Section.
                if(type != 'element'){


                    // Delete Old
                    $(".info-color-scheme-list,.info-font-family-list,.info-animation-list,.info-basic-typography-list,.info-basic-size-list").empty();


                    // Get elements as variable.
                    var colorlist = $(".info-color-scheme-list"),
                    familylist = $(".info-font-family-list"),
                    animatelist = $(".info-animation-list"),
                    sizelist = $(".info-basic-size-list"),
                    typographyList = $(".info-basic-typography-list"),
                    globalclasslist = $(".info-global-class-list"),
                    globalidlist = $(".info-global-id-list");


                    // Variables
                    var maxWidth = 0,
                    maxWidthEl = null,
                    k = $(window).width();


                    // Append general elements
                    iframeBody.append("<h1 id='yp-heading-test-level-1'></h1><h2 id='yp-heading-test-level-2'></h2><h3 id='yp-heading-test-level-3'></h3><h4 id='yp-heading-test-level-4'></h4><h5 id='yp-heading-test-level-5'></h5><h6 id='yp-heading-test-level-6'></h6><h6 id='yp-paragraph-test'></h6>");


                    // Font Sizes
                    var paragraphElement = iframeBody.find("#yp-paragraph-test"),
                    bodyFontSize = (Math.round( parseFloat( iframeBody.css("fontSize") ) * 10 ) / 10),
                    paragraphFontSize = (Math.round( parseFloat( paragraphElement.css("fontSize") ) * 10 ) / 10);


                    // Font family
                    var bodyFamily = iframeBody.css("fontFamily");
                    var paragraphFamily = paragraphElement.css("fontFamily");


                    // Update typography information
                    typographyList
                    .append('<li><span class="typo-list-left">General (body)</span><span class="typo-list-right"><span>'+bodyFontSize+'px, '+get_font_name(bodyFamily)+'</span></span></li>')
                    .append('<li><span class="typo-list-left">Paragraph</span><span class="typo-list-right"><span>'+paragraphFontSize+'px, '+get_font_name(paragraphFamily)+'</span></span></li>');


                    // Delete created element. (Created only for test)
                    paragraphElement.remove();

                    var appendData = '';

                    // Update Heading tags. h1 > h6
                    for(var i = 1; i <= 6; i++){

                        var el = iframeBody.find("#yp-heading-test-level-"+i);
                        var size = parseFloat(el.css("fontSize"));
                        size = Math.round( size * 10 ) / 10;
                        var family = el.css("fontFamily");

                        appendData += '<li><span class="typo-list-left">Heading Level '+i+'</span><span class="typo-list-right"><span>'+size+'px, '+get_font_name(family)+'</span></span></li>';

                        el.remove();

                    }

                    // append after the loop
                    typographyList.append(appendData);


                    // Each all elements for find what we need.
                    var ColoredEl = [];
                    var familyArray = [];
                    var animatedArray = [];
                    var classArray = [];
                    var idArray = [];
                    var boxSizingArray = [];

                    // Each
                    iframeBody.find(get_all_elements()).each(function(i){


                        // Element
                        var el = $(this);


                        // Find container
                        var otherWidth = el.outerWidth();

                        // 720 768 940 960 980 1030 1040 1170 1210 1268
                        if(otherWidth >= 720 && otherWidth <= 1268 && otherWidth < (k-80)){
                            if(otherWidth > maxWidth){
                                maxWidthEl = el;
                            }

                            // MaxWidth Element Founded. (Container)
                            maxWidth = Math.max(otherWidth, maxWidth);

                        }


                        // Filter font family elements.
                        var family = get_font_name(el.css("fontFamily"));
                        if(familyArray.indexOf(family) == -1){
                            familyArray.push(family);
                        }


                        // Filter colored elements.
                        var color = el.css("backgroundColor").toLowerCase().replace(/ /g,"");
                        if(color != 'transparent' && color != 'rgb(255,255,255)' && color != 'rgba(0,0,0,0)' && color != 'rgba(255,255,255,0)'){
                            ColoredEl.push(this);
                        }


                        // Get box sizing
                        if(i < 20){ // Get only on first 20 elements. no need to more.
                            var boxSizing = (el.css("boxSizing"));
                            if(isDefined(boxSizing)){

                                boxSizing  = $.trim(boxSizing);

                                if(boxSizingArray.indexOf(boxSizing) == -1){
                                    boxSizingArray.push(boxSizing);
                                }

                            }
                        }


                        // Find classes and ids
                        setTimeout(function(){

                            // If there not have any class in our list
                            if(globalclasslist.find("li").length === 0){

                                // Get Cleaned classes.
                                var arrayClassAll = get_cleaned_classes(el,classArray);

                                // Concat if not empty.
                                if(arrayClassAll.length > 0){
                                    classArray = classArray.concat(arrayClassAll);
                                }

                            }


                            // Get ID
                            // If there not have any id in our list.
                            if(globalidlist.find("li").length === 0){

                                // Get Id
                                var id = el.attr("id");

                                // is defined
                                if(isDefined(id)){

                                    // continue If not have this class in data
                                    if(idArray.indexOf(id) == -1){

                                        // Push
                                        idArray.push(id);

                                    }

                                }

                            }


                        },500);

                    });

    
                    // Filter animated elements.
                    iframe.find(".yp-styles-area [data-rule='animation-name']").each(function(){

                        var animate = escape_data_value($(this).html());

                        if(animatedArray.indexOf(animate) == -1){
                            animatedArray.push(animate);
                        }

                    });


                    // Not adding on responsive mode.
                    var containerWidth;
                    if(is_responsive_mod() === false){

                        containerWidth = maxWidth+'px';

                    }else{
                        containerWidth = 'Unknown';
                    }


                    appendData = '';
        
                    // Apply colors
                    $(ColoredEl).each(function(){

                        var el = $(this);
                        var color = el.css("backgroundColor").toLowerCase().replace(/ /g,"");

                        var current = $(".info-color-scheme-list div[data-color='"+color+"']");
                        var ratio = parseFloat(100/$(ColoredEl).length);

                        if(current.length > 0){
                            var cWi = parseFloat(current.attr("data-width"));
                            current.css("width",(cWi+ratio)+"%");
                            current.attr("data-width",(cWi+ratio));
                        }else{
                            appendData += '<div data-width="'+ratio+'" data-color="'+color+'" style="width:'+ratio+'%;background-color:'+color+';"></div>';
                        }

                    });

                    colorlist.append(appendData);


                    appendData = '';

                    // Update fonts
                    $.each(familyArray,function(i,v){
                        appendData += "<li>"+v+"</li>";
                    });

                    familylist.append(appendData);


                    appendData = '';

                    // Update animations.
                    $.each(animatedArray,function(i,v){
                        appendData += "<li>"+v+"</li>";
                    });

                    animatelist.append(appendData);


                    // Append Size information to size section.
                    sizelist.append('<li><span class="typo-list-left">Box Sizing</span><span class="typo-list-right"><span>'+boxSizingArray.toString()+'</span></span></li>')
                    .append('<li><span class="typo-list-left">Container Width</span><span class="typo-list-right"><span>'+containerWidth+'</span></span></li>')
                    .append('<li><span class="typo-list-left">Document Width</span><span class="typo-list-right"><span>'+(parseInt(iframe.width())+window.leftbarWidth)+'px</span></span></li>')
                    .append('<li><span class="typo-list-left">Document Height</span><span class="typo-list-right"><span>'+iframe.height()+'px</span></span></li>');


                    // waiting a litte for high performance.
                    setTimeout(function(){

                        appendData = '';

                        // Append classes
                        $.each(classArray,function(i,v){
                            appendData += "<li>."+v+"</li>";
                        });

                        globalclasslist.append(appendData);

                        appendData = '';

                        // Append ids
                        $.each(idArray,function(i,v){
                            appendData += "<li>#"+v+"</li>";
                        });

                        globalidlist.append(appendData);

                    },1000);


                }


                // if is element Section
                if(is_content_selected()){


                    // Hide and show some sections in design information
                    $(".info-no-element-selected").hide();
                    $(".info-element-selected-section").show();
                    $("info-element-selector-section").hide();


                    // cache selected element
                    var selectedEl = get_selected_element();
                    var selectedID = selectedEl.attr("id");


                    // Getting element ID.
                    if(isDefined(selectedID)){

                        // Is valid?
                        if(selectedID !== ''){

                            // Append
                            elementMain.append('<li><span class="typo-list-left">Element ID</span><span class="typo-list-right"><span>#'+selectedID+'</span></span></li>');

                        }

                    }


                    // Append Tag name
                    elementMain.append('<li><span class="typo-list-left">Tag</span><span class="typo-list-right"><span>'+selectedEl[0].nodeName+'</span></span></li>');


                    // Append Affected count
                    elementMain.append('<li><span class="typo-list-left">Affected elements</span><span class="typo-list-right"><span>'+(parseInt(iframeBody.find(".yp-selected-others").length)+1)+'</span></span></li>');


                    // Get class Array
                    var classSelfArray = get_cleaned_classes(selectedEl,[]);

                    var x;

                    appendData = '';

                    // Append all classes.
                    for(x = 0; x < classSelfArray.length; x++){

                        // Append
                        appendData += "<li>."+classSelfArray[x]+"</li>";

                    }

                    elementClasseslist.append(appendData);


                    // Hide class section if empty.
                    if(elementClasseslist.find("li").length === 0){
                        $(".info-element-classes-section").hide();
                    }else{
                        $(".info-element-classes-section").show();
                    }


                    // Current Selector
                    elementSelectorList.append('<li>'+get_current_selector()+'</li>');


                    // Create dom data. For show DOM HTML in Design information tool
                    var clone = selectedEl.clone();


                    // Clean basic position relative style from clone
                    if(isDefined(clone.attr("style"))){

                        // Trim Style
                        var trimCloneStyle = clone.attr("style").replace(/position:(\s*?)relative(\;?)|animation-fill-mode:(\s*?)(both|forwards|backwards|none)(\;?)/g,"");

                        // Remove Style Attr if is empty.
                        if(trimCloneStyle == ''){
                            clone.removeAttr("style");
                        }else{
                            clone.attr("style",trimCloneStyle);
                        }

                    }

                    // Remove Class Attr.
                    clone.removeAttr("class");


                    // Just add valid classes.
                    for(x = 0; x < classSelfArray.length; x++){

                        // addClass.
                        clone.addClass(classSelfArray[x]);

                    }

                    // Dom Content.
                    clone.html("...");

                    // Get.
                    var str = $("<div />").append(clone).html();

                    // Set
                    $(".info-element-dom").val(str);

                    // Box Model
                    update_box_model();


                // Show there no element selected section.
                }else{

                    $(".info-no-element-selected").show();

                    $(".info-element-selected-section").hide();

                }

                // Active wireframe if was active before open.
                // Notice: This function close wireframe for getting colors and details of the elements.
                if(washaveWireFrame === true){
                    body.addClass("yp-wireframe-mode");
                }

            }


            /* ---------------------------------------------------- */
            /* Update Box Model                                     */
            /* ---------------------------------------------------- */
            function update_box_model(){

                // Element
                var el = get_selected_element();

                // Margin
                $(".model-margin-top").text(parseInt(el.css("marginTop")));
                $(".model-margin-right").text(parseInt(el.css("marginRight")));
                $(".model-margin-bottom").text(parseInt(el.css("marginBottom")));
                $(".model-margin-left").text(parseInt(el.css("marginLeft")));

                // Padding Model
                $(".model-padding-top").text(parseInt(el.css("paddingTop")));
                $(".model-padding-right").text(parseInt(el.css("paddingRight")));
                $(".model-padding-bottom").text(parseInt(el.css("paddingBottom")));
                $(".model-padding-left").text(parseInt(el.css("paddingLeft")));

                // Border Model
                $(".model-border-top").text(parseInt(el.css("borderTopWidth")));
                $(".model-border-right").text(parseInt(el.css("borderRightWidth")));
                $(".model-border-bottom").text(parseInt(el.css("borderBottomWidth")));
                $(".model-border-left").text(parseInt(el.css("borderLeftWidth")));

                // Size Model
                $(".model-size").text(parseInt(el.width()) + " x " + parseInt(el.height()));

            }


            /* ---------------------------------------------------- */
            /* Lock Properties                                      */
            /* ---------------------------------------------------- */
            $(".lock-btn").on("click",function(){

                // Toggle active
                $(this).toggleClass("active");

            });


            /* ---------------------------------------------------- */
            /* Getting first Font Family                            */
            /* ---------------------------------------------------- */
            function get_font_name(family){

                if(family.indexOf(",") != -1){
                    family = family.split(",")[0];
                }

                family = $.trim(family).replace(/\W+/g, " ");

                return family;

            }


            /* ---------------------------------------------------- */
            /* Design information: Close                            */
            /* ---------------------------------------------------- */
            $(".advanced-close-link").on("click",function(){
                $(".advanced-info-box").hide();
                $(".info-btn").removeClass("active");
            });


            /* ---------------------------------------------------- */
            /* Design information: Advanced / Info Section          */
            /* ---------------------------------------------------- */
            $(".info-btn").on("click",function(){

                if(!$(this).hasClass("active")){
                    $(".element-btn").trigger("click");
                    var max = $(window).height()-$(this).offset().top;
                    $(".advanced-info-box").css({"top":$(this).offset().top,"max-height": max});
                    update_design_information('all');
                }

                $(".advanced-info-box").toggle();

            });


            /* ---------------------------------------------------- */
            /* Design information: Typography Section               */
            /* ---------------------------------------------------- */
            $(".typography-btn").on("click",function(){
                $(this).parent().find(".active").removeClass("active");
                $(this).addClass("active");
                $(".typography-content,.element-content,.advanced-content").hide();
                $(".typography-content").show();
            });


            /* ---------------------------------------------------- */
            /* Design information: Element Section                  */
            /* ---------------------------------------------------- */
            $(".element-btn").on("click",function(){
                $(this).parent().find(".active").removeClass("active");
                $(this).addClass("active");
                $(".element-content,.typography-content,.advanced-content").hide();
                $(".element-content").show();
            });


            /* ---------------------------------------------------- */
            /* Design information: Advanced Section                 */
            /* ---------------------------------------------------- */
            $(".advanced-btn").on("click",function(){
                $(this).parent().find(".active").removeClass("active");
                $(this).addClass("active");
                $(".element-content,.typography-content,.advanced-content").hide();
                $(".advanced-content").show();
            });

            /* ---------------------------------------------------- */
            /* Design information: Scroll to top on tab click       */
            /* ---------------------------------------------------- */
            $(".advance-info-btns").on("click",function(){
                $(".advanced-info-box-inner").scrollTop(0);
            });


            /* ---------------------------------------------------- */
            /* Animation Manager: Generating Manager                */
            /* ---------------------------------------------------- */
            function animation_manager(){

                $(".yp-animate-manager [data-toggle='tooltipAnim']").tooltip("destroy");
                $(".yp-anim-process-bar-delay,.yp-anim-process-bar").resizable('destroy');
                $(".yp-anim-el-column,.yp-animate-bar").remove();

                // Update metric
                $(".yp-anim-metric").empty();
                for(var i = 1; i < 61; i++){
                    $(".yp-anim-metric").append('<div class="second"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><b>'+i+'s</b></div>');
                }

                iframe.find('[data-rule="animation-name"]').each(function(iX){

                    // Variables
                    var data = $(this).html().replace(/\/\*(.*?)\*\//g, "");
                    var device = $(this).attr("data-size-mode");
                    var array = data.split("{");
                    var selector = array[0];
                    var animateName = escape_data_value(data);
                    var animateDelayOr = "0s";
                    var animateTimeOr = "1s";
                    var mode = 'yp_onscreen';

                    if(animateName == 'none'){
                        return true;
                    }

                    if(selector.indexOf("yp_hover") != -1){
                        mode = 'yp_hover';
                    }else if(selector.indexOf("yp_focus") != -1){
                        mode = 'yp_focus';
                    }else if(selector.indexOf("yp_click") != -1){
                        mode = 'yp_click';
                    }else if(selector.indexOf("yp_onscreen") != -1){
                        mode = 'yp_onscreen';
                    }

                    var deviceName =  '';
                    var deviceHTML = '';
                    var modeName = mode.replace("yp_","");

                    // Get Selector
                    if(selector.indexOf("@media") != -1){
                        device = $.trim(selector.replace("@media",""));
                        selector = array[1].split("{")[0];
                    }

                    if(device != 'desktop'){
                        deviceName = 'Responsive';
                    }

                    if(deviceName !== ''){
                        deviceHTML = " <label data-toggle='tooltipAnim' data-placement='right' title='This animation will only play on specific screen sizes.' class='yp-device-responsive'>"+deviceName+"</label><span class='yp-anim-media-details'>"+device+"</span>";
                    }

                    // Clean Selector
                    var selectorClean = selector.replace(".yp_hover","").replace(".yp_focus","").replace(".yp_click","").replace(".yp_onscreen","");

                    // Get Element Name
                    var elementName = 'Undefined';
                    if(iframe.find(selectorClean).length > 0){
                        elementName = uppercase_first_letter(get_tag_information(selectorClean)).replace(/\d+/g, '');
                    }

                    // Element Variables
                    if(iframe.find("."+get_id(selector)+"-animation-duration-style[data-size-mode='"+device+"']").length > 0){
                        animateTimeOr = iframe.find("."+get_id(selector)+"-animation-duration-style[data-size-mode='"+device+"']").html();
                        animateTimeOr = escape_data_value(animateTimeOr);
                    }

                    if(iframe.find("."+get_id(selector)+"-animation-delay-style[data-size-mode='"+device+"']").length > 0){
                        animateDelayOr = iframe.find("."+get_id(selector)+"-animation-delay-style[data-size-mode='"+device+"']").html();
                        animateDelayOr = escape_data_value(animateDelayOr);
                    }

                    var animateTime = $.trim(animateTimeOr.replace('/[^0-9\.]+/g','').replace(/ms/g,"").replace(/s/g,""));
                    var animateDelay = $.trim(animateDelayOr.replace('/[^0-9\.]+/g','').replace(/ms/g,"").replace(/s/g,""));

                    if(animateName.indexOf(",") == -1){

                        animateTime = animateTime * 100;
                        animateDelay = animateDelay * 100;

                        if(animateDelay < 10){
                            animateDelay = 10;
                        }

                    }

                    var extraClass = '';
                    if(animateDelay == 10){
                        extraClass = ' yp-delay-zero';
                    }
                    
                    var animateContent = "<div class='yp-anim-process-bar-delay"+extraClass+"' data-toggle='tooltipAnim' data-placement='top' title='Delay "+parseFloat(animateDelayOr).toFixed(2)+"s' style='width:"+animateDelay+"px;'></div><div class='yp-anim-process-bar' data-toggle='tooltipAnim' data-placement='top' title='Duration: "+parseFloat(animateTimeOr).toFixed(2)+"s' style='width:"+animateTime+"px;'><span class='animate-part-icons yp-control-trash' data-toggle='tooltipAnim' data-placement='top' title='Delete'><span class='dashicons dashicons-trash'></span></span>"+animateName+"</div>";


                    var childAnimateDelayOr,childAnimateDelay,childAnimateTimeOr,childAnimateTime;
                    if(animateName.indexOf(",") != -1){

                        animateContent = '';

                        var prevsBeforeAppend = 0;

                        for(var i = 0; i < animateName.split(",").length; i++){

                            if(animateDelayOr.toString().indexOf(",") != -1){
                                childAnimateDelayOr = $.trim(animateDelayOr.split(",")[i]);
                            }else{
                                childAnimateDelayOr = animateDelayOr;
                            }

                            // default is 1s for child animate delay Or.
                            if(isUndefined(childAnimateDelayOr)){
                                childAnimateDelayOr = "0s";
                            }

                            if(animateDelay.toString().indexOf(",") != -1){
                                childAnimateDelay = $.trim(animateDelay.split(",")[i]);
                            }else{
                                childAnimateDelay = animateDelay;
                            }

                            // default is 1s for child animate delay.
                            if(isUndefined(childAnimateDelay)){
                                childAnimateDelay = 0;
                            }

                            if(animateTimeOr.toString().indexOf(",") != -1){
                                childAnimateTimeOr = $.trim(animateTimeOr.split(",")[i]);
                            }else{
                                childAnimateTimeOr = animateTimeOr;
                            }

                            // default is 1s for child animate time Or.
                            if(isUndefined(childAnimateTimeOr)){
                                childAnimateTimeOr = "1s";
                            }


                            if(animateTime.toString().indexOf(",") != -1){
                                childAnimateTime = $.trim(animateTime.split(",")[i]);
                            }else{
                                childAnimateTime = animateTime;
                            }

                            // default is 1s for child animate.
                            if(isUndefined(childAnimateTime)){
                                childAnimateTime = 1;
                            }

                            var childAnimate = $.trim(animateName.split(",")[i].replace(/\s+?!important/g,'').replace(/\;$/g,''));

                            childAnimateTime = childAnimateTime * 100;
                            childAnimateDelay = childAnimateDelay * 100;

                            var SmartDelayView = (childAnimateDelay-prevsBeforeAppend);
                            var smartDelayOrView = SmartDelayView/100;
                            if(SmartDelayView <= 10){
                                SmartDelayView = 10;
                                smartDelayOrView = "0s";
                            }

                            extraClass = '';
                            if(SmartDelayView == 10){
                                extraClass = ' yp-delay-zero';
                            }

                            animateContent += "<div class='yp-anim-process-bar-delay"+extraClass+"' data-toggle='tooltipAnim' data-placement='top' title='Delay "+parseFloat(smartDelayOrView).toFixed(2)+"s' style='width:"+SmartDelayView+"px;'></div><div class='yp-anim-process-bar' data-toggle='tooltipAnim' data-placement='top' title='Duration: "+parseFloat(childAnimateTimeOr).toFixed(2)+"s' style='width:"+childAnimateTime+"px;'><span class='animate-part-icons yp-control-trash' data-toggle='tooltipAnim' data-placement='top' title='Delete'><span class='dashicons dashicons-trash'></span></span>"+childAnimate+"</div>";

                            prevsBeforeAppend = childAnimateDelay+childAnimateTime;

                        }

                    }

                    // Append.
                    $(".yp-anim-left-part-column").append("<div class='yp-anim-el-column yp-anim-el-column-"+get_id(selectorClean)+"' data-anim-media-size='"+device+"'><span data-toggle='tooltipAnim' data-placement='right' title='"+selectorClean+"'>"+elementName+"</span> <label>"+modeName+"</label>"+deviceHTML+"</div>");

                    $(".yp-anim-right-part-column").append("<div class='yp-animate-bar' id='yp-animate-bar-"+iX+"'><div class='yp-anim-process-bar-area' data-responsive='"+device+"' data-selector='"+selectorClean+"' data-selector-full='"+selector+"'><div class='yp-anim-process-inner'>"+animateContent+"</div><a class='yp-anim-add' data-toggle='tooltipAnim' data-placement='right' title='Add New Animate'></a></div>");

                });
    
                $(".yp-delay-zero").each(function(){

                        var allLeft = $(".yp-anim-process-inner").offset().left-5;
                        var left = $(this).next(".yp-anim-process-bar").offset().left-allLeft;
                        $(this).css("left",left);

                        $(this).next(".yp-anim-process-bar").addClass("yp-anim-has-zero-delay");

                    });

                // Get current selector
                var Cselector = get_current_selector();
                var Lineway = $(".yp-anim-el-column-"+get_id(Cselector)+"[data-anim-media-size='"+get_media_condition()+"']");

                // has selected element and there not have same element in manager list
                if(isDefined(Cselector) && Lineway.length === 0){

                    // Get Element Name
                    var elementName = 'Undefined';
                    if(iframe.find(Cselector).length > 0){
                        elementName = uppercase_first_letter(get_tag_information(Cselector)).replace(/\d+/g, '');
                    }

                    var deviceHTML = '';

                    if(get_media_condition() != 'desktop'){
                    deviceHTML = " <label data-toggle='tooltipAnim' data-placement='right' title='This animation will only play on specific screen sizes.' class='yp-device-responsive'>Responsive</label><span class='yp-anim-media-details'>"+get_media_condition()+"</span>";
                    }

                    // Bar
                    $(".yp-anim-left-part-column").append("<div class='yp-anim-el-column anim-active-row yp-anim-el-column-"+get_id(Cselector)+"' data-anim-media-size='"+get_media_condition()+"'><span data-toggle='tooltipAnim' data-placement='right' title='"+Cselector+"'>"+elementName+"</span> <label>onscreen</label>"+deviceHTML+"</div>");

                    // Adding
                    $(".yp-anim-right-part-column").append("<div class='yp-animate-bar anim-active-row' id='yp-animate-bar-current'><div class='yp-anim-process-bar-area' data-responsive='"+get_media_condition()+"' data-selector='"+Cselector+"' data-selector-full='"+(Cselector+".yp_onscreen")+"'><div class='yp-anim-process-inner'></div><a class='yp-anim-add' data-toggle='tooltipAnim' data-placement='right' title='Add New Animate'></a></div>");

                }else{
                    Lineway.addClass("anim-active-row");
                }

                // resizable
                $( ".yp-anim-process-bar-delay,.yp-anim-process-bar" ).resizable({
                    handles: 'e',
                    minWidth: 10,
                    start: function() {

                        $(".yp-anim-process-bar-delay,.yp-anim-process-bar").not(this).tooltip("disable").tooltip("hide");

                    },
                    resize: function( event, ui ) {

                        var that = $(this);
                        var w = ui.size.width;
                        var s = parseFloat(w/100).toFixed(2);

                        var newTitle;
                        if(that.hasClass("yp-anim-process-bar-delay")){

                            if(w == 10){
                                s = "0";
                            }
                            newTitle = "Delay: "+s;

                            // Delay zero
                            if(w <= 10){
                                that.addClass("yp-delay-zero");
                                that.next(".yp-anim-process-bar").addClass("yp-anim-has-zero-delay");
                            }else if(that.hasClass("yp-delay-zero")){
                                that.removeClass("yp-delay-zero").css("left","0");
                                that.next(".yp-anim-process-bar").removeClass("yp-anim-has-zero-delay");
                            }

                        }else{

                            newTitle = "Duration: "+s;

                        }


                        $(this).parents(".yp-animate-bar").find(".yp-delay-zero").each(function(){

                            var allLeft = $(".yp-anim-process-inner").offset().left-5;
                            var left = $(this).next(".yp-anim-process-bar").offset().left-allLeft;
                            $(this).css("left",left);

                        });
                            

                        that.attr('data-original-title', newTitle+"s").tooltip('show');

                    },
                    stop: function() {

                        update_animation_manager();
                        $(".yp-anim-process-bar-delay,.yp-anim-process-bar").tooltip("enable");

                    }

                });

                
                // Animation manager tooltip
                $('[data-toggle="tooltipAnim"]').tooltip({
                    animation: false,
                    container: ".yp-animate-manager",
                    html: true
                });
                

                $("[data-toggle='tooltipAnim']").on('show.bs.tooltip', function(){
                    $("[data-toggle='tooltipAnim']").not(this).tooltip("hide");
                });

                if($(".yp-animate-bar").length === 0){
                    $(".animation-manager-empty").show();
                }else{
                    $(".animation-manager-empty").hide();
                }

                // Find largest line for play/stop.
                var maxWidth = Math.max.apply( null, $( '.yp-anim-process-inner' ).map( function (){
                    return $( this ).outerWidth( true );
                }).get() );

                // Always add more px to animate bar width on update.
                $(".yp-anim-process-bar-area").width(maxWidth+$(window).width());

            }


            /* ---------------------------------------------------- */
            /* Animation Manager: Update Manager                    */
            /* ---------------------------------------------------- */
            function update_animation_manager(){

                body.addClass("yp-animate-manager-mode");

                // Find largest line for play/stop.
                var maxWidth = Math.max.apply( null, $( '.yp-anim-process-inner' ).map( function (){
                    return $( this ).outerWidth( true );
                }).get() );

                // Always add more px to animate bar width on update.
                $(".yp-anim-process-bar-area").width(maxWidth+$(window).width());

                // Each all lines
                $(".yp-animate-bar").each(function(){

                    // Get selector with mode.
                    var selector = $(this).find(".yp-anim-process-bar-area").attr("data-selector-full");

                    // Animate name array.
                    var sMultiNames = [];

                    // Find all delays in this line.
                    var sMulti = [];
                    var sMultiDuration = [];

                    // delay
                    var delay = 0;
                    var offets = '';

                    // Get size
                    var size = $(this).find(".yp-anim-process-bar-area").attr("data-responsive");
                    if(size == ''){
                        size = 'desktop';
                    }

                    // Each all animate bars
                    $(this).find(".yp-anim-process-bar,.yp-anim-process-bar-delay").each(function(){

                        // Get width.
                        var w = $(this).width();

                        // Width to Second.
                        var s = w/100;

                        // If delay and its not a multiable line.
                        if($(this).hasClass("yp-anim-process-bar-delay") && $(this).parent().find(".yp-anim-process-bar-delay").length == 1){

                            if(w == 10){
                                s = "0";
                            }

                            // Update one delay.
                            // append as "0s" val cos 0 is not acceptable value.
                            insert_rule(selector, "animation-delay", Math.round(s * 100) / 100 + "s", '', size);

                        // If animate bar and not a multiable line.
                        }else if($(this).hasClass("yp-anim-process-bar") && $(this).parent().find(".yp-anim-process-bar").length == 1){

                            // Update one duration.
                            insert_rule(selector, "animation-duration", s, 's', size);
                            insert_rule(selector, "animation-name", $(this).text(), '', size);
                            sMultiNames.push($(this).text());

                        // If multi line and its delay or animate bar.
                        }else if($(this).parent().find(".yp-anim-process-bar-delay").length > 1 || $(this).parent().find(".yp-anim-process-bar").length > 1){

                            // Delay.. Multi..
                            if($(this).hasClass("yp-anim-process-bar-delay")){

                                offets = $(this).offset().left-$(this).parent(".yp-anim-process-inner").offset().left;
                                offets = offets/100;
                                offets = Math.round(offets * 100) / 100;

                                if($(this).width() > 10){

                                    delay = $(this).width()/100;
                                    delay = Math.round(delay * 100) / 100;
                                    sMulti.push(delay+offets+"s");

                                }else{

                                    sMulti.push(offets+"s");

                                }
                                
                            }

                            // Duration.. Multi..
                            if($(this).hasClass("yp-anim-process-bar")){

                                var xy = $(this).width()/100;

                                sMultiDuration.push(xy+"s");
                                sMultiNames.push($(this).text());
                                
                            }

                        }

                    });

                    // Insert multi delays.
                    if(sMulti.length > 1){
                        insert_rule(selector, "animation-delay", sMulti.toString(), '', size);
                        insert_rule(selector, "animation-duration", sMultiDuration.toString(), '', size);
                        insert_rule(selector, "animation-name", sMultiNames.toString(), '', size);

                    }else if(sMultiNames.length === 0 && body.hasClass("yp-anim-removing")){
                        insert_rule(selector, "animation-delay", "disable", '', size);
                        insert_rule(selector, "animation-duration", "disable", '', size);
                        insert_rule(selector, "animation-name", "disable", '', size);
                    }

                    option_change();

                });

                body.removeClass("yp-animate-manager-mode");

            }


            /* ---------------------------------------------------- */
            /* Window Click                                         */
            /* ---------------------------------------------------- */
            $(window).click(function() {
                
                if($(".yp-anim-list-menu").is(":visible")){
                    $(".yp-anim-list-menu").hide();
                }

            });


            /* ---------------------------------------------------- */
            /* Add Animation: Animation list click                  */
            /* ---------------------------------------------------- */
            $(document).on("click", ".yp-anim-list-menu ul li", function(e) {

                // Clean old.
                get_selected_element().removeClass("yp_onscreen yp_hover yp_click yp_focus");

                var p = $(".yp-anim-add.active");

                mainBody.addClass("yp-animate-manager-mode");
                var selector = p.parent().attr("data-selector-full");
                var allAnimNames = [];
                var allDurations = [];
                var allDelays = [];

                // Get size
                var size = p.parents(".yp-anim-process-bar-area").attr("data-responsive");
                if(size == ''){
                    size = 'desktop';
                }

                // If empty, so this new.
                if(p.parent().find(".yp-anim-process-inner").is(':empty')){
                    insert_rule(selector, "animation-name", $(this).data("value"), '',size);
                }else{

                    // push older animations
                    p.parent().find(".yp-anim-process-inner .yp-anim-process-bar").each(function(){
                        allAnimNames.push($(this).text());
                        allDurations.push(($(this).width()/100)+"s");
                    });

                    // push older animations
                    p.parent().find(".yp-anim-process-inner .yp-anim-process-bar-delay").each(function(){
                        var offets = ($(this).offset().left-p.parent().find(".yp-anim-process-inner").offset().left)/100;

                        if($(this).hasClass("yp-delay-zero")){
                            allDelays.push(offets+"s");
                        }else{
                            allDelays.push(offets+($(this).width()/100)+"s");
                        }

                    });

                    // push new animation too
                    allAnimNames.push($(this).data("value"));
                    allDurations.push("1s");

                    var lastAnim = p.parent().find(".yp-anim-process-inner .yp-anim-process-bar").last();
                    var offets = ((lastAnim.offset().left+lastAnim.width())-p.parent().find(".yp-anim-process-inner").offset().left)/100;
                    allDelays.push(offets+"s");

                    insert_rule(selector, "animation-name",allAnimNames.toString(), '',size);
                    insert_rule(selector, "animation-duration",allDurations.toString(), '',size);
                    insert_rule(selector, "animation-delay",allDelays.toString(), '',size);

                }

                mainBody.removeClass("yp-animate-manager-mode");

                setTimeout(function(){
                    animation_manager();
                    update_animation_manager();
                },100);

            });


            /* ---------------------------------------------------- */
            /* Animation Manager: Add Animation icon                */
            /* ---------------------------------------------------- */
            $(document).on("click", ".yp-anim-add", function(e) {

                e.stopPropagation();
                var t = $(this).offset().top;
                var l = $(this).offset().left;

                var menu = $(".yp-anim-list-menu ul");
                $(".yp-anim-list-menu").removeAttr("style").removeClass("yp-anim-list-top");
                menu.empty();
                $("#yp-animation-name-data option").each(function(){
                    var el = $(this);
                    if(el.text() != 'none'){
                        menu.append("<li data-value='"+el.attr("value")+"' data-text='"+el.data("text")+"'>"+el.text()+"</li>");
                    }
                });

                var d = $(window).height()-t-46;

                if(d < 150){
                    $(".yp-anim-list-menu").addClass("yp-anim-list-top");
                }else{
                    if(menu.height() > d){
                        menu.height(d);
                    }
                }

                $(".yp-anim-list-menu").css("left",l).css("top",t).show();

                $(".yp-anim-add").removeClass("active");
                $(this).addClass("active");

            });


            /* ---------------------------------------------------- */
            /* Lite version modal close                */
            /* ---------------------------------------------------- */
            $(".yp-info-modal-close").click(function() {
                $(this).parent().parent().hide();
                $(".yp-popup-background").hide();
            });

            /* ---------------------------------------------------- */
            /* Background Upload popup close                        */
            /* ---------------------------------------------------- */
            $(".yp-popup-background").click(function() {
                $(this).hide();
                $(".yp-info-modal").hide();
            });


            /* ---------------------------------------------------- */
            /* Element Inspector button: Click                      */
            /* ---------------------------------------------------- */
            $(".yp-selector-mode").click(function() {

                if($(".yp-ruler-btn").hasClass("active")){
                    $(".yp-ruler-btn").trigger("click");
                    $(".yp-selector-mode").trigger("click");
                }

                if ($(this).hasClass("active") && $(".yp-sharp-selector-btn.active").length > 0) {
                    $(".yp-sharp-selector-btn").removeClass("active");
                    body.removeClass("yp-sharp-selector-mode-active");
                    iframeBody.removeClass("yp-sharp-selector-mode-active");
                    window.singleInspector = false;
                }
                
                body.toggleClass("yp-body-selector-mode-active");
                clean();

            });


            // cache
            window.scroll_width = get_scroll_bar_width();


            /* ---------------------------------------------------- */
            /* Draw Responsive Handles                              */
            /* ---------------------------------------------------- */
            function draw_responsive_handle() {

                if (is_responsive_mod() === false) {
                    return false;
                }

                // variables
                var iframeElement = $("#iframe");

                if(isUndefined(window.FrameleftOffset)){
                    var offset = iframeElement.offset();
                    window.FrameleftOffset = offset.left;
                    window.FrametopOffset = offset.top;
                }

                var w = iframeElement.width();
                var h = iframeElement.height();

                var left = window.FrameleftOffset + w;
                var top = window.FrametopOffset + h;

                $(".responsive-right-handle").css("left", left)
                .css("top", window.FrametopOffset - 2)
                .css("height", h + 2);

                $(".responsive-bottom-handle").css("left", window.FrameleftOffset)
                .css("top", top)
                .css("width", w);

            }

            // Right
            window.responsiveModeRMDown = false;
            window.SelectorDisableResizeRight = false;
            window.rulerWasActive = false;
            window.selectorWasActive = false;


            /* ---------------------------------------------------- */
            /* Responsive Right Handle                              */
            /* ---------------------------------------------------- */
            $(".responsive-right-handle").on("mousedown", function(e) {

                $('.responsive-right-handle').tooltip("hide");

                window.responsiveModeRMDown = true;
                body.addClass("yp-clean-look yp-responsive-resizing yp-responsive-resizing-right yp-hide-borders-now");

                if($(".yp-selector-mode").hasClass("active")){
                    window.selectorWasActive = true;
                }else{
                    window.selectorWasActive = false;
                }

                if ($(".yp-ruler-btn").hasClass("active")) {
                    window.rulerWasActive = true;
                } else {
                    window.rulerWasActive = false;
                    $(".yp-ruler-btn").trigger("click").removeClass("active");
                }

                if ($(".yp-selector-mode").hasClass("active") && is_content_selected() === false) {
                    $(".yp-selector-mode").trigger("click");

                    window.SelectorDisableResizeRight = true;
                }

            });

            
            /* ---------------------------------------------------- */
            /* Responsive Right Handle                              */
            /* ---------------------------------------------------- */
            mainDocument.on("mousemove", function(e) {

                if (window.responsiveModeRMDown === true) {

                    var hasClass = mainBody.hasClass("yp-css-editor-active");
                    var ww = $(window).width();

                    if (hasClass === true) {
                        e.pageX = e.pageX - 450 - 10;
                    } else {
                        e.pageX = e.pageX - window.leftbarWidth - 10;
                    }

                    // Min 320
                    if (e.pageX < 320) {
                        e.pageX = 320;
                    }

                    // Max full-80 W
                    if (hasClass) {
                        if (e.pageX > ww - 80 - 450) {
                            e.pageX = ww - 80 - 450;
                        }
                    } else {
                        if (e.pageX > ww - 80 - 49) {
                            e.pageX = ww - 80 - 49;
                        }
                    }

                    $("#iframe").width(e.pageX);

                    draw_responsive_handle();
                    update_responsive_size_notice();

                }
            });


            /* ---------------------------------------------------- */
            /* Responsive Right Handle                              */
            /* ---------------------------------------------------- */
            mainDocument.on("mouseup", function() {

                if (window.responsiveModeRMDown === true) {

                    if(body.hasClass("yp-animate-manager-active")){
                        animation_manager();
                    }

                    window.responsiveModeRMDown = false;

                    if (window.SelectorDisableResizeBottom === false) {
                        draw();
                    }

                    body.removeClass("yp-clean-look yp-responsive-resizing yp-responsive-resizing-right");

                    setTimeout(function() {
                        body.removeClass("yp-hide-borders-now");
                        draw_tooltip();
                    }, 25);

                    if (window.SelectorDisableResizeRight === true) {
                        window.SelectorDisableResizeRight = false;
                    }

                    if (window.rulerWasActive === false) {
                        $(".yp-ruler-btn").addClass("active").trigger("click");
                    }


                    if(window.selectorWasActive === true){
                        if($(".yp-selector-mode").hasClass("active") === false){
                            $(".yp-selector-mode").trigger("click");
                        }
                    }else{
                        if($(".yp-selector-mode").hasClass("active")){
                            $(".yp-selector-mode").trigger("click");
                        }
                    }

                    // Update options
                    insert_default_options();

                    setTimeout(function() {
                        $(".reset-enable").removeClass("reset-enable");
                    }, 10);

                }

            });

            // Bottom
            window.responsiveModeBMDown = false;
            window.SelectorDisableResizeBottom = false;


            /* ---------------------------------------------------- */
            /* Responsive Bottom Handle                             */
            /* ---------------------------------------------------- */
            $(".responsive-bottom-handle").on("mousedown", function() {
                window.responsiveModeBMDown = true;
                body.addClass("yp-clean-look yp-responsive-resizing yp-responsive-resizing-bottom yp-hide-borders-now");

                if($(".yp-selector-mode").hasClass("active")){
                    window.selectorWasActive = true;
                }else{
                    window.selectorWasActive = false;
                }

                if ($(".yp-ruler-btn").hasClass("active")) {
                    window.rulerWasActive = true;
                } else {
                    window.rulerWasActive = false;
                    $(".yp-ruler-btn").trigger("click");
                }

                if ($(".yp-selector-mode").hasClass("active") && is_content_selected() === false) {
                    $(".yp-selector-mode").trigger("click");
                    window.SelectorDisableResizeBottom = true;
                }

            });


            /* ---------------------------------------------------- */
            /* Responsive Bottom Handle                             */
            /* ---------------------------------------------------- */
            mainDocument.on("mousemove", function(e) {
                if (window.responsiveModeBMDown === true) {

                    var ext = 0;
                    if(mainBody.hasClass("yp-html-mod-active")){
                        ext = 42;
                    }

                    if ($(this).find("#iframe").length > 0) {
                        e.pageY = e.pageY - 48 - ext;
                    }

                    // Min 320
                    if (e.pageY < 320) {
                        e.pageY = 320;
                    }

                    // Max full-80 H
                    if (e.pageY > $(window).height() - 80 - 40 - ext) {
                        e.pageY = $(window).height() - 80 - 40 - ext;
                    }

                    $("#iframe").height(e.pageY);
                    draw_responsive_handle();

                    update_responsive_size_notice();

                }
            });


            /* ---------------------------------------------------- */
            /* Responsive Bottom Handle                             */
            /* ---------------------------------------------------- */
            mainDocument.on("mouseup", function() {

                if (window.responsiveModeBMDown === true) {
                    window.responsiveModeBMDown = false;

                    if (window.SelectorDisableResizeBottom === false) {
                        draw();
                    }

                    body.removeClass("yp-clean-look yp-responsive-resizing yp-responsive-resizing-bottom");

                    setTimeout(function() {
                        body.removeClass("yp-hide-borders-now");
                    }, 25);

                    if (window.SelectorDisableResizeBottom === true) {
                        $(".yp-selector-mode").trigger("click");
                        window.SelectorDisableResizeBottom = false;
                    }

                    if (window.rulerWasActive === false) {
                        $(".yp-ruler-btn").trigger("click");
                    }

                    if(window.selectorWasActive === true){
                        if($(".yp-selector-mode").hasClass("active") === false){
                            $(".yp-selector-mode").trigger("click");
                        }
                    }else{
                        if($(".yp-selector-mode").hasClass("active")){
                            $(".yp-selector-mode").trigger("click");
                        }
                    }

                    // Update options
                    insert_default_options();

                    setTimeout(function() {
                        $(".reset-enable").removeClass("reset-enable");
                    }, 10);

                }

            });

        
            // Last Key
            var lastKeyUpAt = 0;

            /* ---------------------------------------------------- */
            /* Shortcuts & Keys : KeyUp                             */
            /* ---------------------------------------------------- */
            mainDocument.on("keyup", function(e) {

                lastKeyUpAt = new Date();

                // Getting current tag name.
                var tag = e.target.tagName.toLowerCase();

                // Control
                var ctrlKey = false;
                var tagType = false;

                // Check If is CTRL Key.
                if ((e.ctrlKey === true || e.metaKey === true)) {
                    ctrlKey = true;
                }

                // Stop if this target is input or textarea.
                if (tag == 'input' || tag == 'textarea') {
                    tagType = true;
                }

                // Multi selecting support
                if(ctrlKey === false && tagType === false){
                    body.removeClass("yp-control-key-down");
                    iframe.find(".yp-multiple-selected").removeClass("yp-multiple-selected");
                    iframe.find(".yp-selected-others-multiable-box").remove();
                }

            });


            /* ---------------------------------------------------- */
            /* Shortcuts & Keys : KeyDown                           */
            /* ---------------------------------------------------- */
            mainDocument.on("keydown", function(e) {

                // get current time
                var keyDownAt = new Date();

                // Getting current tag name.
                var tag = e.target.tagName.toLowerCase();

                // Getting Keycode.
                var code = e.keyCode || e.which;

                // Control
                var ctrlKey = false;
                var shifted = e.shiftKey ? true : false;
                var tagType = false;
                var selector;

                // Check If is CTRL Key.
                if ((e.ctrlKey === true || e.metaKey === true)){
                    ctrlKey = true;
                }

                // Stop if this target is input or textarea.
                if (tag == 'input' || tag == 'textarea') {
                    tagType = true;
                }

                // Hide. delete
                if (code == 46 && ctrlKey === false && tagType === false) {
                    insert_rule(null, "display", "none", '');
                    option_change();
                    clean();
                    gui_update();
                }

                // Show parent tree
                if (code == 84 && ctrlKey === false && tagType === false) {
                    if($(".yp-parent-tree").length == 0){
                        if(is_content_selected()){
                            show_parent_tree();
                            return false;
                        }
                    }
                }

                // hide parent tree
                if (code == 27 && ctrlKey === false && tagType === false) {
                    if($(".yp-parent-tree").length == 1){
                        close_parent_tree();
                        return false;
                    }
                }

                // go parent element
                if (code == 80 && ctrlKey === false && tagType === false) {
                    if(is_content_selected()){
                        if(get_selected_element().parent().length > 0){

                            if (get_selected_element().parent()[0].nodeName.toLowerCase() != "html") {

                                // add class to parent.
                                get_selected_element().parent().addClass("yp-will-selected");

                                // clean
                                clean();

                                // Get parent selector.
                                var parentSelector = $.trim(get_parents(iframe.find(".yp-will-selected"), "default"));

                                // Set Selector
                                set_selector(parentSelector,null);

                                return false;

                            }

                        }
                    }
                }

                // ESC for custom selector.
                if (code == 27 && ctrlKey === false) {

                    // Was resizing?
                    if(is_resizing() || is_visual_editing()){
                        return false;
                    }

                    if($(".sweet-alert").css("display") == 'none' || $(".sweet-alert").length === 0){

                        if($(".yp-popup-background").css("display") != 'none'){
                            $(".yp-info-modal-close").trigger("click");
                            return false;
                        }

                        if ($(".yp-button-target.active").length <= 0) {
                            $("#yp-button-target-input").val("");
                            $(".yp-button-target").trigger("click");
                            return false;
                        }

                    }

                }

                if(ctrlKey === false && tagType === false && shifted === true){

                    setTimeout(function() {

                        // Compare key down time with key up time
                        if (+keyDownAt > +lastKeyUpAt && is_content_selected()){
                            
                            body.addClass("yp-control-key-down");

                            var recentElement = iframe.find(".yp-recent-hover-element");

                            if(recentElement.length > 0){
                                recentElement.trigger("mouseover");
                            }

                        }

                    }, 220);

                }


                // UP DOWN keys for move selected element
                if (ctrlKey === false && tagType === false){
                    if(code == 38 || code == 40 || code == 37 || code == 39){
                        if(is_content_selected() && is_dragging() === false){
                            e.preventDefault();

                            var el = get_selected_element();
                            var t = parseInt(el.css("top"));
                            var l = parseInt(el.css("left"));
                            var r = parseInt(el.css("right"));
                            var b = parseInt(el.css("bottom"));
                            var f = 1;

                            if(shifted){
                                f = 10;
                            }

                            if(code == 38){
                                t = t-f;
                            }else if(code == 40){
                                t = t+f;
                            }

                            if(code == 37){
                                l = l-f;
                            }else if(code == 39){
                                l = l+f;
                            }

                            t = t+"px";
                            l = l+"px";

                            // Insert new data. TOP BOTTOM
                            if(code == 38 || code == 40){

                                insert_rule(null, "top", t, '');

                                if (parseFloat(t) + parseFloat(b) !== 0) {
                                    insert_rule(null, "bottom", "auto", '');
                                }
                            
                            }

                            // Insert new data. LEFT RIGHT
                            if(code == 37 || code == 39){

                                insert_rule(null, "left", l, '');

                                if (parseFloat(l) + parseFloat(r) !== 0) {
                                    insert_rule(null, "right", "auto", '');
                                }

                            }

                            var position = el.css("position");

                            if(position == 'static' || position == 'relative'){
                                insert_rule(null, "position", "relative", '');
                            }                    

                            if ($("#position-static").parent().hasClass("active") || $("#position-relative").parent().hasClass("active")){
                                $("#position-relative").trigger("click");
                            }

                            // Set default values for top and left options.
                            if ($("li.position-option.active").length > 0) {
                                $("#top-group,#left-group").each(function() {
                                set_default_value(get_option_id(this));
                            });
                            } else {
                                $("li.position-option").removeAttr("data-loaded"); // delete cached data.
                            }

                            option_change();

                        }
                    }
                }

                // Enter
                if (code == 13 && ctrlKey === false) {
                    if ($(e.target).is("#yp-set-animation-name")) {
                        $(".yp-animation-creator-start").trigger("click");
                    }
                }

                // Disable backspace key.
                if (code == 8 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    return false;
                }

                // Z Key
                if (code == 90 && ctrlKey == true && tagType === false) {

                    e.preventDefault();

                    clearTimeout(window.historyDelay);

                    if(mainBody.hasClass("yp-history-delay")){
                        
                        window.historyDelay = setTimeout(function(){
                            undo_changes();
                        },220);

                    }else{
                        setTimeout(function(){
                            undo_changes();
                        },50);
                    }

                    return false;

                }


                // G Key | Toggle smart guide
                if (code == 71 && ctrlKey === true && tagType === false) {
                    e.preventDefault();

                    body.toggleClass("yp-smart-guide-disabled");
                    return false;
                }


                // Y Key
                if (code == 89 && ctrlKey === true && tagType === false) {

                    e.preventDefault();

                    clearTimeout(window.historyDelay);

                    if(mainBody.hasClass("yp-history-delay")){
                            
                        window.historyDelay = setTimeout(function(){
                            redo_changes();
                        },220);

                    }else{
                        setTimeout(function(){
                            redo_changes();
                        },50);
                    }

                    return false;

                }

                // ESC
                if (code == 27 && ctrlKey === false && tagType === false) {

                    e.preventDefault();

                    // ESC hide image uplaoder
                    if($("#image_uploader").css("display") == 'block'){
                        $("#image_uploader").toggle();
                        $("#image_uploader_background").toggle();
                        return false;
                    }

                    if (mainBody.hasClass("autocomplete-active") === false && $(".iris-picker:visible").length === 0 && ($(".sweet-alert").css("display") == 'none') || $(".sweet-alert").length === 0) {

                        if (!mainBody.hasClass("css-editor-close-by-editor")) {

                            if ($("#cssEditorBar").css("display") == 'block') {
                                if (body.hasClass("yp-fullscreen-editor")) {
                                    body.removeClass("yp-fullscreen-editor");
                                }
                                $(".css-editor-btn").trigger("click");
                                return false;
                            } else if ($("#context-menu-layer:visible").length > 0) {
                                $("#context-menu-layer,.context-menu-list").hide();
                                return false;
                            } else if (is_content_selected()) {
                                clean();
                                gui_update();
                                return false;
                            }

                        } else {
                            mainBody.removeClass("css-editor-close-by-editor");
                            return false;
                        }

                    } else {
                        body.removeClass("yp-select-open");
                    }

                }

                // Space key go to selected element
                if (code == 32 && shifted === false && ctrlKey === false && tagType === false && is_content_selected()) {

                    e.preventDefault();

                    var element = get_selected_element();

                    if (iframe.find(".yp-selected-tooltip").hasClass("yp-fixed-tooltip") || iframe.find(".yp-selected-tooltip").hasClass("yp-fixed-tooltip-bottom")) {
                        var height = parseInt($(window).height() / 2);
                        var selectedHeight = parseInt(element.height() / 2);
                        var scrollPosition = selectedHeight + element.offset().top - height;
                        iframe.scrollTop(scrollPosition);
                    }

                    return false;

                }

                // Space key select hovered element
                if (code == 32 && shifted === false && tagType === false && is_content_selected() === false && $(".yp-selector-mode").hasClass("active")) {

                    e.preventDefault();

                    if(iframe.find(".yp-selected").length > 0){


                        if(mainBody.hasClass("yp-sharp-selector-mode-active")){
                            selector = $.trim(get_parents(null, "sharp"));
                        }else{
                            selector = $.trim(get_parents(null, "default"));
                        }

                        set_selector(selector,get_selected_element());

                    }

                    return false;

                }

                // Space key select multiple hovered element
                if (code == 32 && shifted === true && tagType === false && is_content_selected() === true && $(".yp-selector-mode").hasClass("active")) {

                    e.preventDefault();

                    var selectorCurrent = get_current_selector();
                    var selectorNew = get_parents(iframe.find(".yp-multiple-selected"), "sharp");
                    iframe.find(".yp-selected-others-multiable-box").remove();
                    iframe.find(".yp-multiple-selected").addClass("yp-selected-others").removeClass("yp-multiple-selected");
                    set_selector(selectorCurrent+","+selectorNew,get_selected_element());

                    return false;

                }

                // R Key
                if (code == 82 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".yp-responsive-btn").trigger("click");
                    return false;
                }

                // M Key
                if (code == 77 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".yp-ruler-btn").trigger("click");
                    return false;
                }

                // W Key
                if (code == 87 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".yp-wireframe-btn").trigger("click");
                    return false;
                }

                // D Key
                if (code == 68 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".info-btn").trigger("click");
                    return false;
                }

                // H Key
                if (code == 72 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    css_editor_toggle();
                    return false;
                }

                // L Key
                if (code == 76 && ctrlKey === false && tagType === false && is_dragging() === false) {
                    e.preventDefault();
                    body.toggleClass("yp-hide-borders-now");
                    return false;
                }

                // " Key
                if (code == 162 && ctrlKey === false && tagType === false && mainBody.hasClass("process-by-code-editor") === false) {
                    e.preventDefault();

                    if (is_animate_creator()) {
                        swal({title: "Sorry.",text: l18_cantEditor,type: "warning",animation: false});
                        return false;
                    }

                    $(".css-editor-btn").trigger("click");
                    return false;
                }

                // " For Chrome Key
                if (code == 192 && ctrlKey === false && tagType === false && mainBody.hasClass("process-by-code-editor") === false) {
                    e.preventDefault();

                    if (is_animate_creator()) {
                        swal({title: "Sorry.",text: l18_cantEditor,type: "warning",animation: false});
                        return false;
                    }

                    $(".css-editor-btn").trigger("click");
                    return false;
                }

                // F Key
                if (code == 70 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".yp-button-target").trigger("click");
                    return false;
                }

            });


            /* ---------------------------------------------------- */
            /* Up/Down keys for prefixes                            */
            /* ---------------------------------------------------- */
            $(".yp-after-prefix").keydown(function(e){

                if($(this).val() == 'xp'){
                    $(this).val("px");
                }

                var code = e.keyCode || e.which;

                if (code == 40 || code == 38) {

                    e.preventDefault();
                    // em -> % -> px
                    if ($(this).val() == 'em') {
                        $(this).val("%");
                    } else if ($(this).val() == '%') {
                        $(this).val("px");
                    } else if ($(this).val() == 'px') {
                        $(this).val("em");
                    }

                }

            });


            /* ---------------------------------------------------- */
            /* ESC Close Ace Editor                                 */
            /* ---------------------------------------------------- */
            editor.commands.addCommand({

                name: 'close',
                bindKey: {
                    win: 'ESC',
                    mac: 'ESC'
                },
                exec: function() {

                    if (body.hasClass("yp-fullscreen-editor")) {
                        body.removeClass("yp-fullscreen-editor");
                    }

                    $(".css-editor-btn").trigger("click");
                    mainBody.removeClass("process-by-code-editor").addClass("css-editor-close-by-editor");

                },

                readOnly: false

            });

            
            /* ---------------------------------------------------- */
            /* Disable Form submission in iframe                    */
            /* ---------------------------------------------------- */
            iframe.find("form").submit(function(e) {
                e.preventDefault();
                return false;
            });


            /* ---------------------------------------------------- */
            /* Has Redo? Has Undo?                                  */
            /* ---------------------------------------------------- */
            function check_undoable_history(){

                // Has Undo?
                if(editor.session.getUndoManager().hasUndo() === false){
                    $(".undo-btn").addClass("disabled");
                }else{
                    $(".undo-btn").removeClass("disabled");
                }

                // Has Redo?
                if(editor.session.getUndoManager().hasRedo() === false){
                    $(".redo-btn").addClass("disabled");
                }else{
                    $(".redo-btn").removeClass("disabled");
                }

            }


            /* ---------------------------------------------------- */
            /* KeyUp Slider properties input                        */
            /* ---------------------------------------------------- */
            $(".yp-after-css").keyup(function(e) {

                if($(".lock-btn.active").length == 0 && e.originalEvent){
                    var n = $(this).parent().parent().find(".wqNoUi-target");
                    slide_action(n, n.attr("id").replace("yp-", ""), false, true);
                }

            });


            /* ---------------------------------------------------- */
            /* Measuring Tool                                       */
            /* ---------------------------------------------------- */
            $(".yp-ruler-btn").click(function() {

                if(is_content_selected() === false){
                    clean();
                }

                body.toggleClass("yp-metric-disable");
                gui_update();

                // Disable selector mode.
                if ($(this).hasClass("active") === false) {
                    if ($(".yp-selector-mode.active").length > 0) {
                        window.SelectorModeWasActive = true;
                        $(".yp-selector-mode").removeClass("active");
                    }
                } else {
                    $(".yp-selector-mode").addClass("active");
                }

                return false;
            });

            
            /* ---------------------------------------------------- */
            /* Single Inspector Tool                                */
            /* ---------------------------------------------------- */
            $(".yp-sharp-selector-btn").click(function() {

                body.toggleClass("yp-sharp-selector-mode-active");

                // Update variable
                if(body.hasClass('yp-sharp-selector-mode-active')){
                    window.singleInspector = true;
                }else{
                    window.singleInspector = false;
                }

                if ($(".yp-selector-mode.active").length === 0) {
                    $(".yp-selector-mode").trigger("click");
                }
            });

            
            /* ---------------------------------------------------- */
            /* Up / Down keys for property input value              */
            /* ---------------------------------------------------- */
            $(".yp-after-css-val").keydown(function(e) {

                if($(this).val() == 'xp'){
                    $(this).val("px");
                }

                var code = e.keyCode || e.which;

                if (code == 38) {
                    e.preventDefault();
                    $(this).val(parseFloat($(this).val()) + parseFloat(1));
                }

                if (code == 40) {
                    e.preventDefault();
                    $(this).val(parseFloat($(this).val()) - parseFloat(1));
                }

                if(code == 13){
                    $(this).trigger("blur");
                    return false;
                }

            });


            var wasLast = false;

            /* ---------------------------------------------------- */
            /* Right Key: Go the prefix input from value input      */
            /* ---------------------------------------------------- */
            $(".yp-after-css-val").keyup(function(e) {

                if($(this).val() == 'xp'){
                    $(this).val("px");
                }

                var code = e.keyCode || e.which;
                var range = $(this).getCursorPosition();
                var next = $(this).parent().find(".yp-after-prefix");
                
                if(range == $(this).val().length && wasLast === false){
                    wasLast = true;
                    return true;
                }

                if(range != $(this).val().length){
                    wasLast = false;
                }

                if(code == 39 && wasLast === true){
                    next.trigger("focus");
                    wasLast = false;
                }

            });

            
            var wasLastPrefix = false;

            /* ---------------------------------------------------- */
            /* Left Key: Go the value input from prefix input       */
            /* ---------------------------------------------------- */
            $(".yp-after-prefix").keyup(function(e) {

                if($(this).val() == 'xp'){
                    $(this).val("px");
                }

                var code = e.keyCode || e.which;
                var range = $(this).getCursorPosition();
                var prev = $(this).parent().find(".yp-after-css-val");
                
                if(range === 0 && wasLastPrefix === false){
                    wasLastPrefix = true;
                    return true;
                }

                if(range !== 0){
                    wasLastPrefix = false;
                }

                if(code == 37 && wasLastPrefix === true){
                    prev.trigger("focus");
                    wasLastPrefix = false;
                }

            });


            /* ---------------------------------------------------- */
            /* Number filter for numberic properties input          */
            /* ---------------------------------------------------- */
            $(".yp-after-css-val").keyup(function(e) {

                // Number only
                var numbers = $(this).val().replace(/[^0-9.,-]/g,'');

                if(numbers.length === 0){
                    numbers = 0;
                }

                // non-number only
                var prefixs = $(this).val().replace(/[0-9.,-]/g,'');

                var prefixSelector = $(this).parent().find(".yp-after-prefix");

                if(prefixs.length > 0){

                    $(this).val(numbers);

                    prefixSelector.val(prefixs);

                    // Focus
                    prefixSelector.val(prefixSelector.val()).trigger("focus");

                }

            });

            
            /* ---------------------------------------------------- */
            /* Getting option ID                                    */
            /* ---------------------------------------------------- */
            function get_option_id(element) {
                return $(element).attr("id").replace("-group", "");
            }


            /* ---------------------------------------------------- */
            /* hasAttr Fn                                           */
            /* ---------------------------------------------------- */
            $.fn.hasAttr = function(name) {  
               return this.attr(name) !== undefined;
            };

            /* ---------------------------------------------------- */
            /* getCursorPosition Fn                                 */
            /* ---------------------------------------------------- */
            $.fn.getCursorPosition = function() {
                var input = this.get(0);
                if (!input) return; // No (input) element found
                if ('selectionStart' in input) {
                    // Standard-compliant browsers
                    return input.selectionStart;
                } else if (document.selection) {
                    // IE
                    input.focus();
                    var sel = document.selection.createRange();
                    var selLen = document.selection.createRange().text.length;
                    sel.moveStart('character', -input.value.length);
                    return sel.text.length - selLen;
                }
            };


            /* ---------------------------------------------------- */
            /* Redo Changes                                         */
            /* ---------------------------------------------------- */
            function redo_changes(){

                if(is_resizing() || is_visual_editing() || is_dragging() || mainBody.hasClass("yp-processing-now")){
                    return false;
                }

                if (is_animate_creator()) {
                    swal({title: "Sorry.",text: l18_cantUndo,type: "warning",animation: false});
                    return false;
                }

                if (mainBody.hasClass("yp-animate-manager-active")) {
                    swal({title: "Sorry.",text: l18_cantUndoAnimManager,type: "warning",animation: false});
                    return false;
                }

                editor.commands.exec("redo", editor);

                body.addClass("yp-css-data-trigger");
                $("#cssData").trigger("keyup");

                draw();

                check_undoable_history();

                if(is_responsive_mod()){
                    update_responsive_breakpoints();
                }

            }


            /* ---------------------------------------------------- */
            /* Undo Changes                                         */
            /* ---------------------------------------------------- */
            function undo_changes(){

                if(is_resizing() || is_visual_editing() || is_dragging() || mainBody.hasClass("yp-processing-now")){
                        return false;
                    }

                    if (is_animate_creator()) {
                        swal({title: "Sorry.",text: l18_cantUndo,type: "warning",animation: false});
                        return false;
                    }

                    if (mainBody.hasClass("yp-animate-manager-active")) {
                        swal({title: "Sorry.",text: l18_cantUndoAnimManager,type: "warning",animation: false});
                        return false;
                    }

                    editor.commands.exec("undo", editor);

                    body.addClass("yp-css-data-trigger");
                    $("#cssData").trigger("keyup");
                    draw();

                    // Update draggable after undo
                    var elx = iframeBody.find(".yp-selected");
                    if(elx.length > 0){

                        if(elx.css("position") == 'static'){
                            elx.css("position","relative");
                            iframeBody.find(".yp-selected-others").css("position","relative");
                        }

                    }

                check_undoable_history();

                if(is_responsive_mod()){
                    update_responsive_breakpoints();
                }

            }


            /* ---------------------------------------------------- */
            /* IsDefined                                            */
            /* ---------------------------------------------------- */
            function isDefined(a){
                if(typeof a !== typeof undefined && a !== false && a != '' && a != ' ' && a != 'undefined' && a !== null){
                    return true;
                }else{
                    return false;
                }
            }


            /* ---------------------------------------------------- */
            /* IsUndefined                                          */
            /* ---------------------------------------------------- */
            function isUndefined(a){
                if(typeof a === typeof undefined || a === false || a === '' || a == ' ' || a == 'undefined' || a === null){
                    return true;
                }else{
                    return false;
                }
            }


            /* ---------------------------------------------------- */
            /* CSSImportant Fn                                      */
            /* ---------------------------------------------------- */
            $.fn.cssImportant = function(rule, value) {

                // Set default CSS.
                this.css(rule, value);

                // add important
                $(this).attr("style", this.attr("style").replace(rule + ": " + value, rule + ": " + value + " !important"));

            };


            /* ---------------------------------------------------- */
            /* Live Preview Button                                  */
            /* ---------------------------------------------------- */
            $(".yp-button-live").click(function() {

                var el = $(this);
                var href = el.attr("data-href");
                el.addClass("live-btn-loading");

                if (mainBody.hasClass("yp-yellow-pencil-demo-mode")) {
                    swal({title: "Sorry.",text: l18_live_preview,type: "info",animation: false});
                    el.removeClass("live-btn-loading");
                    return false;
                }

                var posting = $.post( ajaxurl, {
                    action: "yp_preview_data_save",
                    yp_data: get_clean_css(true)
                } );

                // Done.
                posting.complete(function(data) {
                    el.removeClass("live-btn-loading");
                    window.open(href, href);
                    return false;
                });

            });


            /* ---------------------------------------------------- */
            /* Visitor view avatar                                  */
            /* ---------------------------------------------------- */
            $(".yp-logout-btn").click(function(e){

                if (mainBody.hasClass("yp-yellow-pencil-demo-mode")) {
                    e.preventDefault();
                    swal({title: "Sorry.",text: l18_visitor_view,type: "info",animation: false});
                }

                $(".yp-logout-btn").tooltip("hide");

            });


            /* ---------------------------------------------------- */
            /* Setting the Selector                                 */
            /* ---------------------------------------------------- */
            function set_selector(selector,selected) {

                clean();

                window.setSelector = selector;

                var element = iframe.find(get_foundable_query(selector,true,false,false));

                body.attr("data-clickable-select", selector);

                if (iframe.find(".yp-will-selected").length > 0) {
                    iframe.find(".yp-will-selected").trigger("mouseover").trigger("click");
                    iframe.find(".yp-will-selected").removeClass("yp-will-selected");
                } else if(selected !== null){
                    selected.trigger("mouseover").trigger("click");
                }else{
                    element.filter(":visible").first().trigger("mouseover").trigger("click");
                }

                if (element.length > 1) {
                    element.addClass("yp-selected-others");
                    get_selected_element().removeClass("yp-selected-others");
                }

                body.addClass("yp-content-selected");

                window.orginalHeight = parseFloat(element.css("height").replace(/px/g,''));
                window.orginalWidth = parseFloat(element.css("width").replace(/px/g,''));

                if(element.css("float") == 'right'){
                    body.addClass("yp-element-float");
                }else{
                    body.removeClass("yp-element-float");
                }

                if($(".advanced-info-box").css("display") == 'block' && $(".element-btn").hasClass("active")){
                    update_design_information("element");
                }

                var tooltip = iframe.find(".yp-selected-tooltip");
                tooltip.html("<small class='yp-tooltip-small'>" + iframe.find(".yp-selected-tooltip small").html() + "</small> " + selector);

                // Use native hover system
                if (selector.match(/:hover/g)) {

                    body.addClass("yp-selector-hover");
                    body.attr("data-yp-selector", ":hover");
                    $(".yp-contextmenu-hover").addClass("yp-active-contextmenu");
                    iframe.find(".yp-selected-tooltip span").remove();
                    selector = selector.replace(/:hover/g, "");

                }

                // Use native focus system
                if (selector.match(/:focus/g)) {

                    body.addClass("yp-selector-focus");
                    body.attr("data-yp-selector", ":focus");
                    $(".yp-contextmenu-focus").addClass("yp-active-contextmenu");
                    iframe.find(".yp-selected-tooltip span").remove();
                    selector = selector.replace(/:focus/g, "");

                }

                // Use native visited system
                if (selector.match(/:visited/g)) {

                    body.addClass("yp-selector-visited");
                    body.attr("data-yp-selector", ":visited");
                    iframe.find(".yp-selected-tooltip span").remove();
                    selector = selector.replace(/:visited/g, "");

                }

                // Use native visited system
                if (selector.match(/:link/g)) {

                    body.addClass("yp-selector-link");
                    body.attr("data-yp-selector", ":link");
                    iframe.find(".yp-selected-tooltip span").remove();
                    selector = selector.replace(/:link/g, "");

                }

                // Use native visited system
                if (selector.match(/:active/g)) {

                    body.addClass("yp-selector-active");
                    body.attr("data-yp-selector", ":active");
                    iframe.find(".yp-selected-tooltip span").remove();
                    selector = selector.replace(/:active/g, "");

                }

                css_editor_toggle(true); // show if hide

                body.attr("data-clickable-select", selector);

                insert_default_options();

                gui_update();

                draw();

                if(body.hasClass("yp-animate-manager-active")){
                    animation_manager();
                }

                // Update the element informations.
                if($(".advanced-info-box").css("display") == 'block' && $(".element-btn").hasClass("active")){
                    update_design_information("element");
                }

                window.setSelector = false;

            }

            
            /* ---------------------------------------------------- */
            /* Set CSS to the Ace Editor                            */
            /* ---------------------------------------------------- */
            editor.setValue(get_clean_css(true));

            
            /* ---------------------------------------------------- */
            /* Clean Undo Manager                                   */
            /* ---------------------------------------------------- */
            editor.getSession().setUndoManager(new ace.UndoManager());

            
            /* ---------------------------------------------------- */
            /* Tooltips                                             */
            /* ---------------------------------------------------- */
            $('[data-toggle="tooltipTopBottom"]').tooltip({
                animation: false,
                container: ".yp-select-bar",
                 template: '<div class="tooltip hidden-on-fullscreen"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
                html: true
            });

            $('[data-toggle="tooltip-bar"]').tooltip({
                animation: false,
                container: "body",
                html: true
            });

            $('.info-btn').on('show.bs.tooltip', function () {
                if($(this).hasClass("active")){
                    return false;
                }
            });

            $(".yp-none-btn").tooltip({
                animation: false,
                container: '.yp-select-bar',
                title: l18_none
            });

            $(".yp-element-picker").tooltip({
                animation: false,
                placement: 'bottom',
                container: '.yp-select-bar',
                title: l18_picker
            });

            $('[data-toggle="tooltipAnimGenerator"]').tooltip({
                animation: false,
                html: true
            });

            $('[data-toggle="tooltip"]').tooltip({

                animation: false,
                container: ".yp-select-bar",
                html: true

            }).on('shown.bs.tooltip', function () {
                
                // Don't show if popover visible
                if($(".popover").length > 0){
                    $(this).tooltip("hide");
                }

            });


            /* ---------------------------------------------------- */
            /* Popovers                                             */
            /* ---------------------------------------------------- */
            $('[data-toggle="popover"]').popover({
                animation: false,
                trigger: 'hover',
                container: ".yp-select-bar"
            });

            $('.yp-option-group,.yp-advanced-option').on('shown.bs.popover', function () {
                
                // Don't show if popover visible
                if(parseFloat($(".popover").css("top")) < 80){
                    $(this).popover("hide");
                }

            });


            /* ---------------------------------------------------- */
            /* Process Current CSS                                  */
            /* ---------------------------------------------------- */
            $(document).CallCSSEngine(get_clean_css(true));


            /* ---------------------------------------------------- */
            /* Setup The plugin                                     */
            /* ---------------------------------------------------- */
            body.addClass("yp-yellow-pencil");
            body.addClass("yp-yellow-pencil-loaded");


            /* ---------------------------------------------------- */
            /* Editor Panel: Draggable                              */
            /* ---------------------------------------------------- */
            $(".yp-select-bar").draggable({

                handle: ".yp-editor-top",

                start: function(){

                    mainBody.append("<div class='fake-layer'></div>");

                },
                drag: function(event, ui){

                    ui.position.top = Math.max( -30, ui.position.top );

                },
                stop: function(){

                    $(".fake-layer").remove();

                    setTimeout(function(){
                        update_gradient_pointers();
                    },5);

                }
            });


            /* ---------------------------------------------------- */
            /* Animation Generator Bar:  Draggable                  */
            /* ---------------------------------------------------- */
            $(".anim-bar").draggable({
                handle: ".anim-bar-title",
                stop: function() {
                    $(".anim-bar").addClass("anim-bar-dragged");
                }
            });


            /* ---------------------------------------------------- */
            /* Set Animation Name Filter                            */
            /* ---------------------------------------------------- */
            $("#yp-set-animation-name").keyup(function() {
                $(this).val(get_basic_id($(this).val()));
            });

            
            /* ---------------------------------------------------- */
            /* FullScreen CSS Editor                                */
            /* ---------------------------------------------------- */
            $(".yp-css-fullscreen-btn").click(function() {

                // Fullscreen class
                body.toggleClass("yp-fullscreen-editor");

                editor.focus();
                editor.execCommand("gotolineend");
                editor.resize();

            });


            /* ---------------------------------------------------- */
            /* Hide Borders on panel hover                          */
            /* ---------------------------------------------------- */
            $(".top-area-btn-group,.yp-select-bar,.metric").hover(function() {
                if (is_content_selected() === false) {
                    clean();
                }
            });


            /* ---------------------------------------------------- */
            /* Align center animation generator panel               */
            /* ---------------------------------------------------- */
            function update_animate_creator_view() {
                if (!$(".anim-bar").hasClass("anim-bar-dragged")) {
                    $(".anim-bar").css("left", parseFloat($(window).width() / 2) - ($(".anim-bar").width() / 2));
                }
            }

            
            /* ---------------------------------------------------- */
            /* Editing Scene 10% 50% 100%                           */
            /* ---------------------------------------------------- */
            $(document).on('keydown keyup', '.scenes .scene input', function(e){

                $(this).val(number_filter($(this).val().replace(/\-/g,'')));

                if (parseFloat($(this).val()) > 100) {
                    $(this).val('100');
                }

                if (parseFloat($(this).val()) < 0) {
                    $(this).val('0');
                }

            });


            /* ---------------------------------------------------- */
            /* Last Scene always 100%                               */
            /* ---------------------------------------------------- */
            $(document).on('keyup keydown blur', '.scenes .scene:not(.scene-add):last input', function(e) {

                $(this).val('100');

            });


            /* ---------------------------------------------------- */
            /* First scene always 0%                                */
            /* ---------------------------------------------------- */
            $(document).on('keyup keydown blur', '.scenes .scene:first-child input', function(e) {

                $(this).val('0');

            });


            /* ---------------------------------------------------- */
            /* Creating Animation                                   */
            /* ---------------------------------------------------- */
            function yp_create_anim() {

                if (iframe.find(".yp-anim-scenes style").length === 0) {
                    swal({title: "Sorry.",text: l18_allScenesEmpty,type: "warning",animation: false});
                    return false;
                }

                // Variables
                var total = $(".scenes .scene").length;
                var scenesData = '';
                var i;

                // Create animation from data.
                for (i = 1; i < total; i++) {

                    scenesData = scenesData + $(".scenes .scene-" + i + " input").val() + "% {";

                    iframe.find(".yp-anim-scenes").find(".style-scene-" + i).each(function() {
                        scenesData = scenesData + (($(this).html().match(/\{(.*?)\}/g)).toString().replace("{", "").replace("}", "")) + ";";
                    });

                    scenesData = scenesData + "}";

                }

                var scenesDataReverse = scenesData.replace(/\}/g, "}YKSYXA");
                var scenesDataReverseArray = scenesDataReverse.split("YKSYXA").reverse();

                // wait
                var watingForAdd = [];
                var added = '{';
                var x,lineData,rules,countT,count,lineAll;

                for (i = 1; i < scenesDataReverseArray.length; i++) {

                    // Anim part example data.
                    lineData = $.trim(scenesDataReverseArray[i]);
                    lineAll = $.trim(scenesDataReverseArray[i]);
                    lineData = lineData.split("{")[1].split("}")[0];

                    // If is last ie first. ie 0%, no need.
                    if (scenesDataReverseArray.length - 1 == i) {

                        for (var k = 0; k < watingForAdd.length; k++) {

                            countT = 0;

                            // Search in before
                            var crex = new RegExp("(\{|;)" + watingForAdd[k] + ":");
                            
                            // Find current count
                            if (lineAll.match(crex) !== null) {
                                countT = parseInt(lineAll.match(crex).length);
                            }

                            if (countT === 0) {

                                var el = get_selected_element();
                                var val = el.css(watingForAdd[k]);

                                if (watingForAdd[k] == 'top' && val == 'auto') {
                                    val = "0px";
                                }

                                if (watingForAdd[k] == 'left' && val == 'auto') {
                                    val = "0px";
                                }

                                if (watingForAdd[k] == 'width' && val == 'auto') {
                                    val = el.width();
                                }

                                if (watingForAdd[k] == 'height' && val == 'auto') {
                                    val = el.height();
                                }

                                if (watingForAdd[k] == 'opacity' && val == 'auto') {
                                    val = "1";
                                }

                                if (watingForAdd[k] != 'right' && val != 'auto') {
                                    if (watingForAdd[k] != 'bottom' && val != 'auto') {
                                        var all = watingForAdd[k] + ":" + val + ";";
                                        scenesData = scenesData.replace("0% {", "0% {" + all);
                                        added = added + all;
                                    }
                                }

                            }

                        }

                    }

                    // Rules of this part.
                    rules = lineData.split(";");

                    // get only rules names.
                    for (x = 0; x < rules.length; x++) {
                        if (rules[x].split(":")[0] != '') {

                            var founded = rules[x].split(":")[0];
                            count = 0;

                            // Search in before
                            if (scenesData.match("{" + founded + ":") !== null) {
                                count = parseInt(scenesData.match("{" + founded + ":").length);
                            }

                            if (scenesData.match(";" + founded + ":") !== null) {
                                count = count + parseInt(scenesData.match(";" + founded + ":").length);
                            }

                            if (count < parseInt(total - 1)) {
                                watingForAdd.push(founded);
                            }

                        }
                    }

                }

                /* Adding current line data to next line datas. */
                var scenesDataNormal = scenesData.replace(/\}/g, "}TYQA");
                var scenesDataNormalArray = scenesDataNormal.split("TYQA");

                var rulesNames = [];
                var rulesValues = [];

                for (i = 0; i < scenesDataNormalArray.length; i++) {

                    // Anim part example data.
                    lineData = $.trim(scenesDataNormalArray[i]);

                    if (lineData != '' && lineData != ' ') {

                        lineData = lineData.split("{")[1].split("}")[0];

                        // Rules of this part.
                        rules = lineData.split(";");

                        // Each all rules
                        for (x = 0; x < rules.length; x++) {
                            if (rules[x].split(":")[0] != '') {

                                // Get rule name
                                var foundedName = rules[x].split(":")[0];
                                var foundedValue = rules[x].split(":")[1].split(";");

                                // Get rule value
                                if (isUndefined(foundedValue)) {
                                    foundedValue = rules[x].split(":")[1].split("}");
                                }

                                // Clean important prefix.
                                foundedValue = $.trim(foundedValue).replace(/\s+?!important/g,'').replace(/\;$/g,'');

                                // If same rule have in rulesNames, get index.
                                var index = rulesNames.indexOf(foundedName);

                                // Delete ex rule data.
                                if (index != -1) {
                                    rulesNames.splice(index, 1);
                                    rulesValues.splice(index, 1);
                                }

                                // Update with new rules.
                                rulesNames.push(foundedName);
                                rulesValues.push(foundedValue);

                            }

                        }

                        var updatedLine = "{" + lineData;

                        for (var t = 0; t < rulesNames.length; t++) {

                            var current = rulesNames[t];
                            var currentVal = rulesValues[t];

                            countT = 0;

                            // Search in this line
                            if (updatedLine.match("{" + current + ":") !== null) {
                                countT = parseInt(updatedLine.match("{" + current + ":").length);
                            }

                            if (updatedLine.match(";" + current + ":") !== null) {
                                countT = count + parseInt(updatedLine.match(";" + current + ":").length);
                            }

                            // If any rule have in rulesnames and not have in this line,
                            // so add this rule to this line.
                            if (countT < 1) {
                                updatedLine = "{" + current + ":" + currentVal + ";" + updatedLine.replace("{", "");
                            }

                        }

                        // update return value.
                        var pre = $.trim(scenesDataNormalArray[i]).split("{")[0] + "{" + lineData.replace("{", "") + "}";
                        var upNew = $.trim(scenesDataNormalArray[i]).split("{")[0] + "{" + updatedLine.replace("{", "") + "}";
                        scenesData = scenesData.replace(pre, upNew);

                    }

                }

                // Current total scenes
                total = scenesData.match(/\{/g).length;

                // Add animation name.
                scenesData = "@keyframes " + $("#yp-set-animation-name").val() + "{\r" + scenesData + "\r}";

                scenesData = scenesData.replace(/\}/g, "}\r");

                scenesData = scenesData.replace(";;","");

                return scenesData;

            }


            /* ---------------------------------------------------- */
            /* Play & Stop Animation                                */
            /* ---------------------------------------------------- */
            $(document).on("click", ".yp-animation-player,.yp-anim-play", function() {

                var element = $(this);

                var willActive = 1;

                $(".scenes .scene").each(function(i) {

                    if ($(this).hasClass("scene-active")) {
                        willActive = (i + 1);
                    }

                });

                // first scene default.
                $(".scenes .scene-1").trigger("click");

                var anim = yp_create_anim();

                if (anim === false) {
                    return false;
                }

                var delay,delayWait;

                body.addClass("yp-hide-borders-now");

                // Clean scene classes.
                var newClassList = $.trim(mainBody.attr("class").replace(/yp-scene-[0-9]/g, ''));
                mainBody.attr("class", newClassList);

                newClassList = $.trim(iframeBody.attr("class").replace(/yp-scene-[0-9]/g, ''));
                iframeBody.attr("class", newClassList);

                // AddClass
                body.addClass("yp-animate-test-playing");

                // Clean
                iframe.find(".animate-test-drive").empty();

                // Animate
                iframe.find(".animate-test-drive").append("<style>" + anim + "</style>");

                // Getting duration.
                if ($('#animation-duration-value').val().indexOf(".") != -1) {
                    delay = $('#animation-duration-value').val().split(".")[0];
                } else {
                    delay = $('#animation-duration-value').val();
                }

                if ($('#animation-duration-after').val() == 's') {
                    delayWait = delay * 1000; // second to milisecond.
                } else {
                    delayWait = delay; //milisecond
                }

                delayWait = delayWait - 10;

                delay = delay + $('#animation-duration-after').val();

                // Play.
                iframe.find(".animate-test-drive").append("<style>body.yp-animate-test-playing .yp-selected,body.yp-animate-test-playing .yp-selected-others{animation-name:" + $("#yp-set-animation-name").val() + " !important;animation-duration:" + delay + " !important;animation-iteration-count:1 !important;}</style>");

                // playing.
                element.html("Playing"+'<span style="color:#B73131 !important;" class="dashicons dashicons-controls-play"></span>');
                $(".anim-play").html("Playing"+'<span style="color:#B73131 !important;" class="dashicons dashicons-controls-play"></span>');

                clear_animation_timer();

                // Wait until finish. END.
                window.animationTimer4 = setTimeout(function() {

                    element.html("Play"+'<span class="dashicons dashicons-controls-play"></span>');
                    $(".anim-play").html("Play"+'<span class="dashicons dashicons-controls-play"></span>');
                    body.removeClass("yp-animate-test-playing");
                    iframe.find(".animate-test-drive").empty();
                    body.removeClass("yp-hide-borders-now");

                    $(".scenes .scene-" + willActive + "").trigger("click");

                    element_animation_end();

                    draw();

                }, delayWait);

            });

            
            /* ---------------------------------------------------- */
            /* Save Animation                                       */
            /* ---------------------------------------------------- */
            $(".yp-animation-creator-start,.yp-anim-save").click(function() {

                var text = $('.yp-animation-creator-start').text();

                // Save Section
                if (text == l18_save) {

                    // first scene default.
                    $(".scenes .scene-1").trigger("click");

                    var animName = $("#yp-set-animation-name").val();
                    var anim = yp_create_anim();

                    if (anim === false) {
                        return false;
                    }

                    $(".yp-animation-creator-start").text(text == l18_create ? l18_save : l18_create);
                    $(".yp-anim-save").html($(".yp-animation-creator-start").text()+'<span class="dashicons dashicons-flag"></span>');

                    var posting = $.post(ajaxurl, {

                        action: "yp_add_animation",
                        yp_anim_data: anim,
                        yp_anim_name: animName

                    });

                    // Done.
                    posting.complete(function(data) {
                        //Saved.
                    });

                    // Add animation name
                    $("#yp-animation-name-data").append("<option data-text='" + animName + "' value='" + animName + "'>" + animName + "</option>");

                    // Get data by select
                    var data = [];
                    $("#yp-animation-name-data option").each(function() {
                        data.push($(this).text());
                    });

                    // Autocomplete script
                    $("#yp-animation-name").autocomplete({
                        source: data
                    });

                    // Append style
                    iframe.find(".yp-animate-data").append("<style id='" + $("#yp-set-animation-name").val() + "style'>" + anim + "</style>");
                    iframe.find(".yp-animate-data").append("<style id='webkit-" + $("#yp-set-animation-name").val() + "style'>" + anim.replace("@keyframes", "@-webkit-keyframes") + "</style>");

                    yp_anim_cancel();

                    // Set animation name
                    setTimeout(function() {
                        insert_rule(null, "animation-name", animName, '');
                        insert_rule(null, "animation-fill-mode", 'both', '');
                        $("li.animation-option").removeAttr("data-loaded");
                        $("#yp-animation-name").val(animName).trigger("blur");
                    }, 300);

                    return false;

                }

                // append anim data area.
                if (iframe.find(".yp-anim-scenes").length === 0) {

                    // Append style area.
                    if (the_editor_data().length <= 0) {
                        iframeBody.append("<div class='yp-styles-area'></div>");
                    }

                    // Append anim style area.
                    the_editor_data().after('<div class="yp-anim-scenes"><div class="scene-1"></div><div class="scene-2"></div><div class="scene-3"></div><div class="scene-4"></div><div class="scene-5"></div><div class="scene-6"></div></div><div class="animate-test-drive"></div>');

                }

                // close css editor
                if (mainBody.hasClass("yp-css-editor-active")) {
                    $(".yp-css-close-btn").trigger("click");
                }

                // Start
                body.addClass("yp-anim-creator");

                body.addClass("yp-scene-1");
                body.attr("data-anim-scene", "scene-1");

                $(".scene-active").removeClass("scene-active");

                $(".scenes .scene:first-child").addClass("scene-active");

                // Resize scenes area.
                update_animate_creator_view();

                // Back to list.
                $(".animation-option.active > h3").trigger("click");

                $(this).text(text == l18_create ? l18_save : l18_create);
                $(".yp-anim-save").html($(".yp-animation-creator-start").text()+'<span class="dashicons dashicons-flag"></span>');

            });


            /* ---------------------------------------------------- */
            /* Cancel Animation Generating                          */
            /* ---------------------------------------------------- */
            function yp_anim_cancel() {

                // Save to create.
                $(".yp-animation-creator-start").text(l18_create);

                // Clean classes.
                body.removeClass("yp-anim-creator").removeAttr("data-anim-scene").removeClass("yp-anim-link-toggle").removeClass("yp-animate-test-playing");

                body.removeAttr("data-anim-scene");

                // Clean scene classes.
                var newClassList = $.trim(mainBody.attr("class").replace(/yp-scene-[0-9]/g, ''));
                mainBody.attr("class", newClassList);

                newClassList = $.trim(iframeBody.attr("class").replace(/yp-scene-[0-9]/g, ''));
                iframeBody.attr("class", newClassList);

                // Clean all scene data.
                iframe.find(".yp-anim-scenes .scene-1,.yp-anim-scenes .scene-2,.yp-anim-scenes .scene-3,.yp-anim-scenes .scene-4,.yp-anim-scenes .scene-5,.yp-anim-scenes .scene-6").empty();

                if ($(".yp-anim-cancel-link").length > 0) {
                    $(".yp-anim-cancel-link").trigger("click");
                }

                // Set default data again.
                insert_default_options();

                // Delete 3,4,5,6scenes.
                $(".anim-bar .scenes .scene-6 .scene-delete,.anim-bar .scenes .scene-5 .scene-delete,.anim-bar .scenes .scene-4 .scene-delete,.anim-bar .scenes .scene-3 .scene-delete").trigger("click");

                // delete test data
                iframe.find(".animate-test-drive").empty();

                gui_update();
                draw();

            }


            /* ---------------------------------------------------- */
            /* Delete scene                                         */
            /* ---------------------------------------------------- */
            $(document).on("click", ".scenes .scene .scene-delete", function() {

                var current = $(this).parent().attr("data-scene").replace("scene-", "");
                var next = $(".scenes .scene").length - 1;

                // delete all
                $(".scenes .scene:not('.scene-add')").remove();

                for (var i = 1; i < next; i++) {
                    $(".scene-add").trigger("click");
                }

                if (next == 6) {
                    $(".scene-add").show();
                    update_animate_creator_view();
                }

                // Delete all styles for this scene.
                iframe.find(".yp-anim-scenes .scene-" + current + "").empty();

                // prev active
                $(".scenes .scene-" + (current - 1) + "").trigger("click");

                return false;

            });


            /* ---------------------------------------------------- */
            /* Add Scene                                             */
            /* ---------------------------------------------------- */
            $(document).on("click", ".scenes .scene", function() {

                // Not scene add.
                if ($(this).hasClass("scene-add")) {
                    var next = $(".scenes .scene").length;

                    $(".scenes .scene-let-delete").removeClass("scene-let-delete");

                    $(".scene-add").before('<div class="scene-let-delete scene scene-' + next + '" data-scene="scene-' + next + '"><span class="dashicons dashicons-trash scene-delete"></span><p>' + l18_scene + ' ' + next + '<span><input type="text" value="100" /></span></p></div>');

                    // select added scene.
                    $(".scenes .scene-" + next + "").trigger("click");

                    $(".scene-1 input").val("0");
                    $(".scene-2 input").val("100");

                    if (next == 3) {
                        $(".scene-1 input").val("0");
                        $(".scene-2 input").val("50");
                        $(".scene-3 input").val("100");
                    }

                    if (next == 4) {
                        $(".scene-1 input").val("0");
                        $(".scene-2 input").val("33.3");
                        $(".scene-3 input").val("66.6");
                        $(".scene-4 input").val("100");
                    }

                    if (next == 5) {
                        $(".scene-1 input").val("0");
                        $(".scene-2 input").val("25");
                        $(".scene-3 input").val("50");
                        $(".scene-4 input").val("75");
                        $(".scene-5 input").val("100");
                    }

                    if (next == 6) {
                        $(".scene-1 input").val("0");
                        $(".scene-2 input").val("20");
                        $(".scene-3 input").val("40");
                        $(".scene-4 input").val("60");
                        $(".scene-5 input").val("80");
                        $(".scene-6 input").val("100");
                    }

                    if (next == 6) {
                        $(".scene-add").hide();
                    }
                    update_animate_creator_view();
                    return false;
                }

                // Set active class
                $(".scene-active").removeClass("scene-active");
                $(this).addClass("scene-active");

                // Update current scene.
                body.attr("data-anim-scene", $(this).attr("data-scene"));

                // Delete ex scene classes.
                var newClassList = $.trim(mainBody.attr("class").replace(/yp-scene-[0-9]/g, ''));
                mainBody.attr("class", newClassList);

                newClassList = $.trim(iframeBody.attr("class").replace(/yp-scene-[0-9]/g, ''));
                iframeBody.attr("class", newClassList);

                // Add new scene class.
                body.addClass("yp-" + $(this).attr("data-scene"));

                // loop
                for (var currentVal = parseInt($(this).attr("data-scene").replace("scene-", ""));currentVal > 1; currentVal--) {
                    if (currentVal !== 0) {
                        body.addClass("yp-scene-" + currentVal);
                    }
                }

                insert_default_options();
                $(".yp-disable-btn.active").trigger("click");

                draw();

            });


            /* ---------------------------------------------------- */
            /* Cancel Animation Generating                          */
            /* ---------------------------------------------------- */
            $(".yp-anim-cancel").click(function() {
                $(".yp-anim-cancel-link").trigger("click");
            });


            /* ---------------------------------------------------- */
            /* Inline Collapse, used for parallax, transform        */
            /* ---------------------------------------------------- */
            $(".yp-advanced-link").click(function() {

                // Adding animation link
                if ($(this).hasClass("yp-add-animation-link")) {

                    body.toggleClass("yp-anim-link-toggle");
                    $(this).toggleClass("yp-anim-cancel-link");

                    if (!$(this).hasClass("yp-anim-cancel-link")) {
                        yp_anim_cancel();
                    }

                    if ($("#animation-duration-value").val() == '0' || $("#animation-duration-value").val() == '0.00'){
                        $("#animation-duration-value").val("1");
                        $("#animation-duration-value").trigger("blur");
                    }

                    // update animation ame.
                    if($(this).hasClass("yp-add-animation-link")){
                        var slctor = get_current_selector();
                        var animID = get_basic_id(uppercase_first_letter(get_tag_information(slctor)))+"_Animation_"+Math.floor((Math.random() * 99));
                        $("#yp-set-animation-name").val(animID).trigger("focus");
                    }

                    var text = $('.yp-add-animation-link').text();
                    $('.yp-add-animation-link').text(text == l18_CreateAnimate ? l18_cancel : l18_CreateAnimate);

                    gui_update();
                    return false;
                }

                $(".yp-on").not(this).removeClass("yp-on");

                $(".yp-advanced-option").not($(this).next(".yp-advanced-option")).hide(0);

                $(this).next(".yp-advanced-option").toggle(0);

                $(this).toggleClass("yp-on");

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Updating Responsive handles on CSS editor toggle     */
            /* ---------------------------------------------------- */
            $(".top-area-btn,.yp-css-close-btn").click(function(){
                setTimeout(function(){
                    window.FrameleftOffset = undefined;
                    draw_responsive_handle();
                },50);
            });


            /* ---------------------------------------------------- */
            /* Active Classes Left Panel                            */
            /* ---------------------------------------------------- */
            $(".top-area-btn:not(.undo-btn):not(.redo-btn):not(.css-editor-btn)").click(function(){

                if(is_animate_creator() === false){

                    $(this).toggleClass("active");
                    $(this).tooltip("hide");

                }else if($(this).hasClass("yp-selector-mode") === false && $(this).hasClass("yp-button-target") === false){

                    $(this).toggleClass("active");
                    $(this).tooltip("hide");

                }
                
            });

            
            /* ---------------------------------------------------- */
            /* FullScreen                                           */
            /* ---------------------------------------------------- */
            $(".fullscreen-btn").click(function() {
                toggle_fullscreen(document.body);
            });

            
            /* ---------------------------------------------------- */
            /* Undo                                                 */
            /* ---------------------------------------------------- */
            $(".undo-btn").click(function() {

                clearTimeout(window.historyDelay);

                if(mainBody.hasClass("yp-history-delay")){
                        
                    window.historyDelay = setTimeout(function(){
                        undo_changes();
                    },220);

                }else{
                    undo_changes();
                }

            });


            /* ---------------------------------------------------- */
            /* Redo                                                 */
            /* ---------------------------------------------------- */
            $(".redo-btn").click(function() {

                clearTimeout(window.historyDelay);

                if(mainBody.hasClass("yp-history-delay")){
                        
                    window.historyDelay = setTimeout(function(){
                        redo_changes();
                    },220);

                }else{
                    redo_changes();
                }

            });


            /* ---------------------------------------------------- */
            /* Open Pattern section and dynamic loads               */
            /* ---------------------------------------------------- */
            $(".yp-bg-img-btn").click(function() {

                // Show pattern section
                $(this).toggleClass("active");
                $(".yp_background_assets").toggle();

                // get CSS
                var val = $("#yp-background-image").val();

                // Update active pattern
                if(val.indexOf("yellow-pencil") == -1){
                    $(".yp_bg_assets").removeClass("active");
                }else{
                    $(".yp_bg_assets[data-url='" + val.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "") + "']").addClass("active");
                }
                
                // Delay
                setTimeout(function(){

                    var activePattern = 0;

                    if($(".yp_bg_assets.active").length > 0){
                        activePattern = ($(".yp_bg_assets.active").index()-1) * 100;
                    }

                    $(".yp_background_assets").scrollTop(activePattern);

                    load_near_patterns(activePattern);

                },10);

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Adds focus class to mouseenter pattern               */
            /* ---------------------------------------------------- */
            $(".yp_bg_assets").on("mouseenter mouseover",function(){
                $(".yp_bg_assets").removeClass("focus");
                $(this).addClass("focus");
            });


            /* ---------------------------------------------------- */
            /* Loads background patterns on scrolling               */
            /* ---------------------------------------------------- */
            $(".yp_background_assets").on("scroll",function(){
                load_near_patterns(null);
            });


            /* ---------------------------------------------------- */
            /* Loading near patterns                                */
            /* ---------------------------------------------------- */
            function load_near_patterns(scrollTop){

                if(scrollTop == null){
                    scrollTop = $(".yp_background_assets").scrollTop();
                }

                var start = parseInt(scrollTop/100) - 2;

                var end = start + 13;

                var element;

                for(var i = start; i < end; i++){

                    element = $(".yp_bg_assets:nth-child("+i+")");
                    element.css("backgroundImage", "url(" + element.data("url") + ")");

                }

            }

            /* ---------------------------------------------------- */
            /* Flat color toggle                                    */
            /* ---------------------------------------------------- */
            $(".yp-flat-colors").click(function() {

                $(this).toggleClass("active");
                $(this).parent().find(".yp_flat_colors_area").toggle();

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Gradient Toggle                                    */
            /* ---------------------------------------------------- */
            $(".yp-gradient-btn").on("click", function() {

                var el = $(this);

                // if not active
                if(!el.hasClass("active")){

                    // Background image data
                    var data = $("#yp-background-image").val();

                    // if has gradient, read it
                    if(data.indexOf("linear-gradient(") != -1){

                        // CSS to gradient tool
                        read_gradient(data);

                    }else{

                        // Read default gradient
                        read_gradient('linear-gradient(141deg, #0fb8ad 0%, #2cb5e8 100%)');

                    }

                }

                // Add active class
                el.toggleClass("active");

                // show gradient tool
                $(".yp-gradient-section").toggle();

                // update gui
                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Meterial Colors toggle                               */
            /* ---------------------------------------------------- */
            $(".yp-meterial-colors").click(function() {

                $(this).toggleClass("active");
                $(this).parent().find(".yp_meterial_colors_area").toggle();

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Nice Colors toggle                                   */
            /* ---------------------------------------------------- */
            $(".yp-nice-colors").click(function() {

                $(this).parent().find(".yp_nice_colors_area").toggle();
                $(this).toggleClass("active");

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Image uploader toggle                                */
            /* ---------------------------------------------------- */
            $(".yp-upload-btn").click(function() {

                // Get iframe contents.
                $('#image_uploader iframe').attr('src',$('#image_uploader iframe').attr('data-url'));

                $('#image_uploader iframe').attr('src', function(i, val){
                    return val;
                });

                window.send_to_editor = function(output) {

                    var imgurl = output.match(/src="(.*?)"/g);
                    var imgNew = '';

                    imgurl = imgurl.toString().replace('src="', '').replace('"', '');

                    // Always get full size.
                    if (imgurl != '') {

                        var y = imgurl.split("-").length - 1;

                        if (imgurl.split("-")[y].match(/(.*?)x(.*?)\./g) !== null) {

                            imgNew = imgurl.replace("-" + imgurl.split("-")[y], '');

                            // format
                            if (imgurl.split("-")[y].indexOf(".") != -1) {
                                imgNew = imgNew + "." + imgurl.split("-")[y].split(".")[1];
                            }

                        } else {
                            imgNew = imgurl;
                        }

                    }

                    if($(".background-option.active").length > 0){
                        $("#yp-background-image").val(imgNew).trigger("keyup");
                    }else{
                        $("#yp-list-style-image").val(imgNew).trigger("keyup");
                    }

                    window.send_to_editor = window.restore_send_to_editor;

                    $("#image_uploader").toggle();
                    $("#image_uploader_background").toggle();

                };

                $("#image_uploader").toggle();
                $("#image_uploader_background").toggle();

            });


            /* ---------------------------------------------------- */
            /* Close Image uploader on background click             */
            /* ---------------------------------------------------- */
            $("#image_uploader_background").click(function() {
                $("#image_uploader").toggle();
                $("#image_uploader_background").toggle();
                $('#image_uploader iframe').attr('src', function(i, val) {
                    return val;
                });
            });


            // WP Window Upload
            window.restore_send_to_editor = window.send_to_editor;


            /* ---------------------------------------------------- */
            /* Image Uploader callback                              */
            /* ---------------------------------------------------- */
            window.send_to_editor = function(html) {

                var imgurl = $('img', html).attr('src');

                 if($(".background-option.active").length > 0){
                    $("#yp-background-image").val(imgurl);
                }else{
                    $("#yp-list-style-image").val(imgurl);
                }

                window.send_to_editor = window.restore_send_to_editor;

                $("#image_uploader").toggle();
                $("#image_uploader_background").toggle();
                $('#image_uploader iframe').attr('src', function(i, val) {
                    return val;
                });

            };


            // Default Option Change
            window.option_changeType = 'auto';
            option_change();
            window.option_changeType = 'default';


            // Setup the title
            $("title").html("Yellow Pencil: " + iframe.find("title").html());


            // Check before exit page.
            window.onbeforeunload = confirm_exit;


            /* ---------------------------------------------------- */
            /* Alert before exit                                    */
            /* ---------------------------------------------------- */
            function confirm_exit() {

                if ($(".yp-save-btn").hasClass("waiting-for-save")) {
                    return confirm(l18_sure);
                }

            }

            /* ---------------------------------------------------- */
            /* Save button                                          */
            /* ---------------------------------------------------- */
            $(".yp-save-btn").on("click", function() {

                // If all changes already saved, So Stop.
                if ($(this).hasClass("yp-disabled")) {
                    return false;
                }

                // Getting Customized page id.
                var id = window.location.href.split("&yp_id=");

                if (isDefined(id[1])) {
                    id = id[1].split("&");
                    id = id[0];
                } else {
                    id = undefined;
                }

                // Getting Customized Post Type
                var type = window.location.href.split("&yp_type=");
                if (isDefined(type[1])) {
                    type = type[1].split("&");
                    type = type[0];
                } else {
                    type = undefined;
                }

                // Send Ajax If Not Demo Mode.
                if (!mainBody.hasClass("yp-yellow-pencil-demo-mode")) {

                    var data = get_clean_css(true);

                    // Lite Version Checking.
                    var status = true;

                    if (mainBody.hasClass("wtfv")) {

                        if (
                            data.indexOf("font-family:") != -1 ||
                            data.match(/\s\s+color\:/g) !== null ||
                            data.indexOf("background-image:") != -1 ||
                            data.indexOf("background-color:") != -1 ||
                            data.indexOf("opacity:") != -1 ||
                            data.indexOf("width:") != -1 ||
                            data.indexOf("height:") != -1 ||
                            data.indexOf("animation-name:") != -1){

                            status = false;

                            $(".wt-save-btn").html(l18_save).removeClass("waiting-for-save").removeClass("wt-disabled");

                            $(".yp-info-modal,.yp-popup-background").show();

                        } else {

                            // BeforeSend
                            $(".yp-save-btn").html(l18_saving).addClass("yp-disabled");

                        }

                    } else {

                        // BeforeSend
                        $(".yp-save-btn").html(l18_saving).addClass("yp-disabled");

                    }

                    // Convert CSS To Data and save.
                    if (mainBody.hasClass("yp-need-to-process")) {

                        if (status) {
                            process(false, id, type);
                        }

                    } else {

                        if (status) {

                            var posting = $.post(ajaxurl, {

                                action: "yp_ajax_save",
                                yp_id: id,
                                yp_stype: type,
                                yp_data: data,
                                yp_editor_data: get_editor_data()

                            });

                            $.post(ajaxurl, {

                                action: "yp_preview_data_save",
                                yp_data: data

                            });


                            // Done.
                            posting.complete(function(data) {
                                $(".yp-save-btn").html(l18_saved).addClass("yp-disabled").removeClass("waiting-for-save");
                            });

                        }

                    }

                } else {

                    swal({title: "Sorry.",text: l18_demo_alert,type: "info",animation: false});
                    $(".yp-save-btn").html(l18_saved).addClass("yp-disabled").removeClass("waiting-for-save");

                }

            });


            /* ---------------------------------------------------- */
            /* Check the CSS value with parents                     */
            /* ---------------------------------------------------- */
            function check_with_parents(element, css, value, comparison){

                var checkElements = element.add(element.parents());
                var animation_fill_mode,el,returnValue = true;

                checkElements.each(function(){

                    el = $(this);

                    animation_fill_mode = null;

                    // Be sure there not have any element animating.
                    if(el.hasClass("yp-animating") === false){

                        // nowdays a lot website using animation on page loads.
                        // the problem is a lot animations has transfrom, opacity etc.
                        // This break the system and can't get real value.
                        // So I will fix this issue : ).
                        animation_fill_mode = el.css("animationFillMode");

                        // Disable it until we get real value.
                        if(animation_fill_mode == 'forwards' || animation_fill_mode == 'both'){

                            // Set none for animation-fill-mode and be sure. using promise.
                            $.when(el.css("animationFillMode","none")).promise().always(function() {

                                // Continue after done.
                                returnValue = check_with_parents_last(el, css, value, comparison, animation_fill_mode);

                                if(returnValue === true){
                                    return false;
                                }

                            });

                        }else{

                            // Continue to last part.
                            returnValue = check_with_parents_last(el, css, value, comparison);

                            if(returnValue === true){
                                return false;
                            }

                        }

                    }else{

                        // Continue to last part.
                        returnValue = check_with_parents_last(el, css, value, comparison);

                        if(returnValue === true){
                            return false;
                        }

                    }

                });
                
                return returnValue;

            }


            /* ---------------------------------------------------- */
            /* A part of check_with_parents                         */
            /* ---------------------------------------------------- */
            function check_with_parents_last(el, css, value, comparison, animation_fill_mode){

                var isVal = false;

                // Get CSS
                var cssValue = el.css(css);

                // If not none but and not have any transform.
                if(css == 'transform' && cssValue == 'matrix(1, 0, 0, 1, 0, 0)'){
                    cssValue = 'none';
                }

                if (comparison == '==') {

                    if (cssValue === value) {
                        if(animation_fill_mode !== undefined){el.css("animationFillMode",animation_fill_mode);}
                        return true;

                    }

                } else {

                    if (cssValue !== value) {
                        if(animation_fill_mode !== undefined){el.css("animationFillMode",animation_fill_mode);}
                        return true;

                    }

                }

                if(animation_fill_mode !== undefined){el.css("animationFillMode",animation_fill_mode);}
                return isVal;

            }


            /* ---------------------------------------------------- */
            /* Close contextMenu on scroll                          */
            /* ---------------------------------------------------- */
            var timerx = null;
            iframe.scroll(function() {

                if (iframe.find(".context-menu-active").length > 0) {
                    get_selected_element().contextMenu("hide");
                }

                if(timerx !== null) {
                    clearTimeout(timerx);        
                }

                if(is_content_selected()){

                    // Set outline border while scrolling if its is fixed.
                    // Need to reflesh the position on scrolling and this will make feel slow the editor.
                    if (check_with_parents(get_selected_element(), "position", "fixed", "==") === true) {

                        if (!mainBody.hasClass("yp-has-transform")){ // if not have.

                            body.addClass("yp-has-transform"); // add

                        }else{

                            // back to normal borders and update position. 
                            timerx = setTimeout(function(){

                                body.removeClass("yp-has-transform");

                                draw();

                            },250);

                        }

                    }

                }

            });

    
            /* ---------------------------------------------------- */
            /* Updating tooltips on scrolling                       */
            /* ---------------------------------------------------- */
            var timer = null;
            iframe.on("scroll", iframe, function(evt){

                if(timer !== null) {
                    clearTimeout(timer);        
                }

                timer = setTimeout(function(){
                    if(is_content_selected()){
                        draw_tooltip();
                    }
                }, 120);

            });


            /* ---------------------------------------------------- */
            /* Set as Background Image                              */
            /* ---------------------------------------------------- */
            $(".yp_background_assets div").click(function() {
                $(".yp_background_assets div.active").removeClass("active");
                $(this).parent().parent().find(".yp-input").val($(this).data("url")).trigger("keyup");
                $(this).addClass("active");
                $("#background-repeat-group .yp-none-btn:not(.active),#background-size-group .yp-none-btn:not(.active)").trigger("click");
            });

            
            /* ---------------------------------------------------- */
            /* Set Color                                            */
            /* ---------------------------------------------------- */
            $(".yp_flat_colors_area div,.yp_meterial_colors_area div,.yp_nice_colors_area div").click(function() {

                var element = $(this);
                var elementParent = element.parent();

                $(".yp_flat_colors_area,.yp_meterial_colors_area,.yp_nice_colors_area").find(".active").removeClass("active");
                elementParent.parent().parent().parent().find(".wqcolorpicker").val($(this).data("color")).trigger("change");
                $(this).addClass("active");

            });


            /* ---------------------------------------------------- */
            /* Blur the editor                                      */
            /* ---------------------------------------------------- */
            function blur_editor(event){

                if(!event.originalEvent){
                    return false;
                }

                if(window.documentClick == false){
                    return false;
                }

                var el = $(event.target);

                var irisWasOpen = false;

                if (el.is(".iris-picker") === false && el.is(".iris-square-inner") === false && el.is(".iris-square-handle") === false && el.is(".iris-slider-offset") === false && el.is(".iris-slider-offset .ui-slider-handle") === false && el.is(".iris-picker-inner") === false && el.is(".wqcolorpicker") === false) {

                    // first hide iris, later gradient. not all in one click.
                    if($(".yp-gradient-section .iris-picker:visible").length > 0){
                        irisWasOpen = true;
                    }

                    if($(".iris-picker .ui-state-active").length == 0 && $(".iris-picker .iris-dragging").length == 0){
                        $(".iris-picker").hide();
                        gui_update();
                    }

                    $(".yp-gradient-pointer-area").removeClass("gradient-pointer-no-cursor");

                }

                if (el.is('.yp_bg_assets') === false && el.is('.yp-none-btn') === false && el.is('.yp-bg-img-btn') === false && $(".yp_background_assets:visible").length > 0) {
                    $(".yp_background_assets").hide();
                    $(".yp-bg-img-btn").removeClass("active");
                    gui_update();
                }

                if (el.is('.yp-flat-c') === false && el.is('.yp-flat-colors') === false && $(".yp_flat_colors_area:visible").length > 0) {
                    $(".yp_flat_colors_area").hide();
                    $(".yp-flat-colors").removeClass("active");
                    gui_update();
                }

                if (el.is('.yp-meterial-c') === false && el.is('.yp-meterial-colors') === false && $(".yp_meterial_colors_area:visible").length > 0) {
                    $(".yp_meterial_colors_area").hide();
                    $(".yp-meterial-colors").removeClass("active");
                    gui_update();
                }

                if (el.is('.yp-nice-c') === false && el.is('.yp-nice-colors') === false && $(".yp_nice_colors_area:visible").length > 0) {
                    $(".yp_nice_colors_area").hide();
                    $(".yp-nice-colors").removeClass("active");
                    gui_update();
                }


                if (irisWasOpen == false && $(".iris-picker:visible").length == 0 && el.parents('.context-menu-list').length === 0 && el.is('.context-menu-layer') === false && el.is('.yp-gradient-btn') === false && el.parents('.yp-gradient-section').length === 0 && $(".yp-gradient-section:visible").length > 0 && $(".yp-gradient-pointer.ui-draggable-dragging").length == 0 && $(".yp-gradient-orientation .ui-draggable-dragging").length == 0 && el.is("#context-menu-layer") == false && el.is(".context-menu-list") == false) {
                    $(".yp-gradient-section").hide();
                    $(".yp-gradient-pointer,.yp-gradient-btn").removeClass("active");
                    gui_update();
                }

            }

            /* ---------------------------------------------------- */
            /* blur_editor on click                                 */
            /* ---------------------------------------------------- */
            $(document).on("click", blur_editor);


            /* ---------------------------------------------------- */
            /* Search Selector Tool Close                           */
            /* ---------------------------------------------------- */
            $("#yp-target-dropdown").on("click", function(e) {
                if (e.target !== this) {
                    return;
                }

                $("#target_background").trigger("click");
            });


            /* ---------------------------------------------------- */
            /* Adds similar selectors to Search selector Tool       */
            /* ---------------------------------------------------- */
            function add_similar_selectors(selector) {

                if (selector == '' || selector == '.' || selector == '#' || selector == ' ' || selector == '  ' || selector == get_current_selector() || selector == $("#yp-button-target-input").val()) {
                    return false;
                }

                if ($("#yp-target-dropdown li").length < 10) {

                    if (iframe.find(selector).length === 0) {
                        return false;
                    }

                    if ($("#" + get_id(selector)).length > 0) {
                        return false;
                    }

                    var selectorOrginal = selector;
                    var selectorParts;

                    if (selector.indexOf("::") != -1) {
                        selectorParts = selector.split("::");
                        selector = selectorParts[0] + "<b>::" + selectorParts[1] + "</b>";
                    } else if (selector.indexOf(":") != -1) {
                        selectorParts = selector.split(":");
                        selector = selectorParts[0] + "<b>:" + selectorParts[1] + "</b>";
                    }

                    var role = ' ';
                    if (selector.indexOf(" > ") != -1) {
                        role = ' > ';
                    }

                    selector = "<span style=\"color:#D70669\">" + selector.replace(new RegExp(role, "g"), '</span>' + role + '<span style="color:#D70669">') + "</span>";
                    selector = selector.replace(/<span style=\"(.*?)\">\#(.*?)<\/span>/g, '<span style="color:#6300FF">\#$2<\/span>');

                    $("#yp-target-dropdown").append("<li id='" + get_id(selectorOrginal) + "'>" + selector + " | " + get_tag_information(selectorOrginal) + "</li>");

                }

            }


            /* ---------------------------------------------------- */
            /* Toggle CSS Editor                                    */
            /* ---------------------------------------------------- */
            function css_editor_toggle(status) {

                if (status === true) {

                    if (mainBody.hasClass("yp-css-editor-active")) {
                        $(".yp-css-close-btn").trigger("click");
                    }
                    mainBody.removeClass("yp-clean-look");

                } else {
                    mainBody.toggleClass("yp-clean-look");
                    if (mainBody.hasClass("yp-css-editor-active")) {
                        mainBody.removeClass("yp-css-editor-active");

                        var ebtn = $(".css-editor-btn");
                        ebtn.attr("data-original-title",ebtn.attr("data-title"));

                        $("#leftAreaEditor").hide();
                    }
                    gui_update();
                }

            }


            /* ---------------------------------------------------- */
            /* Creating Similar Selectors for Search Tool           */
            /* ---------------------------------------------------- */
            function create_similar_selectors() {

                var selector;

                $("#yp-target-dropdown li").remove();

                if ($("#yp-button-target-input").val() == '') {

                    selector = get_current_selector();

                } else {

                    selector = $("#yp-button-target-input").val();

                }

                if (isUndefined(selector)) {
                    return false;
                }

                var max = 10;

                // adding all ids
                if (selector == '#') {
                    iframe.find("[id]").not(window.simple_not_selector).each(function(i, v){
                        if (i < max) {
                            add_similar_selectors("#" + $(this).attr('id'));
                        }else{
                            return false;
                        }
                    });
                    return false;
                }

                // adding all classes
                if (selector == '.') {
                    iframe.find("[class]").not(window.simple_not_selector).each(function(i, v) {
                        if (i < max) {
                            add_similar_selectors("." + $(this).attr('class'));
                        }else{
                            return false;
                        }
                    });
                    return false;
                }

                if (selector.indexOf("::") > -1) {
                    selector = selector.split("::")[0];
                } else if (selector.indexOf(":") > -1) {
                    selector = selector.split(":")[0];
                }

                if (selector == '  ' || selector == ' ' || selector == '') {
                    return false;
                }

                // Using prefix
                if (get_selector_array(selector).length > 0) {

                    var last = null;
                    var elsAll,rex;
                    var lastPart = get_selector_array(selector)[(get_selector_array(selector).length - 1)];
                    if (lastPart.indexOf(" ") == -1) {
                        last = lastPart;
                    }

                    if (last !== null){

                        var selectorY = $.trim(selector.replace(/\#$/g,"").replace(/\.$/g,""));

                        // adding all ids
                        if (last == '#') {
                            iframe.find(selectorY).find("[id]").not(window.simple_not_selector).each(function(i, v) {
                                if (i < max) {
                                    add_similar_selectors(selector + $(this).attr('id'));
                                }else{
                                    return false;
                                }
                            });
                            return false;
                        }

                        // adding all classes
                        if (last == '.') {
                            iframe.find(selectorY).find("[class]").not(window.simple_not_selector).each(function(i, v) {
                                if (i < max) {
                                    add_similar_selectors(selector + $(this).attr('class'));
                                }else{
                                    return false;
                                }
                            });
                            return false;
                        }

                        // For Classes
                        if (last.indexOf(".") != -1){

                            elsAll = iframe.find("[class^='" + last.replace(/\./g, '') + "']").not(window.simple_not_selector);
                            rex = new RegExp("^" + last.replace(/\./g, '') + "(.+)");

                            elsAll.each(function(){

                                var classes = $(this).attr('class').split(' ');

                                for (var i = 0; i < classes.length; i++) {

                                    var matches = rex.exec(classes[i]);

                                    if (matches !== null) {
                                        var Foundclass = matches[1];
                                        add_similar_selectors(selector + Foundclass);
                                    }

                                }

                            });

                        }

                        // For ID
                        if (last.indexOf("#") != -1){

                            elsAll = iframe.find("[id^='" + last.replace(/\#/g, '') + "']").not(window.simple_not_selector);
                            rex = new RegExp("^" + last.replace(/\#/g, '') + "(.+)");

                            elsAll.each(function(){

                                var ids = $(this).attr('id').split(' ');

                                for (var i = 0; i < ids.length; i++) {

                                    var matches = rex.exec(ids[i]);

                                    if (matches !== null) {
                                        var Foundclass = matches[1];
                                        add_similar_selectors(selector + Foundclass);
                                    }

                                }

                            });

                        }

                    }else{
                        add_similar_selectors(selector);
                    }

                }

                // Adding childrens.
                var childrens = iframe.find(selector).find("*").not(window.simple_not_selector);

                if (childrens.length === 0) {
                    return false;
                }

                childrens.each(function(i) {
                    if(i < max){
                        add_similar_selectors(selector + " " + get_best_class($(this)));
                    }else{
                        return false;
                    }
                });

            }


            /* ---------------------------------------------------- */
            /* Click to similar selectors                           */
            /* ---------------------------------------------------- */
            $(document).on("click", "#yp-target-dropdown li", function() {

                $("#yp-button-target-input").val($(this).text().split(" |")[0]).trigger("keyup").trigger("focus");

                $(".yp-button-target").trigger("click");

            });


            /* ---------------------------------------------------- */
            /* Open Search Selector Tool                            */
            /* ---------------------------------------------------- */
            $(".yp-button-target").click(function(e) {

                if ($(e.target).hasClass("yp-button-target-input")) {
                    return false;
                }

                if (iframe.find(".context-menu-active").length > 0) {
                    get_selected_element().contextMenu("hide");
                }

                var element = $(this);
                var selector;

                // if Search tool is closed
                if (!element.hasClass("active") && body.hasClass("yp-pressed-enter-key") === false) {

                    body.addClass("yp-target-active");
                    element.removeClass("active");

                    selector = get_current_selector();

                    if (body.attr("data-yp-selector") == ':hover') {
                        selector = selector + ":hover";
                    }

                    if (body.attr("data-yp-selector") == ':focus') {
                        selector = selector + ":focus";
                    }

                    if (body.attr("data-yp-selector") == ':link') {
                        selector = selector + ":link";
                    }

                    if (body.attr("data-yp-selector") == ':active') {
                        selector = selector + ":active";
                    }

                    if (body.attr("data-yp-selector") == ':visited') {
                        selector = selector + ":visited";
                    }

                    if (isUndefined(selector)) {
                        selector = '.';
                    }

                    $("#yp-button-target-input").trigger("focus").val(selector).trigger("keyup");

                    create_similar_selectors();

                } else {

                    selector = $("#yp-button-target-input").val();

                    if (selector == '' || selector == ' ') {
                        element.addClass("active");
                        body.removeClass("yp-target-active");
                    }

                    // Be sure hover and focus to last because just support hover&focus in last.
                    var hoverPosition = selector.match(/:hover(.*?)$/g);
                    var focusPosition = selector.match(/:focus(.*?)$/g);
                    var visitedPosition = selector.match(/:visited(.*?)$/g);
                    var activePosition = selector.match(/:active(.*?)$/g);
                    var linkPosition = selector.match(/:link(.*?)$/g);

                    if(hoverPosition !== null){
                        hoverPosition = hoverPosition.toString().trim().replace(/:hover/g,'').trim().length;
                    }else{
                        hoverPosition = 0;
                    }

                    if(focusPosition !== null){
                        focusPosition = focusPosition.toString().trim().replace(/:focus/g,'').trim().length;
                    }else{
                        focusPosition = 0;
                    }

                    if(visitedPosition !== null){
                        visitedPosition = visitedPosition.toString().trim().replace(/:visited/g,'').trim().length;
                    }else{
                        visitedPosition = 0;
                    }

                    if(activePosition !== null){
                        activePosition = activePosition.toString().trim().replace(/:active/g,'').trim().length;
                    }else{
                        activePosition = 0;
                    }

                    if(linkPosition !== null){
                        linkPosition = linkPosition.toString().trim().replace(/:link/g,'').trim().length;
                    }else{
                        linkPosition = 0;
                    }

                    var selectorNew = selector.replace(/:hover/g, '').replace(/:focus/g, '').replace(/:link/g, '').replace(/:visited/g, '').replace(/:active/g, '');


                    if (iframe.find(selectorNew).length > 0 && selectorNew != '*' && hoverPosition === 0 && focusPosition === 0) {

                        if (iframe.find(selector).hasClass("yp-selected")) {
                            get_selected_element().addClass("yp-will-selected");
                        }

                        set_selector(space_cleaner(selector),null);

                        // selected element
                        var selectedElement = iframe.find(selectorNew);

                        // scroll to element if not visible on screen.
                        var height = parseInt($(window).height() / 2);
                        var selectedHeight = parseInt(selectedElement.height() / 2);
                        if (selectedHeight < height) {
                            var scrollPosition = selectedHeight + selectedElement.offset().top - height;
                            iframe.scrollTop(scrollPosition);
                        }

                        element.addClass("active");
                        body.removeClass("yp-target-active");

                    } else if (selectorNew != '' && selectorNew != ' '){

                        $("#yp-button-target-input").css("color", "red");

                        element.removeClass("active");
                        body.addClass("yp-target-active");

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Close Search Selector Tool                           */
            /* ---------------------------------------------------- */
            $("#target_background").click(function() {

                body.removeClass("yp-target-active");
                $("#yp-button-target-input").val("");
                $(".yp-button-target").trigger("click");

            });


            /* ---------------------------------------------------- */
            /* Writing to Search Selector Tool                      */
            /* ---------------------------------------------------- */
            $("#yp-button-target-input").keyup(function(e) {

                if($(this).val().length > 1 || $(this).val() == '#' || $(this).val() == "."){
                    create_similar_selectors();
                }

                if (e.keyCode != 13){
                    $(this).attr("style", "");
                }

                // Enter
                if (e.keyCode == 13) {
                    body.addClass("yp-pressed-enter-key");
                    $(".yp-button-target").trigger("click");
                    body.removeClass("yp-pressed-enter-key");
                    return false;
                }

            });


            /* ---------------------------------------------------- */
            /* If Selector is not available                         */
            /* ---------------------------------------------------- */
            $("#yp-button-target-input").keydown(function(e) {

                if (e.keyCode != 13){
                    $(this).attr("style", "");
                }

            });


            /* ---------------------------------------------------- */
            /* Setup iris picker                                    */
            /* ---------------------------------------------------- */
            var wIris = 237;
            if ($(window).width() < 1367) {
                wIris = 195;
            }


            /* ---------------------------------------------------- */
            /* Setup inside collapse iris picker                    */
            /* ---------------------------------------------------- */
            $('.yp-select-bar .wqcolorpicker').cs_iris({
                hide: true,
                width: wIris
            });


            /* ---------------------------------------------------- */
            /* Breakpoint bar click                                 */
            /* ---------------------------------------------------- */
            $(document).on("mouseover click",".breakpoint-bar div",function(e){

                // Breakpoint element
                var el = $(this);

                // 40ms delay
                window.breakpointHoverTimer = setTimeout(function(){

                    // Delete all old viewers
                    iframe.find(".yp-element-viewer").remove();

                    // variables
                    var element_offset,element,topBoxesI,leftBoxesI,widthBoxesI,heightBoxesI,selector,elements;

                    // each all founded data
                    the_editor_data().find('[data-size-mode="'+el.attr('data-breakpoint-data')+'"]').each(function(i,v){

                        // find selectors
                        selector = get_foundable_query($(this).html().match(/\{(.*?)\{/g).toString().replace(/\{/g,""),true,true,true);
                        
                        // element object
                        elements = iframe.find(selector);

                        // Each all founded elements
                        elements.each(function(){

                            // element object
                            element = $(this);

                            // offset
                            element_offset = element.offset();

                            // check if valid
                            if (isDefined(element_offset)) {

                                // getting element positions
                                topBoxesI = element_offset.top;
                                leftBoxesI = element_offset.left;

                                if (leftBoxesI < 0) {
                                    leftBoxesI = 0;
                                }

                                // Getting element sizes
                                widthBoxesI = element.outerWidth();
                                heightBoxesI = element.outerHeight();

                                var id = "yp-element-viewer-"+parseInt(widthBoxesI)+"-"+parseInt(heightBoxesI)+"-"+parseInt(topBoxesI)+"-"+parseInt(leftBoxesI)+"";

                                // add the viewer
                                if(iframe.find("#"+id).length == 0){
                                    iframeBody.append("<div class='yp-element-viewer' id='"+id+"' style='width:"+widthBoxesI+"px;height:"+heightBoxesI+"px;top:"+topBoxesI+"px;left:"+leftBoxesI+"px;'></div>");
                                }

                            } // element offset if


                        }); // elements each

                    }); // each founded breakpoint datas

                },40); // delay.

            });


            /* ---------------------------------------------------- */
            /* Hide breakpoint on click                             */
            /* ---------------------------------------------------- */
            $(document).on("mousedown",".breakpoint-bar div",function(e){

                $(this).tooltip("hide");

            });


            /* ---------------------------------------------------- */
            /* Shows affected elements when mouseover the breakpoint*/
            /* ---------------------------------------------------- */
            $(document).on("mouseout",".breakpoint-bar div",function(e){

                clearTimeout(window.breakpointHoverTimer);
                iframe.find(".yp-element-viewer").remove();

            });


            /* ---------------------------------------------------- */
            /* Shows the current media queries                      */
            /* ---------------------------------------------------- */
            function update_responsive_breakpoints(){
                
                // Bar Element
                var bar = $(".breakpoint-bar");

                bar.find("div").tooltip('destroy');

                // Empty
                bar.empty();

                // Show breakpoint information.
                if($(".media-control").attr("data-code") == 'max-width'){
                    bar.append("<span class='breakpoint-right-notice'>defined breakpoints</span>"); 
                }else{
                    bar.append("<span class='breakpoint-left-notice'>defined breakpoints</span>"); 
                }

                // Getting all media queries as array
                var queries = get_media_queries(null,true);

                // number Value
                var val = 0;

                var num = 0;

                var query;

                // Window Width
                var winWidth = $(window).width();

                // Has queries?
                if(queries.length > 0){

                    // Each all Queries
                    $.each(queries, function(index, value) {

                        // Simple query.
                        query = process_media_query(value);

                        // isset?
                        if(isDefined(query)){

                            // String
                            query = query.toString();

                            // clean < and > symbols.
                            val = parseInt(number_filter(query.replace(/\</g,"").replace(/\>/g,"")));

                            // Real number value. non proccesed.
                            // proccessed convert rem, em to PX
                            // we using NUM for find the media in data
                            num = space_cleaner(value.match(/\:(.*?)\)/g).toString().replace(/\:/g,"").replace(/\)/g,""));

                            // be sure it a simple media query.
                            if(query.indexOf(",") == -1 && query.indexOf("and") == -1){

                                // Just Min Width
                                if(query.indexOf(">") != -1 && $(".media-control").attr("data-code") == 'min-width'){

                                    // If not has
                                    if($(document).find("#min-breakpoint-"+val+"").length == 0){

                                        // Append
                                        bar.append("<div data-breakpoint='"+val+"' data-media-content='"+value+"' data-breakpoint-data='(min-width:"+num+")' class='min-width' id='min-breakpoint-"+val+"' style='left:"+(46+parseInt(val))+"px;width:"+(winWidth-(46+parseInt(val)))+"px;'>"+val+"</div>");

                                    }


                                }

                                // Just Max Width
                                if(query.indexOf("<") != -1 && $(".media-control").attr("data-code") == 'max-width'){

                                    // If not has
                                    if($(document).find("#max-breakpoint-"+val+"").length == 0){

                                        // Append
                                        bar.append("<div data-breakpoint='"+val+"' data-media-content='"+value+"' data-breakpoint-data='(max-width:"+num+")' class='max-width' id='max-breakpoint-"+val+"' style='width:"+val+"px;'>"+val+"</div>");

                                    }

                                }

                            }

                        }

                    });
    

                    // Sorting breakpoints by value
                    bar.find('div').sort(function(a, b) {
                        return +a.dataset.breakpoint - +b.dataset.breakpoint;
                    }).appendTo(bar);


                    // Updating Max Width Bar
                    var prevWidth = 0;
                    bar.find("div").each(function(i,v){

                        // Object
                        var element = $(this);
                        var nextElement = element.next(".max-width");

                        // Next Width
                        var nextWidth = parseFloat(nextElement.css("width"));

                        // Fix Max width format
                        if(element.hasClass("max-width")){
                            prevWidth = parseFloat(element.css("width")) + prevWidth;
                        }

                        // Updating Positions
                        nextElement.css("width",nextWidth-prevWidth);
                        nextElement.css("left",46+prevWidth);
                        element.css("z-index",i);

                    });


                    // Updating Min Width Bar
                    bar.find(".min-width").each(function(i,v){

                        // Object
                        var element = $(this);
                        var nextElement = element.nextAll(".min-width");

                        // If has next
                        if(nextElement.length > 0){

                            // Getting Breakpoint Values
                            var elementPoint = parseInt(element.attr("data-breakpoint"));
                            var nextPoint = parseInt(nextElement.attr("data-breakpoint"));

                            // Find Dif
                            var maxUntil = nextPoint-elementPoint;

                            // Updating width
                            element.css("width",maxUntil+"px");

                        }

                    });


                    // Fix small media queries
                    bar.find("div").each(function(){

                        var element = $(this);

                        if(parseInt(element.css("width")) <= 100){
                            element.css("background-image","none").css("text-align","center").css("padding","0px").css("min-width","13px");
                        }

                        if(parseInt(element.css("width")) <= 40){
                            element.css("font-size","10px");
                        }

                    });


                    // Adding Toolip to breakponts
                    $(".breakpoint-bar div").tooltip({

                        // Set dynamic title
                        title: function(){
                            var text = $(".media-control").text();
                            var breakpoint = $(this).attr("data-breakpoint");
                            return "<span class='smaller-text-tooltip'>" + breakpoint + "px and "+text+" screens</span>";
                        },

                        // Tooltip settings
                        animation: false,
                        delay: { show: 10, hide: 0 },
                        placement: 'bottom',
                        trigger: 'hover',
                        container: "body",
                        html: true

                    });


                }

            }


            /* ---------------------------------------------------- */
            /* Breakpoint bar click                                 */
            /* ---------------------------------------------------- */
            $(document).on("click",".breakpoint-bar div",function(e){

                $('.responsive-right-handle').tooltip("hide");

                // Getting breakpoint value
                var n = $(this).attr("data-breakpoint");

                // Go
                $("#iframe").width(n);

                // Update
                draw_responsive_handle();
                update_responsive_size_notice();
                draw();

            });


            /* ---------------------------------------------------- */
            /* Updating Responsive Size Notice                      */
            /* ---------------------------------------------------- */
            function update_responsive_size_notice() {

                if (is_responsive_mod() === false) {
                    return false;
                }

                var s = $("#iframe").width();
                var device = '';

                // Set device size.
                $(".device-size").text(s);

                if ($(".media-control").attr("data-code") == 'max-width') {

                    device = '(phones)';

                    if (s >= 375) {
                        device = '(Large phones)';
                    }

                    if (s >= 414) {
                        device = '(tablets & landscape phones)';
                    }

                    if (s >= 736) {
                        device = '(tablets)';
                    }

                    if (s >= 768) {
                        device = '(small desktops & tablets and phones)';
                    }

                    if (s >= 992) {
                        device = '(medium desktops & tablets and phones)';
                    }

                    if (s >= 1200) {
                        device = '(large desktops & tablets and phones)';
                    }

                } else {

                    device = '(phones & tablets and desktops)';

                    if (s >= 375) {
                        device = '(phones & tablets and desktops)';
                    }

                    if (s >= 414) {
                        device = '(large phones & tablets and desktops)';
                    }

                    // Not mobile.
                    if (s >= 736) {
                        device = '(landscape phones & tablets and desktops)';
                    }

                    // Not tablet
                    if (s >= 768) {
                        device = '(desktops)';
                    }

                    // Not small desktop
                    if (s >= 992) {
                        device = '(medium & large desktops)';
                    }

                    // Not medium desktop
                    if (s >= 1200) {
                        device = '(large desktops)';
                    }

                }

                // Set device name
                $(".device-name").text(device);

            }

            /* ---------------------------------------------------- */
            /* Smarrt Insert Default Values                         */
            /* ---------------------------------------------------- */
            function insert_default_options() {

                if (is_content_selected() === false) {
                    return false;
                }

                // current options
                var options = $(".yp-editor-list > li.active:not(.yp-li-about) .yp-option-group");

                // delete all cached data.
                $("li[data-loaded]").removeAttr("data-loaded");

                // UpData current active values.
                if (options.length > 0) {
                    options.each(function() {

                        if ($(this).attr("id") != "background-parallax-group" && $(this).attr("id") != "background-parallax-speed-group" && $(this).attr("id") != "background-parallax-x-group" && $(this).attr("id") != "background-position-group") {

                            var check = 1;

                            if ($(this).attr("id") == 'animation-duration-group' && is_animate_creator() === true) {
                                check = 0;
                            }

                            if (check == 1) {
                                set_default_value(get_option_id(this));
                            }

                        }
                    });
                }

                // cache to loaded data.
                options.parent().attr("data-loaded", "true");

            }

            /* ---------------------------------------------------- */
            /* Setup AutoComplete                                   */
            /* ---------------------------------------------------- */
            $(".input-autocomplete").each(function() {

                // Get data by select
                var data = [];
                $(this).parent().find("select option").each(function() {
                    data.push($(this).text());
                });

                var id = $(this).parent().parent().attr("data-css");

                // Autocomplete script
                $(this).autocomplete({
                    source: data,
                    delay: 0,
                    minLength: 0,
                    autoFocus: true,
                    close: function(event, ui) {

                        $(".active-autocomplete-item").removeClass("active-autocomplete-item");
                        $(this).removeClass("active");

                        setTimeout(function(){
                            mainBody.removeClass("autocomplete-active");
                        },300);

                        if ($(this).parent().find('select option:contains(' + $(this).val() + ')').length) {
                            $(this).val($(this).parent().find('select option:contains(' + $(this).val() + ')').val());
                        }

                    },
                    open: function(event, ui) {

                        window.openVal = $(this).val();

                        $(this).addClass("active");
                        mainBody.addClass("autocomplete-active");

                        var current = $(this).val();

                        var fontGoogle = null;

                        // Show more fonts as well.
                        if(id == 'font-family' || id == 'animation-name'){

                            // Find free space on screen
                            var freeSpace = ($(window).height() - $("#yp-"+id).offset().top) - 200;

                            // already min 300
                            if(freeSpace > 300){

                                // Getting each other li
                                var li = $("#"+id+"-group .ui-autocomplete.ui-menu li").outerHeight();

                                // update free space
                                freeSpace = parseInt(freeSpace/li) * li;

                                // Remove old
                                $("#autocomplete-font-style-"+id).remove();

                                // Add new
                                mainBody.append('<style id="autocomplete-font-style-'+id+'">#'+id+'-group .ui-autocomplete.ui-menu{max-height:'+freeSpace+'px;}</style>');

                            }

                        }

                        // Getting first font family and set active if yp has this font family.
                        if (id == 'font-family') {
                            if (current.indexOf(",") != -1) {
                                var currentFont = $.trim(current.split(",")[0]);
                                currentFont = currentFont.replace(/'/g, "").replace(/"/g, "").replace(/ /g, "").toLowerCase();

                                if ($('#yp-' + id + '-data option[data-text="' + currentFont + '"]').length > 0) {
                                    fontGoogle = $('#yp-' + id + '-data option[data-text="' + currentFont + '"]').text();
                                }

                            }
                        }

                        if (fontGoogle === null){
                            if ($('#yp-' + id + '-data option[value="' + current + '"]').length > 0) {
                                current = $('#yp-' + id + '-data option[value="' + current + '"]').text();
                            }
                        } else {
                            current = fontGoogle;
                        }

                        if ($(this).parent().find(".autocomplete-div").find('li').filter(function() {
                                return $.text([this]) === current;
                            }).length == 1) {

                            $(".active-autocomplete-item").removeClass("active-autocomplete-item");
                            if ($(".active-autocomplete-item").length === 0) {

                                $(this).parent().find(".autocomplete-div").find('li').filter(function() {
                                    return $.text([this]) === current;
                                }).addClass("active-autocomplete-item");

                            }

                        }

                        // Scroll
                        if ($(".active-autocomplete-item").length > 0) {
                            $(this).parent().find(".autocomplete-div").find('li.ui-state-focus').removeClass("ui-state-focus");
                            var parentDiv = $(this).parent().find(".autocomplete-div li.active-autocomplete-item").parent();
                            var activeEl = $(this).parent().find(".autocomplete-div li.active-autocomplete-item");

                            parentDiv.scrollTop(parentDiv.scrollTop() + activeEl.position().top);
                        }

                        // Update font-weight family
                        $("#yp-autocomplete-place-font-weight ul li").css("fontFamily", $("#yp-font-family").val());

                        // Font Weight
                        if (id == 'font-weight') {

                            $(".autocomplete-div li").each(function() {
                                
                                // Light 300 > 300
                                var v = Math.abs(number_filter($(this).text()));
                                $(this).css("fontWeight", v);

                            });

                        }

                        // Load fonts
                        if(id == 'font-weight' || id == 'font-family'){
                            load_near_fonts($(this).parent().find(".autocomplete-div"));
                        }

                        // Text shadow
                        if (id == 'text-shadow') {

                            $(".autocomplete-div li").each(function() {

                                if ($(this).text() == 'Basic Shadow') {
                                    $(this).css("textShadow", 'rgba(0, 0, 0, 0.3) 0px 1px 1px');
                                }

                                if ($(this).text() == 'Shadow Multiple') {
                                    $(this).css("textShadow", 'rgb(255, 255, 255) 1px 1px 0px, rgb(170, 170, 170) 2px 2px 0px');
                                }

                                if ($(this).text() == 'Anaglyph') {
                                    $(this).css("textShadow", 'rgb(255, 0, 0) -1px 0px 0px, rgb(0, 255, 255) 1px 0px 0px');
                                }

                                if ($(this).text() == 'Emboss') {
                                    $(this).css("textShadow", 'rgb(255, 255, 255) 0px 1px 1px, rgb(0, 0, 0) 0px -1px 1px');
                                }

                                if ($(this).text() == 'Neon') {
                                    $(this).css("textShadow", 'rgb(255, 255, 255) 0px 0px 2px, rgb(255, 255, 255) 0px 0px 4px, rgb(255, 255, 255) 0px 0px 6px, rgb(255, 119, 255) 0px 0px 8px, rgb(255, 0, 255) 0px 0px 12px, rgb(255, 0, 255) 0px 0px 16px, rgb(255, 0, 255) 0px 0px 20px, rgb(255, 0, 255) 0px 0px 24px');
                                }

                                if ($(this).text() == 'Outline') {
                                    $(this).css("textShadow", 'rgb(0, 0, 0) 0px 1px 1px, rgb(0, 0, 0) 0px -1px 1px, rgb(0, 0, 0) 1px 0px 1px, rgb(0, 0, 0) -1px 0px 1px');
                                }

                            });

                        }

                    },

                    appendTo: "#yp-autocomplete-place-" + $(this).parent().parent().attr("id").replace("-group", "").toString()
                }).click(function() {
                    $(this).autocomplete("search", "");
                });

            });


            /* ---------------------------------------------------- */
            /* Responsive Mode                                      */
            /* ---------------------------------------------------- */
            $(".yp-responsive-btn").click(function() {

                if (mainBody.hasClass("yp-css-editor-active")) {
                    $(".yp-css-close-btn").trigger("click");
                }

            });


            /* ---------------------------------------------------- */
            /* Responsive Mode                                      */
            /* ---------------------------------------------------- */
            $(".yp-responsive-btn").click(function() {

                if ($(this).hasClass("active")) {
                    body.removeClass("yp-responsive-device-mode");
                    $(this).addClass("active");
                    var styleAttr = $("#iframe").attr("style");
                    $("#iframe").removeAttr("style");
                    $("#iframe").attr("data-style",styleAttr);
                } else {
                    body.addClass("yp-responsive-device-mode");
                    $(this).removeClass("active");

                    styleAttr = $("#iframe").attr("data-style");
                    $("#iframe").removeAttr("data-style");
                    $("#iframe").attr("style",styleAttr);

                }

                insert_default_options();
                update_responsive_size_notice();

                // draw_box must process first. Fix "margin responsive update" problem
                draw_box(".yp-selected", 'yp-selected-boxed');
                
                // draw all again now
                draw();

                if(body.hasClass("yp-animate-manager-active")){
                    animation_manager();
                }

                update_responsive_breakpoints();

            });


            /* ---------------------------------------------------- */
            /* Reset Button                                         */
            /* ---------------------------------------------------- */
            $(".yp-button-reset").click(function() {

                if (is_animate_creator()) {
                    if (!confirm(l18_closeAnim)) {
                        return false;
                    } else {
                        yp_anim_cancel();
                    }
                }

                var p = $(".yp-ul-all-pages-list").find(".active").text();
                var t = $(".yp-ul-single-list").find(".active").text();

                if ($(".yp-ul-all-pages-list").find(".active").length > 0) {
                    l18_reset = "You are sure to reset changes on <strong>'"+p+"'</strong> page?";
                } else if ($(".yp-ul-single-list").find(".active").length > 0) {
                    l18_reset = "You are sure to reset changes on <strong>'"+t+"'</strong> template?";
                } else {
                    l18_reset = "You are sure to reset all <strong>global changes</strong>?";
                }

                var link = $(".yp-source-page-link").parent("a").attr("href");

                var l18_reset_text = "May be you need to more control? You can manage all style sources from <a href='"+link+"' target='_blank'>this page</a>.";                

                swal({
                  title: l18_reset,
                  text: l18_reset_text,
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Yes, Reset!",
                  closeOnConfirm: true,
                  animation: false,
                  customClass: 'yp-reset-popup',
                  html: true
                },function(){

                    iframe.find(".yp_current_styles").remove();

                    // Clean Editor Value.
                    editor.setValue('');

                    // delete undo history.
                    editor.getSession().setUndoManager(new ace.UndoManager());

                    // Clean CSS Data
                    iframe.find("#yp-css-data-full").empty();

                    // Reset Parallax.
                    iframe.find(".yp-parallax-disabled").removeClass("yp-parallax-disabled");

                    // Update Changes.
                    if (is_content_selected()) {

                        insert_default_options();

                        setTimeout(function(){
                            draw();
                        },50);

                    }

                    // Option Changed
                    option_change();

                    // Update draggable after reset
                    var el = iframeBody.find(".yp-selected");
                    if(el.length > 0){

                        if(el.css("position") == 'static'){
                            el.css("position","relative");
                            iframeBody.find(".yp-selected-others").css("position","relative");
                        }

                    }

                });

            });


            /* ---------------------------------------------------- */
            /* Install all option types                             */
            /* ---------------------------------------------------- */
            $(".yp-slider-option").each(function() {
                slider_option(get_option_id(this), $(this).data("decimals"), $(this).data("pxv"), $(this).data("pcv"), $(this).data("emv"));
            });

            $(".yp-radio-option").each(function() {
                radio_option(get_option_id(this));
            });

            $(".yp-color-option").each(function() {
                color_option(get_option_id(this));
            });

            $(".yp-input-option").each(function() {
                input_option(get_option_id(this));
            });

            
            /* ---------------------------------------------------- */
            /* Updating slider by input                             */
            /* ---------------------------------------------------- */
            function update_slide_by_input(element,value,prefix) {

                var elementParent = element.parent().parent().parent();
                var range;

                if(value === false){
                    value = element.parent().find(".yp-after-css-val").val();
                    prefix = element.parent().find(".yp-after-prefix").val();
                }

                var slide = element.parent().parent().find(".wqNoUi-target");

                // Update PX
                if (prefix == 'px') {
                    range = elementParent.data("px-range").split(",");
                }

                // Update %.
                if (prefix == '%') {
                    range = elementParent.data("pc-range").split(",");
                }

                // Update EM.
                if (prefix == 'em') {
                    range = elementParent.data("em-range").split(",");
                }

                // Update S.
                if (prefix == 's' || prefix == '.s') {
                    range = elementParent.data("em-range").split(",");
                }

                // min and max values
                if (range === undefined || range === false) {
                    return false;
                }

                var min = parseInt(range[0]);
                var max = parseInt(range[1]);

                if (value < min) {
                    min = value;
                }

                if (value > max) {
                    max = value;
                }

                if (isNaN(min) === false && isNaN(max) === false && isNaN(value) === false){

                    slide.wqNoUiSlider({
                        range: {
                            'min': parseInt(min),
                            'max': parseInt(max)
                        },

                        start: value
                    }, true);

                }

            }

            /* ---------------------------------------------------- */
            /* Process CSS before open CSS editor                   */
            /* ---------------------------------------------------- */
            $("body:not(.yp-css-editor-active) .css-editor-btn").hover(function() {
                if (!mainBody.hasClass("yp-css-editor-active")) {
                    process(false, false, false);
                }
            });


            /* ---------------------------------------------------- */
            /* Right Click disable                                  */
            /* ---------------------------------------------------- */
            mainDocument.contextmenu(function() {
                return false;
            });


            // auto insert.
            window.disable_auto_insert = false;


            /* ---------------------------------------------------- */
            /* Hide CSS Editor                                      */
            /* ---------------------------------------------------- */
            $(".css-editor-btn,.yp-css-close-btn").click(function() {

                if(body.hasClass("yp-animate-manager-active")){
                    $(".animation-manager-btn.active").trigger("click");
                }

                // delete fullscreen editor
                if (body.hasClass("yp-fullscreen-editor")) {
                    body.removeClass("yp-fullscreen-editor");
                }

                if ($("#leftAreaEditor").css("display") == 'none') {

                    // No selected
                    if (!is_content_selected()) {
                        editor.setValue(get_clean_css(true));
                        editor.focus();
                        editor.execCommand("gotolineend");
                    } else if(window.disable_auto_insert == false){
                        insert_rule(null, 'a', 'a', '');
                        var cssData = get_clean_css(false);
                        var goToLine = cssData.split("a:a")[0].split(/\r\n|\r|\n/).length;
                        cssData = cssData.replace(/a:a !important;/g, "");
                        cssData = cssData.replace(/a:a;/g, "");
                        editor.setValue(cssData);
                        editor.resize(true);
                        setTimeout(function(){
                            editor.scrollToLine(goToLine, true, false);
                        },2);
                        editor.focus();
                        if (is_responsive_mod()) {
                            editor.gotoLine(goToLine, 2, true);
                        } else {
                            editor.gotoLine(goToLine, 1, true);
                        }
                    }

                    $("#cssData,#cssEditorBar,#leftAreaEditor").show();
                    mainBody.addClass("yp-css-editor-active");
                    
                    var ebtn = $(".css-editor-btn");
                    var title = ebtn.attr("data-original-title"); // Save
                    ebtn.attr("data-title",title); // save as data
                    ebtn.attr("data-original-title",""); // remove title

                    iframeBody.trigger("scroll");

                } else {

                    // CSS To data
                    process(true, false, false);

                    // Update breakpoint after CSS changes
                    update_responsive_breakpoints();

                }

                // Update All.
                draw();

            });


            /* ---------------------------------------------------- */
            /* Blur Custom Slider Value                             */
            /* ---------------------------------------------------- */
            $(".yp-after-css-val,.yp-after-prefix").on("keydown keyup",function(e) {

                if(!e.originalEvent){
                    return false;
                }

                var id = $(this).parents(".yp-option-group").attr("data-css");
                var thisContent = $("#" + id + "-group").parent(".yp-this-content");
                var lock = thisContent.find(".lock-btn.active").length;
                var lockedIdArray = [];

                if(lock){
                    thisContent.find(".yp-option-group").each(function(){
                        if($(this).attr("data-css") != id){
                            lockedIdArray.push($(this).attr("data-css"));
                        }
                    });
                }

                var value = $(this).parent().find(".yp-after-css-val").val();
                var prefix = $(this).parent().find(".yp-after-prefix").val();

                // Self
                update_slide_by_input($(this),false);
                slide_action($("#yp-" + id), id, true, false);

                // others
                if(lock){

                    for(var y = 0;y < lockedIdArray.length; y++){
                        $("#" + lockedIdArray[y]+"-value").val(value);
                        $("#" + lockedIdArray[y]+"-after").val(prefix);
                        update_slide_by_input($("#" + lockedIdArray[y]+"-value"),value,prefix);
                        slide_action($("#yp-" + lockedIdArray[y]), lockedIdArray[y], true, false);
                    }

                    option_change();

                }

            });


            /* ---------------------------------------------------- */
            /* Update Gui on load                                   */
            /* ---------------------------------------------------- */
            gui_update();


            /* ---------------------------------------------------- */
            /* Basic Sharp Selector For Editor                      */
            /* ---------------------------------------------------- */
            function get_live_selector(element){

                if(element === undefined){
                    element = get_selected_element();
                }

                // Be sure this item is valid.
                if (element[0] === undefined || element[0] === false || element[0] === null) {
                    return false;
                }

                // Tag info
                var tag = element[0].tagName.toLowerCase();

                // Getting item parents.
                var parents = element.parentsUntil("body"), selector = 'body', currentSelector;

                // Get last selector
                var lastSelector = get_best_class(element);

                // Foreach all loops.
                for (var i = parents.length - 1; i >= 0; i--) {

                    currentSelector = get_best_class(parents[i]);

                    if(/\.|#/g.test(currentSelector)){
                        currentSelector = parents[i].tagName.toLowerCase()+currentSelector;
                    }

                    selector = space_cleaner(selector).trim() + " > " + currentSelector + window.separator;

                } // Each end.

                selector = space_cleaner(selector + " > " + lastSelector + ".yp-selected");

                return selector;

            }


            /* ---------------------------------------------------- */
            /* Single Selector                                      */
            /* ---------------------------------------------------- */
            function single_selector(selector,test) {

                var customClass = 'yp-selected';
                if(mainBody.hasClass("yp-control-key-down") && is_content_selected()){
                    customClass = 'yp-multiple-selected';
                }

                var selectorArray = get_selector_array(selector);
                var i = 0;
                var indexOf = 0;
                var selectorPlus = '';

                for (i = 0; i < selectorArray.length; i++) {

                    if (i > 0) {
                        selectorPlus += window.separator + selectorArray[i];
                    } else {
                        selectorPlus += selectorArray[i];
                    }

                    if (iframe.find(selectorPlus).length > 1) {

                        iframe.find(selectorPlus).each(function(){

                            if (selectorPlus.substr(selectorPlus.length - 1) != ')') {

                                if ($(this).parent().length > 0) {

                                    indexOf = 0;

                                    $(this).parent().children().each(function() {

                                        indexOf++;

                                        if ($(this).find("."+customClass).length > 0 || $(this).hasClass((customClass))) {

                                            selectorPlus = selectorPlus + ":nth-child(" + indexOf + ")";

                                        }

                                    });

                                }

                            }

                        });

                    }

                }


                // Clean no-need nth-childs.
                if(selectorPlus.indexOf(":nth-child") != -1){

                    // Selector Array
                    selectorArray = get_selector_array(selectorPlus);

                    // Each all selector parts
                    for(i = 0; i < selectorArray.length; i++){

                        // Get previous parts of selector
                        var prevAll = get_previous_item(selectorArray,i).join(" ");

                        // Gext next parts of selector
                        var nextAll = get_next_item(selectorArray,i).join(" ");

                        // check the new selector
                        var selectorPlusNew = prevAll + window.separator + selectorArray[i].replace(/:nth-child\((.*?)\)/i,'') + window.separator + nextAll;

                        // clean
                        selectorPlusNew = space_cleaner(selectorPlusNew);

                        // Check the selector without nth-child and be sure have only 1 element.
                        if(iframe.find(selectorPlusNew).length == 1){
                            selectorArray[i] = selectorArray[i].replace(/:nth-child\((.*?)\)/i,'');
                        }

                    }

                    // Array to spin, and clean selector.
                    selectorPlus = space_cleaner(selectorArray.join(" "));

                }


                // Return the selector without use "add_children_selector" func.
                // Test parement used in get_parents func
                if(test){
                    return selectorPlus;
                }


                // Ready.
                return multiple_variation(add_children_support(selectorPlus));

            }


            /* ---------------------------------------------------- */
            /* Adds > symbol to single_selector func                */
            /* ---------------------------------------------------- */
            function add_children_support(selector){

                // Add " > " to selector. Stable V.
                var selectorArray = get_selector_array(selector);
                var newSelector = '', inSelected, thisSelector, testSelector;
                for(var i = 0; i < selectorArray.length; i++){

                    // Don't use nth-child while spin it
                    thisSelector = selectorArray[i].replace(/:nth-child\((.*?)\)/i,'');

                    // To check select in select
                    testSelector = space_cleaner($.trim(newSelector+window.separator+thisSelector+window.separator+thisSelector));

                    // Check if same selector has in the selector
                    inSelected = iframe.find(testSelector).length;

                    if (inSelected > 0){
                        newSelector = space_cleaner(newSelector) + " > " + selectorArray[i] + window.separator; // Add With '>' separator
                    }else{ 
                        newSelector += selectorArray[i] + window.separator; // Add with space separator
                    }

                }


                // Need trim to continue.
                newSelector = $.trim(newSelector);


                // Add > symbol to last if selector still finding more element than one.
                if(iframe.find(newSelector).length > 1){
                    newSelector = newSelector.replace(/(?=[^ ]*$)/i,' > ');
                }

                // Cleans ">" symbols from selector if not need.
                if(newSelector.indexOf(">") != -1){

                    var length = newSelector.split(">").length;
                    var elementLength = iframe.find(newSelector).length;

                    for(var i = 1; i < length; i++){

                        if(iframe.find(newSelector.replace(/ > /i,' ')).length == elementLength){
                            newSelector = newSelector.replace(/ > /i,' ');
                        }

                    }

                }

                // Return it
                return space_cleaner(newSelector);

            }


            /* ---------------------------------------------------- */
            /* Previous array item                                  */
            /* ---------------------------------------------------- */
            function get_previous_item(arr,current){

                var result = [];

                for(var i = 0; i < arr.length; i++){

                    if(i < current){

                        result.push(arr[i]);

                    }

                }

                return result;

            }


            /* ---------------------------------------------------- */
            /* Next array item                                      */
            /* ---------------------------------------------------- */
            function get_next_item(arr,current){

                var result = [];

                for(var i = 0; i < arr.length; i++){

                    if(i > current){

                        result.push(arr[i]);

                    }

                }

                return result;

            }


            /* ---------------------------------------------------- */
            /* Shows Parent Tree                                    */
            /* ---------------------------------------------------- */
            function show_parent_tree(){

                // Selected
                var element = get_selected_element();

                // Get Parents
                var parents = element.parentsUntil("html");
                    
                // Convert to Array
                parents = parents.toArray().reverse();
                parents.push(element[0]);

                // Varriables
                var padding = 14; // px
                var width = 24; // vw
                var height = 140; // px

                // Settings
                var paddingBreak = 30 + padding;
                var marginTop = parseInt(height/2);

                var delay = 800;
                var showShape = 30;

                // OutPut HTML
                var parentTreeHTML = '<div class="yp-parent-tree"><div class="yp-parent-tree-wrapper">';

                // Vars
                var shapeClass = '', current, selector, title, tag, i;

                // Remove "yp-selected" class to work get_parents func without the problem
                iframe.find(".yp-selected").removeClass("yp-selected");

                // loop Parents
                for(i = 0; i < parents.length; i++){

                    // Current Parent
                    current = $(parents[i]);

                    // Add last & first classes
                    if((i + 1) == parents.length){
                        shapeClass = ' yp-shape-last yp-hover-shape';
                    }else if(i == 0){
                        shapeClass = ' yp-shape-first';
                    }else{
                        shapeClass = '';
                    }

                    // Delete previous yp-selected classes
                    iframe.find(".yp-selected").removeClass("yp-selected");

                    // Select current parent
                    current.addClass("yp-selected");

                    // Getting Selector
                    selector = get_parents(current, 'defaultNoCache');

                    // Selector Heading
                    title = get_tag_information(selector);

                    // Element tag
                    tag = current[0].nodeName;

                    // Adds to parent
                    parentTreeHTML += '<div class="yp-tree-shape'+shapeClass+'" data-selector="'+selector+'"><span class="tree-tag">&lt;'+ tag + "&gt;</span> <span class='tree-title'>" + title + '</span>';

                }

                // Closing the shape divs
                for(var i = 0; i < parents.length; i++){
                    parentTreeHTML += '</div>';
                }

                // End HTML var
                parentTreeHTML += '</div></div>';

                // remove all yp selected classes
                iframe.find(".yp-selected").removeClass("yp-selected");

                // Add selected to first selected element
                element.addClass("yp-selected");

                // Apped dom
                mainBody.append(parentTreeHTML);

                // The shape list
                var list = $(".yp-shape-last").parentsUntil(".yp-parent-tree-wrapper");

                // Limit shapes
                list.each(function(i){
                    if(i == 10){
                        $(".yp-parent-tree-wrapper").html($(this)[0].outerHTML);
                    }
                });

                // Update list
                list = $(".yp-shape-last").parentsUntil(".yp-parent-tree-wrapper");

                // Add first class after limit.
                $(".yp-parent-tree-wrapper > .yp-tree-shape").addClass("yp-shape-first");

                // Getting screen sizes
                var screenWidth = $(window).width();
                var screenHeight = $(window).height();

                // Covert the 30% value to px  
                width = parseInt((screenWidth*width)/100);

                // Top & Left position for the last shape
                var topCalc = parseInt(((list.length) * (paddingBreak/2)) + (screenHeight/2));
                var leftCalc = parseInt(((list.length) * (paddingBreak/2)) + (screenWidth/2) - (width/2));

                // Animation start from the current selected element position
                var startTop = $("#iframe").offset().top + (element.offset().top - iframe.scrollTop());
                var startLeft = $("#iframe").offset().left + (element.offset().left - iframe.scrollLeft());

                // CSS3 Animation
                var animation = '<style class="yp-parent-tree-script">@keyframes treeShape {from {width: '+element.width()+'px;height:'+element.height()+'px;top:'+startTop+'px;left:'+startLeft+'px;margin-top:0px;margin-left:0px;}to {margin-top:-'+marginTop+'px;width:'+width+'px;height: '+(height-1)+'px;top:'+topCalc+'px;left:'+leftCalc+'px;}}.yp-shape-last{animation-name:treeShape;animation-duration:'+delay+'ms;animation-fill-mode:both;}.yp-tree-shape{padding:'+padding+'px;}</style>';

                // Append Animation and HTML Code
                mainBody.append(animation);

                // Add Active to begin to show the fade in slowly.
                setTimeout(function(){
                    $(".yp-parent-tree").addClass("yp-tree-parent-active");
                }, showShape);

                // Shows the parent shapes one by one
                setTimeout(function(){

                    // Variables
                    var top = topCalc - marginTop;
                    var left = leftCalc;
                    var widthFirstShape = parseInt($(".yp-shape-last").outerWidth());
                    var heightFirstShape = parseInt($(".yp-shape-last").outerHeight());
                    var paddingLoop = 0;

                    // Re Position the shapes in the screen
                    list.each(function(){

                        var that = $(this);

                        // last shape already ready.
                        if(that.hasClass("yp-shape-last") == false){

                            // Vars
                            top = top - paddingBreak;
                            left = left - paddingBreak;
                            paddingLoop = paddingLoop + paddingBreak;

                            // Defaults
                            that.css("top", top+"px");
                            that.css("left", left+"px");
                            that.css("width", (widthFirstShape+paddingLoop)+"px");
                            that.css("height", (heightFirstShape+paddingLoop)+"px");
                            that.css("marginTop", "-20px");

                            that.find("span").css("opacity","1");

                        }

                    });

                    // Start the parent animation, show one by one.
                    list.each(function(i){

                        var that = $(this);

                        // The last shape already ready.
                        if(that.hasClass("yp-shape-last") == false){

                            // Show the parents one by one with down and fadein effect.
                            setTimeout(function() {

                                that.css("marginTop", "0px");
                                that.css("background-color", "rgba(115, 145, 197, 0.2)");
                                that.css("border", "2px solid rgba(115, 145, 197, 0.2)");
                                that.css("color", "#FFF");

                            }, i * showShape);

                        }

                    });

                    // Ready class disabling pointer events none property.
                    setTimeout(function(){
                        $(".yp-parent-tree").addClass("ready");
                    }, (list.toArray().length * showShape));

                }, delay + 100); // +100 for timeOut problems

            }


            /* ---------------------------------------------------- */
            /* Parent Tree Seleciton                                */
            /* ---------------------------------------------------- */
            $(document).on("mousemove", ".yp-tree-shape", function(e){

                if($(e.target).hasAttr("data-selector")){

                    $(".yp-hover-shape").removeClass("yp-hover-shape");

                    $(e.target).addClass("yp-hover-shape");

                }

            });


            /* ---------------------------------------------------- */
            /* Parent Tree Seleciton: Click                         */
            /* ---------------------------------------------------- */
            $(document).on("click", ".yp-tree-shape", function(e){

                // If it is a tree shape
                if($(e.target).hasAttr("data-selector") && $(e.target).hasClass("yp-hover-shape")){

                    // Get the parent selector
                    var selector = $(e.target).attr("data-selector");

                    // Calcature the index
                    var parentIndex = $(".yp-shape-last").parentsUntil(e.target).toArray().length;

                    // Cache selected
                    var selected = get_selected_element();

                    // Leave the current element
                    clean();            

                    // Selecting the parent
                    setTimeout(function(){

                        // No need if it body.
                        if(selector != 'body'){

                            // Finds the parent element
                            for(var i = 0; i < parentIndex; i++){
                                selected = selected.parent();
                            }

                            // yp will selected class help to find the target element
                            selected.addClass("yp-will-selected");

                        }

                        // Set the new selector manually.
                        set_selector(selector,null);

                    }, 10);

                    // Closing the parent tree
                    setTimeout(function(){
                        close_parent_tree();
                    }, 50);

                }

            });


            /* ---------------------------------------------------- */
            /* Parent Tree Back: Click                              */
            /* ---------------------------------------------------- */
            $(document).on("click", ".yp-parent-tree", function(e){

                if($(e.target).hasClass("yp-parent-tree") || $(e.target).hasClass("yp-parent-tree-wrapper")){
                    close_parent_tree();
                }

            });


            /* ---------------------------------------------------- */
            /* Parent Tree Idle Selection                           */
            /* ---------------------------------------------------- */
            $(document).on("mousemove", ".yp-parent-tree", function(e){

                if($(e.target).hasClass("yp-parent-tree") || $(e.target).hasClass("yp-parent-tree-wrapper")){
                    $(".yp-hover-shape").removeClass("yp-hover-shape");
                    $(".yp-shape-last").addClass("yp-hover-shape");
                }

            });


            /* ---------------------------------------------------- */
            /* Parent Tree Seleciton: Click                         */
            /* ---------------------------------------------------- */
            function close_parent_tree(){

                $(".yp-parent-tree").addClass("exit");

                setTimeout(function(){
                    $(".yp-parent-tree,.yp-parent-tree-script").remove();
                }, 400);

            }
            


            /* ---------------------------------------------------- */
            /* Breakpoint Bar ContextMenu                           */
            /* ---------------------------------------------------- */
            $.contextMenu({

                events: {

                    show: function() {

                        setTimeout(function(){
                            $(".dom_contextmenu").css("top","15px");
                        },1);

                        var breakpointMedia = $(this).attr("data-breakpoint-data");

                        setTimeout(function(){

                            // has available changes for this breakpoint?
                            if(the_editor_data().find('[data-size-mode="'+breakpointMedia+'"]').length == 0){
                                $(".reset-breakpoint-menu").addClass("disabled");
                                $(".show-css-menu").addClass("disabled");
                            }else{
                                $(".reset-breakpoint-menu").removeClass("disabled");
                                $(".show-css-menu").removeClass("disabled");
                            }

                        },10);

                    }

                },

                selector: '.breakpoint-bar div', 
                className: 'dom_contextmenu',
                callback: function(key, options) {
                    
                    // Reset functions
                    if(key == 'reset'){

                        // Getting data
                        var breakpointMedia = $(this).attr("data-breakpoint-data");

                        // Warning title
                        var l18_reset_media = "Do you want to reset the <strong class='bold-light'>"+breakpointMedia+"</strong> media query?";

                        // Alert
                        swal({
                          title: l18_reset_media,
                          text: "This will only reset the changes made by you, it doesn't touch the theme style files.",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonColor: "#DD6B55",
                          confirmButtonText: "Reset!",
                          closeOnConfirm: true,
                          animation: false,
                          customClass: 'yp-reset-popup',
                          html: true
                        },function(){

                            // Find all changes in breakpoint size and remove
                            the_editor_data().find('[data-size-mode="'+breakpointMedia+'"]').remove();

                            // changed
                            option_change();

                            // Update
                            insert_default_options();

                            // Update view
                            draw();

                        });

                    }


                    // Show CSS functions
                    if(key == 'showCSS'){

                        // Editor data
                        var cssData = get_clean_css(false).replace(/ |\t/g,'');

                        // Check if editor open
                        if (mainBody.hasClass("yp-css-editor-active")) {
                            
                            // Get editor data from ace editor if open                            
                            cssData = editor.getValue().replace(/ |\t/g,'');

                        }else{

                            // Open if hidden
                            window.disable_auto_insert = true;
                            $(".css-editor-btn").trigger("click");

                        }

                        // Getting breakpoint query
                        var mediaQuery = $(this).attr("data-media-content").replace(/ |\t/g,'');

                        // Find media start line
                        var goToLine = cssData.split(mediaQuery)[0].split(/\r\n|\r|\n/).length;

                        // Clear whitespaces for check media query
                        var noSpaceCSS = cssData.replace(/\}\s+\}/g,'}}');

                        // is valid?
                        if(isDefined(noSpaceCSS.split(mediaQuery)[1])){

                            // Delay
                            setTimeout(function(){

                                // Scroll to line
                                editor.scrollToLine(goToLine, true, false);

                            },4);

                            // find total media line number
                            var mediaLines = noSpaceCSS.split(mediaQuery)[1].split(/\}\}/g)[0].split(/\r\n|\r|\n/).length;

                            // load range API
                            var Range = ace.require("ace/range").Range;

                            // Select the media query section
                            editor.selection.setRange(new Range(goToLine-1, 0, (goToLine+mediaLines)+1, 1));

                        }

                    }

                },
                items: {
                    "reset": {
                        name: "Reset",
                        className: "reset-breakpoint-menu"
                    },
                    "showCSS": {
                        name: "Show CSS",
                        className: "show-css-menu"
                    }
                }
            });


            /* ---------------------------------------------------- */
            /* Frame context menu options.                          */
            /* ---------------------------------------------------- */
            $.contextMenu({

                events: {

                    // Draw Again Borders, Tooltip After Contextmenu Hide.
                    hide: function(opt) {

                        draw();

                    },

                    // if contextmenu show; update some options.
                    show: function() {

                        // Disable contextmenu on animate creator.
                        if (is_animate_creator()) {
                            get_selected_element().contextMenu("hide");
                        }

                        var selector = get_current_selector();

                        var elementP = iframe.find(selector).parent();

                        if (elementP.length > 0 && elementP[0].nodeName.toLowerCase() != "html") {
                            $(".yp-contextmenu-parent").removeClass("yp-disable-contextmenu");
                        } else {
                            $(".yp-contextmenu-parent").addClass("yp-disable-contextmenu");
                        }

                        if(iframe.find(".yp-selected-others-box").length > 0){
                            $(".yp-contextmenu-select-it").show();
                        }else{
                            $(".yp-contextmenu-select-it").hide();
                        }

                    }

                },

                // Open context menu only if a element selected.
                selector: 'body.yp-content-selected .yp-selected,body.yp-content-selected.yp-selected',
                callback: function(key, options) {

                    var selector = get_current_selector();

                    // Context Menu: Parent
                    if (key == "parent") {

                        // If Disable, Stop.
                        if ($(".yp-contextmenu-parent").hasClass("yp-disable-contextmenu")) {
                            return false;
                        }

                        // add class to parent.
                        get_selected_element().parent().addClass("yp-will-selected");

                        // clean
                        clean();

                        // Get parent selector.
                        var parentSelector = $.trim(get_parents(iframe.find(".yp-will-selected"), "default"));

                        // Set Selector
                        set_selector(parentSelector,null);

                    }

                    // Context Menu: Hover
                    if (key == "hover" || key == "focus" || key == "link" || key == "visited" || key == "active") {

                        selector = selector.replace(/:(?!hover|focus|active|link|visited)/g,"YP_DOTTED_PREFIX");

                        if (!$(".yp-contextmenu-" + key).hasClass("yp-active-contextmenu")){
                            if (selector.indexOf(":") == -1) {
                                selector = selector + ":" + key;
                            } else {
                                selector = selector.split(":")[0] + ":" + key;
                            }
                        } else {
                            selector = selector.split(":")[0];
                        }

                        selector = selector.replace(/YP_DOTTED_PREFIX/g,":");

                        set_selector(selector,null);

                    }

                    // write CSS
                    if (key == "writeCSS") {

                        if (mainBody.hasClass("yp-css-editor-active")) {
                            $(".css-editor-btn").trigger("click");
                        }

                        $(".css-editor-btn").trigger("click");

                    }

                    // Shows the parent tree
                    if(key == "parentTree"){
                        show_parent_tree();
                    }

                    // Select Just It
                    if (key == 'selectjustit') {

                        mainBody.addClass("yp-select-just-it");

                        var currentSelector = get_current_selector();                      

                        if(iframe.find(currentSelector).length > 1){

                            selector = get_parents(null, "sharp");

                            var selectorPlus = single_selector(selector, false);

                            if (iframe.find(selectorPlus).length !== 0) {
                                set_selector(selectorPlus,null);
                            }

                        }

                        mainBody.removeClass("yp-select-just-it");

                    }
                    /* Select just it functions end here */


                    if(key == 'resetit'){
                        reset_selected_element(false);
                    }

                    if(key == 'reset-with-childs'){
                        reset_selected_element(true);
                    }

                    // leave Selected element.
                    if (key == 'close') {
                        clean();
                        gui_update();
                    }

                    // toggle selector editor.
                    if (key == "editselector") {
                        $(".yp-button-target").trigger("click");
                    }

                },

                // Content menu elements.
                items: {
                    "hover": {
                        name: ":Hover",
                        className: "yp-contextmenu-hover"
                    },
                    "focus": {
                        name: ":Focus",
                        className: "yp-contextmenu-focus"
                    },
                    "sep1": "---------",
                    "parent": {
                        name: "Select Parent Item",
                        className: "yp-contextmenu-parent"
                    },
                    "parentTree": {
                        name: "Show Parent Tree",
                        className: "yp-contextmenu-parent"
                    },
                    "sep2": "---------",
                    "editselector": {
                        name: "Edit Selector",
                        className: "yp-contextmenu-selector-edit"
                    },
                    "selectjustit": {
                        name: "Select Only This",
                        className: "yp-contextmenu-select-it"
                    },
                    "writeCSS": {
                        name: "Write CSS",
                        className: "yp-contextmenu-type-css"
                    },
                    "reset": {
                        name: "Reset Styles",
                        items:{
                            "resetit": {
                                name: "The Element",
                                className: "yp-contextmenu-reset-it"
                            },
                            "reset-with-childs": {
                                name: "The Child Elements",
                                className: "yp-contextmenu-reset-childs"
                            },
                        },
                    },
                    "close": {
                        name: "Leave",
                        className: "yp-contextmenu-close"
                    }
                }

            });


            /* ---------------------------------------------------- */
            /* Updating Gui of the editor panel                     */
            /* ---------------------------------------------------- */
            function gui_update() {

                if(mainBody.hasClass("yp-gui-update")){
                    return false;
                }

                var panel = $(".yp-select-bar");
                var list = $(".yp-editor-list");
                var panelNoSelection = $(".yp-no-selected");

                // Get Scroll Top
                window.lastScrollTop = list.scrollTop();

                // Setting height auto value
                mainBody.addClass("yp-gui-update");

                // Positions, varriables
                var height =  list.height(),
                editorTop = $(".yp-editor-top").height(),
                iframeHeight = iframe.height(),
                panelOffsetTop = panel.offset().top,
                winHeight = $(window).height(),
                maximumHeight = winHeight - panelOffsetTop - editorTop - 20;

                // No Element Selected Section
                if (panelNoSelection.css("display") == "block") {

                    // Getting Height
                    height = panelNoSelection.outerHeight() + 35;

                    // Apply
                    panel.height(height + editorTop);

                
                // CSS Properties list section
                } else if ($(".yp-editor-list > li.active:not(.yp-li-about)").length == 0) {

                    // Apply to panel
                    panel.height(height + editorTop);

                    // Apply to list
                    list.height(height);

                // Property Section
                } else {

                    // Apply MaxHeight
                    if(height > maximumHeight){
                        height = maximumHeight;
                    }

                    // Apply to panel
                    panel.height(height + editorTop);

                    // Apply to list
                    list.height(height);

                }

                // Remove Class
                mainBody.removeClass("yp-gui-update");

                // Set Scroll Top
                list.scrollTop(window.lastScrollTop);


                // Auto Margin Right + Margin Scroll problem fix.
                setTimeout(function(){

                    if (iframeHeight > winHeight && is_responsive_mod() === false) {
                        panel.css("marginRight", 8 + get_scroll_bar_width() + "px");
                    } else {
                        panel.css("marginRight", "8px");
                    }

                },10);

            }


            /* ---------------------------------------------------- */
            /* Element picker                                       */
            /* ---------------------------------------------------- */
            $(".yp-element-picker").click(function() {
                mainBody.toggleClass("yp-element-picker-active");
                $(this).toggleClass("active");
            });


            /* ---------------------------------------------------- */
            /* Measuring Tool                                       */
            /* ---------------------------------------------------- */
            mainDocument.on("mousemove mousedown", function(e){

                if (mainBody.hasClass("yp-metric-disable") === false) {

                    var x = e.pageX;
                    var y = e.pageY;
                    var cx = e.clientX;
                    var cy = e.clientY;
                    var ww = $(window).width();
                    var wh = $(window).height();

                    if (mainBody.hasClass("yp-responsive-resizing")) {
                        y = y - 10;
                        x = x - 10;
                        cx = cx - 10;
                        cy = cy - 10;
                    }

                    if ($(this).find("#iframe").length > 0) {

                        if (is_responsive_mod()) {

                            if (mainBody.hasClass("yp-responsive-resizing")) {

                                // Min 320 W
                                if (cx < 320 + 48) {
                                    cx = 320 + 48;
                                }

                                // Max full-80 W
                                if (cx > ww - 82) {
                                    cx = ww - 82;
                                }

                                // Min 320 H
                                if (cy < 320 + 40) {
                                    cy = 320 + 40;
                                }

                                // Max full-80 H
                                if (cy > wh - 80) {
                                    cy = wh - 80;
                                }

                            }

                            $(".metric-top-border").attr("style", "left:" + cx + "px !important;display:block;margin-left:-1px !important;");
                            $(".metric-left-border").attr("style", "top:" + cy + "px !important;");
                            $(".metric-top-tooltip").attr("style", "top:" + cy + "px !important;display:block;");
                            $(".metric-left-tooltip").attr("style", "left:" + cx + "px !important;display:block;margin-left:1px !important;");

                            if (mainBody.hasClass("yp-responsive-resizing")) {
                                $(".metric-left-tooltip span").text(x + 10);
                                $(".metric-top-tooltip span").text(y + 10);
                            } else {
                                $(".metric-left-tooltip span").text(x);
                                $(".metric-top-tooltip span").text(y);
                            }

                        }

                    }

                    if ($(this).find("#iframe").length === 0) {

                        if (mainBody.hasClass("yp-responsive-resizing")) {

                            // Min 320 W
                            if (cx < 320) {
                                cx = 320;
                            }

                            // Max full W
                            if (cx > ww) {
                                cx = ww;
                            }

                            // Min 320 H
                            if (cy < 320) {
                                cy = 320;
                            }

                            // Max full H
                            if (cy > wh) {
                                cy = wh;
                            }

                        }

                        $(".metric-top-border").attr("style", "left:" + cx + "px !important;display:block;");
                        $(".metric-left-border").attr("style", "top:" + cy + "px !important;margin-top:30px;");
                        $(".metric-top-tooltip").attr("style", "top:" + cy + "px !important;display:block;margin-top:32px;");
                        $(".metric-left-tooltip").attr("style", "left:" + cx + "px !important;display:block;");

                        if (mainBody.hasClass("yp-responsive-resizing")) {
                            $(".metric-top-tooltip span").text(y + 10);
                            $(".metric-left-tooltip span").text(x + 10);
                        } else {
                            $(".metric-top-tooltip span").text(y);
                            $(".metric-left-tooltip span").text(x);
                        }

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Measuiring Tool: Hover Box                           */
            /* ---------------------------------------------------- */
            iframe.on("mousemove", function(e) {

                if (mainBody.hasClass("yp-metric-disable") === false){

                    var element = $(e.target);

                    if (is_resizing() || is_visual_editing() || is_dragging()) {
                        element = get_selected_element();
                    }

                    if(element.hasAttr("class")){
                        if(/(^|\s+)yp-(.*?)/g.test(element.attr("class"))){
                            element = get_selected_element();
                        }
                    }

                    // CREATE SIMPLE BOX
                    var element_offset = element.offset();

                    if (isDefined(element_offset)) {

                        var topBoxesI = element_offset.top;
                        var leftBoxesI = element_offset.left;

                        if (leftBoxesI < 0) {
                            leftBoxesI = 0;
                        }

                        var widthBoxesI = element.outerWidth();
                        var heightBoxesI = element.outerHeight();

                        // Dynamic Box
                        if (iframe.find(".hover-info-box").length === 0) {
                            iframeBody.append("<div class='hover-info-box'></div>");
                        }

                        iframe.find(".hover-info-box").css("width", widthBoxesI).css("height", heightBoxesI).css("top", topBoxesI).css("left", leftBoxesI);

                    }

                    if (isUndefined(element_offset)) {
                        return false;
                    }

                    var topBoxes = element_offset.top;
                    var leftBoxes = element_offset.left;

                    if (leftBoxes < 0) {
                        leftBoxes = 0;
                    }

                    var widthBoxes = element.outerWidth(false);
                    var heightBoxes = element.outerHeight(false);

                    var bottomBoxes = topBoxes + heightBoxes;

                    if (iframe.find(".yp-size-handle").length === 0) {
                        iframeBody.append("<div class='yp-size-handle'>W : <span class='ypdw'></span> px<br>H : <span class='ypdh'></span> px</div>");
                    }

                    var w = element.css("width");
                    var h = element.css("height");

                    iframe.find(".yp-size-handle .ypdw").text(parseInt(w));
                    iframe.find(".yp-size-handle .ypdh").text(parseInt(h));

                    leftBoxes = leftBoxes + (widthBoxes / 2);

                    iframe.find(".yp-size-handle").css("top", bottomBoxes).css("bottom", "auto").css("left", leftBoxes).css("position", "absolute");

                    if (parseFloat(bottomBoxes) > (parseFloat($("body #iframe").height()) + parseFloat(iframe.scrollTop())) + 40) {

                        iframe.find(".yp-size-handle").css("bottom", "10px").css("top", "auto").css("left", leftBoxes).css("position", "fixed");

                    }

                }

            });

    
            /* ---------------------------------------------------- */
            /* Window Resize                                        */
            /* ---------------------------------------------------- */
            $(window).resize(function(){

                if(mainBody.find(".yp-parent-tree").length > 0){

                    close_parent_tree();

                    setTimeout(function(){
                        show_parent_tree();
                    },5);

                }

                setTimeout(function(){
                    gui_update();
                },5);

                setTimeout(function(){
                    update_gradient_pointers();
                },5);

            });


            /* ---------------------------------------------------- */
            /* Element Selector Box Function                        */
            /* ---------------------------------------------------- */
            iframe.on("mouseover", iframe, function(evt){

               if ($(".yp-selector-mode.active").length > 0 && mainBody.hasClass("yp-metric-disable")){

                    // Element
                    var element = $(evt.target);

                    // Adding always class to last hovered element for some reasions.
                    iframe.find(".yp-recent-hover-element").removeClass("yp-recent-hover-element");

                    if (is_content_selected() === true && mainBody.hasClass("yp-control-key-down") === false) {
                        element.addClass("yp-recent-hover-element");
                    }

                    var elementClasses = element.attr("class");

                    // Multi selecting support
                    if (is_content_selected() === false) {
                        if (element.hasClass("yp-selected-tooltip")) {
                            clean();
                            return false;
                        }

                        if (element.parent().length > 0) {
                            if (element.parent().hasClass("yp-selected-tooltip")) {
                                clean();
                                return false;
                            }
                        }
                    }

                    // If not any yellow pencil element.
                    if (isDefined(elementClasses)) {
                        if (elementClasses.indexOf("yp-selected-boxed-") > -1) {
                            return false;
                        }
                    }

                    // If colorpicker stop.
                    if (mainBody.hasClass("yp-element-picker-active")) {

                        window.pickerColor = element.css("backgroundColor");

                        if (window.pickerColor == '' || window.pickerColor == 'transparent') {

                            var bgColor = $(this).css("backgroundColor");
                            element.parents().each(function() {

                                if (bgColor != 'transparent' && bgColor != '' && bgColor !== null) {
                                    window.pickerColor = $(this).css("backgroundColor");
                                    return false;
                                }

                            });

                        }

                        var color = window.pickerColor.toString();

                        $(".yp-element-picker.active").parent().parent().find(".wqcolorpicker").val(get_color(color)).trigger("change");

                        if (window.pickerColor == '' || window.pickerColor == 'transparent') {
                            var id_prt = $(".yp-element-picker.active").parent().parent();
                            id_prt.find(".yp-disable-btn.active").trigger("click");
                            id_prt.find(".yp-none-btn:not(.active)").trigger("click");
                            id_prt.find(".wqminicolors-swatch-color").css("backgroundColor", "transparent");
                        }

                    }

                    var nodeName = element[0].nodeName;

                    // If element already selected, stop.
                    if (is_content_selected() === true && mainBody.hasClass("yp-control-key-down") === false) {
                        return false;
                    }

                    // Not show if p tag and is empty.
                    if (element.html() == '&nbsp;' && element[0].nodeName == 'P') {
                        return false;
                    }

                    if (nodeName.toLowerCase() == 'html') {
                        return false;
                    }

                    // if Not Null continue.
                    if (element === null) {
                        return false;
                    }

                    // stop if not have
                    if (element.length === 0) {
                        return false;
                    }

                    // If selector disable stop.
                    if (body.hasClass("yp-selector-disabled")) {
                        return false;
                    }

                    if (is_content_selected() === false){

                        // Remove all ex data.
                        clean();

                        // Hover it
                        element.addClass("yp-selected");

                    }

                    // Geting selector.
                    var selector;
                    if (window.setSelector === false) {
                        selector = get_parents(element, "default");
                    } else {
                        selector = window.setSelector;
                    }
                    

                    evt.stopPropagation();
                    evt.preventDefault();

                    if (is_content_selected() === false) {

                            // transform.
                            if (check_with_parents(element, "transform", "none", "!=") === true) {
                                body.addClass("yp-has-transform");
                            }

                            draw_box(evt.target, 'yp-selected-boxed');

                            var selectorView = selector;

                            var selectorTag = selector.replace(/>/g, '').replace(/  /g, ' ').replace(/\:nth-child\((.*?)\)/g, '');

                            // Element Tooltip  |  Append setting icon.
                            iframeBody.append("<div class='yp-selected-tooltip'><small class='yp-tooltip-small'>" + get_tag_information(selectorTag) + "</small> " + $.trim(selectorView) + "</div><div class='yp-edit-tooltip'><span class='yp-edit-menu'></span></div>");

                            var appendSelectData = '';
                            var currentData = '';

                            // Select Others.. (using .not because will be problem when selector has "," multip selectors)
                            iframe.find(selector).not(".yp-selected").not(".yp-multiple-selected").each(function(i) {

                                $(this).addClass("yp-selected-others");

                                currentData = draw_other_box(this, 'yp-selected-others', i);

                                if (typeof currentData === 'string') {
                                    appendSelectData += currentData;
                                }

                            });

                            if(appendSelectData != ''){
                                iframeBody.append(appendSelectData);
                            }

                            draw_tooltip();

                    }else{

                        if(is_content_selected() && mainBody.hasClass("yp-control-key-down")){

                            if(element.parents(".yp-selected").length === 0){

                                // Clean before
                                iframe.find(".yp-multiple-selected").removeClass("yp-multiple-selected");

                                // Add new
                                element.addClass("yp-multiple-selected");

                                // Draw
                                appendSelectData = draw_other_box(element, 'yp-selected-others', "multiable");

                                if (typeof appendSelectData === 'string') {
                                    iframeBody.append(appendSelectData);
                                }

                            }

                        }

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Updating Draws                                       */
            /* ---------------------------------------------------- */
            function draw() {

                // If not visible stop.
                var element = get_selected_element();
                if (check_with_parents(element, "display", "none", "==") === true || check_with_parents(element, "opacity", "0", "==") === true || check_with_parents(element, "visibility", "hidden", "==") === true) {
                    return false;
                }

                // selected boxed.
                draw_box(".yp-selected", 'yp-selected-boxed');

                var appendSelectData = '';
                var currentData = '';

                // Select Others.
                iframe.find(".yp-selected-others:not(.yp-multiple-selected)").each(function(i) {

                    currentData = draw_other_box(this, 'yp-selected-others', i);

                    if (typeof currentData === 'string') {
                        appendSelectData += currentData;
                    }

                });

                if(appendSelectData != ''){
                    iframeBody.append(appendSelectData);
                }

                // Tooltip
                draw_tooltip();

                // Dragger update.
                update_drag_handle_position();

            }


            /* ---------------------------------------------------- */
            /* ReSorting styles as the best                         */
            /* ---------------------------------------------------- */
            function resort_style_data_positions(){

                var styleArea = the_editor_data();

                // Sort element by selector because Created CSS Will keep all css rules in one selector.
                styleArea.find("style").each(function(){

                    var that = $(this);

                    // Check if not resorted.
                    if(that.hasClass("yp-resorted") === false){

                        // addClass for not sort again.
                        that.addClass("yp-resorted");

                        // Get this selector.
                        var style = that.attr("data-style");

                        // check if there next styles that has same selector.
                        if(styleArea.find("[data-style="+style+"]").length > 1){

                            // Find all next styles that has same selector
                            styleArea.find("[data-style="+style+"]").not(this).each(function(){

                                // Cache
                                var element = $(this);

                                if(element.hasClass("yp-resorted") === false){

                                    // move from dom.
                                    that.after(element);

                                    // add class
                                    element.addClass("yp-resorted");

                                }

                            });

                        }

                    }

                });

                // max-width == 9 > 1
                styleArea.find("style[data-size-mode^='(max-width:']").not("[data-size-mode*=and]").sort(function (a,b){
                    return +parseInt(b.getAttribute('data-size-mode').replace(/\D/g,'')) - +parseInt(a.getAttribute('data-size-mode').replace(/\D/g,''));
                }).appendTo(styleArea);

                // min-width == 1 > 9
                styleArea.find("style[data-size-mode^='(min-width:']").not("[data-size-mode*=and]").sort(function (a,b){
                    return +parseInt(a.getAttribute('data-size-mode').replace(/\D/g,'')) - +parseInt(b.getAttribute('data-size-mode').replace(/\D/g,''));
                }).appendTo(styleArea);

            }


            /* ---------------------------------------------------- */
            /* Getting current media query                          */
            /* ---------------------------------------------------- */
            function create_media_query_before(css) {

                if (mainBody.hasClass("process-by-code-editor")) {
                    if (mainBody.attr("data-responsive-type") !== undefined && mainBody.attr("data-responsive-type") !== false && mainBody.attr("data-responsive-type") != 'desktop') {
                        return mainBody.attr("data-responsive-type");
                    } else {
                        return '';
                    }
                }

                if (is_responsive_mod()) {

                    var w = $("#iframe").width();
                    var format = $(".media-control").attr("data-code");
                    return '@media (' + format + ':' + w + 'px){';

                } else {

                    if(isUndefined(css)){
                        return '';
                    }

                    var automedia = get_media_queries(css,false);

                    if(automedia != false && automedia != undefined){
                        mainBody.addClass("yp-adding-auto-media");
                        return automedia+"{";
                    }else{
                        return '';
                    }

                    
                }

            }


            /* ---------------------------------------------------- */
            /* Getting media query end                              */
            /* ---------------------------------------------------- */
            function create_media_query_after() {

                if (is_responsive_mod()) {

                    return '}';

                } else {

                    // Auto Media
                    if(mainBody.hasClass("yp-adding-auto-media")){
                        mainBody.removeClass("yp-adding-auto-media");
                        return '}';
                    }else{
                        return ''; // Blank
                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Toggle Media max/min-width                           */
            /* ---------------------------------------------------- */
            $(".media-control").click(function() {

                var c = $(this).attr("data-code");

                if (c == 'max-width') {
                    $(this).attr("data-code", "min-width");
                    $(this).text("above");
                }

                if (c == 'min-width') {
                    $(this).attr("data-code", "max-width");
                    $(this).text("below");
                }

                update_responsive_size_notice();

                update_responsive_breakpoints();

                $(this).tooltip('fixTitle').tooltip("show");

            });


            /* ---------------------------------------------------- */
            /* Media Control Toolip                                 */
            /* ---------------------------------------------------- */
            $(".media-control").tooltip({

                // Set dynamic title
                title: function(){
                    var format = $(this).attr("data-code");

                    // opposite
                    if(format == 'max-width'){
                        format = 'min-width';
                    }else if(format == 'min-width'){
                        format = 'max-width';
                    }

                    return "Toggle media query condition as <strong>"+format+"</strong>";
                },

                // Tooltip settings
                animation: false,
                delay: { show: 10, hide: 0 },
                placement: 'top',
                trigger: 'hover',
                container: "body",
                html: true

            });


            /* ---------------------------------------------------- */
            /* use important if CSS not working without important   */
            /* ---------------------------------------------------- */
            function force_insert_rule(selector, id, value, prefix, size) {

                if (isUndefined(size)){
                    size = get_media_condition();
                }

                var css = id;

                // Clean value
                value = value.replace(/\s+?!important/g,'').replace(/\;$/g,'');

                // Remove Style Without important.
                iframe.find("." + get_id(selector) + '-' + id + '-style[data-size-mode="' + size + '"]').remove();

                // Append Style Area If Not Have.
                if (the_editor_data().length <= 0) {
                    iframeBody.append("<div class='yp-styles-area'></div>");
                }

                // Checking.
                if (value == 'disable' || value == '' || value == 'undefined' || value === null) {
                    return false;
                }

                // Responsive Settings
                var mediaBefore = create_media_query_before(css);

                if(mainBody.hasClass("yp-adding-auto-media")){
                    size = space_cleaner(mediaBefore.replace("@media","").replace("{",""));
                }

                var mediaAfter = create_media_query_after();

                if(isDefined(size) && body.hasClass("yp-animate-manager-active") && is_responsive_mod()){
                    mediaBefore = "@media " + size + "{";
                }

                // New Value
                var current = value + prefix;

                // Append.
                if (get_id(selector) != '') {

                    if (is_animate_creator() === true && id != 'position') {

                        iframe.find("." + get_id(body.attr("data-anim-scene") + css)).remove();

                        iframe.find(".yp-anim-scenes ." + body.attr('data-anim-scene') + "").append('<style data-rule="' + css + '" class="style-' + body.attr("data-anim-scene") + ' scenes-' + get_id(css) + '-style">' + selector + '{' + css + ':' + current + ' !important}</style>');

                    } else {

                        the_editor_data().append('<style data-rule="' + css + '" data-size-mode="' + size + '" data-style="' + get_id(selector) + '" class="' + get_id(selector) + '-' + id + '-style yp_current_styles">' + mediaBefore + '' + '' + selector + '{' + css + ':' + current + ' !important}' + '' + mediaAfter + '</style>');

                        resort_style_data_positions();

                    }

                }

                // Check if important rule worked.
                setTimeout(function(){

                    // Added important tag but still not work,
                    // So we need to use a better selector.
                    if(is_css_work(id,css,current) == false){

                        // current selector Length
                        var selectorLength = get_selector_array(selector).length;

                        // Max long selector is 12
                        if((selectorLength + 1) > 12){
                            return false;
                        }

                        // add 1 more to new Selector
                        window.minCroppedSelector = selectorLength + 1;

                        // Generate a better selector
                        var betterSelector = get_parents(iframe.find(".yp-content-selected .yp-selected"), window.lastParentQueryStatus);

                        // Return to default
                        window.minCroppedSelector = false;

                        // Stop if not have another selector alternative.
                        if(get_selector_array(betterSelector).length <= selectorLength){
                            return false;
                        }

                        // Remove old style.
                        iframe.find("." + get_id(selector) + '-' + id + '-style[data-size-mode="' + size + '"]').remove();


                        // Run force insert rule function with an better selector.
                        force_insert_rule(betterSelector, id, value, prefix, size);

                        setTimeout(function(){
                            draw();
                        }, 10);

                    }

                }, 5);


            }


            // Typing Timer Editor
            var typingTimer;

            
            /* ---------------------------------------------------- */
            /* Updating Draw/Gui while keyUp                        */
            /* ---------------------------------------------------- */
            $("#cssData").on("keyup", function(e) {

                var typingTimerS = 0;
                if(e.originalEvent){
                    typingTimerS = 900;
                }

                if (body.hasClass("yp-selectors-hide") === false && body.hasClass("yp-css-data-trigger") === false && typingTimerS !== 0) {

                    body.addClass("yp-selectors-hide");

                    // Opacity Selector
                    if (iframe.find(".context-menu-active").length > 0) {
                        get_selected_element().contextMenu("hide");
                    }

                    hide_frame_ui(0);

                }

                body.removeClass("yp-css-data-trigger");

                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {

                    if (body.hasClass("yp-selectors-hide") && $(".wqNoUi-active").length === 0 && mainBody.hasClass("autocomplete-active") === false && $(".yp-select-bar .tooltip").length === 0) {

                        body.removeClass("yp-selectors-hide");

                        show_frame_ui(200);

                    }

                    insert_default_options();
                    return false;

                }, typingTimerS);

                // Append all css to iframe.
                if (iframe.find("#yp-css-data-full").length === 0) {
                    the_editor_data().after("<style id='yp-css-data-full'></style>");
                }

                // Need to process.
                body.addClass("yp-need-to-process");

                // Update css source.
                iframe.find("#yp-css-data-full").html(editor.getValue());

                // Empty data.
                the_editor_data().empty();

                // Remove ex.
                iframe.find(".yp-live-css").remove();

                // Update
                $(".yp-save-btn").html(l18_save).removeClass("yp-disabled").addClass("waiting-for-save");

                // Update sceen.
                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Process CSS                                          */
            /* ---------------------------------------------------- */
            $(".yp-select-bar").on("mouseover mouseout", function() {

                if (mainBody.hasClass("yp-need-to-process")) {

                    // CSS To Data.
                    process(false, false);

                }

            });


            /* ---------------------------------------------------- */
            /* Define the plugin elements                           */
            /* ---------------------------------------------------- */
            window.yp_elements = ".yp-selected-handle,.yp-selected-tooltip,.yp-selected-boxed-margin-top,.yp-selected-boxed-margin-bottom,.yp-selected-boxed-margin-left,.yp-selected-boxed-margin-right,.yp-selected-boxed-top,.yp-selected-boxed-bottom,.yp-selected-boxed-left,.yp-selected-boxed-right,.yp-selected-others-box,.yp-edit-tooltip,.yp-selected-boxed-padding-top,.yp-selected-boxed-padding-bottom,.yp-selected-boxed-padding-left,.yp-selected-boxed-padding-right,.yp-edit-menu";


            /* ---------------------------------------------------- */
            /* Hide blue borders                                    */
            /* ---------------------------------------------------- */
            function hide_frame_ui(number) {

                if (!is_content_selected()) {
                    return false;
                }

                if (iframe.find(".yp-selected-boxed-top").css("opacity") != 1) {
                    return false;
                }

                draw();

                iframe.find(window.yp_elements).stop().animate({
                    opacity: 0
                }, number);

            }


            /* ---------------------------------------------------- */
            /* Show blue border                                     */
            /* ---------------------------------------------------- */
            function show_frame_ui(number) {

                if (!is_content_selected()) {
                    return false;
                }

                if(body.hasClass("yp-force-hide-select-ui")){
                    return false;
                }

                if (iframe.find(".yp-selected-boxed-top").css("opacity") != "0") {
                    return false;
                }

                draw();

                iframe.find(window.yp_elements).stop().not(".yp-selected-others-box").animate({
                    opacity: 1
                }, number);

                iframe.find(".yp-selected-others-box").stop().animate({
                    opacity: 0.9
                }, number);

            }


            /* ---------------------------------------------------- */
            /* Hide borders on panel and animation generator bar    */
            /* ---------------------------------------------------- */
            $(".yp-this-content,.anim-bar").bind({
                mouseenter: function() {

                    if($(".fake-layer").length > 0){
                        return false;
                    }

                    if (body.hasClass("yp-selectors-hide") === false) {

                        body.addClass("yp-selectors-hide");

                        // Opacity Selector
                        if (iframe.find(".context-menu-active").length > 0) {
                            get_selected_element().contextMenu("hide");
                        }

                        hide_frame_ui(200);

                    }

                },
                mouseleave: function() {

                    if($(".fake-layer").length > 0){
                        return false;
                    }

                    if (body.hasClass("yp-selectors-hide") && $(".wqNoUi-active").length === 0 && mainBody.hasClass("autocomplete-active") === false && $(".yp-select-bar .tooltip").length === 0) {

                        body.removeClass("yp-selectors-hide");

                        show_frame_ui(200);

                    }

                }
            });


            /* ---------------------------------------------------- */
            /* Iframe mouseover selection                           */
            /* ---------------------------------------------------- */
            iframe.on("mouseover", iframe, function(){

                if ($(".wqNoUi-active").length === 0 && mainBody.hasClass("autocomplete-active") === false && $(".yp-select-bar .tooltip").length === 0) {

                    show_frame_ui(200);

                }

            });


            /* ---------------------------------------------------- */
            /* Show borders when panel mouseleave                   */
            /* ---------------------------------------------------- */
            iframe.on("mouseleave", ".yp-select-bar", function(){

                if (body.hasClass("yp-selectors-hide") && $(".wqNoUi-active").length === 0 && mainBody.hasClass("autocomplete-active") === false && $(".yp-select-bar .tooltip").length === 0) {

                    body.removeClass("yp-selectors-hide");

                    show_frame_ui(200);

                }

            });


            /* ---------------------------------------------------- */
            /* Get current media condition                          */
            /* ---------------------------------------------------- */
            function get_media_condition(){

                // Default
                var size = 'desktop';
                
                // Is res?
                if (is_responsive_mod()) {

                    var frameWidth = $("#iframe").width();
                    var media = $(".media-control").attr("data-code");
                    size = '(' + media + ':' + frameWidth + 'px)';

                }

                return size;

            }


            /* ---------------------------------------------------- */
            /* Getting all CSS selectors in stylesheets             */
            /* ---------------------------------------------------- */
            function get_all_selectors(source){

                source = get_minimized_css(source,true);

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


            /* ---------------------------------------------------- */
            /* Resetting selected element                           */
            /* ---------------------------------------------------- */
            function reset_selected_element(childs){

                // If not have an selected element
                if(!is_content_selected()){
                    return false;
                }

                var data = editor.getValue();

                // Selectors
                var array = get_all_selectors(data);

                // If not have selectors
                if(array.length <= 0){
                    return false;
                }

                // Each all selectors
                for(var i = 0; i < array.length; i++){

                    var searchSelector = get_foundable_query(array[i],true,true,true);


                    if(childs === false){

                        // Target is selected element.
                        if(iframe.find(searchSelector).hasClass("yp-selected")){

                            // remove
                            iframe.find("[data-style='"+get_id(array[i])+"']").remove();

                        }

                    }

                    if(childs === true){

                        // Target is selected element and childs.
                        if(iframe.find(searchSelector).parents(".yp-selected").length > 0){

                            // remove
                            iframe.find("[data-style='"+get_id(array[i])+"']").remove();

                        }

                    }

                }

                // Update
                option_change();

                // Set && update        
                insert_default_options();

            }


            /* ---------------------------------------------------- */
            /* Clean not etc sysbols                                 */
            /* ---------------------------------------------------- */
            function nice_selectors(data,start){

                if(start === true){

                    // Nth child
                    data = data.replace(/:nth-child\((.*?)\)/g, '\.nth-child\.$1\.');

                    // Not
                    data = data.replace(/:not\((.*?)\)/g, '\.notYP$1YP');

                    // lang
                    data = data.replace(/:lang\((.*?)\)/g, '\.langYP$1YP');

                    // nth-last-child()
                    data = data.replace(/:nth-last-child\((.*?)\)/g, '\.nth-last-child\.$1\.');

                    // nth-last-of-type()
                    data = data.replace(/:nth-last-of-type\((.*?)\)/g, '\.nth-last-of-type\.$1\.');

                    // nth-of-type()
                    data = data.replace(/:nth-of-type\((.*?)\)/g, '\.nth-of-type\.$1\.');

                }else{

                    // Nth child
                    data = data.replace(/\.nth-child\.(.*?)\./g, ':nth-child($1)');

                    // Not
                    data = data.replace(/\.notYP(.*?)YP/g, ':not($1)');

                    // lang
                    data = data.replace(/\.langYP(.*?)YP/g, ':lang($1)');

                    // nth-last-child()
                    data = data.replace(/\.nth-last-child\.(.*?)\./g, ':nth-last-child($1)');

                    // nth-last-of-type()
                    data = data.replace(/\.nth-last-of-type\.(.*?)\./g, ':nth-last-of-type($1)');

                    // nth-of-type()
                    data = data.replace(/\.nth-of-type\.(.*?)\./g, ':nth-of-type($1)');

                }

                return data;

            }


            /* ---------------------------------------------------- */
            /* Super Basic insert any CSS rule to plugin data       */
            /* ---------------------------------------------------- */
            function get_insert_rule_basic(selector, id, value, size) {

                var appendData = '';

                if (isUndefined(size)) {
                    if (is_responsive_mod()) {
                        var frameW = $("#iframe").width();
                        var format = $(".media-control").attr("data-code");
                        size = '(' + format + ':' + frameW + 'px)';
                    } else {
                        size = 'desktop';
                    }
                }

                // Responsive Settings
                var mediaBefore = create_media_query_before(id);

                if(mainBody.hasClass("yp-adding-auto-media")){
                    size = space_cleaner(mediaBefore.replace("@media","").replace("{",""));
                }

                var mediaAfter = create_media_query_after();

                if(isDefined(size) && body.hasClass("yp-animate-manager-active") && is_responsive_mod()){
                    mediaBefore = "@media " + size + "{";
                }

                // Delete same data.
                var exStyle = iframe.find("." + get_id(selector) + '-' + id + '-style[data-size-mode="' + size + '"]');
                if (exStyle.length > 0) {
                    if (escape_data_value(exStyle.html()) == value) {
                        return false;
                    } else {
                        exStyle.remove(); // else remove.
                    }
                }

                // Delete same for -webkit- prefix: filter and transform.
                exStyle = iframe.find("." + get_id(selector) + '-' + "-webkit-" + id + '-style[data-size-mode="' + size + '"]');
                if (exStyle.length > 0) {
                    if (escape_data_value(exStyle.html()) == value){
                        return false;
                    } else {
                        exStyle.remove(); // else remove.
                    }
                }

                // Append style area.
                if (the_editor_data().length <= 0) {
                    iframeBody.append("<div class='yp-styles-area'></div>");
                }

                // Append default value.
                if (get_id(selector) != ''){

                    var dpt = ':';

                    // Append
                    appendData = '<style data-rule="' + id + '" data-size-mode="' + size + '" data-style="' + get_id(selector) + '" class="' + get_id(selector) + '-' + id + '-style yp_current_styles">' + mediaBefore + '' + '' + selector + '{' + id + dpt + value + '}' + '' + mediaAfter + '</style>';

                }

                return appendData;

            }


            /* ---------------------------------------------------- */
            /* insert CSS to the plugin data                        */
            /* ---------------------------------------------------- */
            function css_to_data(type) {

                // add classses and use as flag.
                body.addClass("process-by-code-editor").attr("data-responsive-type", type);

                // Source.
                var source = editor.getValue();

                // Clean "()" symbol for lets to process CSS as well.
                source = nice_selectors(source,true);

                // Clean.
                source = source.replace(/(\r\n|\n|\r)/g, "").replace(/\t/g, '');

                // Don't care rules in comment.
                source = source.replace(/\/\*(.*?)\*\//g, "");

                // clean.
                source = source.replace(/\}\s+\}/g, '}}').replace(/\s+\{/g, '{').replace(/\}\s+/g,'}');

                // clean.
                source = source.replace(/\s+\}/g, '}').replace(/\{\s+/g, '{');

                // replace bad queris
                source = filter_bad_queries(source);

                // If responsive
                if (type != 'desktop') {

                    // Media query regex. Clean everything about media.
                    var regexType = $.trim(type.replace(/\)/g, "\\)").replace(/\(/g, "\\("));
                    var re = new RegExp(regexType + "(.*?)\}\}", "g");
                    var reQ = new RegExp(regexType, "g");
                    source = source.match(re).toString();

                    source = source.replace(reQ, "");
                    source = source.toString().replace(/\}\}/g, "}");

                } else {

                    // Don't care rules in media query in non-media mode.
                    source = source.replace(/@media(.*?)\}\}/g, '');

                }

                // if no source, stop.
                if (source == '') {
                    return false;
                }

                // if have a problem in source, stop.
                if (source.split('{').length != source.split('}').length) {
                    swal({title: "Sorry.",text: "CSS Parse Error. The recent edit could not be saved, please try again.",type: "error",animation: false});
                    return false;
                }

                var selector,insertData = '';

                // IF Desktop; Remove All Rules. (because first call by desktop)
                if (type == 'desktop') {
                    the_editor_data().empty();
                }

                // Replace ","" after "}".
                source = source.toString().replace(/\}\,/g, "}");

                // Getting All CSS Selectors.
                var allSelectors = array_cleaner(source.replace(/\{(.*?)\}/g, '|BREAK|').split("|BREAK|"));

                // add to first.
                source = "}" + source;

                // Make } it two for get multiple selectors rules.
                source = source.replace(/\}/g,"}}");

                // Each All Selectors
                for (var i = 0; i < allSelectors.length; i++) {

                    // Get Selector.
                    selector = space_cleaner(allSelectors[i]);

                    // Valid selector
                    if (selector.indexOf("}") == -1 && selector.indexOf("{") == -1) {

                        // Clean selector with regex.
                        var selectorRegex = selector_regex(selector);
                        
                        // Getting CSS Rules by selector.
                        var CSSRules = source.match(new RegExp("\}" + selectorRegex + '{(.*?)}', 'g'));

                        // Back up cleanen "(" symbols
                        selector = nice_selectors(selector,false);

                        if (CSSRules !== null && CSSRules != '') {

                            // Clean.
                            CSSRules = CSSRules.toString().match(/\{(.*?)\}/g).toString().replace(/\}\,\{/g, ';').replace(/\{/g, '').replace(/\}/g, '').replace(/\;\;/g, ';').split(";");

                            // Variables.
                            var ruleAll;
                            var ruleName;
                            var ruleVal;

                            // Each CSSRules.
                            for (var iq = 0; iq < CSSRules.length; iq++) {

                                ruleAll = $.trim(CSSRules[iq]);

                                if (typeof ruleAll !== undefined && ruleAll.length >= 3 && ruleAll.indexOf(":") != -1) {

                                    ruleName = ruleAll.split(":")[0];

                                    if (ruleName != '') {

                                        ruleVal = ruleAll.split(':').slice(1).join(':');

                                        if (ruleVal != '') {

                                            // Update
                                            insertData += get_insert_rule_basic(selector, ruleName, ruleVal, type.toString().replace(/\{/g, '').replace(/@media /g, '').replace(/@media/g, ''));

                                        }

                                    }

                                }

                            }

                        }

                    }

                }

                // insert at end.
                if(insertData != ''){
                    the_editor_data().append(insertData);
                    resort_style_data_positions();
                }

                // remove classes when end.
                body.removeAttr("data-responsive-type");

            }


            /* ---------------------------------------------------- */
            /* Appy CSS To theme for demo                           */
            /* ---------------------------------------------------- */
            function insert_rule(selector, id, value, prefix, size) {

                if(selector === null){
                    selector = get_current_selector();
                }

                if (isUndefined(size)){
                    size = get_media_condition();
                }

                prefix = $.trim(prefix);

                if (prefix == '.s') {
                    prefix = 's';
                }

                if (prefix.indexOf("px") != -1) {
                    prefix = 'px';
                }

                var css = id;

                // Delete basic CSS.
                delete_live_css(id, false);

                // delete live css.
                iframe.find(".yp-live-css").remove();

                // stop if empty
                if (isUndefined(value)) {
                    return false;
                }

                // toLowerCase
                id = id.toString().toLowerCase();
                css = css.toString().toLowerCase();
                prefix = prefix.toString().toLowerCase();

                if(value.length){

                    var r1 = /\.00$/;
                    var r2 = /\.0$/;

                    if(r1.test(value)){
                        value = value.replace(/\.00$/g,"");
                    }

                    if(r2.test(value)){
                        value = value.replace(/\.0$/g,"");
                    }

                }

                // Value always loweCase.
                if (id != 'font-family' && id != 'background-image' && id != 'list-style-image' && id != 'animation-name' && id != 'animation-play' && id != 'filter' && id != '-webkit-filter' && id != '-webkit-transform') {
                    value = value.toString().toLowerCase();
                }


                // Checks min height and min width and update.
                if(id == 'height' || id == 'width'){

                    // minValue & minFormat
                    var minVal = number_filter($("#min-"+id+"-value").val());
                    var minFormat = $("#min-"+id+"-after").val();

                    // if height is smaller than min-height, so update min height
                    if(parseFloat(value) < parseFloat(minVal) && prefix == minFormat){

                        // Insert min-height
                        insert_rule(selector,'min-'+id,value,prefix,size);

                        // Set default values
                        setTimeout(function(){
                            $.each(['min-'+id], function(i, v) {
                                set_default_value(v);
                            });
                        },50);

                    }

                }


                // Anim selector.
                if (is_animate_creator() === true && id != 'position') {

                    selector = $.trim(selector.replace(/(body)?\.yp-scene-[0-9]/g, ''));
                    selector = add_class_to_body(selector, "yp-" + body.attr("data-anim-scene"));

                    // Dont add any animation rule.
                    if (id.indexOf('animation') != -1) {
                        return false;
                    }

                }

                // Stop.
                if (css == 'set-animation-name') {
                    
                    return false;
                }

                if (id == 'background-color' || id == 'color' || id == 'border-color' || id == 'border-left-color' || id == 'border-right-color' || id == 'border-top-color' || id == 'border-bottom-color') {

                    var valueCheck = $.trim(value).replace("#", '');

                    if (valueCheck == 'red') {
                        value = '#FF0000';
                    } else if (valueCheck == 'white') {
                        value = '#FFFFFF';
                    } else if (valueCheck == 'blue') {
                        value = '#0000FF';
                    } else if (valueCheck == 'orange') {
                        value = '#FFA500';
                    } else if (valueCheck == 'green') {
                        value = '#008000';
                    } else if (valueCheck == 'purple') {
                        value = '#800080';
                    } else if (valueCheck == 'pink') {
                        value = '#FFC0CB';
                    } else if (valueCheck == 'black') {
                        value = '#000000';
                    } else if (valueCheck == 'brown') {
                        value = '#A52A2A';
                    } else if (valueCheck == 'yellow') {
                        value = '#FFFF00';
                    } else if (valueCheck == 'gray') {
                        value = '#808080';
                    }

                }

                // Set perspective id to parent
                if(id == 'perspective'){

                    // Cache current
                    var oldSelector = selector;

                    // clean cache
                    body.removeAttr("data-clickable-select");

                    // Update selector var
                    selector = $.trim(get_parents(get_selected_element().parent(), "default"));

                    // set old as cache again
                    body.attr("data-clickable-select",oldSelector);

                }

                // Set defaults
                if(id == 'border-width'){

                    // Set default values
                    $.each(['border-top-width','border-left-width','border-right-width','border-bottom-width'], function(i, v) {
                        set_default_value(v);
                    });

                }

                if(id == 'border-color'){

                    // Set default values
                    $.each(['border-top-color','border-left-color','border-right-color','border-bottom-color'], function(i, v) {
                        set_default_value(v);
                    });

                }

                if(id == 'border-style'){

                    // Set default values
                    $.each(['border-top-style','border-left-style','border-right-style','border-bottom-style'], function(i, v) {
                        set_default_value(v);
                    });

                }

                // When border-x-style change
                if(id.indexOf("border-") != -1 && id.indexOf("-style") != -1 && id != 'border-style'){

                    // update default value for;
                    set_default_value("border-style");

                }

                // When border-x-style change
                if(id.indexOf("border-") != -1 && id.indexOf("-color") != -1 && id != 'border-color'){

                    // update default value for;
                    set_default_value("border-color");

                }

                // When border-x-style change
                if(id.indexOf("border-") != -1 && id.indexOf("-width") != -1 && id != 'border-width'){

                    // update default value for;
                    set_default_value("border-width");

                }


                // also using in bottom.
                var duration,delay;

                // update multiple duration and delay by animation name
                if(id == 'animation-name' && $(".yp-animate-manager-active").length === 0){

                    var animCount = 1;

                    if(value == 'none' || value == 'disable'){
                        animCount = 0;
                    }

                    if(value.indexOf(",") != -1){
                        animCount = value.split(",").length;
                    }

                    // DURATION
                    var singleDuration = get_selected_element().css("animationDuration");
                    var singleDurationAr = singleDuration.split(",");
                    var durationCount = 1;

                    if(singleDuration.indexOf(",") != -1){
                        durationCount = singleDurationAr.length;
                    }

                    if(durationCount != animCount){

                        singleDuration = [];
                        for(var i = 0; i < animCount; i++){
                            singleDuration.push(singleDurationAr[i]);
                        }

                        singleDuration = singleDuration.toString().replace(/\s+/g,'');

                        if(animCount <= 1){$("#animation-duration-group").removeClass("hidden-option");}
                        insert_rule(null, "animation-duration", singleDuration, '');

                    }

                    // DELAY
                    var singleDelay = get_selected_element().css("animationDelay");
                    var singleDelayAr = singleDelay.split(",");
                    var delayCount = 1;

                    if(singleDelay.indexOf(",") != -1){
                        delayCount = singleDelayAr.length;
                    }

                    if(delayCount != animCount){

                        singleDelay = [];
                        for(i = 0; i < animCount; i++){
                            singleDelay.push(singleDelayAr[i]);
                        }

                        singleDelay = singleDelay.toString().replace(/\s+/g,'');

                        if(animCount <= 1){$("#animation-iteration-count-group").removeClass("hidden-option");}
                        insert_rule(null, "animation-delay", singleDelay, '');

                    }

                }



                // Animation name play.
                if (id == 'animation-name' || id == 'animation-play' || id == 'animation-iteration' || id == 'animation-duration') {

                    if($(".yp-animate-manager-active").length === 0 && value != 'none'){

                        duration = get_selected_element().css("animationDuration");
                        delay = get_selected_element().css("animationDelay");

                        // Getting right time delay if have multiple animations.
                        var newDelay = get_multiple_delay(duration,delay);

                        if(newDelay !== false){
                            delay = parseFloat(newDelay);
                        }else if(isUndefined(delay)){
                            delay = 0;
                        }else{
                            delay = parseFloat(duration_ms(delay)); // delay
                        }

                        if (isUndefined(duration)) {
                            duration = 1000;
                        } else {
                            duration = parseFloat(duration_ms(duration)); // duration
                        }

                        var waitDelay = delay + duration;

                        if(waitDelay === 0){
                            waitDelay = 1000;
                        }

                        waitDelay = waitDelay + 100;

                        // Add class.
                        body.addClass("yp-hide-borders-now yp-force-hide-select-ui");
                        
                        clear_animation_timer();

                        window.animationTimer1 = setTimeout(function(){

                            // remove class.
                            body.removeClass("yp-hide-borders-now yp-force-hide-select-ui");

                            element_animation_end();

                            // Update.
                            draw();

                        }, waitDelay);

                    }

                }

                // If has style attr. // USE IMPORTANT
                if (css != 'top' && css != 'bottom' && css != 'left' && css != 'right' && css != 'height' && css != 'width') {

                    var element = get_selected_element();

                    if (isDefined(element.attr("style"))) {

                        // if more then one rule
                        if ($.trim(element.attr("style")).split(";").length > 0) {

                            var obj = element.attr("style").split(";");

                            for (var item in obj) {
                                if ($.trim(obj[item].split(":")[0]) == css) {

                                    // Use important.
                                    if (css != 'position' && css != 'animation-fill-mode') {
                                        force_insert_rule(selector, id, value, prefix, size);
                                        
                                        return false;
                                    }

                                }
                            }

                        } else {
                            if ($.trim(element.attr("style")).split(":")[0] == css) {

                               if (css != 'position' && css != 'animation-fill-mode') {
                                    force_insert_rule(selector, id, value, prefix, size);
                                    
                                    return false;
                                }

                            }
                        }

                    }
                }

                // Background image fix.
                if (id == 'background-image' && value != 'disable' && value != 'none' && value != '') {

                    // If not has a url
                    if (value.replace(/\s/g, "") == 'url()') {
                        value = 'disable';
                    }

                    // disable if not a gradient or non valid URL
                    if(value.indexOf("//") == -1 && value.indexOf("linear-gradient(") == -1){
                        value = 'disable';
                    }

                }

                // List Style image fix.
                if (id == 'list-style-image' && value != 'disable' && value != 'none' && value != '') {

                    // If not has a url
                    if (value.replace(/\s/g, "") == 'url()') {
                        value = 'disable';
                    }

                    // disable if URL is not valid
                    if(value.indexOf("//") == -1){
                        value = 'disable';
                    }

                }

                // adding automatic relative.
                if (id == 'top' || id == 'bottom' || id == 'left' || id == 'right') {

                    setTimeout(function() {
                        if ($("#position-static").parent().hasClass("active") || $("#position-relative").parent().hasClass("active")){
                            $("#position-relative").trigger("click");
                        }
                    }, 5);

                }

                // Background color
                if (id == 'background-color') {
                    if ($("#yp-background-image").val() != 'none' && $("#yp-background-image").val() != '') {
                        force_insert_rule(selector, id, value, prefix, size);
                        
                        return false;
                    }
                }

                if (id == 'animation-name' && $(".yp-animate-manager-active").length === 0){
                    set_default_value('animation-duration');
                    set_default_value('animation-delay');
                    set_default_value('animation-fill-mode');
                }

                // Animation Name Settings. (Don't playing while insert by CSS editor or animation manager)
                if (body.hasClass("process-by-code-editor") === false && $(".yp-animate-manager-active").length === 0) {

                    if (id == 'animation-name' || id == 'animation-duration' || id == 'animation-delay') {

                        if(mainBody.hasClass("yp-animate-manager-mode") === false){
                            selector = selector.replace(/\.yp_onscreen/g, '').replace(/\.yp_hover/g, '').replace(/\.yp_focus/g, '').replace(/\.yp_click/g, '');
                        }

                        var play = '';
                        if(mainBody.hasClass("yp-animate-manager-mode") === false){
                            play = "." + $("#yp-animation-play").val();
                        }

                        // Getting array
                        var selectorNew = selector.split(":");

                        // Check if there have : 
                        if(selectorNew.length > 0){

                            // Getting all prev selectors until last :
                            var prevSelectors = '';

                            for(var y = 0; y < selectorNew.length-1; y++){
                                prevSelectors = prevSelectors + selectorNew[y];
                            }

                            if (selectorNew[selectorNew.length-1] == 'hover' || selectorNew[selectorNew.length-1] == 'focus') {
                                selector = prevSelectors + play + ":" + selectorNew[selectorNew.length-1];
                            }else{

                                selector = selector + play;

                            }

                        }else{ // default

                            selector = selector + play;

                        }

                    }

                }

                // Selection settings.
                var selection = $('body').attr('data-yp-selector');

                if (isUndefined(selection)) {

                    selection = '';

                } else {

                    if(!mainBody.hasClass("yp-processing-now") && selector.indexOf("yp_onscreen") == -1 && selector.indexOf("yp_click") == -1 && selector.indexOf("yp_focus") == -1 && selector.indexOf("yp_hover") == -1 && id != 'animation-play'){

                        selector = add_class_to_body(selector, 'yp-selector-' + selection.replace(':', ''));

                        selector = selector.replace('body.yp-selector-' + selection.replace(':', '') + ' body.yp-selector-' + selection.replace(':', '') + ' ', 'body.yp-selector-' + selection.replace(':', '') + ' ');

                    }

                }


                // Responsive Settings
                var mediaBefore = create_media_query_before(css);

                if(mainBody.hasClass("yp-adding-auto-media")){
                    size = space_cleaner(mediaBefore.replace("@media","").replace("{",""));
                }

                var mediaAfter = create_media_query_after();

                if(isDefined(size) && body.hasClass("yp-animate-manager-active") && is_responsive_mod()){
                    mediaBefore = "@media " + size + "{";
                }


                // Delete same data.
                var exStyle = iframe.find("." + get_id(selector) + '-' + id + '-style[data-size-mode="' + size + '"]');
                if (exStyle.length > 0){
                    if (escape_data_value(exStyle.html()) == value) {
                        return false;
                    } else {
                        exStyle.remove(); // else remove.
                    }
                }

                // Delete same data for anim.
                if (is_animate_creator()) {
                    exStyle = iframe.find(".yp-anim-scenes ." + $('body').attr('data-anim-scene') + " .scenes-" + get_id(id) + "-style");
                    if (exStyle.length > 0) {
                        if (escape_data_value(exStyle.html()) == value) {
                            return false;
                        } else {
                            exStyle.remove(); // else remove.
                        }
                    }
                }

                // Delete same data for filter and transform -webkit- prefix.
                exStyle = iframe.find("." + get_id(selector) + '-' + "-webkit-" + id + '-style[data-size-mode="' + size + '"]');
                if (exStyle.length > 0) {
                    if (escape_data_value(exStyle.html()) == value) {
                        return false;
                    } else {
                        exStyle.remove(); // else remove.
                    }
                }

                // Delete same data for filter and transform -webkit- prefix on anim scenes.
                if (is_animate_creator()) {
                    exStyle = iframe.find(".yp-anim-scenes ." + $('body').attr('data-anim-scene') + " .scenes-webkit" + get_id(id) + "-style");
                    if (exStyle.length > 0) {
                        if (escape_data_value(exStyle.html()) == value) {
                            return false;
                        } else {
                            exStyle.remove(); // else remove.
                        }
                    }
                }

                // Filter
                if (id == 'filter' || id == 'transform') {

                    if (value != 'disable' && value != '' && value != 'undefined' && value !== null) {
                        insert_rule(selector, "-webkit-" + id, value, prefix, size);
                    }

                }

                // Append style area.
                if (the_editor_data().length <= 0) {
                    iframeBody.append("<div class='yp-styles-area'></div>");
                }

                // No px em etc for this options.
                if (id == 'z-index' || id == 'opacity' || id == 'background-parallax-speed' || id == 'background-parallax-x' || id == 'blur-filter' || id == 'grayscale-filter' || id == 'brightness-filter' || id == 'contrast-filter' || id == 'hue-rotate-filter' || id == 'saturate-filter' || id == 'sepia-filter' || id.indexOf("-transform") != -1) {
                    if (id != 'text-transform' && id != '-webkit-transform') {
                        value = number_filter(value);
                        prefix = '';
                    }
                }

                // Filter Default options.
                if (id == 'blur-filter' || id == 'grayscale-filter' || id == 'brightness-filter' || id == 'contrast-filter' || id == 'hue-rotate-filter' || id == 'saturate-filter' || id == 'sepia-filter') {

                    var filterData = filter_generator(true);

                    insert_rule(selector, 'filter', filterData, '', size);
                    
                    return false;

                }
                // Filter options end

                // Transform Settings
                if (id.indexOf("-transform") != -1 && id != 'text-transform' && id != '-webkit-transform') {

                    body.addClass("yp-has-transform");

                    var translateData = transform_generator(true);

                    insert_rule(selector, 'transform', translateData, '', size);

                    if(translateData == 'none' || translateData == 'disable'){
                        body.removeClass("yp-has-transform");
                    }
                    
                    return false;

                }
                // Transform options end


                // border-type is not a CSS Rule.
                if(id == 'border-type'){
                    return false;
                }


                // Box Shadow
                if (id == 'box-shadow-inset' || id == 'box-shadow-color' || id == 'box-shadow-vertical' || id == 'box-shadow-blur-radius' || id == 'box-shadow-spread' || id == 'box-shadow-horizontal') {

                    var shadowData = box_shadow_generator();

                    insert_rule(selector, 'box-shadow', shadowData, '', size);
                    
                    return false;

                }
                // Box shadow options end


                // Animation options
                if (id == 'animation-play') {

                    iframe.find("[data-style][data-size-mode='"+size+"']").each(function(){

                        // onscreen
                        if ($(this).data("style") == get_id(selector + ".yp_onscreen")) {
                            $(this).remove();
                        }

                        // hover
                        if ($(this).data("style") == get_id(selector + ".yp_hover")) {
                            $(this).remove();
                        }

                        // click
                        if ($(this).data("style") == get_id(selector + ".yp_click")) {
                            $(this).remove();
                        }

                        // click
                        if ($(this).data("style") == get_id(selector + ".yp_focus")) {
                            $(this).remove();
                        }

                    });

                    insert_rule(selector, 'animation-name', $("#yp-animation-name").val(), prefix, size);
                    
                    return false;

                }

                // Animation name
                if (id == 'animation-name'){

                    // is selected, valid value.
                    if (value != 'disable' && value != 'none' && is_content_selected() && body.hasClass("yp-animate-manager-active") === false){

                        // add "s" if is one animate
                        if($("#animation-duration-group").hasClass("hidden-option") === false && $("#animation-delay-group").hasClass("hidden-option") === false){

                            // Get duration from CSS
                            duration = get_selected_element().css("animationDuration").replace(/[^0-9.,]/g, '');

                            // Get delay from CSS
                            delay = get_selected_element().css("animationDelay").replace(/[^0-9.,]/g, '');

                            // If selected element;
                            if (get_foundable_query(selector,false,true,true) == get_current_selector().trim()){

                                // Duration
                                if(duration == "0"){
                                    duration = 1;
                                }

                                // update with s prefix
                                insert_rule(selector, 'animation-duration', duration + 's', prefix, size);


                                // Delay
                                if (delay < 0) {
                                    delay = 0;
                                }

                                // update with s prefix
                                insert_rule(selector, 'animation-delay', delay + 's', prefix, size);

                            }

                        }


                        // Get fill mode from CSS
                        var fillMode = get_selected_element().css("animationFillMode");

                        // FillMode
                        if (fillMode == null || fillMode == 'none') {
                            fillMode = 'both';
                        }

                        insert_rule(get_current_selector(), 'animation-fill-mode', fillMode, prefix, size);

                    }

                    if (value == 'bounce') {

                        if (value != 'disable' && value != 'none') {
                            insert_rule(selector, 'transform-origin', 'center bottom', prefix, size);
                        } else {
                            insert_rule(selector, 'transform-origin', value, prefix, size);
                        }

                    } else if (value == 'swing') {

                        if (value != 'disable' && value != 'none') {
                            insert_rule(selector, 'transform-origin', 'top center', prefix, size);
                        } else {
                            insert_rule(selector, 'transform-origin', value, prefix, size);
                        }

                    } else if (value == 'jello') {

                        if (value != 'disable' && value != 'none') {
                            insert_rule(selector, 'transform-origin', 'center', prefix, size);
                        } else {
                            insert_rule(selector, 'transform-origin', value, prefix, size);
                        }

                    } else {
                        insert_rule(selector, 'transform-origin', 'disable', prefix, size);
                    }

                    if (value == 'flipInX') {
                        insert_rule(selector, 'backface-visibility', 'visible', prefix, size);
                    } else {
                        insert_rule(selector, 'backface-visibility', 'disable', prefix, size);
                    }

                }


                // Checking.
                if (value == 'disable' || value == '' || value == 'undefined' || value === null) {
                    
                    return false;
                }

                // New Value
                var current = value + prefix;

                // Clean.
                current = current.replace(/\s+?!important/g,'').replace(/\;$/g,'');

                // Append default value.
                if (get_id(selector) != '') {

                    var dpt = ':';

                    if (is_animate_creator() === true && id != 'position') {

                        iframe.find("." + get_id(body.attr("data-anim-scene") + css)).remove();

                        iframe.find(".yp-anim-scenes ." + body.attr("data-anim-scene") + "").append('<style data-rule="' + css + '" class="style-' + body.attr("data-anim-scene") + ' scenes-' + get_id(css) + '-style">' + selector + '{' + css + dpt + current + '}</style>');

                        // update next scenes
                        var scene = 0;
                        var styleEl;
                        var selectorT = selector;
                        for(var n = parseInt(body.attr("data-anim-scene").replace("scene-",""))+1; n <= 6; n++){

                            // current scene
                            scene = "scene-"+n;

                            // get style if have
                            styleEl = iframe.find(".yp-anim-scenes ." + scene + " .scenes-" + get_id(css) + "-style");

                            // If not has this style or has but this generated by same method
                            if(styleEl.length == 0 || styleEl.hasClass("dynamic-generated-scene")){

                                selectorT = selector.replace(/body\.yp-scene-[0-9]/g, 'body.yp-scene-' + n);

                                // Append
                                iframe.find(".yp-anim-scenes ." + scene + "").append('<style data-rule="' + css + '" class="dynamic-generated-scene style-' + scene + ' scenes-' + get_id(css) + '-style">' + selectorT + '{' + css + dpt + current + '}</style>');

                            }

                        }


                    } else {

                        the_editor_data().append('<style data-rule="' + css + '" data-size-mode="' + size + '" data-style="' + get_id(selector) + '" class="' + get_id(selector) + '-' + id + '-style yp_current_styles">' + mediaBefore + '' + '' + selector + '{' + css + dpt + current + '}' + '' + mediaAfter + '</style>');

                        resort_style_data_positions();

                    }

                    draw();

                }

                // No need to important for text-shadow.
                if (id == 'text-shadow' || id == 'perspective') {
                    return false;
                }

                // No need to important on keyframes
                if(mainBody.hasClass("yp-anim-creator")){
                    return false;
                }

                // stop if applied CSS worked.
                if(is_css_work(id,css,current)){
                    return false;
                }

                // Use important.
                force_insert_rule(selector, id, value, prefix, size);
                

            }



            /* ---------------------------------------------------- */
            /* Checks if CSS worked or not                          */
            /* ---------------------------------------------------- */
            function is_css_work(id, css, current){

                // Worked? 
                var worked = false;

                // Convert for check important need.
                if(css == 'border-width'){
                    css = 'border-top-width';
                }else if(css == 'border-style'){
                    css = 'border-top-style';
                }else if(css == 'border-color'){
                    css = 'border-top-color';
                }

                // Variables
                var cumputedValue, color, shadow, fullWidth;

                // Each all selected element and check if need to use important.
                iframe.find(".yp-selected,.yp-selected-others").each(function(){

                    // Current Value
                    cumputedValue = $(this).css(css);

                    // If current value not undefined
                    if (isDefined(cumputedValue)) {

                        // Convert Hex colors to RGB format
                        cumputedValue = cumputedValue.replace(/#[0-9A-Fa-f]{6}/g, function(v){return hex_to_rgb(v);}).replace(/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)/g, function(v) {return v.replace(/\s/g,'');});

                        if(css == 'background-image' || css == 'list-style-image'){
                            cumputedValue = cumputedValue.replace(/\'/g,'').replace(/\"/g,'');
                        }

                        // move color to the end
                        if (cumputedValue.indexOf("rgb") != -1 && id == 'box-shadow') {
                            color = cumputedValue.match(/rgb(.*?)\((.*?)\)/g).toString();
                            shadow = cumputedValue.replace(/rgb(.*?)\((.*?)\)/g, "");
                            cumputedValue = shadow + " " + color;
                        }

                        if(css == 'box-shadow'){
                            cumputedValue = cumputedValue.replace('inset','');
                            cumputedValue = cumputedValue.replace(/\s+/g, ' ');
                        }

                        // Clean
                        cumputedValue = $.trim(cumputedValue);

                    }

                    // Replace inset
                    if(css == 'box-shadow'){
                        current = current.replace('inset','');
                    }

                    // Convert Hex colors to RGB format
                    current = current.replace(/#[0-9A-Fa-f]{6}/g, function(v) {return hex_to_rgb(v);}).replace(/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)/g, function(v) {return v.replace(/\s/g,'');});

                    // replace whitespaces
                    if(id == 'animation-delay' || id == 'animation-duration'){
                        cumputedValue = cumputedValue.replace(/\s/g,'');
                        current = current.replace(/\s/g,'');
                    }

                    // Clean
                    current = $.trim(current);

                    // If date mean same thing: stop.
                    if (get_basic_id(current) == 'length' && get_basic_id(cumputedValue) == 'autoauto') {
                        worked = true;
                    }

                    if (get_basic_id(current) == 'inherit' && get_basic_id(cumputedValue) == 'normal') {
                        worked = true;
                    }

                    // No need important for parallax and filter.
                    if (id == 'background-parallax' || id == 'background-parallax-x' || id == 'background-parallax-speed' || id == 'filter' || id == '-webkit-filter' || id == '-webkit-transform') {
                        worked = true;
                    }

                    if (isUndefined(cumputedValue)) {
                        worked = true;
                    }

                    // if value is same, stop.
                    if (current == cumputedValue && iframe.find(".yp-selected-others").length === 0) {
                        worked = true;
                    }

                    // font-family bug.
                    if ((current.replace(/'/g, '"').replace(/, /g, ",")) == cumputedValue) {
                        worked = true;
                    }

                    // background position fix.
                    if (id == 'background-position') {

                        if (current == 'lefttop' && cumputedValue == '0%0%') {
                            worked = true;
                        }

                        if (current == 'leftcenter' && cumputedValue == '0%50%') {
                            worked = true;
                        }

                        if (current == 'leftbottom' && cumputedValue == '0%100%') {
                            worked = true;
                        }

                        if (current == 'righttop' && cumputedValue == '100%0%') {
                            worked = true;
                        }

                        if (current == 'rightcenter' && cumputedValue == '100%50%') {
                            worked = true;
                        }

                        if (current == 'rightbottom' && cumputedValue == '100%100%') {
                            worked = true;
                        }

                        if (current == 'centertop' && cumputedValue == '50%0%') {
                            worked = true;
                        }

                        if (current == 'centercenter' && cumputedValue == '50%50%') {
                            worked = true;
                        }

                        if (current == 'centercenter' && cumputedValue == '50%50%') {
                            worked = true;
                        }

                        if (current == 'centerbottom' && cumputedValue == '50%100%') {
                            worked = true;
                        }

                        if (current == 'centerbottom' && cumputedValue == '50%100%') {
                            worked = true;
                        }

                    }

                    // Digital
                    if (id == 'width' || id == 'min-width' || id == 'max-width' || id == 'height' || id == 'min-height' || id == 'max-height' || id == 'font-size' || id == 'line-height' || id == 'letter-spacing' || id == 'word-spacing' || id == 'margin-top' || id == 'margin-left' || id == 'margin-right' || id == 'margin-bottom' || id == 'padding-top' || id == 'padding-left' || id == 'padding-right' || id == 'padding-bottom' || id == 'border-left-width' || id == 'border-right-width' || id == 'border-top-width' || id == 'border-bottom-width' || id == 'border-top-left-radius' || id == 'border-top-right-radius' || id == 'border-bottom-left-radius' || id == 'border-bottom-right-radius' || id == 'opacity' || id == 'border-width' || id == 'z-index' || id == 'top' || id == 'left' || id == 'right' || id == 'bottom') {

                        // If value is similar.
                        if (number_filter(current.replace(/\.00$/g, "").replace(/\.0$/g, "")) !== '' && number_filter(current.replace(/\.00$/g, "").replace(/\.0$/g, "")) == number_filter(cumputedValue.replace(/\.00$/g, "").replace(/\.0$/g, ""))){
                            worked = true;
                        }

                        if(id == 'min-height' && mainBody.hasClass("yp-element-resizing")){
                            worked = true;
                        }

                        if((Math.round(parseFloat(current) * 100) / 100) == (Math.round(parseFloat(cumputedValue) * 100) / 100)){
                            worked = true;
                        }

                        // Browser always return in px format, custom check for %, em.
                        if (current.indexOf("%") != -1 && cumputedValue.indexOf("px") != -1) {

                            get_selected_element().addClass("yp-full-width");
                            fullWidth = iframe.find(".yp-full-width").css("width");
                            get_selected_element().removeClass("yp-full-width");

                            if ((parseInt(cumputedValue) - (parseInt(fullWidth) * parseFloat(current) / 100)) < 1) {
                                worked = true;
                            }

                        }

                        // smart important not available for em format
                        if (current.indexOf("em") != -1 && cumputedValue.indexOf("px") != -1) {
                            worked = true;
                        }

                    }

                    // not use important, if browser return value with matrix.
                    if (id == "transform") {
                        if (cumputedValue.indexOf("matrix") != -1) {
                            worked = true;
                        }
                    }

                    if(id == 'animation-fill-mode' || id == 'transform-origin'){
                        worked = true;
                    }

                    // not use important, If value is inherit.
                    if (current == "inherit" || current == "auto") {
                        worked = true;
                    }

                    // not worked? stop each.
                    if(worked === false){
                        return false;
                    }

                }); // Each end.

                // Don't use important if animation manager active
                if(mainBody.hasClass("yp-animate-manager-mode")){
                    worked = true;
                }

                return worked;

            }



            /* ---------------------------------------------------- */
            /* Hide blue borders on options click section           */
            /* ---------------------------------------------------- */
            $(document).on("click",".yp-this-content",function(e){
                if (e.originalEvent) {
                    hide_frame_ui(200);
                }
            });


            /* ---------------------------------------------------- */
            /* Setup Slider Option                                  */
            /* ---------------------------------------------------- */
            function slider_option(id, decimals, pxv, pcv, emv) {

                var thisContent = $("#" + id + "-group").parent(".yp-this-content");

                // Set Maximum and minimum values for custom prefixs.
                $("#" + id + "-group").data("px-range", pxv);
                $("#" + id + "-group").data("pc-range", pcv);
                $("#" + id + "-group").data("em-range", emv);

                // Default PX
                var range = $("#" + id + "-group").data("px-range").split(",");

                // Update PX.
                if ($("#" + id + "-group .yp-after-prefix").val() == 'px') {
                    range = $("#" + id + "-group").data("px-range").split(",");
                }

                // Update %.
                if ($("#" + id + "-group .yp-after-prefix").val() == '%') {
                    range = $("#" + id + "-group").data("pc-range").split(",");
                }

                // Update EM.
                if ($("#" + id + "-group .yp-after-prefix").val() == 'em') {
                    range = $("#" + id + "-group").data("em-range").split(",");
                }

                // Update s.
                if ($("#" + id + "-group .yp-after-prefix").val() == 's') {
                    range = $("#" + id + "-group").data("em-range").split(",");
                }

                // Setup slider.
                $('#yp-' + id).wqNoUiSlider({

                    start: [0],

                    range: {
                        'min': parseInt(range[0]),
                        'max': parseInt(range[1])
                    },

                    format: wNumb({
                        mark: '.',
                        decimals: decimals
                    })

                }).on('change', function() {

                    $(".fake-layer").remove();

                    var lock = thisContent.find(".lock-btn.active").length;
                    var lockedIdArray = [];

                    if(lock){

                        thisContent.find(".yp-option-group").each(function(){
                            lockedIdArray.push($(this).attr("data-css"));
                        });

                        var val = $(this).val();

                        for(var y = 0;y < lockedIdArray.length; y++){
                            $('#yp-' + lockedIdArray[y]).val(val);
                            $('#' + lockedIdArray[y] + '-after').trigger("keyup");
                            slide_action($("#yp-" + lockedIdArray[y]), lockedIdArray[y], true, false);
                        }

                        option_change();

                    }else{
                        slide_action($(this), id, true, true);
                    }

                }).on('slide', function() {

                    // Be sure its hidden.
                    hide_frame_ui(200);

                    var lock = thisContent.find(".lock-btn.active").length;
                    var lockedIdArray = [];

                    if(lock){
                        thisContent.find(".yp-option-group").each(function(){
                            lockedIdArray.push($(this).attr("data-css"));
                        });
                    }

                    // Get val
                    var val = $(this).val();
                    var prefix = $('#' + id+"-after").val();
                    var y;

                    val = Number((parseFloat(val)).toFixed(2));
                    var left = $("#" + id + "-group").find(".wqNoUi-origin").css("left");

                    // Update the input.
                    if(lock === 0){
                        $('#' + id + '-value').val(val);
                    }else{
                        for(y = 0;y < lockedIdArray.length; y++){
                            $('#' + lockedIdArray[y] + '-value').val(val);
                            $('#' + lockedIdArray[y] + '-after').val(prefix);
                            $('#' + lockedIdArray[y] + '-group').find(".wqNoUi-origin").css("left",left);
                        }
                    }


                    // some rules not support live css, so we check some rules.
                    if (id != 'background-parallax-speed' && id != 'background-parallax-x') {

                        prefix = $(this).parent().find("#" + id + "-after").val();

                        // Standard.
                        if(lock === 0){
                            delete_live_css(id, false);
                            insert_live_css(id, val + prefix, false);
                        }else{
                            for(y = 0;y < lockedIdArray.length; y++){
                                delete_live_css(lockedIdArray[y], false);
                                insert_live_css(lockedIdArray[y], val + prefix, false);
                            }
                        }


                    } else { // for make it as live, inserting css to data.
                        slide_action($(this), id, true, true);
                    }

                    if($(".fake-layer").length === 0){
                        mainBody.append("<div class='fake-layer'></div>");
                    }

                });

            }


            /* ---------------------------------------------------- */
            /* Slider Event                                         */
            /* ---------------------------------------------------- */
            function slide_action(element, id, $slider,changed) {

                var css = element.parent().parent().data("css");
                element.parent().parent().addClass("reset-enable");

                var val;

                if ($slider === true) {

                    val = element.val();

                    // If active, disable it.
                    element.parent().parent().find(".yp-btn-action.active").trigger("click");

                } else {

                    val = element.parent().find("#" + css + "-value").val();

                }

                var css_after = element.parent().find("#" + css + "-after").val();

                // Set for demo
                insert_rule(null, id, val, css_after);

                // Option Changed
                if(changed){
                    option_change();
                }

            }


            /* ---------------------------------------------------- */
            /* ESCAPE                                               */
            /* ---------------------------------------------------- */
            function escape(s) {
                return ('' + s) /* Forces the conversion to string. */
                    .replace(/\\/g, '\\\\') /* This MUST be the 1st replacement. */
                    .replace(/\t/g, '\\t') /* These 2 replacements protect whitespaces. */
                    .replace(/\n/g, '\\n')
                    .replace(/\u00A0/g, '\\u00A0') /* Useful but not absolutely necessary. */
                    .replace(/&/g, '\\x26') /* These 5 replacements protect from HTML/XML. */
                    .replace(/'/g, '\\x27')
                    .replace(/"/g, '\\x22')
                    .replace(/</g, '\\x3C')
                    .replace(/>/g, '\\x3E');
            }


            /* ---------------------------------------------------- */
            /* Border Type: Update View                             */
            /* ---------------------------------------------------- */
            $("#yp-border-type .yp-radio").on("click", function(){

                var value = $("#yp-border-type .yp-radio.active input").val();

                $(".yp-border-all-section,.yp-border-top-section,.yp-border-right-section,.yp-border-bottom-section,.yp-border-left-section").hide();

                $(".yp-border-"+value+"-section").show();

                insert_default_options();

            });


            /* ---------------------------------------------------- */
            /* Getting radio value                                  */
            /* ---------------------------------------------------- */
            function radio_value(the_id, $n, data) {

                var id_prt = the_id.parent().parent();

                // for none btn
                id_prt.find(".yp-btn-action.active").trigger("click");

                if (data == id_prt.find(".yp-none-btn").text()) {
                    id_prt.find(".yp-none-btn").trigger("click");
                }

                if (data == 'auto auto') {
                    data = 'auto';
                }

                if (data != '' && typeof data != 'undefined') {

                    if (data.match(/\bauto\b/g)) {
                        data = 'auto';
                    }

                    if (data.match(/\bnone\b/g)) {
                        data = 'none';
                    }

                    if ($("input[name=" + $n + "][value=" + escape(data) + "]").length > 0) {

                        the_id.find(".active").removeClass("active");

                        $("input[name=" + $n + "][value=" + escape(data) + "]").prop('checked', true).parent().addClass("active");

                    } else {

                        the_id.find(".active").removeClass("active");

                        // Disable all.
                        $("input[name=" + $n + "]").each(function() {

                            $(this).prop('checked', false);

                        });

                        id_prt.find(".yp-none-btn:not(.active)").trigger("click");

                    }

                }

            }

            /* ---------------------------------------------------- */
            /* Radio Setup                                          */
            /* ---------------------------------------------------- */
            function radio_option(id) {

                $("#yp-" + id + " label").on('click', function() {

                    if($(".position-option.active").length === 0){
                        if($(this).parent().hasClass("active")){
                            return false;
                        }
                    }

                    // Disable none.
                    $(this).parent().parent().parent().parent().find(".yp-btn-action.active").removeClass("active");
                    $(this).parent().parent().parent().parent().addClass("reset-enable").css("opacity", 1);

                    $("#yp-" + id).find(".active").removeClass("active");

                    $(this).parent().addClass("active");

                    $("#" + $(this).attr("data-for")).prop('checked', true);

                    var val = $("input[name=" + id + "]:checked").val();

                    // Set for demo
                    insert_rule(null, id, val, '');

                    // Option Changed
                    option_change();

                });

            }

            /* ---------------------------------------------------- */
            /* Check if is safe font family.                        */
            /* ---------------------------------------------------- */
            function is_safe_font(a) {

                if(isUndefined(a)){
                    return false;
                }

                var regex = /\barial\b|\barial black\b|\barial narrow\b|\barial rounded mt bold\b|\bavant garde\b|\bcalibri\b|\bcandara\b|\bcentury gothic\b|\bfranklin gothic medium\b|\bgeneva\b|\bfutura\b|\bgill sans\b|\bhelvetica neue\b|\bimpact\b|\blucida grande\b|\boptima\b|\bsegoe ui\b|\btahoma\b|\btrebuchet ms\b|\bverdana\b|\bbig caslon\b|\bbodoni mt\b|\bbook antiqua\b|\bcalisto mt\b|\bcambria\b|\bdidot\b|\bgaramond\b|\bgeorgia\b|\bgoudy old style\b|\bhoefler text\b|\blucida bright\b|\bpalatino\b|\bperpetua\b|\brockwell\b|\brockwell extra bold\b|\bbaskerville\b|\btimes new roman\b|\bconsolas\b|\bcourier new\b|\blucida console\b|\bhelveticaneue\b/;

                var data = a.toLowerCase();

                return regex.test(data);

            }


            /* ---------------------------------------------------- */
            /* Warning System                                       */
            /* ---------------------------------------------------- */

            // Margin : display inline / negative margin warnings
            $("#margin-left-group,#margin-right-group,#margin-top-group,#margin-bottom-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // Show display warning
                if (get_selected_element().css("display") == "inline" || get_selected_element().css("display") == "table-cell") {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_display_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                // Show negative margin value warning if not responsive
                } else if($("#"+$(this).attr("data-css")+"-value").val() < 0) {

                    // don't show if orginal value
                    if($(this).hasClass("reset-enable") == false && $(this).find(".wqNoUi-active").length == 0){
                        return true;
                    }

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_negative_margin_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                }

            });


            // List: disable list style image to use list style type
            $("#list-style-type-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // list stype image has value, and none button not active
                if($("#yp-list-style-image").val().length > 12 && $("#list-style-image-group .yp-none-btn.active").length == 0){

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_list_notice1,
                        trigger: 'hover',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                }

            });


            // List: please select a list item to edit
            $("#list-style-position-group,#list-style-image-group,#list-style-type-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                if($(this).attr("id") == 'list-style-type-group'){

                    // Destroy if there not have another warning.
                    if($("#yp-list-style-image .yp-none-btn.active").length == 1){
                        $(this).popover("destroy");
                    }

                }else{
                    $(this).popover("destroy");
                }

                // Get selected element tag
                var tag = get_selected_element()[0].nodeName.toLowerCase();

                // Show tag warning
                if (tag != 'li' && tag != 'ul'){

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_list_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                }

            });
            
            
            // Width / Padding : display inline warning
            $("#scale-transform-group,#rotate-transform-group,#rotatex-transform-group,#rotatey-transform-group,#rotatez-transform-group,#translate-x-transform-group,#translate-y-transform-group,#skew-x-transform-group,#skew-y-transform-group,#perspective-group,#padding-left-group,#padding-right-group,#padding-top-group,#padding-bottom-group,#width-group,#height-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // Display warning
                if (get_selected_element().css("display") == "inline") {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_display_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                }

            });


            /* ---------------------------------------------------- */
            /* Show responsive notice one time                      */
            /* ---------------------------------------------------- */
            $(".yp-responsive-btn").click(function(){

                var resBtn = $(".yp-responsive-btn");

                // Opened && not showed before
                if(resBtn.hasClass("active") == false && mainBody.hasClass("yp-responsive-notice-showed") == false){

                    // 500ms wait
                    setTimeout(function(){

                        if(resBtn.hasClass("active") == false){

                            // Destory
                            $('.responsive-right-handle').tooltip("destroy");

                            // Install
                            $('.responsive-right-handle').tooltip({
                                title: l18_responsive_notice,
                                animation: true,
                                placement: 'right',
                                trigger: 'manual',
                                container: "body",
                                html: true
                            }).tooltip("show");

                            // Added: showed class
                            mainBody.addClass("yp-responsive-notice-showed");

                        }

                    },500);

                    // Hide after 6s
                    setTimeout(function(){
                        $('.responsive-right-handle').tooltip("hide");
                    },5000);

                }else{

                    // Hide
                    $('.responsive-right-handle').tooltip("hide");

                }

            });            


            /* ---------------------------------------------------- */
            /* position: top left right bottom = 120 > not good.    */
            /* ---------------------------------------------------- */
            $("#left-group,#right-group,#top-group,#bottom-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Show notice just for desktop mode.
                if(is_responsive_mod()){
                    return true;
                }

                // don't show if orginal value
                if($(this).hasClass("reset-enable") == false && $(this).find(".wqNoUi-active").length == 0){
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // high value warning
                if($("#"+$(this).attr("id").replace("group","value")).val() >= 120) {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_high_position_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                }

            });

            
            /* ---------------------------------------------------- */
            /* Fixed and absolute not recommend                     */
            /* ---------------------------------------------------- */
            $("#position-group").on("mousemove click", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // Show notice just for desktop mode.
                if(is_responsive_mod()){
                    return true;
                }

                // don't show if orginal value
                if($(this).hasClass("reset-enable") == false && $(this).find(".wqNoUi-active").length == 0){
                    return true;
                }


                // fixed warning
                if($(".yp-radio.active #position-fixed").length > 0) {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_fixed_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                // absolute warning
                } else if($(".yp-radio.active #position-absolute").length > 0) {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_absolute_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                }

            });


            /* ---------------------------------------------------- */
            /* Parallax feature need to a background image          */
            /* ---------------------------------------------------- */
            $(".background-parallax-div,#background-size-group,#background-repeat-group,#background-blend-mode-group,#background-attachment-group,#background-position-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // show warning if not have a blackground image
                if ($("#yp-background-image").val() == '' || $("#background-image-group .yp-none-btn.active").length === 1) {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_bg_img_notice_two,
                        trigger: 'hover',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                }

            });


            /* ---------------------------------------------------- */
            /* show a warning when open animation section.          */
            /* ---------------------------------------------------- */ 
            $(".animation-option").on("click", function(e){

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // El
                var t = $("#animation-name-group");

                // stop if not active
                if(!$(this).hasClass("active")){
                    t.popover("destroy");
                    return true;
                }

                // Display warning
                if (get_selected_element().css("display") == "inline") {

                    t.popover({
                        animation: false,
                        title: l18_warning,
                        content: l18_display_notice,
                        trigger: 'click',
                        placement: "left",
                        container: ".yp-select-bar",
                        html: true
                    }).popover("show");

                // else destroy
                } else {
                    t.popover("destroy");
                }

            });


            /* ---------------------------------------------------- */
            /* Destroy popover after disable                        */
            /* ---------------------------------------------------- */
            $(".yp-disable-btn").on("click",function(){
                $(this).parents(".yp-option-group").popover("destroy");
            });


            // Hide while editor options scrolling
            $(".yp-editor-list").on("scroll",function(){
                $(".yp-option-group,.yp-advanced-option").popover("hide");
            });



            /* ---------------------------------------------------- */
            /* Select li hover                                      */
            /* ---------------------------------------------------- */
            $(".input-autocomplete").keydown(function(e) {

                var code = e.keyCode || e.which;

                if (code == 38 || code == 40) {

                    $(this).parent().find(".autocomplete-div .ui-state-focus").prev().trigger("mouseout");
                    $(this).parent().find(".autocomplete-div .ui-state-focus").trigger("mouseover");

                }

                // enter
                if (code == 13) {

                    $(this).blur();

                }

            });

            
            /* ---------------------------------------------------- */
            /* Blur select after select                             */
            /* ---------------------------------------------------- */
            $(document).on("click", ".autocomplete-div ul li", function() {
                $(this).parent().parent().parent().find(".ui-autocomplete-input").trigger("blur");
            });


            /* ---------------------------------------------------- */
            /* autocomplete blur                                    */
            /* ---------------------------------------------------- */
            $(".input-autocomplete").on("blur keyup", function(e) {

                if (window.openVal == $(this).val()) {
                    return false;
                }

                var id = $(this).parent().parent().data("css");

                $(".active-autocomplete-item").removeClass("active-autocomplete-item");
                $(this).removeClass("active");

                setTimeout(function(){
                    mainBody.removeClass("autocomplete-active");
                },300);

                delete_live_css(id, "#yp-" + id + "-test-style");

                // Disable
                $(this).parent().parent().find(".yp-btn-action.active").trigger("click");
                $("#" + id + "-group").addClass("reset-enable");

                // Font weight.
                if (id == 'font-weight') {
                    $("#yp-font-weight").css(id, $(this).val()).css("fontFamily", $("#yp-font-family").val());
                }

                // Font family
                if (id == 'font-family') {
                    $("#yp-font-family").css(id, $(this).val());
                    $("#yp-font-weight").css("fontFamily", $("#yp-font-family").val());
                }

                // Text shadow live change.
                if (id == 'text-shadow') {
                    $("#yp-text-shadow").css(id, $(this).val());
                }

                var val = $(this).val();

                if (id == 'font-family') {
                    if (val.indexOf(",") == -1 && val.indexOf("'") == -1 && val.indexOf('"') == -1) {
                        val = "'" + val + "'";
                    }
                }

                // Set for data
                insert_rule(null, id, val, '');

                option_change();

            });

    
            /* ---------------------------------------------------- */
            /* Fix Break minify issue after editor loaded:          */
            /* convert line break to space in the selectors         */
            /* ---------------------------------------------------- */
            iframe.find(".yp-styles-area,.yp-animate-data").each(function(){

                var clas = 'yp-styles-area';

                if($(this).hasClass("yp-styles-area")){
                    clas = 'yp-styles-area';
                }else{
                    clas = 'yp-animate-data';
                }
                
                // Update Style Elements
                $(this).replaceWith(('<div class="'+clas+'">' + $(this).html() + '</div>').replace(/(\n|(\s+))/g,' '));

            });


            /* ---------------------------------------------------- */
            /* autocomplete li hover                                */
            /* ---------------------------------------------------- */
            $(document).on("mouseover", ".autocomplete-div li", function() {

                var element = $(this);

                $(".active-autocomplete-item").removeClass("active-autocomplete-item");

                var id = element.parent().parent().attr("id").replace("yp-autocomplete-place-", "");

                    // If not current.
                    if (!element.hasClass("ui-state-focus")) {
                        return false;
                    }

                    // If not undefined.
                    if (typeof element.parent().attr("id") == 'undefined') {
                        return false;
                    }

                    // Font weight
                    if (id == 'font-weight') {

                        delete_live_css("font-weight");
                        insert_live_css("font-weight", number_filter(element.text()).replace("-", ""));

                    }

                    // Font family
                    if (id == 'font-family') {

                        load_near_fonts(element.parent());

                        delete_live_css("font-family");

                        // Getting the right font family
                        var index = element.index(), currentOption = $("#yp-font-family-data option").eq(index);

                        // All Font family
                        if(currentOption.length > 0){

                            insert_live_css('font-family',currentOption.val()); // 'Open Sans', sans-serif

                        // Only first font family
                        }else{

                            insert_live_css('font-family', "'" + element.text() + "'"); // 'Open Sans'

                        }

                    }


                // Font Weight
                if (id == 'font-weight') {

                    $(".autocomplete-div li").each(function() {
                        element.css("fontWeight", number_filter(element.text()).replace(/-/g, ''));
                    });

                    $(".autocomplete-div li").css("fontFamily", $("#yp-font-family").val());
                }

            });


            /* ---------------------------------------------------- */
            /* getting multiple delay                               */
            /* ---------------------------------------------------- */
            function get_multiple_delay(duration,delay){

                if(isUndefined(duration) || isUndefined(delay)){
                    return false;
                }

                var resultDelay = 0;
                var durationArray = duration.toString().split(",");
                var delayArray = delay.toString().split(",");

                if(durationArray.length != delayArray.length){
                    return false;
                }

                if(durationArray.length <= 1){
                    return false;
                }

                var currents = 0;
                for(var i = 0; i < durationArray.length; i++){
                    if(isDefined(delayArray[i+1])){
                        currents = currents + parseFloat(duration_ms(durationArray[i]));
                        resultDelay = (parseFloat(duration_ms(delayArray[i+1])) - currents) + resultDelay;
                        currents = currents + resultDelay;
                    }
                }

                return resultDelay;

            }


            /* ---------------------------------------------------- */
            /* mouseout autocomplete div                            */
            /* ---------------------------------------------------- */
            $(document).on("mouseout", ".autocomplete-div", function() {

                delete_live_css("font-family");

            });


            /* ---------------------------------------------------- */
            /* if mouseleave, leave                                 */
            /* ---------------------------------------------------- */
            $(document).on("mouseleave", $(document), function() {

                if(body.hasClass("yp-mouseleave")){
                    return false;
                }

                body.addClass("yp-mouseleave");

                // remove multiple selection support.
                body.removeClass("yp-control-key-down");
                iframe.find(".yp-multiple-selected").removeClass("yp-multiple-selected");
                iframe.find(".yp-selected-others-multiable-box").remove();

                if(is_content_selected() === false){
                    clean();
                }

            });

            /* ---------------------------------------------------- */
            /* If mouseenter                                        */
            /* ---------------------------------------------------- */
            $(document).on("mouseenter", $(document), function() {

                body.removeClass("yp-mouseleave");

                // remove multiple selection support.
                body.removeClass("yp-control-key-down");
                iframe.find(".yp-multiple-selected").removeClass("yp-multiple-selected");
                iframe.find(".yp-selected-others-multiable-box").remove();

            });


            /* ---------------------------------------------------- */
            /* iframe: if mouseleave, leave                         */
            /* ---------------------------------------------------- */
            iframe.on("mouseleave", iframe, function() {

                if(body.hasClass("yp-iframe-mouseleave")){
                    return false;
                }

                body.addClass("yp-iframe-mouseleave");

                // remove multiple selection support.
                body.removeClass("yp-control-key-down");
                iframe.find(".yp-multiple-selected").removeClass("yp-multiple-selected");
                iframe.find(".yp-selected-others-multiable-box").remove();

            });

            /* ---------------------------------------------------- */
            /* iframe: If mouseenter                                */
            /* ---------------------------------------------------- */
            iframe.on("mouseenter", iframe, function() {

                body.removeClass("yp-iframe-mouseleave");

            });


            /* ---------------------------------------------------- */
            /* Loading visible fonts                                */
            /* ---------------------------------------------------- */
            function load_near_fonts(t){

                var ul = $("#font-family-group .ui-autocomplete.ui-menu").outerHeight();
                var li = $("#font-family-group .ui-autocomplete.ui-menu li").outerHeight();

                var number = parseInt(ul/li)+1;

                var element = t.find(".ui-state-focus");

                if(element.length === 0){
                    element = t.find(".active-autocomplete-item");
                }

                var prev = element.prevAll().slice(0,number);
                var next = element.nextAll().slice(0,number);

                var all = prev.add(next).add(element);

               all.each(function() {

                    var element = $(this);

                    var styleAttr = element.attr("style");

                    if (isDefined(styleAttr)){
                        return true;
                    }

                    var fontId = get_basic_id($.trim(element.text().replace(/ /g, '+')));

                    if (is_safe_font(element.text()) === false && iframe.find(".yp-font-test-" + fontId).length === 0) {

                        iframeBody.append("<link rel='stylesheet' class='yp-font-test-" + fontId + "'  href='https://fonts.googleapis.com/css?family=" + $.trim(element.text().replace(/ /g, '+')) + ":300italic,300,400,400italic,500,500italic,600,600italic,700,700italic' type='text/css' media='all' />");

                        // Append always to body.
                        mainBody.append("<link rel='stylesheet' class='yp-font-test-" + fontId + "'  href='https://fonts.googleapis.com/css?family=" + $.trim(element.text().replace(/ /g, '+')) + ":300italic,300,400,400italic,500,500italic,600,600italic,700,700italic' type='text/css' media='all' />");

                    }

                    element.css("fontFamily", element.text());

                });

            }


            /* ---------------------------------------------------- */
            /* Loading fonts while autocomplete scrolling           */
            /* ---------------------------------------------------- */
            $("#yp-autocomplete-place-font-family > ul").bind('scroll', function() {

                load_near_fonts($(this));

            }); 


            // Toggle options.
            $(".wf-close-btn-link").click(function(e) {
                if ($(".yp-editor-list > li.active:not(.yp-li-about):not(.yp-li-footer)").length > 0) {
                    e.preventDefault();
                    $(".yp-editor-list > li.active:not(.yp-li-about):not(.yp-li-footer) > h3").trigger("click");
                }
            });


            /* ---------------------------------------------------- */
            /* Creating live CSS because more faster. Color/Slider  */
            /* ---------------------------------------------------- */
            function insert_live_css(id, val, custom) {

                var selector = get_current_selector();

                // Set parent element as current
                if(id == 'perspective'){

                    // Cache current
                    var oldSelector = selector;

                    // clean cache
                    body.removeAttr("data-clickable-select");

                    // Update selector var
                    selector = $.trim(get_parents(get_selected_element().parent(), "default"));

                    // set old as cache again
                    body.attr("data-clickable-select",oldSelector);

                }

                // Adds relative automatics
                if(id == 'top' || id == 'left' || id == 'right' ||id == 'bottom'){

                    // If is static
                    if($(".yp-radio.active #position-static").length > 0){

                        // Insert position relative
                        insert_rule(null, "position", "relative", '');
                        $("#position-group .yp-radio.active").removeClass("active");
                        $("#position-relative").parent().addClass("active");

                    }

                }


                // Checks min height and min width and update.
                if(id == 'height' || id == 'width'){

                    // minValue & minFormat
                    var minVal = number_filter($("#min-"+id+"-value").val());
                    var prefix = $("#"+id+"-after").val();
                    var minFormat = $("#min-"+id+"-after").val();

                    // if height is smaller than min-height, so update min height
                    if(parseFloat(val) < parseFloat(minVal) && prefix == minFormat){

                        // Insert min-height
                        delete_live_css('min-'+id,false);
                        insert_live_css('min-'+id,val,false);

                    }

                }


                // Responsive helper
                var mediaBefore = create_media_query_before(null);
                var mediaAfter = create_media_query_after();

                // Style id
                var styleId;
                if (custom !== false && custom !== undefined) {
                    styleId = custom;
                } else {
                    styleId = "#" + id + "-live-css";
                }

                //Element
                var element = iframe.find(styleId);

                // Check
                if (element.length === 0) {

                    var idAttr = styleId.replace('#', '').replace('.', '');

                    // not use prefix (px,em,% etc)
                    if (id == 'z-index' || id == 'opacity') {
                        val = parseFloat(val);
                    }


                    // Filter Default options.
                    if (id == 'blur-filter' || id == 'grayscale-filter' || id == 'brightness-filter' || id == 'contrast-filter' || id == 'hue-rotate-filter' || id == 'saturate-filter' || id == 'sepia-filter') {

                        id = 'filter';
                        idAttr = 'filter';

                        val = filter_generator(false);

                    }
                    // Filter options end

                    // Transform Settings
                    if (id.indexOf("-transform") != -1 && id != 'text-transform' && id != '-webkit-transform') {

                        id = 'transform';
                        idAttr = 'transform';

                        val = transform_generator(false);
                        

                    }
                    // Transform options end


                    // Box Shadow
                    if (id == 'box-shadow-inset' || id == 'box-shadow-color' || id == 'box-shadow-vertical' || id == 'box-shadow-blur-radius' || id == 'box-shadow-spread' || id == 'box-shadow-horizontal') {

                        id = 'box-shadow';
                        idAttr = 'box-shadow';

                        val = box_shadow_generator();
                        

                    }
                    // Box shadow options end


                    // Append
                    if(id == 'filter' || id == 'transform'){ // Webkit support

                        iframeBody.append("<style class='" + idAttr + " yp-live-css' id='" + idAttr + "'>" + mediaBefore + ".yp-selected,.yp-selected-others," + selector + "{" + id + ":" + val + " !important;-webkit-" + id + ":" + val + " !important;}" + mediaAfter + "</style>");

                    }else{ // default

                        iframeBody.append("<style class='" + idAttr + " yp-live-css' id='" + idAttr + "'>" + mediaBefore + ".yp-selected,.yp-selected-others," + selector + "{" + id + ":" + val + " !important;}" + mediaAfter + "</style>");

                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Generating transform generator                       */
            /* ---------------------------------------------------- */
            function transform_generator(type){

                // Getting all other options.
                var scale = "scale(" + $.trim($("#scale-transform-value").val()) + ")";
                var rotate = "rotate(" + $.trim($("#rotate-transform-value").val()) + "deg)";
                var rotatex = "rotateX(" + $.trim($("#rotatex-transform-value").val()) + "deg)";
                var rotatey = "rotateY(" + $.trim($("#rotatey-transform-value").val()) + "deg)";
                var rotatez = "rotateZ(" + $.trim($("#rotatez-transform-value").val()) + "deg)";
                var translateX = "translatex(" + $.trim($("#translate-x-transform-value").val()) + "px)";
                var translateY = "translatey(" + $.trim($("#translate-y-transform-value").val()) + "px)";
                var skewX = "skewx(" + $.trim($("#skew-x-transform-value").val()) + "deg)";
                var skewY = "skewy(" + $.trim($("#skew-y-transform-value").val()) + "deg)";

                // Check if disable or not
                if ($("#scale-transform-group .yp-disable-btn").hasClass("active")) {
                    scale = '';
                }

                if ($("#rotate-transform-group .yp-disable-btn").hasClass("active")) {
                    rotate = '';
                }

                if ($("#rotatex-transform-group .yp-disable-btn").hasClass("active")) {
                    rotatex = '';
                }

                if ($("#rotatey-transform-group .yp-disable-btn").hasClass("active")) {
                    rotatey = '';
                }

                if ($("#rotatez-transform-group .yp-disable-btn").hasClass("active")) {
                    rotatez = '';
                }

                if ($("#translate-x-transform-group .yp-disable-btn").hasClass("active")) {
                    translateX = '';
                }

                if ($("#translate-y-transform-group .yp-disable-btn").hasClass("active")) {
                    translateY = '';
                }

                if ($("#skew-x-transform-group .yp-disable-btn").hasClass("active")) {
                    skewX = '';
                }

                if ($("#skew-y-transform-group .yp-disable-btn").hasClass("active")) {
                    skewY = '';
                }

                // Dont insert if no data.
                if (scale == 'scale()' || ($("#scale-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        scale = '';
                    } else {
                        scale = 'scale(1)';
                    }

                }

                if (rotate == 'rotate(deg)' || ($("#rotate-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        rotate = '';
                    } else {
                        rotate = 'rotate(0deg)';
                    }

                }

                if (rotatex == 'rotateX(deg)' || ($("#rotatex-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        rotatex = '';
                    } else {
                        rotatex = 'rotateX(0deg)';
                    }

                }

                if (rotatey == 'rotateY(deg)' || ($("#rotatey-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        rotatey = '';
                    } else {
                        rotatey = 'rotateY(0deg)';
                    }

                }

                if (rotatez == 'rotateZ(deg)' || ($("#rotatez-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        rotatez = '';
                    } else {
                        rotatez = 'rotateZ(0deg)';
                    }

                }

                if (translateX == 'translatex(px)' || ($("#translate-x-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        translateX = '';
                    } else {
                        translateX = 'translatex(0px)';
                    }

                }

                if (translateY == 'translatey(px)' || ($("#translate-y-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        translateY = '';
                    } else {
                        translateY = 'translatey(0px)';
                    }

                }

                if (skewX == 'skewx(deg)' || ($("#skew-x-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        skewX = '';
                    } else {
                        skewX = 'skewx(0deg)';
                    }

                }

                if (skewY == 'skewy(deg)' || ($("#skew-y-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        skewY = '';
                    } else {
                        skewY = 'skewy(0deg)';
                    }

                }

                // All data.
                var translateData = $.trim(scale + " " + rotate + " " + rotatex + " " + rotatey + " " + rotatez + " " + translateX + " " + translateY + " " + skewX + " " + skewY);

                if (translateData === '' || translateData == ' ') {
                    translateData = 'disable';
                    body.removeClass("yp-has-transform");
                }

                return translateData;

            }


            /* ---------------------------------------------------- */
            /* Filter generating                                    */
            /* ---------------------------------------------------- */
            function filter_generator(type){

                // Getting all other options.
                var blur = "blur(" + $.trim($("#blur-filter-value").val()) + "px)";
                var grayscale = "grayscale(" + $.trim($("#grayscale-filter-value").val()) + ")";
                var brightness = "brightness(" + $.trim($("#brightness-filter-value").val()) + ")";
                var contrast = "contrast(" + $.trim($("#contrast-filter-value").val()) + ")";
                var hueRotate = "hue-rotate(" + $.trim($("#hue-rotate-filter-value").val()) + "deg)";
                var saturate = "saturate(" + $.trim($("#saturate-filter-value").val()) + ")";
                var sepia = "sepia(" + $.trim($("#sepia-filter-value").val()) + ")";

                // Check if disable or not
                if ($("#blur-filter-group .yp-disable-btn").hasClass("active")) {
                    blur = '';
                }

                if ($("#grayscale-filter-group .yp-disable-btn").hasClass("active")) {
                    grayscale = '';
                }

                if ($("#brightness-filter-group .yp-disable-btn").hasClass("active")) {
                    brightness = '';
                }

                if ($("#contrast-filter-group .yp-disable-btn").hasClass("active")) {
                    contrast = '';
                }

                if ($("#hue-rotate-filter-group .yp-disable-btn").hasClass("active")) {
                    hueRotate = '';
                }

                if ($("#saturate-filter-group .yp-disable-btn").hasClass("active")) {
                    saturate = '';
                }

                if ($("#sepia-filter-group .yp-disable-btn").hasClass("active")) {
                    sepia = '';
                }

                // Dont insert if no data.
                if (blur == 'blur(px)' || ($("#blur-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        blur = '';
                    } else {
                        blur = 'blur(0px)';
                    }

                }

                if (grayscale == 'grayscale()' || ($("#grayscale-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        grayscale = '';
                    } else {
                        grayscale = 'grayscale(0)';
                    }

                }

                if (brightness == 'brightness()' || ($("#brightness-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        brightness = '';
                    } else {
                        brightness = 'brightness(1)';
                    }

                }

                if (contrast == 'contrast()' || ($("#contrast-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        contrast = '';
                    } else {
                        contrast = 'contrast(1)';
                    }

                }

                if (hueRotate == 'hue-rotate(deg)' || ($("#hue-rotate-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        hueRotate = '';
                    } else {
                        hueRotate = 'hue-rotate(0deg)';
                    }

                }

                if (saturate == 'saturate()' || ($("#saturate-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        saturate = '';
                    } else {
                        saturate = 'saturate(1)';
                    }

                }

                if (sepia == 'sepia()' || ($("#sepia-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        sepia = '';
                    } else {
                        sepia = 'sepia(0)';
                    }

                }

                // All data.
                var filterData = $.trim(blur + " " + brightness + " " + contrast + " " + grayscale + " " + hueRotate + " " + saturate + " " + sepia);

                if (filterData === '' || filterData == ' ') {
                    filterData = 'disable';
                }

                return filterData;

            }


            /* ---------------------------------------------------- */
            /* Box Shadow generating                                */
            /* ---------------------------------------------------- */
            function box_shadow_generator(){

                // Get inset option
                var inset = '';
                if ($("#box-shadow-inset-inset").parent().hasClass("active")) {
                    inset = 'inset';
                }

                        // Getting all other options.
                var color = $.trim($("#yp-box-shadow-color").val());
                var vertical = $.trim($("#yp-box-shadow-vertical").val());
                var radius = $.trim($("#yp-box-shadow-blur-radius").val());
                var spread = $.trim($("#yp-box-shadow-spread").val());
                var horizontal = $.trim($("#yp-box-shadow-horizontal").val());

                if ($("#box-shadow-color-group .yp-disable-btn").hasClass("active") || $("#box-shadow-color-group .yp-none-btn").hasClass("active")) {
                    color = 'transparent';
                }

                if ($("#box-shadow-vertical-group .yp-disable-btn").hasClass("active")) {
                    vertical = '0';
                }

                if ($("#box-shadow-blur-radius-group .yp-disable-btn").hasClass("active")) {
                    radius = '0';
                }

                if ($("#box-shadow-spread-group .yp-disable-btn").hasClass("active")) {
                    spread = '0';
                }

                if ($("#box-shadow-horizontal-group .yp-disable-btn").hasClass("active")) {
                    horizontal = '0';
                }

                var shadowData = $.trim(horizontal + "px " + vertical + "px " + radius + "px " + spread + "px " + color + " " + inset);

                if(horizontal == 0 && vertical == 0 && radius == 0 && spread == 0){
                    shadowData = 'none';
                }

                if(color == 'transparent'){
                    shadowData = 'none';
                }

                return shadowData;

            }


            /* ---------------------------------------------------- */
            /* Delete live CSS                                      */
            /* ---------------------------------------------------- */
            function delete_live_css(id, custom) {

                // Style id
                var styleId;
                if (custom !== false && custom !== undefined) {
                    styleId = custom;
                } else {
                    styleId = "#" + id + "-live-css";
                }

                var element = iframe.find(styleId);

                if (element.length > 0) {
                    element.remove();
                }

            }


            /* ---------------------------------------------------- */
            /* iris color picker helper                             */
            /* ---------------------------------------------------- */
            mainDocument.on("mousemove", function(){

                var element,css,val;

                if ($(".iris-dragging").length > 0) {

                    element = $(".iris-dragging").parents(".yp-option-group");

                    css = element.data("css");
                    val = element.find(".wqcolorpicker").val();

                    if(css != 'background-image'){
                        delete_live_css(css, false);
                        insert_live_css(css, val, false);
                    }

                    if($(".fake-layer").length === 0){
                        mainBody.append("<div class='fake-layer'></div>");
                    }

                }

                if ($(".iris-slider").find(".ui-state-active").length > 0) {

                    element = $(".iris-slider").find(".ui-state-active").parents(".yp-option-group");

                    css = element.data("css");
                    val = element.find(".wqcolorpicker").val();

                    if(css != 'background-image'){
                        delete_live_css(css, false);
                        insert_live_css(css, val, false);
                    }

                    if($(".fake-layer").length === 0){
                        mainBody.append("<div class='fake-layer'></div>");
                    }

                }

                if ($(".cs-alpha-slider").find(".ui-state-active").length > 0) {

                    element = $(".cs-alpha-slider").find(".ui-state-active").parents(".yp-option-group");

                    css = element.data("css");
                    val = element.find(".wqcolorpicker").val();

                    if(css != 'background-image'){
                        delete_live_css(css, false);
                        insert_live_css(css, val, false);
                    }

                    if($(".fake-layer").length === 0){
                        mainBody.append("<div class='fake-layer'></div>");
                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Iris color picker insert Color                       */
            /* ---------------------------------------------------- */
            mainDocument.on("mouseup", function(event) {

                var element;

                if ($(document).find(".iris-dragging").length > 0) {

                    element = $(".iris-dragging").parents(".yp-option-group");

                    element.find(".wqcolorpicker").trigger("change");

                    $(".fake-layer").remove();

                    if(element.attr("id") == 'background-image-group'){
                        update_gradient("insert");
                    }

                } else if ($(document).find(".iris-slider .ui-state-active").length > 0) {

                    element = $(".ui-state-active").parents(".yp-option-group");

                    element.find(".wqcolorpicker").trigger("change");

                    $(".fake-layer").remove();

                    if(element.attr("id") == 'background-image-group'){
                        update_gradient("insert");
                    }

                } else if ($(document).find(".cs-alpha-slider .ui-state-active").length > 0) {

                    element = $(".cs-alpha-slider .ui-state-active").parents(".yp-option-group");

                    $(".fake-layer").remove();

                    if(element.attr("id") == 'background-image-group'){
                        update_gradient("insert");
                    }

                } else if($(event.target).hasClass("iris-square-handle")){

                    element = $(event.target).parents(".yp-option-group");

                    element.find(".wqcolorpicker").trigger("change");

                    $(".fake-layer").remove();

                    if(element.attr("id") == 'background-image-group'){
                        update_gradient("insert");
                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Color Event                                          */
            /* ---------------------------------------------------- */
            function color_option(id) {

                // Color picker on blur
                $("#yp-" + id).on("blur", function() {

                    // If empty, set disable.
                    if ($(this).val() == '') {
                        return false;
                    }

                });

                // Show picker on click
                $("#yp-" + id).on("click", function() {

                    $(this).parent().parent().find(".iris-picker").show();
                    $(this).parent().parent().parent().css("opacity", 1);
                    gui_update();

                });

                // disable to true.
                $("#" + id + "-group").find(".yp-after a").on("click", function() {
                    $(this).parent().parent().parent().css("opacity", 1);
                });

                // Update on keyup
                $("#yp-" + id).on("keydown keyup", function() {
                    $(this).parent().find(".wqminicolors-swatch-color").css("backgroundColor", $(this).val());
                });

                // Color picker on change
                $("#yp-" + id).on('change', function() {

                    var css = $(this).parent().parent().parent().data("css");
                    $(this).parent().parent().parent().addClass("reset-enable");
                    var val = $(this).val();

                    if (val.indexOf("#") == -1 && val.indexOf("rgb") == -1) {
                        val = "#" + val;
                    }

                    // Disable
                    $(this).parent().parent().find(".yp-btn-action.active").trigger("click");

                    if (val.length < 3) {
                        val = 'transparent';
                        $(this).parent().parent().find(".yp-none-btn:not(.active)").trigger("click");
                    }

                    // Set for demo
                    delete_live_css(css, false);

                    insert_rule(null, id, val, '');

                    // Option Changed
                    option_change();

                });

            }

            /* ---------------------------------------------------- */
            /* Input Event                                          */
            /* ---------------------------------------------------- */
            function input_option(id) {

                // Keyup
                $("#yp-" + id).on('keyup', function() {

                    $(this).parent().parent().addClass("reset-enable");

                    var val = $(this).val();

                    // Disable
                    $(this).parent().find(".yp-btn-action.active").trigger("click");

                    if (val == 'none') {
                        $(this).parent().parent().find(".yp-none-btn").not(".active").trigger("click");
                        $(this).val('');
                    }

                    if (val == 'disable') {
                        $(this).parent().parent().find(".yp-disable-btn").not(".active").trigger("click");
                        $(this).val('');
                    }

                    // Background image
                    if (id == 'background-image' && val.indexOf("linear-gradient(") == -1) {

                        val = val.replace(/\)/g, '').replace(/\url\(/g, '');

                        $(this).val(val);

                        val = 'url(' + val + ')';

                        $(".yp-background-image-show").remove();

                        var imgSrc = val.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "");

                        if (val.indexOf("yellow-pencil") == -1) {

                            if (imgSrc.indexOf("//") != -1 && imgSrc != '' && imgSrc.indexOf(".") != -1) {
                                $("#yp-background-image").after("<img src='" + imgSrc + "' class='yp-background-image-show' />");
                            }

                        }

                    }

                    // List Style image
                    if (id == 'list-style-image') {

                        val = val.replace(/\)/g, '').replace(/\url\(/g, '');

                        $(this).val(val);

                        val = 'url(' + val + ')';

                    }

                    // Remove active pattern if not have pattern more.
                    if(id == 'background-image' && val.indexOf("yellow-pencil") == -1){
                        $(".yp_bg_assets.active").removeClass("active");
                    }

                    // Add
                    if(id == 'background-image' && val.indexOf("yellow-pencil") != -1){
                        $(".yp_bg_assets.active").addClass("active");
                    }

                    // Set for demo
                    insert_rule(null, id, val, '');

                    // Option Changed
                    option_change();

                });

            }


            /* ---------------------------------------------------- */
            /* Clean data that not selected yet.                    */
            /* ---------------------------------------------------- */
            function simple_clean(){

                // Animate update
                if(body.hasClass("yp-animate-manager-active")){
                    animation_manager();
                }

                // Clean basic classes
                body.removeAttr("data-clickable-select").removeAttr("data-yp-selector").removeClass("yp-element-float yp-selector-focus yp-selector-hover yp-selector-active yp-selector-link yp-selector-visited yp-css-data-trigger yp-content-selected yp-body-select-just-it yp-has-transform yp-element-resizing yp-element-resizing-height-top yp-element-resizing-height-bottom yp-element-resizing-width-left yp-element-resizing-width-right yp-visual-editing yp-visual-editing-x yp-visual-editing-y");

                // for html overflow hidden on resizing
                iframe.find("html").removeClass("yp-element-resizing");
 
                // Clean classes from selected element
                iframe.find(".yp-selected,.yp-selected-others").removeClass("ui-draggable ui-draggable-handle ui-draggable-handle yp-selected-has-transform");

                // Remove yp-selected classes
                iframe.find(".yp-selected-others,.yp-selected").removeClass("yp-selected-others").removeClass("yp-selected");

                // Remove created elements
                iframe.find(".yp-edit-menu,.yp-edit-tooltip,.yp-selected-handle,.yp-selected-others-box,.yp-selected-tooltip,.yp-selected-boxed-top,.yp-selected-boxed-left,.yp-selected-boxed-right,.yp-selected-boxed-bottom,.yp-selected-boxed-margin-top,.yp-selected-boxed-margin-left,.yp-selected-boxed-margin-right,.yp-selected-boxed-margin-bottom,.selected-just-it-span,.yp-selected-boxed-padding-top,.yp-selected-boxed-padding-left,.yp-selected-boxed-padding-right,.yp-selected-boxed-padding-bottom,.yp-live-css,.yp-selected-tooltip span").remove();

                // Update
                if(mainBody.hasClass("yp-select-just-it") === false){
                    window.selectorClean = null;
                }

                // Update informations
                if($(".advanced-info-box").css("display") == 'block' && $(".element-btn").hasClass("active")){
                    $(".info-element-selected-section").hide();
                    $(".info-no-element-selected").show();
                }

                $(".yp-disable-btn.active").removeClass("active");

            }



            /* ---------------------------------------------------- */
            /* Gradient Generator Start                             */
            /* ---------------------------------------------------- */
            $(document).on("click", ".yp-gradient-pointer-area", function(e) {

                // Not action if picker open
                if ($(".iris-picker:visible").length !== 0) {
                    return true;
                }

                // only blank area clicks are valid
                if ($(e.target).hasClass("yp-gradient-pointer") || $(e.target).hasClass("yp-gradient-pointer-color")) {
                    return false;
                }

                // gradient pointer area
                var area = $(".yp-gradient-pointer-area");

                // Getting pointer area width
                var areaWidth = area.width();

                // Getting pointer area offset
                var areaOffset = area.offset();
                var leftOffset = areaOffset.left;

                // rel x in px
                var deg = e.pageX - leftOffset;

                // find relX in % format
                deg = parseInt(deg/areaWidth*100);

                $(".yp-gradient-pointer").removeClass("active");

                // get color
                var color = "#FF5253";
                if($("#iris-gradient-color").val().length == 7){
                    color = $("#iris-gradient-color").val();
                }

                // pointer html Template
                var pointerTemplate = '<div class="yp-gradient-pointer active" data-color="'+color+'" data-position="'+deg+'" style="left:'+deg+'%;"><i class="yp-gradient-pointer-color" style="background-color:'+color+';"></i></div>';

                // Append pointer
                area.append(pointerTemplate);

                // Adds draggable support to pointers
                update_gradient_pointers();

                // Update after append
                update_gradient("insert");

            });


            /* ---------------------------------------------------- */
            /* Draggable gradient pointers                          */
            /* ---------------------------------------------------- */
            $(".yp-gradient-orientation i").draggable({

                containment: "parent",

                start: function(e,ui){
                },

                drag: function(e,ui){

                    var ori = $(".yp-gradient-orientation");

                    var offset = ori.offset();

                    var center_x = (offset.left) + (ori.width()/2);
                    var center_y = (offset.top) + (ori.height()/2);

                    var x = ui.offset.left;
                    var y = ui.offset.top;

                    var radians = Math.atan2(x - center_x, y - center_y);
                    var degree = (radians * (180 / Math.PI) * -1);

                    $(this).parent().attr("data-degree",parseInt(degree));

                    update_gradient("live");

                },

                stop: function(e,ui){
                    update_gradient("insert");
                }

            });


            window.blockIrıs = false;

            /* ---------------------------------------------------- */
            /* Updating gradient                                    */
            /* ---------------------------------------------------- */
            function update_gradient_pointers(){

                // gradient pointer area
                var area = $(".yp-gradient-pointer-area");

                // Getting pointer area width
                var areaWidth = area.width();

                // Getting pointer area offset
                var areaOffset = area.offset();
                var leftOffset = areaOffset.left;
                var topOffset = areaOffset.top;

                // Draggable gradient pointers
                $(".yp-gradient-pointer").draggable({

                    containment: [leftOffset, topOffset, (leftOffset+areaWidth), topOffset],

                    start: function(e,ui){
                        $(".yp-gradient-pointer").removeClass("active");
                        $(this).addClass("active");

                        //Block iris. not let to open while dragging
                        window.blockIrıs = true;

                        // Hide iris if open
                        $(".yp-gradient-section .iris-picker").hide();

                    },

                    drag: function(e,ui){

                        // Add class to parent
                        area.addClass("gradient-pointer-no-cursor");

                        // update pointer position
                        $(this).attr("data-position",parseInt(ui.position.left/areaWidth*100));

                        // Updating view and data
                        update_gradient("live");

                    },

                    stop: function(e,ui){

                        // remove class from parent
                        area.removeClass("gradient-pointer-no-cursor");
                        
                        // update pointer position
                        $(this).attr("data-position",parseInt(ui.position.left/areaWidth*100));

                        // Updating view and data
                        update_gradient("insert");

                        window.blockIrıs = false;

                    },

                    axis: "x"

                });

            }

            /* ---------------------------------------------------- */
            /* iris picker for gradient                             */
            /* ---------------------------------------------------- */
            $("#iris-gradient-color").cs_iris({

                hide:true,
                width:wIris

            });


            /* ---------------------------------------------------- */
            /* iris color picker global callback                    */
            /* ---------------------------------------------------- */
            window.iris_global_change_callback = function(event,ui){

                // if this is gradient color picker
                if($(".yp-gradient-section .iris-picker:visible").length > 0){

                    // Update the pointer
                    $(".yp-gradient-pointer.active i").css("background-color",ui.color.toString());
                    $(".yp-gradient-pointer.active").attr("data-color",ui.color.toString());

                    // insert gradint CSS before close picker
                    update_gradient("live");

                }

            }


            /* ---------------------------------------------------- */
            /* ContextMenu on gradient pointers                     */
            /* ---------------------------------------------------- */
            $(document).on("click contextmenu", ".yp-gradient-pointer", function(e) {

                $(".yp-gradient-pointer").removeClass("active");
                $(this).addClass("active");

            });


            /* ---------------------------------------------------- */
            /* Double click support to gradient pointers            */
            /* ---------------------------------------------------- */
            $(document).on("dblclick", ".yp-gradient-pointer", function(e) {

                var wIris = 237;
                if ($(window).width() < 1367) {
                    wIris = 195;
                }

                if(window.blockIrıs == true){
                    return false;
                }

                // get color
                var color = $(this).attr("data-color");

                window.gradientlastColor = color;

                $(".yp-gradient-pointer").removeClass("active");

                $(this).addClass("active");

                $(".yp-gradient-pointer-area").addClass("gradient-pointer-no-cursor");

                $("#iris-gradient-color").iris("color",color);

                // If rgba
                var alpha = 100;
                if(color.indexOf("rgba") != -1){

                    alpha = $.trim(color.replace(/^.*,(.+)\)/,'$1'));

                        if(alpha.indexOf(".") != -1){

                            alpha = alpha.replace("000.","").replace("00.","").replace("0.","").replace(".","");

                            if(alpha.length == 1){
                                alpha = alpha.toString()+"0";
                            }

                            alpha = alpha.replace(/^0/, "");
                        }

                    }

                // Update iris alpha.
                $(".yp-gradient-section .iris-picker .cs-alpha-slider").slider('value',alpha);

                $(".yp-gradient-section .iris-picker").show();

            });


            /* ---------------------------------------------------- */
            /* CSS To Gradient View                                 */
            /* ---------------------------------------------------- */
            function read_gradient(value){

                // Variables
                var gradientObject,Colortype,color,lengthType,length,lengthFormat,lengthSpace,pointerTemplate,code,direction,directionType;

                // Cleans value.
                value = value.replace(/\s+?!important/g,'').replace(/\;$/g,'').trim();

                // No direction gradient for gradient bar
                code = 'linear-gradient(to right,';

                // Be sure value is a gradient
                if(value.indexOf("linear-gradient(") == -1){
                    return false;
                }

                // Parse gradient with a javascript plugin.
                gradientObject = GradientParser.parse(value)[0];

                // getting direction type
                directionType = gradientObject.orientation.type;

                // getting direction value
                direction = gradientObject.orientation.value;

                // covert directional value to deg
                if(directionType == 'directional'){

                    // Directional value to deg
                    if(direction == 'top'){
                        direction = '0';
                    }else if(direction == 'right'){
                        direction = '90';
                    }else if(direction == 'bottom'){
                        direction = '180';
                    }else if(direction == 'left'){
                        direction = '270';
                    }else if(direction == 'top'){
                        direction = '360';
                    }

                }

                // Update direction data
                $(".yp-gradient-orientation").attr("data-degree",direction);

                // Empty bar
                $(".yp-gradient-pointer-area").empty();

                // Each color stops
                for(var i = 0; i < gradientObject.colorStops.length; i++){

                    if(isDefined(gradientObject.colorStops[i].length)){

                        // Length Type
                        lengthType = gradientObject.colorStops[i].length.type;

                        // Skip if not %
                        if(lengthType != '%'){
                            return true;
                        }

                        // Length
                        length = gradientObject.colorStops[i].length.value;
                        lengthFormat = '%';
                        lengthSpace = ' ';

                    }else{

                        // Auto length
                        length = (i*100/(gradientObject.colorStops.length-1));
                        lengthFormat = '%';
                        lengthSpace = ' ';

                    }

                    // Int
                    length = parseInt(length);
                    
                    // Color type
                    Colortype = gradientObject.colorStops[i].type;

                    // Color
                    color = gradientObject.colorStops[i].value;

                    // CSS Color
                    if(Colortype == 'rgb' || Colortype == 'rgba'){
                        color = Colortype + "(" + color + ")";
                    }

                    if(Colortype == 'hex'){
                        color = '#' + color;
                    }

                    // Set first color as default color
                    if(i == 0){
                        $("#iris-gradient-color").val(color);
                    }

                    // add colorStops
                    code += ' ' + color + lengthSpace + length + lengthFormat;

                    // add if not last.
                    if(gradientObject.colorStops.length-1 != i){
                        code += ",";
                    }

                    // pointer html Template
                    pointerTemplate = '<div class="yp-gradient-pointer" data-color="'+color+'" data-position="'+length+'" style="left:'+length+''+lengthFormat+';"><i class="yp-gradient-pointer-color" style="background-color:'+color+';"></i></div>';

                    // Append pointer
                    $(".yp-gradient-pointer-area").append(pointerTemplate);

                }

                code += ')';

                // Remove old style
                $("#gradient-bar-view-style").remove();

                // Add new
                mainBody.append('<style id="gradient-bar-view-style">.yp-gradient-bar{background-image:'+code+';}.yp-gradient-orientation{background-image:'+value+';}</style>');

                // Add support
                setTimeout(function(){
                    update_gradient_pointers();
                },26);

            }

    
            /* ---------------------------------------------------- */
            /* Gradient View to CSS Code                            */
            /* ---------------------------------------------------- */
            function update_gradient(type){

                // Getting direction
                var direction = $(".yp-gradient-orientation").attr("data-degree") + "deg";

                if(direction == '0deg'){
                    direction = 'to top';
                }else if(direction == '90deg'){
                    direction = 'to right';
                }else if(direction == '180deg'){
                    direction = 'to bottom';
                }else if(direction == '270deg'){
                    direction = 'to left';
                }else if(direction == '360deg'){
                    direction = 'to top';
                }

                // Linear gradient
                var codeBar = 'linear-gradient(to right,';
                var code = 'linear-gradient('+direction+',';

                // Sort points by position
                $(".yp-gradient-pointer-area .yp-gradient-pointer").sort(function(a, b) {
                    return +a.dataset.position - +b.dataset.position;
                }).appendTo(".yp-gradient-pointer-area");

                // Each all pointers
                $(".yp-gradient-pointer-area .yp-gradient-pointer").each(function(i){

                    // Element
                    var pointer = $(this);

                    // Getting pointer data
                    var color = pointer.attr("data-color");
                    var position = pointer.attr("data-position");

                    // Adds to CSS
                    code += ' ' + color + ' ' + parseInt(position) + '%';
                    codeBar += ' ' + color + ' ' + parseInt(position) + '%';

                    // add if not last.
                    if($(".yp-gradient-pointer").length-1 != i){
                        code += ",";
                        codeBar += ",";
                    }

                });

                code += ')';
                codeBar += ')';

                // Remove old style
                $("#gradient-bar-view-style").remove();

                // Add new
                mainBody.append('<style id="gradient-bar-view-style">.yp-gradient-bar{background-image:'+codeBar+';}.yp-gradient-orientation{background-image:'+code+';}</style>');

                // Update CSS
                if(type == 'live'){
                    delete_live_css("background-image",false);
                    insert_live_css("background-image",code,false);
                    $("#yp-background-image").val(code);
                }else if(type == 'insert'){
                    delete_live_css("background-image",false);
                    $("#yp-background-image").val(code).trigger("keyup");
                }

            }


            /* ---------------------------------------------------- */
            /* Disable right click on gradient parts                */
            /* ---------------------------------------------------- */
            $(".yp-gradient-pointer-area,.yp-gradient-bar").on("contextmenu", function(e){

                // right click allowed just on pointer
                if ($(e.target).hasClass("yp-gradient-pointer") == false && $(e.target).hasClass("yp-gradient-pointer-color") == false) {
                    return false;
                }

            });


            // Gradient pointer contextMenu
            $.contextMenu({

                events: {

                    show: function() {

                        setTimeout(function(){
                            $(".dom_contextmenu").css("top",$(".yp-gradient-bar").offset().top + 34);
                        },1);

                        // delay 
                        setTimeout(function(){

                            // disable delete feature if less than 3 pointers.
                            if($(".yp-gradient-pointer-area .yp-gradient-pointer").length > 2){
                                $(".delete-gradient-menu").removeClass("disabled");
                            }else{
                                $(".delete-gradient-menu").addClass("disabled");
                            }

                        },10);


                    },


                    hide: function(){
                        $(".yp-gradient-pointer").removeClass("active");
                    }

                },

                selector: '.yp-gradient-pointer-area .yp-gradient-pointer', 
                className: 'dom_contextmenu',
                callback: function(key, options) {

                    var el = $(this);

                    // Delete
                    if(key == 'delete'){
                        el.remove();
                        update_gradient("insert");
                    }

                    // Edit
                    if(key == 'change-color'){
                        setTimeout(function(){
                            el.trigger("dblclick");
                        },10);
                        update_gradient("insert");
                    }

                },
                items: {
                    "change-color": {
                        name: "Change color",
                        className: "change-color-gradient-menu"
                    },
                    "delete": {
                        name: "Delete",
                        className: "delete-gradient-menu"
                    }
                }
            });


            /* ---------------------------------------------------- */
            /* Clean previous changes, settings                     */
            /* ---------------------------------------------------- */
            function clean() {

                // Use yp_simple_clean function for simple clean data.
                if(is_content_selected() === false){
                    simple_clean();
                    return false;
                }else{

                    // Stop if dragging
                    if (is_dragging()){
                        return false;
                    }


                    // Hide if close while playing an animate.
                    if(body.hasClass("yp-force-hide-select-ui")){
                        body.removeClass("yp-force-hide-select-ui yp-hide-borders-now");
                    }

                    /* this function remove menu from selected element */
                    if (iframe.find(".context-menu-active").length > 0) {
                        get_selected_element().contextMenu("hide");
                    }

                    // destroy ex element draggable feature.
                    if (iframe.find(".yp-selected.ui-draggable").length > 0){
                        get_selected_element().draggable("destroy");
                    }

                    // Clean animate buttons classes
                    if(mainBody.find(".yp-anim-cancel-link").length > 0){
                        $(".yp-anim-cancel-link").trigger("click");
                    }

                    // Clean lock button active classes
                    $(".lock-btn").removeClass("active");

                    // Clean popovers.
                    $("#margin-left-group,#margin-right-group,#margin-top-group,#margin-bottom-group,#padding-left-group,#padding-right-group,#padding-top-group,#padding-bottom-group,#background-color-group,.background-parallax-div,#background-size-group,#background-repeat-group,#background-blend-mode-group,#background-attachment-group,#background-position-group,#box-shadow-color-group,#animation-name-group,#list-style-position-group,#list-style-image-group,#list-style-type-group").popover("destroy");

                    // close open menu
                    $(".yp-editor-list > li.active:not(.yp-li-about) > h3").trigger("click");

                    // Dont stop playing animate
                    if(mainBody.hasClass("yp-animate-manager-playing") === false){
                        iframe.find(".yp_onscreen,.yp_hover,.yp_click,.yp_focus").removeClass("yp_onscreen yp_hover yp_click yp_focus");
                    }

                    // Remove classes
                    $(".reset-enable").removeClass("reset-enable");

                    // Update panel
                    $(".yp-option-group").css("opacity", "1");
                    $(".yp-after").css("display", "block");

                    // delete cached data.
                    $("li[data-loaded]").removeAttr("data-loaded");

                    // copied by iframe click select section.
                    $(".yp-editor-list > li.active > h3").not(".yp-li-about").not(".yp-li-footer").trigger("click");

                    $(".input-autocomplete").removeAttr("style");
                    $(".yp-disable-contextmenu").removeClass("yp-disable-contextmenu");
                    $(".yp-active-contextmenu").removeClass("yp-active-contextmenu");

                    // Cancel if animater active
                    if(is_animate_creator() || mainBody.hasClass("yp-anim-link-toggle")){
                        yp_anim_cancel();
                    }

                    // Hide some elements from panel
                    $(".background-parallax-div,.yp-transform-area,.yp-filter-area").hide();
                    $(".yp-on").removeClass("yp-on");

                    simple_clean();

                    // Editor Panel Resetting
                    $(".iris-picker,.yp-border-top-section,.yp-border-right-section,.yp-border-bottom-section,.yp-border-left-section").hide();
                    $(".yp-border-all-section").show();
                    $(".yp-gradient-pointer-area").removeClass("gradient-pointer-no-cursor");
                    $(".yp_background_assets,.yp-gradient-section,.yp_nice_colors_area,.yp_meterial_colors_area,.yp_flat_colors_area").hide();
                    $(".yp-bg-img-btn,.yp-flat-colors,.yp-meterial-colors,.yp-nice-colors,.yp-gradient-pointer,.yp-gradient-btn,#border-type-group .yp-radio.active").removeClass("active");

                    gui_update();

                }

            }


            /* ---------------------------------------------------- */
            /* Data element                                         */
            /* ---------------------------------------------------- */
            function the_editor_data(){
                return iframe.find(".yp-styles-area");
            }


            /* ---------------------------------------------------- */
            /* Getting Stylizer data                                */
            /* ---------------------------------------------------- */
            function get_editor_data() {
                var data = the_editor_data().html();
                data = data.replace(/</g, 'YP|@');
                data = data.replace(/>/g, 'YP@|');
                return data;
            }


            /* ---------------------------------------------------- */
            /* Getting cleaned CSS data                             */
            /* ---------------------------------------------------- */
            function get_clean_css(a) {

                var data = get_css_by_screensize('desktop');

                // Adding break
                data = data.replace(/\)\{/g, "){\r").replace(/\)\{/g, "){\r");

                // Clean spaces for nth-child and not.
                var ars = Array(
                    "nth-child",
                    "not",
                    "lang",
                    "nth-last-child",
                    "nth-last-of-type",
                    "nth-of-type"
                );

                for(var ai = 0; ai < ars.length; ai++){

                    // Reg
                    var k = new RegExp(ars[ai] + "\\((.*?)\\)\{\r\r", "g");

                    // Replace
                    data = data.replace(k, ars[ai] + "\($1\)\{");

                }


                if (iframe.find(".yp_current_styles").length > 0) {

                    var mediaArray = [];

                    iframe.find(".yp_current_styles").each(function() {
                        var v = $(this).attr("data-size-mode");

                        if ($.inArray(v, mediaArray) === -1 && v != 'desktop') {
                            mediaArray.push(v);
                        }
                    });

                    $.each(mediaArray, function(i, v) {

                        var q = get_css_by_screensize(v);

                        // Add extra tab for media query content.
                        q = "\t" + q.replace(/\r/g, '\r\t').replace(/\t$/g, '').replace(/\t$/g, '');

                        if (v == 'tablet') {
                            v = '(min-width: 768px) and (max-width: 991px)';
                        }

                        if (v == 'mobile') {
                            v = '(max-width:767px)';
                        }

                        if(isDefined(v)){
                            data = data + "\r\r@media " + v + "{\r\r" + q + "}";
                        }

                    });

                }

                if (a === true) {
                    data = data.replace(/\r\ta:a !important;/g, "");
                    data = data.replace(/a:a !important;/g, "");
                    data = data.replace(/a:a;/g, "");
                }

                // Clean first empty lines.
                data = data.replace(/^\r/g, '').replace(/^\r/g, '');

                data = data.replace(/\}\r\r\r\r@media/g, '}\r\r@media');

                return data;

            }


            /* ---------------------------------------------------- */
            /* Getting CSS styles by selector                       */
            /* ---------------------------------------------------- */
            function get_css_by_screensize(size) {

                if (iframe.find(".yp_current_styles").length <= 0) {
                    return '';
                }

                var totalCreated, classes, selector, data;

                totalCreated = '';

                iframe.find(".yp_current_styles:not(.yp_step_end)[data-size-mode='" + size + "']").each(function() {

                    if (!$(this).hasClass("yp_step_end")) {

                        if ($(this).first().html().indexOf("@media") != -1) {
                            data = $(this).first().html().split("{")[1] + "{" + $(this).first().html().split("{")[2].replace("}}", "}");
                        } else {
                            data = $(this).first().html();
                        }

                        data = data.replace(/\/\*(.*?)\*\//g, "");

                        selector = data.split("{")[0];

                        totalCreated += selector + "{\r";

                        classes = $(this).data("style");

                        iframe.find("style[data-style=" + classes + "][data-size-mode='" + size + "']").each(function() {

                            var datai;
                            if ($(this).first().html().indexOf("@media") != -1) {
                                datai = $(this).first().html().split("{")[1] + "{" + $(this).first().html().split("{")[2].replace("}}", "}");
                            } else {
                                datai = $(this).first().html();
                            }

                            totalCreated += "\t" + datai.split("{")[1].split("}")[0] + ';\r';

                            $(this).addClass("yp_step_end");

                        });

                        totalCreated += "}\r\r";

                        $(this).addClass("yp_step_end");

                    }

                });

                iframe.find(".yp_step_end").removeClass("yp_step_end");

                return totalCreated;

            }

            
            /* ---------------------------------------------------- */
            /* Toggle background image show element                 */
            /* ---------------------------------------------------- */
            $("#background-image-group .yp-none-btn,#background-image-group .yp-disable-btn").click(function(e) {
                if(e.originalEvent){
                    $("#background-image-group .yp-background-image-show").toggle();
                }
            });


            /* ---------------------------------------------------- */
            /* Getting duration MS from CSS Duration                */
            /* ---------------------------------------------------- */
            function duration_ms(durations){

                durations = durations.toString();
                durations = durations.replace(/ms/g,"");

                // result
                var duration = 0;
                var ms;

                // Is multi durations?
                if(durations.indexOf(",") != -1){

                    var durationsArray = durations.split(",");

                    for(var i = 0; i < durationsArray.length; i++){

                        var val = durationsArray[i];
                        
                        // Has dot?
                        if(val.indexOf(".") != -1){

                            ms = parseFloat(val).toString().split(".")[1].length;
                            val = val.replace(".","").toString();

                            if(ms == 2){
                                val = val.replace(/s/g, "0");
                            }else if(ms == 1){
                                val = val.replace(/s/g, "00");
                            }

                        }else{
                            val = val.replace(/s/g, "000");
                        }

                        duration = parseFloat(duration) + parseFloat(val);

                    }

                    return duration;

                }else{

                    // Has dot?
                    if(durations.indexOf(".") != -1){

                        ms = parseFloat(durations).toString().split(".")[1].length;
                        durations = durations.replace(".","").toString();

                        if(ms == 2){
                            durations = durations.replace(/s/g, "0");
                        }else if(ms == 1){
                            durations = durations.replace(/s/g, "00");
                        }

                    }else{
                        durations = durations.replace(/s/g, "000");
                    }

                    return durations;

                }

            }


            /* ---------------------------------------------------- */
            /* Get inserted style by selector and rule              */
            /* ---------------------------------------------------- */
            function get_data_value(selector,css,check,size){

                // Defaults
                var valueDetail = false, dataContent = '', hasInFullCSS = false;

                // Size mean media-size.
                if(isUndefined(size)){
                    size = get_media_condition();
                }

                // replace fake rules as scale-transfrom.
                var cssData = get_css_id(css);

                // Get real css Name
                css = cssData[0];

                // scale etc.
                valueDetail = cssData[1];

                // Get current selector
                if(selector == null){
                    selector = get_current_selector();
                }

                // Selection. :hover etc.
                var selection = $('body').attr('data-yp-selector');

                if (isUndefined(selection)) {
                    selection = '';
                } else {
                    selector = add_class_to_body(selector, 'yp-selector-' + selection.replace(':', ''));
                }

                // Find
                var style = iframe.find('.' + get_id(selector) + '-' + css + '-style[data-size-mode="'+size+'"]');


                // If there
                if (style.length === 0){

                    // Check it from non-processed CSS source.
                    if(iframe.find("#yp-css-data-full").length > 0 && valueDetail == null){

                        // CSS
                        var source = iframe.find("#yp-css-data-full").html();
                        source = get_minimized_css(source, false);
                        
                        // Selector regex
                        var selectorRegex = new RegExp(selector_regex(selector) + "(\\s+)?{(.*?)"+selector_regex(css)+":(.*?);(.*?)}", "g");

                        // Desktop selector match
                        if(size == 'desktop'){

                            // Non media CSS Codes
                            var desktopSource = source.replace(/@media(.*?)\}\}/g, '');

                            // Check if match
                            if(selectorRegex.test(desktopSource)){
                                hasInFullCSS = true;
                                dataContent = desktopSource.match(selectorRegex).toString();
                            }

                        }else{

                            // Media Regex
                            var mediaRegex = new RegExp("@media(\\s+)"+selector_regex(size)+"(\\s+)?{(.*?)}(\\s+)?}", "g");

                            // Get target media content
                            var mediaContent = source.match(mediaRegex);

                            // continue if valid content
                            if(mediaContent != null){

                                // test
                                if(selectorRegex.test(mediaContent.toString())){
                                    hasInFullCSS = true;
                                    dataContent = desktopSource.match(selectorRegex).toString();
                                }

                            }

                        }

                        // Format data for espace_data_value func
                        if(dataContent.indexOf(":") != -1){
                            dataContent = dataContent.match(new RegExp(selector_regex(css) + ":(.*?)" + "(;|})", "g")).toString();
                            dataContent = selector + "{" + dataContent + "}";
                        }

                        // Try search in full CSS and return
                        if(hasInFullCSS == true && check == true){
                            return true;
                        }

                        if(hasInFullCSS == true && check == false){
                            return escape_data_value(dataContent);
                        }

                    }

                    // No CSS, No Style.
                    // Exit.
                    return false;

                // If has style, continue;
                }else if(check == true){

                    if(valueDetail != null){ // if has detail

                        if(style.html().match(new RegExp(valueDetail,"g"))){
                            return true;
                        }else{
                            return false;
                        }

                    }else{ // No detail
                        return true;
                    }

                }

                // Get Data
                dataContent = style.html();

                // get rule value by an css style string.
                return escape_data_value(dataContent);

            }


            /* ---------------------------------------------------- */
            /* Espace CSS rule value by CSS code                    */
            /* ---------------------------------------------------- */
            function escape_data_value(data){

                // Defaults
                var value,dataNew;

                if(data === null || data === undefined || data === ''){
                    return false;
                }

                data = data.replace(/\/\*(.*?)\*\//g, "");

                // HasMedia?
                if(data.indexOf("@media") != -1){

                    // Get media content
                    dataNew = data.match(/\){(.*?)\}$/g);

                    // re check for media.
                    if(dataNew == null){
                        dataNew = data.match(/\{(.*?)\}$/g);
                    }

                }else{

                    dataNew = data;

                }

                // Value
                value = dataNew.toString().split(/:(.+)/)[1].split("}")[0];

                // Be sure
                if(!value){
                    return false;
                }

                // who do like important tag?.
                value = value.replace(/\s+?!important/g,'').replace(/\;$/g,'').trim();

                // return
                return value;

            }


            /* ---------------------------------------------------- */
            /* Get real CSS name and replace fake rules             */
            /* as scale transform. param1: real CSS name,          */
            /* param2: [scale]-transfrom. I.E "scale".              */
            /* ---------------------------------------------------- */
            function get_css_id(css){

                var cssDetail = null;

                // No webkit
                css = css.replace(/\-webkit\-/g,'');

                // Update transfrom parts
                if(css.indexOf("-transform") != -1 && css != 'text-transform'){

                    // CSS
                    cssDetail = css.replace(/-transform/g,'');
                    css = 'transform';
                    cssDetail = cssDetail.replace(/\-/g,"");

                }

                // Update filter parts
                if(css.indexOf("-filter") != -1){

                    // CSS
                    cssDetail = css.replace(/-filter/g,'');
                    css = 'filter';

                }

                // Update filter parts
                if(css.indexOf("box-shadow-") != -1){

                    // CSS
                    css = 'box-shadow';
                    cssDetail = css.replace(/box-shadow-/g,'');

                }

                return [css,cssDetail];

            }


            /* ---------------------------------------------------- */
            /* Set Default Option Value                             */
            /* ---------------------------------------------------- */
            function set_default_value(id) {

                // Get Selector
                var selector = get_current_selector();

                // Set parent element as current
                if(id == 'perspective'){

                    // Cache current
                    var oldSelector = selector;

                    // clean cache
                    body.removeAttr("data-clickable-select");

                    // Update selector var
                    selector = $.trim(get_parents(get_selected_element().parent(), "default"));

                    // set old as cache again
                    body.attr("data-clickable-select",oldSelector);

                }

                // Get Element Object
                var the_element = iframe.find(selector);

                // Adding animation classes to element
                if (id == 'animation-name' || id == 'animation-iteration-count' || id == 'animation-duration' || id == 'animation-delay'){
                    the_element.addClass("yp_onscreen yp_hover yp_click yp_focus");
                }

                // Get styleAttr
                var styleData = the_element.attr("style");

                // Remove style attr before getting value for position id.
                if(id == 'position'){
                    if (isUndefined(styleData)) {
                        styleData = '';
                    }else{
                        the_element.removeAttr("style");
                    }
                }

                setTimeout(function() {

                    // Current media size
                    var size = get_media_condition();

                    // Default
                    var ypEvent = '';

                        // onscreen event
                    if (iframe.find('[data-style="' + elementID + get_id(".yp_onscreen") + '"][data-size-mode="'+size+'"]').length > 0) {
                        ypEvent = 'yp_onscreen';
                    }

                    // click event
                    if (iframe.find('[data-style="' + elementID + get_id(".yp_click") + '"][data-size-mode="'+size+'"]').length > 0) {
                        ypEvent = 'yp_click';
                    }

                    // hover event
                    if (iframe.find('[data-style="' + elementID + get_id(".yp_hover") + '"][data-size-mode="'+size+'"]').length > 0) {
                        ypEvent = 'yp_hover';
                    }

                        // Focus event
                    if (iframe.find('[data-style="' + elementID + get_id(".yp_focus") + '"][data-size-mode="'+size+'"]').length > 0) {
                        ypEvent = 'yp_focus';
                    }

                    // hover event default
                    if (mainBody.hasClass("yp-selector-hover") && ypEvent == '') {
                        ypEvent = 'yp_hover';
                    }

                    // Focus event default
                    if (mainBody.hasClass("yp-selector-focus") && ypEvent == '') {
                        ypEvent = 'yp_focus';
                    }

                        // default is onscreen
                    if (isUndefined(ypEvent) || ypEvent == '') {
                        ypEvent = 'yp_onscreen';
                    }


                    // replace fake rules as scale-transfrom.
                    var ruleID = get_css_id(id);

                    // Get details
                    var elementID = ruleID[0];
                    var cssDetail = ruleID[1];

                    // remove reset-enable class if is not a fake option.
                    if(get_css_id(id)[1] == null){
                        $("#" + id + "-group").removeClass("reset-enable");
                    }

                    // Has editor style?
                    if (id == 'animation-name' || id == 'animation-iteration-count' || id == 'animation-duration' || id == 'animation-delay'){

                        if(get_data_value(selector+"."+ypEvent,id,true)){
                            $("#" + id + "-group").addClass("reset-enable");
                        }

                    }else{
                        
                        if(get_data_value(selector,id,true) && id.indexOf("-transform") == -1 && id.indexOf("box-shadow-") == -1){
                            $("#" + id + "-group").addClass("reset-enable");
                        }

                    }
                    

                    // data is default value
                    var data,numberData;

                    // Getting CSS Data. (Animation play not an CSS rule.)
                    if (id != 'animation-play' && id != 'border-width' && id != 'border-color' && id != 'border-style' && elementID != 'transform') {
                        data = the_element.css(elementID);
                        numberData = number_filter(data);
                    }

                    // If data has at CSS editor, get it
                    if(get_data_value(selector,id,true) == true){
                        data = get_data_value(selector,id,false);
                        numberData = number_filter(data);
                    }

                    // Not set auto for top and left
                    if(id == 'top' || id == 'left'){
                        if(data == 'auto'){
                            data = '0px';
                            numberData = 0;
                        }
                    }

                    // Border: default is ALL
                    if(id == 'border-type'){

                        // Update only if not have a active radio.
                        if($("#border-type-group").find(".yp-radio.active").length == false){
                            data = 'all';
                        }

                    }

                    // Getting format: px, em, etc.
                    var format = alfa_filter(data).replace(/(\.|\,)/g,'');

                    // Chome return "rgba(0,0,0,0)" if no background color,
                    // its is chrome hack.
                    if (id == 'background-color' && data == 'rgba(0, 0, 0, 0)') {
                        data = 'transparent';
                    }

                    // checks
                    if(id == 'position' && data == 'relative' && styleData.indexOf("relative") != -1){
                        data = 'static';
                    }

                    // add deleted style attr again
                    if(id == 'position'){
                        if(!isUndefined(styleData)){
                            the_element.attr(styleData);
                        }
                    }

                    // Check border style
                    var top;
                    if(id == 'border-style'){

                        data = 'solid';

                        top = the_element.css("borderTopStyle");

                        if(top == the_element.css("borderLeftStyle") && top == the_element.css("borderRightStyle") && top == the_element.css("borderBottomStyle")){
                            data = top;
                        }
                        
                    }

                    // Check border width
                    if(id == 'border-width'){

                        data = '0px';
                        numberData = 0;

                        top = the_element.css("borderTopWidth");

                        if(top == the_element.css("borderLeftWidth") && top == the_element.css("borderRightWidth") && top == the_element.css("borderBottomWidth")){
                            data = top;
                            numberData = number_filter(top);
                        }
                        
                    }

                    // Check border color
                    if(id == 'border-color'){

                        data = the_element.css("color");

                        top = the_element.css("borderTopColor");

                        if(top == the_element.css("borderLeftColor") && top == the_element.css("borderRightColor") && top == the_element.css("borderBottomColor")){
                            data = top;
                        }
                        
                    }


                    // Check if margin left/right is auto or else.
                    if(id == 'margin-left' || id == 'margin-right'){

                        var frameWidth = iframe.width();

                        var marginLeft = parseFloat(the_element.css("marginLeft"));
                        var width = parseFloat(the_element.css("width"));

                        // Full in frame with margins
                        if(frameWidth == (marginLeft * 2) + width && marginLeft > 0){

                            data = 'auto';
                            numberData = 0;

                        // Full in parent with margins
                        }else if(the_element.parent().length > 0){

                            if(parseFloat(the_element.parent().width()) == ((marginLeft * 2) + width) && marginLeft > 0){
                                data = 'auto';
                                numberData = 0;
                            }

                        }

                    }


                    // some script for custom CSS Rule: animation-play
                    if (id == 'animation-play') {
                        data = ypEvent;
                    }

                
                    // Play if is animation name.
                    if (id == 'animation-name' && data != 'none'){

                        // Add class.
                        body.addClass("yp-hide-borders-now yp-force-hide-select-ui");

                        var time = the_element.css("animationDuration");
                        var timeDelay = the_element.css("animationDelay");
                        
                        // Getting right time delay if have multiple animations.
                        var newDelay = get_multiple_delay(time,timeDelay);

                        if(newDelay !== false){
                            timeDelay = newDelay;
                        }else if(isUndefined(timeDelay)){
                            timeDelay = 0;
                        }else{
                            timeDelay = duration_ms(timeDelay); // timeDelay
                        }

                        if (isUndefined(time)){
                            time = 1000;
                        }else{
                            time = duration_ms(time); // Time
                        }
                        
                        time = parseFloat(time) + parseFloat(timeDelay);

                        if(time === 0){
                            time = 1000;
                        }

                        time = time + 100;

                        clear_animation_timer();

                        window.animationTimer2 = setTimeout(function() {

                            element_animation_end();

                            // Update.
                            draw();

                            // remove class.
                            body.removeClass("yp-hide-borders-now yp-force-hide-select-ui");

                        }, time);

                    }


                
                    // filter = explode filter data to parts
                    if (elementID == 'filter'){

                        // Try to get css with webkit prefix.
                        if (data === null || data == 'none' || data === undefined) {
                            data = the_element.css("-webkit-filter"); // for chrome.
                        }

                        // Special default values for filter css rule.
                        if (data != 'none' && data !== null && data !== undefined) {

                            // Get brightness, blur etc from filter data.
                            data = data.match(new RegExp(cssDetail+"\\((.*?)\\)","g"));

                            // is?
                            if (isDefined(data)) {

                                // replace prefixes
                                data = data.toString().replace("deg", "").replace("hue-rotate(", "").replace(")", "");

                                // Update data
                                data = number_filter(data);

                                // Update number data
                                numberData = data;

                            }else{

                                // Set default
                                data = 'disable';
                                numberData = 0;

                            }

                        }else{

                            // Set default
                            data = 'disable';
                            numberData = 0;

                        }

                    }


                    // Font weight fix.
                    if (id == 'font-weight'){

                        if(data == 'bolder'){ data = '700'; }
                        if(data == 'bold'){ data = '600'; }
                        if(data == 'normal'){ data = '400'; }
                        if(data == 'lighter'){ data = '300'; }

                    }


                    // transform = explode transform data to parts
                    if (elementID == 'transform') {

                        // Get transfrom style from editor data.
                        data = get_data_value(selector,id,size);

                        // Getting transform value from HTML Source ANIM.
                        var styleString = null;
                        if (is_animate_creator()){

                            var currentScene = parseInt(mainBody.attr("data-anim-scene").replace("scene-", ""));

                            // Check scenes by scenes for get transfrom data.
                            for(var transfromIndex = 0; transfromIndex < 6; transfromIndex++){

                                // style element
                                var styleOb = iframe.find('.scene-' + (currentScene - transfromIndex) + ' .scenes-transform-style');

                                // Get
                                if (styleOb.length > 0) {
                                    styleString = styleOb.last().html();
                                    break;
                                }

                            }

                            // Get scene transform data else default.
                            if(styleString != null){
                                data = escape_data_value(styleString);
                            }

                        } // Anim end.

                        
                        // explode transform data
                        if (data != 'none' && data !== false && data !== undefined) {

                            // Get brightness, blur etc from filter data.
                            data = data.match(new RegExp(cssDetail+"\\((.*?)\\)","g"));

                            // is?
                            if (isDefined(data)) {

                                // String.
                                data = data.toString();

                                // Update data
                                data = number_filter(data);

                                // Update number data
                                numberData = data;

                            }else{

                                // Set default
                                data = 'disable';
                                numberData = 0;

                            }

                        }else{

                            // Set default
                            data = 'disable';
                            numberData = 0;

                        }

                    }

                    // Animation creator; don't append 0s duration.
                    if (id == "animation-duration" && is_animate_creator() === true) {
                        if (data == '0s' || data == '0ms') {
                            return false;
                        }
                    }

                    // Set auto
                    if(id == 'min-width' || id == 'min-height'){
                        if(parseFloat(data) == 0){
                            data = 'auto';
                        }
                    }

                    // Check bottom and set auto
                    if (id == 'bottom') {

                        if (parseFloat(the_element.css("top")) + parseFloat(the_element.css("bottom")) === 0) {
                            data = 'auto';
                        }
                    }

                    // Check right and set auto
                    if (id == 'right') {
                        if (parseFloat(the_element.css("left")) + parseFloat(the_element.css("right")) === 0) {
                            data = 'auto';
                        }
                    }

                    // Box Shadow.
                    if (elementID == 'box-shadow' && data != 'none' && data !== null && data !== undefined) {

                        // Box shadow color default value.
                        if (id == 'box-shadow-color') {

                            // Hex
                            if (data.indexOf("#") != -1) {
                                if (data.split("#")[1].indexOf("inset") == -1) {
                                    data = $.trim(data.split("#")[1]);
                                } else {
                                    data = $.trim(data.split("#")[1].split(' ')[0]);
                                }
                            } else {
                                if (data.indexOf("rgb") != -1) {
                                    data = data.match(/rgb(.*?)\((.*?)\)/g).toString();
                                }
                            }

                        }

                        // split all box-shadow data.
                        var numbericBox = data.replace(/rgb(.*?)\((.*?)\) /g, '').replace(/ rgb(.*?)\((.*?)\)/g, '').replace(/inset /g, '').replace(/ inset/g, '');

                        // shadow horizontal value.

                        if (id == 'box-shadow-horizontal') {
                            data = numbericBox.split(" ")[0];
                            numberData = number_filter(data);
                        }

                        // shadow vertical value.
                        if (id == 'box-shadow-vertical') {
                            data = numbericBox.split(" ")[1];
                            numberData = number_filter(data);
                        }

                        // Shadow blur radius value.
                        if (id == 'box-shadow-blur-radius') {
                            data = numbericBox.split(" ")[2];
                            numberData = number_filter(data);
                        }

                        // Shadow spread value.
                        if (id == 'box-shadow-spread') {
                            data = numbericBox.split(" ")[3];
                            numberData = number_filter(data);
                        }

                    }

                    // if no info about inset, default is no.
                    if (id == 'box-shadow-inset') {

                        if (isUndefined(data)) {

                            data = 'no';

                        } else {

                            if (data.indexOf("inset") == -1) {
                                data = 'no';
                            } else {
                                data = 'inset';
                            }

                        }

                    }

                    // option element.
                    var the_option = $("#yp-" + id);

                    // option element parent of parent.
                    var id_prt = the_option.parent().parent();

                    // if special CSS, get css by CSS data.
                    // ie for parallax. parallax not a css rule.
                    // yellow pencil using css engine for parallax Property.
                    if (the_element.css(id) === undefined && iframe.find('.' + elementID + '-' + id + '-style').length > 0) {

                        data = get_data_value(selector,id);
                        numberData = number_filter(data);

                    } else if (isUndefined(the_element.css(id))) { // if no data, use "disable" for default.

                        if (id == 'background-parallax') {
                            data = 'disable';
                        }

                        if (id == 'background-parallax-speed') {
                            data = 'disable';
                        }

                        if (id == 'background-parallax-x') {
                            data = 'disable';
                        }

                    }

                    // IF THIS IS A SLIDER
                    if (the_option.hasClass("wqNoUi-target")){

                        // if has multi duration
                        if(id == 'animation-duration' && data.indexOf(",") != -1){
                            data = '1s'; // Reading as 1second
                            format = 's';
                            numberData = '1';
                            $("#animation-duration-group").addClass("hidden-option");
                        }else if(id == 'animation-duration'){
                            $("#animation-duration-group").removeClass("hidden-option");
                        }


                        // if has multi delay
                        if(id == 'animation-delay' && data.indexOf(",") != -1){
                            data = '0s'; // Reading as 1second
                            format = 's';
                            numberData = '0';
                            $("#animation-delay-group").addClass("hidden-option");
                        }else if(id == 'animation-delay'){
                            $("#animation-delay-group").removeClass("hidden-option");
                        }

                        // If not inline
                        if (the_element.css("display") != 'inline' || the_element.css("display").indexOf("table") != -1) {

                            // if has children and id is height 
                            if (id == 'height' && the_element.children().length > 0 && the_element.children().length < 12) {

                                var elHeight = the_element.css("height");
                                var tHeight;

                                // parent is display block
                                the_element.children().each(function(){

                                    tHeight = $(this).css("height");

                                    if(elHeight == tHeight){

                                        data = 'auto';
                                        return false;

                                    }

                                });

                            }

                        }


                        // if no data, active none option.
                        if (data == 'none' || data == 'auto' || data == 'inherit' || data == 'initial'){
                            if(id_prt.find(".yp-none-btn").hasClass("active")){
                                id_prt.find(".yp-none-btn").trigger("click").trigger("click");
                            }else{
                                id_prt.find(".yp-none-btn").trigger("click");
                            }
                            format = 'px';
                        } else {
                            id_prt.find(".yp-none-btn.active").trigger("click"); // else disable none option.
                        }

                        format = $.trim(format);

                        // be sure format is valid.
                        if (format === '' || format == 'px .px' || format == 'px px') {
                            format = 'px';
                        }

                        // be sure format is valid.
                        if (format.indexOf("px") != -1) {
                            format = 'px';
                        }

                        // Default value is 1 for transform scale.
                        if (numberData == '' && id == 'scale-transform') {
                            numberData = 1;
                        }

                        // Default value is 1 for filter
                        if (numberData == '' && id == 'brightness-filter') {
                            numberData = 1;
                        }

                        // Default value is 1 for filter
                        if (numberData == '' && id == 'contrast-filter') {
                            numberData = 1;
                        }

                        // Default value is 1 for filter
                        if (numberData == '' && id == 'saturate-filter') {
                            numberData = 1;
                        }

                        // default value is 1 for opacity.
                        if (numberData == '' && id == 'opacity') {
                            numberData = 1;
                        }

                        // If no data, set zero value.
                        if (numberData == '') {
                            numberData = 0;
                        }

                        var range = id_prt.data("px-range").split(",");

                        var $min = parseInt(range[0]); // mininum value
                        var $max = parseInt(range[1]); // maximum value

                        // Check values.
                        if (numberData < $min) {
                            $min = numberData;
                        }

                        if (numberData > $max) {
                            $max = numberData;
                        }

                        // Speacial max and min limits for CSS size rules.
                        if (id == 'width' || id == 'max-width' || id == 'min-width' || id == 'height' || id == 'min-height' || id == 'max-height') {
                            $max = parseInt($max) + (parseInt($max) * 1.5);
                            $min = parseInt($min) + (parseInt($min) * 1.5);
                        }

                        // if width/height is same with windows width, set 100%!
                        // Note: browsers always return value in PX format.
                        if (the_element.css("display") != 'inline') {

                            // Width
                            if (id == 'width' && the_element.parent().length > 0) {

                                // is px and display block
                                if(format == 'px' && the_element.parent().css("display") != 'inline' && the_element.parent().css("display") != 'inline-flex' && the_element.parent().css("display").indexOf("table") == -1){

                                    var parentWidth = the_element.parent().width();

                                    // if width is same with parent width, so set 100%!
                                    if (parentWidth == parseInt(numberData)) {
                                        numberData = '100';
                                        format = '%';
                                    }

                                    // if width is 50% of parent width, so set 50%!
                                    if (parseInt(parentWidth/2) == (parseInt(numberData))) {
                                        numberData = '50';
                                        format = '%';
                                    }

                                    // if width is 25% of parent width, so set 25%!
                                    if (parseInt(parentWidth/4) == (parseInt(numberData))) {
                                        numberData = '25';
                                        format = '%';
                                    }

                                    // if width is 20% of parent width, so set 20%!
                                    if (parseInt(parentWidth/5) == (parseInt(numberData))) {
                                        numberData = '20';
                                        format = '%';
                                    }

                                }

                            }

                            // if  height is 100% of window height!
                            if (id == 'height' && parseInt($(window).height()) == parseInt(numberData) && format == 'px') {
                                numberData = '100';
                                format = 'vh';
                            }

                        }

                        // max and min for %.
                        if (format == '%'){
                            range = $('#' + id + '-group').attr("data-pcv").split(",");
                            $min = range[0];
                            $max = range[1];
                        }else if(format == 'em'){
                            range = $('#' + id + '-group').attr("data-emv").split(",");
                            $min = range[0];
                            $max = range[1];
                        }

                        // Raund
                        numberData = Math.floor(numberData * 100) / 100;

                        // Just int
                        if(id == 'height' || id == 'font-size' || id.indexOf("margin-") || id.indexOf("padding-") || id.indexOf("border-width") || id.indexOf("-radius") || id.indexOf("z-index") || id == 'top' || id == 'right' || id == 'bottom' || id == 'left'){
                            numberData = parseInt(numberData);
                        }

                        the_option.wqNoUiSlider({
                            range: {
                                'min': parseInt($min),
                                'max': parseInt($max)
                            },
                            start: parseFloat(numberData)
                        }, true);

                        // Set new value.
                        the_option.val(numberData);

                        // Update the input.
                        $('#' + id + '-value').val(numberData);

                        format = format.replace(/\./g,'');

                        // set format of value. px, em etc.
                        $("#" + id + "-after").val(format);

                        return false;

                        // IF THIS IS A SELECT TAG
                    } else if (the_option.hasClass("input-autocomplete")) {

                        // Checking font family settings.
                        if (id == 'font-family' && typeof data != 'undefined') {

                            data = $.trim(data.replace(/"/g, "").replace(/'/g, ""));

                        }

                        if (isDefined(data)) {

                            // Append default font family to body. only for select font family.
                            if ($(".yp-font-test-" + get_basic_id($.trim(data.replace(/ /g, '+')))).length === 0 && id == 'font-family') {

                                // If safe font, stop.
                                if (is_safe_font(data) === false) {

                                    // Be sure its google font.
                                    if (is_google_font(data)) {

                                        // Append always to body.
                                        body.append("<link rel='stylesheet' class='yp-font-test-" + get_basic_id($.trim(data.replace(/ /g, '+'))) + "'  href='https://fonts.googleapis.com/css?family=" + $.trim(data.replace(/ /g, '+')) + ":300italic,300,400,400italic,500,500italic,600,600italic,700,700italic' type='text/css' media='all' />");

                                    }

                                }

                            }

                            // If have data, so set!
                            if (id == 'font-family' && data.indexOf(",") == -1) {

                                // Getting value
                                var value = $("#yp-font-family-data option").filter(function() {
                                    return $(this).text() === data;
                                }).first().attr("value");

                                // Select by value.
                                if (value !== undefined) {

                                    value = value.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                        return letter.toUpperCase();
                                    });

                                    the_option.val(value);
                                } else {

                                    data = data.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                        return letter.toUpperCase();
                                    });

                                    the_option.val(data);
                                }

                            } else {

                                // set value.
                                the_option.val(data);

                            }

                            if (id == 'font-family') {
                                $("#yp-font-family,#yp-font-weight").each(function() {
                                    $(this).css("fontFamily", data);
                                });
                            }

                        }

                        // Active none button.
                        id_prt.find(".yp-btn-action.active").trigger("click");

                        // If data is none, auto etc, so active none button.
                        if (data == id_prt.find(".yp-none-btn").text()) {
                            id_prt.find(".yp-none-btn").trigger("click");
                        }

                        // If not have this data in select options, insert this data.
                        if (the_option.val() === null && data != id_prt.find(".yp-none-btn").text() && data !== undefined) {
                            the_option.val(data);
                        }

                        return false;

                        // IF THIS IS A RADIO TAG
                    } else if (the_option.hasClass("yp-radio-content")) {

                        // Fix background size rule.
                        if (id == 'background-size') {
                            if (data == 'auto' || data == '' || data == ' ' || data == 'auto auto') {
                                data = 'auto auto';
                            }
                        }

                        // If disable, active disable button.
                        if (data == 'disable' && id != 'background-parallax') {
                            id_prt.find(".yp-disable-btn").not(".active").trigger("click");
                        } else {
                            radio_value(the_option, id, data); // else Set radio value.
                        }

                        return false;

                        // IF THIS IS COLORPICKER
                    } else if (the_option.hasClass("wqcolorpicker")) {

                        // Remove active
                        $(".yp-nice-c.active,.yp-flat-c.active,.yp-meterial-c.active").removeClass("active");

                        if (id == 'box-shadow-color') {
                            if (data === undefined || data === false || data == 'none' || data == '') {
                                data = the_element.css("color");
                            }
                        }

                        // Convert to rgb and set value.
                        var rgbd;
                        if (isDefined(data)) {
                            if (data.indexOf("#") == -1) {
                                rgbd = get_color(data);
                            }
                        }

                        // browsers return value always in rgb format.
                        the_option.val(rgbd);
                        the_option.iris('color', data);

                        // If rgba
                        var alpha = 100;
                        if(data.indexOf("rgba") != -1){
                            alpha = $.trim(data.replace(/^.*,(.+)\)/,'$1'));
                            if(alpha.indexOf(".") != -1){
                                alpha = alpha.replace("000.","").replace("00.","").replace("0.","").replace(".","");
                                if(alpha.length == 1){
                                    alpha = alpha.toString()+"0";
                                }
                                alpha = alpha.replace(/^0/, "");
                            }
                        }

                        // Update iris alpha.
                        id_prt.find(".cs-alpha-slider").slider('value',alpha); 

                        // Set current color on small area.
                        the_option.parent().find(".wqminicolors-swatch-color").css("backgroundColor", rgbd);

                        // If transparent
                        if (data == 'transparent' || data == '') {
                            id_prt.find(".yp-disable-btn.active").trigger("click");
                            id_prt.find(".yp-none-btn:not(.active)").trigger("click");
                            the_option.parent().find(".wqminicolors-swatch-color").css("backgroundColor", "transparent");
                        } else {
                            id_prt.find(".yp-none-btn.active").trigger("click");
                        }

                        if (id == 'box-shadow-color') {
                            $("#box-shadow-color-group .wqminicolors-swatch-color").css("backgroundColor", data);
                        }

                        return false;

                        // IF THIS IS INPUT OR TEXTAREA
                    } else if (the_option.hasClass("yp-input") || the_option.hasClass("yp-textarea")) {

                        // clean URL() prefix for background image. no gradients
                        if (data != 'disable' && id == "background-image" && data != window.location.href && data.indexOf("linear-gradient(") == -1) {

                            // If background-image is empty.
                            var a = $(document).find("#iframe").attr("src");
                            var b = data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "");

                            // Cleans if no data
                            if (a == b) {
                                data = '';
                            }

                            // Cleans
                            the_option.val(data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, ""));

                            // remove class from active pattern
                            $(".yp_bg_assets").removeClass("active");

                            // if it is a pattern
                            if (data.indexOf("yellow-pencil") != -1) {

                                // Find the pattern and add active class
                                $(".yp_bg_assets[data-url='" + data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "") + "']").addClass("active");

                                $(".yp-background-image-show").remove();

                            } else { // if image

                                // Remove background image
                                $(".yp-background-image-show").remove();

                                // Get img URL
                                var imgSrc = data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "");

                                // check if image is valid
                                if (imgSrc.indexOf("//") != -1 && imgSrc != '' && imgSrc.indexOf(".") != -1) {

                                    // Append the view image
                                    $("#yp-background-image").after("<img src='" + imgSrc + "' class='yp-background-image-show' />");

                                }

                            }

                        } else {

                            // remove background image
                            $(".yp-background-image-show").remove();

                        }



                        // clean URL() prefix for list style image.
                        if (data != 'disable' && id == "list-style-image" && data != window.location.href) {

                            // If list-style-image is empty.
                            var a = $(document).find("#iframe").attr("src");
                            var b = data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "");

                            // Cleans if no data
                            if (a == b) {
                                data = '';
                            }

                            // Cleans
                            the_option.val(data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, ""));

                        }


                        // If is background image and has gradient
                        if(id == 'background-image' && data.indexOf("linear-gradient(") != -1){

                            // Set data
                            the_option.val(data);

                            // Update gradient tool
                            read_gradient(data);

                            // Open gradient tool
                            window.documentClick = false;

                            $(".yp-gradient-btn:not(.active)").trigger("click");
                                
                            setTimeout(function(){
                                window.documentClick = true;
                            },50);

                        }


                        // If no data, active none button.
                        if (data == 'none') {
                            id_prt.find(".yp-none-btn").not(".active").trigger("click");
                            the_option.val(''); // clean value.
                        } else {
                            id_prt.find(".yp-none-btn.active").trigger("click"); // else disable.
                        }

                        // If no data, active disable button.
                        if (data == 'disable') {
                            id_prt.find(".yp-disable-btn").not(".active").trigger("click");
                            the_option.val('');
                        } else {
                            id_prt.find(".yp-disable-btn.active").trigger("click"); // else disable.
                        }

                        return false;

                    }

                }, 20);

            }


            /* ---------------------------------------------------- */
            /* Check if it is an google Font                        */
            /* ---------------------------------------------------- */
            function is_google_font(font) {

                var status = false;
                $('select#yp-font-family-data option').each(function() {
                    if ($(this).text() == font) {
                        status = true;
                        return true;
                    }
                });

                return status;

            }


            /* ---------------------------------------------------- */
            /* Converting selectors to Array                        */
            /* ---------------------------------------------------- */
            function get_selector_array(selector){

                var selectorArray = [];

                // Clean
                selector = $.trim(selector);

                // Clean multispaces
                selector = selector.replace(/\s\s+/g, ' ');

                // Clean spaces before ">,+,~" and after
                selector = selector.replace(/(\s)?(\>|\,|\+|\~)(\s)?/g, '$2');

                // Convert > to space 
                selector = selector.replace(/\>/g, ' ');

                selector = $.trim(selector);

                // Check if still there have another selector
                if(selector.indexOf(" ") != -1){

                    var selectorSplit = selector.split(" ");

                    // Split with space
                    var v;
                    for(var i = 0; i < selectorSplit.length; i++){

                        // Clean
                        v = $.trim(selectorSplit[i]);

                        // Push
                        selectorArray.push(v);

                    }

                }else{

                    // Push if single.
                    selectorArray.push(selector);

                }

                var selectorArrayNew = [];

                // Add spaces again
                $.each(selectorArray,function(i,v){
                    selectorArrayNew.push(v.replace(/\~/g,' ~ ').replace(/\+/g,' + '));
                });

                return selectorArrayNew;

            }


            /* ---------------------------------------------------- */
            /* Converting Classes to Array                          */
            /* ---------------------------------------------------- */
            function get_classes_array(classes){

                var classesArray = [];

                // Clean
                classes = $.trim(classes);

                // Clean multispaces
                classes = classes.replace(/\s\s+/g, ' ');

                // Check if still there have another class
                if(classes.indexOf(" ") != -1){

                    var classessplit = classes.split(" ");

                    // Split with space
                    var v;
                    for(var i = 0; i < classessplit.length; i++){

                        // Clean
                        v = $.trim(classessplit[i]);

                        // Push
                        classesArray.push(v);

                    }

                }else{

                    // Push if single.
                    classesArray.push(classes);

                }

                return classesArray;

            }



            /* ---------------------------------------------------- */
            /* PREFERED CLASSES                                     */
            /* ---------------------------------------------------- */
            var preferedClasses = [
                'current-menu-item',
                'active(!singleInspector)',
                'current(!singleInspector)',
                'post',
                'hentry',
                'widget',
                'wp-post-image',
                '(entry|article|post)-title',
                '(entry|article|post)-content',
                '(entry|article|post)-meta',
                'comment-author-admin',
                '([a-zA-Z0-9_-]+)?item',
                'widget-title',
                'widgettitle',
                'next',
                'prev',
                'product',
                'footer',
                'header',
                'sidebar',
                'form-control',
                'footer-top',
                'copyright',
                'menu-item',
                'kc-css-([a-zA-Z0-9_-]+)?',
                'row_inner', // cos row_inner element has a lot bad classes in themify.
                'filterall'
            ];


            /* ---------------------------------------------------- */
            /* FILTERING NEVER-USE CLASSES                          */
            /* ---------------------------------------------------- */
            /* These classes will never be used in the selector.    */
            /* Use only if tag is "div" and not have a alternative. */
            /* ---------------------------------------------------- */
            var blockedClasses = [

                // Classes from a animate.css
                '([a-zA-Z0-9_-]+)?infinite([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?bounce([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?flash([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?pulse([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?rubberBand([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?shake([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?headShake([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?swing([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?tada([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?wobble([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?jello([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hinge([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?fade([a-zA-Z0-9_-]+)?',

                '([a-zA-Z0-9_-]+)?slide([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?(Up|up|Down|down|Left|left|Right|right)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?roll([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?fall([a-zA-Z0-9_-]+)?(In|in|Out|out|Up|up|Down|down|Left|left|Right|right)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?flip([a-zA-Z0-9_-]+)?(In|in|Out|out|Up|up|Down|down|Left|left|Right|right)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?lightSpeed([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?rotate([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?zoom([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?',


                // Post Status classes
                '([a-zA-Z0-9_-]+)?publish([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?draft([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?pending([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?private([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?trash([a-zA-Z0-9_-]+)?',

                // Basic post formats
                '(standard|aside|audio|chat|gallery|image|link|quote|status|video)',

                // Some functional classes
                '([a-zA-Z0-9_-]+)?viewport([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?ltr([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?padding([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?inherit([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?margin([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?relative([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?transparent([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?visibility([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hidden([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?active-slide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?current-slide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?animated([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?draggable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?resize([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?cloned([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?sortable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?status([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?-spacing-yes',
                '([a-zA-Z0-9_-s]+)?-spacing-no',
                '([a-zA-Z0-9_-]+)?clearfix([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?clear([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(clr|clfw)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?clean([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hover([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?default_template([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?ready([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?validate([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?false([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?true([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?loading([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?loaded([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?finished([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?center([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?delay([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?enabled([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?disabled([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?ga-track([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?raw_code([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?raw_html([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?padded([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?bold([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?initialised([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?even([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?odd([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?dismissable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?underlined([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?flippable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?clickable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?gutter([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?animation([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?animate([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?transition([a-zA-Z0-9_-]+)?',

                // Functional 3 party classes
                '([a-zA-Z0-9_-]+)?withbg([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?bg_layout([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?rand',
                'mrg-(btm|top|left|right|tp|lft|rght)-([a-zA-Z0-9_-]+)',
                'is([_-])([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)none',

                // Wordpress Core
                'page([_-])item',
                '([a-zA-Z0-9_-]+)?object([_-])page',
                '([a-zA-Z0-9_-]+)?closed',
                'thread([_-])alt',
                '([a-zA-Z0-9_-]+)?([_-])has([_-])?([a-zA-Z0-9_-]+)|([a-zA-Z0-9_-]+)?([_-])?has([_-])([a-zA-Z0-9_-]+)',
                'screen([_-])reader([_-])text',
                'tag-link([a-zA-Z0-9_-]+)?',
                'post-no-media',

                // Browser Classes
                '([a-zA-Z0-9_-]+)?opera([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?firefox([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?safari([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?chrome([a-zA-Z0-9_-]+)?',

                // WooCommerce
                '([a-zA-Z0-9_-]+)?product_tag([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?product_cat([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?taxable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?onsale([a-zA-Z0-9_-]+)?',
                'calculated_shipping',
                'currency([a-zA-Z0-9_-]+)?',
                'woocommerce-MyAccount-navigation-link--([a-zA-Z0-9_-]+)?',

                // Etc
                'img-responsive',
                'ls-active',
                'disappear',
                'appear',
                'noSwipe',
                'wow',
                'bootstrap-touchspin-down',
                'section--no',
                'cat-item',
                '([a-zA-Z0-9_-]+)?direction-ltr',
                '([a-zA-Z0-9_-]+)?show-dropdown', // it is a hover class.
                'kc-elm',
                'kc_column',
                'selected',
                'alternate_color', // enfold
                'open-mega-a', // enfold
                'sf-menu',
                'sf-arrows',

                // Bounce after tests
                '([a-zA-Z0-9_-]+)?nojquery([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?js-comp-ver([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?wpb-js-composer([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?-shd',
                '([a-zA-Z0-9_-]+)?with([_-])([a-zA-Z0-9]+)',
                '([a-zA-Z0-9_-]+)?m-t-([0-9])+([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(serif|sans|font|webfont)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?uppercase([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?([_-])(to|from)([_-])(top|left|right|bottom)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(cursor|pointer)([a-zA-Z0-9_-]+)?',
                '(not|no)([_-])([a-zA-Z0-9_-]+)?',
                'ajax',
                'neg-marg',
                '([a-zA-Z0-9_-]+)?video-aspect-ratio-([a-zA-Z0-9_-]+)',
                'lazy',
                'lazy-img'

            ];


            /* ---------------------------------------------------- */
            /* FILTERING UNPREFERED CLASSES                         */
            /* ---------------------------------------------------- */
            /* UnPrefered Classes, these classes is not a priority  */
            /* ---------------------------------------------------- */
            var unPreferedClasses = [

                // Logical
                '([a-zA-Z0-9_-]+)([_-])', // End with -_ 
                '([_-])([a-zA-Z0-9_-]+)', // start with -_
                '([a-zA-Z0-9_-]+)?([_-])([_-])([a-zA-Z0-9_-]+)?', // multiple -_ ex: bad--class--name

                // WordPress Dynamic Classes
                '([a-zA-Z0-9_-]+)?(tag|category|cat)([_-])([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?format([a-zA-Z0-9_-]+)?',
                'menu([_-])item([_-])type([_-])post([_-])type',
                'menu([_-])item([_-])object([_-])page',
                'menu([_-])item([_-])(object|type)([_-])custom',
                'widget_([a-zA-Z0-9_-]+)',
                'bg-([a-zA-Z0-9_-]+)',

                // Modern Columns.
                '([a-zA-Z0-9_-]+)?([_-])(l|m|s|xs)([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?pure([_-])([a-zA-Z0-9_-]+)?([_-])u([_-])[0-9]+([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?col([_-])([a-zA-Z0-9_-]+)?([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?col([_-])([a-zA-Z0-9_-]+)?([_-])offset([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?(medium|large|small)([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?(medium|large|small)([_-])([a-zA-Z0-9_-]+)?([_-])[0-9]+',

                // Bootstrap Classes
                '([a-zA-Z0-9_-]+)?(small|medium|large)([_-])(push|pull)([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?span[0-9]+',
                '([a-zA-Z0-9_-]+)?span([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?col([_-])[0-9]+([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?col([_-])[0-9]+',

                // Classic Grid Columns
                '(column|columns|col)',
                '([a-zA-Z0-9_-]+)(one|two|three|four|five|six|seven|eight|nine|ten|eleven|twelve)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(one|two|three|four|five|six|seven|eight|nine|ten|eleven|twelve)([a-zA-Z0-9_-]+)',

                // Structural
                '([a-zA-Z0-9_-]+)?sticky([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?fixed([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?logged([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?print([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?visible([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?trigger([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?required([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?pull([a-zA-Z0-9_-]+)(left|right)',
                '(left|right)',
                '([a-zA-Z0-9_-]+)?([_-])(yes|no)([_-])([a-zA-Z0-9_-]+)?', // _yes_
                '([a-zA-Z0-9_-]+)?(yes|no)([_-])([a-zA-Z0-9_-]+)?', // yes_
                '([a-zA-Z0-9_-]+)?([_-])(yes|no)([a-zA-Z0-9_-]+)?', // _yes,
                '([a-zA-Z0-9_-]+)?is([_-])active([a-zA-Z0-9_-]+)?', // is_active,
                
                // Dynamic CSS classes.
                '([a-zA-Z0-9_-]+)?background([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?width([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?height([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?position([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?parent([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?color([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?layout([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?invert([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)style([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?scroll([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?equal([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?square([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?([_-])skin([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?skin([_-])([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?toggled([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?style([0-9_-]+)',
                '([a-zA-Z0-9_-]+)?rounded([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?radius([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?type([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?dynamic([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?first',
                '([a-zA-Z0-9_-]+)?last',
                '([a-zA-Z0-9_-]+)?text([_-])justify',
                '([a-zA-Z0-9_-]+)?row([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?border([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?align([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?effect([0-9_-]+)',
                '([a-zA-Z0-9_-]+)?dimension([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?inline-inside([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?corner-pointed([a-zA-Z0-9_-]+)?',

                // General Theme Option Classes
                '([a-zA-Z0-9_-]+)([_-])(on|off)',
                '([a-zA-Z0-9_-]+)default([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)size([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)mobile([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)desktop([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)populated([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?show([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?parallax([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?responsive([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?intense([a-zA-Z0-9_-]+)?',

                // Unyson
                'unyson-page',
                'end',

                // Pagenavi
                'larger',
                'smaller',

                // Buddypress
                'created_group',
                'mini',
                'activity_update',

                // Force Builder
                'forge-block',
                'forge-',

                // Elementor
                'elementor-section-items-middle',

                // Themify
                'themify_builder',
                'module',

                // live composer
                'dslc-post-no-thumb',

                // Woocommerce
                'downloadable',
                'purchasable',
                'instock',

                // Others
                'above',
                'open',

                // Enfold
                'template-page',
                'alpha',
                'units',
                'flex_column_div',
                '([a-zA-Z0-9_-]+)?no-sibling([a-zA-Z0-9_-]+)?',

                // bounce after tests
                '([a-zA-Z0-9_-]+)?float([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(blue|black|red|dark|white|light|green|yellow|purple|pink|orange|brown|gray)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(black|dark|white|light)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?last-child([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?first-child([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)([_-])only',
                '([a-zA-Z0-9_-]+)?(text-left|text-right)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(round|scale|shadow|rotate|default|minimal|animsition|nimation)([a-zA-Z0-9_-]+)?',
                'woocommerce',
                'affix-top',
                'st-menu',
                'circle',
                'vc_figure',
                'vc_general',
                'waves-effect',
                'collapsed',
                'collapse'

            ];


            /* ---------------------------------------------------- */
            /* FILTERING UNPREFED CLASSES                           */
            /* ---------------------------------------------------- */
            /* This filter excluding the structural classes in the  */
            /* selector. ex: col-md-8, exclude it if no need.       */
            /* ---------------------------------------------------- */
            var unPreferedSelectors = [

                // General
                '([a-zA-Z0-9_-]+)?inner([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?overlay([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?container([a-zA-Z0-9_-]+)?',

                // new visual composer
                '([a-zA-Z0-9_-]+)?google-fonts([a-zA-Z0-9_-]+)?',

                // siteorigin
                '([a-zA-Z0-9_-]+)?fl-col-content([a-zA-Z0-9_-]+)?',

                // Enfold
                'av-content-full'

            ];
            
            unPreferedSelectors.concat(blockedClasses);
            unPreferedSelectors.concat(unPreferedClasses);


            /* ---------------------------------------------------- */
            /* SKIP SOME NUMMERIC CLASSES AS NORMAL                 */
            /* ---------------------------------------------------- */
            /* process these  nummeric classes as non nummeric      */
            /* ---------------------------------------------------- */
            var filterNumSkipBest = [
                '([a-zA-Z0-9_-]+)?wpcf7([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?mc4wp([a-zA-Z0-9_-]+)?'
            ];


            /* ---------------------------------------------------- */
            /* EXCLUDE BAD NUMMERIC CLASSES                         */
            /* ---------------------------------------------------- */
            /* Never uses the following nummeric classes            */
            /* ---------------------------------------------------- */
            var blockedDigitalClasses = [
                '([a-zA-Z0-9_-]+)?page([_-])item([_-])([0-9]+)',
                '(vc_|vc-)(.*?)(_|-)[a-zA-Z-0-9]{22,22}',
                'themify_builder_content-([0-9]+)',
                'themify_builder_([0-9]+)_row',
                'tb_([0-9]+)_column',
                'et_pb_image_([0-9]+)',
                '([a-zA-Z0-9_-]+)?(post|page|portfolio|product|work|port|form|video)([_-])([0-9]+)',
                '([a-zA-Z0-9_-]+)?(post|page|portfolio|product|work|port|form|video)([_-])(entry|item|id)([_-])([0-9]+)',
                '([0-9])+px',
                '([a-zA-Z0-9_-]+)?wishlist-([0-9])+',
                'wpbs-bookable-([0-9])+',
                'wpbs-day-([0-9])+',
                '([a-zA-Z0-9_-]+)?rand-([0-9])+',
                '([a-zA-Z0-9_-]+)?(ie|ie8|ie9|ie10|ie11)',
                'testimonials-items-([a-zA-Z0-9_-]+)',
                'instance-([0-9]+)'
            ];

            
            /* ---------------------------------------------------- */
            /* GOOD ALLOWED NUMMERIC CLASSES                        */
            /* ---------------------------------------------------- */
            /* Prefer one digital class in 2 or more.               */
            /* ---------------------------------------------------- */
            var preferedDigitalClasses = [

                // General
                'wp-image-[0-9]+',

                // Basic
                '([a-zA-Z0-9_-]+)?section([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?slide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?button([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?image([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?row([a-zA-Z0-9_-]+)?',

                // Visual composer
                'vc_custom_([a-zA-Z0-9_-]+)?',

                // Beaver builder
                'fl-node([a-zA-Z0-9_-]+)?',
                'fl-row([a-zA-Z0-9_-]+)?',

                // Themify
                'module_row_([0-9]+)',
                'module_column_([0-9]+)',

                // Divi
                'et_pb_(section|row)_[0-9]+',

                // king composer
                'kc-css-([0-9]+)',

                // forge builder
                'forge-col[0-9]+',

                // enfold
                '(avia|av)-builder-el-([0-9]+)',

                // flatsome
                'footer-([0-9]+)'

            ];


            /* ---------------------------------------------------- */
            /* GOOD ALLOWED NUMMERIC IDS                            */
            /* ---------------------------------------------------- */
            /* YP Editor allow just the following nummeric ids      */
            /* Ex: #section-15 is a allowed nummeric id             */
            /* ---------------------------------------------------- */
            var allowedDigitalIds = [

                // General
                '([a-zA-Z0-9_-]+)?module([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?slide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?section([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?row([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?layout([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?form([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?wrapper([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?container([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?parallax([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?block([a-zA-Z0-9_-]+)?',

                // 3 party plugin allowed ids
                'layers-widget-([a-zA-Z0-9_-]+)?',
                'builder-module-([a-zA-Z0-9_-]+)?',
                'pg-([a-zA-Z0-9_-]+)?',
                'ptpb_s([a-zA-Z0-9_-]+)?',
                'billing_address_([0-9])([a-zA-Z0-9_-]+)?', // woocommerce
                'el-([a-zA-Z0-9_-]+)',
                'dslc-module-([a-zA-Z0-9_-]+)',
                'module-([0-9]){13,13}-([0-9]){4,4}', // upfront
                'wrapper-([0-9]){13,13}-([0-9]){4,4}' // upfront

            ];


            /* ---------------------------------------------------- */
            /* Blocked IDS                                          */
            /* ---------------------------------------------------- */
            var blockedIds = [

                'widget',
                "recentcomments",
                'fws_([a-zA-Z0-9_-]+)', // 3 party plugin dynamic ID
                'wrapper-[a-zA-Z-0-9]{16,16}' // headway dynamic ID

            ];


            /* ---------------------------------------------------- */
            /* Prefered tags as selector                            */
            /* ---------------------------------------------------- */
            var simpleLikedTags = [
                "h1",
                "h2",
                "h3",
                "h4",
                "h5",
                "h6",
                "p",
                "span",
                "img",
                "strong",
                "a",
                "li",
                "i",
                "ul",
                "header",
                "footer",
                "article",
                "b",
                "em",
                "code",
                "form",
                "label",
                "ol",
                "small",
                "blockquote",
                "nav"
            ];


            /* ---------------------------------------------------- */
            /* Filtering post format classes                        */
            /* ---------------------------------------------------- */
            var postFormatFilters = [

                // Don't care post formats
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*standard)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*aside)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*audio)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*chat)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*gallery)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*image)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*link)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*quote)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*status)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*video)',

            ];


            /* ---------------------------------------------------- */
            /* Filtering classes func                               */
            /* ---------------------------------------------------- */
            function process_class(filter,classes){

                var a;

                for(var i = 0; i < filter.length; i++){

                    // Regex
                    a = new RegExp("(\\s|^)" + filter[i] + "(\\s|$)","gi");

                    // Replace
                    classes = classes.replace(a, ' ');

                }

                return classes.trim();

            }


            /* ---------------------------------------------------- */
            /* Filtering test                                       */
            /* ---------------------------------------------------- */
            function filter_test(filter,classN){

                // Is Single Inspector
                if(window.singleInspector){

                    // if filter has 'not single inspector' regEx
                    if(/\(\!singleInspector\)/g.test(filter)){
                        return false; // because is active
                    }else{
                        filter = filter.replace(/\(\!singleInspector\)/g,'');
                    }

                }else{

                    // if filter has 'just single inspector' regEx
                    if(/\(singleInspector\)/g.test(filter)){
                        return false; // because is not active
                    }else{
                        filter = filter.replace(/\(singleInspector\)/g,'');
                    }

                }
                            
                // Regex
                var r = new RegExp("(\\s|^)" + filter + "(\\s|$)","gi");

                // has
                if(r.test(classN)){
                    return true;
                }else{ // not have
                    return false;
                }

            }


            /* ---------------------------------------------------- */
            /* Deleting non-acceptable classes & ids                */
            /* ---------------------------------------------------- */
            function delete_bad_terms(classes){

                // list
                var classesArray = [];

                // Clean
                classes = $.trim(classes);

                // Clean multispaces
                classes = classes.replace(/\s\s+/g, ' ');

                // Check if still there have another class
                if(classes.indexOf(" ") != -1){

                    var classessplit = classes.split(" ");

                    // Split with space
                    var v;
                    for(var i = 0; i < classessplit.length; i++){

                        // Clean
                        v = $.trim(classessplit[i]);

                        // Push
                        if(/([\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]|\{|\}|\:|\<|\>|\(|\)|\[|\]|\~|\"|\'|\?|\\)/g.test(v) == false){
                            classesArray.push(v);
                        }

                    }

                }else{

                    // Push if single.
                    if(/([\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]|\{|\}|\:|\<|\>|\(|\)|\[|\]|\~|\"|\'|\?|\\)/g.test(classes) == false){
                        classesArray.push(classes);
                    }

                }

                return classesArray.join(" ");

            }


            /* ---------------------------------------------------- */
            /* Espacing some new ID and Class chars                 */
            /* ---------------------------------------------------- */
            function html5_espace_attr(value){

                if(value === null | value === undefined || typeof value == typeof undefined || value == false || value == true){
                    return value;
                }

                return delete_bad_terms(value).replace(/(@|\.|\/|!|\*|#|\+)/g,"\\$1");

            }


            /* ---------------------------------------------------- */
            /* Get Best Class Name                                  */
            /* ---------------------------------------------------- */
            /*
                 the most important function in yellow pencil scripts
                  this functions try to find most important class name
                  in classes.

                  If no class, using ID else using tag name.
             */
            window.reGetBestClass = false;
            function get_best_class($element){

                // Default Variables
                var filteredClasses = '',
                DigitalClasses = '',
                numberRex = /\d+/,
                id,tag,element,passedFilter,return_selector,goodClassFounded,digitalClassFounded,classes;

                // Cache
                element = $($element);

                // Element Classes
                classes = element.attr("class");

                // Clean The Element Classes
                if (classes !== undefined && classes !== null) {
                    classes = $.trim(class_cleaner(html5_espace_attr(classes)));
                }

                // Cache id and tagname.
                id = element.attr("id");
                tag = element[0].nodeName.toLowerCase();

                if (tag == 'body' || tag == 'html') {
                    return tag;
                }

                // If Element has ID, Return ID.
                if (isDefined(id)){

                    // trim
                    id = $.trim(html5_espace_attr(id));

                    // Check if ID has number.
                    if(numberRex.test(id)){

                        // allow just some digital ids
                        var isIdAllowed = false;

                        // This id in allowed list?
                        for(var d = 0; d < allowedDigitalIds.length; d++){

                            // yes
                            if(filter_test(allowedDigitalIds[d],id)){
                                isIdAllowed = true;
                                break;
                            }

                        }

                        // else not allow
                        if(isIdAllowed == false){
                            id = '';
                        }

                    }


                    // Blocked id checks
                    if (id != '') {

                        // Check if a blocked ID
                        for(var f = 0; f < blockedIds.length; f++){

                            if(filter_test(blockedIds[f],id)){
                                id = '';
                                break;
                            }

                        }

                    }


                    // return the available id
                    if (id != '') {
                        return '#' + id;
                    }


                }


                // If has classes.
                if (classes !== undefined && classes !== null) {

                    // Classes to array.
                    var ArrayClasses = get_classes_array(classes);

                }


                // we want never use some class names. so disabling this classes.
                if(isDefined(ArrayClasses)){

                    // Trim
                    filteredClasses = $.trim(classes);

                    // If length?
                    if(filteredClasses.length > 1){

                        filteredClasses = process_class(blockedClasses,filteredClasses);

                    }

                    // Update filtered Classes
                    filteredClasses = $.trim(filteredClasses);

                }


                var hasFilteredClasses = false;
                var hasFilteredClasses2 = false;

                // If Has Filtered classes
                if (filteredClasses != ''){

                    // yes, have.
                    hasFilteredClasses = true;

                    // Replace significant classes and keep best classes.
                    var filteredClasses2 = filteredClasses;
                    
                    // Replace all non useful classes
                    if(isDefined(filteredClasses2)){

                        // If has
                        if(filteredClasses2.length > 1){

                            filteredClasses2 = process_class(unPreferedClasses,filteredClasses2);

                        }

                        // trim
                        filteredClasses2 = $.trim(filteredClasses2);

                    }

                }


                // If Has Filtered classes2
                if ($.trim(filteredClasses2) != ''){

                    // Yes, have.
                    hasFilteredClasses2 = true;

                }


                // Make as array.
                var filteredClassesArray = get_classes_array(filteredClasses);
                var filteredClasses2Array = get_classes_array(filteredClasses2);

                // Foreach classes and exclude nummeric classes
                if(hasFilteredClasses2){

                    var v;
                    var isNum = false;
                    for(var i = 0; i < filteredClasses2Array.length; i++){

                        // Value
                        v = filteredClasses2Array[i];

                        // default
                        isNum = false;

                        if(numberRex.test(v)){
                            isNum = true;
                        }

                        // Don't see as nummeric class
                        for(var s = 0; s < filterNumSkipBest.length; s++){

                            // skip
                            if(isNum == true && filter_test(filterNumSkipBest[s],v) == true){
                                isNum = false;
                                break;
                            }

                        }

                        // Has number
                        if(isNum){

                            passedFilter = true;

                            for(var m = 0; m < blockedDigitalClasses.length; m++){

                                // Not has page-item class | not use vc_'s dynamic class
                                if(filter_test(blockedDigitalClasses[m],v) == true){

                                    passedFilter = false;
                                    break;

                                }

                            }

                            // Added as nummeric classes
                            if(passedFilter){
                                DigitalClasses += ' ' + v;
                            }

                        }

                    }

                }

                var filteredDigitalArray = get_classes_array(DigitalClasses);


                // Clean Up FilteredClasses by digits.
                if(hasFilteredClasses){
                    var cleanedFilter = [], cleanedFilter2 = [], i;
                    for(i = 0; i < filteredClassesArray.length; i++){
                        if(!numberRex.test(filteredClassesArray[i])){
                            cleanedFilter.push(filteredClassesArray[i]);
                        }
                    }
                    filteredClassesArray = cleanedFilter;
                    filteredClasses = filteredClassesArray.join(" ");

                    if(hasFilteredClasses2){
                        for(i = 0; i < filteredClasses2Array.length; i++){
                            if(!numberRex.test(filteredClasses2Array[i])){
                                cleanedFilter2.push(filteredClasses2Array[i]);
                            }
                        }
                        filteredClasses2Array = cleanedFilter2;
                    }
                }


                // search in good class filter
                if(hasFilteredClasses2){

                    goodClassFounded = false;

                    // filtered classes
                    for (var i = 0; i < filteredClasses2Array.length; i++){

                        // good filters
                        for(var u = 0; u < preferedClasses.length; u++){

                            if(filter_test(preferedClasses[u],filteredClasses2Array[i])){

                                // if this is "post" and has hentry, prefer hentry
                                if(filteredClasses2Array[i] == 'post' && filteredClasses2Array.indexOf("hentry") != -1){
                                    break;                                    
                                }

                                goodClassFounded = true;
                                return_selector = "." + filteredClasses2Array[i];
                                break;

                            }

                        }

                        // stop if founded
                        if(goodClassFounded){
                            break;
                        }

                    }

                }


                // Try filtered Classes V1 if not founded in v2
                if(goodClassFounded == false && hasFilteredClasses == true){

                    // filtered classes
                    for (var i = 0; i < filteredClassesArray.length; i++){

                        // good filters
                        for(var u = 0; u < preferedClasses.length; u++){

                            if(filter_test(preferedClasses[u],filteredClassesArray[i])){
                                goodClassFounded = true;
                                return_selector = "." + filteredClassesArray[i];
                                break;
                            }

                        }

                        // stop if founded
                        if(goodClassFounded){
                            break;
                        }

                    }

                }


                // Some nummeric classes is important.
                if(DigitalClasses != ''){

                    digitalClassFounded = false;

                    for(var p = 0; p < filteredDigitalArray.length; p++){

                        for(var e = 0; e < preferedDigitalClasses.length; e++){

                            // is one good nummeric? great
                            if(filter_test(preferedDigitalClasses[e],filteredDigitalArray[p]) == true){
                                digitalClassFounded = true;
                                return_selector = "." + filteredDigitalArray[p];
                                break;
                            }

                        }

                        if(digitalClassFounded){
                            break;
                        }

                    }

                }


                // use input type for selector
                if(tag == "input" && window.reGetBestClass == false){

                    var type = element.attr("type");

                    // Single Inspector
                    if(mainBody.hasClass("yp-sharp-selector-mode-active")){

                        window.reGetBestClass = true;

                        var className = get_best_class($element);

                        return_selector = 'input[type=' + type + ']'+className;

                        window.reGetBestClass = false;

                    // Default Inspector
                    }else{
                        return_selector = 'input[type=' + type + ']';
                    }


                }
                

                // Prefered Tag
                var preferedTag = false;

                // Try find prefered tags
                for(var n = 0; n < simpleLikedTags.length; n++){

                    if(simpleLikedTags[n] == tag){
                        preferedTag = tag;
                        break;
                    }

                }


                var lastLuckNummeric = false;

                // if not have any good selector, try nummeric class which pass neverUseNum filter
                if(DigitalClasses != ''){
                    lastLuckNummeric = ai_class(filteredDigitalArray);
                }


                // Matchless Classes for single Inspector
                var matchlessClass = false;

                if(mainBody.hasClass("yp-sharp-selector-mode-active")){

                    // Filtered 2 classes: AI
                    matchlessClass = matchless2(ai_class(filteredClasses2Array,true,false));

                    // Filtered 2 classes: NO AI
                    if(matchlessClass == false){
                        matchlessClass = matchless2(filteredClasses2Array);
                    }


                    // filtered 1 classes: AI
                    if(matchlessClass == false){
                        matchlessClass = matchless2(ai_class(filteredClassesArray,true,false));
                    }

                    // filtered 1 classes: NO AI
                    if(matchlessClass == false){
                        matchlessClass = matchless2(filteredClassesArray);
                    }

                }


                // prefered Classes
                var classPrefered = false;

                // Filtered 2 classes: AI
                var classPrefered = ai_class(filteredClasses2Array,false,false);

                // filtered 1 classes: AI
                if(classPrefered == false){
                    classPrefered = ai_class(filteredClassesArray,false,false);
                }

                // Recommended selector
                if(isDefined(return_selector)){ // Good Classes

                    return return_selector;

                // Cool matchless class for single Inspector
                }else if(matchlessClass != false){

                    return matchlessClass;

                // Try to use prefered class
                }else if(classPrefered != false){

                    return classPrefered;

                // use prefered tags
                }else if(preferedTag != false){

                    return preferedTag;

                // use a nummeric class
                }else if(lastLuckNummeric != false){

                    return lastLuckNummeric;

                // Use any tag if not id
                }else if(tag != 'div'){

                    return tag;

                // Use any class if not have alternative.
                }else if(filteredClasses != '' && filteredClasses != undefined && filteredClasses != null){

                    // Get AI class.
                    var aiClass = ai_class(filteredClassesArray);

                    // this func will use any class if not have an alternative
                    // but blocked digital classes are is dangerous, because these classes
                    // is dynamic, changes on page re-load. Use div tag but
                    // never use blocked digital class.
                    var passedBlockedDigital = true;

                    // Loops
                    for(var m = 0; m < blockedDigitalClasses.length; m++){

                        // Not has page-item class | not use vc_'s dynamic class
                        if(filter_test(blockedDigitalClasses[m],aiClass.replace(/^(\.|#)/g, '')) == true){

                            passedBlockedDigital = false;
                            break;

                        }

                    }

                    // return AI class if pass blocked digital classes
                    if(passedBlockedDigital){
                        return aiClass;
                    }else{
                        return tag; // Return tag even "div".
                    }

                }else{

                    return tag;

                }

            }


            /* ---------------------------------------------------- */
            /* Finds matchless2 Classes                             */
            /* ---------------------------------------------------- */
            function matchless2(classes){

                // ai_class returns false
                if(classes === false || classes == ''){
                    return false;
                }

                // Find
                var matchlessClasses = classes.sort(function(b, a) {
                    return iframeBody.find("."+b).length - iframeBody.find("."+a).length;
                });

                // be sure it is just 1
                if(iframeBody.find("."+matchlessClasses[0]).length == 1){

                    return "." + matchlessClasses[0];

                }

                return false;

            }


            /* ---------------------------------------------------- */
            /* Finds AI Classes                                     */
            /* ---------------------------------------------------- */
            function ai_class(classes,array,alternative){

                // 0 classes
                if(classes.length == 0){
                    return false;
                }

                // 1 classes: has alternative
                if(classes.length == 1 && alternative == true){
                    return false;
                }

                // 1 classes: no alternative
                if(array == false){

                    if(classes.length == 1 && alternative == false){

                        if(classes[0].length > 1){

                            return "." + classes[0];

                        }else{

                            return false;

                        }

                    }

                }else if(classes.length == 1 && alternative == false){

                    if(classes[0].length > 1){

                        return classes;

                    }else{

                        return false;

                    }

                }


                // Variables
                var newClasses = [],new2Classes = [],depthArray = [],tagArray = [],i,new4Classes = [];

                // Data
                var a1 = []; // just classes which has "-"
                var a2 = []; // just classes which has "_"

                // Filter: Difference parent depth and difference HTML tags
                for(i = 0; i < classes.length; i++){

                    // Arrays
                    depthArray  = [];
                    tagArray = [];

                    if(/(\,|\[|\]|\#)/g.test(classes[i])){
                        continue;
                    }

                    // Each current class
                    iframe.find("." + classes[i]).each(function(){

                        var element = $(this);

                        // Push depth len
                        depthArray.push(element.parents().length);

                        // Push tags used
                        tagArray.push(element[0].nodeName);

                    });

                    var depthArrayEquals = depthArray.every(function(v, i, a){
                        return v === a[0];
                    });

                    var tagArrayEquals = tagArray.every(function(v, i, a){
                        return v === a[0];
                    });

                    // Passed depth and tag methods
                    if(depthArrayEquals && tagArrayEquals){
                        newClasses.push(classes[i]);
                    }

                }

                // Use default if newClasses is not avaiable
                if(newClasses.length == 0){
                    newClasses = classes;
                }


                // Filter a1
                for(i = 0; i < newClasses.length; i++){

                    // just classes which has "-"
                    if (/\_/g.test(newClasses[i]) == false && /\-/g.test(newClasses[i]) == true){
                        a1.push(newClasses[i]);
                    }

                }


                // Try a2 if a1 is empty.
                if(a1.length == 0){
                
                    // Filter a2
                    for(i = 0; i < newClasses.length; i++){

                        // just classes which has "-"
                        if (/\-/g.test(newClasses[i]) == false && /\_/g.test(newClasses[i]) == true){
                            a2.push(newClasses[i]);
                        }

                    }

                    // a1 and a2 is empty
                    if(a2.length == 0){

                        new2Classes = newClasses;

                    }else{

                        // Use a2 if it is available
                        new2Classes = a2;

                    }

                }else{ // Use a1 if available

                    new2Classes = a1;

                }



                // Filter: 3 time repeated char classes
                for(i = 0; i < new2Classes.length; i++){

                    // If char not repeat 3 time as tessst.
                    if (/(.)\1\1/.test(new2Classes[i]) == false){
                        new4Classes.push(new2Classes[i]);
                    }

                }

                // Use non filtered classes if any class cant pass
                if(new4Classes.length == 0){
                    new4Classes = newClasses;
                }


                // Sort
                new4Classes.sort(function(a, b){
                    return b.length - a.length;
                });


                // Return format
                if(array){
                    return new4Classes;
                }else{
                    return "." + new4Classes[0];
                }

            }


            /* ---------------------------------------------------- */
            /* Getting current selector                             */
            /* ---------------------------------------------------- */
            function get_current_selector(){

                // Get current
                var parentsv = body.attr("data-clickable-select");

                var newSelector = false;

                // If has
                if (isDefined(parentsv)) {

                    // If unvalid
                    if(check_selector(parentsv,true,false) == false){

                        newSelector = get_parents(null, "default");

                    }else{ // if valid return

                        return parentsv;

                    }

                // If not has selector
                } else {

                    // return
                    newSelector = get_parents(null, "default");

                }

                // Replace old with new
                if(newSelector != false){

                    if(iframe.find(".yp-selected-others").length == 0 && iframe.find(newSelector).length > 1){
                        body.addClass("yp-sharp-selector-mode-active");
                        newSelector = get_parents(null, "default");
                        body.removeClass("yp-sharp-selector-mode-active");
                    }

                    set_selector(newSelector, get_selected_element());
                    return newSelector;

                }

            }


            /* ---------------------------------------------------- */
            /* Finds bad queries                                    */
            /* ---------------------------------------------------- */
            function filter_bad_queries(data){
                return  data.replace(/[\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]/g,'');
            }


            /* ---------------------------------------------------- */
            /* Checks if selector valid                             */
            /* ---------------------------------------------------- */
            function check_selector(selector,mustHas,searchInBody){

                var content;
                if(searchInBody){
                    content = iframeBody;
                }else{
                    content = iframe;
                }

                try {

                    // Get element
                    var element = content.find(selector);

                    // Return false if document not have this element
                    if(mustHas == true && element.length == 0){
                        return false;
                    }else{
                        return element; // return true if valid
                    }

                    
                } catch (e) {
                    return false;
                }

            }


            /* ---------------------------------------------------- */
            /* Getting minimized CSS. Cleaning spaces.              */
            /* ---------------------------------------------------- */
            function get_minimized_css(data,media){

                // Clean.
                data = data.replace(/(\r\n|\n|\r)/g, "").replace(/\t/g, '');

                // Don't care rules in comment.
                data = data.replace(/\/\*(.*?)\*\//g, "");

                // clean.
                data = data.replace(/\}\s+\}/g, '}}').replace(/\s+\{/g, '{');

                // clean.
                data = data.replace(/\s+\}/g, '}').replace(/\{\s+/g, '{');

                // replace queries
                data = filter_bad_queries(data);

                // Don't care rules in media query
                if(media === true){
                    data = data.replace(/@media(.*?)\}\}/g, '').replace(/@?([a-zA-Z0-9_-]+)?keyframes(.*?)\}\}/g, '').replace(/@font-face(.*?)\}\}/g, '').replace(/@import(.*?)\;/g,'').replace(/@charset(.*?)\;/g,'');
                }

                // data
                return data;

            }


            // Get defined selector controller.
            window.definedSelectorArray = [];
            window.definedSelectorArrayEnd = false;

            /* ---------------------------------------------------- */
            /* Get Defined Selectors                                */
            /* ---------------------------------------------------- */
            function get_defined_selector(){

                var data = window.definedStyleData;

                var allSelectors,i;

                // Don't search it always
                if(window.definedSelectorArray.length === 0){

                    // if no data, stop.
                    if(data == ''){
                        return false;
                    }

                    data = data.toString().replace(/\}\,/g, "}");

                    // Getting All CSS Selectors.
                    allSelectors = array_cleaner(data.replace(/\{(.*?)\}/g, '|BREAK|').split("|BREAK|"));

                }

                // Vars
                var foundedSelectors = [];
                var selector;

                // get cached selector Array
                if(window.definedSelectorArrayEnd){
                    allSelectors = window.definedSelectorArray;
                }

                if(isUndefined(allSelectors)){
                    return false;
                }


                // Filtering bad classes
                var element, passedClasses;


                // Each All Selectors
                for (i = 0; i < allSelectors.length; i++){

                    // Get Selector.
                    selector = space_cleaner(allSelectors[i]);
                    selector = space_cleaner(selector.replace(/(\{|\})/g,'').replace(/>(\.|\#|[a-zA-Z-_])/g, "> ").replace(/(\.|\#|[a-zA-Z-_])>/g, " >"));                    

                    // YP not like so advanced selectors.
                    if(selector.indexOf(",") != -1 || selector.indexOf("*") != -1 || selector.indexOf("/") != -1){
                        continue;
                    }

                    // skip html5 advanced terms
                    if(/([\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]|\{|\}|\:|\<|\>|\(|\)|\[|\]|\~|\"|\'|\?|\\)/g.test(selector) == true){
                        continue;
                    }

                    // Not basic html tag selectors.
                    if(selector.indexOf("#") == -1 && selector.indexOf(".") == -1){
                        continue;
                    }

                    // min two
                    if(get_selector_array(selector).length < 2){
                        continue;
                    }

                    element = check_selector(selector,true,true);

                    // be sure it valid
                    if(element === false){
                        continue;
                    }

                    // Bad EX: span.class, h1#id
                    if(/[a-zA-Z-_0-9](\.|#)/g.test(selector)){
                        continue;
                    }

                    // Cache other selectors.
                    if(window.definedSelectorArrayEnd === false){
                        window.definedSelectorArray.push(selector);
                    }

                    // Founded Selector
                    if(element.hasClass("yp-selected")){
                        foundedSelectors.push(selector);
                    }

                }

                // Don't read again css files. cache all defined CSS selectors.
                window.definedSelectorArrayEnd = true;

                // New selectors
                var foundedNewSelectors = [];
                var o,selectorBefore,re;

                // Each all founded selectors.
                // Don't use if has non useful classes as format-link etc.
                for(i = 0; i < foundedSelectors.length; i++){

                    selectorBefore = foundedSelectors[i];
                    passedClasses = true;

                    // Check if has an useful class
                    for(o = 0; o < blockedClasses.length; o++){

                        // Regex
                        re = new RegExp("(\\s|^|\.|\#)" + blockedClasses[o] + "(\\s|$)","gi");
                        
                        // Founded an non useful class.
                        if(re.test(selectorBefore)){
                            passedClasses = false;
                            break;
                        }

                    }

                    // Check if has an useful class
                    for(o = 0; o < blockedClasses.length; o++){

                        // Regex
                        re = new RegExp("(\\s|^|\.|\#)" + blockedClasses[o] + "(\\s|$)","gi");
                        
                        // Founded an non useful class.
                        if(re.test(selectorBefore)){
                            passedClasses = false;
                            break;
                        }

                    }

                    // Check if has an useful class
                    for(o = 0; o < unPreferedClasses.length; o++){

                        // Regex
                        re = new RegExp("(\\s|^|\.|\#)" + unPreferedClasses[o] + "(\\s|$)","gi");
                        
                        // Founded an non useful class.
                        if(re.test(selectorBefore)){
                            passedClasses = false;
                            break;
                        }

                    }

                    // Check if has bad class
                    for(o = 0; o < postFormatFilters.length; o++){

                        // Regex
                        re = new RegExp("(\\s|^|\.|\#)" + postFormatFilters[o] + "(\\s|$)","gi");

                        // Founded an bad class.
                        if(re.test(selectorBefore)){
                            passedClasses = false;
                            break;
                        }

                    }

                    // Successful.
                    if(passedClasses === true){
                        foundedNewSelectors.push(foundedSelectors[i]);
                    }

                }

                return foundedNewSelectors;

            }


            /* ---------------------------------------------------- */
            /* This function cropping selector until 5 class        */
            /* ---------------------------------------------------- */
            function crop_selector(selector){

                var limit = 5;

                // generate long selector as we want with: "window.minCroppedSelector"
                if(window.minCroppedSelector != false){
                    limit = window.minCroppedSelector;
                }

                // Keep selectors smart and short!
                if(get_selector_array(selector).length > 5){

                    // short Selector Ready
                    var shortSelectorReady = false;

                    // Find a founded elements
                    var foundedElements = iframe.find(selector).length;

                    // Get array from selector.
                    var shortSelector = get_selector_array(selector);

                    // Each array items
                    $.each(shortSelector,function(){

                        if(shortSelectorReady === false){

                            // Shift
                            shortSelector.shift();

                            // make it short
                            var shortSelectorString = shortSelector.toString().replace(/\,/g," ");

                            // Search
                            var foundedElShort =  iframe.find(shortSelectorString).length;

                            // Shift until make it maximum 5 item
                            if(shortSelector.length <= 5 && foundedElements == foundedElShort){
                                shortSelectorReady = true;
                                selector = shortSelectorString;
                            }

                        }

                    });

                }

                return selector;

            }


            /* ---------------------------------------------------- */
            /* This function trying all settings for the selector   */
            /* if there 10 classes in the selector                  */
            /* it will try 100 combine                              */
            /* ---------------------------------------------------- */
            function multiple_variation(selector){
          
                // Get current selector length
                var selectorLen = iframejs.querySelectorAll(selector).length;

                // Orginal selector
                var selectorOrginal = crop_selector(selector);
                
                // will keep the results in this array
                var resultArray = [];

                var last,first,cssSelector = '';

                // Need to first and last
                if(selector.indexOf(">") == -1){

                    // The Array
                    selector = get_selector_array(selector);

                    // Last element
                    last = selector[selector.length - 1];
                    selector.pop();
                  
                    // First element
                    first = selector[0];
                    selector.shift();

                // Getting first and last in the selector which has ">" symbols.
                }else{

                    var getEnd,getStart,arr,firstReg,lastReg,centerSelector,centerMatch;
                    var type = null;

                    // Match for Gettin End
                    getEnd = selector.match(/\>(?!.*\>)(.*?)$/g).toString().replace(/(\s)?>(\s)?/, '');

                    // Match for getting start
                    getStart = selector.match(/^(.*?)\s\>/g).toString().replace(/(\s)?>(\s)?/, '');

                    // Check if > in start or end.
                    if (getEnd.indexOf(' ') == -1){
                        arr = getStart.split(" ");
                        type = "getStart";
                    } else if (getStart.indexOf(' ') == -1) {
                        arr = getEnd.split(" ");
                        type = "getEnd";
                    }

                    // get the getEnd
                    if(type == null){

                        if (getEnd.split(" ").length > getStart.split(" ").length){
                            arr = getEnd.split(" ");
                            type = "getEnd";
                        }else{ // get the getStart
                            arr = getStart.split(" ");
                            type = "getStart";
                        }

                    }

                    // cache first and last selectors and delete
                    if (arr.length - 2 > 1) {

                        // cache first & last
                        first = arr[0];
                        last = arr[arr.length - 1];

                        // RegExp for parsing
                        firstReg = new RegExp("^(.*?) > " + first, "g");
                        lastReg = new RegExp(last + " > (.*?)$", "g");

                        if(selector.match(firstReg) != null && selector.match(firstReg) != null){

                            // Check the type and update first/last
                            if (type == "getEnd") {
                                first = selector.match(firstReg).toString();
                            } else if (type == 'getStart') {
                                last = selector.match(lastReg).toString();
                            }

                            // Get just between
                            arr.pop();
                            arr.shift();

                            centerSelector = arr;

                        }

                    }

                    // Trying another method. It will get center of selector.
                    if (centerSelector == undefined){

                        // Test it
                        if (/\>(.*?)\>/g.test(selector)) {

                            // Get center
                            centerMatch = selector.match(/\>(.*?)\>/g).toString().replace(/(\s)?>(\s)?/g, '');

                            // must has a space
                            if (centerMatch.indexOf(" ") != -1) {

                                // parse 
                                arr = centerMatch.split(" ");

                                // cache first and last selectors and delete
                                if (arr.length - 2 > 1) {

                                    // Cache first and last.
                                    first = arr[0];
                                    last = arr[arr.length - 1];

                                    // RegExp for parsing
                                    firstReg = new RegExp("^(.*?) > " + first, "g");
                                    lastReg = new RegExp(last + " > (.*?)$", "g");

                                    // Check the type and update first & last
                                    first = selector.match(firstReg).toString();
                                    last = selector.match(lastReg).toString();

                                    arr.pop();
                                    arr.shift();

                                    centerSelector = arr;

                                }

                            }

                        }

                    }

                    // Check if it is available.
                    if(centerSelector != undefined){
                        selector = centerSelector;
                    }else{
                        return selectorOrginal;
                    }

                } // > symbol codes end here.


                // Try to delete structural selectors
                var newSelector = [],passed,r;
                for(var i = 0; i < selector.length; i++){

                    passed = true;

                    for(var ix = 0; ix < unPreferedSelectors.length; ix++){

                        // Regex
                        r = new RegExp("(\\s|^)\." + unPreferedSelectors[ix] + "(\\s|$)","gi");

                        // has
                        if(r.test(selector[i])){
                            passed = false;
                            break;
                        }

                    }

                    if(passed){
                        newSelector.push(selector[i]);
                    }

                }


                if(isUndefined(first) || isUndefined(first)){
                    return selectorOrginal;
                }


                // just try combine first and last
                if(first.indexOf(" ") == -1 && last.indexOf(" ") == -1){
                    cssSelector = space_cleaner(first + window.separator + last);
                }


                // is valid? // first & last
                if(check_selector(cssSelector,false,false) && window.minCroppedSelector == false){

                    // Combine just first and last if there were a lot selector but all were structural selectors.
                    if(selector.length >= 1 && newSelector.length == 0 && iframe.find(cssSelector).length == selectorLen){
                        return cssSelector;
                    }

                }


                // Update only if high than 1
                if(newSelector.length > 1){
                    selector = newSelector;
                }


                // Must be minimum 2 class excluding
                // first and last classes in the selector.
                if(selector.length < 2){
                    return selectorOrginal;
                }


                // Prefer only classes which used in same parent depth and same tag names.
                // The class that which used in difference depth is structural class
                // The class that which used in difference tags is structural class.
                var depthArray,tagArray,ek;
                var newSelector2 = [];
                for(i = 0; i < selector.length; i++){

                    if(/.|#/g.test(selector[i])){

                        // Arrays
                        depthArray  = [];
                        tagArray = [];

                        // Each current class
                        iframe.find(selector[i]).each(function(){

                            ek = $(this);

                            // Push depth len
                            depthArray.push(ek.parents().length);

                            // Push tags used
                            tagArray.push(ek[0].nodeName);

                        });

                        var depthArrayEquals = depthArray.every(function(v, i, a){
                            return v === a[0];
                        });

                        var tagArrayEquals = tagArray.every(function(v, i, a){
                            return v === a[0];
                        });

                        // Passed depth and tag methods
                        if(depthArrayEquals && tagArrayEquals){
                            newSelector2.push(selector[i]);
                        }

                    }

                }


                // Update only if high than 1
                if(newSelector2.length > 1){
                    selector = newSelector2;
                }


                // Variables
                var nexts = selector.slice(0);
                var current,i,n,currentNext,testSelector;

                // Combine All
                for(i = 0; i < selector.length; i++){
                
                    current = selector[i];

                    // all selector for test
                    testSelector = first + window.separator + current + window.separator + last;
                
                    // Add if pass
                    if(iframejs.querySelectorAll(testSelector).length == selectorLen){
                        resultArray.push(testSelector);
                    }
                
                    // Shift on  each
                    nexts.shift();

                    // Use current with all other selector parts
                    for(n = 0; n < nexts.length; n++){
                      
                        currentNext = nexts[n];

                        // all selector for test
                        testSelector = first + window.separator + current + window.separator + currentNext + window.separator + last;
                      
                        // Add if pass
                        if(iframejs.querySelectorAll(testSelector).length == selectorLen){
                            resultArray.push(testSelector);
                        }
                      
                    }
                
                }
              
              
                // There not have any variation
                if(resultArray.length == 0){
                    return selectorOrginal;
                }
              
              
                // Find the short
                resultArray.sort(function(a,b){
                    return a.length - b.length;
                });


                // Find the selector long as we want with "window.minCroppedSelector"
                if(window.minCroppedSelector != false){

                    // loop the results
                    for(var k = 0; k < resultArray.length; k++){

                        // find the longer selector
                        if(get_selector_array(resultArray[k]).length >= window.minCroppedSelector){
                            return space_cleaner(resultArray[k]);
                        }

                    }

                    return selectorOrginal;

                }
                

                // Return the result
                return space_cleaner(resultArray[0]);
              
            }


            /* ---------------------------------------------------- */
            /* Get Parents                                          */
            /* ---------------------------------------------------- */
            function get_parents(element, status){

                // If parent already has.
                var parentsv = body.attr("data-clickable-select");

                // If status default, return current data.
                if (status == 'default' && window.minCroppedSelector == false) {

                    // If defined
                    if (isDefined(parentsv)) {

                        // if valid return
                        if(check_selector(parentsv,true,false) != false){
                            return parentsv;
                        }

                    }

                }

                if(status == 'defaultNoCache'){
                    status = 'default';
                }


                if(element === null){
                    element = get_selected_element();
                }


                // Be sure this item is valid.
                if (element[0] === undefined || element[0] === false || element[0] === null) {
                    return false;
                }


                // Tag info
                var tag = element[0].tagName;


                // Is Single Inspector
                if(mainBody.hasClass("yp-sharp-selector-mode-active")){
                    status = 'sharp';
                }


                // HTML tag not supported.
                if(isUndefined(tag) || tag == 'HTML'){
                    return false;
                }

                // If body, return.
                if (tag == 'BODY') {
                    return 'body';
                }


                // Getting item parents.
                var parents = element.parentsUntil("body"), selector = '', reseted, inSelected, currentSelector,previousSelector = '';


                // Get last selector
                var lastSelector = get_best_class(element);


                // Return if element selector is ID.
                if(/#/g.test(lastSelector) == true){
                    return lastSelector;
                }


                // Resets
                var resetSelectors = [], dontReset = false, dontResetLive = false;


                // Check if there is waited selector
                if(window.minCroppedSelector != false){

                    // waited selector by long.
                    if(window.minCroppedSelector >= parents.length){
                        dontReset = true;
                    }

                }


                // Foreach all loops.
                for (var i = parents.length - 1; i >= 0; i--) {

                    // Default false
                    reseted = false;

                    // Get Selector of the current parent element.
                    currentSelector = get_best_class(parents[i]);

                    // Don't reset if waited selector is long
                    dontResetLive = false;
                    if(window.minCroppedSelector != false){
                        if((i-1) <= window.minCroppedSelector){
                            dontResetLive = true;
                        }
                    }

                    // Check if this has a class or ID.
                    if(/\.|#/g.test(currentSelector) == true && dontReset == false && dontResetLive == false){

                        // Check if need or no need for generated previous selectors
                        if(iframe.find(currentSelector).length == 1){

                            // No Need to previous Selectors
                            if (status != 'sharp') {

                                // Reset old selectors
                                selector = currentSelector + window.separator;

                                resetSelectors.push(currentSelector);
                                reseted = true;

                            }else{

                                // In Single Selector reset only if no need nth-child selector.
                                if (single_selector(selector, true).indexOf("nth-child") == -1) {

                                    // Reset old selectors
                                    selector = currentSelector + window.separator;

                                    resetSelectors.push(currentSelector);
                                    reseted = true;

                                }

                            }

                        }

                    }


                    // Can't reset.
                    // Continue to add current class name/id/tag to current selector
                    if(reseted == false){

                        // Check if same selector has in the selector
                        inSelected = iframe.find(selector+window.separator+currentSelector+window.separator+currentSelector+","+selector+window.separator+previousSelector+window.separator+currentSelector).length;

                        if (status == 'default' && inSelected > 0 && space_cleaner(selector).trim() != ''){
                            selector = space_cleaner(selector).trim() + " > " + currentSelector + window.separator; // Add With '>' separator
                        }else{ 
                            selector += currentSelector + window.separator; // Add with space separator
                        }

                    }

                    previousSelector = currentSelector;


                } // Each end.


                // Fix google map contents
                if(selector.indexOf(".gm-style") != -1){
                    selector = '.gm-style';
                }


                // Check if same selector has in the selector
                inSelected = iframe.find(selector+window.separator+lastSelector+window.separator+lastSelector+","+selector+window.separator+currentSelector+window.separator+lastSelector).length;

                if (inSelected > 0){
                    selector = space_cleaner(selector + " > " + lastSelector); // Add With '>' separator
                }else{ 
                    selector = space_cleaner(selector + window.separator + lastSelector); // Add with space separator
                }


                // If there is multiple reset indexs and the selected reset is not a ID
                // so lets find the best reset selector.
                if(resetSelectors.length > 1 && selector.charAt(0) != '#'){


                    // Try to delete structural selectors
                    var newReset1 = [],passed,r;
                    for(var i = 0; i < resetSelectors.length; i++){

                        passed = true;

                        for(var ix = 0; ix < unPreferedSelectors.length; ix++){

                            // Regex
                            r = new RegExp("(\\s|^)\." + unPreferedSelectors[ix] + "(\\s|$)","gi");

                            // has
                            if(r.test(resetSelectors[i])){
                                passed = false;
                                break;
                            }

                        }

                        if(passed){
                            newReset1.push(resetSelectors[i]);
                        }

                    }


                    // Try newReset2 if newReset1 has more items than 1.
                    if(newReset1.length > 1){

                        // Prefer only classes which used in same parent depth and same tag names.
                        // The class that which used in difference depth is structural class
                        // The class that which used in difference tags is structural class.
                        var depthArray,tagArray,ek;
                        var newReset2 = [];
                        for(i = 0; i < newReset1.length; i++){

                            // Arrays
                            depthArray  = [];
                            tagArray = [];

                            // Each current class
                            iframe.find(newReset1[i]).each(function(){

                                ek = $(this);

                                // Push depth len
                                depthArray.push(ek.parents().length);

                                // Push tags used
                                tagArray.push(ek[0].nodeName);

                            });

                            var depthArrayEquals = depthArray.every(function(v, i, a){
                                return v === a[0];
                            });

                            var tagArrayEquals = tagArray.every(function(v, i, a){
                                return v === a[0];
                            });

                            // Passed depth and tag methods
                            if(depthArrayEquals && tagArrayEquals){
                                newReset2.push(newReset1[i]);
                            }

                        }

                    }


                    // empty if is undefined
                    if(isUndefined(newReset2)){
                        var newReset2 = [];
                    }


                    // null as default
                    var newFirstSelector = null;


                    // Get first selector
                    if(newReset2.length > 0){
                        newFirstSelector = newReset2[newReset2.length -1];
                    }else if(newReset1.length > 0){
                        newFirstSelector = newReset1[newReset1.length -1];
                    }


                    // if is valid
                    if(newFirstSelector != null){

                        // Get all selector exlcude first class
                        var newSelector = selector.match(/(\s)(.*?)$/g).join('').toString();
                        
                        // Get ready the new selector
                        newSelector = newFirstSelector + newSelector;

                        // be sure it is valid
                        if(check_selector(newSelector, true,false)){

                            // be sure this selectors make same work
                            if(iframe.find(newSelector).length == iframe.find(selector).length){

                                // update
                                selector = newSelector;

                            }

                        }

                    } // not valid

                }


                // Last Parent Query Status
                window.lastParentQueryStatus = status;


                // Return if is single selector
                if (status == 'sharp') {
                    return single_selector(selector, false);
                }


                // Check all others elements has same nodename or not.
                if(simpleLikedTags.indexOf(tag.toLowerCase()) != -1){

                    var foundedTags = [], n;
                    iframe.find(selector).each(function(){

                        n = $(this)[0].nodeName;

                        if(foundedTags.indexOf(n) == -1){
                            foundedTags.push(n);
                        }

                    });

                    // h1.test | div.test = Use "h1"
                    if(foundedTags.length > 1){

                        selector = $.trim(selector.match(new RegExp("^(.*?)" + selector_regex(lastSelector) + "$","g")).join('').toString()) + window.separator + tag.toLowerCase();

                    // if this is a single element, use the tag.
                    }else if(is_matchless2(selector,lastSelector,tag)){

                        selector = $.trim(selector.match(new RegExp("^(.*?)(?=" + selector_regex(lastSelector) + "$)","g")).join('').toString()) + window.separator + tag.toLowerCase();

                    }

                }


                // Getting selectors by CSS files.
                if(get_selector_array(selector).length > 1 && window.minCroppedSelector == false){

                    // Get defined selectors
                    var definedSelectors = get_defined_selector();

                    // Get valid defined selectors
                    var goodDefinedSelectors = [];

                    // Check is valid
                    if(definedSelectors.length > 0){

                        // Each founded selectors
                        $.each(definedSelectors,function(qx){

                            // Find the best in defined selectors
                            if(iframe.find(definedSelectors[qx]).length == iframe.find(selector).length){

                                // Push
                                goodDefinedSelectors.push(definedSelectors[qx]);

                            }

                        });

                        // There is good selectors?
                        if(goodDefinedSelectors.length > 0){
                            
                            // Find max long selector
                            var maxSelector = goodDefinedSelectors.sort(function(a, b) {
                                return b.length - a.length;
                            });

                            // Be sure more long than 10 char
                            if(maxSelector[0].length > 10){

                                // Update
                                selector = maxSelector[0];

                            }

                        }

                    }

                }


                // remove multiple spaces
                selector = space_cleaner(selector);


                // Cleans ">" symbols from selector if not need.
                if(selector.indexOf(">") != -1){

                    var length = selector.split(">").length;
                    var elementLength = iframe.find(selector).length;

                    for(var i = 1; i < length; i++){

                        if(iframe.find(selector.replace(/ > /i,' ')).length == elementLength){
                            selector = selector.replace(/ > /i,' ');
                        }

                    }

                }


                // Ready
                selector = multiple_variation(space_cleaner(selector));


                // Use as single inspector if selector is div and more than 20
                if(/( |>)div$/g.test(selector) && iframe.find(selector).length >= 20){
                    return single_selector(selector);
                }


                // Return result.
                return selector;

            }


            /* ---------------------------------------------------- */
            /* Return true if the element in same parent or         */
            /* selector match a single element                      */
            /* ---------------------------------------------------- */
            function is_matchless2(selector, lastSelector, tag){

                var element = iframe.find(selector);
                var element2 = iframe.find($.trim(selector.match(new RegExp("^(.*?)(?=" + selector_regex(lastSelector) + "$)","g")).join('').toString()) + window.separator + tag.toLowerCase());
                
                // If tag and class selector length is same
                if(element.length == 1 && element2.length == 1){
                    return true;

                // continue if the element length is same
                }else if(element.length == element2.length && /.|#/g.test(lastSelector)){

                    // Arrays
                    var depthArray  = [];
                    var tagArray = [];

                    // Each current class
                    iframe.find(lastSelector).each(function(){

                        var element = $(this);

                        // Push depth len
                        depthArray.push(element.parents().length);

                        // Push tags used
                        tagArray.push(element[0].nodeName);

                    });

                    var depthArrayEquals = depthArray.every(function(v, i, a){
                        return v === a[0];
                    });

                    var tagArrayEquals = tagArray.every(function(v, i, a){
                        return v === a[0];
                    });

                    // Passed depth and tag methods
                    if(depthArrayEquals && tagArrayEquals){
                        return true;
                    }

                }

                return false;

            }


            /* ---------------------------------------------------- */
            /* Draw borders.                                        */
            /* ---------------------------------------------------- */
            function draw_box(element, classes) {

                var element_p;

                if (typeof $(element) === 'undefined') {
                    element_p = $(element);
                } else {
                    element_p = iframe.find(element);
                }

                // Be sure this element have.
                if (element_p.length > 0) {

                    var marginTop = element_p.css("marginTop");
                    var marginBottom = element_p.css("marginBottom");
                    var marginLeft = element_p.css("marginLeft");
                    var marginRight = element_p.css("marginRight");

                    var paddingTop = element_p.css("paddingTop");
                    var paddingBottom = element_p.css("paddingBottom");
                    var paddingLeft = element_p.css("paddingLeft");
                    var paddingRight = element_p.css("paddingRight");

                    var marginLeftOr = marginLeft;
                    var marginRightOr = marginRight;

                    if(parseInt(paddingTop) == 0){paddingTop = "7px";}
                    if(parseInt(paddingRight) == 0){paddingRight = "5px";}
                    if(parseInt(paddingBottom) == 0){paddingBottom = "7px";}
                    if(parseInt(paddingLeft) == 0){paddingLeft = "7px";}

                    iframe.find(".yp-zero-margin-h").removeClass("yp-zero-margin-h");

                    if(parseInt(marginTop) == 0){
                        marginTop = "5px";
                        iframe.find(".yp-selected-boxed-margin-top").addClass("yp-zero-margin-h");
                    }

                    //Dynamic boxes variables
                    var element_offset = element_p.offset();
                    var topBoxes = element_offset.top;
                    var leftBoxes = element_offset.left;
                    if (leftBoxes < 0) {
                        leftBoxes = 0;
                    }

                    // Width
                    var widthBoxes = element_p.outerWidth(false);
                    var heightBoxes = element_p.outerHeight(false);
                    var bottomBoxes = topBoxes + heightBoxes;

                    // Frame Height
                    var iframeHeight = iframe.height();

                    // Show 5px Margin Bottom if element not in bottom.
                    if(parseInt(marginBottom) == 0 && Math.round(bottomBoxes) != iframeHeight && Math.round(bottomBoxes+2) != iframeHeight){
                        marginBottom = "5px";
                        iframe.find(".yp-selected-boxed-margin-bottom").addClass("yp-zero-margin-h");
                    }

                    // bottom element or not
                    if (Math.round(bottomBoxes) == iframeHeight || Math.round(bottomBoxes+2) == iframeHeight) {
                        body.addClass("yp-selected-bottom");
                    }else{
                        body.removeClass("yp-selected-bottom");
                    }

                    // Window Width
                    var iframeWidth = $("#iframe").width();
                    var scroll_width = iframeWidth - iframe.find("html").width();
                    var windowWidth = $(window).width() - window.leftbarWidth - scroll_width;

                    // Extra
                    var rightExtra = 1;
                    if (is_content_selected()) {
                        rightExtra = 2;
                    }


                    // Fix scroll problems
                    if ((leftBoxes + widthBoxes) > windowWidth) {

                        widthBoxes = windowWidth - leftBoxes - rightExtra;

                    }else if(is_responsive_mod()){

                        if ((leftBoxes + widthBoxes) > iframeWidth) {
                            widthBoxes = iframeWidth - leftBoxes - scroll_width;
                        }

                        if(iframeWidth == widthBoxes && iframe.find("html").height() > $("#iframe").height()){
                            widthBoxes = widthBoxes - scroll_width;
                        }

                    }

                    // Left in pixel to right border
                    var rightBoxes = leftBoxes + widthBoxes - rightExtra;


                    // if element full width
                    if ((leftBoxes + widthBoxes + 2) >= (iframeWidth - scroll_width)) {
                        body.addClass("yp-full-width-selected");
                        
                    }else{
                        body.removeClass("yp-full-width-selected");
                    }


                    // firefox dont get marginRight if is auto, so this fix problem.
                    var isMarginAuto = false;
                    if(iframeWidth == (parseFloat(marginLeft * 2)) + widthBoxes && parseFloat(marginLeft) > 0){
                        isMarginAuto = true;
                    }else if(element_p.parent().length > 0){
                        if(parseFloat(element_p.parent().width()) == ((parseFloat(marginLeft) * 2) + widthBoxes) && parseFloat(marginLeft) > 0){
                            isMarginAuto = true;
                        }
                    }


                    // Show empty margin left/right just if there have free space (if not full width)
                    if((iframeWidth - scroll_width) > (leftBoxes + widthBoxes + 30)){

                        iframe.find(".yp-zero-margin-w").removeClass("yp-zero-margin-w");

                        if(parseInt(marginRight) == 0){
                            marginRight = "5px";
                            iframe.find(".yp-selected-boxed-margin-right").addClass("yp-zero-margin-w");
                        }

                        if(parseInt(marginLeft) == 0){
                            marginLeft = "5px";
                            iframe.find(".yp-selected-boxed-margin-left").addClass("yp-zero-margin-w");
                        }

                    }


                    // Margin default values
                    var marginTopText = '', marginRightText = '', marginBottomText = '', marginLeftText = '';
                    if(parseInt(marginTop) > 30){marginTopText = parseInt(marginTop)+"px";}
                    if(parseInt(marginRight) > 30){marginRightText = parseInt(marginRight)+"px";}
                    if(parseInt(marginBottom) > 30){marginBottomText = parseInt(marginBottom)+"px";}
                    if(parseInt(marginLeft) > 30){marginLeftText = parseInt(marginLeft)+"px";}


                    // Padding default values
                    var paddingTopText = '', paddingRightText = '', paddingBottomText = '', paddingLeftText = '';
                    if(parseInt(paddingTop) > 30){paddingTopText = parseInt(paddingTop)+"px";}
                    if(parseInt(paddingRight) > 30){paddingRightText = parseInt(paddingRight)+"px";}
                    if(parseInt(paddingBottom) > 30){paddingBottomText = parseInt(paddingBottom)+"px";}
                    if(parseInt(paddingLeft) > 30){paddingLeftText = parseInt(paddingLeft)+"px";}

                    // Shows Auto text
                    if(isMarginAuto){
                        marginLeftText = "Auto";
                        marginRightText = "Auto";
                    }

                    // Append border elements
                    if (heightBoxes > 1 && widthBoxes > 1) {

                        if (iframe.find("." + classes + "-top").length === 0) {

                            // Border
                            var appendBox = "<div class='" + classes + "-top'></div><div class='" + classes + "-bottom'></div><div class='" + classes + "-left'></div><div class='" + classes + "-right'></div>";

                            // margin
                            appendBox += "<div class='" + classes + "-margin-top'>"+marginTopText+"</div><div class='" + classes + "-margin-bottom'>"+marginBottomText+"</div><div class='" + classes + "-margin-left'>"+marginLeftText+"</div><div class='" + classes + "-margin-right'>"+marginRightText+"</div>";

                            // padding
                            appendBox += "<div class='" + classes + "-padding-top'>"+paddingTopText+"</div><div class='" + classes + "-padding-bottom'>"+paddingBottomText+"</div><div class='" + classes + "-padding-left'>"+paddingLeftText+"</div><div class='" + classes + "-padding-right'>"+paddingRightText+"</div>";

                            // Append
                            iframeBody.append(appendBox);

                        }else{

                            // Update margin box value
                            iframe.find("." + classes + "-margin-top").text(marginTopText);
                            iframe.find("." + classes + "-margin-right").text(marginRightText);
                            iframe.find("." + classes + "-margin-bottom").text(marginBottomText);
                            iframe.find("." + classes + "-margin-left").text(marginLeftText);

                            // Update padding box value
                            iframe.find("." + classes + "-padding-top").text(paddingTopText);
                            iframe.find("." + classes + "-padding-right").text(paddingRightText);
                            iframe.find("." + classes + "-padding-bottom").text(paddingBottomText);
                            iframe.find("." + classes + "-padding-left").text(paddingLeftText);

                        }


                        // Variables for inline CSS
                        var topTop = parseFloat(topBoxes) - parseFloat(marginTop);
                        var leftLeft = parseFloat(leftBoxes) - parseFloat(marginLeft);
                        var bottomBottom = bottomBoxes - parseFloat(paddingBottom);
                        var rightRight = rightBoxes - parseFloat(paddingRight);


                        // Box Border
                        var style = "." + classes + "-top{top:"+topBoxes+"px !important;left:"+leftBoxes+"px !important;width:"+widthBoxes+"px !important;}";
                        style += "." + classes + "-bottom{top:"+bottomBoxes+"px !important;left:"+leftBoxes+"px !important;width:"+widthBoxes+"px !important;}";
                        style += "." + classes + "-left{top:"+topBoxes+"px !important;left:"+leftBoxes+"px !important;height:"+heightBoxes+"px !important;}";
                        style += "." + classes + "-right{top:"+topBoxes+"px !important;left:"+rightBoxes+"px !important;height:"+heightBoxes+"px !important;}";


                        // Max margin right position
                        var marginRightX = parseFloat(marginRight);
                        if(((rightBoxes+2) + parseFloat(marginRight)) > (iframeWidth - scroll_width)){
                            marginRightX = (iframeWidth - scroll_width) - (rightBoxes + 2);
                        }


                        // Margin
                        style += "." + classes + "-margin-top{top:"+topTop+"px !important;left:"+(parseFloat(leftBoxes) - parseFloat(marginLeftOr))+"px !important;width:"+(parseFloat(widthBoxes) + parseFloat(marginLeftOr) + parseFloat(marginRightOr))+"px !important;height:"+parseFloat(marginTop)+"px !important;}";
                        style += "." + classes + "-margin-bottom{top:"+bottomBoxes+"px !important;left:"+(parseFloat(leftBoxes) - parseFloat(marginLeftOr))+"px !important;width:"+(parseFloat(widthBoxes) + parseFloat(marginLeftOr) + parseFloat(marginRightOr))+"px !important;height:"+parseFloat(marginBottom)+"px !important;}";
                        style += "." + classes + "-margin-left{top:"+topBoxes+"px !important;left:"+leftLeft+"px !important;width:"+parseFloat(marginLeft)+"px !important;height:"+heightBoxes+"px !important;}";
                        style += "." + classes + "-margin-right{top:"+topBoxes+"px !important;left:"+(parseFloat(rightBoxes)+2)+"px !important;width:"+parseFloat(marginRightX)+"px !important;height:"+heightBoxes+"px !important;}";


                        // Padding
                        style += "." + classes + "-padding-top{top:"+parseFloat(topBoxes)+"px !important;left:"+parseFloat(leftBoxes)+"px !important;width:"+widthBoxes+"px !important;height:"+parseFloat(paddingTop)+"px !important;}";
                        style += "." + classes + "-padding-bottom{top:"+bottomBottom+"px !important;left:"+parseFloat(leftBoxes)+"px !important;width:"+widthBoxes+"px !important;height:"+parseFloat(paddingBottom)+"px !important;}";
                        style += "." + classes + "-padding-left{top:"+parseFloat(topBoxes)+"px !important;left:"+parseFloat(leftBoxes)+"px !important;width:"+parseFloat(paddingLeft)+"px !important;height:"+parseFloat(heightBoxes)+"px !important;}";
                        style += "." + classes + "-padding-right{top:"+parseFloat(topBoxes)+"px !important;left:"+rightRight+"px !important;width:"+parseFloat(paddingRight)+"px !important;height:"+parseFloat(heightBoxes)+"px !important;}";


                        // Style#yp-draw-box
                        var drawBoxStyle = iframeBody.find("#yp-draw-box");

                        // Append
                        if(drawBoxStyle.length > 0){
                            drawBoxStyle.html(style);
                        }else{
                            iframeBody.append("<style id='yp-draw-box'>"+style+"</style>");
                        }

                        if(is_resizing() == false && is_dragging() == false && is_visual_editing() == false){
                            iframe.find(".yp-selected-handle").css("left", leftBoxes).css("top", topBoxes);
                        }

                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Process media queries                                */
            /* ---------------------------------------------------- */
            function process_media_query(condition){

                var die = false;

                // Not processable
                var conRex = /\bhandheld\b|\baural\b|\bbraille\b|\bembossed\b|\bprojection\b|\btty\b|\btv\b|\bprint\b|\b3d-glasses\b/;

                if(conRex.test(condition)){
                    return null;
                }

                // not and , not acceptable
                var conRex2 = /,|\bnot\b/;
                if(conRex2.test(condition)){
                    return false;
                }

                // For replace em & and rem
                var fontSizeRotio = parseFloat(iframe.find("html").css("fontSize"));

                // replacing rem & em to PX
                condition = condition.replace(/[0-9. ]+(rem|em)/g, function(match, contents, offset, s){
                        return parseFloat(match)*fontSizeRotio+"px";
                    }
                );

                // Get all queries
                var queries = condition.match(/\((.*?)\)/g);

                var goValue = [];
                var minmaxRex = /max-width|min-width/;

                // loop queries
                $.each(queries, function(index,query){

                    // Just max and min width
                    if(minmaxRex.test(query) == false){
                        die = true;
                        return false;
                    }
                    
                    // Cleaning
                    query = query.replace(/\(|\)|:|px|\s+/g,'');

                    // max min widths
                    query = query.replace(/min-width/g,'>');
                    query = query.replace(/max-width/g,'<');

                    goValue.push(query);

                });

                // Return
                if(die == false){
                    return goValue;
                }

            }


            /* ---------------------------------------------------- */
            /* Cleans selector for regex                            */
            /* ---------------------------------------------------- */
            function selector_regex(selector){
                return selector
                .replace(   /\\/g, "\\\\") // \
                .replace(/\./g, "\\.")  // [
                .replace(/\[/g, "\\[")  // [
                .replace(/\]/g, "\\]")  // ]
                .replace(/\(/g, "\\(")  // (
                .replace(/\)/g, "\\)")  // )
                .replace(/\^/g, "\\^")  // ^
                .replace(/\$/g, "\\$")  // $
                .replace(/\*/g, "\\*")  // *
                .replace(/\:/g, "\\:")  // :
                .replace(/\+/g, "\\+"); // +
            }



            /* ---------------------------------------------------- */
            /* Getting media queries by stylesheet files            */
            /* ---------------------------------------------------- */
            function get_media_queries(css,justCondition){

                var data = window.definedStyleData;

                var dataOther;
                var mediaSelectors;
                var mediaContent;
                var mediaList = [];

                // Adding Editor data for justCondition mode.
                if(justCondition){

                    if(iframe.find("#yp-css-data-full").length == 0){
                        dataOther = get_clean_css();
                    }else{
                        dataOther = iframe.find("#yp-css-data-full").html();
                    }

                    // Getting minimized data.
                    data += get_minimized_css(dataOther,false);

                }

                // if no data, stop.
                if(data == ''){
                    return false;
                }

                data = data.toString().replace(/\}\,/g, "}");

                // Getting All media Queries.
                var allMedia = data.match(/@media(.*?){(.*?)}}/g);

                // stop if no media
                if(isUndefined(allMedia)){
                    return false;
                }

                // Clean array
                allMedia = array_cleaner(allMedia);

                // Getting just media condition
                var condition = data.match(/@media(.*?){/g);

                // stop if no media
                if(isUndefined(condition)){
                    return false;
                }
                
                // Clean array
                condition = array_cleaner(condition);
                    
                // Stop and return just condition. 
                if(justCondition){
                    return condition;
                }

                // Each media queries
                $.each(allMedia,function(index,value){

                    // Current media CSS
                    mediaContent = value.match(/\{(.*?)\}}/g).join("").replace(/\}\}$/g,'}');

                    // GEtting all selectors inside media
                    mediaSelectors = array_cleaner(mediaContent.replace(/\{(.*?)\}/g, '|BREAK|').split("|BREAK|"));

                    // Each all selectors
                    $.each(mediaSelectors,function(index,selector){

                        // End after 50
                        if(index > 50){
                            return false;
                        }

                        // Check if there any selector matches the target element
                        if(selector != '' && selector.indexOf("*") == -1  && selector.indexOf(":") == -1 && selector.indexOf("@") == -1 && check_selector(selector,false,false) !== false){

                            if(iframe.find(get_foundable_query(selector,true,true,true)).hasClass("yp-selected")){

                                var ruleAll = mediaContent.match(new RegExp(selector_regex(selector) + "(\s+)?\{(.*?)\}",'gi')).toString();

                                var rules = array_cleaner(ruleAll.match(/\{(.*?)\}/g).toString().replace(/:(.*?)(;|})(\s+)?/g,'|BREAK|').replace(/(\{|\}|\,)/g,'').split("|BREAK|"));

                                // If the current media has any selector
                                // for target element, so process the media query.
                                if(rules.indexOf(css.replace(/(min-|max-)/g,'')) != -1){

                                    mediaList.push( value.match(/\@media(.*?){/g).toString().replace("{",'') );

                                }

                            }

                        }

                    });

                });

        
                // Each all media list
                var mediaQueries = [];
                var foundedMedia = false;
                var processed,queryX;
                $.each(array_cleaner(mediaList),function(index,query){

                    // Processed
                    processed = process_media_query(query);

                    // Adds mediaQueries
                    mediaQueries.push( processed );

                    // Basic for check with matchMedia
                    queryX = space_cleaner(query.replace("@media",""));

                    // add if it is active
                    if(window.matchMedia(queryX).matches && processed != false && processed != null){
                        foundedMedia = query;
                    }

                });

                if(foundedMedia != false){
                    return foundedMedia;
                }

                return creating_auto_media_query(mediaQueries);

            }


            /* ---------------------------------------------------- */
            /* Generate automatic media query                       */
            /* ---------------------------------------------------- */
            function creating_auto_media_query(arrMedia){

                var condition = false;
                var closestLow;

                // Current Width
                var currentWidth = $(window).width();

                var upArr = [];
                var downArr = [];

                // ARR Media
                $.each(arrMedia,function(index,value){
                    
                    if(value != null){
                        value = value.toString();
                        if(value.indexOf("<") != -1){
                            downArr.push(value.replace(/\</g,''));
                        }else if(value.indexOf(">") != -1){
                            upArr.push(value.replace(/\>/g,''));
                        }
                    }

                });

                // High to low
                upArr = upArr.sort(function(a, b){return b-a});
                downArr = downArr.sort(function(a, b){return b-a});

                $.each(downArr, function(){
                  if (this <= currentWidth && (closestLow == null || (currentWidth - this) < (currentWidth - closestLow))) {
                    closestLow = this;
                  }
                });

                // if min-width high and max-width low than current width
                if(downArr.length > 0 && upArr.length > 0){
                    if(upArr[0] > currentWidth && downArr[0] < currentWidth){
                        condition = '@media (min-width:'+downArr[0]+'px) and (max-width:'+upArr[0]+'px)';
                    }
                }

                // if min-width and max-width high than current width
                if(downArr.length > 0 && upArr.length > 0){
                    if(upArr[0] > currentWidth && downArr[0] > currentWidth){

                        if(closestLow < currentWidth){
                            condition = '@media (max-width:'+upArr[0]+'px) and (min-width:'+closestLow+'px)';
                        }else{
                            condition = '@media (max-width:'+upArr[0]+'px)';
                        }

                    }
                }

                // if min-width and max-width high than current width
                if(downArr.length == 0 && upArr.length > 0){
                    if(upArr[0] > currentWidth){
                        condition = '@media (max-width:'+upArr[0]+'px)';
                    }
                }

                // if min-width and max-width low than current width
                if(downArr.length > 0 && upArr.length > 0){
                    if(upArr[0] < currentWidth && downArr[0] < currentWidth){
                        condition = '@media (min-width:'+downArr[0]+'px)';
                    }
                }

                // if min-width and max-width low than current width
                if(downArr.length > 0 && upArr.length == 0){
                    if(downArr[0] < currentWidth){
                        condition = '@media (min-width:'+downArr[0]+'px)';
                    }
                }

                // if min-width and max-width low than current width
                if(downArr.length > 1 && upArr.length == 0){
                    if(downArr[0] > currentWidth && closestLow < currentWidth){
                        condition = '@media (max-width:'+downArr[0]+'px) and (min-width:'+closestLow+'px)';
                    }
                }

                return condition;

            }


            /* ---------------------------------------------------- */
            /* Getting the scrollbar Width                          */
            /* ---------------------------------------------------- */
            function get_scroll_bar_width() {

                // no need on responsive mode.
                if (is_responsive_mod()) {
                    return 0;
                }

                // If no scrollbar, return zero.
                if (iframe.height() <= $(window).height() && mainBody.hasClass("yp-metric-disable")) {
                    return 0;
                }

                var inner = document.createElement('p');
                inner.style.width = "100%";
                inner.style.height = "200px";

                var outer = document.createElement('div');
                outer.style.position = "absolute";
                outer.style.top = "0px";
                outer.style.left = "0px";
                outer.style.visibility = "hidden";
                outer.style.width = "200px";
                outer.style.height = "150px";
                outer.style.overflow = "hidden";
                outer.appendChild(inner);

                document.body.appendChild(outer);
                var w1 = inner.offsetWidth;
                outer.style.overflow = 'scroll';
                var w2 = inner.offsetWidth;
                if (w1 == w2) w2 = outer.clientWidth;

                document.body.removeChild(outer);

                return (w1 - w2);

            }


            /* ---------------------------------------------------- */
            /* Unselect multiple selected items                     */
            /* ---------------------------------------------------- */
            iframe.on("click", '.yp-selected-others', function() {

                var el = $(this);

                var currentSelector = get_current_selector();

                if(mainBody.hasClass("yp-control-key-down") && currentSelector.split(",").length > 0){

                    // Remove YP Classes
                    el.removeClass("yp-selected-others yp-recent-hover-element");

                    // Get Selector
                    var selector = get_parents(el,'sharp');

                    currentSelector = currentSelector.replace(new RegExp(","+selector_regex(selector),"g"),"");

                    var firstEl = get_selected_element();

                    set_selector(currentSelector,firstEl);

                    // return false to block other click function
                    return false;

                }

            });



            /* ---------------------------------------------------- */
            /* Draw other borders.                                  */
            /* ---------------------------------------------------- */
            function draw_other_box(element, classes, $i) {

                var element_p = $(element);

                var elementClasses = element_p.attr("class");

                if (element_p === null) {
                    return false;
                }

                if (element_p[0].nodeName == "HTML" || element_p[0].nodeName == "BODY") {
                    return false;
                }

                if (element_p.length === 0) {
                    return false;
                }

                // Be sure this is visible on screen
                if (element_p.css("display") == 'none' || element_p.css("visibility") == 'hidden' || element_p.css("opacity") == '0') {
                    return false;
                }

                // Not show if p tag and is empty.
                if (element_p.html() == '&nbsp;' && element_p.prop("tagName") == 'P') {
                    return false;
                }

                // Stop.
                if(mainBody.hasClass("yp-has-transform")){
                    return false;
                }

                // not draw new box and delete last.
                if(isDefined(elementClasses)){

                    elementClasses = elementClasses.replace(/yp-selected-others/g,'');

                    var pluginelRex = /yp-selected|yp-tooltip-small|yp-edit-/;

                    if(pluginelRex.test(elementClasses) || element_p.hasClass("yp-selected-others-box")){
                        if(iframe.find("." + classes + "-" + $i + "-box").length > 0){
                            iframe.find("." + classes + "-" + $i + "-box").remove();
                        }

                        return false;

                    }

                }
                    
                // Stop.
                if (check_with_parents(element_p, "transform", "none", "!=") === true) {
                    element_p.addClass("yp-selected-has-transform");
                    return false;
                }

                // Stop.
                if (check_with_parents(element_p, "display", "none", "==") === true || check_with_parents(element_p, "opacity", "0", "==") === true || check_with_parents(element_p, "visibility", "hidden", "==") === true) {
                    return false;
                }

                //Dynamic boxes variables
                var element_offset = element_p.offset();
                var topBoxes = element_offset.top;
                var leftBoxes = element_offset.left;
                var widthBoxes = element_p.outerWidth(false);
                var heightBoxes = element_p.outerHeight(false);

                if (heightBoxes > 1 && widthBoxes > 1) {

                    // Append Dynamic Box
                    if (iframe.find("." + classes + "-" + $i + "-box").length === 0) {

                        return "<div class='" + classes + "-box " + classes + "-" + $i + "-box' style='top:"+parseFloat(topBoxes)+"px !important;left:"+parseFloat(leftBoxes)+"px !important;width:"+parseFloat(widthBoxes)+"px !important;height:"+parseFloat(heightBoxes)+"px !important;'></div>";

                    }else{

                        // Update boxes
                        iframeBody.find("." + classes + "-" + $i + "-box").css("top",parseFloat(topBoxes)+"px").css("left",parseFloat(leftBoxes)+"px").css("width",parseFloat(widthBoxes)+"px").css("height",parseFloat(heightBoxes)+"px");

                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Visible Height in scroll.                            */
            /* ---------------------------------------------------- */
            function get_visible_height(t) {
                var top = t.offset().top;
                var scrollTop = iframe.scrollTop();
                var height = t.outerHeight();

                if (top < scrollTop) {
                    return height - (scrollTop - top);
                } else {
                    return height;
                }

            }


            /* ---------------------------------------------------- */
            /* Draw Tooltip.                                        */
            /* ---------------------------------------------------- */
            function draw_tooltip(){

                var tooltip = iframe.find(".yp-selected-tooltip");
                var tooltipMenu = iframe.find(".yp-edit-tooltip");

                if (tooltip.length <= 0) {
                    return false;
                }

                // remove small tooltip class
                tooltip.removeClass("yp-small-tooltip");

                // Hide until set position to tooltip if element still not selected.
                if (!is_content_selected()) {
                    tooltip.css("visibility", "hidden");
                    tooltipMenu.css("visibility", "hidden");
                }

                var element = get_selected_element();

                // If not visible stop.
                if (check_with_parents(element, "display", "none", "==") === true || check_with_parents(element, "opacity", "0", "==") === true || check_with_parents(element, "visibility", "hidden", "==") === true) {
                    return false;
                }

                var element_offset = element.offset();

                if (isUndefined(element_offset)) {
                    return false;
                }

                tooltip.removeClass("yp-tooltip-bottom-outside");

                var topElement = parseFloat(element_offset.top) - 24;

                var leftElement = parseFloat(element_offset.left);

                if(leftElement < 0){
                    leftElement = 0;
                }

                tooltip.css("top", topElement).css("left", leftElement);
                tooltipMenu.css("top", topElement).css("left", leftElement);

                // If outside of bottom, show.
                if (topElement >= ($(window).height() + iframe.scrollTop() - 24)) {

                    if (!tooltip.hasClass("yp-fixed-tooltip")) {
                        tooltip.addClass("yp-fixed-tooltip");
                    }

                    // Update
                    topElement = ($(window).height() + iframe.scrollTop() - 24);

                    tooltip.addClass("yp-fixed-tooltip-bottom");

                } else {

                    if (tooltip.hasClass("yp-fixed-tooltip")) {
                        tooltip.removeClass("yp-fixed-tooltip");
                    }

                    tooltip.removeClass("yp-fixed-tooltip-bottom");

                }

                // If out of top, show.
                var tooltipRatio;
                if (topElement < 2 || topElement < (iframe.scrollTop() + 2)) {

                    var bottomBorder = iframe.find(".yp-selected-boxed-bottom");

                    topElement = parseFloat(bottomBorder.css("top")) - parseFloat(get_visible_height(element));

                    tooltip.css("top", topElement);
                    tooltipMenu.css("top", topElement);

                    tooltip.addClass("yp-fixed-tooltip");

                    tooltipRatio = (tooltip.outerHeight() * 100 / get_visible_height(element));

                    if (tooltipRatio > 10) {
                        tooltip.addClass("yp-tooltip-bottom-outside");
                        topElement = parseFloat(bottomBorder.css("top")) - parseFloat(tooltip.outerHeight()) + tooltip.outerHeight();

                        tooltip.css("top", topElement);
                        tooltipMenu.css("top", topElement);

                    } else {
                        tooltip.removeClass("yp-tooltip-bottom-outside");
                    }

                } else {
                    tooltip.removeClass("yp-fixed-tooltip");
                }

                if (tooltipRatio < 11) {
                    tooltip.removeClass("yp-tooltip-bottom-outside");
                }

                if (tooltip.hasClass("yp-fixed-tooltip") && tooltip.hasClass("yp-tooltip-bottom-outside") === false) {
                    tooltipMenu.addClass("yp-fixed-edit-menu");
                } else {
                    tooltipMenu.removeClass("yp-fixed-edit-menu");
                }

                if (tooltip.hasClass("yp-tooltip-bottom-outside")) {
                    tooltipMenu.addClass("yp-bottom-outside-edit-menu");
                } else {
                    tooltipMenu.removeClass("yp-bottom-outside-edit-menu");
                }

                if (tooltip.hasClass("yp-fixed-tooltip-bottom")) {
                    tooltipMenu.addClass("yp-fixed-bottom-edit-menu");
                } else {
                    tooltipMenu.removeClass("yp-fixed-bottom-edit-menu");
                }


                tooltip.css({"visibility":"visible","pointer-events":"none"});
                tooltipMenu.css({"visibility":"visible","pointer-events":"none"});

                    // If high
                    if ($("#iframe").width() - (tooltip.width() + tooltip.offset().left + 80) <= 0) {

                        // simple tooltip.
                        tooltip.addClass("yp-small-tooltip");

                    } else { // If not high

                        // if already simple tooltip
                        if (tooltip.hasClass("yp-small-tooltip")) {

                            // return to default.
                            tooltip.removeClass("yp-small-tooltip");

                            // check again if need to be simple
                            if ($("#iframe").width() - (tooltip.width() + tooltip.offset().left + 80) <= 0) {

                                // make it simple.
                                tooltip.addClass("yp-small-tooltip");

                            }

                        }

                    }

                tooltip.css({"pointer-events":"auto"});
                tooltipMenu.css({"pointer-events":"auto"});

            }


            /* ---------------------------------------------------- */
            /* Trigger mouseup event if mouseup on iframe.          */
            /* ---------------------------------------------------- */
            iframe.on("mouseup", iframe, function() {

                $(document).trigger("mouseup");

            });


            /* ---------------------------------------------------- */
            /* Installing draggable event to the element            */
            /* ---------------------------------------------------- */
            function set_draggable(element) {

                // Add drag support
                if (iframeBody.find(".yp-selected").length > 0) {

                    element.draggable({

                        containment: "document",
                        delay: 100,
                        start: function(e, ui) {

                            window.elDragWidth = element.outerWidth();
                            window.elDragHeight = element.outerHeight();

                            if (mainBody.hasClass("yp-css-editor-active")) {
                                $(".css-editor-btn").trigger("click");
                            }

                            if (!is_content_selected()) {
                                return false;
                            }

                            // Close contextmenu
                            if (iframe.find(".context-menu-active").length > 0) {
                                get_selected_element().contextMenu("hide");
                            }

                            get_selected_element().removeClass("yp_onscreen yp_hover yp_click yp_focus");

                            // Get Element Style attr.
                            window.styleAttr = element.attr("style");

                            // Add some classes
                            body.addClass("yp-clean-look yp-dragging yp-hide-borders-now");

                            // show position tooltip
                            iframeBody.append("<div class='yp-helper-tooltip'></div>");

                            create_smart_guides();

                            // Delete important tag from old for let to drag elements. Top left right bottom..
                            var corners = ['top','left','right','bottom'],ex;
                            for(var i = 0; i < 4; i++){

                                ex = iframe.find("[data-style='"+get_id(get_current_selector())+"'][data-rule='"+corners[i]+"']");

                                if(ex.length > 0){
                                    ex.html(ex.html().replace(/\s+?!important/g,'').replace(/\;$/g,''));
                                }

                            }


                        },
                        drag: function(event, ui) {

                            if (window.elDragHeight != $(this).outerHeight()) {
                                element.css("width", window.elDragWidth + 1);
                                element.css("height", window.elDragHeight);
                            }

                            // Smart Guides. :-)

                            // tolerance.
                            var t = 6;

                            // Defaults
                            var c,f;

                            // Variables
                            var wLeft,wWidth,wTop,wHeight,otherTop,otherLeft,otherWidth,otherHeight,otherBottom,otherRight;

                            // this
                            var self = $(this);

                            // This offets
                            draw_box(".yp-selected", 'yp-selected-boxed');

                            var selfRW = self.outerWidth();
                            var selfTop = Math.round(parseFloat(iframeBody.find(".yp-selected-boxed-top").css("top")));
                            var selfLeft = Math.round(parseFloat(iframeBody.find(".yp-selected-boxed-left").css("left")));
                            var selfRight = Math.round(parseFloat(iframeBody.find(".yp-selected-boxed-right").css("left")));
                            var selfBottom = Math.round(parseFloat(iframeBody.find(".yp-selected-boxed-bottom").css("top")));

                            // sizes
                            var selfWidth = selfRight - selfLeft;
                            var selfHeight = selfBottom - selfTop;
                            var selfPLeft = parseFloat(self.css("left"));
                            var selfPTop = parseFloat(self.css("top"));

                            // Margin
                            var selfTopMargin = parseFloat(self.css("marginTop"));
                            var selfLeftMargin = parseFloat(self.css("marginLeft"));

                            // Bottom
                            var yBorder = iframeBody.find(".yp-y-distance-border");
                            var xBorder = iframeBody.find(".yp-x-distance-border");

                            xBorder.css("display", "none");
                            yBorder.css("display", "none");


                            // Search for:
                            // top in top 
                            // bottom in bottom
                            // top in bottom
                            // bottom in top
                            var axsisxEl = iframeBody.find(".yp-smart-guide-elements[data-yp-bottom-round='" + yp_round(selfBottom) + "']");
                            axsisxEl = axsisxEl.add(iframeBody.find(".yp-smart-guide-elements[data-yp-top-round='" + yp_round(selfTop) + "']"));
                            axsisxEl = axsisxEl.add(iframeBody.find(".yp-smart-guide-elements[data-yp-top-round='" + yp_round(selfBottom) + "']"));
                            axsisxEl = axsisxEl.add(iframeBody.find(".yp-smart-guide-elements[data-yp-bottom-round='" + yp_round(selfTop) + "']"));

                            if (axsisxEl.length > 0) {

                                // Getting sizes
                                otherTop = parseFloat(axsisxEl.attr("data-yp-top"));
                                otherLeft = parseFloat(axsisxEl.attr("data-yp-left"));
                                otherWidth = parseFloat(axsisxEl.attr("data-yp-width"));
                                otherHeight = parseFloat(axsisxEl.attr("data-yp-height"));
                                otherBottom = parseFloat(otherTop + otherHeight);
                                otherRight = parseFloat(otherLeft + otherWidth);

                                // Calculate smart guides positions.
                                if (selfLeft > otherLeft) {
                                    wLeft = otherLeft;
                                    wWidth = selfRight - otherLeft;
                                } else {
                                    wLeft = selfLeft;
                                    wWidth = otherRight - selfLeft;
                                }

                                // TOP = TOP
                                if (axsisxEl.attr("data-yp-top-round") == yp_round(selfTop)) {
                                    wTop = otherTop;
                                }

                                // BOTTOM = BOTTOM
                                if (axsisxEl.attr("data-yp-bottom-round") == yp_round(selfBottom)) {
                                    wTop = otherBottom;
                                }

                                // BOTTOM = TOP
                                if (axsisxEl.attr("data-yp-bottom-round") == yp_round(selfTop)) {
                                    wTop = otherBottom;
                                }

                                // TOP = BOTTOM
                                if (axsisxEl.attr("data-yp-top-round") == yp_round(selfBottom)) {
                                    wTop = otherTop;
                                }

                                // controllers
                                c = (ui.offset.top + selfTopMargin) - otherTop;

                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherTop - selfTop) + selfPTop);
                                    ui.position.top = f;
                                    xBorder.css({'top': wTop,'left': wLeft,'width': wWidth,'display': 'block'});
                                }

                                c = (ui.offset.top + selfTopMargin) - otherBottom + selfHeight;

                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherBottom - selfBottom) + selfPTop);
                                    ui.position.top = f;
                                    xBorder.css({'top': wTop,'left': wLeft,'width': wWidth,'display': 'block'});
                                }

                                c = (ui.offset.top + selfTopMargin) - otherTop + selfHeight;

                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherTop - selfBottom) + selfPTop);
                                    ui.position.top = f;
                                    xBorder.css({'top': wTop,'left': wLeft,'width': wWidth,'display': 'block'});
                                }

                                c = (ui.offset.top + selfTopMargin) - otherBottom;

                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherBottom - selfTop) + selfPTop);
                                    ui.position.top = f;
                                    xBorder.css({'top': wTop,'left': wLeft,'width': wWidth,'display': 'block'});
                                }

                            }


                            // Search for:
                            // left in left
                            // right in right
                            // left in right
                            // right in left
                            var axsisyEl = iframeBody.find(".yp-smart-guide-elements[data-yp-right-round='" + yp_round(selfRight) + "']");

                            axsisyEl = axsisyEl.add(iframeBody.find(".yp-smart-guide-elements[data-yp-left-round='" + yp_round(selfLeft) + "']"));

                            axsisyEl = axsisyEl.add(iframeBody.find(".yp-smart-guide-elements[data-yp-left-round='" + yp_round(selfRight) + "']"));

                            axsisyEl = axsisyEl.add(iframeBody.find(".yp-smart-guide-elements[data-yp-right-round='" + yp_round(selfLeft) + "']"));

                            if (axsisyEl.length > 0) {

                                // Getting sizes
                                otherTop = parseFloat(axsisyEl.attr("data-yp-top"));
                                otherLeft = parseFloat(axsisyEl.attr("data-yp-left"));
                                otherWidth = parseFloat(axsisyEl.attr("data-yp-width"));
                                otherHeight = parseFloat(axsisyEl.attr("data-yp-height"));
                                otherBottom = parseFloat(otherTop + otherHeight);
                                otherRight = parseFloat(otherLeft + otherWidth);

                                // Calculate smart guides positions.
                                if (selfTop > otherTop) {
                                    wTop = otherTop;
                                    wHeight = selfBottom - otherTop;
                                } else {
                                    wTop = selfTop;
                                    wHeight = otherBottom - selfTop;
                                }

                                // LEFT = LEFT
                                if (axsisyEl.attr("data-yp-left-round") == yp_round(selfLeft)) {
                                    wLeft = otherLeft;
                                }

                                // RIGHT = RIGHT
                                if (axsisyEl.attr("data-yp-right-round") == yp_round(selfRight)) {
                                    wLeft = otherRight;
                                }

                                // RIGHT = LEFT
                                if (axsisyEl.attr("data-yp-right-round") == yp_round(selfLeft)) {
                                    wLeft = otherRight;
                                }

                                // LEFT = RIGHT
                                if (axsisyEl.attr("data-yp-left-round") == yp_round(selfRight)) {
                                    wLeft = otherLeft;
                                }

                                // controller
                                c = (ui.offset.left + selfLeftMargin) - otherLeft;

                                // Sharpening
                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherLeft - selfLeft) + selfPLeft);
                                    ui.position.left = f;
                                    yBorder.css({'top': wTop,'left': wLeft,'height': wHeight,'display': 'block'});
                                }

                                // controller
                                c = (ui.offset.left + selfLeftMargin) - otherRight;

                                // Sharpening
                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherRight - selfLeft) + selfPLeft);
                                    ui.position.left = f;
                                    yBorder.css({'top': wTop,'left': wLeft,'height': wHeight,'display': 'block'});
                                }

                                // controller
                                c = (ui.offset.left + selfLeftMargin) - otherRight + selfWidth;

                                // Sharpening
                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherRight - selfRight) + selfPLeft);
                                    ui.position.left = f;
                                    yBorder.css({'top': wTop,'left': wLeft,'height': wHeight,'display': 'block'});
                                }

                                // controller
                                c = Math.round((ui.offset.left + selfLeftMargin) - otherLeft + selfRW);

                                // Sharpening
                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherLeft - selfRight) + selfPLeft - (selfRW - selfWidth));
                                    ui.position.left = f;
                                    yBorder.css({'top': wTop,'left': wLeft,'height': wHeight,'display': 'block'});
                                }

                            }


                            if (ui.position.top == 1 || ui.position.top == -1 || ui.position.top == 2 || ui.position.top == -2) {
                                ui.position.top = 0;
                            }

                            if (ui.position.left == 1 || ui.position.left == -1 || ui.position.left == 2 || ui.position.left == -2) {
                                ui.position.left = 0;
                            }

                            // Update helper tooltip
                            if (selfTop >= 60) {
                                iframeBody.find(".yp-helper-tooltip").css({
                                    'top': selfTop,
                                    'left': selfLeft
                                }).html("X : " + parseInt(ui.position.left) + " px<br>Y : " + parseInt(ui.position.top) + " px");
                            } else {
                                iframeBody.find(".yp-helper-tooltip").css({
                                    'top': selfTop + selfHeight + 40 + 10,
                                    'left': selfLeft
                                }).html("X : " + parseInt(ui.position.left) + " px<br>Y : " + parseInt(ui.position.top) + " px");
                            }

                        },
                        stop: function() {

                            clean_smart_guides();

                            var delay = 1;

                            // CSS To Data.
                            if (mainBody.hasClass("yp-need-to-process")) {
                                process(false, false);
                                delay = 70;
                            }

                            // Draw tooltip qiuckly
                            draw_tooltip();

                            // Wait for process.
                            setTimeout(function() {

                                var t = element.css("top");
                                var l = element.css("left");
                                var b = element.css("bottom");
                                var r = element.css("right");


                                // Back To Orginal Style Attr.
                                if (isDefined(window.styleAttr)) {

                                    var trimAtr = window.styleAttr.replace(/position:(\s*?)relative(\;?)/g,"");

                                    if (trimAtr == '') {
                                        element.removeAttr("style");
                                    } else {
                                        element.attr("style", trimAtr);
                                    }

                                } else {
                                    element.removeAttr("style");
                                }


                                // Insert new data.
                                insert_rule(null, "top", t, '');
                                insert_rule(null, "left", l, '');

                                if (parseFloat(t) + parseFloat(b) !== 0) {
                                    insert_rule(null, "bottom", "auto", '');
                                }

                                if (parseFloat(l) + parseFloat(r) !== 0) {
                                    insert_rule(null, "right", "auto", '');
                                }

                                // Adding styles
                                if (element.css("position") == 'static') {
                                    insert_rule(null, "position", "relative", '');
                                }

                                if ($("#position-static").parent().hasClass("active") || $("#position-relative").parent().hasClass("active")) {
                                    $("#position-relative").trigger("click");
                                }

                                // Set default values for top and left options.
                                if ($("li.position-option.active").length > 0) {
                                    $("#top-group,#left-group").each(function() {
                                        set_default_value(get_option_id(this));
                                    });
                                } else {
                                    $("li.position-option").removeAttr("data-loaded"); // delete cached data.
                                }

                                // Remove
                                iframe.find(".yp-selected,.yp-selected-others").removeClass("ui-draggable-handle ui-draggable-handle");

                                // Update css.
                                option_change();

                                body.removeClass("yp-clean-look yp-dragging yp-hide-borders-now");

                                draw();

                                gui_update();

                            }, delay);

                        }

                    });

                }

            }


            /* ---------------------------------------------------- */
            /* Updating drag icon position                          */
            /* ---------------------------------------------------- */
            function update_drag_handle_position() {

                // Element selected?
                if (!is_content_selected()) {
                    return false;
                }

                // element
                var element = get_selected_element();

                var handle = iframe.find(".yp-selected-handle");

                // Add new
                if (element.height() > 20 && element.width() > 60 && handle.length === 0) {
                    iframeBody.append("<span class='yp-selected-handle'></span>");
                }

                handle.css("left", iframe.find(".yp-selected-boxed-right").css("left"));
                handle.css("top", iframe.find(".yp-selected-boxed-bottom").css("top"));
                handle.css("opacity", iframe.find(".yp-selected-boxed-bottom").css("opacity"));

            }


            window.mouseisDown = false;
            window.styleAttrBeforeChange = null;
            window.visualResizingType = null;
            window.ResizeSelectedBorder = null;
            window.elementOffsetLeft = null;
            window.elementOffsetRight = null;

            /* ---------------------------------------------------- */
            /* Getting the domain                                   */
            /* ---------------------------------------------------- */
            function get_domain(url) {
                var domain;
                if (url.indexOf("://") > -1) {
                    domain = url.split('/')[2];
                } else {
                    domain = url.split('/')[0];
                }
                domain = domain.split(':')[0];
                return $.trim(domain);
            }


            /* ---------------------------------------------------- */
            /* Getting absolute Path                                */
            /* ---------------------------------------------------- */
            var get_absolute_path = function(href){
                var link = document.createElement("a");
                link.href = href;
                return (link.protocol+"//"+link.host+link.pathname+link.search+link.hash);
            };


            // Surfing on iframe
            iframe.find('a[href]').on("click", iframe, function(evt) {

                $(this).attr("target", "_self");

                if(mainBody.hasClass("yp-metric-disable") === false){
                    return false;
                }

                // if aim mode disable.
                if ($(".yp-selector-mode.active").length === 0) {

                    var href = $(this).attr("href");

                    if (href == '' || href == '#' || href.indexOf("yellow-pencil-editor") != -1) {
                        return false;
                    }

                    // Get full URL
                    href = get_absolute_path(href);

                    if (href.indexOf("#noAiming") > -1) {
                        swal({title: "Sorry.",text: "This link is not an wordpress page. You can't edit this page.",type: "warning",animation: false});
                        return false;
                    }

                    if (href !== null && href != '' && href.charAt(0) != '#' && href.indexOf("javascript:") == -1 && href.indexOf("yellow_pencil=true") == -1) {

                        var link_host = get_domain(href);
                        var main_host = window.location.hostname;

                        if (link_host != main_host) {
                            swal({title: "Sorry.",text: "This is external link. You can't edit this page.",type: "warning",animation: false});
                            return false;
                        }

                        if (href.indexOf(siteurl.split("://")[1]) == -1 || href.indexOf("wp-login.php?action=logout") != -1) {
                            swal({title: "Sorry.",text: "This link is not an wordpress page. You can't edit this page.",type: "warning",animation: false});
                            return false;
                        }

                        // https to http
                        if (location.protocol == 'http:' && href.indexOf('https:') != -1 && href.indexOf('http:') == -1) {
                            href = href.replace("https:", "http:");
                            $(this).attr("href", href);
                        }

                        // Http to https
                        if (location.protocol == 'https:' && href.indexOf('http:') != -1 && href.indexOf('https:') == -1) {
                            href = href.replace("http:", "https:");
                            $(this).attr("href", href);
                        }

                        // if selector mode not active and need to save.
                        if ($(".yp-save-btn").hasClass("waiting-for-save")){
                            if (confirm(l18_sure) == true) {
                                $(".waiting-for-save").removeClass("waiting-for-save");
                            } else {
                                return false;
                            }
                        }

                    } else {
                        return false;
                    }

                    $("#iframe").remove();
                    body.removeClass("yp-yellow-pencil-loaded");
                    $(".loading-files").html("Page loading..");

                    // Get parent url
                    var parentURL = window.location;

                    //delete after href.
                    parentURL = parentURL.toString().split("href=")[0] + "href=";

                    // get iframe url
                    var newURL = href;
                    if (newURL.substring(0, 6) == 'about:') {
                        $(this).show();
                        return false;
                    }

                    $.get(newURL, function(data){

                        mainBody.append("<div id='yp-load-test-admin'></div>");

                        newURL = newURL.replace(/.*?:\/\//g, ""); // delete protocol
                        newURL = newURL.replace("&yellow_pencil_frame=true", "").replace("?yellow_pencil_frame=true", "");
                        newURL = encodeURIComponent(newURL); // encode url
                        parentURL = parentURL + newURL; // update parent URL

                        window.location = parentURL;

                    });

                    

                }

            });


            /* ---------------------------------------------------- */
            /* Element Select, Cancel Select Funcs                  */
            /* ---------------------------------------------------- */
            iframe.on("click", iframe, function(evt) {

                if ($(".yp-selector-mode.active").length > 0 && mainBody.hasClass("yp-metric-disable")) {

                    if (evt.which == 1 || evt.which === undefined) {
                        evt.stopPropagation();
                        evt.preventDefault();
                    }


                    // Not clickable while animate playing
                    if(body.hasClass("yp-animate-manager-playing")){
                        return false;
                    }

                    // Visual Edited
                    if(body.hasClass("yp-visual-edited")){
                        body.removeClass("yp-visual-edited");
                        return false;
                    }

                    // Resized
                    if (body.hasClass("yp-element-resized") || body.hasClass("resize-time-delay")) {
                        body.removeClass("yp-element-resized resize-time-delay");
                        return false;
                    }

                    // Colorpicker for all elements.
                    if (mainBody.hasClass("yp-element-picker-active")) {
                        $(".yp-element-picker-active").removeClass("yp-element-picker-active");
                        $(".yp-element-picker.active").removeClass("active");
                        return false;
                    }

                    if ($(".yp_flat_colors_area:visible").length !== 0) {

                        $(".yp-flat-colors.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if ($(".yp_meterial_colors_area:visible").length !== 0) {

                        $(".yp-meterial-colors.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if ($(".yp_nice_colors_area:visible").length !== 0) {

                        $(".yp-nice-colors.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if ($(".iris-picker:visible").length !== 0) {

                        $(".iris-picker:visible").each(function() {
                            $(this).hide();
                        });

                        gui_update();

                        return false;

                    }

                    if ($(".yp_background_assets:visible").length !== 0) {

                        $(".yp-bg-img-btn.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if ($(".yp-gradient-section:visible").length !== 0) {

                        $(".yp-gradient-btn.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if (mainBody.hasClass("autocomplete-active")) {

                        $(".input-autocomplete").each(function() {
                            $(this).autocomplete("close");
                        });

                        return false;

                    }

                    if (is_content_selected() === true) {

                        // CSS To Data.
                        if (mainBody.hasClass("yp-need-to-process")) {
                            process(false, false);
                        }

                        if (iframe.find(".context-menu-active").length > 0) {
                            get_selected_element().contextMenu("hide");
                            return false;
                        }

                    }

                    var element = $(evt.target);
                    var element_offset;

                    if (evt.which === undefined || evt.which == 1) {

                        if (is_content_selected() === true) {

                            if (element.hasClass("yp-edit-menu") && element.hasClass("yp-content-selected") === false) {
                                element_offset = element.offset();
                                var x = element_offset.left;
                                if (x === 0) {
                                    x = 1;
                                }
                                var y = element_offset.top + 26;
                                get_selected_element().contextMenu({
                                    x: x,
                                    y: y
                                });
                                return false;
                            }

                            if (element.hasClass("yp-selected-tooltip")) {
                                $(".yp-button-target").trigger("click");
                                return false;
                            } else if (element.parent().length > 0) {
                                if (element.parent().hasClass("yp-selected-tooltip")) {
                                    $(".yp-button-target").trigger("click");
                                    return false;
                                }
                            }

                            // click notting on visual margin/padding helper
                            if(element.is("[class*=yp-selected-boxed-margin-],[class*=yp-selected-boxed-padding-]")){
                                clearTimeout(window.visualEditDelay);
                                return false;
                            }

                        }

                    }

                    if (body.hasClass("yp-selector-disabled")) {
                        return false;
                    }

                    if (body.hasClass("yp-disable-disable-yp")) {
                        return false;
                    }

                    var selector = get_parents(element, "default");

                    if (mainBody.hasClass("autocomplete-active") && selector == 'body') {
                        return false;
                    }

                    if (evt.which == 1 || evt.which === undefined) {

                        if (element.hasClass("yp-selected") === false) {

                            if (is_content_selected() === true && element.parents(".yp-selected").length != 1) {

                                if (is_animate_creator() && is_dragging() === false) {
                                    if (!confirm(l18_closeAnim)) {
                                        return false;
                                    } else {
                                        yp_anim_cancel();
                                        return false;
                                    }
                                }

                                // Multiable Selector
                                if(is_content_selected() && mainBody.hasClass("yp-control-key-down")){

                                    if(element.hasClass("yp-selected-others-box") === false){

                                        var selectorCurrent = get_current_selector();
                                        var selectorNew = get_parents(element, "sharp");
                                        iframe.find(".yp-selected-others-multiable-box").remove();
                                        iframe.find(".yp-multiple-selected").addClass("yp-selected-others").removeClass("yp-multiple-selected");
                                        set_selector(selectorCurrent+","+selectorNew,get_selected_element());

                                        // Disable focus style after clicked.
                                        element.blur();

                                    }


                                    return false;

                                }

                                // remove ex
                                clean();

                                // Quick update
                                iframe.find(evt.target).trigger("mouseover");

                            }

                        } else {

                            if (is_content_selected() === false){

                                if (check_with_parents(element, "transform", "none", "!=") === true){
                                    body.addClass("yp-has-transform");
                                }

                                // Set selector as  body attr.
                                body.attr("data-clickable-select", selector);

                                set_draggable(element);

                                // RESIZE ELEMENTS
                                window.visualResizingType = 'width';
                                window.ResizeSelectedBorder = "right";
                                window.styleAttrBeforeChange = element.attr("style");

                                element_offset = element.offset();
                                window.elementOffsetLeft = element_offset.left;
                                window.elementOffsetRight = element_offset.right;

                                element.width(parseFloat(element.width() + 10));

                                if (window.elementOffsetLeft == element_offset.left && window.elementOffsetRight != element_offset.right) {
                                    window.ResizeSelectedBorder = "right";
                                } else if (window.elementOffsetLeft != element_offset.left && window.elementOffsetRight == element_offset.right && element.css("text-align") != 'center') {
                                    window.ResizeSelectedBorder = "left";
                                } else {
                                    window.ResizeSelectedBorder = "right";
                                }

                                if (isDefined(window.styleAttrBeforeChange)) {
                                    element.attr("style", window.styleAttrBeforeChange);
                                } else {
                                    element.removeAttr("style");
                                    window.styleAttrBeforeChange = null;
                                }

                                // element selected
                                body.addClass("yp-content-selected");

                                window.orginalHeight = parseFloat(element.css("height").replace(/px/g,''));
                                window.orginalWidth = parseFloat(element.css("width").replace(/px/g,''));

                                if(element.css("float") == 'right'){
                                    body.addClass("yp-element-float");
                                }else{
                                    body.removeClass("yp-element-float");
                                }

                                css_editor_toggle(true); // show if hide

                                // Disable focus style after clicked.
                                element.blur();

                                if(body.hasClass("yp-animate-manager-active")){
                                    animation_manager();
                                }

                                // Update the element informations.
                                if($(".advanced-info-box").css("display") == 'block' && $(".element-btn").hasClass("active")){
                                    update_design_information("element");
                                }

                            }

                        }

                    } else {

                        var hrefAttr = $(evt.target).attr("href");

                        // If has href
                        if (isDefined(hrefAttr)) {

                            if (evt.which == 1 || evt.which === undefined) {
                                evt.stopPropagation();
                                evt.preventDefault();
                            }

                            return false;

                        }

                    }

                    draw();
                    gui_update();

                }

            });

            
            /* ---------------------------------------------------- */
            /* Creating smart guides while resize & dragging        */
            /* ---------------------------------------------------- */
            function create_smart_guides(){

                if(body.hasClass("yp-smart-guide-disabled") || mainBody.hasClass("yp-has-transform")){
                    return false;
                }

                var maxWidth = 0;
                var maxWidthEl = null;
                var k = $(window).width();

                // Smart guides: START
                var Allelements = iframeBody.find(get_all_elements(":not(ul li)"));

                for (var i=0; i < Allelements.length; i++){ 

                    // Element
                    var el = $(Allelements[i]);
                    var otherWidth = el.outerWidth();


                    // 720 768 940 960 980 1030 1040 1170 1210 1268
                    if(otherWidth >= 720 && otherWidth <= 1268 && otherWidth < (k-80)){

                        if(otherWidth > maxWidth){
                            maxWidthEl = el;
                        }

                        maxWidth = Math.max(otherWidth, maxWidth);

                    }


                    if(el.parents(".yp-selected").length <= 0 && el.parents(".yp-selected-others").length <= 0 && el.css("display") != 'none' && el.css("opacity") != "0" && el.css("visibility") != 'hidden' && el.height() >= 10){ 
                            
                        var offset = el.offset();

                        // Getting sizes
                        var otherTop = Math.round(offset.top);
                        var otherLeft = Math.round(offset.left);
                        var otherHeight = Math.round(el.outerHeight());

                            // don't add "inner" same size elements.
                            if(iframeBody.find('[data-yp-top="'+otherTop+'"][data-yp-left="'+otherLeft+'"][data-yp-width="'+otherWidth+'"][data-yp-height="'+otherHeight+'"]').length <= 0){

                                // Saving for use on drag event.
                                // faster performance.
                                el.addClass("yp-smart-guide-elements")
                                .attr("data-yp-top",otherTop)
                                .attr("data-yp-left",otherLeft)
                                .attr("data-yp-top-round",yp_round(otherTop))
                                .attr("data-yp-bottom-round",yp_round(otherTop+otherHeight))
                                .attr("data-yp-left-round",yp_round(otherLeft))
                                .attr("data-yp-right-round",yp_round(otherLeft+otherWidth))
                                .attr("data-yp-width",otherWidth)
                                .attr("data-yp-height",otherHeight);
                            }

                        }

                }

                // Not adding on responsive mode.
                if(maxWidthEl !== null){

                    var Pleft = maxWidthEl.offset().left;

                    if(Pleft > 50){

                        var Pright = Pleft+maxWidth;

                        if(parseInt(Pleft) == parseInt(iframe.width()-Pright)){
                        
                            iframeBody.append("<div class='yp-page-border-left' style='left:"+Pleft+"px;'></div><div class='yp-page-border-right' style='left:"+Pright+"px;'></div>");

                        }

                    }

                }

                // Adding distance borders
                iframeBody.append("<div class='yp-x-distance-border'></div><div class='yp-y-distance-border'></div>");

            }


            /* ---------------------------------------------------- */
            /* Clean up smart guides                                */
            /* ---------------------------------------------------- */
            function clean_smart_guides(){

                iframeBody.find(".yp-page-border-left,.yp-page-border-right").remove();

                // Removing distance borders
                iframeBody.find(".yp-x-distance-border,.yp-y-distance-border,.yp-helper-tooltip").remove();

                iframeBody.find(".yp-smart-guide-elements").removeClass("yp-smart-guide-elements")
                    .removeAttr("data-yp-top")
                    .removeAttr("data-yp-left")
                    .removeAttr("data-yp-width")
                    .removeAttr("data-yp-top-round")
                    .removeAttr("data-yp-bottom-round")
                    .removeAttr("data-yp-left-round")
                    .removeAttr("data-yp-right-round")
                    .removeAttr("data-yp-height");

            }


            /* ---------------------------------------------------- */
            /* Resize Start : Width                                 */
            /* ---------------------------------------------------- */
            iframe.on("mousedown", '.yp-selected-boxed-left,.yp-selected-boxed-right', function(event) {

            var element = $(this);

            // if float not right, left disable
            if(body.hasClass("yp-element-float") == false && element.hasClass("yp-selected-boxed-left")){
                return false;
            }

            body.addClass("resize-time-delay");

            clearTimeout(window.resizeDelay);
            window.resizeDelay = setTimeout(function(){

                if (is_content_selected() === false) {
                    return false;
                }

                window.visualResizingType = 'width';

                if (element.hasClass("yp-selected-boxed-left")) {
                    window.ResizeSelectedBorder = "left";
                } else {
                    window.ResizeSelectedBorder = "right";
                }

                window.mouseisDown = true;

                var el = iframeBody.find(".yp-selected");

                window.mouseDownX = el.offset().left;
                window.exWidthX = parseFloat(el.css("width"));
                window.exWidthY = null;
                window.currentMarginLeft = parseFloat(el.css("marginLeft"));

                window.maxData = {width: parseFloat(el.css("maxWidth")), height: parseFloat(el.css("maxHeight"))};
                window.minData = {width: parseFloat(el.css("minWidth")), height: parseFloat(el.css("minHeight"))};


                // Try to use % Percent format
                var widthPercent = calcature_smart_sizes(get_selected_element(),get_selected_element().css('width'));
                window.liveResizeWPercent = false;
                if(widthPercent.format == '%'){
                    window.liveResizeWPercent = true;
                }

                // Get saved sata from CSS editor.
                if(widthPercent.format != '%' && get_data_value(get_current_selector(),'width',true) == true){

                    var data = get_data_value(get_current_selector(),'width',false);
                    
                    if(data.indexOf("%") != -1){
                        window.liveResizeWPercent = true;
                    }

                }

                iframe.find("html").addClass("yp-element-resizing");
                body.addClass("yp-element-resizing yp-clean-look");

                // Close contextmenu
                if (iframe.find(".context-menu-active").length > 0) {
                    get_selected_element().contextMenu("hide");
                }

                // show size tooltip
                iframeBody.append("<div class='yp-helper-tooltip'></div>");

                create_smart_guides();

            },150);

            });


            /* ---------------------------------------------------- */
            /* Resize Start : Height                                */
            /* ---------------------------------------------------- */
            iframe.on("mousedown", '.yp-selected-boxed-bottom', function(event) { // removed since 5.5.6 .yp-selected-boxed-top

            var element = $(this);

            body.addClass("resize-time-delay");

            clearTimeout(window.resizeDelay);
            window.resizeDelay = setTimeout(function(){

                if (is_content_selected() === false) {
                    return false;
                }

                // Update variables
                window.mouseisDown = true;

                window.visualResizingType = 'height';
                
                if (element.hasClass("yp-selected-boxed-top")) {
                    window.ResizeSelectedBorder = "top";
                } else {
                    window.ResizeSelectedBorder = "bottom";
                }

                var el = iframeBody.find(".yp-selected");

                window.mouseDownY = el.offset().top;
                window.exWidthY = parseFloat(el.css("height"));
                window.exWidthX = null;
                window.currentMarginTop = parseFloat(el.css("marginTop"));

                window.maxData = {width: parseFloat(el.css("maxWidth")), height: parseFloat(el.css("maxHeight"))};
                window.minData = {width: parseFloat(el.css("minWidth")), height: parseFloat(el.css("minHeight"))};

                body.addClass("yp-element-resizing yp-clean-look");

                // Close contextmenu
                if (iframe.find(".context-menu-active").length > 0) {
                    get_selected_element().contextMenu("hide");
                }

                // Removing classes.
                iframe.find(get_current_selector()).removeClass("yp_selected yp_onscreen yp_hover yp_focus yp_click");

                // show size tooltip
                iframeBody.append("<div class='yp-helper-tooltip'></div>");

                create_smart_guides();

            },150);

            });


            /* ---------------------------------------------------- */
            /* Resize: Resizing                                     */
            /* ---------------------------------------------------- */
            iframe.on("mousemove", iframe, function(event) {

                // Record mousemoves after element selected.
                window.lastTarget = event.target;

                if (window.mouseisDown === true) {

                    var yBorder = iframeBody.find(".yp-y-distance-border");
                    var xBorder = iframeBody.find(".yp-x-distance-border");

                    event = event || window.event;

                    // cache
                    var element = get_selected_element();

                    var elof = element.offset();

                    // Convert display inline to inline-block.
                    if (element.css("display") == 'inline') {
                        insert_rule(null, "display", "inline-block", "");
                    }

                    var format = 'px';

                    if(window.liveResizeWPercent == true){
                        format = '%';
                    }

                    var width,smartData,height,dif;

                    // If width
                    if (window.visualResizingType == "width") {

                        if (window.ResizeSelectedBorder == 'left'){
                            width = Math.round(elof.left) + Math.round(element.outerWidth()) - Math.round(event.pageX);
                        } else {
                            width = Math.round(event.pageX) - Math.round(elof.left);
                        }
                        

                        // Min 4px
                        if ((format == 'px' && width > 4) || (format == '%' && width > 2)) {

                            if (element.css("boxSizing") == 'content-box') {
                                width = width - Math.round(parseFloat(element.css("paddingLeft"))) - Math.round(parseFloat(element.css("paddingRight")));
                            }

                            // calcature smart sizes. 100% etc
                            smartData = calcature_smart_sizes(element,width);

                            // Update
                            width = smartData.val;
                            format = smartData.format;

                            if(window.wasLockX === false){
                                if (window.ResizeSelectedBorder == 'left'){
                                    dif = Math.round(event.pageX)-Math.round(window.mouseDownX)+window.currentMarginLeft;
                                    element.cssImportant("margin-left", dif + "px");
                                }

                                element.cssImportant("width", width + format);

                            }

                            draw_box(".yp-selected", 'yp-selected-boxed');

                        }

                        body.addClass("yp-element-resizing-width-" + window.ResizeSelectedBorder);

                    } else if (window.visualResizingType == "height") { // else height

                        if (window.ResizeSelectedBorder == 'top') {
                            height = Math.round(elof.top+element.outerHeight()) - Math.round(event.pageY);
                        } else {
                            height = Math.round(event.pageY) - Math.round(elof.top);
                        }

                        // Min 4px
                        if (format == 'px' && height > 4){

                            if (element.css("boxSizing") == 'content-box') {
                                height = height - Math.round(parseFloat(element.css("paddingTop"))) - Math.round(parseFloat(element.css("paddingBottom")));
                            }

                            if(window.wasLockY === false){
                                if (window.ResizeSelectedBorder == 'top'){
                                    dif = Math.round(event.pageY)-Math.round(window.mouseDownY)+window.currentMarginTop;
                                    element.cssImportant("margin-top", dif + "px");
                                }
                                element.cssImportant("height", height + format);
                            }

                            draw_box(".yp-selected", 'yp-selected-boxed');

                        }

                        body.addClass("yp-element-resizing-height-" + window.ResizeSelectedBorder);

                    }

                    var tooltipContent = '';
                    var roundedNum = 0;

                    // Update helper tooltip
                    if(window.visualResizingType == "width"){
                        if(width < 5 && format == 'px'){width = 5;}else if(width < 2){width = 2;}
                        if(format == '%'){roundedNum = Math.round(width * 10) / 10;}else{roundedNum = Math.round(width);}
                        tooltipContent = 'W : '+roundedNum + format;
                    }else{
                        if(height < 5){height = 5;}
                        roundedNum = Math.round(height);
                        tooltipContent = 'H : '+roundedNum + format;
                    }


                    // Show : initial at tooltip when resize at default value
                    if(window.visualResizingType == "height"){
                        if(parseInt(window.orginalHeight) == parseInt(height)){
                            tooltipContent = 'H : ' + "initial - " + window.orginalHeight + "px";
                        }
                    }else{
                        if(parseInt(window.orginalWidth) == parseInt(width)){
                            tooltipContent = 'W : ' + "initial - " + window.orginalWidth + "px";
                        }
                    }


                    // offsets
                    var selfTop = Math.round(parseFloat(iframeBody.find(".yp-selected-boxed-top").css("top")));
                    var selfLeft = Math.round(parseFloat(iframeBody.find(".yp-selected-boxed-left").css("left")));
                    var selfRight = Math.round(parseFloat(iframeBody.find(".yp-selected-boxed-right").css("left")));
                    var selfBottom = Math.round(parseFloat(iframeBody.find(".yp-selected-boxed-bottom").css("top")));

                    // Variables
                    var wLeft,wWidth,wTop,forceH,wHeight,forceW,otherTop,otherLeft,otherWidth,otherHeight,otherBottom,otherRight;

                    // Create smart guides for height.
                    if(window.visualResizingType == "height"){

                        xBorder.css("display","none");
                        window.wasLockY = false;

                        var axsisxEl = iframeBody.find(".yp-smart-guide-elements[data-yp-top-round='"+yp_round(event.pageY)+"'],.yp-smart-guide-elements[data-yp-bottom-round='"+yp_round(event.pageY)+"']").first();

                        if(axsisxEl.length > 0){

                            // Getting sizes
                            otherTop = parseFloat(axsisxEl.attr("data-yp-top"));
                            otherLeft = parseFloat(axsisxEl.attr("data-yp-left"));
                            otherWidth = parseFloat(axsisxEl.attr("data-yp-width"));
                            otherHeight = parseFloat(axsisxEl.attr("data-yp-height"));
                            otherBottom = parseFloat(otherTop+otherHeight);
                            otherRight = parseFloat(otherLeft+otherWidth);

                            // Calculate smart guides positions.
                            if(selfLeft > otherLeft){
                                wLeft = otherLeft;
                                wWidth = selfRight-wLeft;
                            }else{
                                wLeft = selfLeft;
                                wWidth = otherRight-selfLeft;
                            }

                            // Find top or bottom.
                            if(axsisxEl.attr("data-yp-top-round") == yp_round(event.pageY)){
                                wTop = otherTop;
                                forceH = otherTop-selfTop;
                            }else{
                                wTop = otherBottom;
                                forceH = otherBottom-selfTop;
                            }

                            if(window.ResizeSelectedBorder != 'top'){
                                element.cssImportant("height", forceH + format);
                                window.wasLockY = true;
                            }else{
                                forceH = height;
                            }

                            xBorder.css({'top':wTop,'left':wLeft,'width':wWidth,'display':'block'});

                            if(forceH < 5){forceH = 5;}
                            roundedNum = Math.round(forceH);

                            tooltipContent = 'H : '+roundedNum + format;

                        }

                        // Show : initial at tooltip when resize at default value
                        if(parseInt(window.orginalHeight) == parseInt(forceH)){
                            tooltipContent = 'H : ' + "initial - " + window.orginalHeight + "px";
                        }

                    }

                    // Create smart guides for width.
                    if(window.visualResizingType == "width"){

                        window.wasLockX = false;
                        yBorder.css("display","none");

                        var axsisyEl = iframeBody.find(".yp-smart-guide-elements[data-yp-left-round='"+yp_round(event.pageX)+"'],.yp-smart-guide-elements[data-yp-right-round='"+yp_round(event.pageX)+"']").first();

                        if(axsisyEl.length > 0){

                            // Getting sizes
                            otherTop = parseFloat(axsisyEl.attr("data-yp-top"));
                            otherLeft = parseFloat(axsisyEl.attr("data-yp-left"));
                            otherWidth = parseFloat(axsisyEl.attr("data-yp-width"));
                            otherHeight = parseFloat(axsisyEl.attr("data-yp-height"));
                            otherBottom = parseFloat(otherTop+otherHeight);
                            otherRight = parseFloat(otherLeft+otherWidth);

                            // Calculate smart guides positions.
                            if(selfTop > otherTop){
                                wTop = otherTop;
                                wHeight = selfBottom-otherTop;
                            }else{
                                wTop = selfTop;
                                wHeight = otherBottom-selfTop;
                            }

                            // Find top or bottom.
                            if(axsisyEl.attr("data-yp-left-round") == yp_round(event.pageX)){
                                wLeft = otherLeft;
                                forceW = otherLeft-selfLeft;
                            }else{
                                wLeft = otherRight;
                                forceW = otherRight-selfLeft;
                            }


                            // calcature smart sizes. 100% etc
                            smartData = calcature_smart_sizes(element,forceW);

                            // Update
                            forceW = smartData.val;
                            format = smartData.format;


                            if(window.ResizeSelectedBorder != 'left'){
                                element.cssImportant("width", forceW + format);
                                window.wasLockX = true;
                            }else{
                                forceW = width;
                            }

                            yBorder.css({'top':wTop,'left':wLeft,'height':wHeight,'display':'block'});

                            if(format == '%'){
                                if(forceW < 2){forceW = 2;}
                                roundedNum = Math.round(forceW * 10) / 10;
                            }else{
                                if(forceW < 5){forceW = 5;}
                                roundedNum = Math.round(forceW);
                            }
                            tooltipContent = 'W : '+roundedNum + format;

                        }

                        // Show : initial at tooltip when resize at default value
                        if(parseInt(window.orginalWidth) == parseInt(forceW)){
                            tooltipContent = 'W : ' + "initial - " + window.orginalWidth + "px";
                        }

                    }

                    var leftX = event.pageX + 30;
                    if(leftX + 120 >= $("#iframe").width()){
                        leftX = event.pageX - 120;
                    }

                    // Update helper tooltip
                    iframeBody.find(".yp-helper-tooltip").css({'top':event.pageY,'left':leftX}).html(tooltipContent);


                }

            });


            /* ---------------------------------------------------- */
            /* Calcature Smart Sizes 100%, 100vh etc                */
            /* ---------------------------------------------------- */
            function calcature_smart_sizes(element,val){

                // Variable
                var result = [];

                var founded = false;

                // Check parent details.
                if(element.parent().length > 0){

                    // IF not any inline or table display
                    if(element.parent().css("display").indexOf("table") == -1 && element.parent().css("display") != 'inline' && element.parent().css("display") != 'inline-flex'){

                        var parentWidth = element.parent().width();

                        // if start width percent, use automatic percent all time while resizing.
                        if(window.liveResizeWPercent == true){

                            // Flag
                            founded = true;

                            // Update
                            result.val = Math.round((parseFloat(val)*100/parseFloat(parentWidth)) * 10 ) / 10;
                            result.format = '%';

                            
                        }

                        // if width is same with parent width, so set 100%!
                        if (parseInt(parentWidth) == parseInt(val) && founded == false) {

                            // Flag
                            founded = true;

                            // Update
                            result.val = 100;
                            result.format = '%';

                        }

                        // if width is 50% with parent width, so set 50%!
                        if (parseInt(parentWidth/2) == parseInt(val) && founded == false) {

                            // Flag
                            founded = true;

                            // Update
                            result.val = 50;
                            result.format = '%';

                        }

                        // if width is 25% with parent width, so set 25%!
                        if (parseInt(parentWidth/4) == parseInt(val) && founded == false) {

                            // Flag
                            founded = true;

                            // Update
                            result.val = 25;
                            result.format = '%';

                        }

                        // if width is 20% with parent width, so set 20%!
                        if (parseInt(parentWidth/5) == parseInt(val) && founded == false) {

                            // Flag
                            founded = true;

                            // Update
                            result.val = 20;
                            result.format = '%';

                        }

                    }

                }

                // Return default
                if(founded === false){
                    result.val = val;
                    result.format = 'px';
                }

                return result;

            }


            /* ---------------------------------------------------- */
            /* Resize: End                                          */
            /* ---------------------------------------------------- */
            iframe.on("mouseup", iframe, function() {

                clearTimeout(window.resizeDelay);

                if (is_resizing()) {

                    clean_smart_guides();

                    // show size tooltip
                    iframeBody.find(".yp-helper-tooltip").remove();

                    body.addClass("yp-element-resized");

                    var delay = 1;

                    // CSS To Data.
                    if (mainBody.hasClass("yp-need-to-process")) {
                        process(false, false);
                        delay = 70;
                    }

                    // Wait for process.
                    setTimeout(function() {

                        var bWidth;
                        if(window.visualResizingType == 'width'){
                            bWidth = window.exWidthX;
                        }else{
                            bWidth = window.exWidthY;
                        }

                        // cache
                        var element = get_selected_element();

                        // get result
                        var width = parseFloat(element.css(window.visualResizingType)).toString();
                        var format = 'px';
                        var widthCa = width;

                        // width 100% for screen
                        if (window.visualResizingType == 'width') {
                            
                            // calcature smart sizes. 100% etc
                            var smartData = calcature_smart_sizes(element,width);

                            // Update
                            width = smartData.val;
                            format = smartData.format;

                        }

                        if(window.exWidthX !== null && window.ResizeSelectedBorder == 'left' && widthCa != bWidth){
                            insert_rule(null,"margin-left",parseFloat(element.css("marginLeft")),'px');
                        }

                        if(window.exWidthY !== null && window.ResizeSelectedBorder == 'top' && widthCa != bWidth){
                            insert_rule(null,"margin-top",parseFloat(element.css("marginTop")),'px');
                        }

                        //return to default
                        if (isDefined(window.styleAttrBeforeChange)) {
                            element.attr("style", window.styleAttrBeforeChange);
                        } else {
                            element.removeAttr("style");
                        }

                        // insert to data.
                        if(widthCa != bWidth){

                            // Set just min height if new value higher than old
                            if(window.visualResizingType == 'height' && widthCa > window.orginalHeight){

                                var exStyle = iframe.find("." + get_id(get_current_selector()) + '-' + "height" + '-style[data-size-mode="' + get_media_condition() + '"]');
                                if (exStyle.length > 0) {exStyle.remove();}

                                insert_rule(null, "min-height", width, format);

                            }else{

                                insert_rule(null, window.visualResizingType, width, format);

                            }

                        }

                        iframe.find("html").removeClass("yp-element-resizing");
                        body.removeClass("yp-element-resizing yp-clean-look yp-element-resizing-height-bottom yp-element-resizing-width-left yp-element-resizing-width-right yp-element-resizing-height-top");


                        // If width/height large than max width/height
                        if(window.maxData[window.visualResizingType] < width){
                            insert_rule(null, "max-"+window.visualResizingType, width, format);
                        }

                        // If width large than max width/height
                        if(window.minData[window.visualResizingType] > width){
                            insert_rule(null, "min-"+window.visualResizingType, width, format);
                        }

                        draw();

                        // Update
                        option_change();

                        // Set default values for top and left options.
                        $.each(['width','height','max-width','max-height','min-width','min-height','margin-left','margin-top'], function(i, v) {
                            set_default_value(v);
                        });
                        
                        window.mouseisDown = false;
                        window.liveResizeWPercent = false;

                        draw();

                    }, delay);

                    setTimeout(function() {
                        body.removeClass("yp-element-resized resize-time-delay");
                    }, 100);

                }

            });


            /* ---------------------------------------------------- */
            /* Rounding numbers                                     */
            /* ---------------------------------------------------- */
            function yp_round(x){
                return Math.round(x / 6) * 6;
            }


            window.visualEdit = false;
            window.visualEditDelay = null;

            /* ---------------------------------------------------- */
            /* Visual Editing : Start                               */
            /* ---------------------------------------------------- */
            iframe.on("mousedown", '.yp-selected-boxed-margin-left,.yp-selected-boxed-margin-right,.yp-selected-boxed-margin-top,.yp-selected-boxed-margin-bottom,.yp-selected-boxed-padding-left,.yp-selected-boxed-padding-right,.yp-selected-boxed-padding-top,.yp-selected-boxed-padding-bottom', function(event) {

            if(event.which == 2 || event.which == 3){
                return false;
            }

            // margin/padding viewer element
            var element = $(this);

            clearTimeout(window.visualEditDelay);

            window.visualEditDelay = setTimeout(function(){

                if (is_content_selected() === false) {
                    return false;
                }

                // margin/padding viewer element class
                var classes = element.attr("class").replace(/yp-recent-hover-element/g,'').trim();

                element.addClass("yp-visual-active").removeClass("yp-zero-margin-w yp-zero-margin-h");

                // Margin || Padding
                window.visualEditType = classes.match(/boxed-[a-z]+/g).toString().replace("boxed-","");

                // Top, left, right, bottom
                window.visualEditPosition = classes.match(/boxed-(margin|padding)-[a-z]+/g).toString().replace(/boxed-|margin|padding|-/g,"");

                // continue on mousemove event
                window.visualEdit = true;

                // Cache mouse position on mousedown
                window.visualEditX = Math.round(event.pageX);
                window.visualEditY = Math.round(event.pageY);

                // ex margin-top
                var rule = window.visualEditType + "-" + window.visualEditPosition;

                // CSS property
                window.visualEditValue = get_selected_element().css(rule);

                // Cache original data
                window.visualEditValueOr = window.visualEditValue;

                // Default 5
                if(isUndefined(window.visualEditValue)){
                    window.visualEditValue = 5;

                // Int
                }else{
                    window.visualEditValue = parseInt(window.visualEditValue);
                }

                // Add class
                body.addClass("yp-visual-editing yp-clean-look");

                // X and Y
                if(/(left|right)/g.test(window.visualEditPosition)){
                    body.addClass("yp-visual-editing-x");
                }else{
                    body.addClass("yp-visual-editing-y");
                }

                // Use outline for performance
                body.addClass("yp-has-transform");

                window.currentLiveSelector = get_live_selector();

            },150);

            });



            /* ---------------------------------------------------- */
            /* Visual Editing : Editing                             */
            /* ---------------------------------------------------- */
            iframe.on("mousemove", iframe, function(event) {

                if(window.visualEdit){

                    var dif,rule,style,format;

                    // Dif
                    if(/(left|right)/g.test(window.visualEditPosition)){

                        if(window.visualEditType == 'padding'){

                            // Negative
                            if(/left/g.test(window.visualEditPosition)){
                                dif = Math.round(event.pageX) - window.visualEditX;
                            }else{
                                dif = window.visualEditX - Math.round(event.pageX);
                            }

                        }else{

                            dif = Math.round(event.pageX) - window.visualEditX;

                        }

                        format = 'width';

                    }else{
                        
                        dif = Math.round(event.pageY) - window.visualEditY;

                        format = 'height';

                    }

                    // All in
                    dif = dif + window.visualEditValue;

                    // min 0
                    if(dif < 0){
                        dif = 0;
                    }

                    // CSS Rule
                    rule = window.visualEditType + "-" + window.visualEditPosition;

                    style = '';


                    // variables
                    var selectedElement = get_selected_element();
                    var elementOffset = selectedElement.offset();
                    var elementLeft = elementOffset.left;
                    var elementTop = elementOffset.top;
                    var elementWidth = selectedElement.outerWidth(false);
                    var elementHeight = selectedElement.outerHeight(false);

                    var elementRight = parseFloat(elementLeft + elementWidth);
                    var elementBottom = parseFloat(elementTop+elementHeight);

                    // Update Margin Right
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-right'){
                        style += ".yp-selected-boxed-margin-right{ top: "+elementTop+"px !important; height:"+elementHeight+"px !important;left:"+elementRight+"px !important; }";
                    }

                    // Update Padding Right
                    if(window.visualEditType + "-" + window.visualEditPosition == 'padding-right'){
                        style += ".yp-selected-boxed-padding-right{ top: "+elementTop+"px !important; height:"+elementHeight+"px !important;left:"+(elementRight-dif)+"px !important; }";
                    }

                    // Update Margin Left
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-left'){
                        style += ".yp-selected-boxed-margin-left{ top: "+elementTop+"px !important; height:"+elementHeight+"px !important;left:"+(elementLeft-dif)+"px !important; }";
                    }

                    // Update Padding Left
                    if(window.visualEditType + "-" + window.visualEditPosition == 'padding-left'){
                        style += ".yp-selected-boxed-padding-left{ top: "+elementTop+"px !important; height:"+elementHeight+"px !important;left:"+elementLeft+"px !important; }";
                    }

                    // Need marginLeft and MarginRight value for margin top and bottom
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-top' || window.visualEditType + "-" + window.visualEditPosition == 'margin-bottom'){
                        var marginLeft = parseFloat(selectedElement.css("margin-left"));
                        var marginRight = parseFloat(selectedElement.css("margin-right"));
                        var outlineWidth = parseFloat(elementWidth) + marginLeft + marginRight;
                        var marginOutLeft = parseFloat(elementLeft) - marginLeft;
                    }

                    // Update Margin Top
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-top'){
                        style += ".yp-selected-boxed-margin-top{ top: "+(elementTop-dif)+"px !important; width:"+outlineWidth+"px !important;left:"+marginOutLeft+"px !important; }";
                    }

                    // Update Padding Top
                    if(window.visualEditType + "-" + window.visualEditPosition == 'padding-top'){
                        style += ".yp-selected-boxed-padding-top{ top: "+elementTop+"px !important; width:"+elementWidth+"px !important;left:"+elementLeft+"px !important; }";
                    }

                    // Update Margin Bottom
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-bottom'){
                        style += ".yp-selected-boxed-margin-bottom{ top: "+elementBottom+"px !important; width:"+outlineWidth+"px !important;left:"+marginOutLeft+"px !important; }";
                    }

                    // Update Padding Bottom
                    if(window.visualEditType + "-" + window.visualEditPosition == 'padding-bottom'){
                        style += ".yp-selected-boxed-padding-bottom{ top: "+(elementBottom-dif)+"px !important; width:"+elementWidth+"px !important;left:"+elementLeft+"px !important; }";
                    }


                    // Int only.
                    dif = parseInt(dif);


                    // Ex .yp-selected-boxed-padding-top:15px;
                    style += ".yp-selected-boxed-" + window.visualEditType + "-" + window.visualEditPosition + "{ " + format + " : " + dif + "px !important; }";

                    // Set the new value to the element
                    style += "body.yp-content-selected .yp-selected," + window.currentLiveSelector + "{ " + rule + " : " + dif + "px !important; }";

                    // Add & Update the live CSS
                    if(iframe.find("#yp-visual-edit-css").length == 0){

                        // Add
                        iframeBody.append("<style id='yp-visual-edit-css'>" + style + "</style>");

                    }else{

                        // Update
                        iframe.find("#yp-visual-edit-css").html(style);

                    }

                    // Show PX
                    iframe.find(".yp-selected-boxed-"+window.visualEditType+"-"+window.visualEditPosition+"").html(dif+"px");

                }

            });


            /* ---------------------------------------------------- */
            /* Visual Editing : End                                 */
            /* ---------------------------------------------------- */
            iframe.on("mouseup", iframe, function() {

                if(window.visualEdit){

                    window.visualEdit = false;

                    var delay = 1;

                    // CSS To Data.
                    if (mainBody.hasClass("yp-need-to-process")) {
                        process(false, false);
                        delay = 70;
                    }

                    // Add
                    body.addClass("yp-visual-edited");

                    // Wait for process
                    setTimeout(function(){

                        // Remove
                        body.removeClass("yp-visual-editing yp-visual-editing-x yp-visual-editing-y yp-clean-look yp-has-transform");

                        // CSS Remove
                        iframe.find("#yp-visual-edit-css").remove();

                        // Element
                        var element = iframe.find(".yp-selected-boxed-"+window.visualEditType+"-"+window.visualEditPosition+"");

                        // Val
                        var value = element.text();

                        // Clean HTML
                        if(parseInt(value) <= 30){
                            element.html("");
                        }

                        // Insert CSS if data not same
                        if(window.visualEditValueOr != value){

                            // Insert CSS
                            insert_rule(null, window.visualEditType + "-" + window.visualEditPosition, value);

                            // Update
                            option_change();

                            // Set default values for current property options.
                            set_default_value(window.visualEditType+"-"+window.visualEditPosition);

                            gui_update();

                        }

                    }, delay);

                    setTimeout(function(){

                        // remove active class
                        iframe.find(".yp-visual-active").removeClass("yp-visual-active");

                        // Update
                        body.removeClass("yp-visual-edited");

                        // Draw
                        draw();

                    }, (delay+100));

                }

            });


            /* ---------------------------------------------------- */
            /* Doesn't getting styles while element hover           */
            /* because the editor must load only non-hover styles   */
            /* ---------------------------------------------------- */
            body.on('mousedown', '.yp-editor-list > li:not(.yp-li-footer):not(.yp-li-about):not(.active)', function() {

                if (is_content_selected() === true) {

                    // Get data
                    var data = $(this).attr("data-loaded");

                    // If no data, so set.
                    if (typeof data == typeof undefined || data === false) {

                        // Set default values
                        $(this).find(".yp-option-group").each(function() {
                            set_default_value(get_option_id(this));
                        });

                        // cache to loaded data.
                        $(this).attr("data-loaded", "true");

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* ReDraw the element if hover                          */
            /* ---------------------------------------------------- */
            iframe.on("mouseout mouseover", '.yp-selected', function() {

                if (is_content_selected() == true && is_resizing() == false && is_dragging() == false && is_visual_editing() == false) {

                    clearTimeout(window.update_drawmouseOver);
                    window.update_drawmouseOver = setTimeout(function() {
                        draw();
                    }, 50);

                }

            });


            /* ---------------------------------------------------- */
            /* Getting All ideal elements. Used by smart guides.    */
            /* ---------------------------------------------------- */
            function get_all_elements(custom){

                var selector = '*';

                var notSelectors = [
                    ".yp-x-distance-border",
                    ".yp-y-distance-border",
                    ".hover-info-box",
                    ".yp-size-handle",
                    ".yp-edit-tooltip",
                    ".yp-edit-menu",
                    ".yp-selected-tooltip",
                    ".yp-tooltip-small",
                    ".yp-helper-tooltip",
                    "[class^='yp-selected-boxed-']",
                    "[class^='yp-selected-others-box']",
                    "link",
                    "style",
                    "script",
                    "param",
                    "option",
                    "tr",
                    "td",
                    "th",
                    "thead",
                    "tbody",
                    "tfoot",
                    "iframe",
                    "noscript",
                    "scene-1",
                    "scene-2",
                    "scene-3",
                    "scene-4",
                    "scene-5",
                    "scene-6",
                    "yp-anim-scenes",
                    "animate-test-drive"
                ];

                // Get classes added by editor
                var pluginClasses = window.plugin_classes_list.split("|");

                for(var x = 0; x < pluginClasses.length; x++){
                    pluginClasses[x] = "." + pluginClasses[x];
                }

                // concat
                notSelectors = notSelectors.concat(pluginClasses);

                // Adding not selectors
                for(var i = 0; i < notSelectors.length; i++){
                    selector += ":not("+notSelectors[i]+")";
                }

                // parement
                if(custom !== undefined){
                    selector += custom;
                }

                // Visible filter
                selector += ":visible";

                return selector;

            }


            /* ---------------------------------------------------- */
            /* None / Disable Buttons API                           */
            /* ---------------------------------------------------- */
            $(".yp-btn-action").click(function(e) {

                var elementPP = $(this).parent().parent().parent();

                var id = get_option_id(elementPP);

                var value,prefix;

                // inherit, none etc.
                if ($(this).hasClass("yp-none-btn")) {

                    if (elementPP.find(".yp-disable-btn.active").length >= 0) {
                        elementPP.find(".yp-disable-btn.active").trigger("click");

                        if (e.originalEvent) {
                            elementPP.addClass("reset-enable");
                        }

                    }

                    value = '';
                    prefix = '';

                    // If slider
                    if (elementPP.hasClass("yp-slider-option")) {

                        if ($(this).hasClass("active")) {

                            $(this).removeClass("active");

                            // Show
                            elementPP.find(".yp-after").show();

                            // Is Enable
                            elementPP.find(".yp-after-disable-disable").hide();

                            // Value
                            value = $("#yp-" + id).val();
                            prefix = $("#" + id + "-after").val();

                        } else {

                            $(this).addClass("active");

                            // Hide
                            elementPP.find(".yp-after").hide();

                            // Is Disable
                            elementPP.find(".yp-after-disable-disable").show();

                            // Value
                            value = elementPP.find(".yp-none-btn").text();

                        }

                        // If is radio
                    } else if (elementPP.find(".yp-radio-content").length > 0) {

                        if ($(this).hasClass("active")) {

                            $(this).removeClass("active");

                            // Value
                            value = $("input[name=" + id + "]:checked").val();

                            $("input[name=" + id + "]:checked").parent().addClass("active");

                        } else {

                            $(this).addClass("active");

                            elementPP.find(".yp-radio.active").removeClass("active");

                            // Value
                            value = elementPP.find(".yp-none-btn").text();

                        }

                        // If is select
                    } else if (elementPP.find("select").length > 0) {
                        

                        if ($(this).hasClass("active")) {

                            $(this).removeClass("active");

                            // Value
                            value = $("#yp-" + id).val();

                        } else {

                            $(this).addClass("active");

                            // Value
                            value = elementPP.find(".yp-none-btn").text();

                        }

                    } else {

                        if ($(this).hasClass("active")) {

                            $(this).removeClass("active");

                            // Value
                            value = $("#yp-" + id).val();

                            elementPP.find(".wqminicolors-swatch-color").css("backgroundColor",value);

                        } else {

                            $(this).addClass("active");

                            elementPP.find(".wqminicolors-swatch-color").css("backgroundColor","transparent");

                            // Value
                            value = 'transparent';

                        }

                    }

                    if (id == 'background-image') {

                        if (value.indexOf("//") != -1 && value.indexOf("linear-gradient(") == -1) {
                            value = "url(" + value + ")";
                        }

                        if (value == 'transparent') {
                            value = 'none';
                        }

                    }

                    if (id == 'list-style-image') {

                        if (value.indexOf("//") != -1) {
                            value = "url(" + value + ")";
                        }

                        if (value == 'transparent') {
                            value = 'none';
                        }

                    }

                    if (e.originalEvent) {

                        insert_rule(null, id, value, prefix);
                        option_change();

                    }

                } else { // disable this option

                    value = '';
                    prefix = '';

                    // If slider
                    if (elementPP.hasClass("yp-slider-option")) {

                        if ($(this).hasClass("active")) {

                            $(this).removeClass("active");

                            // Value
                            if (!elementPP.find(".yp-none-btn").hasClass("active")) {
                                value = $("#yp-" + id).val();
                                prefix = $("#" + id + "-after").val();
                            } else {
                                value = elementPP.find(".yp-none-btn").text();
                            }

                        } else {

                            $(this).addClass("active");

                            // Value
                            value = 'disable';

                        }

                        // If is radio
                    } else if (elementPP.find(".yp-radio-content").length > 0) {

                        if ($(this).hasClass("active")) {

                            $(this).removeClass("active");

                            // Value
                            if (!elementPP.find(".yp-none-btn").hasClass("active")) {
                                value = $("input[name=" + id + "]:checked").val();
                            } else {
                                value = elementPP.find(".yp-none-btn").text();
                            }

                        } else {

                            $(this).addClass("active");

                            // Value
                            value = 'disable';

                        }

                        // If is select
                    } else if (elementPP.find("select").length > 0) {

                        if ($(this).hasClass("active")) {

                            $(this).removeClass("active");

                            // Value
                            if (!elementPP.find(".yp-none-btn").hasClass("active")) {
                                value = $("#yp-" + id).val();
                            } else {
                                value = elementPP.find(".yp-none-btn").text();
                            }

                        } else {

                            $(this).addClass("active");

                            // Value
                            value = 'disable';

                        }

                    } else {

                        if ($(this).hasClass("active")) {

                            $(this).removeClass("active");

                            // Value
                            if (!elementPP.find(".yp-none-btn").hasClass("active")) {
                                value = $("#yp-" + id).val();
                            } else {
                                value = elementPP.find(".yp-none-btn").text();
                            }

                        } else {

                            $(this).addClass("active");

                            // Value
                            value = 'disable';

                        }

                        if (id == 'background-image' && value.indexOf("linear-gradient(") == -1) {

                            if (value.indexOf("//") != -1) {
                                value = "url(" + value + ")";
                            }

                            if (value == 'transparent') {
                                value = 'none';
                            }

                        }

                        if (id == 'list-style-image') {

                            if (value.indexOf("//") != -1) {
                                value = "url(" + value + ")";
                            }

                            if (value == 'transparent') {
                                value = 'none';
                            }

                        }

                    }


                    if (e.originalEvent) {
                        insert_rule(null, id, "disable", prefix);
                        set_default_value(id);
                        elementPP.removeClass("reset-enable");
                    }

                    if (e.originalEvent) {
                        option_change();
                    }

                }

                // Update panel
                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Collapse List                                        */
            /* ---------------------------------------------------- */
            $(".yp-editor-list > li > h3").click(function() {

                var element = $(this);
                var elementParent = element.parent();

                if (elementParent.hasClass("yp-li-about") || elementParent.hasClass("yp-li-footer")) {
                    return '';
                }

                elementParent.addClass("current");

                // Disable.
                $(".yp-editor-list > li.active:not(.current)").each(function() {

                    $(".yp-editor-list > li").show();
                    element.find(".yp-this-content").hide().parent().removeClass("active");

                    $(".lock-btn").removeClass("active");

                });

                if (elementParent.hasClass("active")) {
                    elementParent.removeClass("active");
                } else {
                    elementParent.addClass("active");
                    $(".yp-editor-list > li:not(.active)").hide();
                }

                elementParent.find(".yp-this-content").toggle();
                elementParent.removeClass("current");

                if ($(".yp-close-btn.dashicons-menu").length > 0) {
                    $(".yp-close-btn").removeClass("dashicons-menu").addClass("dashicons-no-alt");
                    $(".yp-close-btn").tooltip('hide').attr('data-original-title', l18_close_editor).tooltip('fixTitle');
                }

                if ($(".yp-editor-list > li.active:not(.yp-li-about):not(.yp-li-footer) > h3").length > 0) {
                    $(".yp-close-btn").removeClass("dashicons-no-alt").addClass("dashicons-menu");
                    $(".yp-close-btn").tooltip('hide').attr('data-original-title', l18_back_to_menu).tooltip('fixTitle');

                }

                $('.yp-editor-list').scrollTop(0);

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Filters                                              */
            /* ---------------------------------------------------- */
            function number_filter(a) {
                if (typeof a !== "undefined" && a != '') {
                    if (a.replace(/[^\d.-]/g, '') === null || a.replace(/[^\d.-]/g, '') === undefined) {
                        return 0;
                    } else {
                        return a.replace(/[^\d.-]/g, '');
                    }
                } else {
                    return 0;
                }
            }

            function alfa_filter(a) {
                if (typeof a !== "undefined" && a != '') {
                    return a.replace(/\d/g, '').replace(".px", "px");
                } else {
                    return '';
                }
            }

            var get_basic_id = function(str) {
                if (typeof str !== "undefined" && str != '') {
                    str = str.replace(/\W+/g, "");
                    return str;
                } else {
                    return '';
                }
            };

            function get_id(str) {
                if (typeof str !== "undefined" && str != '') {

                    // \^\#\+\$\(\)\[\]\=\*\-\:\.\>\,\~\@\/\! work in process. 
                    str = str.replace(/\:/g, "yp-sym-p")
                    .replace(/\^/g, "yp-sym-a")
                    .replace(/\#/g, "yp-sym-c")
                    .replace(/\+/g, "yp-sym-o")
                    .replace(/\$/g, "yp-sym-q")
                    .replace(/\(/g, "yp-sym-e")
                    .replace(/\)/g, "yp-sym-s")
                    .replace(/\[/g, "yp-sym-g")
                    .replace(/\]/g, "yp-sym-x")
                    .replace(/\=/g, "yp-sym-k")
                    .replace(/\*/g, "yp-sym-n")
                    .replace(/\-/g, "yp-sym-t")
                    .replace(/\./g, "yp-sym-u")
                    .replace(/\>/g, "yp-sym-l")
                    .replace(/\,/g, "yp-sym-b")
                    .replace(/\~/g, "yp-sym-m")
                    .replace(/\@/g, "yp-sym-i")
                    .replace(/\//g, "yp-sym-y")
                    .replace(/\!/g, "yp-sym-v")
                    .replace(/[^a-zA-Z0-9_\^\#\+\$\(\)\[\]\=\*\-\:\.\>\,\~\@\/\!]/g, "");
                    return str;
                } else {
                    return '';
                }
            }

            function array_cleaner(actual) {

                var uniqueArray = actual.filter(function(item, pos) {
                    return actual.indexOf(item) == pos;
                });

                return uniqueArray;

            }

            function uppercase_first_letter(str){
                return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
            }

            function letter_repeat(str) {
                var reg = /^([a-z])\1+$/;
                var d = reg.test(str);
                return d;
            }

            function title_case(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }


            /* ---------------------------------------------------- */
            /* Getting selected element name                        */
            /* ---------------------------------------------------- */
            function get_tag_information(selectors){

                var selectorsArray = selectors.split(",");

                // If is one selector
                if(selectorsArray.length == 1){
                    return get_single_tag_information(selectors);
                }


                // Multi Selectors
                var allTagNames = [];
                var name = '';

                // Get all tag names by selectors
                for(var i = 0; i < selectorsArray.length; i++){

                    // Get tag name
                    name = get_single_tag_information(selectorsArray[i]);

                    // Push if the name not in name-list
                    if(allTagNames.indexOf(name) == -1){
                        allTagNames.push(name);
                    }

                }

                return allTagNames.toString().replace(/\,/g,", ");

            }


            /* ---------------------------------------------------- */
            /* Need it while processing stylesheet selectors        */
            /* ---------------------------------------------------- */
            function get_foundable_query(selector,css,body,animation){

                if(css === true){

                    // Hover Focus active visited link
                    selector = selector.replace(/:hover/g,'').replace(/:focus/g,'').replace(/:active/g,'').replace(/:visited/g,'').replace(/:link/g,'');

                    // After
                    selector = selector.replace(/:after/g,'').replace(/::after/g,'');

                    // Before
                    selector = selector.replace(/:before/g,'').replace(/::before/g,'');

                    // First Letter
                    selector = selector.replace(/:first-letter/g,'').replace(/::first-letter/g,'');

                    // First Line
                    selector = selector.replace(/:first-line/g,'').replace(/::first-line/g,'');

                    // Selection
                    selector = selector.replace(/:selection/g,'').replace(/::selection/g,'');

                }

                if(body === true){

                    // YP Selector Hover
                    selector = selector.replace(/body\.yp-selector-hover/g,'').replace(/\.yp-selector-hover/g,'');

                    // YP Selector Focus
                    selector = selector.replace(/body\.yp-selector-focus/g,'').replace(/\.yp-selector-focus/g,'');

                    // YP Selector active
                    selector = selector.replace(/body\.yp-selector-active/g,'').replace(/\.yp-selector-active/g,'');

                    // YP Selector visited
                    selector = selector.replace(/body\.yp-selector-visited/g,'').replace(/\.yp-selector-visited/g,'');

                    // YP Selector link
                    selector = selector.replace(/body\.yp-selector-link/g,'').replace(/\.yp-selector-link/g,'');

                }

                if(animation === true){

                    // YP Animations
                    selector = selector.replace(/.yp_onscreen/g,'').replace(/.yp_focus/g,'').replace(/.yp_hover/g,'').replace(/.yp_click/g,'');

                }

                return selector.trim();

            }


            /* ---------------------------------------------------- */
            /* Cleans multiple spaces                               */
            /* ---------------------------------------------------- */
            function space_cleaner(data){
                return $.trim(data.replace(/\s\s+/g,' '));
            }


            /* ---------------------------------------------------- */
            /* Simple Defined Element Names                         */
            /* ---------------------------------------------------- */
            function get_single_tag_information(selector){

                selector = get_foundable_query(selector,true,true,true);

                if(iframe.find(selector).length <= 0){
                    return;
                }

                var PPname,Pname;

                // tagName
                var a = iframe.find(selector)[0].nodeName;

                // length
                var length = get_selector_array(selector).length - 1;

                // Names
                var n = get_selector_array(selector)[length].toUpperCase();
                if (n.indexOf(".") != -1){
                    n = n.split(".")[1].replace(/[^\w\s]/gi, '');
                }

                // Class Names
                var className = $.trim(get_selector_array(selector)[length]);
                if (className.indexOf(".") != -1) {
                    className = className.split(".")[1];
                }

                // ID
                var id = get_selected_element().attr("id");

                if (isDefined(id)) {
                    id = id.toUpperCase().replace(/[^\w\s]/gi, '');
                }

                // Parents 1
                if (length > 1) {
                    Pname = get_selector_array(selector)[length - 1].toUpperCase();
                    if (Pname.indexOf(".") != -1) {
                        Pname = Pname.split(".")[1].replace(/[^\w\s]/gi, '');
                    }
                } else {
                    Pname = '';
                }

                // Parents 2
                if (length > 2) {
                    PPname = get_selector_array(selector)[length - 2].toUpperCase();
                    if (PPname.indexOf(".") != -1) {
                        PPname = PPname.split(".")[1].replace(/[^\w\s]/gi, '');
                    }
                } else {
                    PPname = '';
                }

                // ID
                if (id == 'TOPBAR') {
                    return l18_topbar;
                } else if (id == 'HEADER') {
                    return l18_header;
                } else if (id == 'FOOTER') {
                    return l18_footer;
                } else if (id == 'CONTENT') {
                    return l18_content;
                }

                // Parrents Class
                if (PPname == 'LOGO' || PPname == 'SITETITLE' || Pname == 'LOGO' || Pname == 'SITETITLE') {
                    return l18_logo;
                } else if (n == 'MAPCANVAS') {
                    return l18_google_map;
                } else if (Pname == 'ENTRYTITLE' && a == 'A') {
                    return l18_entry_title_link;
                } else if (Pname == 'CATLINKS' && a == 'A') {
                    return l18_category_link;
                } else if (Pname == 'TAGSLINKS' && a == 'A') {
                    return l18_tag_link;
                }

                // Current Classes
                if (n == 'WIDGET') {
                    return l18_widget;
                } else if (n == 'FA' || get_selector_array(selector)[length].toUpperCase().indexOf("FA-") >= 0) {
                    return l18_font_awesome_icon;
                } else if (n == 'SUBMIT' && a == 'INPUT') {
                    return l18_submit_button;
                } else if (n == 'MENUITEM') {
                    return l18_menu_item;
                } else if (n == 'ENTRYMETA' || n == 'ENTRYMETABOX' || n == 'POSTMETABOX') {
                    return l18_post_meta_division;
                } else if (n == 'COMMENTREPLYTITLE') {
                    return l18_comment_reply_title;
                } else if (n == 'LOGGEDINAS') {
                    return l18_login_info;
                } else if (n == 'FORMALLOWEDTAGS') {
                    return l18_allowed_tags;
                } else if (n == 'LOGO') {
                    return l18_logo;
                } else if (n == 'ENTRYTITLE' || n == 'POSTTITLE') {
                    return l18_post_title;
                } else if (n == 'COMMENTFORM') {
                    return l18_comment_form;
                } else if (n == 'WIDGETTITLE') {
                    return l18_widget_title;
                } else if (n == 'TAGCLOUD') {
                    return l18_tag_cloud;
                } else if (n == 'ROW' || n == 'VCROW') {
                    return l18_row;
                } else if (n == 'BUTTON') {
                    return l18_button;
                } else if (n == 'BTN') {
                    return l18_button;
                } else if (n == 'LEAD') {
                    return l18_lead;
                } else if (n == 'WELL') {
                    return l18_well;
                } else if (n == 'ACCORDIONTOGGLE') {
                    return l18_accordion_toggle;
                } else if (n == 'PANELBODY') {
                    return l18_accordion_content;
                } else if (n == 'ALERT') {
                    return l18_alert_division;
                } else if (n == 'FOOTERCONTENT') {
                    return l18_footer_content;
                } else if (n == 'GLOBALSECTION' || n == 'VCSSECTION') {
                    return l18_global_section;
                } else if (n == 'MORELINK') {
                    return l18_show_more_link;
                } else if (n == 'CONTAINER' || n == 'WRAPPER') {
                    return l18_wrapper;
                } else if (n == 'DEFAULTTITLE') {
                    return l18_article_title;
                } else if (n == 'MENULINK' || n == 'MENUICON' || n == 'MENUBTN' || n == 'MENUBUTTON') {
                    return l18_menu_link;
                } else if (n == 'SUBMENU') {
                    return l18_submenu;

                    // Bootstrap Columns
                } else if (n.indexOf('COLMD12') != -1 || n == 'MEDIUM12' || n == 'LARGE12' || n == 'SMALL12') {
                    return l18_column + ' 12/12';
                } else if (n.indexOf('COLMD11') != -1 || n == 'MEDIUM11' || n == 'LARGE11' || n == 'SMALL11') {
                    return l18_column + ' 11/12';
                } else if (n.indexOf('COLMD10') != -1 || n == 'MEDIUM10' || n == 'LARGE10' || n == 'SMALL10') {
                    return l18_column + ' 10/12';
                } else if (n.indexOf('COLMD9') != -1 || n == 'MEDIUM9' || n == 'LARGE9' || n == 'SMALL9') {
                    return l18_column + ' 9/12';
                } else if (n.indexOf('COLMD8') != -1 || n == 'MEDIUM8' || n == 'LARGE8' || n == 'SMALL8') {
                    return l18_column + ' 8/12';
                } else if (n.indexOf('COLMD7') != -1 || n == 'MEDIUM7' || n == 'LARGE7' || n == 'SMALL7') {
                    return l18_column + ' 7/12';
                } else if (n.indexOf('COLMD6') != -1 || n == 'MEDIUM6' || n == 'LARGE6' || n == 'SMALL6') {
                    return l18_column + ' 6/12';
                } else if (n.indexOf('COLMD5') != -1 || n == 'MEDIUM5' || n == 'LARGE5' || n == 'SMALL5') {
                    return l18_column + ' 5/12';
                } else if (n.indexOf('COLMD4') != -1 || n == 'MEDIUM4' || n == 'LARGE4' || n == 'SMALL4') {
                    return l18_column + ' 4/12';
                } else if (n.indexOf('COLMD3') != -1 || n == 'MEDIUM3' || n == 'LARGE3' || n == 'SMALL3') {
                    return l18_column + ' 3/12';
                } else if (n.indexOf('COLMD2') != -1 || n == 'MEDIUM2' || n == 'LARGE2' || n == 'SMALL2') {
                    return l18_column + ' 2/12';
                } else if (n.indexOf('COLMD1') != -1 || n == 'MEDIUM1' || n == 'LARGE1' || n == 'SMALL1') {
                    return l18_column + ' 1/12';
                } else if (n.indexOf('COLXS12') != -1) {
                    return l18_column + ' 12/12';
                } else if (n.indexOf('COLXS11') != -1) {
                    return l18_column + ' 11/12';
                } else if (n.indexOf('COLXS10') != -1) {
                    return l18_column + ' 10/12';
                } else if (n.indexOf('COLXS9') != -1) {
                    return l18_column + ' 9/12';
                } else if (n.indexOf('COLXS8') != -1) {
                    return l18_column + ' 8/12';
                } else if (n.indexOf('COLXS7') != -1) {
                    return l18_column + ' 7/12';
                } else if (n.indexOf('COLXS6') != -1) {
                    return l18_column + ' 6/12';
                } else if (n.indexOf('COLXS5') != -1) {
                    return l18_column + ' 5/12';
                } else if (n.indexOf('COLXS4') != -1) {
                    return l18_column + ' 4/12';
                } else if (n.indexOf('COLXS3') != -1) {
                    return l18_column + ' 3/12';
                } else if (n.indexOf('COLXS2') != -1) {
                    return l18_column + ' 2/12';
                } else if (n.indexOf('COLXS1') != -1) {
                    return l18_column + ' 1/12';
                } else if (n.indexOf('COLSM12') != -1) {
                    return l18_column + ' 12/12';
                } else if (n.indexOf('COLSM11') != -1) {
                    return l18_column + ' 11/12';
                } else if (n.indexOf('COLSM10') != -1) {
                    return l18_column + ' 10/12';
                } else if (n.indexOf('COLSM9') != -1) {
                    return l18_column + ' 9/12';
                } else if (n.indexOf('COLSM8') != -1) {
                    return l18_column + ' 8/12';
                } else if (n.indexOf('COLSM7') != -1) {
                    return l18_column + ' 7/12';
                } else if (n.indexOf('COLSM6') != -1) {
                    return l18_column + ' 6/12';
                } else if (n.indexOf('COLSM5') != -1) {
                    return l18_column + ' 5/12';
                } else if (n.indexOf('COLSM4') != -1) {
                    return l18_column + ' 4/12';
                } else if (n.indexOf('COLSM3') != -1) {
                    return l18_column + ' 3/12';
                } else if (n.indexOf('COLSM2') != -1) {
                    return l18_column + ' 2/12';
                } else if (n.indexOf('COLSM1') != -1) {
                    return l18_column + ' 1/12';
                } else if (n.indexOf('COLLG12') != -1) {
                    return l18_column + ' 12/12';
                } else if (n.indexOf('COLLG11') != -1) {
                    return l18_column + ' 11/12';
                } else if (n.indexOf('COLLG10') != -1) {
                    return l18_column + ' 10/12';
                } else if (n.indexOf('COLLG9') != -1) {
                    return l18_column + ' 9/12';
                } else if (n.indexOf('COLLG8') != -1) {
                    return l18_column + ' 8/12';
                } else if (n.indexOf('COLLG7') != -1) {
                    return l18_column + ' 7/12';
                } else if (n.indexOf('COLLG6') != -1) {
                    return l18_column + ' 6/12';
                } else if (n.indexOf('COLLG5') != -1) {
                    return l18_column + ' 5/12';
                } else if (n.indexOf('COLLG4') != -1) {
                    return l18_column + ' 4/12';
                } else if (n.indexOf('COLLG3') != -1) {
                    return l18_column + ' 3/12';
                } else if (n.indexOf('COLLG2') != -1) {
                    return l18_column + ' 2/12';
                } else if (n.indexOf('COLLG1') != -1) {
                    return l18_column + ' 1/12';
                } else if (n == 'POSTBODY') {
                    return l18_post_division;
                } else if (n == 'POST') {
                    return l18_post_division;
                } else if (n == 'CONTENT' || n == 'DEFAULTCONTENT') {
                    return l18_content_division;
                } else if (n == 'ENTRYTITLE') {
                    return l18_entry_title;
                } else if (n == 'ENTRYCONTENT') {
                    return l18_entry_content;
                } else if (n == 'ENTRYFOOTER') {
                    return l18_entry_footer;
                } else if (n == 'ENTRYHEADER') {
                    return l18_entry_header;
                } else if (n == 'ENTRYTIME') {
                    return l18_entry_time;
                } else if (n == 'POSTEDITLINK') {
                    return l18_post_edit_link;
                } else if (n == 'POSTTHUMBNAIL') {
                    return l18_post_thumbnail;
                } else if (n == 'THUMBNAIL') {
                    return l18_thumbnail;
                } else if (n.indexOf("ATTACHMENT") >= 0) {
                    return l18_thumbnail_image;
                } else if (n == 'EDITLINK') {
                    return l18_edit_link;
                } else if (n == 'COMMENTSLINK') {
                    return l18_comments_link_division;
                } else if (n == 'SITEDESCRIPTION') {
                    return l18_site_description;
                } else if (n == 'POSTCLEAR' || n == 'POSTBREAK') {
                    return l18_post_break;
                }

                // Smart For ID
                if (get_name_by_classes(id) !== false) {
                    return get_name_by_classes(id);
                }

                // Smart For Class
                if (get_name_by_classes(className) !== false) {
                    return get_name_by_classes(className);
                }

                // If not have name found, use clear.
                if (n.indexOf("CLEARFIX") != -1 || n.indexOf("CLEARBOTH") != -1 || n == "CLEAR") {
                    return l18_clear;
                }

                // TAG NAME START
                if (a == 'P') {
                    return l18_paragraph;
                } else if (a == 'BR') {
                    return l18_line_break;
                } else if (a == 'HR') {
                    return l18_horizontal_rule;
                } else if (a == 'A') {
                    return l18_link;
                } else if (a == 'LI') {
                    return l18_list_item;
                } else if (a == 'UL') {
                    return l18_unorganized_list;
                } else if (a == 'OL') {
                    return l18_unorganized_list;
                } else if (a == 'IMG') {
                    return l18_image;
                } else if (a == 'B') {
                    return l18_bold_tag;
                } else if (a == 'I') {
                    return l18_italic_tag;
                } else if (a == 'STRONG') {
                    return l18_strong_tag;
                } else if (a == 'Em') {
                    return l18_italic_tag;
                } else if (a == 'BLOCKQUOTE') {
                    return l18_blockquote;
                } else if (a == 'PRE') {
                    return l18_preformatted;
                } else if (a == 'TABLE') {
                    return l18_table;
                } else if (a == 'TR') {
                    return l18_table_row;
                } else if (a == 'TD') {
                    return l18_table_data;
                } else if (a == 'HEADER' || n == 'HEADER') {
                    return l18_header_division;
                } else if (a == 'FOOTER' || n == 'FOOTER') {
                    return l18_footer_division;
                } else if (a == 'SECTION' || n == 'SECTION') {
                    return l18_section;
                } else if (a == 'FORM') {
                    return l18_form_division;
                } else if (a == 'BUTTON') {
                    return l18_button;
                } else if (a == 'CENTER') {
                    return l18_centred_block;
                } else if (a == 'DL') {
                    return l18_definition_list;
                } else if (a == 'DT') {
                    return l18_definition_term;
                } else if (a == 'DD') {
                    return l18_definition_description;
                } else if (a == 'H1') {
                    return l18_header + ' (' + l18_level + ' 1)';
                } else if (a == 'H2') {
                    return l18_header + ' (' + l18_level + ' 2)';
                } else if (a == 'H3') {
                    return l18_header + ' (' + l18_level + ' 3)';
                } else if (a == 'H4') {
                    return l18_header + ' (' + l18_level + ' 4)';
                } else if (a == 'H5') {
                    return l18_header + ' (' + l18_level + ' 5)';
                } else if (a == 'H6') {
                    return l18_header + ' (' + l18_level + ' 6)';
                } else if (a == 'SMALL') {
                    return l18_smaller_text;
                } else if (a == 'TEXTAREA') {
                    return l18_text_area;
                } else if (a == 'TBODY') {
                    return l18_body_of_table;
                } else if (a == 'THEAD') {
                    return l18_head_of_table;
                } else if (a == 'TFOOT') {
                    return l18_foot_of_table;
                } else if (a == 'U') {
                    return l18_underline_text;
                } else if (a == 'SPAN') {
                    return l18_span;
                } else if (a == 'Q') {
                    return l18_quotation;
                } else if (a == 'CITE') {
                    return l18_citation;
                } else if (a == 'CODE') {
                    return l18_expract_of_code;
                } else if (a == 'NAV' || n == 'NAVIGATION' || n == 'NAVIGATIONCONTENT') {
                    return l18_navigation;
                } else if (a == 'LABEL') {
                    return l18_label;
                } else if (a == 'TIME') {
                    return l18_time;
                } else if (a == 'DIV') {
                    return l18_division;
                } else if (a == 'CAPTION') {
                    return l18_caption_of_table;
                } else if (a == 'INPUT') {
                    return l18_input;
                } else {
                    return a.toLowerCase();
                }

            }

            
            /* ---------------------------------------------------- */
            /* Reading nice class names                             */
            /* ---------------------------------------------------- */
            function get_name_by_classes(className) {

                if (typeof className == typeof undefined || className === false) {
                    return false;
                }

                // RegExp
                var upperCase = new RegExp('[A-Z]');
                var numbers = new RegExp('[0-9]');
                var bottomRex = /_/;
                var topRex = /-/;

                // Only - or _
                if (bottomRex.test(className) && topRex.test(className)) {
                    return false;
                }

                // max 3 -
                if (topRex.test(className)) {
                    if (className.match(/-/g).length >= 3) {
                        return false;
                    }
                }

                // max 3 _
                if (bottomRex.test(className)) {
                    if (className.match(/_/g).length >= 3) {
                        return false;
                    }
                }

                // Clean
                className = className.replace(/_/g, ' ').replace(/-/g, ' ');

                var classNames = get_classes_array(className);

                var i = 0;
                for (i = 0; i < classNames.length; i++) {
                    if (classNames[i].length < 4 || classNames[i].length > 12) {
                        return false;
                    }
                }

                // if all lowerCase
                // if not any number
                // if minimum 3 and max 20
                if (className.match(upperCase) || className.match(numbers) || className.length < 5 || className.length > 20) {
                    return false;
                }

                if (letter_repeat(className)) {
                    return false;
                }

                // For id.
                className = className.replace("#", "");

                return title_case(className);

            }


            /* ---------------------------------------------------- */
            /* Disable jQuery Plugins. // Parallax.                 */
            /* ---------------------------------------------------- */
            $("#yp-background-parallax .yp-radio").click(function() {

                var v = $(this).find("input").val();

                if (v == 'disable') {
                    iframe.find(get_current_selector()).addClass("yp-parallax-disabled");
                } else {
                    iframe.find(get_current_selector()).removeClass("yp-parallax-disabled");
                }

            });

            

            /* ---------------------------------------------------- */
            /* Update save button                                   */
            /* ---------------------------------------------------- */
            function option_change(){

                clearTimeout(window.yp_insert_data_delay);

                mainBody.addClass("yp-history-delay");

                if(window.option_changeType != 'auto'){
                    $(".yp-save-btn").html(l18_save).removeClass("yp-disabled").addClass("waiting-for-save");
                }

                window.yp_insert_data_delay = setTimeout(function() {

                    var data = get_clean_css(true);

                    // Call CSS Engine.
                    $(document).CallCSSEngine(data);

                    editor.setValue(data);

                    mainBody.removeClass("yp-history-delay");

                }, 200);

                // Update undo/redo icons
                setTimeout(function(){
                    check_undoable_history();
                },220);

                // Update breakpoints if responsive mode
                if(is_responsive_mod){
                    update_responsive_breakpoints();
                }

                // Update box model in design information box if visible
                if($(".info-btn.active").length > 0){
                    update_box_model();
                }

            }

            
            /* ---------------------------------------------------- */
            /* Process all and get a clean CSS                      */
            /* ---------------------------------------------------- */
            function process(close, id, type) {

                // close css editor with process..
                if (close === true) {

                    iframe.find(".yp-styles-area style[data-rule='a']").remove();

                    $("#cssData,#cssEditorBar,#leftAreaEditor").hide();
                    iframeBody.trigger("scroll");
                    mainBody.removeClass("yp-css-editor-active");

                    $(".css-editor-btn").attr("data-original-title",$(".css-editor-btn").attr("data-title"));

                    // Update All.
                    draw();

                }

                // IF not need to process, stop here.
                if (mainBody.hasClass("yp-need-to-process") === false || mainBody.hasClass("yp-processing-now")) {
                    return false;
                }

                // Remove class.
                body.removeClass("yp-need-to-process");

                // Processing.
                if (body.find(".yp-processing").length === 0) {
                    body.addClass("yp-processing-now");
                    body.append("<div class='yp-processing'><span></span><p>" + l18_process + "</p></div>");
                } else {
                    body.addClass("yp-processing-now");
                }

                if (editor.getValue().length > 800) {
                    body.find(".yp-processing").show();
                }

                setTimeout(function() {

                    css_to_data('desktop');

                    if (editor.getValue().toString().indexOf("@media") != -1) {

                        var mediaTotal = editor.getValue().toString().replace(/(\r\n|\n|\r)/g, "").match(/@media(.*?){/g);

                        // Search medias and convert to Yellow Pencil Data
                        $.each(mediaTotal, function(index, value) {

                            // make .min the media content
                            value = get_minimized_css(value,false);

                            css_to_data(value);

                        });

                    }

                    iframe.find("#yp-css-data-full").remove();

                    // Added from css_to_data function. must remove.
                    body.removeClass("process-by-code-editor");

                    setTimeout(function() {
                        body.removeClass("yp-processing-now");
                        body.find(".yp-processing").hide();

                        var oldData = editor.session.getUndoManager();
                        editor.setValue(get_clean_css(true));
                        editor.session.setUndoManager(oldData);

                    }, 5);

                    // Save
                    if (id !== false) {

                        var posting = $.post(ajaxurl, {
                            action: "yp_ajax_save",
                            yp_id: id,
                            yp_stype: type,
                            yp_data: get_clean_css(true),
                            yp_editor_data: get_editor_data()
                        });

                        $.post(ajaxurl, {

                                action: "yp_preview_data_save",
                                yp_data: data

                            });

                        // Done.
                        posting.complete(function(data) {
                            $(".yp-save-btn").html(l18_saved).addClass("yp-disabled").removeClass("waiting-for-save");
                        });

                    }

                    if(body.hasClass("yp-animate-manager-active")){
                        animation_manager();
                    }

                }, 50);

            }

            
            /* ---------------------------------------------------- */
            /* RGB To hex                                           */
            /* ---------------------------------------------------- */
            function get_color(rgb) {
                if (typeof rgb !== 'undefined') {

                    if(rgb.indexOf("rgba") != -1){
                        return rgb.replace(/\s+/g,"");
                    }

                    rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);

                    return (rgb && rgb.length === 4) ? "#" + ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) + ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) + ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';

                } else {
                    return '';
                }
            }


            /* ---------------------------------------------------- */
            /* Hex To RGB                                           */
            /* ---------------------------------------------------- */
            function hex_to_rgb(hex){

                var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
                hex = hex.replace(shorthandRegex, function(m, r, g, b) {
                    return r + r + g + g + b + b;
                });

                var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
                
                if(result){
                    return 'rgb('+parseInt(result[1], 16)+','+parseInt(result[2], 16)+','+parseInt(result[3], 16)+')';
                }else{
                    return null;
                }
              
            }


            // Long to short sorted for replacement.
            window.plugin_classes_list_sorted = window.plugin_classes_list.split("|").sort(function(a, b){return b.length - a.length;}).join("|");


            /* ---------------------------------------------------- */
            /* Clean all classes which added by the plugin.         */
            /* ---------------------------------------------------- */
            function class_cleaner(data) {

                if (isUndefined(data)) {
                    return '';
                }

                return data.replace(new RegExp(window.plugin_classes_list_sorted,"gi"), '');
                
            }


            /* ---------------------------------------------------- */
            /* Clear all animation timers                           */
            /* ---------------------------------------------------- */
            function clear_animation_timer(){

                clearTimeout(window.animationTimer1);
                clearTimeout(window.animationTimer2);
                clearTimeout(window.animationTimer3);
                clearTimeout(window.animationTimer4);

            }


            /* ---------------------------------------------------- */
            /* Stop the element animation.                          */
            /* ---------------------------------------------------- */
            function element_animation_end(){

                if(is_content_selected()){
                    get_selected_element().trigger("animationend webkitAnimationEnd oanimationend MSAnimationEnd");
                }

            }


            /* ---------------------------------------------------- */
            /* Adds class to body on a selector string.             */
            /* ---------------------------------------------------- */
            function add_class_to_body(selector, prefix) {

                var selectorOrginal = selector;

                // Basic
                if (selector == 'body') {
                    selector = selector + "." + prefix;
                }

                // If class added, return.
                if (selector.indexOf("body." + prefix) != -1) {
                    return selector;
                }

                var firstHTML = '';
                if (get_selector_array(selector).length > 0) {

                    var firstSelector = $.trim(get_selector_array(selector)[0]);

                    if (firstSelector.toLowerCase() == 'html') {
                        firstHTML = firstSelector;
                    }

                    if (iframe.find(firstSelector).length > 0) {
                        if (firstSelector.indexOf("#") != -1) {
                            if (iframe.find(firstSelector)[0].nodeName == 'HTML') {
                                firstHTML = firstSelector;
                            }
                        }

                        if (firstSelector.indexOf(".") != -1) {
                            if (iframe.find(firstSelector)[0].nodeName == 'HTML') {
                                firstHTML = firstSelector;
                            }
                        }
                    }

                    if (firstHTML != '') {
                        selector = get_selector_array(selector)[1];
                    }

                }

                // find body tag
                selector = selector.replace(/\bbody\./g, 'body.' + prefix + ".");
                selector = selector.replace(' body ', ' body.' + prefix + " ");

                // If class added, return.
                if (selector.indexOf("body." + prefix) != -1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                // Get all body classes.
                if (iframeBody.attr("class") !== undefined && iframeBody.attr("class") !== null) {

                    // Find element
                    var element = iframe.find(selectorOrginal);

                    if(element.length > 0){

                        if(element[0].nodeName == 'BODY'){

                            var bodyClasses = get_classes_array(iframeBody.attr("class"));

                            // Adding to next to classes.
                            for (var i = 0; i < bodyClasses.length; i++) {
                                selector = selector.replace("." + bodyClasses[i] + " ", "." + bodyClasses[i] + "." + prefix + " ");

                                if (get_selector_array(selector).length == 1 && bodyClasses[i] == selector.replace(".", "")) {
                                    selector = selector + "." + prefix;
                                }

                            }

                        }

                    }

                }

                // If class added, return.
                if (selector.indexOf("." + prefix + " ") != -1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                // If class added, return.
                if (selector.indexOf("." + prefix) != -1 && get_selector_array(selector).length == 1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                // Get body id.
                var bodyID = iframeBody.attr("id");

                selector = selector.replace("#" + bodyID + " ", "#" + bodyID + "." + prefix + " ");

                // If class added, return.
                if (selector.indexOf("." + prefix + " ") != -1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                selector = "YPIREFIX" + selector;
                selector = selector.replace(/YPIREFIXbody /g, 'body.' + prefix + " ");
                selector = selector.replace("YPIREFIX", "");

                // If class added, return.
                if (selector.indexOf("body." + prefix + " ") != -1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                if (selector.indexOf(" body ") == -1 || selector.indexOf(" body.") == -1) {
                    selector = "body." + prefix + " " + selector;
                }

                if (firstHTML != '') {
                    selector = firstHTML + " " + selector;
                }

                return selector;

            }


            /* ---------------------------------------------------- */
            /* FullScreen.                                          */
            /* ---------------------------------------------------- */
            function toggle_fullscreen(elem) {
                // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
                if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
                    if (elem.requestFullScreen) {
                        elem.requestFullScreen();
                    } else if (elem.mozRequestFullScreen) {
                        elem.mozRequestFullScreen();
                    } else if (elem.webkitRequestFullScreen) {
                        elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    }
                    body.addClass("yp-fullscreen");
                    setTimeout(function(){draw_responsive_handle();},250);
                } else {
                    if (document.cancelFullScreen) {
                        document.cancelFullScreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitCancelFullScreen) {
                        document.webkitCancelFullScreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                    body.removeClass("yp-fullscreen");
                    setTimeout(function(){draw_responsive_handle();},250);
                }
            }


            /* ---------------------------------------------------- */
            /* FullScreen Event.                                    */
            /* ---------------------------------------------------- */
            $(document).bind('webkitfullscreenchange mozfullscreenchange fullscreenchange', function(e) {
                var state = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
                var event = state ? 'FullscreenOn' : 'FullscreenOff';

                if (event == 'FullscreenOff') {
                    $(".fullscreen-btn").removeClass("active");
                    body.removeClass("yp-fullscreen");
                }

                if (event == 'FullscreenOn') {
                    $(".fullscreen-btn").addClass("active");
                    body.addClass("yp-fullscreen");
                }

            });


            /* ---------------------------------------------------- */
            /* Disable history shift mouse.                         */
            /* ---------------------------------------------------- */
            mainDocument.keydown(function(e){

                var tag = e.target.tagName.toLowerCase();

                if(tag != 'input' && tag != 'textarea'){
                
                    if (e.shiftKey && (e.which == '61' || e.which == '107' || e.which == '173' || e.which == '109'  || e.which == '187'  || e.which == '189')){
                            e.preventDefault();
                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Disable shift + scroll event.                        */
            /* ---------------------------------------------------- */
            mainDocument.bind('mousewheel DOMMouseScroll', function (e) {
                if (e.shiftKey) {
                   e.preventDefault();
                }
            });


        }; // Yellow Pencil main function.

}(jQuery));