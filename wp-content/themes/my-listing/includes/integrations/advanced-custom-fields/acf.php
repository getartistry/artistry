<?php

namespace MyListing;

class ACF {
	use \CASE27\Traits\Instantiatable;

	public function __construct() {
		add_filter( 'acf/settings/path',       [ $this, 'settings_path' ] );
		add_filter( 'acf/settings/dir',        [ $this, 'settings_dir' ] );
		add_filter( 'acf/settings/save_json',  [ $this, 'save_json' ] );
		add_filter( 'acf/settings/load_json',  [ $this, 'load_json' ] );
		add_filter( 'acf/settings/show_admin', [ $this, 'show_admin' ], 30 );

		require_once CASE27_INTEGRATIONS_DIR . '/advanced-custom-fields/acf-icon-picker/acf-icon_picker.php';

		$this->add_options_page();
	}

	public function settings_path( $path ) {
    	return CASE27_INTEGRATIONS_DIR . '/advanced-custom-fields/plugin/';
	}

	public function settings_dir( $dir ) {
    	return get_template_directory_uri() . '/includes/integrations/advanced-custom-fields/plugin/';
	}

	public function save_json( $path ) {
    	return CASE27_INTEGRATIONS_DIR . '/advanced-custom-fields/acf-json';
	}

	public function load_json( $paths ) {
		// Remove original path.
    	unset($paths[0]);
    	$paths[] = CASE27_INTEGRATIONS_DIR . '/advanced-custom-fields/acf-json';
	    return $paths;
	}

    public function show_admin() {
        return CASE27_ENV === 'dev';
    }

	public function add_options_page() {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return false;
		}

		acf_add_options_page( [
			'page_title' 	=> __('Theme Options', 'my-listing'),
			'menu_title'	=> __('Theme Options', 'my-listing'),
			'menu_slug' 	=> 'theme-general-settings',
			'capability'	=> 'edit_posts',
			'redirect'		=> false,
		] );

		acf_add_options_page( [
			'page_title' 	=> __('Integrations', 'my-listing'),
			'menu_title'	=> __('Integrations', 'my-listing'),
			'menu_slug' 	=> 'theme-integration-settings',
			'capability'	=> 'edit_posts',
			'redirect'		=> false,
		] );
	}
}

ACF::instance();