<?php
namespace ElementPack\Modules\Member\Skins;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Phaedra extends Elementor_Skin_Base {
	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-member/section_style/before_section_start', [ $this, 'register_phaedra_style_controls' ] );

	}

	public function get_id() {
		return 'bdt-phaedra';
	}

	public function get_title() {
		return __( 'Phaedra', 'bdthemes-element-pack' );
	}

	public function register_phaedra_style_controls() {
		$this->start_controls_section(
			'section_style_phaedra',
			[
				'label' => __( 'Phaedra', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'phaedra_overlay_color',
			[
				'label'     => __( 'Overlay Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$phaedra_id = 'phaedra' . $this->parent->get_id();
		$settings   = $this->parent->get_settings();
		?>
		<div class="bdt-member bdt-member-skin-phaedra bdt-transition-toggle">
			
			<?php if ( ! empty( $settings['photo']['url'] ) ) :
				$photo_hover_animation = ( '' != $settings['photo_hover_animation'] ) ? ' bdt-transition-scale-'.$settings['photo_hover_animation'] : '';
			?>

				<div class="bdt-member-photo-wrapper">
					<div class="bdt-member-photo">
						<div class="<?php echo ($photo_hover_animation); ?>">
							<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'photo' ); ?>
						</div>
					</div>
				</div>
				
			<?php endif; ?>

			<div class="bdt-member-overlay bdt-overlay-default bdt-position-cover bdt-transition-fade">
				<div class="bdt-member-desc bdt-position-center bdt-text-center">
					<div class="bdt-member-description bdt-transition-slide-top-small">
						<?php if ( ! empty( $settings['name'] ) ) : ?>
							<span class="bdt-member-name"><?php echo $settings['name']; ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $settings['role'] ) ) : ?>
							<span class="bdt-member-role"><?php echo $settings['role']; ?></span>
						<?php endif; ?>
					</div>

					<div class="bdt-member-icons bdt-transition-slide-bottom-small">
						<?php 
						foreach ( $settings['social_link_list'] as $link ) :
							$tooltip = ( 'yes' == $settings['social_icon_tooltip'] ) ? ' title="'.esc_attr( $link['social_link_title'] ).'" bdt-tooltip' : '';
						?>
							<a href="<?php echo esc_url( $link['social_link'] ); ?>" class="bdt-member-icon elementor-repeater-item-<?php echo $link['_id']; ?>" target="_blank"<?php echo $tooltip; ?>>
								<i class="fa-fw <?php echo esc_attr( $link['social_icon'] ); ?>"></i>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			
		</div>
		<?php
	}
}

