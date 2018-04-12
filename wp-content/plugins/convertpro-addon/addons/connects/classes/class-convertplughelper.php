<?php
/**
 * Convert Pro Services Helper Class
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

/**
 * ConvertPlug data handling class that deals
 * with all database operations.
 *
 * @since 1.0.0
 */
final class ConvertPlugHelper {

	/**
	 * Returns an array of account data for all integrated services.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	static public function get_services() {
		return get_terms(
			CP_CONNECTION_TAXONOMY, array(
				'hide_empty' => false,
			)
		);
	}

	/**
	 * Gets all saved services.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	static public function get_saved_services() {
		$return_array = array();

		$terms = get_terms(
			CP_CONNECTION_TAXONOMY, array(
				'hide_empty' => false,
			)
		);

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $key => $term ) {
				if ( isset( $term->term_id ) ) {
					$return_array[] = $term->name;
				}
			}
		}

		return $return_array;
	}

	/**
	 * Updates the account data for an integrated service.
	 *
	 * @since 1.0.0
	 * @param string $service The service id.
	 * @param string $account The account name.
	 * @param array  $data The account data.
	 * @return void
	 */
	static public function update_services( $service, $account, $data ) {
		$services = self::get_services();
		$account  = sanitize_text_field( $account );

		if ( ! isset( $services[ $service ] ) ) {
			$services[ $service ] = array();
		}

		$services[ $service ][ $account ] = $data;

		update_option( '_cp_v2_services', $services );
	}

	/**
	 * Deletes an account for an integrated service.
	 *
	 * @since 1.0.0
	 * @param string $account The account name.
	 * @return integer
	 */
	static public function delete_service_account( $account ) {
		$services = self::get_services();

		$term = get_term_by( 'slug', $account, CP_CONNECTION_TAXONOMY );
		if ( isset( $term->term_id ) ) {
			return wp_delete_term( $term->term_id, CP_CONNECTION_TAXONOMY );
		}

		return -1;
	}

	/**
	 * Returns $_POST data
	 *
	 * @since 1.0.0
	 * @return array()
	 */
	static public function get_post_data() {
		return ConvertPlugHelper::sanitize_post_data( $_POST );
	}

	/**
	 * Returns Sanitized $_POST data
	 *
	 * @since 1.0.0
	 * @param array $array Posted array.
	 * @return array()
	 */
	static public function sanitize_post_data( &$array ) {

		if ( is_array( $array ) ) {

			foreach ( $array as &$value ) {

				if ( ! is_array( $value ) ) {

					// Sanitize if value is not an array.
					$value = sanitize_text_field( $value );

				} else {
					// Go inside this function again.
					ConvertPlugHelper::sanitize_post_data( $value );
				}
			}
		}
		return $array;
	}

	/**
	 * Renders connection meta data.
	 *
	 * @since 1.0.0
	 * @param string $connection The connection slug.
	 * @return array
	 */
	static public function get_connection_data( $connection = '' ) {

		if ( '' != $connection ) {
			$term = get_term_by( 'slug', $connection, CP_CONNECTION_TAXONOMY );
			if ( isset( $term->term_id ) ) {

				return get_term_meta( $term->term_id );
			}
		}

		return array();
	}

