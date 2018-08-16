/**
 * Showing and Hiding controls of Customizer.
 *
 * @package Astra Addon
 * @since 1.4.0
 */

( function( $ ) {
	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {


		// Mobile Menu style.
		ASTCustomizerToggles['astra-settings[disable-primary-nav]'].push(
			{
				controls: [
					'astra-settings[mobile-menu-style]',
					'astra-settings[mobile-header-toggle-btn-style]',
					'astra-settings[mobile-header-toggle-btn-style-color]',
					'astra-settings[header-main-menu-label]',
				],
				callback: function( menu ) {
					if ( ! menu ) {
						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[mobile-header-toggle-btn-border-radius]',
				],
				callback: function( menu ) {
					var disable_prim_menu = api( 'astra-settings[disable-primary-nav]' ).get();
					var mobileMenuStyle   = (typeof api( 'astra-settings[mobile-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-menu-style]' ).get() : '';
					var toggle_style      = api( 'astra-settings[mobile-header-toggle-btn-style]' ).get();

					if ( ! disable_prim_menu && 'no-toggle' !== mobileMenuStyle && 
						'minimal' !== toggle_style ) {
						return true;
					}

					return false;
				},
			}
		);

		if ( typeof ASTCustomizerToggles['astra-settings[mobile-menu-style]'] != 'undefined' && ASTCustomizerToggles['astra-settings[mobile-menu-style]'].length > 0 ) {

			ASTCustomizerToggles['astra-settings[mobile-menu-style]'].push(
				{
					controls: [
						'astra-settings[flyout-mobile-menu-alignment]',
					],
					callback: function( mobileMenuStyle ) {

						if ( 'flyout' == mobileMenuStyle ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-header-toggle-btn-style]',
						'astra-settings[mobile-header-toggle-btn-style-color]',
						'astra-settings[header-main-menu-label]',
					],
					callback: function( mobileMenuStyle ) {
						var menu        = api( 'astra-settings[disable-primary-nav]' ).get();
						if ( 'no-toggle' !== mobileMenuStyle && ( ! menu ) ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-header-toggle-btn-border-radius]',
					],
					callback: function( menu ) {
						var disable_prim_menu = api( 'astra-settings[disable-primary-nav]' ).get();
						var mobileMenuStyle   = (typeof api( 'astra-settings[mobile-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-menu-style]' ).get() : '';
						var toggle_style      = api( 'astra-settings[mobile-header-toggle-btn-style]' ).get();

						if ( ! disable_prim_menu && 'no-toggle' !== mobileMenuStyle && 
							'minimal' !== toggle_style ) {
							return true;
						}

						return false;
					}
				}
			);

		} else {

			ASTCustomizerToggles['astra-settings[mobile-menu-style]'] = [
				{
					controls: [
						'astra-settings[flyout-mobile-menu-alignment]',
					],
					callback: function( mobileMenuStyle ) {

						if ( 'flyout' == mobileMenuStyle ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-header-toggle-btn-style]',
						'astra-settings[mobile-header-toggle-btn-style-color]',
						'astra-settings[header-main-menu-label]',
					],
					callback: function( mobileMenuStyle ) {
						var menu        = api( 'astra-settings[disable-primary-nav]' ).get();
						var custom_menu = api( 'astra-settings[header-main-rt-section]' ).get();
						if ( 'no-toggle' !== mobileMenuStyle && ( ! menu ) ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-header-toggle-btn-border-radius]',
					],
					callback: function( menu ) {
						var disable_prim_menu = api( 'astra-settings[disable-primary-nav]' ).get();
						var mobileMenuStyle   = (typeof api( 'astra-settings[mobile-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-menu-style]' ).get() : '';
						var toggle_style      = api( 'astra-settings[mobile-header-toggle-btn-style]' ).get();

						if ( ! disable_prim_menu && 'no-toggle' !== mobileMenuStyle && 
							'minimal' !== toggle_style ) {
							return true;
						}

						return false;
					}
				},
			];
		}

		// Mobile Above Header Menu style.
		ASTCustomizerToggles['astra-settings[mobile-above-header-menu-style]'] = [
			{
				controls: [
					'astra-settings[mobile-above-header-color-divider]',
					'astra-settings[above-header-menu-label]',
				],
				callback: function( menu_style )
				{
					var val                    = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
					var menu_style             = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
					var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
					var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
					var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
					var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';

					if ( 'no-toggle' !== menu_style && above_header_on_mobile && 1 != merge_menu &&
						(
							( val == 'above-header-layout-2' && left_section == 'menu' ) ||
							( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
					))) {

						return true;
					}

					return false;
				}
			},
			{
				controls: [
					'astra-settings[mobile-above-header-menu-b-color]',
				],
				callback: function( val ) {
					var val                    =  api( 'astra-settings[mobile-above-header-menu-all-border]' ).get();
					var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
					var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
					var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
					var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
					var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
					
					if (( above_header_on_mobile &&
						1 != aboveHeaderMerge &&
						( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) && ( '' != val.top || '' != val.right || '' != val.bottom || '' != val.left ) && ( 0 != val.top || 0 != val.right || 0 != val.bottom || 0 != val.left ) )
					{
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[mobile-above-header-toggle-btn-border-radius]',
				],
				callback: function( val ) {
					var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined') ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
					var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
					var above_header_layout    = api( 'astra-settings[above-header-layout]' ).get();
					var mobileMenuStyle        = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
					var toggle_style           = api( 'astra-settings[mobile-above-header-toggle-btn-style]' ).get();
					var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
					var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';

					if ( ( above_header_layout == 'above-header-layout-2' && left_section == 'menu' ) ||
							( above_header_layout == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
					)) {			

						if ( above_header_on_mobile && '1' != merge_menu && 'no-toggle' !== mobileMenuStyle && 
							'minimal' !== toggle_style ) {
							return true;
						}
					}

					return false;
				}
			},
		];

		// Mobile Below Header Menu style.
		ASTCustomizerToggles['astra-settings[mobile-below-header-menu-style]'] = [
			{
				controls: [
					'astra-settings[below-header-menu-label]',
				],
				callback: function( val ) {
					var mobileMenuStyle            = api( 'astra-settings[mobile-below-header-menu-style]' ).get() || '';
					var belowHeader            = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined') ? api( 'astra-settings[below-header-layout]' ).get() : '';
					var belowHeaderMerge       = ( typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined') ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
					var left_section           = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined') ? api( 'astra-settings[below-header-section-1]' ).get() : '';
					var right_section          = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined') ? api( 'astra-settings[below-header-section-2]' ).get() : '';
					var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';

					if ( below_header_on_mobile && 'no-toggle' !== mobileMenuStyle && 1 != belowHeaderMerge && ( ( belowHeader == 'below-header-layout-2' && left_section == 'menu' ) ||
							( belowHeader == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) )  ) {
						return true;
					}

					return false;
				}
			},
		];

		/* Mobile Menu Border */
		ASTCustomizerToggles['astra-settings[mobile-header-menu-all-border]'] = [
			{
				controls: [
					'astra-settings[mobile-header-menu-b-color]',
				],
				callback: function( val ) {

					if ( ( '' != val.top || '' != val.right || '' != val.bottom || '' != val.left ) && ( 0 != val.top || 0 != val.right || 0 != val.bottom || 0 != val.left ) ) {
						return true;
					}
					return false;
				}
			},
		];

		/* Mobile Menu Border */
		ASTCustomizerToggles['astra-settings[mobile-header-menu-all-border]'] = [
			{
				controls: [
					'astra-settings[mobile-header-menu-b-color]',
				],
				callback: function( val ) {

					if ( ( '' != val.top || '' != val.right || '' != val.bottom || '' != val.left ) && ( 0 != val.top || 0 != val.right || 0 != val.bottom || 0 != val.left ) ) {
						return true;
					}
					return false;
				}
			},
		];

		/* Mobile Above Menu Border */
		ASTCustomizerToggles['astra-settings[mobile-above-header-menu-all-border]'] = [
			{
				controls: [
					'astra-settings[mobile-above-header-menu-b-color]',
				],
				callback: function( val ) {
					var val                    =  api( 'astra-settings[mobile-above-header-menu-all-border]' ).get();
					var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
					var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
					var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
					var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
					var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
					
					if (( above_header_on_mobile &&
						1 != aboveHeaderMerge &&
						( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) && ( '' != val.top || '' != val.right || '' != val.bottom || '' != val.left ) && ( 0 != val.top || 0 != val.right || 0 != val.bottom || 0 != val.left ) )
					{
						return true;
					}
					return false;
				}
			},
		];

		/**
		 * Above Header
		 */
		if ( typeof ASTCustomizerToggles['astra-settings[above-header-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[above-header-layout]'].length > 0 ) {
			ASTCustomizerToggles['astra-settings[above-header-layout]'].push(
				{
					controls: [
						'astra-settings[mobile-above-header-menu-b-color]',
					],
					callback: function( val ) {
						var val                    =  api( 'astra-settings[mobile-above-header-menu-all-border]' ).get();
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						
						if (( above_header_on_mobile &&
							1 != aboveHeaderMerge &&
							( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) && ( '' != val.top || '' != val.right || '' != val.bottom || '' != val.left ) && ( 0 != val.top || 0 != val.right || 0 != val.bottom || 0 != val.left ) )
						{
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-above-header-divider]',
						'astra-settings[mobile-above-header-menu-style]',
						'astra-settings[mobile-above-header-menu-b-color]',

					],
					callback: function( aboveHeaderMerge ) {
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						

						if ( above_header_on_mobile && 1 != aboveHeaderMerge && ( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) {
							return true;
						}
						return false;
					}
				}
			);
		}
		/* Mobile above Merge Menu */
		if ( typeof ASTCustomizerToggles['astra-settings[above-header-merge-menu]'] != 'undefined' && ASTCustomizerToggles['astra-settings[above-header-merge-menu]'].length > 0 ) {
			ASTCustomizerToggles['astra-settings[above-header-merge-menu]'].push(
				{
					controls: [
						'astra-settings[mobile-above-header-menu-b-color]',
					],
					callback: function( val ) {
						var val                    =  api( 'astra-settings[mobile-above-header-menu-all-border]' ).get();
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						
						if (( above_header_on_mobile &&
							1 != aboveHeaderMerge &&
							( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) && ( '' != val.top || '' != val.right || '' != val.bottom || '' != val.left ) && ( 0 != val.top || 0 != val.right || 0 != val.bottom || 0 != val.left ) )
						{
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-above-header-divider]',
						'astra-settings[mobile-above-header-menu-style]',
						'astra-settings[mobile-above-header-menu-b-color]',
					],
					callback: function( aboveHeaderMerge ) {
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';

						if ( above_header_on_mobile && 1 != aboveHeaderMerge && ( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) {
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-above-header-color-divider]',
						'astra-settings[above-header-menu-label]',
					],
					callback: function( menu_style )
					{
						var val                    = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var menu_style             = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';

						if ( 'no-toggle' !== menu_style && above_header_on_mobile && 1 != merge_menu &&
							(
								( val == 'above-header-layout-2' && left_section == 'menu' ) ||
								( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
						))) {

							return true;
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-above-header-toggle-btn-border-radius]',
					],
					callback: function( val ) {

						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined') ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						var above_header_layout    = api( 'astra-settings[above-header-layout]' ).get();
						var mobileMenuStyle        = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
						var toggle_style           = api( 'astra-settings[mobile-above-header-toggle-btn-style]' ).get();
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';

						if ( ( above_header_layout == 'above-header-layout-2' && left_section == 'menu' ) ||
								( above_header_layout == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
						)) {			

							if ( above_header_on_mobile && '1' != merge_menu && 'no-toggle' !== mobileMenuStyle && 
								'minimal' !== toggle_style ) {
								return true;
							}
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[above-header-menu-align]',
					],
					callback: function( val ) {

						var header_layout          = api( 'astra-settings[above-header-layout]' ).get();
						var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined') ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';	

						if( 'above-header-layout-1' == header_layout &&
						 '1' != merge_menu &&  above_header_on_mobile ) {
							return true;
						}

						return false;
					}
				}
			);
		}

		/**
		 * Above Header Section 1
		 **/
		if ( typeof ASTCustomizerToggles['astra-settings[above-header-section-1]'] != 'undefined' && ASTCustomizerToggles['astra-settings[above-header-section-1]'].length > 0 ) {
			/* Layout 1 Left Section */
			ASTCustomizerToggles['astra-settings[above-header-section-1]'].push(
				{
					controls: [
						'astra-settings[mobile-above-header-menu-b-color]',
					],
					callback: function( val ) {
						var val                    =  api( 'astra-settings[mobile-above-header-menu-all-border]' ).get();
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						
						if (( above_header_on_mobile &&
							1 != aboveHeaderMerge &&
							( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) && ( '' != val.top || '' != val.right || '' != val.bottom || '' != val.left ) && ( 0 != val.top || 0 != val.right || 0 != val.bottom || 0 != val.left ) )
						{
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-above-header-divider]',
						'astra-settings[mobile-above-header-menu-style]',
					],
					callback: function( aboveHeaderMerge ) {
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						

						if ( above_header_on_mobile && 1 != aboveHeaderMerge && ( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) {
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-above-header-color-divider]',
						'astra-settings[above-header-menu-label]',
					],
					callback: function( menu_style )
					{
						var val                    = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var menu_style             = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';

						if ( 'no-toggle' !== menu_style && above_header_on_mobile && 1 != merge_menu &&
							(
								( val == 'above-header-layout-2' && left_section == 'menu' ) ||
								( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
						))) {

							return true;
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-above-header-toggle-btn-border-radius]',
					],
					callback: function( val ) {

						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined') ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						var above_header_layout    = api( 'astra-settings[above-header-layout]' ).get();
						var mobileMenuStyle        = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
						var toggle_style           = api( 'astra-settings[mobile-above-header-toggle-btn-style]' ).get();
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';

						if ( ( above_header_layout == 'above-header-layout-2' && left_section == 'menu' ) ||
								( above_header_layout == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
						)) {			

							if ( above_header_on_mobile && '1' != merge_menu && 'no-toggle' !== mobileMenuStyle && 
								'minimal' !== toggle_style ) {
								return true;
							}
						}

						return false;
					}
				}
			);
		}

		/**
		 * Above Header Section 2
		 **/
		if ( typeof ASTCustomizerToggles['astra-settings[above-header-section-2]'] != 'undefined' && ASTCustomizerToggles['astra-settings[above-header-section-2]'].length > 0 ) {
			/* Layout 2 Right Section */
			ASTCustomizerToggles['astra-settings[above-header-section-2]'].push(
				{
					controls: [
						'astra-settings[mobile-above-header-menu-b-color]',
					],
					callback: function( val ) {
						var val                    =  api( 'astra-settings[mobile-above-header-menu-all-border]' ).get();
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						
						if (( above_header_on_mobile &&
							1 != aboveHeaderMerge &&
							( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) && ( '' != val.top || '' != val.right || '' != val.bottom || '' != val.left ) && ( 0 != val.top || 0 != val.right || 0 != val.bottom || 0 != val.left ) )
						{
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-above-header-divider]',
					],
					callback: function( aboveHeaderMerge ) {
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						
						if ( above_header_on_mobile && 1 != aboveHeaderMerge && ( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) {
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-above-header-color-divider]',
						'astra-settings[above-header-menu-label]',
					],
					callback: function( menu_style )
					{
						var val                    = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var menu_style             = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';

						if ( 'no-toggle' !== menu_style && above_header_on_mobile && 1 != merge_menu &&
							(
								( val == 'above-header-layout-2' && left_section == 'menu' ) ||
								( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
						))) {

							return true;
						}
						return false;
					}
				}
			);
		}

		/**
		 * Above Header On Mobile
		 **/
		if ( typeof ASTCustomizerToggles['astra-settings[above-header-on-mobile]'] != 'undefined' && ASTCustomizerToggles['astra-settings[above-header-on-mobile]'].length > 0 ) {
			/* Layout 2 Right Section */
			ASTCustomizerToggles['astra-settings[above-header-on-mobile]'].push(
				{
					controls: [
						'astra-settings[mobile-above-header-menu-b-color]',
					],
					callback: function( val ) {
						var val                    =  api( 'astra-settings[mobile-above-header-menu-all-border]' ).get();
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						
						if (( above_header_on_mobile &&
							1 != aboveHeaderMerge &&
							( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) && ( '' != val.top || '' != val.right || '' != val.bottom || '' != val.left ) && ( 0 != val.top || 0 != val.right || 0 != val.bottom || 0 != val.left ) )
						{
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-above-header-divider]',
					],
					callback: function( aboveHeaderMerge ) {
						var aboveHeader            = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var aboveHeaderMerge       = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						

						if ( above_header_on_mobile && 1 != aboveHeaderMerge && ( ( aboveHeader == 'above-header-layout-2' && left_section == 'menu' ) || ( aboveHeader == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) {
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-above-header-color-divider]',
						'astra-settings[above-header-menu-label]',
					],
					callback: function( menu_style )
					{
						var val                    = (typeof api( 'astra-settings[above-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[above-header-layout]' ).get() : '';
						var menu_style             = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';
						var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';

						if ( 'no-toggle' !== menu_style && above_header_on_mobile && 1 != merge_menu &&
							(
								( val == 'above-header-layout-2' && left_section == 'menu' ) ||
								( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
						))) {

							return true;
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[mobile-above-header-toggle-btn-border-radius]',
					],
					callback: function( val ) {

						var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined') ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
						var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
						var above_header_layout    = api( 'astra-settings[above-header-layout]' ).get();
						var mobileMenuStyle        = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
						var toggle_style           = api( 'astra-settings[mobile-above-header-toggle-btn-style]' ).get();
						var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';

						if ( ( above_header_layout == 'above-header-layout-2' && left_section == 'menu' ) ||
								( above_header_layout == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
						)) {			

							if ( above_header_on_mobile && '1' != merge_menu && 'no-toggle' !== mobileMenuStyle && 
								'minimal' !== toggle_style ) {
								return true;
							}
						}

						return false;
					}
				}
			);
		}


		/**
		 * below Header
		 */
		if ( typeof ASTCustomizerToggles['astra-settings[below-header-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[below-header-layout]'].length > 0 ) {
			ASTCustomizerToggles['astra-settings[below-header-layout]'].push(
				{
					controls: [
						'astra-settings[mobile-below-header-color-divider]',

						'astra-settings[mobile-below-header-color-divider]',
						'astra-settings[below-header-menu-label]',
					],
					callback: function( val ) {
						var mobileMenuStyle            = api( 'astra-settings[mobile-below-header-menu-style]' ).get() || '';
						var belowHeader            = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined') ? api( 'astra-settings[below-header-layout]' ).get() : '';
						var belowHeaderMerge       = ( typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined') ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined') ? api( 'astra-settings[below-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined') ? api( 'astra-settings[below-header-section-2]' ).get() : '';
						var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';


						if ( below_header_on_mobile && 'no-toggle' !== mobileMenuStyle && 1 != belowHeaderMerge && ( ( belowHeader == 'below-header-layout-2' && left_section == 'menu' ) ||
								( belowHeader == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) )  ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-below-header-divider]',
												
						// Colors & Backgrounds.
						'astra-settings[divider-mobile-below-header-menu]',

						'astra-settings[below-header-menu-label]',
					],
					callback: function( val ) {

						var left_section = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[below-header-section-1]' ).get() : '';
						var right_section = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[below-header-section-2]' ).get() : '';
						var merge_menu = (typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
						var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';

						if ( below_header_on_mobile && 1 != merge_menu && ( ( val == 'below-header-layout-2' && left_section == 'menu' ) ||
							( val == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) {

							return true;
						}

						return false;
					}
				}
			);
		}
		 if ( typeof ASTCustomizerToggles['astra-settings[below-header-merge-menu]'] != 'undefined' && ASTCustomizerToggles['astra-settings[below-header-merge-menu]'].length > 0 ) {
			/* Mobile below Merge Menu */
			ASTCustomizerToggles['astra-settings[below-header-merge-menu]'].push(
				{
					controls: [
						'astra-settings[below-header-menu-label]',
					],
					callback: function( val ) {
						var mobileMenuStyle            = api( 'astra-settings[mobile-below-header-menu-style]' ).get() || '';
						var belowHeader            = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined') ? api( 'astra-settings[below-header-layout]' ).get() : '';
						var belowHeaderMerge       = ( typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined') ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined') ? api( 'astra-settings[below-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined') ? api( 'astra-settings[below-header-section-2]' ).get() : '';
						var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';

						if ( below_header_on_mobile && 'no-toggle' != mobileMenuStyle && 1 != belowHeaderMerge && ( ( belowHeader == 'below-header-layout-2' && left_section == 'menu' ) ||
								( belowHeader == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) )  ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-below-header-divider]',

						// Colors & Backgrounds.
						'astra-settings[divider-mobile-below-header-menu]',
					],
					callback: function( belowHeaderMerge ) {
						var belowHeader            = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined') ? api( 'astra-settings[below-header-layout]' ).get() : '';
						var belowHeaderMerge       = ( typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined') ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined') ? api( 'astra-settings[below-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined') ? api( 'astra-settings[below-header-section-2]' ).get() : '';
						var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';

						if ( below_header_on_mobile &&  1 != belowHeaderMerge && ( ( belowHeader == 'below-header-layout-2' && left_section == 'menu' ) || ( belowHeader == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) {
							return true;
						}
						return false;
					}
				}
			);
		}

		/**
		 * below Header Section 1
		 **/
		 if ( typeof ASTCustomizerToggles['astra-settings[below-header-section-1]'] != 'undefined' && ASTCustomizerToggles['astra-settings[below-header-section-1]'].length > 0 ) {
			/* Layout 1 Left Section */
			ASTCustomizerToggles['astra-settings[below-header-section-1]'].push(
				{
					controls: [
						'astra-settings[below-header-menu-label]',
					],
					callback: function( val ) {
						var mobileMenuStyle            = api( 'astra-settings[mobile-below-header-menu-style]' ).get() || '';
						var belowHeader            = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined') ? api( 'astra-settings[below-header-layout]' ).get() : '';
						var belowHeaderMerge       = ( typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined') ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined') ? api( 'astra-settings[below-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined') ? api( 'astra-settings[below-header-section-2]' ).get() : '';
						var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';

						if ( below_header_on_mobile && 'no-toggle' != mobileMenuStyle && 1 != belowHeaderMerge && ( ( belowHeader == 'below-header-layout-2' && left_section == 'menu' ) ||
								( belowHeader == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) )  ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-below-header-divider]',
						
						// Colors & Backgrounds.
						'astra-settings[divider-mobile-below-header-menu]',

						'astra-settings[below-header-menu-label]',
					],
					callback: function( left_section ) {

						var val = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[below-header-layout]' ).get() : '';
						var right_section = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[below-header-section-2]' ).get() : '';
						var merge_menu = (typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
						var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';

						if ( below_header_on_mobile && 1 != merge_menu && ( ( val == 'below-header-layout-2' && left_section == 'menu' ) ||
							( val == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) {

							return true;
						}

						return false;
					}
				}
			);
		}

		/**
		 * below Header Section 2
		 **/
		 if ( typeof ASTCustomizerToggles['astra-settings[below-header-section-2]'] != 'undefined' && ASTCustomizerToggles['astra-settings[below-header-section-2]'].length > 0 ) {
			/* Layout 2 Right Section */
			ASTCustomizerToggles['astra-settings[below-header-section-2]'].push(
				{
					controls: [
						'astra-settings[below-header-menu-label]',
					],
					callback: function( val ) {
						var mobileMenuStyle            = api( 'astra-settings[mobile-below-header-menu-style]' ).get() || '';
						var belowHeader            = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined') ? api( 'astra-settings[below-header-layout]' ).get() : '';
						var belowHeaderMerge       = ( typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined') ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined') ? api( 'astra-settings[below-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined') ? api( 'astra-settings[below-header-section-2]' ).get() : '';
						var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';

						if ( below_header_on_mobile && 'no-toggle' != mobileMenuStyle && 1 != belowHeaderMerge && ( ( belowHeader == 'below-header-layout-2' && left_section == 'menu' ) ||
								( belowHeader == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) )  ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-below-header-divider]',
						// Colors & Backgrounds.
						'astra-settings[divider-mobile-below-header-menu]',

						'astra-settings[below-header-menu-label]',
					],
					callback: function( right_section ) {

						var val = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[below-header-layout]' ).get() : '';
						var left_section = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[below-header-section-1]' ).get() : '';
						var merge_menu = (typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
						var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';

						if (  below_header_on_mobile && 1 != merge_menu && ( ( val == 'below-header-layout-2' && left_section == 'menu' ) ||
							( val == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) ) {

							return true;
						}

						return false;
					}
				}
			);
		}

		/**
		 * below Header On Mobile
		 **/
		 if ( typeof ASTCustomizerToggles['astra-settings[below-header-on-mobile]'] != 'undefined' && ASTCustomizerToggles['astra-settings[below-header-on-mobile]'].length > 0 ) {
			/* Layout 1 Left Section */
			ASTCustomizerToggles['astra-settings[below-header-on-mobile]'].push(
				{
					controls: [
						'astra-settings[below-header-menu-label]',
					],
					callback: function( val ) {
						var mobileMenuStyle            = api( 'astra-settings[mobile-below-header-menu-style]' ).get() || '';
						var belowHeader            = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined') ? api( 'astra-settings[below-header-layout]' ).get() : '';
						var belowHeaderMerge       = ( typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined') ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';
						var left_section           = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined') ? api( 'astra-settings[below-header-section-1]' ).get() : '';
						var right_section          = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined') ? api( 'astra-settings[below-header-section-2]' ).get() : '';
						var below_header_on_mobile = (typeof api( 'astra-settings[below-header-on-mobile]' ) != 'undefined' ) ? api( 'astra-settings[below-header-on-mobile]' ).get() : '';

						if ( below_header_on_mobile && 'no-toggle' != mobileMenuStyle && 1 != belowHeaderMerge && ( ( belowHeader == 'below-header-layout-2' && left_section == 'menu' ) ||
								( belowHeader == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) )  ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						// Layout.
						'astra-settings[mobile-header-below-header-divider]',

						// Colors & Backgrounds.
						'astra-settings[divider-mobile-below-header-menu]',

						'astra-settings[below-header-menu-label]',
					],
					callback: function( below_header_on_mobile ) {

						var val = (typeof api( 'astra-settings[below-header-layout]' ) != 'undefined' ) ? api( 'astra-settings[below-header-layout]' ).get() : '';
						var left_section = (typeof api( 'astra-settings[below-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[below-header-section-1]' ).get() : '';
						var right_section = (typeof api( 'astra-settings[below-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[below-header-section-2]' ).get() : '';
						var merge_menu = (typeof api( 'astra-settings[below-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[below-header-merge-menu]' ).get() : '';

						if ( below_header_on_mobile && 1 != merge_menu && (
							( val == 'below-header-layout-2' && left_section == 'menu' ) || 
							( val == 'below-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) 
						) ) {

							return true;
						}

						return false;
					}
				}
			);
		}

		ASTCustomizerToggles['astra-settings[mobile-header-toggle-btn-style]'] = [
			{
				controls: [
					'astra-settings[mobile-header-toggle-btn-border-radius]',
				],
				callback: function( val ) {

					var disable_prim_menu = api( 'astra-settings[disable-primary-nav]' ).get();
					var mobileMenuStyle   = (typeof api( 'astra-settings[mobile-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-menu-style]' ).get() : '';
					var toggle_style      = api( 'astra-settings[mobile-header-toggle-btn-style]' ).get();

					if ( ! disable_prim_menu && 'no-toggle' !== mobileMenuStyle && 
						'minimal' !== toggle_style ) {
						return true;
					}

					return false;
				}
			}
		];

		ASTCustomizerToggles['astra-settings[mobile-above-header-toggle-btn-style]'] = [
			{
				controls: [
					'astra-settings[mobile-above-header-toggle-btn-border-radius]',
				],
				callback: function( val ) {

					var above_header_on_mobile = (typeof api( 'astra-settings[above-header-on-mobile]' ) != 'undefined') ? api( 'astra-settings[above-header-on-mobile]' ).get() : '';
					var merge_menu             = (typeof api( 'astra-settings[above-header-merge-menu]' ) != 'undefined' ) ? api( 'astra-settings[above-header-merge-menu]' ).get() : '';
					var above_header_layout    = api( 'astra-settings[above-header-layout]' ).get();
					var mobileMenuStyle        = (typeof api( 'astra-settings[mobile-above-header-menu-style]' ) != 'undefined' ) ? api( 'astra-settings[mobile-above-header-menu-style]' ).get() : '';
					var toggle_style           = api( 'astra-settings[mobile-above-header-toggle-btn-style]' ).get();
					var left_section           = (typeof api( 'astra-settings[above-header-section-1]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-1]' ).get() : '';
					var right_section          = (typeof api( 'astra-settings[above-header-section-2]' ) != 'undefined' ) ? api( 'astra-settings[above-header-section-2]' ).get() : '';

					if ( ( above_header_layout == 'above-header-layout-2' && left_section == 'menu' ) ||
							( above_header_layout == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' )
					)) {			

						if ( above_header_on_mobile && '1' != merge_menu && 'no-toggle' !== mobileMenuStyle && 
							'minimal' !== toggle_style ) {
							return true;
						}
					}

					return false;
				}
			}
		];

	});
})( jQuery );