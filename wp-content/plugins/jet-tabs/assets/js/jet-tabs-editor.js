(function( $ ) {

	'use strict';

	var JetTabsEditor,
		JetTabsData = window.JetTabsData || {};

	JetTabsEditor = {

		modal: false,

		init: function() {
			window.elementor.on( 'preview:loaded', JetTabsEditor.onPreviewLoaded );
		},

		onPreviewLoaded: function() {
			var $previewContents = window.elementor.$previewContents,
				elementorFrontend = $('#elementor-preview-iframe')[0].contentWindow.elementorFrontend;

			elementorFrontend.hooks.addAction( 'frontend/element_ready/jet-tabs.default', function( $scope ){
				$scope.find( '.jet-tabs__edit-cover' ).on( 'click', JetTabsEditor.showTemplatesModal );
				$scope.find( '.jet-tabs-new-template-link' ).on( 'click', function( event ) {
					window.location.href = $( this ).attr( 'href' );
				} );
			} );

			elementorFrontend.hooks.addAction( 'frontend/element_ready/jet-accordion.default', function( $scope ){
				$scope.find( '.jet-toggle__edit-cover' ).on( 'click', JetTabsEditor.showTemplatesModal );
				$scope.find( '.jet-toogle-new-template-link' ).on( 'click', function( event ) {
					window.location.href = $( this ).attr( 'href' );
				} );
			} );

			JetTabsEditor.getModal().on( 'hide', function() {
				window.elementor.reloadPreview();
			});
		},

		showTemplatesModal: function() {
			var editLink = $( this ).data( 'template-edit-link' );
			console.log(editLink);

			JetTabsEditor.showModal( editLink );
		},

		showModal: function( link ) {
			var $iframe,
				$loader;

			JetTabsEditor.getModal().show();

			$( '#jet-template-library-modal .dialog-message').html( '<iframe src="' + link + '" id="jet-tabs-edit-frame" width="100%" height="100%"></iframe>' );
			$( '#jet-template-library-modal .dialog-message').append( '<div id="jet-tabs-loading"><div class="elementor-loader-wrapper"><div class="elementor-loader"><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div></div><div class="elementor-loading-title">Loading</div></div></div>' );

			$iframe = $( '#jet-tabs-edit-frame');
			$loader = $( '#jet-tabs-loading');

			$iframe.on( 'load', function() {
				$loader.fadeOut( 300 );
			} );
		},

		getModal: function() {

			if ( ! JetTabsEditor.modal ) {
				this.modal = elementor.dialogsManager.createWidget( 'lightbox', {
					id: 'jet-template-library-modal',
					closeButton: true,
					hide: {
						onBackgroundClick: false
					}
				} );
			}

			return JetTabsEditor.modal;
		}

	};

	$( window ).on( 'elementor:init', JetTabsEditor.init );

})( jQuery );
