/**
 *
 * Module Image Param
 *
 */
;(function ( $, window, document, undefined ) {
  	'use strict'; 
	$.fn.cp_image = function() { 

		/**
		 * Set default image 
		 */
		var img_src    = $(".cp-panel-field").find('.cp-edit-modal-data.media').val();
		var imagesource = img_src.split('|');
		
		if( imagesource[0] == '0' ) {
			img_src = cp_admin_ajax.assets_url + imagesource[1];
		} else {			
			//  Update Media Size on Initial 
			updateMediaSize();
			img_src = imagesource[1];
		}
		
		$(".cp-panel-field").find('.cp-media-container').prepend('<img src="'+ img_src +'" />');

		if( imagesource[0] == '0' ) { //if image is default image
			$(".cp-panel-field").find('.cp-media-sizes').addClass('cp-hide-field');	
		}

		//upload image on click of upload button
		$(".cp-panel-field").find('.cp-upload-media').click(function(e) {

			_wpPluploadSettings['defaults']['multipart_params']['admin_page']= 'customizer';
			var button = jQuery(this);
			var img_parent_wrap = button.closest('.cp-edit-panel-field');
			var id = 'cp_'+button.attr('id');
			var uid = button.data('uid');
			var img_container = button.attr('id')+'_container';

			//Extend wp.media object
			var uploader = wp.media.frames.file_frame = wp.media({
				title: 'Select or Upload Image',
				button: {
					text: 'Choose Image'
				},
				library: {
					type: 'image'
				},
				multiple: false,
			});

			uploader.on( 'select', function( props, attachment ){
				
				attachment = uploader.state().get('selection').first().toJSON();

				var media_sizes_select = img_parent_wrap.find('#cp_module_image_size');

				media_sizes_select.html('');
					
				if( typeof attachment.sizes !== 'undefined') {
					for( var size in attachment.sizes ) {

						var title = size.replace("-", "");
						title = title.toLowerCase().replace(/\b[a-z]/g, function(letter) {
								    return letter.toUpperCase();
								}) + ' - ';

						var img_title 	= title + attachment.sizes[size].width + ' x ' + attachment.sizes[size].height;

						media_sizes_select.append(jQuery("<option/>", {
							value: attachment.sizes[size].url,
							text: img_title,
						}));

						media_sizes_select.find( "option[value='" + attachment.sizes[size].url +   "']" ).data( "size", size );
					}
				}

				media_sizes_select.val( attachment.sizes['full'].url );

				var image_sizes = {};

				if( typeof attachment.sizes !== 'undefined') {
					jQuery.each( attachment.sizes, function(index, val) {
						image_sizes[index] = {
							"url": val.url, 
							"width": val.width,
							"height": val.height
						}
					});
				}

				var element_name = jQuery("#"+id).attr('for');
				var image_sizes_option = jQuery("#"+id).attr('name') + "_sizes";

				bmodel.setModalValue( element_name, step_id, image_sizes_option, image_sizes, false );

				img_parent_wrap.find('.cp-media-sizes').removeClass('cp-hide-field');

				var alt         = attachment.alt;
				var url 		= attachment.url;           
				var a 			= jQuery( "#" + id );
				var val 		= attachment.id + '|' + url + '|full|' + alt;
				var name 		= jQuery( "#" + id ).attr('name');
				
				a.val(val);
				jQuery(".cp-panel-field").find('.cp-custom-alt').val( alt );
				jQuery(".cp-media-" + uid ).attr( 'data-id',attachment.id );
				jQuery(".cp-media-" + uid ).attr( 'data-alt', attachment.alt );
				jQuery(".cp-panel-field").attr( 'data-alt', attachment.alt );
				jQuery(".cp-media-" + uid ).parents(".cp-media-sizes").removeClass("hide-for-default");

				jQuery("."+img_container).html('<img src="'+attachment.url+'"/>');

				button.text('Change Image');
				
				bmodel.setModalValue( element_name, step_id, 'width', 'auto');
				bmodel.setModalValue( element_name, step_id, 'height', 'auto' );
				
				ConvertProHelper._applySettings( element_name, 'width', 'auto', '', false, '', step_id );
				ConvertProHelper._applySettings( element_name, 'height', 'auto', '', false, '', step_id );

				jQuery("#"+id).trigger('change');

				/* Update Media Size on Image Change */
				//updateMediaSize();
			});

			uploader.open(button);
			return false;
		});

		// Replace default image 
		$(".cp-panel-field").find('.cp-default-media').on('click', function(e){

			e.preventDefault();
			
			var button 			= jQuery(this);
			var id 				= button.attr('id').replace("default_","cp_");
			// var upload 			= button.attr('id').replace("default_","");
			var img_container 	= button.attr('id').replace("default_","")+'_container';
			var container 		= jQuery(this).parents('.content');
			var default_img 	= jQuery(this).data('default');
			var defaultImg 		= default_img.split('|');
			var default_img		= defaultImg[1];
			var currentVal 		= jQuery("#"+id).val();
			var predefault_img 	= '0|' + default_img;
			var element         = $("#"+id).attr('for');

			jQuery("#"+id).val(predefault_img);

			var value 		= default_img; 	//	Empty the background image
			var name 		= jQuery("#"+id).attr('name');

			default_img = cp_admin_ajax.assets_url + default_img;

			jQuery("."+img_container).html('<img src="'+default_img+'"/>');

			jQuery("#"+id).trigger('change');

			container.find(".cp-media-sizes").hide().addClass('hide-for-default');
			$(".cp-panel-field").find('.cp-media-sizes').addClass('cp-hide-field');
			$('.cp-close-image,.cp-image').closest('.cp-field-html-data').removeClass('selected');
			$('.cp-close-image,.cp-image').closest('.cp-field-html-data').find(".ui-resizable-handle").removeClass('show');
			var isImg = $("#"+element).find('img').hasClass('cp-image');
			var isClose = $("#"+element).find('img').hasClass('cp-close-image');
			if(isImg){
				$("#"+element).find('img.cp-image').css({'height':'128px', 'width':'128px'});
			}
			if(isClose){
				$("#"+element).find('img.cp-close-image').css({'height':'32px', 'width':'32px'});	
			}
		});

		// Media size change function
		$(".cp-panel-field").find(".cp-media-size").on("change", function(e){

			var file_url 	= jQuery(this).find(':selected').val();
			var input 		= 'cp_' + jQuery(this).parents('.cp-media-sizes').data('name');
			var currentVal 	= jQuery( "#" + input ).val();

			var valTemp 	= currentVal.split('|');
			var alt    	    = typeof valTemp[3] != 'undefined' ? valTemp[3] : '';
			var size        = jQuery(this).find(":selected").data("size");
			var newVal 		= valTemp[0] + '|' + file_url + '|' + size + '|' + alt;

			jQuery(this).parents(".cp-panel-field").find('.cp-media-container img').attr( 'src', file_url );

			jQuery("#"+input).val(newVal);
			jQuery("#"+input).trigger('change');
		});
	}

	/**
	 *Replace sizes to cp_media_sizes
	 */
	function updateMediaSize() {
	
		$(".cp-panel-field").find('.cp-media-size').each(function( i ) {
			
			var $this = $(this);
			var img_src = $this.parents(".cp-panel-field").find('.cp-edit-modal-data.media').val();
			var img_arr = img_src.split('|');
			var img_id 	= 0;
			var img_url = '';
			var selected = '';
			var element_id = "cp_" + $this.data("image-name");
			var for_element_id = jQuery(".cp-edit-panel-field").find( "#" + element_id ).attr('for');
			var image_sizes_option = $this.data("image-name") + "_sizes";
			var image_sizes = bmodel.getModalValue( for_element_id, step_id, image_sizes_option );

			if( img_arr.length > 1 ){

				if ( img_arr[0] !== undefined && img_arr[0] != '' ) {
					img_id = img_arr[0];
				}

				if ( img_arr[1] !== undefined && img_arr[1] != '' ) {
					img_url = img_arr[1];
				}
			}

			$(".cp-panel-field").find('.cp-media-size').attr( 'data-id', img_id );

			$this.html('');
			var selected = false;

			if ( typeof image_sizes == 'string' && ConvertProHelper.isJsonString( image_sizes ) ) {
				image_sizes = JSON.parse( ConvertProHelper._decodeHtmlEntity( image_sizes ) );
			}
				
			if( typeof image_sizes !== 'undefined' && 'object' === typeof image_sizes ) {
				for( var size in image_sizes ) {

					var title = size.replace("-", "");
					title = title.toLowerCase().replace(/\b[a-z]/g, function(letter) {
							    return letter.toUpperCase();
							}) + ' - ';

					var img_title 	= title + image_sizes[size].width + ' x ' + image_sizes[size].height;

					if ( img_url == image_sizes[size].url ) {
						selected = 'selected';
					} else {
						selected = false;
					}

					$this.append(jQuery("<option/>", {
						value: image_sizes[size].url,
						text: img_title,
						selected: selected,
					}));
				}
			} else {

				$this.append(jQuery("<option/>", {
					value: img_url,
					text: 'full',
					selected: 'selected'
				}));
			}
		});
	}
})( jQuery, window, document );
