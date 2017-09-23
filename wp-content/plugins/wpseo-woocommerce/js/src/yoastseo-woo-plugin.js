(function() {

	var AssessmentResult = require( 'yoastseo/js/values/AssessmentResult' );

	/**
	 * Adds eventlistener to load the Yoast WooCommerce plugin
	 */
	if ( typeof YoastSEO !== 'undefined' && typeof YoastSEO.app !== 'undefined' ) {
		new YoastWooCommercePlugin();
	}
	else {
		jQuery( window ).on(
			'YoastSEO:ready',
			function() {
				new YoastWooCommercePlugin();
			}
		);
	}

	/**
	 * Registers Plugin and Test for Yoast WooCommerce.
	 */
	function YoastWooCommercePlugin() {
		YoastSEO.app.registerPlugin( 'YoastWooCommerce', { 'status': 'ready' } );

		YoastSEO.app.registerAssessment( 'productTitle', { getResult: this.productDescription.bind( this ) }, 'YoastWooCommerce' );

		this.addCallback();

		YoastSEO.app.registerPlugin( 'YoastWooCommercePlugin', { status: 'ready' } );

		this.registerModifications();

		this.bindEvents();
	}

	/**
	 * Strip double spaces from text
	 *
	 * @param {String} text The text to strip spaces from.
	 * @returns {String} The text without double spaces
	 */
	var stripSpaces = function( text ) {

		// Replace multiple spaces with single space
		text = text.replace( /\s{2,}/g, ' ' );

		// Replace spaces followed by periods with only the period.
		text = text.replace( /\s\./g, '.' );

		// Remove first/last character if space
		text = text.replace( /^\s+|\s+$/g, '' );

		return text;
	};

	/**
	 * Strip HTML-tags from text
	 *
	 * @param {String} text The text to strip the HTML-tags from.
	 * @returns {String} The text without HTML-tags.
	 */
	var stripTags = function( text ) {
		text = text.replace( /(<([^>]+)>)/ig, ' ' );
		text = stripSpaces( text );
		return text;
	};

	/**
	 * Tests the length of the productdescription.
	 * @param {object} paper The paper to run this assessment on
	 * @param {object} researcher The researcher used for the assessment
	 * @param {object} i18n The i18n-object used for parsing translations
	 * @returns {object} an assessmentresult with the score and formatted text.
	 */
	YoastWooCommercePlugin.prototype.productDescription = function( paper, researcher, i18n ) {
		var productDescription = document.getElementById( 'excerpt' ).value;
		if (typeof tinyMCE !== 'undefined' && tinyMCE.get( 'excerpt') !== null) {
			productDescription = tinyMCE.get( 'excerpt').getContent();
		}

		productDescription = stripTags( productDescription );
		var result = this.scoreProductDescription( productDescription.split( ' ' ).length );
		var assessmentResult = new AssessmentResult();
		assessmentResult.setScore( result.score );
		assessmentResult.setText( result.text );
		return assessmentResult;
	};

	/**
	 * Returns the score based on the lengt of the product description.
	 * @param {number} length The length of the product description.
	 * @returns {{score: number, text: *}} The result object with score and text.
	 */
	YoastWooCommercePlugin.prototype.scoreProductDescription = function( length ) {

		if ( length === 0 ) {
			return {
				score: 1,
				text: wpseoWooL10n.woo_desc_none
			};
		}

		if ( length > 0 && length < 20 ) {
			return {
				score: 5,
				text: wpseoWooL10n.woo_desc_short
			};
		}

		if ( length >= 20 && length <= 50 ) {
			return {
				score: 9,
				text: wpseoWooL10n.woo_desc_good
			};
		}
		if ( length > 50 ) {
			return {
				score: 5,
				text: wpseoWooL10n.woo_desc_long
			};
		}
	};

	/**
	 * Adds callback to the excerpt field to trigger the analyzeTimer when product description is updated.
	 * The tinyMCE triggers automatically since that inherets the binding from the content field tinyMCE.
	 */
	YoastWooCommercePlugin.prototype.addCallback = function() {
		var elem = document.getElementById( 'excerpt' );
		if( elem !== null ){
			elem.addEventListener( 'input', YoastSEO.app.analyzeTimer.bind( YoastSEO.app ) );
		}

	};

	/**
	 * binds events to the add_product_images anchor.
	 */
	YoastWooCommercePlugin.prototype.bindEvents = function() {
		jQuery( '.add_product_images' ).find( 'a' ).on( 'click', this.bindLinkEvent.bind( this ) );

	};

	/**
	 * counters for the setTimeouts, used to make sure we don't end up in an infinite loop.
	 * @type {number}
	 */
	var buttonEventCounter = 0;
	var deleteEventCounter = 0;

	/**
	 * after the modal dialog is opened, check for the button that adds images to the gallery to trigger
	 * the modification.
	 */
	YoastWooCommercePlugin.prototype.bindLinkEvent = function() {
		if (jQuery( '.media-modal-content' ).find( '.media-button' ).length === 0 ) {
			buttonEventCounter++;
			if ( buttonEventCounter < 10 ) {
				setTimeout( this.bindLinkEvent.bind( this ) );
			}
		} else {
			buttonEventCounter = 0;
			jQuery( '.media-modal-content' ).find( '.media-button' ).on( 'click', this.buttonCallback.bind( this )  );
		}
	};

	/**
	 * After the gallery is added, call the analyzeTimer of the app, to add the modifications.
	 * Also call the bindDeleteEvent, to bind the analyzerTimer when an image is deleted.
	 */
	YoastWooCommercePlugin.prototype.buttonCallback = function() {
		YoastSEO.app.analyzeTimer();
		this.bindDeleteEvent();
	};

	/**
	 * Checks if the delete buttons of the added images are available. When they are, bind the analyzeTimer function
	 * so when a image is removed, the modification is run.
	 */
	YoastWooCommercePlugin.prototype.bindDeleteEvent = function() {
		if ( jQuery( '#product_images_container' ).find( '.delete' ).length === 0 ){
			deleteEventCounter++;
			if ( deleteEventCounter < 10 ) {
				setTimeout( this.bindDeleteEvent.bind( this ) );
			}
		} else {
			deleteEventCounter = 0;
			jQuery( '#product_images_container' ).find( '.delete' ).on( 'click', YoastSEO.app.analyzeTimer.bind( YoastSEO.app ) );
		}
	};

	/**
	 * Registers the addImageToContent modification
	 */
	YoastWooCommercePlugin.prototype.registerModifications = function() {
		var callback = this.addImageToContent.bind( this );

		YoastSEO.app.registerModification( 'content', callback, 'YoastWooCommercePlugin', 10 );
	};

	/**
	 * Adds the images from the pagegallery to the content to be analyzed by the analyzer.
	 * @param data {String}
	 * @returns {String}
	 */
	YoastWooCommercePlugin.prototype.addImageToContent = function( data ) {
		var images = jQuery( '#product_images_container' ).find( 'img' );

		for (var i = 0; i < images.length; i++ ){
			data += images[ i ].outerHTML;
		}
		return data;
	};
}
());
