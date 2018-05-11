jQuery(document).ready(function(jQuery){
	jQuery('.cp-upload-media').click(function(e) {
		_wpPluploadSettings['defaults']['multipart_params']['admin_page']= 'customizer';
		var button 			= jQuery(this),
			btn_attr_id 	= button.attr('id'),
			id 				= 'cp_' + btn_attr_id,
			uid 			= button.data('uid'),
			img_container 	= btn_attr_id + '_container';

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
		
		uploader.on('select', function(props, attachment){

			attachment = uploader.state().get('selection').first().toJSON();

			var sizes_select 	= jQuery(".cp-media-"+uid).val(),
				alt 			= attachment.alt,
				val 			= attachment.id + '|' + attachment.url + "|full",
				cp_bg_image_id	= jQuery("#"+id),
				name			= cp_bg_image_id.attr('name'),			
				cp_media_select = jQuery(".cp-media-"+uid);

			cp_bg_image_id.val(val);
			cp_bg_image_id.attr('data-img-id',attachment.id);
			cp_bg_image_id.attr('data-img-url',attachment.url);
			
			cp_media_select.attr('data-id',attachment.id);
			cp_media_select.attr('data-alt',attachment.alt);
			cp_media_select.parents(".cp-media-sizes").removeClass("hide-for-default");
			jQuery("." + img_container ).html('<img src="'+attachment.url+'"/>');

			button.text('Change Image');

			var media_sizes_select = jQuery(".cp-media-"+uid).parents(".cp-media-sizes").find('.cp-media-size');	
			media_sizes_select.html('');
				
			if( typeof attachment.sizes !== 'undefined') {
				for( size in attachment.sizes ) {

					title = size.replace("-", "");
					title = title.toLowerCase().replace(/\b[a-z]/g, function(letter) {
							    return letter.toUpperCase();
							}) + ' - ';

					img_title 	= title + attachment.sizes[size].width + ' x ' + attachment.sizes[size].height;

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

			var image_sizes_option = name + '_sizes';
			var panel_id = "panel-" + ( step_id + 1 );

			bmodel.setModalValue( panel_id, step_id, image_sizes_option, image_sizes, false );

			cp_bg_image_id.trigger('change');
		});

		uploader.open(button);
		return false;
	});

	jQuery('.cp-default-media').on('click', function(e){

		e.preventDefault();
		
		var button 			= jQuery(this);
		var id 				= button.attr('id').replace("default_","cp_");
		var upload 			= button.attr('id').replace("default_","");
		var img_container 	= button.attr('id').replace("default_","")+'_container';
		var container 		= button.parents('.cp-panel-content');
		var default_img 	= button.data('default');
		var newvalue		= 0 + '|' + default_img;
		var cp_bg_image_id  = jQuery("#"+id);
		cp_bg_image_id.attr('value', newvalue);
		cp_bg_image_id.attr('data-img-id', 0);
		cp_bg_image_id.attr('data-img-url', default_img);

		//  Partial Refresh
        //  -   Apply background, background-color, color etc.
        //var cp_bg_image_id 	= jQuery("#"+id);
        var css_preview 	= cp_bg_image_id.attr('data-css-preview') || '';
        var selector    	= cp_bg_image_id.attr('data-css-selector') || '';
        var property    	= cp_bg_image_id.attr('data-css-property') || '';
        var unit        	= cp_bg_image_id.attr('data-unit') || 'px';
        var value       	= default_img; 	//	Empty the background image
        var name 			= cp_bg_image_id.attr('name');
		var html = '<p class="description">No Image Selected</p>';

		default_img = cp_admin_ajax.assets_url + default_img;

		jQuery("."+img_container).html('<img src="'+default_img+'"/>');
		
		cp_bg_image_id.trigger('change');
		container.find(".cp-media-sizes").hide().addClass('hide-for-default');
	});

	jQuery(".cp-media-size").on("change", function(e){

		var $this 		 = jQuery(this),
			img_url 	 = $this.val(),
			img_id		 = $this.attr('data-id'),
			img_alt		 = $this.attr('data-alt'),
			name		 = $this.attr('name'),
			img_id_input = jQuery('#cp_'+$this.parents('.cp-media-sizes').data('name')),
			input_name	 = img_id_input.attr('name');
			size         = $this.find(":selected").data('size');

		var val = "";
		if( img_id !== '' ) {
			val = img_id + '|' + img_url + '|' + size;
		}
		
		img_id_input.val(val);
		img_id_input.trigger("change");

	});

});