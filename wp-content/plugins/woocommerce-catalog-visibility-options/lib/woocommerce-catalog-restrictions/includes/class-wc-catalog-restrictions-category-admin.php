<?php

class WC_Catalog_Restrictions_Category_Admin {

	public static $instance;

	public static function instance() {
		if ( !self::$instance ) {
			self::$instance = new WC_Catalog_Restrictions_Category_Admin();
		}

		return self::$instance;
	}

	public function __construct() {

		add_action( 'admin_enqueue_scripts', array($this, 'on_admin_scripts'), 99 );
		add_action( 'product_cat_add_form_fields', array($this, 'add_category_fields') );
		add_action( 'product_cat_edit_form_fields', array($this, 'edit_category_fields'), 10, 2 );
		add_action( 'created_term', array($this, 'category_field_save'), 10, 3 );
		add_action( 'edit_term', array($this, 'category_field_save'), 10, 3 );
		add_filter( 'manage_edit-product_cat_columns', array($this, 'cat_columns') );
		add_filter( 'manage_product_cat_custom_column', array($this, 'cat_column'), 10, 3 );
	}

	function on_admin_scripts() {
		global $wc_catalog_restrictions;
		$screen = get_current_screen();
		if ( strpos( $screen->id, 'product_cat' ) !== false ) :
			wp_enqueue_style( 'wc-product-restrictions-admin', $wc_catalog_restrictions->plugin_url() . '/assets/css/admin.css' );
			wp_enqueue_script( 'wc-product-restrictions-admin', $wc_catalog_restrictions->plugin_url() . '/assets/js/admin.js' );
			wp_enqueue_script( 'woocommerce_admin' );
			wp_enqueue_script( 'jquery-ui-sortable' );

		endif;
	}

