<?php
namespace ElementPack\Modules\ThumbGallery\Skins;
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

		add_action( 'elementor/element/bdt-thumb-gallery/section_query/after_section_end', [ $this, 'register_thumb_gallery_custom_controls'   ] );
		add_action( 'elementor/element/bdt-thumb-gallery/section_button/after_section_start', [ $this, 'register_thumb_gallery_custom_button_controls'   ] );

	}

	public function register_thumb_gallery_custom_controls() {
		$this->start_controls_section(
			'section_custom_content',
			[
				'label' => esc_html__( 'Custom Content', 'bdthemes-element-pack' ),
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
	}

	public function register_thumb_gallery_custom_button_controls( Widget_Base $widget ) {
		$this->parent = $widget;
		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'bdthemes-element-pack' ),
				'default' => [
					'url' => '#',
				],
			]
		);
	}

	public function render_image($image, $size) {
		$image_url = wp_get_attachment_image_src( $image['gallery_image']['id'], $size );

		$image_url = ( '' != $image_url ) ? $image_url[0] : $image['gallery_image']['url'];

		return $image_url;
	}

	public function render_title($title) {
		if ( ! $this->parent->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->parent->get_settings( 'title_tag' );
		$classes = ['bdt-thumb-gallery-title'];
		?>

		<<?php echo $tag ?> class="<?php echo implode(" ", $classes); ?>">
			<?php echo esc_attr($title['image_title']); ?>
		</<?php echo $tag ?>>
		<?php
	}

	public function render_text($text) {
		if ( ! $this->parent->get_settings( 'show_text' ) ) {
			return;
		}

		?>
		<div class="bdt-thumb-gallery-text bdt-text-small">
			<?php echo wp_kses_post($text['image_text']); ?>
		</div>
		<?php
	}

	public function render_button() {
		if ( ! $this->parent->get_settings( 'show_button' ) ) {
			return;
		}

		$settings      = $this->parent->get_settings();
		$self_settings = $this->get_instance_value('link');
		
		$external      = ($self_settings['is_external']) ? "_blank" : "_self";
		$link_url      = empty( $self_settings['url'] ) ? '#' : $self_settings['url'];
		
		$animation     = ($settings['button_hover_animation']) ? ' elementor-animation-'.$settings['button_hover_animation'] : '';
		
		?>
			<div>
				<a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($external); ?>" class="bdt-thumb-gallery-button bdt-display-inline-block<?php echo esc_attr($animation); ?>">
					<?php echo esc_attr($settings['button_text']); ?>
				
					<?php if ($settings['icon']) : ?>
						<span class="bdt-button-icon-align-<?php echo esc_attr($settings['icon_align']); ?>">
							<i class="<?php echo esc_attr($settings['icon']); ?>"></i>
						</span>
					<?php endif; ?>
				</a>
			</div>
		<?php
	}

	public function render_loop_items() {
		$settings             = $this->parent->get_settings();
		$gallery              = $this->get_instance_value('gallery');
		$content_transition   = ($settings['content_transition']) ? ' bdt-transition-' . $settings['content_transition'] : '';
		$slideshow_fullscreen = ( $settings['slideshow_fullscreen'] ) ? ' bdt-height-viewport="offset-top: true"' : '';
		?>
		<ul class="bdt-slideshow-items"<?php echo $slideshow_fullscreen; ?>>
			<?php
			foreach ( $gallery as $item ) :

				$gallery_image = $this->render_image($item, 'full');
				?>
				<li>
					<div class="bdt-transition-toggle">

						<?php if( 'yes' == $settings['kenburns_animation'] ) : ?>
							<div class="bdt-position-cover bdt-animation-kenburns bdt-animation-reverse bdt-transform-origin-center-left">
						<?php endif; ?>

							<img src="<?php echo esc_url($gallery_image); ?>" alt="" bdt-cover>

						<?php if( 'yes' == $settings['kenburns_animation'] ) : ?>
				            </div>
				        <?php endif; ?>

						<?php if (( 'yes' == $settings['show_title'] ) || ( 'yes' == $settings['show_text'] ) || ( 'yes' == $settings['show_button'] )) : ?>
							<div class="bdt-position-z-index bdt-position-<?php echo $settings['content_position']; ?> bdt-position-large bdt-text-<?php echo $settings['content_align']; ?>">
								<div class="bdt-thumb-gallery-content<?php echo esc_attr($content_transition); ?>">
						        	<?php $this->render_title($item); ?>
						        	<?php $this->render_text($item); ?>
						        	<?php $this->render_button(); ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</li>
				<?php
			endforeach;
			?>
    	</ul>
    	<?php
	}

	public function render() {
		$this->parent->render_header();
		$this->render_loop_items();
		$this->parent->render_navigation();
		$this->render_loop_pagination();
		$this->parent->render_footer();
	}


	public function render_loop_pagination() {
		if (( 'arrows' == $this->parent->get_settings('navigation')) || ( 'none' == $this->parent->get_settings('navigation') )) {
			return;
		}
		$thumbnav_outside = '';
		$vertical_thumbnav = '';
	

		if  ( 'center-left' == $this->parent->get_settings('thumbnav_position') || 'center-right' == $this->parent->get_settings('thumbnav_position') ) {
			if ('yes' == $this->parent->get_settings('thumbnav_outside')) {
				$thumbnav_outside = '-out';
			}
			$vertical_thumbnav = ' bdt-thumbnav-vertical';
		}

		?>
		<div class="bdt-thumbnav-wrapper bdt-position-<?php echo esc_attr($this->parent->get_settings('thumbnav_position').$thumbnav_outside); ?> bdt-position-small">
        	<ul class="bdt-thumbnav<?php echo esc_attr($vertical_thumbnav); ?>">

				<?php		
				$bdt_counter = 0;
				$gallery_thumb = $this->get_instance_value('gallery');
				      
				foreach ( $gallery_thumb as $item ) :

					$gallery_thumbnail = $this->render_image($item, 'thumbnail');
					echo '<li class="bdt-thumb-gallery-thumbnav" bdt-slideshow-item="'.$bdt_counter.'"><a class="bdt-overflow-hidden bdt-background-cover" href="#" style="background-image: url('.esc_url($gallery_thumbnail).')"></a></li>';
					$bdt_counter++;

				endforeach; ?>
        	</ul>
		</div>
    	<?php
	}
}