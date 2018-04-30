<?php
namespace ElementPack\Modules\CustomGallery\Skins;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

use Elementor\Utils;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Fedara extends Elementor_Skin_Base {
	public function get_id() {
		return 'bdt-fedara';
	}

	public function get_title() {
		return __( 'Fedara', 'bdthemes-element-pack' );
	}

	public function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-custom-gallery/section_design_layout/after_section_end', [ $this, 'register_fedara_overlay_animation_controls'   ] );

	}

	public function register_fedara_overlay_animation_controls( Widget_Base $widget ) {

		$this->parent = $widget;
		$this->start_controls_section(
			'section_style_fedara',
			[
				'label' => __( 'Fedara Style', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'desc_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery-skin-fedara-desc' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery-skin-fedara-desc *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'desc_padding',
			[
				'label'      => __( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bdt-custom-gallery-skin-fedara-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'desc_alignment',
			[
				'label'       => __( 'Alignment', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
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
					'{{WRAPPER}} .bdt-custom-gallery-skin-fedara-desc' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render_overlay($content) {
		$settings                    = $this->parent->get_settings();
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
					$lightbox_settings['data-elementor-lightbox-slideshow'] = $this->parent->get_id();
					$lightbox_settings['data-elementor-lightbox-index']     = $this->parent->lightbox_slide_index;
					$image_url                                                = wp_get_attachment_image_src( $content['gallery_image']['id'], 'full' );

					if ( ! $image_url ) {
						$lightbox_settings['href'] = $content['gallery_image']['url'];
					} else {
						$lightbox_settings['href'] = $image_url[0];
					}
					$this->parent->lightbox_slide_index++;
					
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
				</div>
			</div>
		</div>
		<?php
	}

	public function render_title($title) {
		if ( ! $this->parent->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->parent->get_settings( 'title_tag' );
		?>
		<<?php echo $tag ?> class="bdt-gallery-item-title">
			<?php echo $title['image_title']; ?>
		</<?php echo $tag ?>>
		<?php
	}

	public function render_text($text) {
		if ( ! $this->parent->get_settings( 'show_text' ) ) {
			return;
		}

		?>
		<div class="bdt-gallery-item-text"><?php echo $text['image_text']; ?></div>
		<?php
	}

	public function render_desc($content) {
		?>
		<div class="bdt-custom-gallery-skin-fedara-desc bdt-padding-small">
			<?php
			$this->render_title($content); 
			$this->render_text($content);
			?>
			
		</div>
		<?php
	}

	public function render() {
		$settings = $this->parent->get_settings();
		$tilt     = ('yes' === $settings['tilt_show']) ? ' data-tilt' : '';
		$this->parent->render_loop_header('fedara');
		foreach ( $settings['gallery'] as $item ) :

			$item_settigs = [];
			$item_settigs['class'] = [
				'bdt-gallery-item',
			];

			?>
			<div <?php echo \element_pack_helper::attrs($item_settigs); ?><?php echo esc_attr($tilt); ?>>
				<div class="bdt-gallery-item-inner bdt-transition-toggle bdt-position-relative">
					<?php 
					$this->parent->render_thumbnail($item);
					$this->render_overlay($item);
					?>
				</div>
				<?php $this->render_desc($item); ?>
			</div>
		<?php endforeach; ?>
		<?php $this->parent->render_loop_footer($item);
	}
}

