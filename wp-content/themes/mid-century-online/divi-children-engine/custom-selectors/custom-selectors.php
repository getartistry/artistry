<?php
/**
 * Custom Selectors functions
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


/**
 * Loads styles and js for added 'Divi Children Custom Selectors' metabox
 */
 
function load_custom_metabox_style() {
	wp_register_style( 'custom_metabox_style', get_stylesheet_directory_uri() . '/divi-children-engine/custom-selectors/css/custom-metabox.css' );
	wp_enqueue_style( 'custom_metabox_style' );
	wp_enqueue_script( 'custom_selectors_js', get_stylesheet_directory_uri() . '/divi-children-engine/custom-selectors/js/custom-metabox.js', array( 'jquery' ) );
	wp_localize_script( 'custom_selectors_js', 'dce_cs_vars', array(
			'dce_cs_add_nonce'		=> wp_create_nonce( 'cs-add-nonce' ),
			'dce_cs_rename_nonce'	=> wp_create_nonce( 'cs-rename-nonce' ),
			'dce_cs_remove_nonce'	=> wp_create_nonce( 'cs-remove-nonce' ),
	) );
}
add_action( 'admin_enqueue_scripts', 'load_custom_metabox_style' );


/**
 * Adds 'Divi Children Custom Selectors' metabox for pages and projects
 */

function dce_add_custom_selectors_metabox() {
	$post_types = apply_filters( 'et_pb_builder_post_types', array(
		'page',
		'project',
	) );
	foreach ( $post_types as $post_type ){
		add_meta_box( 'custom_selectors', 'Divi Children Custom Selectors', 'dce_custom_selectors_metabox', $post_type, 'side', 'default' );
	}
}


/**
 * Hooks the new metabox
 */

function dce_hook_custom_selectors_metabox() {
	add_action( 'add_meta_boxes', 'dce_add_custom_selectors_metabox' );
}
if ( dce_enable( 'custom_selectors' ) ) {
	add_action( 'after_setup_theme', 'dce_hook_custom_selectors_metabox' );
}


/**
 * Creates the 'Divi Children Custom Selectors' metabox
 */

function dce_custom_selectors_metabox() {
	$custom_selectors_classes = array (
		__( 'Custom Full Width Headers', 'divi-children-engine' )	=>	'custom_fullwidth_header',
		// __( 'Custom Sliders', 'divi-children-engine' )				=>	'custom_slider',
		__( 'Custom Call To Actions', 'divi-children-engine' )		=>	'custom_cta',
	);
	$custom_selectors_ids = array (
		__( 'Custom Sidebars', 'divi-children-engine' )	=>	'custom_sidebar_module',
	);
	ob_start();
	?>
	<div id="custom_classes">	
		<h3 class="title_trigger"><span><?php _e( 'Custom Module Classes', 'divi-children-engine' ); ?></span><div class="opentrigger" title="Click to toggle"></div><div class="closedtrigger" title="Click to toggle"></div></h3>
		<div class="inside">
			<p><em><?php _e( 'Copy any existing Custom Module Class (or create a new one) and paste it on the "CSS Class" field of the Divi module.', 'divi-children-engine' ); ?></em></p>	
			<?php
			dce_custom_selector_box( $custom_selectors_classes, 'class' );
			?>
		</div>
	</div>

	<div id="custom_ids">	
		<h3 class="title_trigger"><span><?php _e( 'Custom Module IDs', 'divi-children-engine' ); ?></span><div class="opentrigger" title="Click to toggle"></div><div class="closedtrigger" title="Click to toggle"></div></h3>
		<div class="inside">
			<p><em><?php _e( 'Copy any existing Custom Module ID (or create a new one) and paste it on the "CSS ID" field of the Divi module.', 'divi-children-engine' ); ?></em></p>	
			<?php
			dce_custom_selector_box( $custom_selectors_ids, 'id' );
			?>
		</div>
	</div>
	<?php

	echo ob_get_clean();

}


/**
 * Creates the individual colored box for each Custom Class or ID.
 */
 
