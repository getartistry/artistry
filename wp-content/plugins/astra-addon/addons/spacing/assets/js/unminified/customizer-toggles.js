( function( $ ) {
	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {
		
		/**
		 * Site Identity Spacer
		 */
		ASTCustomizerToggles['astra-settings[display-site-title]'].push(
			{
				controls: [
					'astra-settings[site-identity-spacing-divider]',
					'astra-settings[site-identity-spacing]',
				],
				callback: function( value ) {

					var site_tagline = api( 'astra-settings[display-site-tagline]' ).get();
					var has_custom_logo = api( 'custom_logo' ).get();
					var has_retina_logo = api( 'astra-settings[ast-header-retina-logo]' ).get();

					if ( value || site_tagline || has_custom_logo || has_retina_logo ) {
						return true;
					}
					return false;
				}
			}
		);

		ASTCustomizerToggles['astra-settings[display-site-tagline]'].push(
			{
				controls: [
					'astra-settings[site-identity-spacing-divider]',
					'astra-settings[site-identity-spacing]',
				],
				callback: function( value ) {

					var site_title = api( 'astra-settings[display-site-title]' ).get();
					var has_custom_logo = api( 'custom_logo' ).get();
					var has_retina_logo = api( 'astra-settings[ast-header-retina-logo]' ).get();

					if ( value || site_title || has_custom_logo || has_retina_logo ) {
						return true;
					}
					return false;
				}
			}
		);

		ASTCustomizerToggles['astra-settings[ast-header-retina-logo]'].push(
			{
				controls: [
					'astra-settings[site-identity-spacing-divider]',
					'astra-settings[site-identity-spacing]',
				],
				callback: function( value ) {

					var site_title = api( 'astra-settings[display-site-title]' ).get();
					var has_custom_logo = api( 'custom_logo' ).get();
					var has_retina_logo = api( 'astra-settings[ast-header-retina-logo]' ).get();

					if ( value || site_title || has_custom_logo || has_retina_logo ) {
						return true;
					}
					return false;
				}
			}
		);

		ASTCustomizerToggles['custom_logo'].push(
			{
				controls: [
					'astra-settings[site-identity-spacing-divider]',
					'astra-settings[site-identity-spacing]',
				],
				callback: function( value ) {

					var has_retina_logo = api( 'astra-settings[ast-header-retina-logo]' ).get();
					var site_tagline = api( 'astra-settings[display-site-tagline]' ).get();
					var site_title = api( 'astra-settings[display-site-title]' ).get();

					if ( value || has_retina_logo || site_title || site_tagline ) {
						return true;
					}
					return false;
				}
			}
		);

		/**
		 * Below Header Menu Spacer
		 */
		if( typeof ASTCustomizerToggles['astra-settings[below-header-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[below-header-layout]'].length > 0 ) {
			
			ASTCustomizerToggles['astra-settings[below-header-layout]'].push(
				{
					controls: [
						'astra-settings[below-header-menu-spacing]',
						'astra-settings[below-header-submenu-spacing]',
					],
					callback: function( val ) {

						var section_1 = api( 'astra-settings[below-header-section-1]' ).get();
						var section_2 = api( 'astra-settings[below-header-section-2]' ).get();

						if ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
							|| ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) {
							return true;
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[below-header-spacing-divider]',
						'astra-settings[below-header-spacing]',
					],
					callback: function( val ) {

						if ( 'below-header-layout-1' == val || 'below-header-layout-2' == val ) {
							return true;
						}

						return false;
					}
				}
			);

			ASTCustomizerToggles['astra-settings[below-header-section-1]'].push(
				{
					controls: [
						'astra-settings[below-header-menu-spacing]',
						'astra-settings[below-header-submenu-spacing]',
					],
					callback: function( section_1 ) {

						var val = api( 'astra-settings[below-header-layout]' ).get();
						var section_2 = api( 'astra-settings[below-header-section-2]' ).get();

						if ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
							|| ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) {
							return true;
						}

						return false;
					}
				}
			);

			ASTCustomizerToggles['astra-settings[below-header-section-2]'].push(
				{
					controls: [
						'astra-settings[below-header-menu-spacing]',
						'astra-settings[below-header-submenu-spacing]'
					],
					callback: function( section_2 ) {

						var val = api( 'astra-settings[below-header-layout]' ).get();
						var section_1 = api( 'astra-settings[below-header-section-1]' ).get();

						if ( ( 'below-header-layout-1' == val && ( 'menu' == section_1 || 'menu' == section_2 ) )
							|| ( 'below-header-layout-2' == val && 'menu' == section_1 ) ) {
							return true;
						}

						return false;
					}
				}
			);
		}


		/**
		 * Top Menu Spacer
		 */
		if( typeof ASTCustomizerToggles['astra-settings[above-header-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[above-header-layout]'].length > 0 ) {

			ASTCustomizerToggles['astra-settings[above-header-layout]'].push(
				{
					controls: [
						'astra-settings[above-header-menu-spacing]',
						'astra-settings[above-header-submenu-spacing]',
					],
					callback: function( val ) {

						var left_section = api( 'astra-settings[above-header-section-1]' ).get();
						var right_section = api( 'astra-settings[above-header-section-2]' ).get();

						if ( ( val == 'above-header-layout-2' && left_section == 'menu' ) ||
							( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) {

							return true;
						}

						return false;
					}
				},
				{
					controls: [
						'astra-settings[above-header-spacing-divider]',
						'astra-settings[above-header-spacing]',
					],
					callback: function( val ) {

						if ( val == 'above-header-layout-2' || val == 'above-header-layout-1' ) {
							return true;
						}

						return false;
					}
				}
			);

			ASTCustomizerToggles['astra-settings[above-header-section-1]'].push(
				{
					controls: [
						'astra-settings[above-header-menu-spacing]',
						'astra-settings[above-header-submenu-spacing]',
					],
					callback: function( left_section ) {

						var val = api( 'astra-settings[above-header-layout]' ).get();
						var right_section = api( 'astra-settings[above-header-section-2]' ).get();

						if ( ( val == 'above-header-layout-2' && left_section == 'menu' ) ||
							( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) {

							return true;
						}

						return false;
					}
				}
			);

			ASTCustomizerToggles['astra-settings[above-header-section-2]'].push(
				{
					controls: [
						'astra-settings[above-header-menu-spacing]',
						'astra-settings[above-header-submenu-spacing]',
					],
					callback: function( right_section ) {

						var val = api( 'astra-settings[above-header-layout]' ).get();
						var left_section = api( 'astra-settings[above-header-section-1]' ).get();

						if ( ( val == 'above-header-layout-2' && left_section == 'menu' ) ||
							( val == 'above-header-layout-1' && ( left_section == 'menu' || right_section == 'menu' ) ) ) {

							return true;
						}

						return false;
					}
				}
			);
		}

		/**
		 * Footer Spacer
		 */
		if( typeof ASTCustomizerToggles['astra-settings[footer-sml-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[footer-sml-layout]'].length > 0 ) {

			ASTCustomizerToggles['astra-settings[footer-sml-layout]'].push(
				{
					controls: [
						'astra-settings[footer-sml-spacing]',
						'astra-settings[footer-spacing-divider]',
					],
					callback: function( small_footer_layout ) {

						if ( 'disabled' != small_footer_layout ) {
							return true;
						}
						return false;
					}
				},
				{
					controls: [
						'astra-settings[footer-menu-spacing]',
					],
					callback: function( small_footer_layout ) {

						var left_section = api( 'astra-settings[footer-sml-section-1]' ).get();
						var right_section = api( 'astra-settings[footer-sml-section-2]' ).get();

						if ( 'disabled' != small_footer_layout && ( 'menu' == left_section || 'menu' == right_section ) ) {
							return true;
						}
						return false;
					}
				}
			);

			ASTCustomizerToggles['astra-settings[footer-sml-section-1]'].push(
				{
					controls: [
						'astra-settings[footer-menu-spacing]',
					],
					callback: function( left_section ) {

						var small_footer_layout = api( 'astra-settings[footer-sml-layout]' ).get();
						var right_section = api( 'astra-settings[footer-sml-section-2]' ).get();

						if ( 'disabled' != small_footer_layout && ( 'menu' == left_section || 'menu' == right_section ) ) {
							return true;
						}
						return false;
					}
				}
			);

			ASTCustomizerToggles['astra-settings[footer-sml-section-2]'].push(
				{
					controls: [
						'astra-settings[footer-menu-spacing]',
					],
					callback: function( right_section ) {

						var small_footer_layout = api( 'astra-settings[footer-sml-layout]' ).get();
						var left_section = api( 'astra-settings[footer-sml-section-1]' ).get();

						if ( 'disabled' != small_footer_layout && ( 'menu' == left_section || 'menu' == right_section ) ) {
							return true;
						}
						return false;
					}
				}
			);
		}

		/**
		 * Blog Pro pagination
		 */
		if( typeof ASTCustomizerToggles['astra-settings[blog-pagination]'] != 'undefined' && ASTCustomizerToggles['astra-settings[blog-pagination]'].length > 0 ) {
			
			ASTCustomizerToggles['astra-settings[blog-pagination]'].push(
				{
					controls: [
						'astra-settings[blog-post-pagination-spacing]',
					],
					callback: function( blog_pagination ) {

						if ( 'number' == blog_pagination ) {
							return true;
						}
						return false;
					}
				}
			);
		}

		/**
		 * Blog Pro Grid 
		 */
		if( typeof ASTCustomizerToggles['astra-settings[blog-layout]'] != 'undefined' && ASTCustomizerToggles['astra-settings[blog-layout]'].length > 0 ) {
			
			ASTCustomizerToggles['astra-settings[blog-layout]'].push(
				{
					controls: [
						'astra-settings[blog-post-outside-spacing]',
					],
					callback: function( blog_layout ) {

						var blog_between_space = api( 'astra-settings[blog-space-bet-posts]' ).get();
						var blog_grid = api( 'astra-settings[blog-grid]' ).get();

						if ( 'blog-layout-2' == blog_layout || 'blog-layout-3' == blog_layout ||  (  1 == blog_grid || ( 2 == blog_grid || 3 == blog_grid || 4 == blog_grid ) &&  blog_between_space ) ) {
							return true;
						}
						return false;
					}
				}
			);
		}

		if( typeof ASTCustomizerToggles['astra-settings[blog-grid]'] != 'undefined' && ASTCustomizerToggles['astra-settings[blog-grid]'].length > 0 ) {
			
			ASTCustomizerToggles['astra-settings[blog-grid]'].push(
				{
					controls: [
						'astra-settings[blog-post-outside-spacing]',
					],
					callback: function( blog_grid ) {

						var blog_between_space = api( 'astra-settings[blog-space-bet-posts]' ).get();
						var blog_layout = api( 'astra-settings[blog-layout]' ).get();

						if ( 'blog-layout-2' == blog_layout || 'blog-layout-3' == blog_layout ||  (  1 == blog_grid || ( 2 == blog_grid || 3 == blog_grid || 4 == blog_grid ) &&  blog_between_space ) ) {
							return true;
						}
						return false;
					}
				}
			);
		}

		ASTCustomizerToggles ['astra-settings[blog-space-bet-posts]'] = [
			{
				controls: [
					'astra-settings[blog-post-outside-spacing]',
				],
				callback: function( blog_between_space ) {
					var blog_grid = api( 'astra-settings[blog-grid]' ).get();
					var blog_layout = api( 'astra-settings[blog-layout]' ).get();

					if ( 'blog-layout-2' == blog_layout || 'blog-layout-3' == blog_layout || (  1 == blog_grid || ( 2 == blog_grid || 3 == blog_grid || 4 == blog_grid ) &&  blog_between_space ) ) {
						return true;
					}
					return false;
				}
			}
		];
	});

})( jQuery );
