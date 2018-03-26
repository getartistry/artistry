<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Styled_Maps extends Widget_Base {

		public function get_name() {
			return 'ep_styled_maps';
		}

		public function get_title() {
			return __( 'Google Maps Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-maps';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_map',
				[
					'label' => __( 'Google Maps Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'latitude',
				[
					'label' => __( 'Latitude', 'elements-plus' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => '',
					'default' => '37.585636',
					'label_block' => true,
				]
			);

			$this->add_control(
				'longitude',
				[
					'label' => __( 'Longitude', 'elements-plus' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => '',
					'default' => '26.127548',
					'label_block' => true,
				]
			);


			$this->add_control(
				'zoom',
				[
					'label' => __( 'Zoom Level', 'elements-plus' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 10,
					],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 20,
						],
					],
				]
			);

			$this->add_control(
				'height',
				[
					'label' => __( 'Height', 'elements-plus' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 400,
					],
					'range' => [
						'px' => [
							'min' => 40,
							'max' => 1440,
						],
					],
				]
			);

			$this->add_control(
				'marker',
				[
					'label' => __( 'Custom Marker', 'elements-plus' ),
					'type' => Controls_Manager::MEDIA,
				]
			);

			$this->add_control(
				'info',
				[
					'label'   => __( 'Map Info Window', 'elements-plus' ),
					'type'    => Controls_Manager::TEXTAREA,
					'placeholder' => __( 'Fill any info you want to display when the map marker is clicked.', 'elements-plus' ),
				]
			);

			require_once ELEMENTS_PLUS_PATH . 'inc/map-styles.php';

			$this->add_control(
				'style',
				[
					'label'   => __( 'Map Style', 'elements-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => apply_filters( 'ep_map_styles', $ep_map_styles ),
				]
			);

			$this->add_control(
				'user_style',
				[
					'label' => __( 'User Style', 'elements-plus' ),
					'type'  => Controls_Manager::TEXTAREA,
					'placeholder' => __( 'Paste the javascript style array from your preferred Snazzy Maps style here.', 'elements-plus' ),
				]
			);

			$this->add_control(
				'prevent_scroll',
				[
					'label'     => __( 'Prevent Scroll', 'elements-plus' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'label_on'  => __( 'Yes', 'elements-plus' ),
					'label_off' => __( 'No', 'elements-plus' ),
				]
			);

			$this->add_control(
				'view',
				[
					'label' => __( 'View', 'elements-plus' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);

			$this->end_controls_section();
		}

		protected function render() {
			$settings = $this->get_settings();

			if ( empty( $settings['latitude'] ) || empty( $settings['longitude'] ) )
				return;

			if ( 0 === absint( $settings['zoom']['size'] ) )
				$settings['zoom']['size'] = 10;

			$pointer_events = 'yes' === $settings['prevent_scroll'] ? 'false' : 'true';

			$data_error = '<p style="text-align: center">' . __( "If you can't see the map please try adding a map API key under Elementor > Elements Plus .", 'elements-plus' ) . '</p>';

			$map_style = ! empty( $settings['user_style'] ) ? $settings['user_style'] : elements_plus_get_map_style( $settings['style'] );

		?>
			<div id="map-<?php echo esc_attr( $this->get_id() ); ?>" style="width:100%;height:<?php echo esc_attr( $settings['height']['size'] ); ?>px;background-color: grey;" data-latitude="<?php echo esc_attr( $settings['latitude'] ); ?>" data-longitude="<?php echo esc_attr( $settings['longitude'] ); ?>" data-zoom="<?php echo esc_attr( $settings['zoom']['size'] ); ?>" data-style="<?php echo esc_attr( $map_style ); ?>" data-scroll="<?php echo esc_attr( $pointer_events ); ?>" data-icon="<?php echo esc_url( $settings['marker']['url'] ); ?>" data-info="<?php echo esc_html( $settings['info'] ); ?>" data-error="<?php echo esc_attr( $data_error ); ?>"></div>
			<script>
				jQuery(document).ready(function(){
					jQuery(document).trigger('elementor/render/ep_styled_maps','#map-<?php echo esc_attr( $this->get_id() ); ?>');
				});
			</script>
		<?php
		}

		protected function _content_template() {}
	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_Styled_Maps() );
	} );
