<?php
	function tm_nav_search(){
	?>
		<div class="tm-nav-search" id="tm-nav-product-search">
			<div class="nav-search-toggler">
				<span class="search-icon"></span>	
			</div>
			<div class="nav-search-content"> 
				<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
				<label class="screen-reader-text" for="s"><?php _e( 'Search for:', 'woocommerce' ); ?></label>
				<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'woocommerce' ); ?>" />
				<button type="submit" class="search-submit" value=""><span class="icon"></span></button>
				<input type="hidden" name="post_type" value="product" />
				</form>
			<span class="close-button"></span>
			</div>
		</div>
	<?php
	}

	function tm_search_nav_enqueue_styles(){
        wp_enqueue_style(
            'tm-nav-search-style',
            TM_PLUGIN_URI . '/css/nav-search.css', 
            array()
        );
	}
	function tm_search_nav_enqueue_scripts(){
        wp_enqueue_script(
            'tm-nav-search-js',
            TM_PLUGIN_URI . '/js/nav-search.js',
            array(),
            null,
            true
        );
    }

    add_action('wp_enqueue_scripts', 'tm_search_nav_enqueue_scripts');   
	add_action( 'wp_enqueue_scripts', 'tm_search_nav_enqueue_styles' );
	add_action('wp_footer', 'tm_nav_search');
?>