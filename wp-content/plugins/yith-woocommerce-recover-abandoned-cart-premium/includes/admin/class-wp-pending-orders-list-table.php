<?php

if ( !defined( 'ABSPATH' ) || !defined( 'YITH_YWRAC_VERSION' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Abandoned Carts List Table
 *
 * @class   YITH_YWRAC_Pending_Orders_List_Table
 * @package YITH WooCommerce Recover Abandoned Cart
 * @since   1.0.0
 * @author  Yithemes
 */

class YITH_YWRAC_Pending_Orders_List_Table extends WP_List_Table {

	private $post_type;

	public function __construct( $args = array() ) {
		parent::__construct( array() );
		$this->post_type = 'shop_order';
	}

	function get_columns() {
		$columns = array(
			'post_title' => __( 'Order', 'yith-woocommerce-recover-abandoned-cart' ),
			'purchased'  => __( 'Purchased', 'yith-woocommerce-recover-abandoned-cart' ),
			'date'       => __( 'Date', 'yith-woocommerce-recover-abandoned-cart' ),
			'total'      => __( 'Total', 'yith-woocommerce-recover-abandoned-cart' ),
			'status_email' => __( 'Last email sent', 'yith-woocommerce-recover-abandoned-cart' ),
			'action'       => __( 'Action', 'yith-woocommerce-recover-abandoned-cart' ),
		);
		return $columns;
	}

	function prepare_items() {
		global $wpdb, $_wp_column_headers;

		$screen = get_current_screen();

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = array();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$order_string = 'ORDER BY ywrac_p.post_date DESC ';

		$query = $wpdb->prepare( "SELECT ywrac_p.* FROM $wpdb->posts AS ywrac_p 
            WHERE 	ywrac_p.post_type 	= '%s'
            AND 	ywrac_p.post_status 	= 'wc-pending'
             $order_string", $this->post_type );

		$totalitems = $wpdb->query($query);

		$perpage = 10;
		//Which page is this?
		$paged = !empty($_GET["paged"]) ? $_GET["paged"] : '';
		//Page Number
		if ( empty( $paged ) || !is_numeric( $paged ) || $paged <= 0 ) {
			$paged = 1;
		}
		//How many pages do we have in total?
		$totalpages = ceil( $totalitems / $perpage );
		//adjust the query to take pagination into account
		if ( !empty( $paged ) && !empty( $perpage ) ) {
			$offset = ( $paged - 1 ) * $perpage;
			$query .= ' LIMIT ' . (int) $offset . ',' . (int) $perpage;
		}

		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			"total_items" => $totalitems,
			"total_pages" => $totalpages,
			"per_page"    => $perpage,
		) );
		//The pagination links are automatically built according to those parameters

		$_wp_column_headers[$screen->id]=$columns;
		$this->items = $wpdb->get_results($query);

	}

	function column_default( $item, $column_name ) {
		$the_order = wc_get_order( $item->ID );
		$billing_phone = yit_get_prop( $the_order, '_billing_phone');
		$billing_first_name = yit_get_prop( $the_order, '_billing_first_name');
		$billing_last_name = yit_get_prop( $the_order, '_billing_last_name');
		$billing_email = yit_get_prop( $the_order, '_billing_email');
		$user_id = method_exists( $the_order, 'get_customer_id' ) ? $the_order->get_customer_id() : yit_get_prop( $the_order, '_customer_user' );
		switch( $column_name ) {
			case 'post_title':
				$customer_tip = array();

				if ( $address = $the_order->get_formatted_billing_address() ) {
					$customer_tip[] = __( 'Billing:', 'woocommerce' ) . ' ' . $address . '<br/><br/>';
				}

				if ( $billing_phone ) {
					$customer_tip[] = __( 'Tel:', 'woocommerce' ) . ' ' . $billing_phone;
				}

				echo '<div class="tips" data-tip="' . wc_sanitize_tooltip( implode( "<br/>", $customer_tip ) ) . '">';

				if ( $user_id ) {
					$user_info = get_userdata( $user_id );
				}

				if ( ! empty( $user_info ) ) {

					$username = '<a href="user-edit.php?user_id=' . absint( $user_info->ID ) . '">';

					if ( $user_info->first_name || $user_info->last_name ) {
						$username .= esc_html( ucfirst( $user_info->first_name ) . ' ' . ucfirst( $user_info->last_name ) );
					} else {
						$username .= esc_html( ucfirst( $user_info->display_name ) );
					}

					$username .= '</a>';

				} else {
					if ( $billing_first_name || $billing_last_name ) {
						$username = trim( $billing_first_name . ' ' . $billing_last_name );
					} else {
						$username = __( 'Guest', 'woocommerce' );
					}
				}

				printf( _x( '%s by %s', 'Order number by X', 'woocommerce' ), '<a href="' . admin_url( 'post.php?post=' . absint( $item->ID ) . '&action=edit' ) . '"><strong>#' . esc_attr( $the_order->get_order_number() ) . '</strong></a>', $username );

				if ( $billing_email ) {
					echo '<small class="meta email"><a href="' . esc_url( 'mailto:' . $billing_email ) . '">' . esc_html( $billing_email  ) . '</a></small>';
				}

				echo '</div>';
				break;
			case 'purchased':
				echo  apply_filters( 'woocommerce_admin_order_item_count', sprintf( _n( '%d item', '%d items', $the_order->get_item_count(), 'woocommerce' ), $the_order->get_item_count() ), $the_order ) ;

				break;
			case 'date':
				if ( '0000-00-00 00:00:00' == $item->post_date ) {
					$t_time = $h_time = __( 'Unpublished', 'woocommerce' );
				} else {
					$t_time = get_the_time( __( 'Y/m/d g:i:s A', 'woocommerce' ), $item );
					$h_time = get_the_time( __( 'Y/m/d', 'woocommerce' ), $item );
				}

				$date =  '<abbr title="' . esc_attr( $t_time ) . '">' . esc_html( apply_filters( 'post_date_column_time', $h_time, $item ) ) . '</abbr>';
				return $date;
				break;
			case 'total':
				$currency = method_exists( $the_order, 'get_currency' ) ? $the_order->get_currency() : $the_order->get_order_currency();

				if ( $the_order->get_total_refunded() > 0 ) {
					echo '<del>' . strip_tags( $the_order->get_formatted_order_total() ) . '</del> <ins>' . wc_price( $the_order->get_total() - $the_order->get_total_refunded(), array( 'currency' => $currency ) ) . '</ins>';
				} else {
					echo esc_html( strip_tags( $the_order->get_formatted_order_total() ) );
				}

				$payment_method_title = yit_get_prop( $the_order, '_payment_method_title' );
				if ( $payment_method_title ) {
					echo '<small class="meta">' . __( 'Via', 'woocommerce' ) . ' ' . esc_html( $payment_method_title ). '</small>';
				}
				break;
			case 'status_email':
				$emails_sent = get_post_meta( $item->ID, '_emails_sent', true);
				if ( empty( $emails_sent ) ) {
					$email_status = __( 'Not sent', 'yith-woocommerce-recover-abandoned-cart' );
				}else{
					$last = end( $emails_sent );
					$email_status = $last['email_name'].'<br>'.$last['data_sent'];
				}
				return '<span class="email_status" data-id="'.$item->ID.'">' . $email_status . '</span>';
				break;
			default:
				return ''; //Show the whole array for troubleshooting purposes
		}


	}

	/**
	 * Add the content of the column 'action' in the list table
	 * @since 1.1.0
	 *
	 * @param $item
	 *
	 * @return string|void
	 * @author Emanuela Castorina
	 */
	function column_action( $item ) {
		$html            = '';
		$email_templates = YITH_WC_Recover_Abandoned_Cart_Email()->get_email_templates( 'order', false );

		if ( ! empty( $email_templates ) ) {
			$select = '<select name="ywrac_template_email">';
			foreach ( $email_templates as $em ) {
				$select .= '<option value="' . $em->ID . '">' . $em->post_title . '</option>';
			}
			$select .= '</select>';
			$html = $select . '<input type="button" id="sendemail" class="ywrac_send_email button action"  value="' . __( 'Send email', 'yith-woocommerce-recover-abandoned-cart' ) . '" data-id="' . $item->ID . '" data-type="order">';
		} else {
			$html = __( 'Add a new email template', 'yith-woocommerce-recover-abandoned-cart' );
		}

		return $html;
	}

	/**
	 * Display the search box.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @param string $text The search button text
	 * @param string $input_id The search input id
     * @author Emanuela Castorina
	 */
	public function search_box( $text, $input_id ) {

		$input_id = $input_id . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) )
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		if ( ! empty( $_REQUEST['order'] ) )
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';

		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
			<input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" placeholder="<?php _e('Search','yith-woocommerce-recover-abandoned-cart') ?>"/>
			<?php submit_button( $text, 'button', '', false, array('id' => 'search-submit') ); ?>
		</p>
		<?php
	}



}
