<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Elementor\Base_Data_Control' ) ) return;

/**
 * A Font Icon select box.
 */
class CASE27_Elementor_Control_Icon extends Base_Data_Control {

	public static function get_icons() {
		$icons = [];

		$font_awesome_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/font-awesome.php';
		$material_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/material-icons.php';
		$theme_icons = require CASE27_INTEGRATIONS_DIR . '/27collective/icons/theme-icons.php';

		foreach ($font_awesome_icons as $icon) {
			$icons["fa {$icon}"] = str_replace('fa-', '', $icon);
		}

		foreach ($material_icons as $icon) {
			$icons["material-icons {$icon}"] = $icon;
		}

		foreach ($theme_icons as $icon) {
			$icons[$icon] = str_replace('icon-', '', $icon);
		}

		return $icons;
	}

	/**
	 * Retrieve icon control type.
	 */
	public function get_type() {
		return 'icon';
	}

	/**
	 * Retrieve icons control default settings.
	 */
	protected function get_default_settings() {
		return [
			'options' => self::get_icons(),
		];
	}

	/**
	 * Render icons control output in the editor.
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<select id="<?php echo $control_uid; ?>" class="elementor-control-icon" data-setting="{{ data.name }}" data-placeholder="<?php _e( 'Select Icon', 'elementor' ); ?>">
					<option value=""><?php _e( 'Select Icon', 'elementor' ); ?></option>
					<# _.each( data.options, function( option_title, option_value ) { #>
					<option value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{ data.description }}</div>
		<# } #>
		<?php
	}
}

add_action('elementor/controls/controls_registered', function($el) {
	$el->register_control('icon', new CASE27_Elementor_Control_Icon);
});