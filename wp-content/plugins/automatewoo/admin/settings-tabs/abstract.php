<?php

namespace AutomateWoo;

/**
 * @class Admin_Settings_Tab_Abstract
 */
abstract class Admin_Settings_Tab_Abstract {

	/** @var string */
	public $id;

	/** @var string */
	public $name;

	/** @var array */
	protected $messages = [];

	/** @var array */
	protected $errors = [];

	/** @var array  */
	protected $settings = [];

	/** @var bool */
	public $show_tab_title = true;

	/** @var string */
	public $prefix = 'automatewoo_';


	/**
	 * @return string
	 */
	function get_id() {
		return $this->id;
	}


	/**
	 * @return string
	 */
	function get_url() {
		return admin_url( 'admin.php?page=automatewoo-settings&tab=' . $this->get_id() );
	}


	/**
	 * Optional method
	 */
	function load_settings() {}


	/**
	 * @return array
	 */
	function get_settings() {
		if ( empty( $this->settings ) ) {
			$this->load_settings();
			do_action( 'automatewoo/admin/settings/' . $this->get_id(), $this ); // allow third parties to add settings
		}
		return $this->settings;
	}


	function output() {
		$this->output_settings_form();
	}


	function output_settings_form() {
		Admin::get_view( 'settings-form', [
			'tab' => $this
		]);
	}


	function output_settings_fields() {
		foreach ( $this->get_settings() as $setting ) {
			$this->output_settings_field( $setting );
		}
	}


