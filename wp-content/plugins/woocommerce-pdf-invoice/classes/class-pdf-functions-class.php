<?php

        class WC_pdf_functions {

            public function __construct() {

            	// Stop everything if iconv is not loaded, prevents fatal errors
            	if ( extension_loaded('iconv') ) {
				
					global $wpdb,$woocommerce;
					$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
					
					// $woocommerce_pdf_invoice_options['create_invoice'] contains all the order status's that should generate an invoice
					$order_status_array = $woocommerce_pdf_invoice_options['create_invoice'];
					
					/**
					 * Create Invoice actions
					 */
					// Add invoice details to order when order is marked completed
					add_action( 'woocommerce_order_status_completed', array( $this,'woocommerce_completed_order_create_invoice' ) );
					// Add invoice details to order when order is marked processing
					add_action( 'woocommerce_order_status_processing', array( $this,'woocommerce_completed_order_create_invoice' ) );
					// Add invoice details to order when order is marked pending
					add_action( 'woocommerce_order_status_pending', array( $this,'woocommerce_completed_order_create_invoice' ) );
					// Add invoice details to order when order is marked on-hold
					add_action( 'woocommerce_order_status_on-hold', array( $this,'woocommerce_completed_order_create_invoice' ) );
					// Monitor for status changes
					add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this,'woocommerce_completed_order_create_invoice' ) );
					add_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $this,'woocommerce_completed_order_create_invoice' ) );
					add_action( 'woocommerce_order_status_pending_to_completed_notification', array( $this,'woocommerce_completed_order_create_invoice' ) );
					
					// Add woocommerce-pdf_admin-css.css to admin
					add_action( 'admin_enqueue_scripts', array( $this, 'woocommerce_pdf_admin_css' ) );
					
					// Add Invoice meta box to completed orders
					add_action( 'add_meta_boxes', array( $this,'invoice_details_admin_init' ), 10, 2 );

					// Add Invoice Number column to orders page in admin
					add_action( 'admin_init' , array( $this, 'pdf_manage_edit_shop_order_columns' ), 10, 2 );

					// Add Invoice Number to column
					add_action( 'manage_shop_order_posts_custom_column' , array( $this, 'invoice_number_admin_init') , 2 );

					// Add Send Invoice icon to actions on orders page in admin
					add_filter( 'woocommerce_admin_order_actions', array( $this,'send_invoice_icon_admin_init' ) ,10 , 2 );
					
					// Add Download Invoice icon to actions on orders page in admin
					add_filter( 'woocommerce_admin_order_actions', array( $this,'download_invoice_icon_admin_init' ) ,11 , 2 );

					// Send PDF when icon is clicked
					add_action( 'wp_ajax_pdfinvoice-admin-send-pdf', array( $this, 'pdfinvoice_admin_send_pdf') );
					
					// Add invoice action to My-order page
					add_filter( 'woocommerce_my_account_my_orders_actions', array( $this,'my_account_pdf' ), 10, 2 );
					
					// Keep an eye on the URL
					add_action( 'init' , array( $this,'pdf_url_check') );
					add_action( 'admin_init' , array( $this,'admin_pdf_url_check') );
					
					// Send test email with PDF attachment
					add_action( 'admin_init' , array( $this,'pdf_invoice_send_test') );

					// Delete Invoice information
					add_action( 'admin_init' , array( $this,'pdf_invoice_delete_invoices') );
					
					// Add invoice link to Thank You page for processing
					if ( isset($woocommerce_pdf_invoice_options['link_thanks']) && $woocommerce_pdf_invoice_options['link_thanks'] == 'true' && isset($woocommerce_pdf_invoice_options['create_invoice']) && $woocommerce_pdf_invoice_options['create_invoice'] == 'processing' ) {
						add_action( 'woocommerce_thankyou' , array( $this,'invoice_link_thanks' ), 10 );
					}
					
					// Add invoice link to Thank You page for on-hold
					if ( isset($woocommerce_pdf_invoice_options['link_thanks']) && $woocommerce_pdf_invoice_options['link_thanks'] == 'true' && isset($woocommerce_pdf_invoice_options['create_invoice']) && $woocommerce_pdf_invoice_options['create_invoice'] == 'on-hold' ) {
						add_action( 'woocommerce_thankyou' , array( $this,'invoice_link_thanks' ), 10 );
					}

					// WC Subscriptions support: prevent unnecessary order meta from polluting renewal orders
					if ( function_exists( 'wcs_order_contains_subscription' ) ) {
						// Subscriptions 2.0
						// don't copy over invoice meta from the original order to the subscription (subscription objects should not have an invoice)
						add_filter( 'wcs_subscription_meta', 'subscriptions_remove_subscription_order_meta', 10, 3 );

						// don't copy over invoice meta to subscription object during upgrade from 1.5.x to 2.0
						add_filter( 'wcs_upgrade_subscription_meta_to_copy', 'subscriptions_remove_renewal_order_meta_2' );

						// don't copy over invoice meta from the subscription to the renewal order
						add_filter( 'wcs_renewal_order_meta', 'subscriptions_remove_renewal_order_meta_2' );

					} else {
						add_filter( 'woocommerce_subscriptions_renewal_order_meta_query', array( $this, 'subscriptions_remove_renewal_order_meta' ), 10, 4 );
					}

				}

			}

			/** 
			 * If an order is marked complete add _invoice_number, _invoice_number_display and _invoice_date
			 * It's important to remember that once an invoice has been created you can not change
			 * the number or date and you shouldn't change any other details either!
			 */ 	 
			function woocommerce_completed_order_create_invoice( $order_id ) {
				global $wpdb,$woocommerce;

				if( !class_exists('WC_send_pdf') ){
					include( 'class-pdf-send-pdf-class.php' );
				}
				
				$order = new WC_Order( $order_id );

				// Get the invoice options
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				// Create an array of acceptable order statuses based on $woocommerce_pdf_invoice_options['create_invoice']
				if ( $woocommerce_pdf_invoice_options['create_invoice'] == 'on-hold' ) {
					$order_status_array = array( 'on-hold','pending','processing','completed' );
				} elseif ( $woocommerce_pdf_invoice_options['create_invoice'] == 'pending' ) {
					$order_status_array = array( 'pending','processing','completed' );
				} elseif ( $woocommerce_pdf_invoice_options['create_invoice'] == 'processing' ) {
					$order_status_array = array( 'processing','completed' );
				} else {
					$order_status_array = array( 'completed' );
				}

				// WooCommerce 3.0 compatibility
				$order_status	= is_callable( array( $order, 'get_status' ) ) ? $order->get_status() : $order->order_status;
				
				// if the current order status is not in the $order_status_array don't go any further
				if ( !in_array( $order_status, $order_status_array ) ) {
					return;
				}
				
				/**
				 * Let's create an invoice if it's not there already
				 */		
				if ( !get_post_meta( $order_id, '_invoice_number', TRUE ) ) {
		
					if ( $woocommerce_pdf_invoice_options['sequential'] == 'true' ) {

						/**
						 * Check for a stored $current_invoice
						 */
						if( get_option( 'woocommerce_pdf_invoice_current_invoice' ) && get_option( 'woocommerce_pdf_invoice_current_invoice' ) != '' && !function_exists( 'is_wpe' ) ) {

							$current_invoice  = get_option( 'woocommerce_pdf_invoice_current_invoice' );

						} else {
							/** 
							 * Check if we have created an invoice before this order
							 */
							$invoice = $wpdb->get_row("SELECT * FROM $wpdb->postmeta 
													   WHERE meta_key = '_invoice_number' 
													   ORDER BY CAST(meta_value AS SIGNED) DESC
													   LIMIT 1;"
													);
							$current_invoice  = $invoice->meta_value;

						}

						/**
						 * If !$current_invoice then we use the start_number or 1 if no start number is set
						 */
						if ( !$current_invoice ) {

							if ( $woocommerce_pdf_invoice_options['start_number'] ) {
								$next_invoice = $woocommerce_pdf_invoice_options['start_number'];
							} else {
								$next_invoice = 1;
							}

						} else {

							$next_invoice = $current_invoice + 1;

						}

						/**
						 * Check woocommerce_pdf_invoice_current_year and $woocommerce_pdf_invoice_options['annual_restart']
						 */
						$current_year = get_option( 'woocommerce_pdf_invoice_current_year' );
						if ( isset($woocommerce_pdf_invoice_options['annual_restart']) && $woocommerce_pdf_invoice_options['annual_restart'] == 'TRUE' && isset($current_year) && $current_year != '' && $current_year != date('Y') ) {
						 	$next_invoice = 1;
						}

						// Set an option for the current invoice and year to avoid querying the DB everytime
						update_option( 'woocommerce_pdf_invoice_current_invoice', $next_invoice );
						update_option( 'woocommerce_pdf_invoice_current_year', date('Y') );
			
					} else {
						// Sequential order numbering is not needed, just use the order_id
						$next_invoice = $order_id;
			
					}

					// SET INVOICE DATE IF NEEDED
					if ( !get_post_meta($order_id, '_invoice_date', TRUE) ) {

						update_post_meta( $order_id, '_invoice_date', current_time('mysql') );
				
					}

					update_post_meta( $order_id, '_invoice_number', $next_invoice );
					update_post_meta( $order_id, '_invoice_number_display', WC_pdf_functions::create_display_invoice_number( $next_invoice, $order_id ) );
			
					// SET INVOICE DETAILS TO AVOID CHANGES IN THE FUTURE
					update_post_meta( $order_id, '_pdf_company_name', isset( $woocommerce_pdf_invoice_options['pdf_company_name'] ) ? $woocommerce_pdf_invoice_options['pdf_company_name'] : '' );
					update_post_meta( $order_id, '_pdf_company_information', isset( $woocommerce_pdf_invoice_options['pdf_company_information'] ) ? $woocommerce_pdf_invoice_options['pdf_company_information'] : '' );
					update_post_meta( $order_id, '_pdf_registered_name', isset( $woocommerce_pdf_invoice_options['pdf_registered_name'] ) ? $woocommerce_pdf_invoice_options['pdf_registered_name'] : '' );
					update_post_meta( $order_id, '_pdf_registered_office', isset( $woocommerce_pdf_invoice_options['pdf_registered_office'] ) ? $woocommerce_pdf_invoice_options['pdf_registered_office'] : '' );
					update_post_meta( $order_id, '_pdf_company_number', isset( $woocommerce_pdf_invoice_options['pdf_company_number'] ) ? $woocommerce_pdf_invoice_options['pdf_company_number'] : '' );
					update_post_meta( $order_id, '_pdf_tax_number', isset( $woocommerce_pdf_invoice_options['pdf_tax_number'] ) ? $woocommerce_pdf_invoice_options['pdf_tax_number'] : '' );

				}
		
			} // woocommerce_completed_order_create_invoice

			/**
			 * [create_display_invoice_number description]
			 * @param  [type] $next_invoice [Raw invoice number]
			 * @param  [type] $id           [Order ID]
			 * @return [type]               [Formatted Invoice Number]
			 */
			public static function create_display_invoice_number( $next_invoice, $id ) { 

				// Get the invoice options
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );

				// pattern substitution
				$replacements = array(
					'{{D}}'    			=> date_i18n( 'j' ),
					'{{DD}}'   			=> date_i18n( 'd' ),
					'{{M}}'    			=> date_i18n( 'n' ),
					'{{MM}}'   			=> date_i18n( 'm' ),
					'{{YY}}'   			=> date_i18n( 'y' ),
					'{{yy}}'   			=> date_i18n( 'y' ),
					'{{YYYY}}' 			=> date_i18n( 'Y' ),
					'{{H}}'    			=> date_i18n( 'G' ),
					'{{HH}}'   			=> date_i18n( 'H' ),
					'{{N}}'    			=> date_i18n( 'i' ),
					'{{S}}'    			=> date_i18n( 's' ),
					'{{year}}' 			=> date_i18n( 'Y' ),
					'{{YEAR}}' 			=> date_i18n( 'Y' ),
					'{{invoicedate}}' 	=> WC_send_pdf::get_woocommerce_pdf_date( $id,'completed' ),
					'{{INVOICEDATE}}' 	=> WC_send_pdf::get_woocommerce_pdf_date( $id,'completed' ),
					'{D}'    			=> date_i18n( 'j' ),
					'{DD}'   			=> date_i18n( 'd' ),
					'{M}'    			=> date_i18n( 'n' ),
					'{MM}'   			=> date_i18n( 'm' ),
					'{YY}'   			=> date_i18n( 'y' ),
					'{yy}'   			=> date_i18n( 'y' ),
					'{YYYY}' 			=> date_i18n( 'Y' ),
					'{H}'    			=> date_i18n( 'G' ),
					'{HH}'   			=> date_i18n( 'H' ),
					'{N}'    			=> date_i18n( 'i' ),
					'{S}'    			=> date_i18n( 's' ),
					'{year}' 			=> date_i18n( 'Y' ),
					'{YEAR}' 			=> date_i18n( 'Y' ),
					'{invoicedate}' 	=> WC_send_pdf::get_woocommerce_pdf_date( $id,'completed' ),
					'{INVOICEDATE}' 	=> WC_send_pdf::get_woocommerce_pdf_date( $id,'completed' ),
				);
				
				$invoice_prefix = esc_html( $woocommerce_pdf_invoice_options['pdf_prefix'] );
				$invoice_prefix = str_replace( array_keys( $replacements ), $replacements, $invoice_prefix );

				$invoice_suffix = esc_html( $woocommerce_pdf_invoice_options['pdf_sufix'] );
				$invoice_suffix = str_replace( array_keys( $replacements ), $replacements, $invoice_suffix );
					
				// Add number padding if necessary
				if ( '' != $woocommerce_pdf_invoice_options['padding'] ) {
					$invnum 	= $invoice_prefix . str_pad($next_invoice, strlen($woocommerce_pdf_invoice_options['padding']), "0", STR_PAD_LEFT) . $invoice_suffix;
				} else {
					$invnum 	= $invoice_prefix . $next_invoice . $invoice_suffix;
				}
				
				return $invnum;

			}

			/**
			 * Add woocommerce-pdf-admin-css.css to admin
			 */
			function woocommerce_pdf_admin_css() {
				wp_register_style('woocommerce-pdf-admin-css', str_replace( 'classes/', '', plugins_url( 'assets/css/woocommerce-pdf-admin-css.css', __FILE__ ) ) );
				wp_enqueue_style( 'woocommerce-pdf-admin-css' );
    		}

			/**
			 * Create Invoice MetaBox
			 */	
			function invoice_details_admin_init($post_type,$post) {
				if ( get_post_meta( $post->ID, '_invoice_number_display', TRUE ) ) {
  					add_meta_box( 'woocommerce-invoice-details', __('Invoice Details', 'woocommerce-pdf-invoice'), array($this,'woocommerce_invoice_details_meta_box'), 'shop_order', 'side', 'high');
				}
			}
			
			/**
			 * Displays the invoice details meta box
			 * We include a download link, even if the order is not complete - let's the store owner view an invoice before the order is complete.
			 */
			function woocommerce_invoice_details_meta_box( $post ) {
				global $woocommerce;
	
				$data = get_post_custom( $post->id );
				?>
				<div class="invoice_details_group">
					<ul class="totals">
			
						<li class="left">
							<label><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoice' ); ?></label>
							<?php if ( get_post_meta( $post->ID, '_invoice_number_display', TRUE ) ) 
									echo get_post_meta( $post->ID, '_invoice_number_display', TRUE ); ?>
						</li>
			
						<li class="right">
							<label><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoice' ); ?></label>
							<?php 
							if ( get_post_meta( $post->ID, '_invoice_date', TRUE ) ) :
							
								$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
								$date_format = $woocommerce_pdf_invoice_options['pdf_date_format'];
		
								if ( !isset( $date_format ) || $date_format == '' ) :
									$date_format = "j F, Y";
								endif;
								
								echo date_i18n( $date_format, strtotime( get_post_meta( $post->ID, '_invoice_date', TRUE ) ) );
								
							endif;
							?>
						</li>
                        
                        <li class="left">
							<a href="<?php echo $_SERVER['REQUEST_URI'] ?>&pdfid=<?php echo $post->ID ?>"><?php _e( 'Download Invoice', 'woocommerce-pdf-invoice' ); ?></a>
						</li>
	
					</ul>
					<div class="clear"></div>
				</div><?php
				
			}

			/**
			 * Add Invoice Number column to orders page in admin
			 */
			function pdf_manage_edit_shop_order_columns( $columns ) {
				add_filter( 'manage_edit-shop_order_columns', 'invoice_column_admin_init' );
			}

			/**
			 * Add invoice number to invoice column
			 */
			function invoice_number_admin_init( $column ) {
				global $post, $woocommerce, $the_order;

				if ( $column == 'pdf_invoice_num' ) {

					if ( get_post_meta( $post->ID, '_invoice_number_display', TRUE ) ) {
						echo '<a href="'. $_SERVER['REQUEST_URI'] .'&pdfid='. $post->ID .'">' . get_post_meta( $post->ID, '_invoice_number_display', TRUE ) .'</a>';
					}

				}

			}

			/**
			 * Add Send Invoice icon to actions on orders page in admin
			 */
			function send_invoice_icon_admin_init( $actions, $order ) {
				global $post, $column, $woocommerce;

				if ( get_post_meta( $post->ID, '_invoice_number', TRUE ) ) {

					$actions['sendpdf'] = array(
						'url' 		=> wp_nonce_url( admin_url( 'admin-ajax.php?action=pdfinvoice-admin-send-pdf&order_id=' . $post->ID ), 'pdfinvoice-admin-send-pdf' ),
						'name' 		=> __( 'Send PDF', 'woocommerce-pdf-invoice' ),
						'action' 	=> "icon-sendpdf"
					);
				
				}

				return $actions;

			}
			
			/**
			 * Add Download Invoice icon to actions on orders page in admin
			 */
			function download_invoice_icon_admin_init( $actions, $order ) {
				global $post, $column, $woocommerce;

				$actions['downloadpdf'] = array(
					'url' 		=> ( $_SERVER['REQUEST_URI'] . '&pdfid=' .$post->ID ),
					'name' 		=> __( 'Download PDF', 'woocommerce-pdf-invoice' ),
					'action' 	=> "icon-downloadpdf"
				);

				return $actions;

			}

			/**
			 * Send a PDF invoice from Admin order list
			 */
			function pdfinvoice_admin_send_pdf() {

				if ( !is_admin() ) die;
				if ( !current_user_can('edit_posts') ) wp_die( __('You do not have sufficient permissions to access this page.', 'woocommerce-pdf-invoice') );
				if ( !check_admin_referer('pdfinvoice-admin-send-pdf')) wp_die( __('You have taken too long. Please go back and retry.', 'woocommerce-pdf-invoice') );
				
				$order_id = isset($_GET['order_id']) && (int) $_GET['order_id'] ? (int) $_GET['order_id'] : '';
				if (!$order_id) die;

				// Send the 'Order Complete' email again, complete with PDF invoice!
				do_action( 'woocommerce_order_status_completed' , $order_id );

				wp_safe_redirect( wp_get_referer() );

			}
			
			/**
			 * Add a PDF link to the My Account orders table
			 */
			 function my_account_pdf( $actions = NULL, $order = NULL ) {
				global $woocommerce;

				// WooCommerce 3.0 compatibility 
        		$order_id   = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id;

        		$page_id 	= version_compare( WC_VERSION, '3.0', '<' ) ? woocommerce_get_page_id( 'view_order' ) : wc_get_page_id( 'view_order' );
				 
				if ( get_post_meta( $order_id, '_invoice_number', TRUE ) ) {
				 
				 	$actions['pdf'] = array(
						'url'  => add_query_arg( 'pdfid', $order_id, get_permalink( $page_id ) ),
						'name' => __( apply_filters('woocommerce_pdf_my_account_button_label', __( 'PDF Invoice', 'woocommerce-pdf-invoice' ) ) )
					);
				 
				}
				
				return $actions;
				 
			 }
			 
			 /**
			  * Check URL for pdfaction
			  */
			 function pdf_url_check() {
				 global $woocommerce;
				 
				 if ( isset( $_GET['pdfid'] ) && !is_admin() ) {

				 	if( !class_exists('WC_send_pdf') ){
						include( 'class-pdf-send-pdf-class.php' );
					}
					
					$orderid = stripslashes( $_GET['pdfid'] );
					$order   = new WC_Order( $orderid );

					// Get the current user
					$current_user = wp_get_current_user();

					// Get the user id from the order
					$user_id = is_callable( array( $order, 'get_user_id' ) ) ? $order->get_user_id() : $order->user_id;
				
					// Allow $user_id to be filtered
					$user_id = apply_filters( 'pdf_invoice_download_user_id', $user_id, $current_user, $orderid );
					
					// Check the current user ID matches the ID of the user who placed the order
					if ( $user_id == $current_user->ID ) {
						echo WC_send_pdf::get_woocommerce_invoice( $order , 'false' );
					}
				 
				}

			 }
			 
			 /**
			  * Check Admin URL for pdfaction
			  */
			 function admin_pdf_url_check() {
				 global $woocommerce;
				 
				 if ( is_admin() && isset( $_GET['pdfid']) ) {
					
					$orderid = stripslashes( $_GET['pdfid'] );
					$order   = new WC_Order($orderid);
				
					echo WC_send_pdf::get_woocommerce_invoice( $order , 'false' );
				 
				}

			 }
			 
			 /**
			  * Add an invoice link to the thank you page
			  */
			 function invoice_link_thanks( $order_id ) {
				
				if ( get_post_meta( $order_id, '_invoice_number_display', TRUE ) ) {
					
					echo  _e('<p class="pdf-download">Download your invoice : ', 'woocommerce-pdf-invoice' );
					echo '<a href="'. add_query_arg( 'pdfid', $order_id ) .'">' . get_post_meta( $order_id, '_invoice_number_display', TRUE ) .'</a>';
					echo _e('</p>', 'woocommerce-pdf-invoice');
					
				}
						 
			 }
			 
			 /**
			  * Send a test PDF from the PDF Debugging settings
			  */
			function pdf_invoice_send_test() {
				 
				 if ( isset( $_POST['pdfemailtest'] ) && $_POST['pdfemailtest'] == '1' ) {

				 	if( !class_exists('WC_send_pdf') ){
						include( 'class-pdf-send-pdf-class.php' );
					}
					
					if ( !isset($_POST['pdf_test_nonce']) || !wp_verify_nonce($_POST['pdf_test_nonce'],'pdf_test_nonce_action') ) {
						die( 'Security check' );
					}

					WC_send_pdf::send_test_pdf();

				}
				 
			}

			/**
			 * [pdf_invoice_delete_invoices description]
			 * @return [type] [description]
			 */
			function pdf_invoice_delete_invoices() {
				$current_user = wp_get_current_user();

				// Only admins can do this!
				if( in_array('administrator', $current_user->roles) ) {
					// Delete the invoice meta from the order
					if ( isset( $_POST['pdfdelete'] ) && $_POST['pdfdelete'] == '1' && isset( $_POST['pdfdelete-confirmaion'] ) && $_POST['pdfdelete-confirmaion'] === "confirm" ) {
						
						if ( !isset($_POST['pdf_delete_nonce']) || !wp_verify_nonce($_POST['pdf_delete_nonce'],'pdf_delete_nonce_action') ) {
							die( 'Security check' );
						}

						delete_post_meta_by_key( '_invoice_number' );
						delete_post_meta_by_key( '_invoice_number_display' );
						delete_post_meta_by_key( '_invoice_date' );
						delete_post_meta_by_key( '_pdf_company_name' );
						delete_post_meta_by_key( '_pdf_company_information' );
						delete_post_meta_by_key( '_pdf_registered_name' );
						delete_post_meta_by_key( '_pdf_registered_office' );
						delete_post_meta_by_key( '_pdf_company_number' );
						delete_post_meta_by_key( '_pdf_tax_number' );

						// Delete invoice number option
						delete_option( 'woocommerce_pdf_invoice_current_invoice' );

					}

				}
				 
			}

			/**
			 * subscriptions_remove_renewal_order_meta description Subs 1.5
			 * @param  [type] $order_meta_query  [description]
			 * @param  [type] $original_order_id [description]
			 * @param  [type] $renewal_order_id  [description]
			 * @param  [type] $new_order_role    [description]
			 * @return [type]                    [description]
			 *
			 * Remove the Invoice meta keys from the list when creating a renewal order
			 * This information will be added when the invoice is created
			 */
			function subscriptions_remove_renewal_order_meta( $order_meta_query, $original_order_id, $renewal_order_id, $new_order_role ) {

				$order_meta_query .= " AND meta_key NOT IN ( 
											'_invoice_number', 
											'_invoice_number_display', 
											'_invoice_date', 
											'_pdf_company_name', 
											'_pdf_company_information', 
											'_pdf_registered_name', 
											'_pdf_registered_office', 
											'_pdf_company_number', 
											'_pdf_tax_number' 
										)";
				return $order_meta_query;
			}

			/**
			 * Remove invoice meta when creating a subscription object from an order at checkout.
			 * Subscriptions aren't true orders so they shouldn't have an invoice
			 *
			 * @return array
			 */
			function subscriptions_remove_subscription_order_meta( $order_meta, $to_order, $from_order ) {

				// only when copying from an order to a subscription
				if ( $to_order instanceof WC_Subscription && $from_order instanceof WC_Order ) {

					$pdf_invoice_meta_array = array( 
												 '_invoice_number', 
												 '_invoice_number_display', 
											 	 '_invoice_date', 
												 '_pdf_company_name', 
												 '_pdf_company_information', 
												 '_pdf_registered_name', 
												 '_pdf_registered_office', 
												 '_pdf_company_number', 
												 '_pdf_tax_number'
											);

					foreach ( $order_meta as $index => $meta ) {

						if ( in_array( $meta['meta_key'], $pdf_invoice_meta_array ) ) {
							unset( $order_meta[ $index ] );
						}

					}
				}

				return $order_meta;
			}

			/**
			 * subscriptions_remove_renewal_order_meta_2 description Subs 2.0
			 * @param  [type] $order_meta
			 *
			 * Remove the Invoice meta keys from the list when creating a renewal order
			 * This information will be added when the invoice is created
			 */
			function subscriptions_remove_renewal_order_meta_2( $order_meta ) {

				$pdf_invoice_meta_array = array( 
												 '_invoice_number', 
												 '_invoice_number_display', 
											 	 '_invoice_date', 
												 '_pdf_company_name', 
												 '_pdf_company_information', 
												 '_pdf_registered_name', 
												 '_pdf_registered_office', 
												 '_pdf_company_number', 
												 '_pdf_tax_number'
											);

				foreach ( $order_meta as $index => $meta ) {

					if ( in_array( $meta['meta_key'], $pdf_invoice_meta_array ) ) {
						unset( $order_meta[ $index ] );
					}

				}

				return $order_meta;
			}
			
		} // EOF WC_pdf_functions
		
		$GLOBALS['WC_pdf_functions'] = new WC_pdf_functions();

		function invoice_column_admin_init( $columns ) {
			global $woocommerce;
				
			$columns = 	array_slice( $columns, 0, 2, true ) +
    					array( "pdf_invoice_num" => "Invoice" ) +
    					array_slice($columns, 2, count($columns) - 1, true) ;
				
    		return $columns;

		}