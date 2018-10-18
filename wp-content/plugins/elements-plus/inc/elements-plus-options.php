<?php

class Elements_Plus extends \Elementor\Settings {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ], 502 );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
	}

	function add_admin_menu() {

		add_submenu_page( \Elementor\Settings::PAGE_ID, 'ElementsPlus', __( 'Elements <em>Plus!</em>', 'elements-plus' ), 'manage_options', 'elements_plus', [ $this, 'options_page' ] );
	}

	function settings_init() {

		$args = array(
				'sanitize_callback' => 'elements_plus_sanitize_settings',
		);

		register_setting( 'ElementsPlus', 'elements_plus_settings', $args );

		add_settings_section(
			'elements_plus_settings_section',
			__( 'Custom Widgets For Elementor', 'elements-plus' ),
			[ $this, 'settings_section_callback' ],
			'ElementsPlus'
		);

		add_settings_field(
			'checkbox_audioigniter',
			__( 'AudioIgniter <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_audioigniter_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_dual_input',
			__( 'Button <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_dual_input_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_cta',
			__( 'Call to Action <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_cta_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_flipclock',
			__( 'FlipClock <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_flipclock_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_justified',
			__( 'Gallery <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_justified_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_maps',
			__( 'Google Maps <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_maps_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'api_maps',
			__( 'Google Maps API Key', 'elements-plus' ),
			[ $this, 'api_maps_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_icon',
			__( 'Icon <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_icon_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_image_comparison',
			__( 'Image Comparison <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_image_comparison_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_image_hover_effects',
			__( 'Image Hover Effects <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_image_hover_effects_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_instagram',
			__( 'Instagram <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_instagram_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_label',
			__( 'Label <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_label_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_preloader',
			__( 'Preloader <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_preloader_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_scheduled',
			__( 'Scheduled <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_scheduled_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

		add_settings_field(
			'checkbox_tooltip',
			__( 'Tooltip <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_tooltip_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);
	
		add_settings_field(
			'checkbox_video_slider',
			__( 'YouTube Slideshow <em>Plus!</em>', 'elements-plus' ),
			[ $this, 'checkbox_video_slider_render' ],
			'ElementsPlus',
			'elements_plus_settings_section'
		);

	}

	function checkbox( $id ) {

		$options = elements_plus_sanitize_settings( get_option( 'elements_plus_settings' ) );
		?>
			<input type='checkbox' name='elements_plus_settings[<?php echo esc_attr( $id ); ?>]' <?php checked( $options[ $id ] ); ?> value='1'>
		<?php

	}

	function checkbox_audioigniter_render() {
		if ( ! elements_plus_is_audioigniter_active() ) { ?>
			<p><?php
			/* translators: %s is a URL. */
			echo wp_kses( sprintf( __( '<a href="%s" target="_blank">AudioIgniter</a> is not active. Install and activate the plugin to use this module.', 'elements-plus' ), 'https://wordpress.org/plugins/audioigniter/' ), array(
					'a' => array(
						'href'   => true,
						'target' => true,
					),
			) ); ?></p>
		<?php } else {
			$this->checkbox( 'checkbox_audioigniter' );
		}
	}

	function checkbox_dual_input_render() {
		$this->checkbox( 'checkbox_dual_input' );
	}

	function checkbox_cta_render() {
		$this->checkbox( 'checkbox_cta' );
	}

	function checkbox_flipclock_render() {
		$this->checkbox( 'checkbox_flipclock' );
	}

	function checkbox_justified_render() {
		$this->checkbox( 'checkbox_justified' );
	}

	function checkbox_maps_render() {
		$this->checkbox( 'checkbox_maps' );
	}

	function checkbox_icon_render() {
		$this->checkbox( 'checkbox_icon' );
	}

	function checkbox_image_comparison_render() {
		$this->checkbox( 'checkbox_image_comparison' );
	}

	function checkbox_image_hover_effects_render() {
		$this->checkbox( 'checkbox_image_hover_effects' );
	?>
	<span><?php echo wp_kses(__('Caution: These effects are WebGL based and may hurt the performance of your pages. Do not use more than 4 - 5 instances of this widget in any given page. ', 'elements-plus'), ''); ?></span>
	<?php		
	}

	function checkbox_instagram_render() {
		if ( ! elements_plus_is_wp_instagram_active() ) { ?>
			<p><?php
			/* translators: %s is a URL. */
			echo wp_kses( sprintf( __( '<a href="%s" target="_blank">WP Instagram Widget</a> is not active. Install and activate the plugin to use this module.', 'elements-plus' ), 'https://wordpress.org/plugins/wp-instagram-widget/' ), array(
					'a' => array(
						'href'   => true,
						'target' => true,
					),
			) ); ?></p>
		<?php } else {
			$this->checkbox( 'checkbox_instagram' );
		}
	}

	function checkbox_label_render() {
		$this->checkbox( 'checkbox_label' );
	}

	function checkbox_preloader_render() {
		$this->checkbox( 'checkbox_preloader' );
	}

	function checkbox_scheduled_render() {
		$this->checkbox( 'checkbox_scheduled' );
	?>
		<span><?php echo wp_kses( __( 'With this module you can set date/time-based display restrictions on every module available. Check the "Schedule" section in the "Advanced" tab of your modules.', 'elements-plus'), '' ); ?></span>
	<?php
	}

	function checkbox_tooltip_render() {
		$this->checkbox( 'checkbox_tooltip' );
	?>
		<span><?php echo wp_kses( __( 'This option will enable a tooltip section in the following Elementor default widgets: Heading, Button, Icon, and Icon Box.', 'elements-plus'), '' ); ?></span>
	<?php
	}

	function checkbox_video_slider_render() {
		$this->checkbox( 'checkbox_video_slider' );
	}

	function api_maps_render() {

		$options = elements_plus_sanitize_settings( get_option( 'elements_plus_settings' ) );
		$api_key = $options['api_maps'];
		?>
		<p style="margin-bottom: 10px;">
			<?php
				/* translators: %s is a URL. */
				echo wp_kses( sprintf( __( 'Paste your Google Maps API Key below. This is <strong>required</strong> in order to get the maps widget working. For info on how to get an API key read <a href="%s" target="_blank">this article</a>.', 'elements-plus' ), 'https://www.cssigniter.com/kb/generate-a-google-maps-api-key/' ), array(
					'strong' => array(),
					'a' => array(
						'href'   => true,
						'target' => true,
					),
				) );
			?>
		</p>
		<input type='text' style="min-width: 350px;" name='elements_plus_settings[api_maps]' value="<?php echo esc_attr( $api_key ); ?>">
		<?php

	}

	function settings_section_callback() {

		echo '<p>' . esc_html_e( 'Use the checkboxes below to enable or disable the custom elements.', 'elements-plus' ) . '</p>';

	}

	function options_page() {

		?>
		<div class="elements-plus-container">

			<div class="elements-plus-content">
				<form action='options.php' method='post' class="elements-plus-form">

					<h1><?php esc_html_e( 'Elements Plus!', 'elements-plus' ); ?></h1>

					<?php settings_errors(); ?>

					<?php
						settings_fields( 'ElementsPlus' );
						do_settings_sections( 'ElementsPlus' );
						submit_button();
					?>

				</form>
			</div><!-- /elements-plus-content -->
			<div class="elements-plus-sidebar">
				<a href="https://www.cssigniter.com/"><img
							src="<?php echo esc_url( ELEMENTS_PLUS_URL . 'assets/images/banner2.jpg' ); ?>"
							class="elements-plus-banner"/></a>
			</div>

		</div><!-- /elements-plus-container -->
		<?php

	}

}

new Elements_Plus();
