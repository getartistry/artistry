(function($, document)
{

    // variables
    var cacheKey = 'data-jckwt-galleries';

	/* on doc ready */
	$(document).ready(function()
	{
		setupVariationImageManagement();
		setupBulkSaveBtns();
	});

	// local cache

	var localCache = {

        /**
        * timeout for cache in millis
        * @type {number}
        */
        timeout: 1800000, // 30 minutes
        /**
        * @type {{_: number, data: {}}}
        **/
        data: {},
        remove: function (key) {
            delete localCache.data[key];
        },
        exist: function (key) {
            return !!localCache.data[key] && ((new Date().getTime() - localCache.data[key]._) < localCache.timeout);
        },
        get: function (key) {
            return localCache.data[key].data;
        },
        set: function ( key, cachedData, callback) {
            localCache.remove(key);
            localCache.data[key] = {
                _: new Date().getTime(),
                data: cachedData
            };
            if ($.isFunction(callback)) callback(cachedData);
        }

    };

   	// ! Bulk Save Buttons

   	function setupBulkSaveBtns()
   	{
	   	$('.saveVariationImages').on('click', function(){

	   		var btn = $(this),
	   			$messageContainer = btn.next('.updateMsg'),
	   			updateText = btn.attr('data-update'),
	   			updatedText = btn.attr('data-updated'),
	   			updatingText = btn.attr('data-updating'),
	   			errorText = btn.attr('data-error'),
	   			varid = btn.attr('data-varid'),
	   			images = $(btn.attr('data-input')).val(),
	   			ajaxargs = {
					action: iconic_woothumbs_vars.slug+'_bulk_save',
					nonce: iconic_woothumbs_vars.nonce,
					varid: varid,
					images: images
				}

			btn.val(updatingText);

			$.post( iconic_woothumbs_vars.ajaxurl, ajaxargs, function( data )
			{
				if(data.result == 'success')
				{
					btn.val(updatedText);
				}
				else
				{
					btn.val(errorText);
				}

				$messageContainer.text(data.message);

				setTimeout(function(){
					btn.val(updateText);
					$messageContainer.text('');
				}, 3000);
			});

		   	return false;
	   	});
   	}

   	// Update Selected Images

   	function selectedImgs($tableCol) {
		// Get all selected images
		var $selectedImgs = [],
		    $gallery_field = $tableCol.find('.variation_image_gallery');

		$tableCol.find('.wooThumbs .image').each(function(){
			$selectedImgs.push($(this).attr('data-attachment_id'));
		});

		// Update hidden input with chosen images
    	$gallery_field.val($selectedImgs.join(','));
    	input_changed( $gallery_field );
	}

	function triggerGalleryData() {

    	var $ImgUploadBtns = $('.woocommerce_variations .upload_image_button');

        // set an empty object to store our variation galleries by id
        localCache.set( cacheKey, {} );

        // loop through each upload image btn
		$ImgUploadBtns.each(function(){

			var $uploadBtn = $(this),
			    varId = $uploadBtn.attr('rel'),
			    galleries = {};

            // if the cache is already set, get the current data
			if (localCache.exist( cacheKey )) {
        		var galleries = localCache.get( cacheKey );
    		}

    		if( typeof(galleries[varId]) != "undefined" && galleries[varId] !== null ) {

                // this gallery has been loaded before, so
                // trigger this button as ready
                $('body').trigger( 'gallery_ready', [ $uploadBtn, varId ] );

    		} else {

        		// Set up content to inset after variation Image
    			var ajaxargs = {
    				'action': 		'admin_load_thumbnails',
    				'nonce':   		iconic_woothumbs_vars.nonce,
    				'varID': 		varId
    			}

    			$.ajax({
    				url: iconic_woothumbs_vars.ajaxurl,
    				data: ajaxargs,
    				context: this
    			}).success(function(data) {

        			var gallery = data;

            		// add our gallery to the galleries data
            		// and add it to the cache
            		galleries[varId] = gallery;
        			localCache.set( cacheKey, galleries );

        			// this gallery is now loaded, so,
        			// trigger this button as ready
        			$('body').trigger( 'gallery_ready', [ $uploadBtn, varId ] );

                });

    		}

		});

		refreshGalleryHtml();

	}

	// insert gallery html

	function refreshGalleryHtml() {

    	$('body').on('gallery_ready', function( event, $btn, varId ){

            var galleries = {};

    		if (localCache.exist( cacheKey )) {
        		var galleries = localCache.get( cacheKey );
    		}

    		if( typeof(galleries[varId]) != "undefined" && galleries[varId] !== null ) {

        		var galleryWrapperClass = 'wooThumbs-wrapper--'+varId;

        		$('.'+galleryWrapperClass).remove();

                var $wooThumbs = '<div class="wooThumbs-wrapper '+galleryWrapperClass+'"><h4>Additional Images</h4>'+galleries[varId]+'<a href="#" class="manage_wooThumbs button">Add Additional Images</a></div>';
                $btn.after($wooThumbs);

            }

            // Sort Images
			$( ".wooThumbs" ).sortable({
			    deactivate: function(en, ui) {
			        var $tableCol = $(ui.item).closest('.upload_image');
					selectedImgs($tableCol);
			    },
			    placeholder: 'ui-state-highlight'
            });

		});

	}

	function input_changed( $input ) {
    	$input
            .closest( '.woocommerce_variation' )
            .addClass( 'variation-needs-update' );

        $( 'button.cancel-variation-changes, button.save-variation-changes' ).removeAttr( 'disabled' );

        $( '#variable_product_options' ).trigger( 'woocommerce_variations_input_changed' );
	}

	// Setup Variation Image Manager

	function setupVariationImageManagement()
	{
		triggerGalleryData();

		var product_gallery_frame;

		$('.manage_wooThumbs').live( 'click', 'a', function( event ) {

			var $wooThumbs = $(this).siblings('.wooThumbs');
			var $image_gallery_ids = $(this).siblings('.variation_image_gallery');

			var $el = $(this);
			var attachment_ids = $image_gallery_ids.val();

			event.preventDefault();

			// Create the media frame.
			product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
				// Set the title of the modal.
				title: 'Manage Variation Images',
				button: {
					text: 'Add to variation',
				},
				multiple: true
			});

			// When an image is selected, run a callback.
			product_gallery_frame.on( 'select', function() {

				var selection = product_gallery_frame.state().get('selection');

				selection.map( function( attachment ) {

					attachment = attachment.toJSON();

					if ( attachment.id ) {
						attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

						$wooThumbs.append('\
							<li class="image" data-attachment_id="' + attachment.id + '">\
								<a href="#" class="delete" title="Delete image"><img src="' + attachment.url + '" /></a>\
							</li>');
					}

				} );

				$image_gallery_ids.val( attachment_ids );
				input_changed( $image_gallery_ids );
			});

			// Finally, open the modal.
			product_gallery_frame.open();

			return false;
		});

		// Delete Image

		$('.wooThumbs .delete').live("mouseenter mouseleave click", function(event){

			if (event.type == 'click') {
				var $tableCol = $(this).closest('.upload_image');
				// Remove clicked image
				$(this).closest('li').remove();

				selectedImgs($tableCol);
		        return false;
		    }

			if (event.type == 'mouseenter') {
		        $(this).find('img').animate({"opacity": 0.3}, 150);
		    }
		    if (event.type == 'mouseleave') {
		        $(this).find('img').animate({"opacity": 1}, 150);
		    }

		});

		// after variations load

		$( '#woocommerce-product-data' ).on( 'woocommerce_variations_loaded', function(){

    		triggerGalleryData();

		});

		// Once a new variation is added

		$('#variable_product_options').on('woocommerce_variations_added', function(){

    		triggerGalleryData();

		});

	}

}(jQuery, document));








