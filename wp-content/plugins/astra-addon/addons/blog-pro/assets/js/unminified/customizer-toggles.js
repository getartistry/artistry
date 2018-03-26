/**
 * Showing and Hiding controls of Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

( function( $ ) {

	ASTControlTrigger.addHook( 'astra-toggle-control', function( argument, api ) {

		// Blog Layout
		ASTCustomizerToggles ['astra-settings[blog-layout]'] = [
			{
				controls: [
					'astra-settings[blog-masonry]',
					'astra-settings[first-post-full-width]',
				],
				callback: function( blog_layout ) {

					var blog_grid        = api( 'astra-settings[blog-grid]' ).get();
					var blog_grid_layout = api( 'astra-settings[blog-grid-layout]' ).get();

					if ( ( 'blog-layout-1' == blog_layout && 1 != blog_grid ) 
						 || ( 'blog-layout-1' != blog_layout && 1 != blog_grid_layout ) ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[blog-grid]',
					'astra-settings[blog-post-structure]',
					'astra-settings[blog-featured-image-padding]',
				],
				callback: function( blog_layout ) {

					if ( 'blog-layout-1' == blog_layout ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[blog-grid-layout]'
				],
				callback: function( blog_layout ) {

					if ( 'blog-layout-1' != blog_layout ) {
						return true;
					}
					return false;
				}
			}
		];

		ASTCustomizerToggles ['astra-settings[blog-grid]'] = [
			{
				controls: [
					'astra-settings[blog-masonry]',
					'astra-settings[first-post-full-width]',
				],
				callback: function( blog_grid ) {

					var blog_layout = api( 'astra-settings[blog-layout]' ).get();
					var blog_grid_layout = api( 'astra-settings[blog-grid-layout]' ).get();

					if ( ( 'blog-layout-1' == blog_layout && 1 != blog_grid ) 
						 || ( 'blog-layout-1' != blog_layout && 1 != blog_grid_layout ) ) {
						return true;
					}
					return false;
				}
			}
		];

		ASTCustomizerToggles ['astra-settings[blog-grid-layout]'] = [
			{
				controls: [
					'astra-settings[blog-masonry]',
					'astra-settings[first-post-full-width]',
				],
				callback: function( blog_grid_layout ) {

					var blog_layout = api( 'astra-settings[blog-layout]' ).get();
					var blog_grid = api( 'astra-settings[blog-grid]' ).get();

					if ( ( 'blog-layout-1' == blog_layout && 1 != blog_grid ) 
						 || ( 'blog-layout-1' != blog_layout && 1 != blog_grid_layout ) ) {
						return true;
					}
					return false;
				}
			}
		];

		// Blog Content
		ASTCustomizerToggles ['astra-settings[blog-post-content]'] = [
			{
				controls: [
					'astra-settings[blog-excerpt-count]',
					'astra-settings[blog-read-more-text]',
					'astra-settings[blog-read-more-as-button]',
				],
				callback: function( blog_content ) {

					if ( 'excerpt' == blog_content ) {
						return true;
					}
					return false;
				}
			}
		];

		// Blog Content
		ASTCustomizerToggles ['astra-settings[blog-date-box]'] = [
			{
				controls: [
					'astra-settings[blog-date-box-style]'
				],
				callback: function( date_box ) {

					if ( date_box ) {
						return true;
					}
					return false;
				}
			}
		];
		
		

		// Blog Pagination
		ASTCustomizerToggles ['astra-settings[blog-pagination]'] = [
			{
				controls: [
					'astra-settings[blog-pagination-style]',
				],
				callback: function( blog_pagination ) {

					if ( 'number' == blog_pagination ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[blog-infinite-scroll-event]',
				],
				callback: function( blog_pagination ) {

					if ( 'infinite' == blog_pagination ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[blog-load-more-text]',
				],
				callback: function( blog_pagination ) {

					var scroll_event = api( 'astra-settings[blog-infinite-scroll-event]' ).get();

					if ( 'infinite' == blog_pagination && 'click' == scroll_event ) {
						return true;
					}
					return false;
				}
			},
			{
				controls: [
					'astra-settings[divider-section-archive-typo-pagination]',
					'astra-settings[text-transform-post-pagination]',
					'astra-settings[font-size-post-pagination]',
				],
				callback: function( blog_pagination ) {

					if ( 'infinite' != blog_pagination ) {
						return true;
					}
					return false;
				}
			}
		];

		ASTCustomizerToggles ['astra-settings[blog-infinite-scroll-event]'] = [
			{
				controls: [
					'astra-settings[blog-load-more-text]',
				],
				callback: function( scroll_event ) {

					var blog_pagination = api( 'astra-settings[blog-pagination]' ).get();

					if ( 'infinite' == blog_pagination && 'click' == scroll_event ) {
						return true;
					}

					return false;
				}
			}
		];
	});
})( jQuery );
