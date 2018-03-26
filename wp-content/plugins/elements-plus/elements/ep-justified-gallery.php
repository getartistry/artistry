<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Justified_Gallery extends Widget_Base {

		public function get_name() {
			return 'justified-gallery';
		}

		public function get_title() {
			return __( 'Gallery Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-gallery';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function _register_controls() {
			$this->start_controls_section(
				'section_gallery',
				[
					'label' => __( 'Gallery Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'wp_gallery',
				[
					'label' => __( 'Add Images', 'elements-plus' ),
					'type'  => Controls_Manager::GALLERY,
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name'    => 'thumbnail',
					'exclude' => [ 'custom' ],
				]
			);

			$this->add_control(
				'gallery_link',
				[
					'label'   => __( 'Link to', 'elements-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'file',
					'options' => [
						'file'       => __( 'Media File', 'elements-plus' ),
						'attachment' => __( 'Attachment Page', 'elements-plus' ),
						'none'       => __( 'None', 'elements-plus' ),
					],
				]
			);

			$this->add_control(
				'open_lightbox',
				[
					'label'     => __( 'Lightbox', 'elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'default',
					'options'   => [
						'default' => __( 'Default', 'elements-plus' ),
						'yes'     => __( 'Yes', 'elements-plus' ),
						'no'      => __( 'No', 'elements-plus' ),
					],
					'condition' => [
						'gallery_link' => 'file',
					],
				]
			);

			$this->add_control(
				'row-height',
				[
					'label'   => __( 'Row Height', 'elements-plus' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 120,
					'min'     => 50,
					'max'     => 600,
					'step'    => 10,
				]
			);

			$this->add_control(
				'gallery-margins',
				[
					'label'   => __( 'Margins', 'elements-plus' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 1,
					'min'     => 0,
					'max'     => 50,
					'step'    => 1,
				]
			);

			$this->add_control(
				'last_row',
				[
					'label'   => __( 'Last Row', 'elements-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'nojustify',
					'options' => [
						'nojustify' => __( 'No Justify', 'elements-plus' ),
						'justify'   => __( 'Justify', 'elements-plus' ),
						'hide'      => __( 'Hide', 'elements-plus' ),
						'left'      => __( 'Left', 'elements-plus' ),
						'right'     => __( 'Right', 'elements-plus' ),
					],
				]
			);

			$this->add_control(
				'randomize',
				[
					'label'        => __( 'Randomize', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => false,
					'label_on'     => __( 'On', 'elements-plus' ),
					'label_off'    => __( 'Off', 'elements-plus' ),
					'return_value' => true,
				]
			);

			$this->add_control(
				'view',
				[
					'label'   => __( 'View', 'elements-plus' ),
					'type'    => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_gallery_images',
				[
					'label' => __( 'Images', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'      => 'image_border',
					'label'     => __( 'Image Border', 'elements-plus' ),
					'selector'  => '{{WRAPPER}} .elementor-justified-gallery img',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'image_border_radius',
				[
					'label'      => __( 'Border Radius', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .elementor-justified-gallery img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_caption',
				[
					'label' => __( 'Caption', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'gallery_display_caption',
				[
					'label'     => __( 'Display', 'elements-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => '',
					'options'   => [
						''     => __( 'Show', 'elements-plus' ),
						'none' => __( 'Hide', 'elements-plus' ),
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-justified-gallery .caption' => 'display: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'align',
				[
					'label'     => __( 'Alignment', 'elements-plus' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'    => [
							'title' => __( 'Left', 'elements-plus' ),
							'icon'  => 'fa fa-align-left',
						],
						'center'  => [
							'title' => __( 'Center', 'elements-plus' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'   => [
							'title' => __( 'Right', 'elements-plus' ),
							'icon'  => 'fa fa-align-right',
						],
						'justify' => [
							'title' => __( 'Justified', 'elements-plus' ),
							'icon'  => 'fa fa-align-justify',
						],
					],
					'default'   => 'center',
					'selectors' => [
						'{{WRAPPER}} .elementor-justified-gallery .caption' => 'text-align: {{VALUE}};',
					],
					'condition' => [
						'gallery_display_caption' => '',
					],
				]
			);

			$this->add_control(
				'text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .elementor-justified-gallery .caption' => 'color: {{VALUE}};',
					],
					'condition' => [
						'gallery_display_caption' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'typography',
					'label'     => __( 'Typography', 'elements-plus' ),
					'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
					'selector'  => '{{WRAPPER}} .elementor-justified-gallery .caption',
					'condition' => [
						'gallery_display_caption' => '',
					],
				]
			);

			$this->end_controls_section();
		}

		protected function render() {
			$settings = $this->get_settings();

			if ( ! $settings['wp_gallery'] ) {
				return;
			}

			$ids = wp_list_pluck( $settings['wp_gallery'], 'id' );

			$this->add_render_attribute( 'shortcode', 'ids', implode( ',', $ids ) );
			$this->add_render_attribute( 'shortcode', 'size', $settings['thumbnail_size'] );


			?>
			<div id="jg-<?php echo esc_attr( $this->get_id() ); ?>" class="hide-gallery elementor-justified-gallery" data-row-height="<?php echo intval( $settings['row-height'] ); ?>" data-margins="<?php echo intval( $settings['gallery-margins'] ); ?>" data-last-row="<?php echo esc_attr( $settings['last_row'] ); ?>" data-randomize="<?php echo esc_attr( $settings['randomize'] ? 'true' : 'false' ); ?>" data-selector="<?php echo esc_attr( $settings['gallery_link'] === 'none' ? 'div' : 'a' ); ?>">
				<?php foreach ( $ids as $id ) : ?>
					<?php
						$img      = wp_get_attachment_image_src( $id, $settings['thumbnail_size'] );
						$img_full = wp_get_attachment_url( $id );
						$att_link = get_attachment_link( $id );
						$link     = $settings['gallery_link'];
						$link_to  = $link === 'file' ? $img_full : $att_link;
					?>

					<?php if ( $link !== 'none' ) : ?>
						<a href="<?php echo esc_url( $link_to ); ?>" class="elementor-clickable" data-elementor-open-lightbox="<?php echo esc_attr( $settings['open_lightbox'] ); ?>" data-elementor-lightbox-slideshow="<?php echo esc_attr( $this->get_id() ); ?>">
					<?php else : ?>
						<div>
					<?php endif; ?>

					<img src="<?php echo esc_url( $img['0'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $id, '_wp_attachment_image_alt', true) ); ?>">

					<?php if ( $link !== 'none' ) : ?>
						</a>
					<?php else :?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<script>
				jQuery(document).ready(function(){
					jQuery(document).trigger('elementor/render/justified-gallery','#jg-<?php echo esc_attr( $this->get_id() ); ?>');
				});
			</script>
			<?php
		}

		protected function _content_template() {

		}
	}

	add_action( 'elementor/widgets/widgets_registered', function ( $widgets_manager ) {
		$widgets_manager->register_widget_type( new Widget_Justified_Gallery() );
	} );

