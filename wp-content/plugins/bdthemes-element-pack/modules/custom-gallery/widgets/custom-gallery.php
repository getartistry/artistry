<?php
namespace ElementPack\Modules\CustomGallery\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

use ElementPack\Modules\CustomGallery\Skins;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Custom Gallery
 */
class Custom_Gallery extends Widget_Base {

	/**
	 * @var \WP_Query
	 */

	public $lightbox_slide_index;

	public function get_name() {
		return 'bdt-custom-gallery';
	}

	public function get_title() {
		return esc_html__( 'Custom Gallery', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'isotope', 'uikit-icons', 'tilt' ];
	}

	public function _register_skins() {
		$this->add_skin( new Skins\Skin_Abetis( $this ) );
		$this->add_skin( new Skins\Skin_Fedara( $this ) );
	}

	public function _register_controls() {
		$this->start_controls_section(
			'section_custom_gallery_content',
			[
				'label' => esc_html__( 'Custom Gallery', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'gallery',
			[
				'label' => esc_html__( 'Gallery Items', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'image_title'   => esc_html__( 'Image #1', 'bdthemes-element-pack' ),
						'image_text'    => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
					[
						'image_title'   => esc_html__( 'Image #2', 'bdthemes-element-pack' ),
						'image_text'    => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
					[
						'image_title'   => esc_html__( 'Image #3', 'bdthemes-element-pack' ),
						'image_text'    => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
					[
						'image_title'   => esc_html__( 'Image #4', 'bdthemes-element-pack' ),
						'image_text'    => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
					[
						'image_title'   => esc_html__( 'Image #5', 'bdthemes-element-pack' ),
						'image_text'    => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
					[
						'image_title'   => esc_html__( 'Image #6', 'bdthemes-element-pack' ),
						'image_text'    => esc_html__( 'I am item content. Click edit button to change this text.', 'bdthemes-element-pack' ),
					],
				],
				'fields' => [
					[
						'name'    => 'image_title',
						'label'   => esc_html__( 'Title', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::TEXT,
						'default' => esc_html__( 'Slide Title' , 'bdthemes-element-pack' ),
					],
					[
						'name'  => 'gallery_image',
						'label' => esc_html__( 'Image', 'bdthemes-element-pack' ),
						'type'  => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name'    => 'image_text',
						'label'   => esc_html__( 'Content', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::WYSIWYG,
						'default' => esc_html__( 'Slide Content', 'bdthemes-element-pack' ),
					],
				],
				'title_field' => '{{{ image_title }}}',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_custom_gallery_layout',
			[
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'bdthemes-element-pack' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'label'        => esc_html__( 'Image Size', 'bdthemes-element-pack' ),
				'exclude'      => [ 'custom' ],
				'default'      => 'medium',
				'prefix_class' => 'bdt-custom-gallery--thumbnail-size-',
			]
		);

		$this->add_control(
			'masonry',
			[
				'label'        => esc_html__( 'Masonry', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::SWITCHER,
			]
		);

		$this->add_responsive_control(
			'item_ratio',
			[
				'label'   => esc_html__( 'Item Height', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 265,
				],
				'range' => [
					'px' => [
						'min'  => 50,
						'max'  => 500,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-gallery-thumbnail img' => 'height: {{SIZE}}px',
				],
				'condition' => [
					'masonry!' => 'yes',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_additional',
			[
				'label' => esc_html__( 'Additional Options', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label'   => esc_html__( 'Overlay Animation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => element_pack_transition_options(),
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'   => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => element_pack_title_tags(),
				'default'   => 'h3',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_text',
			[
				'label'   => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_lightbox',
			[
				'label'   => esc_html__( 'Show Lightbox', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'link_type',
			[
				'label'   => esc_html__( 'Link Type', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon' => esc_html__('Icon', 'bdthemes-element-pack'),
					'text' => esc_html__('Text', 'bdthemes-element-pack'),
				],
				'condition' => [
					'show_lightbox' => 'yes',
				]
			]
		);

		$this->add_control(
			'tilt_show',
			[
				'label'   => esc_html__( 'Tilt Effect', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => esc_html__( 'Items', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_gap',
			[
				'label'   => esc_html__( 'Item Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery'               => 'margin: -{{SIZE}}px -{{SIZE}}px 0',
					'(desktop){{WRAPPER}} .bdt-gallery-item' => 'width: calc( 100% / {{columns.SIZE}} ); border: {{SIZE}}px solid transparent',
					'(tablet){{WRAPPER}} .bdt-gallery-item'  => 'width: calc( 100% / {{columns_tablet.SIZE}} ); border: {{SIZE}}px solid transparent',
					'(mobile){{WRAPPER}} .bdt-gallery-item'  => 'width: calc( 100% / {{columns_mobile.SIZE}} ); border: {{SIZE}}px solid transparent',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'overlay_content_alignment',
			[
				'label'       => __( 'Overlay Content Alignment', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'prefix_class' => 'bdt-custom-gallery-skin-fedara-style-',
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-overlay' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'overlay_content_position',
			[
				'label'       => __( 'Overlay Content Vertical Position', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'top' => [
						'title' => __( 'Top', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'default' => 'middle',
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-overlay' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-thumbnail, {{WRAPPER}} .bdt-custom-gallery .bdt-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'_skin' => '',
				],
			]
		);

		$this->add_control(
			'item_skin_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item'           => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .bdt-custom-gallery .bdt-overlay'                       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
				],
				'condition' => [
					'_skin!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_background',
			[
				'label'  => esc_html__( 'Overlay Color', 'bdthemes-element-pack' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item .bdt-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'overlay_gap',
			[
				'label'   => esc_html__( 'Overlay Gap', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item .bdt-overlay' => 'margin: {{SIZE}}px',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item .bdt-gallery-item-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
					'_skin'      => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .bdt-gallery-item .bdt-gallery-item-title',
				'condition' => [
					'show_title' => 'yes',
					'_skin'      => '',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item .bdt-gallery-item-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'text_typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .bdt-gallery-item .bdt-gallery-item-text',
				'condition' => [
					'show_text' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Link Style', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_lightbox' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link span'    => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link',
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link',
				'condition' => [
					'show_lightbox' => 'yes',
					'link_type'     => 'text',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link:hover span'    => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery .bdt-gallery-item-link:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}
	
	public function render_thumbnail($image) {
		$settings  = $this->get_settings();
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $image['gallery_image']['id'], 'thumbnail_size', $settings );

		if ( ! $image_url ) {
			$image_url = $image['gallery_image']['url'];
		}

		echo '<div class="bdt-gallery-thumbnail bdt-transition-toggle"><img src="'.esc_url($image_url).'" alt=""></div>';
	}
	
	public function render_title($title) {
		if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->get_settings( 'title_tag' );
		?>
		<<?php echo $tag ?> class="bdt-gallery-item-title bdt-transition-slide-top-small">
			<?php echo $title['image_title']; ?>
		</<?php echo $tag ?>>
		<?php
	}
	
	public function render_text($text) {
		if ( ! $this->get_settings( 'show_text' ) ) {
			return;
		}

		?>
		<div class="bdt-gallery-item-text bdt-transition-slide-bottom-small"><?php echo $text['image_text']; ?></div>
		<?php
	}

	public function render_overlay($content) {
		$settings                    = $this->get_settings();
		$overlay_settings            = [];
		$overlay_settings['class']   = ['bdt-position-cover bdt-overlay bdt-overlay-default'];
		
		if ($settings['overlay_animation']) {
			$overlay_settings['class'][] = 'bdt-transition-'.$settings['overlay_animation'];
		}


		?>
		<div <?php echo \element_pack_helper::attrs($overlay_settings); ?>>
			<div class="bdt-custom-gallery-content">
				<div class="bdt-custom-gallery-content-inner">
				
					<?php 
					$lightbox_settings                                      = [];
					$lightbox_settings['class']                             = ['bdt-gallery-item-link'];
					$lightbox_settings['class'][]                           = 'elementor-clickable';
					$lightbox_settings['class'][]                           = 'icon-type-' . $settings['link_type'];
					$lightbox_settings['data-elementor-lightbox-slideshow'] = $this->get_id();
					$lightbox_settings['data-elementor-lightbox-index']     = $this->lightbox_slide_index;
					$image_url                                                = wp_get_attachment_image_src( $content['gallery_image']['id'], 'full' );

					if ( ! $image_url ) {
						$lightbox_settings['href'] = $content['gallery_image']['url'];
					} else {
						$lightbox_settings['href'] = $image_url[0];
					}

					$this->lightbox_slide_index++;
					
					?>
					<?php if ( 'yes' == $settings['show_lightbox'] )  : ?>
						<div class="bdt-flex-inline bdt-gallery-item-link-wrapper">
							<a <?php echo \element_pack_helper::attrs($lightbox_settings); ?>>
								<?php if ( 'icon' == $settings['link_type'] ) : ?>
									<span bdt-icon="icon: plus; ratio: 1.6"></span>
								<?php elseif ( 'text' == $settings['link_type'] ) : ?>
									<span class="bdt-text"><?php esc_html_e( 'ZOOM', 'bdthemes-element-pack' ); ?></span>
								<?php endif;?>
							</a>
						</div>
					<?php endif; ?>

					<?php 
					$this->render_title($content);
					$this->render_text($content);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_loop_header($skin = 'default') {
		$settings = $this->get_settings();
		$masonry = ('yes' === $settings['masonry']) ? ' bdt-masonry-grid' : '';
		?>
		<div id="bdt-custom-gallery<?php echo $this->get_id(); ?>" class="bdt-custom-gallery bdt-custom-gallery-skin-<?php echo esc_attr($skin); ?><?php echo esc_attr($masonry); ?>">
		<?php
	}

	public function render_loop_footer() {
		$settings = $this->get_settings();
		?>
		</div>

		<?php if ( 'yes' === $settings['masonry'] )  : ?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					"use strict";
					$('.bdt-custom-gallery').isotope({
					  itemSelector: '.bdt-gallery-item',
					});
				});
			</script>
		<?php endif;
	}

	

	public function render() {
		$settings = $this->get_settings();
		$tilt     = ('yes' === $settings['tilt_show']) ? ' data-tilt' : '';

		$this->render_loop_header();
		foreach ( $settings['gallery'] as $item ) :

			$item_settigs = [];
			$item_settigs['class'] = [
				'bdt-gallery-item',
				'bdt-transition-toggle',
			];

			?>
			<div <?php echo \element_pack_helper::attrs($item_settigs); ?><?php echo esc_attr($tilt); ?>>
				<?php 
				$this->render_thumbnail($item);
				$this->render_overlay($item);
				?>
			</div>
		<?php endforeach; ?>
		<?php $this->render_loop_footer($item);
	}
		
}
