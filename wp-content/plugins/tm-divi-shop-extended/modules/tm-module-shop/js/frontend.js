
jQuery(document).ready(function($){


    var prev_post_title,prev_thumbnail,next_post_title,next_thumbnail;


    //Browser resize 
    $(window).resize(function() {
	    var height = $('.remodal').height();
   		$('#tm_contend .summary').css('height',height);
        var scrollable = document.getElementsByClassName('scrollable')[0];
        if(scrollable){
        	if ((scrollable.scrollHeight>scrollable.clientHeight) === true){
		    	$('.scrollbar_bg').css('height',height);
		    	$('.scrollbar_bg').show();
		    }else{
		    	$('.scrollbar_bg').hide();
		    }
        }
	   
   		
  
    });

    //remodel js open 
    $(document).on('opened','.remodal', function(){
    	$('body').css('overflow','hidden');
    	$('.spinner').remove();
    });

    //remodel js closed 
    $(document).on('closed','.remodal', function(){
    	$('body').css('overflow','auto');
    });

    //shop page button click 
    $(document).on('click', ".quick_view", function() {

        var product_id = $(this).data('product-id');
        tm_get_product_details(product_id);
        $(this).append('<div class="spinner"></div>')

    });

    //woocommerce gallery 
    
    $(document).on('click','#tm_contend .thumbnails a',function(e){

   		e.preventDefault();
   		var img_url = $(this).attr('href');
   		var img_src = $(this).find('img').attr('srcset');
 		$('.woocommerce-main-image').find('img').attr('src',img_url);
 		$('.woocommerce-main-image').find('img').attr('srcset',img_src);
 		$('.woocommerce-main-image').closest('a').attr('href',img_url);
 	
 	/*
 		$("a.zoom").prettyPhoto({
			hook: 'data-rel',
			social_tools: false,
			theme: 'pp_woocommerce',
			horizontal_padding: 20,
			opacity: 0.8,
			deeplinking: false
		});
		$("a[data-rel^='prettyPhoto']").prettyPhoto({
			hook: 'data-rel',
			social_tools: false,
			theme: 'pp_woocommerce',
			horizontal_padding: 20,
			opacity: 0.8,
			deeplinking: false
		});
	*/

 	});
 	
     
    //scrolling enable or not
    $(document).on('mouseenter', "#tm_contend .summary", function() {
        var scrollable = document.getElementsByClassName('scrollable')[0];
	    if ((scrollable.scrollHeight>scrollable.clientHeight) === true){
            var $scrollable = $('.scrollable'),
		    $scrollbar  = $('.scrollbar'),
		    H   = $scrollable.outerHeight(true),
		    sH  = $scrollable[0].scrollHeight,
		    sbH = H*H/sH;
		   
		    $scrollbar.height(sbH).hide();

            $scrollable.on("scroll", function(){

			    $scrollbar.css({top: $scrollable.scrollTop()/H*sbH });
			});
	    	$('.scrollbar').show();
	    }
    });

    $(document).on('mouseleave', "#tm_contend .summary", function(scrollable) {
        var scrollable = document.getElementsByClassName('scrollable')[0];
	    if ((scrollable.scrollHeight>scrollable.clientHeight) === true){

	    	$('.scrollbar').hide();
	    }
    });
    
 
	//hover previous button 
	$(document).on('mouseenter', ".tm_prev", function() {
        if($('.tm_prev_title').length === 0){
			$(this).append('<div class="tm_prev_title"><h4>'+prev_post_title+'</h4></div>');
			$(this).append('<div class="tm_prev_thumbnail"></div>');
			$('.tm_prev_thumbnail').html(prev_thumbnail);
			 
	    }   
	});

	$(document).on('mouseleave', ".tm_prev", function() {
        if($('.tm_prev_title').length !== 0){
	        $(this).removeClass('tm_prev_title');
			$('.tm_prev_title').remove();
			$('.tm_prev_thumbnail').remove();
			 
	    }   
	});

	$(document).on('click', ".tm_prev", function() {
	    
	    var product_id = $(this).data('data-prev-post');
		tm_get_product_details(product_id);   
	});

	//hover next button 
	$(document).on('mouseenter', ".tm_next", function() {
        if($('.tm_next_title').length === 0){
        	$(this).append('<div class="tm_next_thumbnail"></div>');
			$(this).append('<div class="tm_next_title"><h4>'+next_post_title+'</h4></div>');
			$('.tm_next_thumbnail').html(next_thumbnail);
			 
	    }   
	});
	$(document).on('mouseleave', ".tm_next", function() {
        if($('.tm_next_title').length !== 0){
	        $(this).removeClass('tm_next_title');
			$('.tm_next_title').remove();
			$('.tm_next_thumbnail').remove();
			 
	    }   
	});

	$(document).on('click', ".tm_next", function() {
	    
	    var product_id = $(this).data('data-next-post');
		tm_get_product_details(product_id);  
	});
 


function tm_get_product_details(product_id){

if(product_id !== undefined){

	        jQuery.ajax({
	        	type: 'POST',
			    url: tm_frontend_obj.ajaxurl, 
			    data :{
			        'action': 'tm_get_product',
			        'product_id':  product_id,
 
			    }, 
			    success:function(response){

			        $('#tm_contend').html(response);
			        $('#tm_contend .summary').addClass('scrollable');
			        $('.remodal').show();
                    
                    var prev_post_id     = $('.tm_prev_data').data('tm-prev-id');
                    var next_post_id     = $('.tm_next_data').data('tm-next-id');
                    prev_post_title      = $('.tm_prev_data').text();
                    next_post_title      = $('.tm_next_data').text();
                    var prev_src         = ($('.tm_prev_data>img').length !== 0)?$('.tm_prev_data>img').attr( 'src' ):'';
                    var nex_src          = ($('.tm_next_data>img').length !== 0)?$('.tm_next_data>img').attr( 'src' ):'';
                    prev_thumbnail       = '<img src = "'+ prev_src +'">';
                    next_thumbnail       = '<img src = "'+ nex_src+'">';
      
                    if(($('.tm_prev').length === 0) && (prev_post_id !== '')){

			         	$('.remodal-wrapper').prepend('<div class="tm_prev wrapper" data-prev-post='+prev_post_id+' style="display:block;left:0;"><div class="icon"></div></div>');             

                    }

                    if(($('.tm_next').length === 0) && (next_post_id !== '')){

			        	 $('.remodal-wrapper').prepend('<div class="tm_next wrapper" data-next-post='+next_post_id+' style="display:block;right:0;"><div class="icon"></div></div>');             

                    }

                    $('.tm_prev').data('data-prev-post',prev_post_id);
                    $('.tm_prev_title').html('<h4>'+prev_post_title+'</h4>');
                    $('.tm_prev_thumbnail').html(prev_thumbnail);

                    $('.tm_next').data('data-next-post',next_post_id);
                    $('.tm_next_title').html('<h4>'+next_post_title+'</h4>');
                    $('.tm_next_thumbnail').html(next_thumbnail);
 

                    if( prev_post_id === ''){
                        $('.tm_prev').remove();
                    }
                    if(next_post_id === ''){
                        $('.tm_next').remove();
                    }

                  //open modal 
			      var inst  = $('[data-remodal-id=modal]').remodal();
			      var state = inst.getState();
			      if(state == 'closed'){
			      	inst.open();
			      }


			        var height = $('.remodal').height();
			        $('#tm_contend .summary').css('height',height);

				    //sroll
				    var color = $('.remodal').css('background-color');

				    $('#tm_contend .scrollbar_bg').css('background',color);
				    $('#tm_contend .scrollbar_bg').html('<div class="scrollbar"></div>');
				  	var height = $('.remodal').height();
			   		$('.scrollbar_bg').css('height',height);
			   		var scrollable = document.getElementsByClassName('scrollable')[0];
	                if ((scrollable.scrollHeight>scrollable.clientHeight) === false){
			   			$('.scrollbar_bg').hide();
			   	    }
				    //end scroll 
			      
			    }
			});

	        
		}

}

 


});