<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "box_shadow".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'box_shadow', 'cp_v2_box_shadow_settings_field' );
	add_action( 'admin_enqueue_scripts', 'framework_box_shadow_admin_styles' );
}

/**
 * Function Name: framework_box_shadow_admin_styles.
 * Function Description: framework_box_shadow_admin_styles.
 *
 * @param string $hook string parameter.
 */
function framework_box_shadow_admin_styles( $hook ) {
	$dev_mode = get_option( 'cp_dev_mode' );
	if ( '1' == $dev_mode ) {
		wp_enqueue_script( 'cp-box_shadow-script', plugins_url( 'box_shadow.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'cp-box_shadow-style', plugins_url( 'box_shadow.css', __FILE__ ) );
	}
}

/**
 * Function Name: cp_v2_box_shadow_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $value string parameter.
 */
function cp_v2_box_shadow_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$options    = isset( $settings['options'] ) ? $settings['options'] : array(
		'None'   => 'none',
		'Inset'  => 'inset',
		'Outset' => 'outset',
	);

	$suffix      = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
	$shadow_type = '';

	$shadow_type = explode( '|', $value );
	$shadow_type = explode( ':', $shadow_type[0] );
	$shadow_type = $shadow_type[1];
	$map_style   = isset( $settings['map_style'] ) ? json_encode( $settings['map_style'] ) : '';

	$output = '<div class="cp-box-shadow-container cp-field-box-shadow-container">
				<input type="hidden" data-mapstyle="' . htmlspecialchars( $map_style ) . '" name="' . $input_name . '" id="' . $input_name . '" value="' . $value . '" class="form-control cp-input ' . $class . ' cp-box-shadow ' . $input_name . '" for="" data-type="' . $type . '" /><span class="cp-edit-helper">' . $suffix . '</span>
				<div class="box">
				  <div class="holder">
				    <div class="frame">
				      <div class="cp-setting-block">
				        <div class="row option-panel"> <label>' . __( 'Shadow Effects', 'convertpro' ) . '</label></span>
				            <div class="cp-edit-field-inputs shadow_type">
				              <select name="shadow_type" id="cp_shadow_type" class="form-control cp-input cp-select shadow_type">';
	foreach ( $options as $text_val => $val ) {
		$selected = '';
		if ( '' !== $shadow_type && $val == $shadow_type ) {
			$selected = ' selected="selected"';
		}
		$output .= '<option class="cp_' . $val . '" ' . $selected . ' value="' . $val . '">' . htmlspecialchars( $text_val ) . '</option>';
	}
								$output .= '</select>  

				           </div>
				        </div>
				      </div>
				      <div class="cp-shadow-options ">
				        <div class="cp-setting-block">
							<label for="shadow-color">' . __( 'Shadow Color', 'convertpro' ) . '</label>
					       	<div class="row color-row">
								<div class="cp-edit-field-inputs bx-color-input">
								  <input id="shadow-color" class="cs-wp-color-picker " data-default-color="" type="text" value="">
								</div>
							</div>
						</div>
						

				        	<div class="cp-form-border-wrap">
								<label for="blur-radius">' . __( 'Blur Radius', 'convertpro' ) . '</label>
								 <div class="cp-setting-block cp-slider-block">				  
									<div class="cp-param-input slider-input">
									  <input id="blur-radius" class="sm-small-inputs form-control" type="number" min="0" value="">
									  <label class="align-right" for="blur-radius">' . __( 'px', 'convertpro' ) . '</label>
									</div>
									<div id="slider-blur-bs" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a><span class="range-quantity" ></span>
									</div>
								</div>
							</div>
							<div class="cp-form-border-wrap">
					             <label for="spread-field">' . __( 'Spread Radius', 'convertpro' ) . '</label>
						         <div class="cp-setting-block cp-slider-block">
						            <div class="cp-param-input slider-input">
						              <input id="spread-field" class="sm-small-inputs form-control" type="number" value="">   
						              <label class="align-right" for="spread-field">' . __( 'px', 'convertpro' ) . '</label>
						            </div>
						          <div id="slider-spread-field" class="row-s slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a><span class="range-quantity" ></span>
						          </div>
						        </div> 
					        </div>
					        <div class="cp-form-border-wrap">
						        <label for="horizontal-length">' . __( 'Horizontal Length', 'convertpro' ) . '</label>
						        <div class="cp-setting-block cp-slider-block">
						            <div class="cp-param-input slider-input">
						              <input id="horizontal-length" class="sm-small-inputs form-control" type="number" value="">
						              <label class="align-right" for="horizontal-length">' . __( 'px', 'convertpro' ) . '</label>
						            </div>
						          <div id="slider-horizontal-bs" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a><span class="range-quantity" ></span>
						          </div>
					          	</div>
				          	</div>
				          	<div class="cp-form-border-wrap">
					          <label for="vertical-length">' . __( 'Vertical Length', 'convertpro' ) . '</label>
					          <div class="cp-setting-block cp-slider-block">
					            <div class="cp-param-input slider-input">
					              <input id="vertical-length" class="sm-small-inputs form-control" type="number" value="">
					              <label class="align-right" for="vertical-length">' . __( 'px', 'convertpro' ) . '</label>
					            </div>
						          <div id="slider-vertical-bs" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a><span class="range-quantity" ></span>
						          </div>
				      		  </div>
			      		  </div>
				      </div>  
				    </div>
				  </div>
				</div>
				</div>';
	return $output;
}
