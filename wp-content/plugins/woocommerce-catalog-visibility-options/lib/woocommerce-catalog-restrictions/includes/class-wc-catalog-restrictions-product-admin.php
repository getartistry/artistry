<?php
/* Fire our meta box setup function on the post editor screen. */

class WC_Catalog_Restrictions_Product_Admin {

	public static $instance;

	public static function instance() {
		if ( !self::$instance ) {
			self::$instance = new WC_Catalog_Restrictions_Product_Admin();
		}

		return self::$instance;
	}

	/* Meta box setup function. */

	public function __construct() {
		add_action( 'load-post.php', array($this, 'post_meta_boxes_setup') );
		add_action( 'load-post-new.php', array($this, 'post_meta_boxes_setup') );
	}

	function post_meta_boxes_setup() {
		global $woocommerce, $wc_catalog_restrictions;
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'wc-product-restrictions-admin', $wc_catalog_restrictions->plugin_url() . 'assets/css/admin.css' );
		wp_enqueue_script( 'wc-product-restrictions-admin', $wc_catalog_restrictions->plugin_url() . 'assets/js/admin.js', array('jquery') );
		
		/* Add meta boxes on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', array($this, 'add_post_meta_boxes') );

		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', array($this, 'save_meta'), 10, 2 );
	}

	/* Create one or more meta boxes to be displayed on the post editor screen. */

	function add_post_meta_boxes() {

		add_meta_box(
			'wc_catalog_restrictions', // Unique ID
			esc_html__( 'Product Restrictions', 'wc_catalog_restrictions' ), // Title
			array($this, 'meta_box'), // Callback function
			'product', // Admin page (or post type)
			'normal', // Context
			'high'     // Priority
		);
	}

	/* Display the post meta box. */