	function add_category_fields() {
		global $woocommerce, $wc_catalog_restrictions, $wp_roles;
		if ( !isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$all_roles = $wp_roles->roles;
		$restricted = 'public';
		$current_restrictions = array();
		?>
		<div id="wc_catalog_restrictions" class="form-field">

			<label><?php _e( 'Role Restrictions', 'wc_catalog_restrictions' ); ?></label>

			<label for="_wc_restrictions"><?php _e( 'Which customer roles can view and purchase products in this category?', 'wc_catalog_restrictions' ); ?></label>
			<select name="_wc_restrictions" id="_wc_restrictions">
				<option value="no-restriction-setting" <?php selected( $restricted, 'no-restriction-setting' ); ?>><?php _e( 'Not Configured', 'wc_catalog_restrictions' ); ?></option>
				<option value="public" <?php selected( $restricted, 'public' ); ?>><?php _e( 'Everyone', 'wc_catalog_restrictions' ); ?></option>
				<option value="restricted" <?php selected( $restricted, 'restricted' ); ?>><?php _e( 'Specific Roles', 'wc_catalog_restrictions' ); ?></option>
			</select>

			<div id="wc_catalog_restrictions_roles_container" style="<?php echo ($restricted == 'restricted' ? 'display:block;' : 'display:none;'); ?>">
				<p class="title"><?php _e( "Choose the roles that can view this product", 'wc_catalog_restrictions' ); ?></p>
				<?php $chunks = array_chunk( $all_roles, ceil( count( $all_roles ) / 3 ), true ); ?>
				<?php foreach ( $chunks as $chunk ) : ?>
					<ul class="list-column">        
						<?php foreach ( $chunk as $role_id => $role ) : ?>
							<?php $role_checked = in_array( $role_id, $current_restrictions ) ? 'checked="checked"' : ''; ?>
							<li>
								<label for="role_<?php echo esc_attr( $role_id ); ?>" class="selectit">
									<input <?php echo $role_checked; ?> type="checkbox" id="role_<?php echo esc_attr( $role_id ); ?>" name="wc_restrictions_allowed[]" value="<?php echo esc_attr( $role_id ); ?>" /><?php echo $role['name']; ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endforeach; ?>

			</div>

			<div class="clearfix"></div>
		</div>
		<div id="wc_catalog_restrictions_location" class="form-field">
			<?php if ( $wc_catalog_restrictions->get_setting( '_wc_restrictions_locations_enabled', 'no' ) == 'yes' ) : ?>
				<?php
				$location_restriction = 'public';
				$current_locations = array();
				?>

				<label for="_wc_restrictions_location"><?php _e( 'What locations should this category be enabled for?', 'wc_catalog_restrictions' ); ?></label>

				<select name="_wc_restrictions_location" id="_wc_restrictions_location">
					<option value="no-restriction-setting" <?php selected( $location_restriction, 'no-restriction-setting' ); ?>><?php _e( 'Not Configured', 'wc_catalog_restrictions' ); ?></option>
					<option value="public" <?php selected( $location_restriction, 'public' ); ?>><?php _e( 'All Locations', 'wc_catalog_restrictions' ); ?></option>
					<option value="restricted" <?php selected( $location_restriction, 'restricted' ); ?>><?php _e( 'Specific Locations', 'wc_catalog_restrictions' ); ?></option>
				</select>

				<div id="wc_catalog_restrictions_locations_container" class="woocommerce_options_panel"  style="<?php echo ($location_restriction == 'restricted' ? 'display:block;' : 'display:none;'); ?>">
					<p class="form-field"><label for="wc_restrictions_locations"><?php _e( 'Target Locations', 'wc_catalog_restrictions' ); ?></label>
						<select name="wc_restrictions_locations[]" class="" multiple="multiple" data-placeholder="<?php _e( 'Search for a country&hellip;', 'woocommerce' ); ?>">
							<?php woocommerce_catalog_restrictions_country_multiselect_options( $current_locations ); ?>
						</select> <img style="width:16px;height:16px;" class="help_tip" data-tip='<?php _e( 'Choose locations for this category.  Only users who select a matching location will be able to view and purchase products in this category.', 'wc_catalog_restrictions' ) ?>' src="<?php echo $woocommerce->plugin_url(); ?>/assets/images/help.png" /></p>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	function edit_category_fields( $term, $taxonomy ) {
		global $woocommerce, $wc_catalog_restrictions, $wp_roles;
		if ( !isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
		$all_roles = $wp_roles->roles;


		$restricted = get_woocommerce_term_meta( $term->term_id, '_wc_restrictions', true );
		$current_restrictions = get_woocommerce_term_meta( $term->term_id, '_wc_restrictions_allowed', false );

		if ( !$current_restrictions ) {
			$current_restrictions = array();
		}
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Role Visibility Rules', 'wc_catalog_restrictions' ); ?></label></th>
			<td>
				<div id="wc_catalog_restrictions" class="form-field">
					<label for="_wc_restrictions"><?php _e( 'Choose the type of visibility rule to use with products in this category.', 'wc_catalog_restrictions' ); ?></label><br />
					<select name="_wc_restrictions" id="_wc_restrictions">
						<option value="no-restriction-setting"><?php _e( 'None', 'wc_catalog_restrictions' ); ?></option>
						<option value="restricted" <?php selected( $restricted, 'restricted' ); ?>><?php _e( 'Show to Specific Roles', 'wc_catalog_restrictions' ); ?></option>
						<option value="public" <?php selected( $restricted, 'public' ); ?>><?php _e( 'Show to Everyone', 'wc_catalog_restrictions' ); ?></option>
					</select>
					<img style="width:16px;height:16px;" class="help_tip" data-tip='<?php _e( 'Use to Show To Everyone to force products in this category to always be displayed, regardless of the customers role. Use Show to Specific Roles to choose which roles will see products in this category.' ); ?>' src="<?php echo $woocommerce->plugin_url(); ?>/assets/images/help.png" />
					<div id="wc_catalog_restrictions_roles_container" style="<?php echo ($restricted == 'restricted' ? 'display:block;' : 'display:none;'); ?>">
						<p class="title"><?php _e( "Choose the roles that can view this product", 'wc_catalog_restrictions' ); ?></p>
						<?php $chunks = array_chunk( $all_roles, ceil( count( $all_roles ) / 3 ), true ); ?>
						<?php foreach ( $chunks as $chunk ) : ?>
							<ul class="list-column">        
								<?php foreach ( $chunk as $role_id => $role ) : ?>
									<?php $role_checked = in_array( $role_id, $current_restrictions ) ? 'checked="checked"' : ''; ?>
									<li>
										<label for="role_<?php echo esc_attr( $role_id ); ?>" class="selectit">
											<input <?php echo $role_checked; ?> type="checkbox" id="role_<?php echo esc_attr( $role_id ); ?>" name="wc_restrictions_allowed[]" value="<?php echo esc_attr( $role_id ); ?>" /><?php echo $role['name']; ?>
										</label>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endforeach; ?>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</td>
		</tr>
		<?php if ( $wc_catalog_restrictions->get_setting( '_wc_restrictions_locations_enabled', 'no' ) == 'yes' ) : ?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php _e( 'Location Visibility Rules', 'wc_catalog_restrictions' ); ?></label></th>
				<td>
					<div id="wc_catalog_restrictions_location" class="form-field">

						<?php
						$location_restriction = get_woocommerce_term_meta( $term->term_id, '_wc_restrictions_location', true );
						$current_locations = get_woocommerce_term_meta( $term->term_id, '_wc_restrictions_locations', false );
						?>

						<label for="_wc_restrictions_location"><?php _e( 'Choose the type of location rule to use for products in this category.', 'wc_catalog_restrictions' ); ?></label>
						<br />
						<select name="_wc_restrictions_location" id="_wc_restrictions_location">
							<option value="no-restriction-setting"><?php _e( 'None', 'wc_catalog_restrictions' ); ?></option>
							<option value="restricted" <?php selected( $location_restriction, 'restricted' ); ?>><?php _e( 'Specific Locations', 'wc_catalog_restrictions' ); ?></option>
							<option value="public" <?php selected( $location_restriction, 'public' ); ?>><?php _e( 'Any Location', 'wc_catalog_restrictions' ); ?></option>
						</select>
						<img style="width:16px;height:16px;" class="help_tip" data-tip='<?php _e( 'Use to Any Location to force products in this category to always be displayed, regardless of the customers location. Use Specific Locations to choose which locations prodcuts in this category will be enabled for.' ); ?>' src="<?php echo $woocommerce->plugin_url(); ?>/assets/images/help.png" />

						<div id="wc_catalog_restrictions_locations_container" class="woocommerce_options_panel" style="<?php echo ($location_restriction == 'restricted' ? 'display:block;' : 'display:none;'); ?>">
							<p class="form-field"><label for="wc_restrictions_locations"><?php _e( 'Target Locations', 'wc_catalog_restrictions' ); ?></label>
								<select name="wc_restrictions_locations[]" class="" multiple="multiple" data-placeholder="<?php _e( 'Search for a country&hellip;', 'woocommerce' ); ?>">
									<?php woocommerce_catalog_restrictions_country_multiselect_options( $current_locations ); ?>
								</select> <img style="width:16px;height:16px;" class="help_tip" data-tip='<?php _e( 'Choose locations for this category.  Only users who select a matching location will be able to view and purchase products in this category.', 'wc_catalog_restrictions' ) ?>' src="<?php echo $woocommerce->plugin_url(); ?>/assets/images/help.png" /></p>
						</div>

					</div>
				</td>
			</tr>
		<?php endif; ?>
		<?php
	}

	function category_field_save( $term_id, $tt_id, $taxonomy ) {

		$restrictions_enabled = isset( $_POST['_wc_restrictions'] ) ? $_POST['_wc_restrictions'] : false;

		if ( empty( $restrictions_enabled ) || $restrictions_enabled == 'no-restriction-setting' ) {
			delete_woocommerce_term_meta( $term_id, '_wc_restrictions' );
			delete_woocommerce_term_meta( $term_id, '_wc_restrictions_allowed' );
		} else {
			update_woocommerce_term_meta( $term_id, '_wc_restrictions', $restrictions_enabled );

			delete_woocommerce_term_meta( $term_id, '_wc_restrictions_allowed' );
			if ( $restrictions_enabled == 'restricted' ) {
				$restrictions = isset( $_POST['wc_restrictions_allowed'] ) ? $_POST['wc_restrictions_allowed'] : array('');
				foreach ( $restrictions as $role ) {
					add_woocommerce_term_meta( $term_id, '_wc_restrictions_allowed', $role );
				}
			}
		}


		$restrictions_location_enabled = isset( $_POST['_wc_restrictions_location'] ) ? $_POST['_wc_restrictions_location'] : false;

		if ( empty( $restrictions_location_enabled ) || $restrictions_location_enabled == 'no-restriction-setting' ) {
			delete_woocommerce_term_meta( $term_id, '_wc_restrictions_location' );
			delete_woocommerce_term_meta( $term_id, '_wc_restrictions_locations' );
		} else {
			update_woocommerce_term_meta( $term_id, '_wc_restrictions_location', $restrictions_location_enabled );

			delete_woocommerce_term_meta( $term_id, '_wc_restrictions_locations' );
			if ( $restrictions_location_enabled == 'restricted' ) {
				$restrictions = isset( $_POST['wc_restrictions_locations'] ) ? $_POST['wc_restrictions_locations'] : array('');
				foreach ( $restrictions as $location ) {
					add_woocommerce_term_meta( $term_id, '_wc_restrictions_locations', $location );
				}
			}
		}
	}

	function cat_columns( $columns ) {
		$new_columns = array();
		$new_columns['cb'] = $columns['cb'];
		$new_columns['restrictions'] = __( 'Restrictions', 'wc_catalog_restrictions' );

		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

	function cat_column( $columns, $column, $id ) {
		if ( $column == 'restrictions' ) {

			$restricted = get_woocommerce_term_meta( $id, '_wc_restrictions', true );
			$current_restrictions = get_woocommerce_term_meta( $id, '_wc_restrictions_allowed', false );

			if ( !$restricted ) {
				
			} elseif ( $restricted == 'public' ) {
				$columns .= 'Everyone has access';
			} else {
				if ( count( $current_restrictions ) == 1 ) {
					if ( !empty( $current_restrictions[0] ) ) {
						$columns .= sprintf( __( '%s role has access', 'wc_catalog_restrictions' ), count( $current_restrictions ) );
					} else {
						$columns .= __( 'No one has access', 'wc_catalog_restrictions' );
					}
				} else {
					$columns .= sprintf( __( '%s roles have access', 'wc_catalog_restrictions' ), count( $current_restrictions ) );
				}
			}
		}
		return $columns;
	}

}