	function output_settings_field( $field ) {

		if ( ! isset( $field['type'] ) || ! isset( $field['id'] ) )
			return;

		if ( ! isset( $field['class'] ) ) {
			$field['class'] = '';
		}
		if ( ! isset( $field['css'] ) ) {
			$field['css'] = '';
		}
		if ( ! isset( $field['default'] ) ) {
			$field['default'] = '';
		}

		if ( ! isset( $field['placeholder'] ) ) {
			$field['placeholder'] = '';
		}

		if ( ! isset( $field['required'] ) ) {
			$field['required'] = false;
		}

		// Custom attribute handling
		$custom_attributes = [];

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
			foreach ( $field['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		// Switch based on type
		switch ( $field['type'] ) {
			// Section Titles
			case 'title':

				echo '<div id="aw_settings_section_' . $field['id'] . '" class="aw-settings-section">';

				if ( ! empty( $field['title'] ) ) {
					echo '<h2>' . esc_html( $field['title'] ) . '</h2>';
				}
				if ( ! empty( $field['desc'] ) ) {
					echo wpautop( wptexturize( wp_kses_post( $field['desc'] ) ) );
				}
				echo '<table class="form-table">'. "\n\n";

				break;

			// Section Ends
			case 'sectionend':
				echo '</table></div>';
				break;

			// Standard text inputs and subtypes like 'number'
			case 'text':
			case 'email':
			case 'number':
			case 'password':

				$option_value = $this->get_option( $field );
				$this->_output_field_start( $field );

				?>
					<input
						name="<?php echo esc_attr( $field['id'] ); ?>"
						id="<?php echo esc_attr( $field['id'] ); ?>"
						type="<?php echo esc_attr( $field['type'] ); ?>"
						style="<?php echo esc_attr( $field['css'] ); ?>"
						value="<?php echo esc_attr( $option_value ); ?>"
						class="<?php echo esc_attr( $field['class'] ); ?>"
						placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"
						<?php echo implode( ' ', $custom_attributes ); ?>
						/> <?php $this->_output_description( $field ); ?>
				<?php

				$this->_output_field_end();
				break;

			// Textarea
			case 'textarea':

				$option_value = $this->get_option( $field );
				$this->_output_field_start( $field );
				$this->_output_description( $field );

				?>

					<textarea
						name="<?php echo esc_attr( $field['id'] ); ?>"
						id="<?php echo esc_attr( $field['id'] ); ?>"
						style="<?php echo esc_attr( $field['css'] ); ?>"
						class="<?php echo esc_attr( $field['class'] ); ?>"
						placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"
						<?php echo implode( ' ', $custom_attributes ); ?>
						><?php echo esc_textarea( $option_value );  ?></textarea>

				<?php

				$this->_output_field_end();
				break;

			// Select boxes
			case 'select' :
			case 'multiselect' :

				$option_value = $this->get_option( $field );
				$this->_output_field_start( $field );

				?>
					<select
						name="<?php echo esc_attr( $field['id'] ); ?><?php if ( $field['type'] == 'multiselect' ) echo '[]'; ?>"
						id="<?php echo esc_attr( $field['id'] ); ?>"
						style="<?php echo esc_attr( $field['css'] ); ?>"
						class="<?php echo esc_attr( $field['class'] ); ?>"
						<?php echo implode( ' ', $custom_attributes ); ?>
						<?php echo ( 'multiselect' == $field['type'] ) ? 'multiple="multiple"' : ''; ?>
						>
						<?php
							foreach ( $field['options'] as $key => $val ) {
								?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php

									if ( is_array( $option_value ) ) {
										selected( in_array( $key, $option_value ), true );
									} else {
										selected( $option_value, $key );
									}

								?>><?php echo $val ?></option>
								<?php
							}
						?>
					</select> <?php $this->_output_description( $field ); ?>
				<?php

				$this->_output_field_end();
				break;



			// Single page selects
			case 'single_select_page' :

				$this->_output_field_start( $field );

				$args = [
					'name'             => $field['id'],
					'id'               => $field['id'],
					'sort_column'      => 'menu_order',
					'sort_order'       => 'ASC',
					'show_option_none' => ' ',
					'class'            => $field['class'],
					'echo'             => false,
					'selected'         => absint( $this->get_option( $field ) )
				];

				if ( isset( $field['args'] ) ) {
					$args = wp_parse_args( $field['args'], $args );
				}

				?>
				<?php echo str_replace(' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', 'woocommerce' ) .  "' style='" . $field['css'] . "' class='" . $field['class'] . "' id=", wp_dropdown_pages( $args ) ); ?>
				<?php $this->_output_description( $field ); ?>
				<?php
				$this->_output_field_end();

				break;


			// Checkbox input
			case 'checkbox' :
				$this->_output_field_start( $field );
				$option_value = $this->get_option( $field );

				?>
					<fieldset>
						<label for="<?php echo $field['id'] ?>">
							<input
								name="<?php echo esc_attr( $field['id'] ); ?>"
								id="<?php echo esc_attr( $field['id'] ); ?>"
								type="checkbox"
								class="<?php echo esc_attr( isset( $field['class'] ) ? $field['class'] : '' ); ?>"
								value="1"
								<?php checked( $option_value, 'yes'); ?>
								<?php echo implode( ' ', $custom_attributes ); ?>
							/>
						</label> <?php $this->_output_description( $field ); ?>
					</fieldset>
					<?php
				$this->_output_field_end();
				break;


			case 'tinymce':
				$this->_output_field_start( $field );

				wp_editor( $this->get_option( $field ), $field['id'], [
					'textarea_name' => $field['id'],
					'tinymce' => true, // default to visual
					'quicktags' => true,
					'textarea_rows' => 14
				]);

				$this->_output_description( $field );
				$this->_output_field_end();

				break;
		}
	}


	private function _output_field_start( $field ) {
		?>
			<tr id="<?php echo esc_attr( $field['id'] ); ?>_field_row" valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?>
						<?php if ( $field['required'] ): ?><span class="aw-required-asterisk"></span><?php endif; ?>
					</label>
				</th>
				<td class="forminp forminp-<?php echo sanitize_title( $field['type'] ) ?>">
	                <div class="automatewoo-settings__input-wrap">
		            <?php $this->_output_tooltip( $field ); ?>
		<?php
	}


	private function _output_field_end() {
		echo '</div></td></tr>';
	}


	/**
	 * @param $field
	 */
	private function _output_tooltip( $field ) {

		if ( isset( $field['desc_tip'] ) ) {
			$field['tooltip'] = $field['desc_tip']; // backwards compat
		}

		if ( empty( $field['tooltip'] ) )
			return;

		echo Admin::help_tip( $field['tooltip'], false );
	}


	/**
	 * @param $field
	 */
	private function _output_description( $field ) {
		if ( empty( $field['desc'] ) )
			return;

		$description = $field['desc'];

		if ( $description && in_array( $field['type'], [ 'textarea', 'select' ] ) )
		{
			$description = '<p class="description" style="margin-top:2px">' . wp_kses_post( $description ) . '</p>';
		}
		elseif ( $description )
		{
			$description = '<span class="description">' . wp_kses_post( $description ) . '</span>';
		}

		echo $description;
	}



	/**
	 * @param array $field
	 * @return string
	 */
	function get_option( $field ) {

		if ( ! $field['id'] )
			return false;

		$option_name = $field['id'];
		$default = empty( $field['default'] ) ? '' : $field['default'];

		// Array value
		if ( strstr( $option_name, '[' ) ) {
			parse_str( $option_name, $option_array );

			// Option name is first key
			$option_name = current( array_keys( $option_array ) );

			// Get value
			$option_values = get_option( $option_name, '' );

			$key = key( $option_array[ $option_name ] );

			if ( isset( $option_values[ $key ] ) ) {
				$option_value = $option_values[ $key ];
			} else {
				$option_value = null;
			}

		// Single value
		}
		else {
			$option_value = get_option( $option_name, null );
		}

		if ( is_array( $option_value ) ) {
			$option_value = array_map( 'stripslashes', $option_value );
		}
		elseif ( ! is_null( $option_value ) ) {
			$option_value = stripslashes( $option_value );
		}

		return $option_value === null ? $default : $option_value;
	}


	function save() {
		$settings = $this->get_settings();
		$saved = $this->save_fields( $settings );

		if ( $saved ) {
			$this->add_message( __( 'Your settings have been saved.', 'automatewoo' ) );
		}
	}


	/**
	 * @param $settings
	 * @return bool
	 */
	function save_fields( $settings ) {

		if ( empty( $_POST ) )
			return false;

		foreach ( $settings as $option ) {
			$this->save_field( $option );
		}

		return true;
	}


	/**
	 * @param $setting
	 */
	function save_field( $setting ) {

		if ( ! isset( $setting['id'] ) || ! isset( $setting['type'] ) )
			return;

		// Get posted value.
		if ( strstr( $setting['id'], '[' ) ) {
			parse_str( $setting['id'], $option_name_array );
			$option_name  = current( array_keys( $option_name_array ) );
			$setting_name = key( $option_name_array[ $option_name ] );
			$raw_value    = isset( $_POST[ $option_name ][ $setting_name ] ) ? wp_unslash( $_POST[ $option_name ][ $setting_name ] ) : null;
		}
		else {
			$option_name  = $setting['id'];
			$setting_name = '';
			$raw_value    = isset( $_POST[ $setting['id'] ] ) ? wp_unslash( $_POST[ $setting['id'] ] ) : null;
		}

		// Format the value based on option type.
		switch ( $setting['type'] ) {
			case 'checkbox':
				$value = is_null( $raw_value ) ? 'no' : 'yes';
				break;

			case 'textarea':
			case 'tinymce':
				$value = wp_kses_post( trim( $raw_value ) );
				break;

			case 'multiselect':
			case 'multi_select_countries':
				$value = array_filter( Clean::recursive( (array) $raw_value ) );
				break;

			default :
				$value = Clean::recursive( $raw_value );
				break;
		}


		if ( is_null( $value ) ) {
			return;
		}

		// Check if option is an array and handle that differently to single values.
		if ( $option_name && $setting_name ) {

			if ( ! isset( $update_options[ $option_name ] ) ) {
				$value = get_option( $option_name, array() );
			}

			if ( ! is_array( $update_options[ $option_name ] ) ) {
				$value = array();
			}
			$value[ $setting_name ] = $value;
		}

		update_option( $option_name, $value, $setting['autoload'] );
	}


	/**
	 * @param $strong string
	 * @param string $more
	 */
    function add_message( $strong, $more = '' ) {
		$this->messages[] = [
			'strong' => $strong,
			'more' => $more
		];
	}

	/**
	 * @param $error string
	 */
	function add_error( $error ) {
		$this->errors[] = $error;
	}


	/**
	 * Output messages + errors.
	 */
	function output_messages() {

		if ( sizeof( $this->errors ) > 0 ) {
			foreach ( $this->errors as $error ) {
				echo '<div class="error"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		}
		elseif ( sizeof( $this->messages ) > 0 ) {
			foreach ( $this->messages as $message ) {
				echo '<div class="updated"><p><strong>' . esc_html( $message['strong'] ) . '</strong>'. $message['more'] . '</p></div>';
			}
		}
	}


	/**
	 * @param $id
	 * @param string $title
	 * @param string $description
	 */
	function section_start( $id, $title = '', $description = '' ) {
		$this->settings[] = [
			'type' => 'title',
			'id' => $this->prefix . $id,
			'title' => $title,
			'desc' => $description,
		];
	}


	/**
	 * @param $id
	 */
   function section_end( $id ) {
		$this->settings[] = [
			'type' => 'sectionend',
			'id' => $this->prefix . $id,
		];
	}


	/**
	 * @param $id
	 * @param $args
	 */
   function add_setting( $id, $args ) {

		$setting = [
			'id' => $this->prefix . $id,
			'autoload' => false
		];

		$default_value = $this->get_default( $id );

		if ( $default_value !== false ) {

			if ( in_array( $args['type'], [ 'text', 'textarea', 'number' ] ) ) {
				$setting['placeholder'] = $default_value;
			}
			else {
				$setting['default'] = $default_value;
			}
		}

		$setting = array_merge( $setting, $args );
		$this->settings[] = $setting;
	}


	/**
	 * @param $id
	 * @return mixed
	 */
    protected function get_default( $id ) {
		return isset( AW()->options()->defaults[ $id ] ) ? AW()->options()->defaults[ $id ] : false;
	}

}
