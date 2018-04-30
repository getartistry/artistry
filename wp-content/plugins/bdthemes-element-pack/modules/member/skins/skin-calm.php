<?php
namespace ElementPack\Modules\Member\Skins;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Calm extends Elementor_Skin_Base {
	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-member/section_style/before_section_start', [ $this, 'register_calm_style_controls' ] );

	}

	public function get_id() {
		return 'bdt-calm';
	}

	public function get_title() {
		return __( 'Calm', 'bdthemes-element-pack' );
	}

	public function register_calm_style_controls() {
		$this->start_controls_section(
			'section_style_calm',
			[
				'label' => __( 'Calm', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'calm_overlay_color',
			[
				'label'     => __( 'Overlay Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-member .bdt-member-overlay' => 'background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%,{{VALUE)}} 100%);',
					'{{WRAPPER}} .bdt-member .bdt-member-overlay' => 'background: linear-gradient(to bottom, rgba(0,0,0,0) 0%,{{VALUE)}} 100%);',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$calm_id = 'calm' . $this->parent->get_id();
		$settings   = $this->parent->get_settings();
		?>
		<div class="bdt-member bdt-member-skin-calm bdt-transition-toggle bdt-inline">
			
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

			<div class="bdt-member-overlay bdt-overlay bdt-position-bottom bdt-text-center">
				<div class="bdt-member-desc">
					<div class="bdt-member-description bdt-transition-slide-bottom-small">
						<?php if ( ! empty( $settings['name'] ) ) : ?>
							<span class="bdt-member-name"><?php echo $settings['name']; ?></span>
						<?php endif; ?>

						<?php if ( ! empty( $settings['role'] ) ) : ?>
							<span class="bdt-member-role"><?php echo $settings['role']; ?></span>
						<?php endif; ?>
					</div>

					<div class="bdt-member-icons bdt-transition-slide-bottom">
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

