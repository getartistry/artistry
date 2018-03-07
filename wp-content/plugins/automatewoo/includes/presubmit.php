<?php

namespace AutomateWoo;

/**
 * Class PreSubmit
 * @package AutomateWoo
 * @since 2.9
 */
class PreSubmit {

	/**
	 * @return array
	 */
	static function get_email_capture_selectors() {
		return apply_filters( 'automatewoo/guest_capture_fields', [
			'.woocommerce-checkout [type="email"]',
			'#billing_email',
			'.automatewoo-capture-guest-email'
		]);
	}


	/**
	 * @return array
	 */
	static function get_checkout_capture_fields() {
		return apply_filters( 'automatewoo/checkout_capture_fields', [
			'billing_first_name',
			'billing_last_name',
			'billing_company',
			'billing_phone',
			'billing_country',
			'billing_address_1',
			'billing_address_2',
			'billing_city',
			'billing_state',
			'billing_postcode'
		]);
	}


	/**
	 * @param $field_name
	 * @return bool
	 */
	static function is_checkout_capture_field( $field_name ) {
		return in_array( $field_name, self::get_checkout_capture_fields() );
	}


	/**
	 * Capture guest email
	 */
	static function ajax_capture_email() {

	    if ( AW()->session_tracker->get_detected_user_id() || ! AW()->options()->session_tracking_enabled ) {
           die;
        }

		$email = Clean::email( aw_request('email') );
		$language = Clean::string( aw_request( 'language' ) );
		$capture_page = Clean::string( aw_request( 'location' ) );
		$checkout_fields = Clean::recursive( aw_request( 'checkout_fields' ) );

		$guest = AW()->session_tracker->maybe_store_guest( $email, $language, $capture_page );

		if ( ! $guest ) {
			Ajax::send_json_error();
		}

		if ( is_array( $checkout_fields ) )  {
            foreach ( $checkout_fields as $field_name => $field_value ) {

                if ( ! self::is_checkout_capture_field( $field_name ) || empty( $field_value ) ) {
                  continue; // IMPORTANT don't save the field if it is empty
                }

                $guest->update_meta( $field_name, stripslashes( $field_value ) );
            }
        }
        else {
            $location = wc_get_customer_default_location();
            if ( $location['country'] ) {
                $guest->update_meta( 'billing_country', $location['country'] );
            }
        }

        Ajax::send_json_success([
           'guest_id' => $guest->get_id()
        ]);
	}


	/**
	 * Capture an additional field from the checkout page
	 */
	static function ajax_capture_checkout_field() {

		if ( AW()->session_tracker->get_detected_user_id() || ! AW()->options()->session_tracking_enabled ) {
			die;
        }

		$guest_id = absint( aw_request( 'guest_id' ) );
		$field_name = Clean::string( aw_request( 'field_name' ) );
		$field_value = stripslashes( Clean::string( aw_request( 'field_value' ) ) );

		$guest = AW()->session_tracker->get_current_guest();

		if ( ! $guest || $guest_id != $guest->get_id() ) {
			die;
		}

		if ( self::is_checkout_capture_field( $field_name ) ) {
			$guest->update_meta( $field_name, $field_value );
		}

		Ajax::send_json_success();
	}



	/**
	 * Add ajax email capture to checkout
	 */
	static function print_js() {

		$selectors = self::get_email_capture_selectors();
		$guest = AW()->session_tracker->get_current_guest();

		ob_start();

		?>
		(function($){

			var guest_id = <?php echo $guest ? $guest->get_id() : 0 ?>;
			var email = '';
			var $checkout_form = $( 'form.checkout' );
			var checkout_fields = <?php echo json_encode( self::get_checkout_capture_fields() ) ?>;
			var checkout_fields_data = {};
			var language = '<?php echo esc_js( Language::get_current() ) ?>';
            var capture_email_xhr;

			$.each( checkout_fields, function( i, field_name ) {
				checkout_fields_data[field_name] = '';
			});

			function captureEmail() {

				if ( ! $(this).val() || email === $(this).val() ) {
					return;
				}

				email = $(this).val();

				var data = {
					email: email,
					location: window.location.href,
					language: language,
					checkout_fields: checkout_fields_data
				};

                if ( capture_email_xhr ) {
                    capture_email_xhr.abort();
                }

                capture_email_xhr = $.post( '<?php echo Ajax::get_endpoint( 'capture_email' ) ?>', data, function( response ) {
					if ( response && response.success ) {
						guest_id = response.data.guest_id;
					}
				});
			}


			function captureCheckoutField() {

				var field_name = $(this).attr( 'name' );

				if ( ! field_name || checkout_fields.indexOf( field_name ) === -1  ) {
					return;
				}

				if ( ! $(this).val() || checkout_fields_data[field_name] == $(this).val() ) {
					return;
				}

				checkout_fields_data[field_name] = $(this).val();

				if ( guest_id ) {
					$.post( '<?php echo Ajax::get_endpoint( 'capture_checkout_field' ) ?>', {
						guest_id: guest_id,
						field_name: field_name,
						field_value: checkout_fields_data[field_name]
					});
				}
			}


			$(document).on( 'blur change', '<?php echo implode( ', ', $selectors ) ?>', captureEmail );
			$checkout_form.on( 'change', 'select', captureCheckoutField );
			$checkout_form.on( 'blur change', '.input-text', captureCheckoutField );

		})(jQuery);
		<?php
	   $js = ob_get_clean();
	   wc_enqueue_js( $js );
	}

}