	function meta_box( $object, $box ) {
		global $woocommerce, $wc_catalog_restrictions, $wp_roles;
		if ( !isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$all_roles = $wp_roles->roles;
		$current_restrictions = get_post_meta( $object->ID, '_wc_restrictions_allowed', false );
		if ( !$current_restrictions ) {
			$current_restrictions = array();
		}

		$current_purchase_restrictions = get_post_meta( $object->ID, '_wc_restrictions_purchase_roles', true );
		if ( !$current_purchase_restrictions ) {
			$current_purchase_restrictions = array();
		}

		$current_price_restrictions = get_post_meta( $object->ID, '_wc_restrictions_price_roles', true );
		if ( !$current_price_restrictions ) {
			$current_price_restrictions = array();
		}
		?>

		<?php wp_nonce_field( basename( __FILE__ ), 'wc_restrictions_nonce' ); ?>

		<div class="">

			<?php if ( $wc_catalog_restrictions->get_setting( '_wc_restrictions_locations_enabled', 'no' ) == 'yes' ) : ?>
				<div class="options_group">
					<?php
					woocommerce_wp_select( array('id' => '_wc_restrictions_location', 'label' => __( 'What locations is this product enabled for?', 'wc_catalog_restrictions' ), 'options' => array(
						'inherit' => __( 'Use Category Settings', 'wc_catalog_restrictions' ),
						'public' => __( 'All Locations', 'wc_catalog_restrictions' ),
						'restricted' => __( 'Specific Locations', 'wc_catalog_restrictions' )
					    ), 'std' => 'inherit', 'desc_tip' => true, 'description' => __( 'Choose if you would like to limit this product to specific locations.', 'wc_catalog_restricitons' ))
					);
					?>

					<div id="wc_catalog_restrictions_locations_container" class="wc_restrictions_options_panel" style="<?php echo (get_post_meta( $object->ID, '_wc_restrictions_location', true ) == 'restricted' ? 'display:block;' : 'display:none;'); ?>">
						<?php
						$current_locations = get_post_meta( $object->ID, '_wc_restrictions_locations', false );
						if ( !$current_locations ) {
							$current_locations = array();
						}
						?>
						<label for="wc_restrictions_locations"><?php _e( 'Target Locations', 'wc_catalog_restrictions' ); ?></label><br />
						<select name="wc_restrictions_locations[]" class="" multiple="multiple" data-placeholder="<?php _e( 'Search for a country&hellip;', 'wc_catalog_restrictions' ); ?>">
							<?php woocommerce_catalog_restrictions_country_multiselect_options( $current_locations ); ?>
							?>
						</select> <img style="width:16px;height:16px;" class="help_tip" data-tip='<?php _e( 'Choose locations for this product.  Only users who select a matching location will be able to view and purchase this product.', 'wc_catalog_restrictions' ) ?>' src="<?php echo $woocommerce->plugin_url(); ?>/assets/images/help.png" />
					</div>
				</div>
			<?php endif; ?>


			<?php
			woocommerce_wp_select( array('id' => '_wc_restrictions', 'label' => __( 'Who can view this product', 'wc_catalog_restrictions' ), 'options' => array(
				'inherit' => __( 'Use Category Settings', 'wc_catalog_restrictions' ),
				'public' => __( 'Everyone', 'wc_catalog_restrictions' ),
				'restricted' => __( 'Specific Roles', 'wc_catalog_restrictions' )
			    ), 'std' => 'inherit', 'desc_tip' => true, 'description' => __( 'If you would like to only show this product to users who are in certian roles select "Specific Roles"', 'wc_catalog_restrictions' )
			) );
			?>


			<div id="wc_catalog_restrictions_roles_container" class="wc_restrictions_options_panel" style="<?php echo (get_post_meta( $object->ID, '_wc_restrictions', true ) == 'restricted' ? 'display:block;' : 'display:none;'); ?>">
				<p><?php _e( "Choose the roles that can view this product", 'wc_catalog_restrictions' ); ?></p>
				<table style="">
					<?php $chunks = array_chunk( $all_roles, ceil( count( $all_roles ) / 3 ), true ); ?>
					<?php foreach ( $chunks as $chunk ) : ?>

						<?php foreach ( $chunk as $role_id => $role ) : ?>
							<?php $role_checked = in_array( $role_id, $current_restrictions ) ? 'checked="checked"' : ''; ?>
							<tr><td class="list-column">  
									<label for="role_<?php echo esc_attr( $role_id ); ?>" class="selectit">
										<input <?php echo $role_checked; ?> type="checkbox" id="role_<?php echo esc_attr( $role_id ); ?>" name="wc_restrictions_allowed[]" value="<?php echo esc_attr( $role_id ); ?>" /><?php echo $role['name']; ?>
									</label>
								</td></tr>
						<?php endforeach; ?>

					<?php endforeach; ?>
				</table>
			</div>
			<div class="clearfix"></div>

			<?php
			woocommerce_wp_select( array('id' => '_wc_restrictions_purchase', 'label' => __( 'Who can purchase this product', 'wc_catalog_restrictions' ), 'options' => array(
				'inherit' => __( 'Use Category Settings', 'wc_catalog_restrictions' ),
				'public' => __( 'Everyone', 'wc_catalog_restrictions' ),
				'restricted' => __( 'Specific Roles', 'wc_catalog_restrictions' )
			    ), 'std' => 'inherit', 'desc_tip' => true, 'description' => __( 'If you would like to only specific users who are in certian roles select "Specific Roles".  Select "Everyone" to override category settings.' )
			) );
			?>

			<div id="wc_catalog_restrictions_purchase_roles_container" class="wc_restrictions_options_panel" style="<?php echo (get_post_meta( $object->ID, '_wc_restrictions_purchase', true ) == 'restricted' ? 'display:block;' : 'display:none;'); ?>">
				<label><?php _e( "Choose the roles that can purchase this product", 'wc_catalog_restrictions' ); ?></label><br />
				<div class="clearfix"></div>
				<?php $chunks = array_chunk( $all_roles, ceil( count( $all_roles ) / 3 ), true ); ?>
				<?php foreach ( $chunks as $chunk ) : ?>
					<ul class="list-column">        
						<?php foreach ( $chunk as $role_id => $role ) : ?>
							<?php $role_checked = in_array( $role_id, $current_purchase_restrictions ) ? 'checked="checked"' : ''; ?>
							<li>
								<label for="_wc_restrictions_purchase_roles_<?php echo esc_attr( $role_id ); ?>" class="selectit">
									<input <?php echo $role_checked; ?> type="checkbox" id="purchase_role_<?php echo esc_attr( $role_id ); ?>" name="wc_restrictions_purchase_roles[]" value="<?php echo esc_attr( $role_id ); ?>" /><?php echo $role['name']; ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endforeach; ?>
			</div>
			<div class="clearfix"></div>


			<?php
			woocommerce_wp_select( array('id' => '_wc_restrictions_price', 'label' => __( 'Who can view prices', 'wc_catalog_restrictions' ), 'options' => array(
				'inherit' => __( 'Use Category Settings', 'wc_catalog_restrictions' ),
				'public' => __( 'Everyone', 'wc_catalog_restrictions' ),
				'restricted' => __( 'Specific Roles', 'wc_catalog_restrictions' )
			    ), 'std' => 'inherit', 'desc_tip' => true, 'description' => __( 'If you would like to only specific users who are in certian roles select "Specific Roles".  Select "Everyone" to override category settings.' )
			) );
			?>

			<div id="wc_catalog_restrictions_prices_roles_container" class="wc_restrictions_options_panel" style="<?php echo (get_post_meta( $object->ID, '_wc_restrictions_price', true ) == 'restricted' ? 'display:block;' : 'display:none;'); ?>">
				<label><?php _e( "Choose the roles that can view this products price", 'wc_catalog_restrictions' ); ?></label><br />
				<div class="clearfix"></div>
				<?php $chunks = array_chunk( $all_roles, ceil( count( $all_roles ) / 3 ), true ); ?>
				<?php foreach ( $chunks as $chunk ) : ?>
					<ul class="list-column">        
						<?php foreach ( $chunk as $role_id => $role ) : ?>
							<?php $role_checked = in_array( $role_id, $current_price_restrictions ) ? 'checked="checked"' : ''; ?>
							<li>
								<label for="wc_restrictions_price_roles_<?php echo esc_attr( $role_id ); ?>" class="selectit">
									<input <?php echo $role_checked; ?> type="checkbox" id="wc_restrictions_price_roles_<?php echo esc_attr( $role_id ); ?>" name="wc_restrictions_price_roles[]" value="<?php echo esc_attr( $role_id ); ?>" /><?php echo $role['name']; ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endforeach; ?>
			</div>
			<div class="clearfix"></div>

		</div>
		<?php
	}

	function save_meta( $post_id, $post ) {
		global $wc_catalog_restrictions;
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['wc_restrictions_nonce'] ) || !wp_verify_nonce( $_POST['wc_restrictions_nonce'], basename( __FILE__ ) ) )
			return $post_id;

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		$restrictions_allowed = filter_input( INPUT_POST, '_wc_restrictions', FILTER_SANITIZE_STRIPPED );
		if ( $restrictions_allowed == 'inherit' ) {
			//Delete the post meta key on inherit so our taxonomy query will know what items it needs to exlude.
			delete_post_meta( $post_id, '_wc_restrictions' );
		} else {
			update_post_meta( $post_id, '_wc_restrictions', $restrictions_allowed );
		}
		$meta_key = '_wc_restrictions_allowed';