function dce_custom_selector_box( $selectors, $selector ) {
	if ( $selector == 'class' ) {
			$remove_msg = __( 'Remove this class', 'divi-children-engine' );
			$no_selector_msg = __( 'No custom classes created yet.', 'divi-children-engine' );
			$add_selector_msg = __( 'Add a New Class', 'divi-children-engine' );
			$added_selector_msg = __( 'New class successfully created.', 'divi-children-engine' );
		} elseif ( $selector == 'id' ) {
			$remove_msg = __( 'Remove this ID', 'divi-children-engine' );
			$no_selector_msg = __( 'No custom IDs created yet.', 'divi-children-engine' );
			$add_selector_msg = __( 'Add a New ID', 'divi-children-engine' );
			$added_selector_msg = __( 'New ID successfully created.', 'divi-children-engine' );
	}
	foreach ( $selectors as $key => $value ) {
		$custom_selectors = dce_get_custom_selectors( $value, 'raw' );
		$next_selector = dce_get_next_custom_selector( $value );
		?>
		<div id="<?php echo $value;?>">
			<h4><?php echo $key;?></h4>
			<ul>
				<?php
				if ( $custom_selectors ) {		
						foreach ( $custom_selectors as $custom_selector ) {
							$alias = get_theme_mod( $custom_selector.'_alias' );
							if ( $alias ) {
									$display_custom_selector = $alias;
								} else {
									$display_custom_selector = $custom_selector;
							}								
							?>
							<div id="<?php echo $custom_selector; ?>">
								<li id="<?php echo $custom_selector; ?>">
									<input class="custom_selector" type="text" style="font-size:13px; background-color:#f8f8ff;" size="23" value="<?php echo $display_custom_selector; ?>" />
									<span class="custom_selector_edit"></span>
								</li>
								<li class="rename_selector_text">
									<input class="new_selector_name" type="text" style="font-size:13px; color:#fff; background-color:#777;" size="23" value="<?php echo $display_custom_selector; ?>" />
									<div class="rename_button"><span class="rename_done"></span></div>
								</li>
								<li class="remove_selector">
									<span><?php echo $remove_msg; ?></span>
								</li>
							</div>
							<?php
						}
					} else {
						?>
						<li class="no_custom_selectors"><?php echo $no_selector_msg; ?></li>
						<?php
				}
				?>
				<div id="<?php echo $next_selector; ?>">
					<li id="<?php echo $next_selector; ?>"><input class="next_selector custom_selector" type="text" style="font-size:13px; background-color:#f8f8ff;" size="23" value="<?php echo $next_selector; ?>" /><span class="custom_selector_edit next_selector_rename"></span></li>
					<li class="rename_selector_text"><input class="next_selector new_selector_name" type="text" style="font-size:13px; color:#fff; background-color:#777;" size="23" value="<?php echo $next_selector; ?>" /><div class="rename_button"><span class="rename_done"></span></div></li>
				</div>						
			</ul>
			<div class="add_custom_selector"><?php echo $add_selector_msg; ?></div>
			<div class="new_selector_created"><?php echo $added_selector_msg; ?></div>
		</div>
		<?php
	}
}


/**
 * Prepare next Custom Selector of a type, reusing any previously removed selector if available.
 */
 
function dce_get_next_custom_selector( $type ) {
	$custom_selectors_all = dce_get_custom_selectors( $type, 'raw', 'all' );
	if ( $custom_selectors_all ) {
			foreach ( $custom_selectors_all as $value ) {
				if ( get_theme_mod( $value ) == 'off' ) {
					$next_selector = $value;
					$custom_selector_alias = $next_selector.'_alias';
					remove_theme_mod( $custom_selector_alias );
					return $next_selector;
				}
			}
			$next_selector_count = ( count( $custom_selectors_all ) ) + 1;
		} else {
			$next_selector_count = 1;
	}
	$next_selector = $type . '_' . $next_selector_count;
	return $next_selector;
}


/**
 * Add Custom Selector ajax callback function
 */
 
function custom_selectors_add_callback() {
	if( !isset( $_POST['dce_cs_add_nonce'] ) OR !wp_verify_nonce($_POST['dce_cs_add_nonce'], 'cs-add-nonce') ) {
		die('Not authorized');	
	}
	$custom_selector = $_POST['custom_selector'];
	set_theme_mod( $custom_selector, 'on' );
    exit();
}
add_action( 'wp_ajax_custom_selectors_add', 'custom_selectors_add_callback' );


/**
 * Rename Custom Selector ajax callback function
 */

function custom_selectors_rename_callback() {
	if( !isset( $_POST['dce_cs_rename_nonce'] ) OR ! wp_verify_nonce( $_POST['dce_cs_rename_nonce'], 'cs-rename-nonce' ) ) {
		die('Not authorized');	
	}
    $custom_selector = $_POST['custom_selector'];
	$custom_selector_alias = $custom_selector.'_alias';
	$alias = $_POST['new_name'];
	set_theme_mod( $custom_selector_alias, $alias );
    exit();
}
add_action( 'wp_ajax_custom_selectors_rename', 'custom_selectors_rename_callback' );


/**
 * Remove Custom Selector ajax callback function
 */

function custom_selector_remove_callback() {
	if( !isset( $_POST['dce_cs_remove_nonce'] ) OR ! wp_verify_nonce( $_POST['dce_cs_remove_nonce'], 'cs-remove-nonce' ) ) {
		die('Not authorized');	
	}
    $custom_selector = $_POST['custom_selector'];
	$custom_selector_alias = $custom_selector.'_alias';
	remove_theme_mod( $custom_selector_alias );
	set_theme_mod( $custom_selector, 'off' );	
    exit();
}
add_action( 'wp_ajax_custom_selector_remove', 'custom_selector_remove_callback' );
