<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YWQCDG_Active_Checkout_Table' ) ) {

	/**
	 * Displays the active checkout table in YWQCDG plugin admin tab
	 *
	 * @class   YWQCDG_Active_Checkout_Table
	 * @package Yithemes
	 * @since   1.0.0
	 * @author  Your Inspiration Themes
	 *
	 */
	class YWQCDG_Active_Checkout_Table {

		/**
		 * Single instance of the class
		 *
		 * @var \YWQCDG_Active_Checkout_Table
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YWQCDG_Active_Checkout_Table
		 * @since 1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self( $_REQUEST );

			}

			return self::$instance;

		}

		/**
		 * Constructor
		 *
		 * @since   1.0.0
		 * @return  mixed
		 * @author  Alberto Ruggiero
		 */
		public function __construct() {

			add_action( 'ywqcdg_active_checkout_table', array( $this, 'output' ) );
			add_action( 'current_screen', array( $this, 'add_options' ) );
			add_filter( 'set-screen-option', array( $this, 'set_options' ), 10, 3 );

		}

		/**
		 * Outputs the active checkout table template with insert form in plugin options panel
		 *
		 * @since   1.0.0
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function output() {

			global $wpdb;

			$current_section = isset( $_GET['section'] ) ? $_GET['section'] : 'products';

			$sections   = array(
				'products'   => array(
					'section' => __( 'Products', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
					'args'    => array(
						'singular' => __( 'product', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
						'plural'   => __( 'products', 'yith-woocommerce-quick-checkout-for-digital-goods' )
					),
					'options' => array(
						'select_table'     => "{$wpdb->prefix}posts a INNER JOIN {$wpdb->prefix}postmeta b ON a.ID = b.post_id",
						'select_columns'   => array(
							'a.ID',
							'a.post_title',
							'MAX( CASE WHEN b.meta_key = "_ywqcdg_active_checkout" THEN b.meta_value ELSE NULL END ) AS checkout',
						),
						'select_where'     => 'a.post_type = "product" AND ( b.meta_key = "_ywqcdg_active_checkout" ) AND b.meta_value = "yes"',
						'select_group'     => 'a.ID',
						'select_order'     => 'a.post_title',
						'select_order_dir' => 'ASC',
						'search_where'     => array(
							'a.post_title'
						),
						'per_page_option'  => 'products_per_page',
						'count_table'      => "( SELECT a.ID, a.post_title FROM {$wpdb->prefix}posts a INNER JOIN {$wpdb->prefix}postmeta b ON a.ID = b.post_id  WHERE a.post_type = 'product' AND ( b.meta_key = '_ywqcdg_active_checkout' ) AND b.meta_value = 'yes' GROUP BY a.ID ) AS a",
						'count_where'      => '',
						'key_column'       => 'ID',
						'view_columns'     => array(
							'cb'      => '<input type="checkbox" />',
							'product' => __( 'Product', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
						),
						'hidden_columns'   => array(),
						'sortable_columns' => array(
							'product' => array( 'post_title', true )
						),
						'custom_columns'   => array(
							'column_product' => function ( $item, $me ) {

								$delete_query_args = array(
									'page'    => $_GET['page'],
									'tab'     => $_GET['tab'],
									'section' => ( isset( $_GET['section'] ) ? $_GET['section'] : 'products' ),
									'action'  => 'delete',
									'id'      => $item['ID']
								);
								$delete_url        = esc_url( add_query_arg( $delete_query_args, admin_url( 'admin.php' ) ) );

								$product_query_args = array(
									'post'   => $item['ID'],
									'action' => 'edit'
								);
								$product_url        = esc_url( add_query_arg( $product_query_args, admin_url( 'post.php' ) ) );

								$actions = array(
									'product' => '<a href="' . $product_url . '" target="_blank">' . __( 'Edit product', 'yith-woocommerce-quick-checkout-for-digital-goods' ) . '</a>',
									'delete'  => '<a href="' . $delete_url . '">' . __( 'Remove from list', 'yith-woocommerce-quick-checkout-for-digital-goods' ) . '</a>',
								);

								return sprintf( '<strong><a class="tips" href="%s" data-tip="%s">#%d %s </a></strong> %s', $product_url, __( 'Edit product', 'yith-woocommerce-quick-checkout-for-digital-goods' ), $item['ID'], $item['post_title'], $me->row_actions( $actions ) );
							},
						),
						'bulk_actions'     => array(
							'actions'   => array(
								'delete' => __( 'Remove from list', 'yith-woocommerce-quick-checkout-for-digital-goods' )
							),
							'functions' => array(
								'function_delete' => function () {
									global $wpdb;

									$ids = isset( $_GET['id'] ) ? $_GET['id'] : array();
									if ( is_array( $ids ) ) {
										$ids = implode( ',', $ids );
									}

									if ( ! empty( $ids ) ) {
										$wpdb->query( "UPDATE {$wpdb->prefix}postmeta
                                           SET meta_value = 'no'
                                           WHERE ( meta_key = '_ywqcdg_active_checkout' ) AND post_id IN ( $ids )"
										);
									}
								}
							)
						),
					),
					'action'  => 'ywqcdg_json_search_products_and_variations'
				),
				'categories' => array(
					'section' => __( 'Categories', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
					'args'    => array(
						'singular' => __( 'category', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
						'plural'   => __( 'categories', 'yith-woocommerce-quick-checkout-for-digital-goods' )
					),
					'options' => array(
						'select_table'     => "{$wpdb->prefix}terms a INNER JOIN {$wpdb->prefix}term_taxonomy b ON a.term_id = b.term_id INNER JOIN {$wpdb->prefix}{$this->get_table_id_wc_prefix()}termmeta c ON c.{$this->get_table_id_wc_prefix()}term_id = a.term_id",
						'select_columns'   => array(
							'a.term_id AS ID',
							'a.name',
							'MAX( CASE WHEN c.meta_key = "_ywqcdg_active_checkout" THEN c.meta_value ELSE NULL END ) AS checkout',
						),
						'select_where'     => 'b.taxonomy = "product_cat" AND ( c.meta_key = "_ywqcdg_active_checkout" ) AND c.meta_value = "yes"',
						'select_group'     => 'a.term_id',
						'select_order'     => 'a.name',
						'select_order_dir' => 'ASC',
						'per_page_option'  => 'categories_per_page',
						'search_where'     => array(
							'a.name'
						),
						'count_table'      => "( SELECT a.* FROM {$wpdb->prefix}terms a INNER JOIN {$wpdb->prefix}term_taxonomy b ON a.term_id = b.term_id INNER JOIN {$wpdb->prefix}{$this->get_table_id_wc_prefix()}termmeta c ON c.{$this->get_table_id_wc_prefix()}term_id = a.term_id WHERE b.taxonomy = 'product_cat' AND ( c.meta_key = '_ywqcdg_active_checkout' ) AND c.meta_value = 'yes' GROUP BY a.term_id ) AS a",
						'count_where'      => '',
						'key_column'       => 'ID',
						'view_columns'     => array(
							'cb'       => '<input type="checkbox" />',
							'category' => __( 'Category', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
						),
						'hidden_columns'   => array(),
						'sortable_columns' => array(
							'category' => array( 'name', true )
						),
						'custom_columns'   => array(
							'column_category' => function ( $item, $me ) {

								$delete_query_args = array(
									'page'    => $_GET['page'],
									'tab'     => $_GET['tab'],
									'section' => isset( $_GET['section'] ) ? $_GET['section'] : 'products',
									'action'  => 'delete',
									'id'      => $item['ID']
								);
								$delete_url        = esc_url( add_query_arg( $delete_query_args, admin_url( 'admin.php' ) ) );

								$category_query_args = array(
									'taxonomy'  => 'product_cat',
									'post_type' => 'product',
									'tag_ID'    => $item['ID'],
									'action'    => 'edit'
								);
								$category_url        = esc_url( add_query_arg( $category_query_args, admin_url( 'edit-tags.php' ) ) );

								$actions = array(
									'product' => '<a href="' . $category_url . '" target="_blank">' . __( 'Edit category', 'yith-woocommerce-quick-checkout-for-digital-goods' ) . '</a>',
									'delete'  => '<a href="' . $delete_url . '">' . __( 'Remove from list', 'yith-woocommerce-quick-checkout-for-digital-goods' ) . '</a>',
								);

								return sprintf( '<strong><a class="tips" href="%s" data-tip="%s">#%d %s </a></strong> %s', $category_url, __( 'Edit category', 'yith-woocommerce-quick-checkout-for-digital-goods' ), $item['ID'], $item['name'], $me->row_actions( $actions ) );
							},
						),
						'bulk_actions'     => array(
							'actions'   => array(
								'delete' => __( 'Remove from list', 'yith-woocommerce-quick-checkout-for-digital-goods' )
							),
							'functions' => array(
								'function_delete' => function () {

									global $wpdb;

									$ids = isset( $_GET['id'] ) ? $_GET['id'] : array();
									if ( is_array( $ids ) ) {
										$ids = implode( ',', $ids );
									}

									if ( ! empty( $ids ) ) {
										$wpdb->query( "UPDATE {$wpdb->prefix}{$this->get_table_id_wc_prefix()}termmeta
                                           SET meta_value = 'no'
                                           WHERE ( meta_key = '_ywqcdg_active_checkout') AND term_id IN ( $ids )"
										);
									}

								}
							)
						),
					),
					'action'  => 'ywqcdg_json_search_product_categories'
				),
				'tags'       => array(
					'section' => __( 'Tags', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
					'args'    => array(
						'singular' => __( 'tag', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
						'plural'   => __( 'tags', 'yith-woocommerce-quick-checkout-for-digital-goods' )
					),
					'options' => array(
						'select_table'     => "{$wpdb->prefix}terms a INNER JOIN {$wpdb->prefix}term_taxonomy b ON a.term_id = b.term_id INNER JOIN {$wpdb->prefix}{$this->get_table_id_wc_prefix()}termmeta c ON c.{$this->get_table_id_wc_prefix()}term_id = a.term_id",
						'select_columns'   => array(
							'a.term_id AS ID',
							'a.name',
							'MAX(CASE WHEN c.meta_key = "_ywqcdg_active_checkout' . '" THEN c.meta_value ELSE NULL END) AS checkout',
						),
						'select_where'     => 'b.taxonomy = "product_tag" AND ( c.meta_key = "_ywqcdg_active_checkout" ) AND c.meta_value = "yes"',
						'select_group'     => 'a.term_id',
						'select_order'     => 'a.name',
						'select_order_dir' => 'ASC',
						'per_page_option'  => 'tags_per_page',
						'search_where'     => array(
							'a.name'
						),
						'count_table'      => "( SELECT a.* FROM {$wpdb->prefix}terms a INNER JOIN {$wpdb->prefix}term_taxonomy b ON a.term_id = b.term_id INNER JOIN {$wpdb->prefix}{$this->get_table_id_wc_prefix()}termmeta c ON c.{$this->get_table_id_wc_prefix()}term_id = a.term_id WHERE b.taxonomy = 'product_tag' AND ( c.meta_key = '_ywqcdg_active_checkout' ) AND c.meta_value = 'yes' GROUP BY a.term_id ) AS a",
						'count_where'      => '',
						'key_column'       => 'ID',
						'view_columns'     => array(
							'cb'  => '<input type="checkbox" />',
							'tag' => __( 'Tag', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
						),
						'hidden_columns'   => array(),
						'sortable_columns' => array(
							'tag' => array( 'name', true )
						),
						'custom_columns'   => array(
							'column_tag' => function ( $item, $me ) {

								$delete_query_args = array(
									'page'    => $_GET['page'],
									'tab'     => $_GET['tab'],
									'section' => isset( $_GET['section'] ) ? $_GET['section'] : 'products',
									'action'  => 'delete',
									'id'      => $item['ID']
								);
								$delete_url        = esc_url( add_query_arg( $delete_query_args, admin_url( 'admin.php' ) ) );

								$tag_query_args = array(
									'taxonomy'  => 'product_tag',
									'post_type' => 'product',
									'tag_ID'    => $item['ID'],
									'action'    => 'edit'
								);
								$tag_url        = esc_url( add_query_arg( $tag_query_args, admin_url( 'edit-tags.php' ) ) );

								$actions = array(
									'product' => '<a href="' . $tag_url . '" target="_blank">' . __( 'Edit tag', 'yith-woocommerce-quick-checkout-for-digital-goods' ) . '</a>',
									'delete'  => '<a href="' . $delete_url . '">' . __( 'Remove from list', 'yith-woocommerce-quick-checkout-for-digital-goods' ) . '</a>',
								);

								return sprintf( '<strong><a class="tips" href="%s" data-tip="%s">#%d %s </a></strong> %s', $tag_url, __( 'Edit tag', 'yith-woocommerce-quick-checkout-for-digital-goods' ), $item['ID'], $item['name'], $me->row_actions( $actions ) );
							},
						),
						'bulk_actions'     => array(
							'actions'   => array(
								'delete' => __( 'Remove from list', 'yith-woocommerce-quick-checkout-for-digital-goods' )
							),
							'functions' => array(
								'function_delete' => function () {

									global $wpdb;

									$ids = isset( $_GET['id'] ) ? $_GET['id'] : array();
									if ( is_array( $ids ) ) {
										$ids = implode( ',', $ids );
									}

									if ( ! empty( $ids ) ) {
										$wpdb->query( "UPDATE {$wpdb->prefix}{$this->get_table_id_wc_prefix()}termmeta
                                           SET meta_value='no'
                                           WHERE ( meta_key = '_ywqcdg_active_checkout' ) AND term_id IN ( $ids )"
										);
									}

								}
							)
						),
					),
					'action'  => 'ywqcdg_json_search_product_tags'
				),
			);
			$array_keys = array_keys( $sections );

			$table = new YITH_Custom_Table( $sections[ $current_section ]['args'] );

			$table->options = $sections[ $current_section ]['options'];

			$message = '';
			$notice  = '';

			$list_query_args = array(
				'page'    => $_GET['page'],
				'tab'     => $_GET['tab'],
				'section' => $current_section
			);

			$list_url = esc_url( add_query_arg( $list_query_args, admin_url( 'admin.php' ) ) );

			if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], basename( __FILE__ ) ) ) {

				$item_valid = $this->validate_fields( $_POST, $current_section );

				if ( $item_valid !== true ) {

					$notice = $item_valid;

				} else {

					switch ( $current_section ) {

						case 'categories':

							$category_ids = ( is_array( $_POST['category_ids'] ) ) ? $_POST['category_ids'] : explode( ',', $_POST['category_ids'] );
							$count        = count( $category_ids );

							foreach ( $category_ids as $category_id ) {

								if ( YITH_WQCDG()->is_wc_lower_2_6 ) {

									update_woocommerce_term_meta( $category_id, '_ywqcdg_active_checkout', 'yes' );

								} else {

									update_term_meta( $category_id, '_ywqcdg_active_checkout', 'yes' );

								}

							}

							break;

						case 'tags':

							$tag_ids = ( is_array( $_POST['tag_ids'] ) ) ? $_POST['tag_ids'] : explode( ',', $_POST['tag_ids'] );
							$count   = count( $tag_ids );

							foreach ( $tag_ids as $tag_id ) {

								if ( YITH_WQCDG()->is_wc_lower_2_6 ) {

									update_woocommerce_term_meta( $tag_id, '_ywqcdg_active_checkout', 'yes' );

								} else {

									update_term_meta( $tag_id, '_ywqcdg_active_checkout', 'yes' );

								}

							}

							break;

						default:

							$product_ids = ( is_array( $_POST['product_ids'] ) ) ? $_POST['product_ids'] : explode( ',', $_POST['product_ids'] );
							$count       = count( $product_ids );

							if ( ! empty( $product_ids ) ) {

								foreach ( $product_ids as $product_id ) {

									$product = wc_get_product( $product_id );
									yit_save_prop( $product, '_ywqcdg_active_checkout', 'yes', true );

								}

							}

					}

					if ( ! empty( $_POST['insert'] ) ) {

						$singular = sprintf( __( '1 %s added successfully', 'yith-woocommerce-quick-checkout-for-digital-goods' ), ucfirst( $sections[ $current_section ]['args']['singular'] ) );
						$plural   = sprintf( __( '%s %s added successfully', 'yith-woocommerce-quick-checkout-for-digital-goods' ), $count, ucfirst( $sections[ $current_section ]['args']['plural'] ) );
						$message  = $count > 1 ? $plural : $singular;

					}

				}

			}

			$table->prepare_items();

			if ( 'delete' === $table->current_action() ) {

				$singular = sprintf( __( '1 %s removed successfully', 'yith-woocommerce-quick-checkout-for-digital-goods' ), ucfirst( $sections[ $current_section ]['args']['singular'] ) );
				$plural   = sprintf( __( '%s %s removed successfully', 'yith-woocommerce-quick-checkout-for-digital-goods' ), count( $_GET['id'] ), ucfirst( $sections[ $current_section ]['args']['plural'] ) );
				$message  = count( $_GET['id'] ) > 1 ? $plural : $singular;

			}

			?>
			<ul class="subsubsub">
				<?php foreach ( $sections as $id => $section ) : ?>
					<li>
						<?php
						$query_args  = array( 'page' => $_GET['page'], 'tab' => $_GET['tab'], 'section' => $id );
						$section_url = esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
						?>

						<a href="<?php echo $section_url; ?>" class="<?php echo( $current_section == $id ? 'current' : '' ); ?>">
							<?php echo $section['section']; ?>
						</a>
						<?php echo( end( $array_keys ) == $id ? '' : '|' ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<br class="clear" />
			<div class="wrap">
				<div class="icon32 icon32-posts-post" id="icon-edit"><br /></div>
				<h1><?php _e( 'Quick checkout list', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?>

					<?php if ( empty( $_GET['action'] ) || ( 'insert' !== $_GET['action'] && 'edit' !== $_GET['action'] ) ) : ?>
						<?php
						$query_args   = array( 'page' => $_GET['page'], 'tab' => $_GET['tab'], 'section' => $current_section, 'action' => 'insert' );
						$add_form_url = esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
						?>
						<a class="page-title-action" href="<?php echo $add_form_url; ?>"><?php echo sprintf( __( 'Add %s', 'yith-woocommerce-quick-checkout-for-digital-goods' ), $sections[ $current_section ]['section'] ) ?></a>
					<?php endif; ?>
				</h1>

				<?php if ( ! empty( $notice ) ) : ?>
					<div id="notice" class="error below-h2"><p><?php echo $notice; ?></p></div>
				<?php endif; ?>

				<?php if ( ! empty( $message ) ) : ?>
					<div id="message" class="updated below-h2"><p><?php echo $message; ?></p></div>
				<?php endif; ?>

				<?php if ( ! empty( $_GET['action'] ) && ( 'insert' == $_GET['action'] ) ) : ?>

					<form id="form" method="POST" action="<?php echo $list_url; ?>">
						<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
						<table class="form-table">
							<tbody>
							<tr valign="top">
								<th scope="row" class="titledesc">
									<label for="<?php echo $sections[ $current_section ]['args']['singular']; ?>_ids">
										<?php echo ( 'edit' == $_GET['action'] ) ? sprintf( __( '%s to edit', 'yith-woocommerce-quick-checkout-for-digital-goods' ), ucfirst( $sections[ $current_section ]['args']['singular'] ) ) : sprintf( __( 'Select %s', 'yith-woocommerce-quick-checkout-for-digital-goods' ), ucfirst( $sections[ $current_section ]['args']['plural'] ) ); ?>
									</label>
								</th>
								<td class="forminp">

									<?php

									$select_args = array(
										'class'            => 'wc-product-search',
										'id'               => $sections[ $current_section ]['args']['singular'] . '_ids',
										'name'             => $sections[ $current_section ]['args']['singular'] . '_ids',
										'data-placeholder' => sprintf( __( 'Search for a %s&hellip;', 'yith-woocommerce-quick-checkout-for-digital-goods' ), $sections[ $current_section ]['args']['singular'] ),
										'data-allow_clear' => false,
										'data-selected'    => '',
										'data-multiple'    => true,
										'data-action'      => $sections[ $current_section ]['action'],
										'value'            => '',
										'style'            => 'width: 50%'
									);

									yit_add_select2_fields( $select_args )

									?>

								</td>
							</tr>
							</tbody>
						</table>
						<input id="<?php echo $_GET['action'] ?>" name="<?php echo $_GET['action'] ?>" type="submit" class="button-primary"
						       value="<?php echo sprintf( __( 'Add %s', 'yith-woocommerce-quick-checkout-for-digital-goods' ), $sections[ $current_section ]['section'] ); ?>"
						/>
						<a class="button-secondary" href="<?php echo $list_url; ?>"><?php _e( 'Back to list', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?></a>
					</form>

				<?php else : ?>

					<p>
						<i>
							<?php _e( 'If you want to enable quick checkout only on products, categories and tags added in this page, you should first select the option \'Enable quick checkout on items in the "Quick Checkout List" only\'.', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?>
						</i>
					</p>
					<form id="custom-table" method="GET" action="<?php echo $list_url; ?>">
						<?php $table->search_box( sprintf( __( 'Search %s' ), $sections[ $current_section ]['args']['singular'] ), $sections[ $current_section ]['args']['singular'] ); ?>
						<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
						<input type="hidden" name="tab" value="<?php echo $_GET['tab']; ?>" />
						<input type="hidden" name="section" value="<?php echo $current_section; ?>" />
						<?php $table->display(); ?>
					</form>

				<?php endif; ?>

			</div>
			<?php

		}

		/**
		 * Get prefix for term table and ic column for WC 2.6 compatibility
		 *
		 * @since   1.0.1
		 * @return  string
		 * @author  Alberto Ruggiero
		 */
		public function get_table_id_wc_prefix() {

			return ( YITH_WQCDG()->is_wc_lower_2_6 ) ? 'woocommerce_' : '';

		}

		/**
		 * Validate input fields
		 *
		 * @since   1.0.0
		 *
		 * @param   $item array POST data array
		 * @param   $current_section
		 *
		 * @return  bool|string
		 * @author  Alberto Ruggiero
		 */
		public function validate_fields( $item, $current_section ) {

			$messages = array();

			if ( ! empty( $item['insert'] ) ) {

				switch ( $current_section ) {

					case 'categories':

						if ( empty( $item['category_ids'] ) ) {
							$messages[] = __( 'Select at least one category', 'yith-woocommerce-quick-checkout-for-digital-goods' );
						}

						break;

					case 'tags':

						if ( empty( $item['tag_ids'] ) ) {
							$messages[] = __( 'Select at least one tag', 'yith-woocommerce-quick-checkout-for-digital-goods' );
						}

						break;

					default:

						if ( empty( $item['product_ids'] ) ) {
							$messages[] = __( 'Select at least one product', 'yith-woocommerce-quick-checkout-for-digital-goods' );
						}

				}

			}

			if ( empty( $messages ) ) {
				return true;
			}

			return implode( '<br />', $messages );

		}

		/**
		 * Add screen options for active checkout list table template
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function add_options() {

			$sections = array(
				'products'   => __( 'Products', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
				'categories' => __( 'Categories', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
				'tags'       => __( 'Tags', 'yith-woocommerce-quick-checkout-for-digital-goods' ),
			);

			$current_section = isset( $_GET['section'] ) ? $_GET['section'] : 'products';

			if ( ( 'yith-plugins_page_yith-wc-quick-checkout-for-digital-goods' == get_current_screen()->id ) && ( isset( $_GET['tab'] ) && $_GET['tab'] == 'active-checkout' ) && ( ! isset( $_GET['action'] ) || ( $_GET['action'] != 'edit' && $_GET['action'] != 'insert' ) ) ) {

				$option = 'per_page';

				$args = array(
					'label'   => $sections[ $current_section ],
					'default' => 10,
					'option'  => $current_section . '_per_page'
				);

				add_screen_option( $option, $args );

			}

		}

		/**
		 * Set screen options for active checkout list table template
		 *
		 * @since   1.0.0
		 *
		 * @param   $status
		 * @param   $option
		 * @param   $value
		 *
		 * @return  mixed
		 * @author  Alberto Ruggiero
		 */
		public function set_options( $status, $option, $value ) {

			$current_section = isset( $_GET['section'] ) ? $_GET['section'] : 'products';

			return ( $current_section . '_per_page' == $option ) ? $value : $status;

		}


	}

	/**
	 * Unique access to instance of YWQCDG_Active_Checkout_Table class
	 *
	 * @return \YWQCDG_Active_Checkout_Table
	 */
	function YWQCDG_Active_Checkout_Table() {

		return YWQCDG_Active_Checkout_Table::get_instance();

	}

	new YWQCDG_Active_Checkout_Table();

}