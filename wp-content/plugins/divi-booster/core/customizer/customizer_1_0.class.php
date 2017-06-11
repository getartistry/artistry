<?php // Class for interacting with the theme customizer

if (!class_exists('booster_customizer_1_0')) { 

	class booster_customizer_1_0 {
		
		var $slug;
		var $css = '';
		var $refresh_js = array();
		var $controls = array();
		
		function __construct($slug) {
			$this->slug = $slug;
			add_action('customize_register', array($this, 'customizer_init'));
		}

		function customizer_init($wp_customize) {
			
			// Add the controls
			foreach($this->controls as $option=>$settings) {
				if ($settings['type']=='rgba') { 
					$this->add_control_rgba($wp_customize, @$settings['label'], @$settings['section'], $option, @$settings['default']); 
				}
			}
			
			// Add the refresh javascript
			if ($wp_customize->is_preview()) { 
				add_action('wp_footer', array($this, 'output_refresh_js'), 21); 
			}
			
		}
		
		// === Controls === //
	
		function add_control($type, $option, $settings) {
			$settings['type'] = $type;
			$this->controls[$option] = $settings;
		}
	
		function add_control_rgba($wp_customize, $label, $section, $option, $default) {
			$wp_customize->add_setting($option, array(
				'default' => $default,
				'type' => 'option',
				'capability' => 'edit_theme_options',
				'transport' => 'postMessage',
			));
		 
			$wp_customize->add_control(new ET_Divi_Customize_Color_Alpha_Control($wp_customize, $option, array(
				'label' => $label,
				'section' => $section,
				'settings' => $option,
				'priority' => 11
				))
			);
		}
		
		// === Refresh JS === //
		
		function add_refresh_js($option, $js) {
			$this->refresh_js[$option] = $js;
		}
		
		// Output the refresh js - binds current value of option to "option" variable
		function output_refresh_js() { ?>
			<script>jQuery(function($){ 
				<?php foreach($this->refresh_js as $option=>$js) { ?>
					wp.customize('<?php echo htmlentities($option); ?>',function(value) {
						value.bind(function(option) {
							<?php echo $js; ?>
						});
					});
				<?php } ?>
			});
			</script>
		<?php 
		}

	}

}