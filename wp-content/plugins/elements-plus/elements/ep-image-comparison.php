<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Image_Comparison extends Widget_Base {

		public function get_name() {
			return 'ep-image-comparison';
		}

		public function get_title() {
			return __( 'Image Comparison Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-image_comparison';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_title',
				[
					'label' => __( 'Image Comparison Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'image_1',
				[
					'label' => __( 'Choose First Image', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
				]
			);

			$this->add_control(
				'image_2',
				[
					'label' => __( 'Choose Second Image', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
				]
			);

			$this->add_control(
				'offset',
				[
					'label' => __( 'Image Offset', 'elements-plus' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ '' ],
					'range' => [
						'' => [
							'min' => 0.1,
							'max' => 0.9,
							'step' => 0.1,
						],
					],
					'default' => [
						'unit' => '',
						'size' => 0.5,
					],
				]
			);

			$this->add_control(
				'orientation',
				[
					'label' => __( 'Orientation', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => [
						'horizontal'  => __( 'Horizontal', 'elements-plus' ),
						'vertical' => __( 'Vertical', 'elements-plus' ),
					],
				]
			);

			$this->add_control(
				'before_label',
				[
					'label' => __( 'Before Label', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Before', 'elements-plus' ),
				]
			);

			$this->add_control(
				'after_label',
				[
					'label' => __( 'After Label', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'After', 'elements-plus' ),
				]
			);

			$this->add_control(
				'overlay',
				[
					'label' => __( 'Overlay', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'false',
					'options' => [
						'false'  => __( 'Show', 'elements-plus' ),
						'true' => __( 'Hide', 'elements-plus' ),
					],
				]
			);

			$this->add_control(
				'hover',
				[
					'label' => __( 'Move Slider On Hover', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'your-plugin' ),
					'label_off' => __( 'No', 'your-plugin' ),
					'return_value' => 'true',
					'default' => 'false',
				]
			);

			$this->add_control(
				'click',
				[
					'label' => __( 'Move Slider By Clicking Anywhere', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'your-plugin' ),
					'label_off' => __( 'No', 'your-plugin' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			$this->add_control(
				'handle',
				[
					'label' => __( 'Move Slider Only With Handle', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'your-plugin' ),
					'label_off' => __( 'No', 'your-plugin' ),
					'return_value' => 'true',
					'default' => 'false',
				]
			);

			$this->end_controls_section();

		}

		protected function render() {
			$settings     = $this->get_settings();
			$offset       = $settings['offset']['size'];
			$orientation  = $settings['orientation'];
			$before_label = $settings['before_label'];
			$after_label  = $settings['after_label'];
			$overlay      = $settings['overlay'];
			$hover        = $settings['hover'];
			$handle       = $settings['handle'];
			$click        = $settings['click'];

			if ( 'true' === $handle ) {
				$click = false;
				$hover = false;
			}

			$image_1_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'large', 'image_1' );
			$image_2_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'large', 'image_2' );
			?>
				<div id="ep-ic-<?php echo esc_attr( $this->get_id() ); ?>" data-offset="<?php echo floatval( $offset ); ?>" data-orientation="<?php echo esc_attr( $orientation ); ?>" data-before-label="<?php echo esc_attr( $before_label ); ?>" data-after-label="<?php echo esc_attr( $after_label ); ?>" data-overlay="<?php echo esc_attr( $overlay ); ?>" data-hover="<?php echo esc_attr( $hover ); ?>" data-handle="<?php echo esc_attr( $handle ); ?>" data-click="<?php echo esc_attr( $click ); ?>">
					<?php echo $image_1_html; ?>
					<?php echo $image_2_html; ?>
				</div>
				<script>
					jQuery(document).ready(function () {
						jQuery(document).trigger('elementor/render/ep_image_comparison', '#ep-ic-<?php echo esc_attr( $this->get_id() ); ?>');
					});
				</script>
			<?php
		}

		protected function _content_template() {}

	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_Image_Comparison() );
	} );