		//Clear out old roles
		delete_post_meta( $post_id, $meta_key );

		if ( $restrictions_allowed == 'restricted' ) {
			$wc_roles = ( isset( $_POST['wc_restrictions_allowed'] ) ? $_POST['wc_restrictions_allowed'] : '' );
			if ( $wc_roles && count( $wc_roles ) ) {
				foreach ( $wc_roles as $role ) {
					add_post_meta( $post_id, $meta_key, $role, false );
				}
			} else {
				//add an empty restriction so our query filter can filter this properly. 
				add_post_meta( $post_id, $meta_key, '', false );
			}
		}

		if ( $wc_catalog_restrictions->get_setting( '_wc_restrictions_locations_enabled', 'no' ) == 'yes' ) {
			$locations_allowed = filter_input( INPUT_POST, '_wc_restrictions_location' );
			if ( $locations_allowed == 'inherit' ) {
				delete_post_meta( $post_id, '_wc_restrictions_location' );
				delete_post_meta( $post_id, '_wc_restrictions_locations' );
			} else {
				update_post_meta( $post_id, '_wc_restrictions_location', $locations_allowed );

				delete_post_meta( $post_id, '_wc_restrictions_locations' );
				$wc_locations = isset( $_POST['wc_restrictions_locations'] ) ? $_POST['wc_restrictions_locations'] : array('');
				foreach ( $wc_locations as $location ) {
					add_post_meta( $post_id, '_wc_restrictions_locations', $location, false );
				}
			}
		}

		$purchase_roles_allowed = filter_input( INPUT_POST, '_wc_restrictions_purchase' );
		update_post_meta( $post_id, '_wc_restrictions_purchase', $purchase_roles_allowed );
		if ( $purchase_roles_allowed == 'inherit' ) {
			delete_post_meta( $post_id, '_wc_restrictions_purchase_roles' );
		} elseif ( $purchase_roles_allowed == 'restricted' ) {
			$proles = isset( $_POST['wc_restrictions_purchase_roles'] ) ? $_POST['wc_restrictions_purchase_roles'] : array('');
			update_post_meta( $post_id, '_wc_restrictions_purchase_roles', $proles );
		}


		$purchase_roles_allowed = filter_input( INPUT_POST, '_wc_restrictions_price' );
		update_post_meta( $post_id, '_wc_restrictions_price', $purchase_roles_allowed );
		if ( $purchase_roles_allowed == 'inherit' ) {
			delete_post_meta( $post_id, '_wc_restrictions_price_roles' );
		} elseif ( $purchase_roles_allowed == 'restricted' ) {
			$proles = isset( $_POST['wc_restrictions_price_roles'] ) ? $_POST['wc_restrictions_price_roles'] : array('');
			update_post_meta( $post_id, '_wc_restrictions_price_roles', $proles );
		}
	}

}