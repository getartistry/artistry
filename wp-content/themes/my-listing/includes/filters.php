<?php
/**
 * Register Filter and Actions.
 */

class CASE27_Filters {

	protected $actions = [
		'woocommerce_login_failed',
        'tgmpa_register',
        'case27_footer',
        'after_switch_theme',
	];

	protected $filters = [
		'query_vars',
		'woocommerce_locate_template',
        'job_manager_geolocation_api_key',
        'get_the_archive_title',
        'loop_shop_columns',
        'woocommerce_output_related_products_args',
        'default_comments_on' => 'wp_insert_post_data',
        'woocommerce_show_page_title',
        'case27_pagetop_args',
        'case27_featured_service_content',
        'register_post_type_job_listing',
        'body_class',
        'author_override' => 'wp_dropdown_users',
	];

	public function __construct()
	{
		$this->add_actions();
		$this->add_filters();
	}

	public function register_action($action)
	{
		if (!in_array($action, $this->actions)) {
			$this->actions[] = $action;
		}
	}

	public function register_filter($filter)
	{
		if (!in_array($filter, $this->filters)) {
			$this->filters[] = $filter;
		}
	}

    /*
     * Register Filters.
     */
	public function add_filters()
	{
		foreach ($this->filters as $callback => $filter) {
			$callback = !is_numeric($callback) ? $callback : $filter;
            $priority = 10; $accepted_args = 1;

            if (is_array($filter)) {
                $_filter = $filter;

                $filter = $_filter['filter'];
                $callback = $_filter['callback'];
                $priority = $_filter['priority'];
                $accepted_args = $_filter['accepted_args'];
            }

			add_filter($filter, array($this, "filter_{$callback}"), $priority, $accepted_args);
		}
	}


    /*
     * Register Actions.
     */
	public function add_actions()
	{
		foreach ($this->actions as $callback => $action) {
			$callback = !is_numeric($callback) ? $callback : $action;
			add_action($action, array($this, "action_{$callback}"));
		}
	}


    /*
     * FILTER CALLBACK METHODS SHOULD BEGIN WITH 'filter_' AND THE FILTER NAME,
     * AS IT'S REGISTERED IN THE 'filters' ARRAY. IN CASES WHERE THE FILTER CONTAINS
     * CHARACTERS NOT ALLOWED IN PHP FUNCTION NAME, YOU CAN WRITE THE FILTER AS AN
     * ASSOCIATIVE ARRAY, WHERE THE KEY IS THE METHOD NAME, AND THE VALUE IS THE FILTER.
     *
     * ACTION CALLBACK METHODS SHOULD BEGIN WITH 'action_'.
     * SAME RULES AS FOR FILTERS APPLY.
     */

    public function filter_query_vars($vars) {
    	$vars[] = 'listing_type';
    	return $vars;
    }

    public function filter_woocommerce_locate_template($template)
    {
    	$_template_name = explode('/templates/', $template);
    	$template_name = array_pop($_template_name);
    	$template_path = CASE27_INTEGRATIONS_DIR . "/woocommerce/templates/{$template_name}";

    	if ( locate_template("includes/integrations/woocommerce/templates/{$template_name}") && file_exists($template_path) ) {
            // do_action("case27_woocommerce_template_{$template_name}_before")
			return $template_path;
            // do_action("case27_woocommerce_template_{$template_name}_after")
		}

    	return $template;
    }

    public function filter_body_class( $classes ) {
        if ( is_singular( 'job_listing' ) ) {
            global $post;

            if ( $post->_case27_listing_type ) {
                $classes[] = "single-listing";
                $classes[] = "type-{$post->_case27_listing_type}";
            }

            if ( $post->_package_id ) {
                $classes[] = "package-{$post->_package_id}";
            }
        }

        return $classes;
    }

    public function action_woocommerce_login_failed()
    {
    	if (isset($_POST['redirect']) && $_POST['redirect']) {
    		// Persist the login notices to the redirect page.
    		if ( ! WC()->session->has_session() ) {
    			WC()->session->set_customer_session_cookie(true);
    		}

    		wc_set_notices(wc_get_notices());
    		wp_redirect( $_POST['redirect'] );
    		exit;
    	}
    }

    public function action_after_switch_theme() {
        flush_rewrite_rules();
    }

    public function filter_job_manager_geolocation_api_key()
    {
        return c27()->get_setting('general_google_maps_api_key');
    }

    public function filter_get_the_archive_title($title)
    {
        if (!class_exists('WooCommerce')) return $title;

        if (is_woocommerce()) {
            $title = woocommerce_page_title(false);
        } elseif (is_cart() || is_checkout() || is_account_page() || is_page()) {
            $title = get_the_title();
        } elseif (is_home()) {
            $title = apply_filters( 'the_title', get_the_title( get_option( 'page_for_posts' ) ) );
        }

        return $title;
    }

    public function filter_loop_shop_columns() {
        return 3;
    }

    public function filter_woocommerce_output_related_products_args($args) {
        $args['columns'] = 3;
        $args['posts_per_page'] = 6;

        return $args;
    }

