<?php
/**
 * Register and Enqueue Theme Assets.
 */

class CASE27_Assets {

	protected static $_instance = null;

	public static function instance()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	protected $styles, $scripts;

	public function __construct()
	{
		// Enqueue Scripts and Styles
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_styles') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_icons') );
	}


	/*
     * Enqueue theme scripts.
	 */
	public function enqueue_scripts()
	{
		global $wp_query;

		// URL Scripts.
		wp_enqueue_script('c27-google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . c27()->get_setting('general_google_maps_api_key') . '&libraries=places&v=3', [], null, true);
		wp_enqueue_script('jquery-ui-sortable');

		if (CASE27_ENV === 'dev') {

			// Vendor Scripts.
			wp_enqueue_script('c27-bootstrap', c27()->template_uri("assets/scripts/vendor/bootstrap.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-jquery-ui', c27()->template_uri("assets/scripts/vendor/jquery-ui.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-jquery.ui.touch-punch', c27()->template_uri("assets/scripts/vendor/jquery.ui.touch-punch.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-jquery.easeScroll', c27()->template_uri("assets/scripts/vendor/jquery.easeScroll.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-jquery.repeater', c27()->template_uri("assets/scripts/vendor/jquery.repeater.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-photoswipe', c27()->template_uri("assets/scripts/vendor/photoswipe.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-photoswipe-ui-default', c27()->template_uri("assets/scripts/vendor/photoswipe-ui-default.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-moment', c27()->template_uri("assets/scripts/vendor/moment.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-daterangepicker', c27()->template_uri("assets/scripts/vendor/daterangepicker.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-isotope', c27()->template_uri("assets/scripts/vendor/isotope.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-scrollbar', c27()->template_uri("assets/scripts/vendor/scrollbar.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-owl.carousel', c27()->template_uri("assets/scripts/vendor/owl.carousel.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-parallax', c27()->template_uri("assets/scripts/vendor/parallax.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-select-custom', c27()->template_uri("assets/scripts/vendor/select-custom.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-vue', c27()->template_uri("assets/scripts/vendor/vue.js"), array(), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-vue-resource', c27()->template_uri("assets/scripts/vendor/vue-resource.js"), array(), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-lodash-debounce',c27()->template_uri("assets/scripts/vendor/lodash.debounce.js"), array(), CASE27_THEME_VERSION, true);

			// Maps scripts.
			wp_enqueue_script('c27-maps', c27()->template_uri("assets/scripts/maps/maps.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-infobox', c27()->template_uri("assets/scripts/maps/infobox.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-custom-marker', c27()->template_uri("assets/scripts/maps/custom-marker.js"), array('jquery'), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-markerclusterer', c27()->template_uri("assets/scripts/maps/markerclusterer.js"), array('jquery'), CASE27_THEME_VERSION, true);

			// General Scripts.
			wp_enqueue_script('c27-main', c27()->template_uri("assets/scripts/main.js"), array(), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-tmp', c27()->template_uri("assets/scripts/tmp.js"), array(), CASE27_THEME_VERSION, true);

			// Vue scripts.
			wp_enqueue_script('c27-vue-site', c27()->template_uri("assets/scripts/vue/site.js"), array(), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-vue-explore', c27()->template_uri("assets/scripts/vue/explore.js"), array(), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-vue-single', c27()->template_uri("assets/scripts/vue/single.js"), array(), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-vue-header-search', c27()->template_uri("assets/scripts/vue/header-search.js"), array(), CASE27_THEME_VERSION, true);
			wp_enqueue_script('c27-vue-quick-view', c27()->template_uri("assets/scripts/vue/quick-view.js"), array(), CASE27_THEME_VERSION, true);

		} else {

			// Minified single scripts file for production.
			wp_enqueue_script('c27-main', c27()->template_uri( 'assets/dist/scripts/frontend.' . CASE27_THEME_VERSION . '.js' ), ['jquery'], CASE27_THEME_VERSION, true);

		}

		// Comment reply script
		if ( is_singular() && comments_open() && get_option('thread_comments') ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Localize data.
		wp_localize_script( 'c27-main', 'CASE27', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'template_uri' => c27()->template_uri(),
			'posts_per_page' => (int) get_field('portfolio_projects_per_page') ? : get_option('posts_per_page'),
			'query_vars' => json_encode( $wp_query->query ),
			'ajax_nonce' => wp_create_nonce('c27_ajax_nonce'),
			'smooth_scroll_enabled' => c27()->get_setting('general_enable_smooth_scrolling', false),
			'autocomplete' => array(
				'types' => (array) c27()->get_setting( 'general_autocomplete_types', 'geocode' ),
				'locations' => array_filter( (array) c27()->get_setting( 'general_autocomplete_locations', [] ) ),
			),
			'l10n' => [
				'selectOption' => _x( 'Select an option', 'Dropdown placeholder', 'my-listing' ),
				'errorLoading' => _x( 'The results could not be loaded.', 'Dropdown could not load results', 'my-listing' ),
				'loadingMore'  => _x( 'Loading more results…', 'Dropdown loading more results', 'my-listing' ),
				'noResults'    => _x( 'No results found', 'Dropdown no results found', 'my-listing' ),
				'searching'    => _x( 'Searching…', 'Dropdown searching', 'my-listing' ),
				'datepicker'   => mylisting()->strings()->get_datepicker_locale(),
			],
		));

		// Custom JavaScript
		wp_add_inline_script( 'c27-main', c27()->get_setting('custom_js') );

		// Disable WooCommerce pretty photo plugin.
		if ( class_exists('WooCommerce') ) {
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
		}
	}


	public function enqueue_icons()
	{
		if (CASE27_ENV === 'dev') {
			wp_enqueue_style('c27-material-icons-classes', c27()->template_uri('assets/styles/vendor/material-icons-classes.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-icon-font', c27()->template_uri('assets/styles/vendor/icon-font.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-font-awesome', c27()->template_uri('assets/styles/vendor/font-awesome.css'), [], CASE27_THEME_VERSION);
		} else {
			wp_enqueue_style('c27-icons', c27()->template_uri('assets/dist/styles/icons.' . CASE27_THEME_VERSION . '.css'), [], CASE27_THEME_VERSION);
		}
	}


	/**
	 * Enqueue Theme Styles.
	 */
	public function enqueue_styles()
	{
		// URL Styles.
		wp_enqueue_style( 'c27-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons' );
		wp_enqueue_style( 'c27-google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700' );

		if (CASE27_ENV === 'dev') {

			// Vendor Styles.
			wp_enqueue_style('c27-bootstrap', c27()->template_uri('assets/styles/vendor/bootstrap.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-daterangepicker', c27()->template_uri('assets/styles/vendor/daterangepicker.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-jquery-ui', c27()->template_uri('assets/styles/vendor/jquery-ui.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-scrollbar', c27()->template_uri('assets/styles/vendor/scrollbar.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-photoswipe', c27()->template_uri('assets/styles/vendor/photoswipe.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-photoswipe-skin', c27()->template_uri('assets/styles/vendor/photoswipe-skin.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-owl.carousel', c27()->template_uri('assets/styles/vendor/owl.carousel.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-select2', c27()->template_uri('assets/styles/vendor/select2.css'), [], CASE27_THEME_VERSION);

			// General Styles.
			wp_enqueue_style('c27-style', c27()->template_uri('assets/styles/style.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-responsive', c27()->template_uri('assets/styles/responsive.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-loaders', c27()->template_uri('assets/styles/loaders.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-custom', c27()->template_uri('assets/styles/custom.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-tmp', c27()->template_uri('assets/styles/tmp.css'), [], CASE27_THEME_VERSION);
			wp_enqueue_style('c27-tmp-2', c27()->template_uri('assets/styles/tmp-2.css'), [], CASE27_THEME_VERSION);

			// TODO: Load conditionally.
			wp_enqueue_style('c27-skin-material', c27()->template_uri('assets/styles/skins/material.css'), [], CASE27_THEME_VERSION);

		} else {

			// Minfied, single styles file for production.
			wp_enqueue_style( 'c27-style', c27()->template_uri('assets/dist/styles/frontend.' . CASE27_THEME_VERSION . '.css'), [], CASE27_THEME_VERSION );

		}

		// Default Styles
		wp_enqueue_style( 'theme-styles-default', c27()->template_uri('style.css') );

		// Custom Styles.
		wp_add_inline_style( 'theme-styles-default', $this->custom_styles());
	}


    /*
     * Get custom styles.
     */
	public function custom_styles()
	{

		ob_start();

		echo c27()->get_setting('custom_css');

		require_once CASE27_ASSETS_DIR . '/dynamic/frontend.css.php';

		return str_replace( "\n", '', ob_get_clean() );
	}
}

CASE27_Assets::instance();
