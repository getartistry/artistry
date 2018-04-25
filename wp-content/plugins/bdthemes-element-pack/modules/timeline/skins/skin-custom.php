<?php
namespace ElementPack\Modules\Timeline\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Custom extends Elementor_Skin_Base {
	public function get_id() {
		return 'bdt-custom';
	}

	public function get_title() {
		return __( 'Custom', 'bdthemes-element-pack' );
	}

	public function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-timeline/section_content_layout/after_section_end', [ $this, 'register_timeline_custom_controls'   ] );

	}

	public function register_timeline_custom_controls(Widget_Base $widget) {
		$this->parent = $widget;
		$this->start_controls_section(
			'section_custom_content',
			[
				'label' => esc_html__( 'Custom Content', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'timeline_items',
			[
				'label'   => esc_html__( 'Timeline Items', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::REPEATER,
				'default' => [
					[
						'timeline_title' => esc_html__( 'This is Timeline Item 1 Title', 'bdthemes-element-pack' ),
						'timeline_text'  => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'bdthemes-element-pack' ),
						'timeline_icon'  => 'fa fa-file-text',
					],
					[
						'timeline_title' => esc_html__( 'This is Timeline Item 2 Title', 'bdthemes-element-pack' ),
						'timeline_text'  => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'bdthemes-element-pack' ),
						'timeline_icon'  => 'fa fa-file-text',
					],
					[
						'timeline_title' => esc_html__( 'This is Timeline Item 3 Title', 'bdthemes-element-pack' ),
						'timeline_text'  => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'bdthemes-element-pack' ),
						'timeline_icon'  => 'fa fa-file-text',
					],
					[
						'timeline_title' => esc_html__( 'This is Timeline Item 4 Title', 'bdthemes-element-pack' ),
						'timeline_text'  => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'bdthemes-element-pack' ),
						'timeline_icon'  => 'fa fa-file-text',
					],
				],
				'fields' => [
					[
						'name'    => 'timeline_title',
						'label'   => esc_html__( 'Title', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::TEXT,
						'default' => esc_html__( 'This is Timeline Item 1 Title' , 'bdthemes-element-pack' ),
					],
					[
						'name'    => 'timeline_date',
						'label'   => esc_html__( 'Date', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '31 December 2018',
					],
					[
						'name'    => 'timeline_image',
						'label'   => esc_html__( 'Image', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name'    => 'timeline_text',
						'label'   => esc_html__( 'Content', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::WYSIWYG,
						'default' => esc_html__( 'I am timeline item content. Click edit button to change this text. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine.', 'bdthemes-element-pack' ),
					],
					[
						'name'        => 'timeline_link',
						'label'       => esc_html__( 'Button Link', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::TEXT,
						'placeholder' => __( 'https://bdthemes.com', 'bdthemes-element-pack' ),
						'default'     => __( 'https://bdthemes.com', 'bdthemes-element-pack' ),
					],
					[
						'name'    => 'timeline_icon',
						'label'   => esc_html__( 'Icon', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::ICON,
						'default' => 'fa fa-file-text',
					],
				],
				'title_field' => '{{{ timeline_title }}}',
			]
		);

		$this->end_controls_section();
	}	

	public function render() {
		$id             = $this->parent->get_id();
		$settings       = $this->parent->get_settings();
		$timeline_items = $this->get_instance_value('timeline_items');
		
		$classes        = ['bdt-grid', 'bdt-grid-collapse'];
		
		$animation      = ($settings['readmore_hover_animation']) ? ' elementor-animation-'.$settings['readmore_hover_animation'] : '';
		
		?>
		<div id="bdt-timeline-<?php echo esc_attr($id); ?>" class="bdt-timeline bdt-timeline-skin-custom">
			<div class="<?php echo \element_pack_helper::acssc($classes); ?>">
				<?php
				$bdt_count = 0;
				foreach ( $timeline_items as $item ) :
					$post_format   =  'standard';
					$timeline_date = '';
					$bdt_count++;
					$item_part     = ($bdt_count%2 === 0) ? 'right' : 'left';
					
					$image_url     = wp_get_attachment_image_src( $item['timeline_image']['id'], 'full' );
					$image_url     = ( '' != $image_url ) ? $image_url[0] : $item['timeline_image']['url'];
			  	?>

				<?php if( $bdt_count%2 === 0) : ?>
			  			<div class="bdt-timeline-item bdt-width-1-2@m bdt-visible@m">
					  		<div class="bdt-timeline-date bdt-text-right"><span><?php echo esc_attr($item['timeline_date']); ?></span></div>
						</div>
					<?php endif; ?>

		  			<div class="bdt-width-1-2@m bdt-timeline-item <?php echo $item_part . '-part'; ?>">
			  			
			  			<div class="bdt-timeline-item-main-wrapper">
			  				<div class="bdt-timeline-line"><span bdt-parallax="opacity: 0,1;viewport: 0.2;"></span></div>
				  			<div class="bdt-timeline-item-main-container">
				  					<?php $item_scrollspy = ('yes' === $settings['item_animation']) ? ' bdt-scrollspy="cls: bdt-animation-scale-up;"' : ''; ?>
				  				<div class="bdt-timeline-icon bdt-post-format-<?php echo esc_attr($post_format); ?>"<?php echo $item_scrollspy; ?>>
				  					<span><i class="<?php echo esc_attr($item['timeline_icon']); ?>"></i></span>
				  				</div>
					  			<?php $item_scrollspy = ('yes' === $settings['item_animation']) ? ' bdt-scrollspy="cls: bdt-animation-slide-'.$item_part.'-medium;"' : ''; ?>
					  			<div class="bdt-timeline-item-main"<?php echo $item_scrollspy; ?>>
					  				<span class="bdt-timeline-arrow"></span>

					  				<?php if ('yes' == $settings['show_image']) : ?>
								  		<div class="bdt-timeline-thumbnail">
								  			<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item['timeline_title']); ?>">
								  		</div>
							  		<?php endif ?>
							  		<div class="bdt-timeline-desc bdt-padding">

										<?php if ('yes' == $settings['show_title']) : ?>
											<h4 class="bdt-timeline-title"><a href="<?php echo esc_url($item['timeline_link']); ?>" class="" title="<?php echo esc_attr($item['timeline_title']); ?>"><?php echo esc_html($item['timeline_title']) ; ?></a></h4>
										<?php endif ?>

										<?php $meta_date = ''; ?>

										<?php if ('yes' == $settings['show_meta']) : ?>
											<ul class="bdt-timeline-meta bdt-subnav bdt-hidden@m"><li><?php echo esc_attr($item['timeline_date']); ?></li></ul>
										<?php endif ?>

										<?php if ('yes' == $settings['show_excerpt']) : ?>
											<div class="bdt-timeline-excerpt"><?php echo do_shortcode($item['timeline_text']); ?></div>
										<?php endif ?>

										<?php if ('yes' == $settings['show_readmore']) : ?>
											<a href="<?php echo esc_url($item['timeline_link']); ?>" class="bdt-timeline-readmore elementor-button elementor-size-<?php echo esc_attr($settings['button_size']); ?><?php echo esc_attr($animation); ?>"><?php echo esc_html($settings['readmore_text']); ?>

												<?php if ($settings['icon']) : ?>
													<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
														<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
													</span>
												<?php endif; ?>
											</a>
										<?php endif ?>
							  		</div>
								</div>
							</div>
						</div>
					</div>

				  	<?php if( $bdt_count%2 === 1) : ?>
				  		<?php 
				  			$item_part = ($bdt_count%2 === 1) ? 'right' : 'left';
				  			$item_scrollspy = ('yes' === $settings['item_animation']) ? ' bdt-scrollspy="cls: bdt-animation-slide-'.$item_part.'-medium;"' : ''; ?>
			  			<div class="bdt-timeline-item bdt-width-1-2@m bdt-visible@m">
					  		<div class="bdt-timeline-date"<?php echo $item_scrollspy; ?>><span><?php echo esc_attr($item['timeline_date']); ?></span></div>
					  		
						</div>
					<?php endif; ?>

				<?php endforeach; ?>
			</div>
		</div>
 		<?php
	}
}