    /*
     * Register theme required plugins using TGM Plugin Activation library.
     */
    public function action_tgmpa_register()
    {
        // List of plugins to install.
        $plugins = [
            [
                'name' => __( 'MyListing Addons', 'my-listing' ),
                'slug' => 'my-listing-addons',
                'source' => 'my-listing-addons.zip',
                'version' => '1.0.38',
                'required' => true,
                'force_activation' => true,
            ],
            [
                'name' => __( 'Elementor', 'my-listing' ),
                'slug' => 'elementor',
                'required' => true, // If false, the plugin is only 'recommended' instead of required.
                'force_activation' => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            ],
            [
                'name' => __( 'WP Job Manager', 'my-listing' ),
                'slug' => 'wp-job-manager',
                'required' => true,
                'force_activation' => true,
            ],
            [
                'name' => __( 'WooCommerce', 'my-listing' ),
                'slug' => 'woocommerce',
                'required' => true,
                'force_activation' => true,
            ],
            [
                'name' => __( 'Contact Form 7', 'my-listing' ),
                'slug' => 'contact-form-7',
                'required' => true,
                'force_activation' => true,
            ],
            [
                'name' => __( 'All-in-One WP Migration', 'my-listing' ),
                'slug' => 'all-in-one-wp-migration',
                'required' => false,
                'force_activation' => false,
            ],
        ];

        // Array of configuration settings.
        $config = array(
            'id'           => 'case27',
            'default_path' => c27()->template_path('includes/plugins/'),
            'dismissable'  => true,
            'is_automatic' => true,
        );

        tgmpa( $plugins, $config );
    }

    public function filter_default_comments_on($data)
    {
        if( $data['post_type'] == 'job_listing' && $data['post_status'] == 'publish' ) {
            $data['comment_status'] = 'open';
        }

        return $data;
    }

    public function filter_woocommerce_show_page_title()
    {
        return false;
    }

    public function filter_case27_pagetop_args($pageTop)
    {
        if (defined('ELEMENTOR_VERSION') && $pageID = get_the_ID()) {
            $GLOBALS['c27_elementor_page'] = $page = \Elementor\PageSettings\Manager::get_page( $pageID );
            $pageTop['header']['show'] = false;
            $pageTop['title-bar']['show'] = false;

            if (!$page->get_settings('c27_hide_header')) {
                $pageTop['header']['show'] = true;
                $pageTop['header']['args'] = $page->get_settings('c27_customize_header') == 'yes' ? ([
                    'fixed'                   => $page->get_settings('c27_header_position'),
                    'style'                   => $page->get_settings('c27_header_style'),
                    'skin'                    => $page->get_settings('c27_header_skin'),
                    'menu_location'           => $page->get_settings('c27_header_menu_location'),
                    'background_color'        => $page->get_settings('c27_header_background'),
                    'border_color'            => $page->get_settings('c27_header_border_color'),
                    'show_search_form'        => $page->get_settings('c27_header_show_search_form'),
                    'show_call_to_action'     => $page->get_settings('c27_header_show_call_to_action'),
                    'is_edit_mode' => Elementor\Plugin::$instance->editor->is_edit_mode(),
                ]) : [];

                $pageTop['header']['args']['blend_to_next_section'] = $page->get_settings('c27_header_blend_to_next_section') === 'yes';

                if ( $page->get_settings( 'c27_show_title_bar' ) == 'yes' ) {
                    $pageTop['title-bar']['show'] = true;
                }
            }
        }

        $is_buddypress_profile = function_exists( 'bp_is_user' ) ? bp_is_user() : false;

        if (is_singular('job_listing') || is_page_template('templates/content-featured-image.php') || (is_singular('post') && has_post_thumbnail()) || $is_buddypress_profile ) {
            $pageTop['header']['show'] = true;
            $pageTop['title-bar']['show'] = false;
            $pageTop['header']['args']['style'] = c27()->get_setting('single_listing_header_style', 'default');
            $pageTop['header']['args']['skin'] = c27()->get_setting('single_listing_header_skin', 'dark');
            $pageTop['header']['args']['background_color'] = c27()->get_setting('single_listing_header_background_color', 'rgba(29, 29, 31, 0.95)');
            $pageTop['header']['args']['border_color'] = c27()->get_setting('single_listing_header_border_color', 'rgba(29, 29, 31, 0.95)');
            $pageTop['header']['args']['fixed'] = true;
            $pageTop['header']['args']['blend_to_next_section'] = true;
        }

        return $pageTop;
    }

    public function filter_case27_featured_service_content( $content )
    {
        if ( ! trim( $content ) ) {
            return $content;
        }

        $dom = new DOMDocument;
        $dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

        foreach ( ['h1', 'h2', 'h3'] as $tagSelector) {
            foreach ( $dom->getElementsByTagName( $tagSelector ) as $tag ) {
                $tag->setAttribute( 'class', $tag->getAttribute( 'class' ) . ' case27-primary-text' );
            }
        }

        return $dom->saveHTML();
    }

    public function filter_register_post_type_job_listing( $args )
    {
        $args['menu_position'] = 3;
        $args['supports'][] = 'author';

        return $args;
    }

    public function action_case27_footer()
    {
        ?>
        <style type="text/css">
            <?php echo $GLOBALS['case27_custom_styles'] ?>
        </style>
        <?php

        if ( c27()->get_setting('custom_code') ) {
            echo c27()->get_setting('custom_code');
        }
    }

    public function filter_author_override( $output ) {
        global $post, $user_ID;

        if ( empty( $post ) ) return $output;

        // Return if this isn't a listing.
        if ( $post->post_type != 'job_listing' ) return $output;

        // return if this isn't the theme author override dropdown
        if (!preg_match('/post_author_override/', $output)) return $output;

        // return if we've already replaced the list (end recursion)
        if (preg_match ('/post_author_override_replaced/', $output)) return $output;

        // replacement call to wp_dropdown_users
        $output = wp_dropdown_users(array(
            'echo' => 0,
            'name' => 'post_author_override_replaced',
            'selected' => empty($post->ID) ? $user_ID : $post->post_author,
            'include_selected' => true
        ));

        // put the original name back
        $output = preg_replace('/post_author_override_replaced/', 'post_author_override', $output);

        return $output;
    }
}

new CASE27_Filters;