	/**
	 * Renders html with respective input fields
	 *
	 * @since 1.0.0
	 * @param string $id The connection slug.
	 * @param array  $settings The input type settings array.
	 * @return void
	 */
	static public function render_input_html( $id = '', $settings = array() ) {
		ob_start();

		if ( '' != $id && ! empty( $settings ) ) {

			$class = ( isset( $settings['class'] ) ) ? $settings['class'] : '';
			?>
			<div class="cp-api-fields cp-<?php echo esc_attr( $id ); ?>-wrap <?php echo $class; ?>">
			<?php
			switch ( $settings['type'] ) {
				case 'text':
					$default_value = ( isset( $settings['default'] ) ) ? $settings['default'] : '';
					?>
					<input type="text" name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $settings['class'] ); ?> cp-customizer-input" value="<?php echo esc_attr( $default_value ); ?>"/>
					<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $settings['label'] ); ?></label>
					<?php
					break;

				case 'text-wrap':
					$default_value = ( isset( $settings['default'] ) ) ? $settings['default'] : '';
					?>
					<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $settings['label'] ); ?>
					<?php
					if ( isset( $settings['help'] ) && '' != $settings['help'] ) {
					?>
						<span class="cp-tooltip-icon has-tip" data-position="top" style="cursor: help;" title="<?php echo $settings['help']; ?>"><i class="dashicons dashicons-editor-help"></i></span>
					<?php
					}
					?>
					</label>
					<p><input type="text" name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $settings['class'] ); ?> cp-customizer-input" value="<?php echo esc_attr( $default_value ); ?>"/></p>
					<?php if ( isset( $settings['note'] ) && '' != $settings['note'] ) { ?>
						<p class="cpro-service-note"><?php echo esc_attr( $settings['note'] ); ?></p>
					<?php
}
					break;

				case 'checkbox':
					$checked = ( isset( $settings['default'] ) ) ? ( ( true == $settings['default'] ) ? 'checked="checked"' : '' ) : '';
					?>
					<p><input type="checkbox" <?php echo $checked; ?> name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $settings['class'] ); ?> cp-customizer-input" />
					<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $settings['label'] ); ?></label></p>
					<?php
					break;

				case 'select':
				?>
				<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $settings['label'] ); ?></label>
				<p>
					<select name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" class="cp-customizer-select">
					<?php foreach ( $settings['options'] as $key => $value ) { ?>

						<?php $selected = ( isset( $settings['default'] ) ) ? ( ( $key == $settings['default'] ) ? 'selected="selected"' : '' ) : ''; ?>

						<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
					<?php } ?>
					</select>
				</p>
				<?php
					break;

				case 'multi-select':
				?>
				<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $settings['label'] ); ?></label>
				<p>
					<select name="<?php echo esc_attr( $id ); ?>" class="cp-customizer-select cp-multi-select" multiple>
					<?php foreach ( $settings['options'] as $key => $value ) { ?>

						<?php $selected = ( isset( $settings['default'] ) && is_array( $settings['default'] ) ) ? ( ( in_array( $key, $settings['default'] ) ) ? 'selected="selected"' : '' ) : ''; ?>

						<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
					<?php } ?>
					</select>
					<script type="text/javascript">jQuery( '.cp-multi-select' ).select2();</script>
				</p>
				<?php
					break;

				case 'radio':
				?>
				<label><?php echo esc_attr( $settings['label'] ); ?></label>
				<div class="cp-customizer-radio-wrap">
					<?php foreach ( $settings['options'] as $key => $value ) { ?>

						<?php $selected = ( isset( $settings['default'] ) ) ? ( ( $key == $settings['default'] ) ? 'checked="checked"' : '' ) : ''; ?>
						<div class="cp-customizer-radio cp-customizer-list-radio">
							<input type="radio" id="<?php echo $key; ?>" value="<?php echo $key; ?>" name="<?php echo esc_attr( $id ); ?>" <?php echo $selected; ?> data-account-name="<?php echo $value; ?>">
							<label for="<?php echo $key; ?>"><?php echo $value; ?></label>
							<i class="cp-customizer-remove-account dashicons dashicons-trash" data-account-slug="<?php echo $key; ?>" data-account-name="<?php echo $value; ?>" data-isassociated="<?php echo $settings['association'][ $key ]; ?>"></i>
						</div>
					<?php } ?>
				</div>
				<?php
					break;

				default:
					break;
			}

			if ( isset( $settings['desc'] ) && '' != $settings['desc'] ) {
			?>
			<div class="cp-api-fields-desc"><?php echo $settings['desc']; ?></div>
			<?php
			}
			?>
			</div>
			<?php

		}
		$input = ob_get_clean();
		echo $input;
	}

	/**
	 * Returns an array of cp mapping data
	 *
	 * @since 1.0.0
	 * @param array $mapping_array Mapping array.
	 * @return array
	 */
	static public function get_mapping_array( $mapping_array ) {
		$return_array = array();
		if ( ! empty( $mapping_array ) ) {
			foreach ( $mapping_array as $key => $value ) {
				if ( false !== strpos( $key, 'cp_mapping' ) ) {

					$key                  = str_replace( 'cp_mapping', '', $key );
					$key                  = str_replace( '{', '', $key );
					$key                  = str_replace( '}', '', $key );
					$return_array[ $key ] = $value;

				}
			}
		}
		return $return_array;
	}

	/**
	 * Returns an array of json decoded data
	 *
	 * @since 1.0.0
	 * @param array $mapping_array Mapping array.
	 * @return array
	 */
	static public function get_decoded_array( $mapping_array ) {
		$data         = ( ! is_array( $mapping_array ) && ! is_object( $mapping_array ) ) ? json_decode( $mapping_array ) : array();
		$return_array = array();
		$mailer       = '';

		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( 'cp-integration-service' == $value->name ) {
					$mailer = $value->value;
					break;
				}
				$return_array[ $value->name ] = $value->value;
			}

			if ( 'infusionsoft' == $mailer ) {
				foreach ( $data as $key => $value ) {
					if ( 'infusionsoft_tags' == $value->name ) {
						$return_array[ $value->name ][] = $value->value;
					} else {
						$return_array[ $value->name ] = $value->value;
					}
				}
			} elseif ( 'ontraport' == $mailer ) {
				foreach ( $data as $key => $value ) {
					if ( 'ontraport_tags' == $value->name ) {
						$return_array[ $value->name ][] = $value->value;
					} else {
						$return_array[ $value->name ] = $value->value;
					}
				}
			} elseif ( 'mautic' == $mailer ) {
				foreach ( $data as $key => $value ) {
					if ( 'mautic_segment' == $value->name ) {
						$return_array[ $value->name ][] = $value->value;
					} else {
						$return_array[ $value->name ] = $value->value;
					}
				}
			} elseif ( 'mailchimp' == $mailer ) {
				foreach ( $data as $key => $value ) {
					if ( 'mailchimp_groups' == $value->name ) {
						$return_array[ $value->name ][] = $value->value;
					} else {
						$return_array[ $value->name ] = $value->value;
					}
				}
			} elseif ( 'sendlane' == $mailer ) {
				foreach ( $data as $key => $value ) {
					if ( 'sendlane_tags' == $value->name ) {
						$return_array[ $value->name ][] = $value->value;
					} else {
						$return_array[ $value->name ] = $value->value;
					}
				}
			} else {
				foreach ( $data as $key => $value ) {
					$return_array[ $value->name ] = $value->value;
				}
			}
		}

		return $return_array;
	}
}
