<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Hover_Effects extends Widget_Base {

		public function get_name() {
			return 'ep-image-hover-effects';
		}

		public function get_title() {
			return __( 'Image Hover Effects Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-image_hover';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_title',
				[
					'label' => __( 'Image Hover Effects Plus!', 'elements-plus' ),
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
				'displacement_image',
				[
					'label' => __( 'Displacement Image', 'elements-plus' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '1',
					'options' => [
						'1'  => __( '1', 'elements-plus' ),
						'2'    => __( '2', 'elements-plus' ),
						'3'    => __( '3', 'elements-plus' ),
						'4'    => __( '4', 'elements-plus' ),
						'5'    => __( '5', 'elements-plus' ),
						'6'    => __( '6', 'elements-plus' ),
						'7'    => __( '7', 'elements-plus' ),
						'8'    => __( '8', 'elements-plus' ),
						'9'    => __( '9', 'elements-plus' ),
						'10'    => __( '10', 'elements-plus' ),
						'11'    => __( '11', 'elements-plus' ),
						'12'    => __( '12', 'elements-plus' ),
						'13'    => __( '13', 'elements-plus' ),
						'14'    => __( '14', 'elements-plus' ),
						'15'    => __( '15', 'elements-plus' ),
						'16'    => __( '16', 'elements-plus' ),
						'17'    => __( '17', 'elements-plus' ),
					],
				]
			);

			$this->end_controls_section();

		}

		protected function render() {
			$settings           = $this->get_settings();
			$displacement_image = $settings['displacement_image'];

			$image_1                = wp_get_attachment_image_src( $settings['image_1']['id'], 'full' );
			$image_2                = wp_get_attachment_image_src( $settings['image_2']['id'], 'full' );
			$displacement_image_url = ELEMENTS_PLUS_URL . 'assets/images/displacements/' . intval( $displacement_image ) . '.jpg';

			?>
				<div id="ep-ihe-<?php echo esc_attr( $this->get_id() ); ?>" class="ep-ihe" data-image1="<?php echo esc_url( $image_1[0] ); ?>" data-image2="<?php echo esc_url( $image_2[0] ); ?>" data-displacement="<?php echo esc_url( $displacement_image_url ); ?>" style="padding-top:calc(<?php echo intval( $image_1[2] ); ?> / <?php echo intval( $image_1[1] ); ?> * 100%);">
					<div class="img-container"></div>		
				</div>
				<script>
					jQuery(document).ready(function () {
						jQuery(document).trigger('elementor/render/ep_image_hover_effects', '#ep-ihe-<?php echo esc_attr( $this->get_id() ); ?>');
					});
				</script>
			<?php
		}

		protected function _content_template() {}

	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_Hover_Effects() );
	} );
