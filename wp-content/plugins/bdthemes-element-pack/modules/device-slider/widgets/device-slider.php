<?php
namespace ElementPack\Modules\DeviceSlider\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

use ElementPack\Modules\QueryControl\Controls\Group_Control_Posts;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Device_Slider extends Widget_Base {
	private $_query = null;

	protected $_has_template_content = false;

	public function get_name() {
		return 'bdt-device-slider';
	}

	public function get_title() {
		return esc_html__( 'Device Slider', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	protected function _register_controls() {
		$this->register_query_section_controls();
	}

	private function register_query_section_controls() {

		$this->start_controls_section(
			'section_content_sliders',
			[
				'label' => esc_html__( 'Sliders', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'device_type',
			[
				'label'   => esc_html__( 'Select Device', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desktop',
				'options' => [
					'desktop'    => esc_html__('Desktop', 'bdthemes-element-pack') ,
					'macbookpro' => esc_html__('Macbook Pro', 'bdthemes-element-pack') ,
					'macbookair' => esc_html__('Macbook Air', 'bdthemes-element-pack') ,
					'tablet'     => esc_html__('Tablet', 'bdthemes-element-pack') ,
					'mobile'     => esc_html__('Mobile', 'bdthemes-element-pack') ,
					'galaxy'     => esc_html__('Galaxy S9', 'bdthemes-element-pack') ,
					'iphonex'    => esc_html__('IPhone X', 'bdthemes-element-pack') ,
				],
			]
		);


		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slider Items', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'title'       => esc_html__( 'Slide Item 1', 'bdthemes-element-pack' ),
						'button_link' => ['url' => '#'],
					],
					[
						'title'       => esc_html__( 'Slide Item 2', 'bdthemes-element-pack' ),
						'button_link' => ['url' => '#'],
					],
					[
						'title'       => esc_html__( 'Slide Item 3', 'bdthemes-element-pack' ),
						'button_link' => ['url' => '#'],
					],
					[
						'title'       => esc_html__( 'Slide Item 4', 'bdthemes-element-pack' ),
						'button_link' => ['url' => '#'],
					],
				],
				'fields' => [
					
					[
						'name'        => 'title',
						'label'       => esc_html__( 'Title', 'bdthemes-element-pack' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => esc_html__( 'Slide Title' , 'bdthemes-element-pack' ),
						'label_block' => true,
					],
					[
						'name'    => 'background',
						'label'   => esc_html__( 'Background', 'bdthemes-element-pack' ),
						'type'    => Controls_Manager::CHOOSE,
						'default' => 'color',
						'options' => [
							'color' => [
								'title' => esc_html__( 'Color', 'bdthemes-element-pack' ),
								'icon'  => 'fa fa-paint-brush',
							],
							'image' => [
								'title' => esc_html__( 'Image', 'bdthemes-element-pack' ),
								'icon'  => 'fa fa-picture-o',
							],
							'video' => [
								'title' => esc_html__( 'Video', 'bdthemes-element-pack' ),
								'icon'  => 'fa fa-play-circle',
							],
							'youtube' => [
								'title' => esc_html__( 'Youtube', 'bdthemes-element-pack' ),
								'icon'  => 'fa fa-youtube',
							],
						],
					],
					[
						'name'      => 'color',
						'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#14ABF4',
						'condition' => [
							'background' => 'color'
						],
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
						],
					],
					[
						'name'      => 'image',
						'label'     => esc_html__( 'Image', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'condition' => [
							'background' => 'image'
						],
					],
					[
						'name'      => 'video_link',
						'label'     => esc_html__( 'Video Link', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::TEXT,
						'condition' => [
							'background' => 'video'
						],
						'default' => '//www.quirksmode.org/html5/videos/big_buck_bunny.mp4',
					],
					[
						'name'      => 'youtube_link',
						'label'     => esc_html__( 'Youtube Link', 'bdthemes-element-pack' ),
						'type'      => Controls_Manager::TEXT,
						'condition' => [
							'background' => 'youtube'
						],
						'default' => 'https://youtu.be/YE7VzlLtp-4',
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->add_responsive_control(
			'slider_size',
			[
				'label' => esc_html__( 'Slider Size', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 180,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-device-slider-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'align',
			[
				'label'   => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'bdt-device-slider-align-',
				'condition' => [
					'slider_size!' => [ '' ],
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'   => esc_html__( 'Show Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'navigation',
			[
				'label'   => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'arrows',
				'options' => [
					'arrows'           => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
					'dots'             => esc_html__( 'Dots', 'bdthemes-element-pack' ),
					'arrows_dots'      => esc_html__( 'Arrows and Dots', 'bdthemes-element-pack' ),
					'none'             => esc_html__( 'None', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Title Layout', 'bdthemes-element-pack' ),
				'condition' => [
					'show_title' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label'   => esc_html__( 'Position', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => element_pack_position_options(),
			]
		);


		$this->add_responsive_control(
			'content_align',
			[
				'label'   => esc_html__( 'Alignment', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'bdthemes-element-pack' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_slider',
			[
				'label' => esc_html__( 'Slider', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay',
			[
				'label'   => esc_html__( 'Overlay', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'       => esc_html__( 'None', 'bdthemes-element-pack' ),
					'background' => esc_html__( 'Background', 'bdthemes-element-pack' ),
					'blend'      => esc_html__( 'Blend', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'overlay' => ['background', 'blend']
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-overlay-default' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'blend_type',
			[
				'label'   => esc_html__( 'Blend Type', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'multiply',
				'options' => [
					'multiply'    => esc_html__( 'Multiply', 'bdthemes-element-pack' ),
					'screen'      => esc_html__( 'Screen', 'bdthemes-element-pack' ),
					'overlay'     => esc_html__( 'Overlay', 'bdthemes-element-pack' ),
					'darken'      => esc_html__( 'Darken', 'bdthemes-element-pack' ),
					'lighten'     => esc_html__( 'Lighten', 'bdthemes-element-pack' ),
					'color-dodge' => esc_html__( 'Color-Dodge', 'bdthemes-element-pack' ),
					'color-burn'  => esc_html__( 'Color-Burn', 'bdthemes-element-pack' ),
					'hard-light'  => esc_html__( 'Hard-Light', 'bdthemes-element-pack' ),
					'soft-light'  => esc_html__( 'Soft-Light', 'bdthemes-element-pack' ),
					'difference'  => esc_html__( 'Difference', 'bdthemes-element-pack' ),
					'exclusion'   => esc_html__( 'Exclusion', 'bdthemes-element-pack' ),
					'hue'         => esc_html__( 'Hue', 'bdthemes-element-pack' ),
					'saturation'  => esc_html__( 'Saturation', 'bdthemes-element-pack' ),
					'color'       => esc_html__( 'Color', 'bdthemes-element-pack' ),
					'luminosity'  => esc_html__( 'Luminosity', 'bdthemes-element-pack' ),
				],
				'condition' => [
					'overlay' => 'blend',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label'     => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-slideshow-items .bdt-device-slider-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_background',
			[
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-slideshow-items .bdt-device-slider-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-slideshow-items .bdt-device-slider-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_radius',
			[
				'label'      => esc_html__( 'Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-slideshow-items .bdt-device-slider-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-device-slider-container .bdt-slideshow-items .bdt-device-slider-title',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label'     => esc_html__( 'Navigation', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation!' => 'none',
				],
			]
		);

		$this->add_control(
			'heading_arrows',
			[
				'label'     => esc_html__( 'Arrows', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
					'navigation' => [ 'arrows', 'arrows_dots', 'arrows_thumbnavs' ],
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'     => esc_html__( 'Arrows Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-slidenav' => 'color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'arrows_dots', 'arrows_thumbnavs' ],
				],
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label'     => esc_html__( 'Arrows Hover Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-slidenav:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'arrows_dots', 'arrows_thumbnavs' ],
				],
			]
		);

		$this->add_control(
			'heading_dots',
			[
				'label'     => esc_html__( 'Dots', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
					'navigation' => [ 'dots', 'arrows_dots' ],
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label'     => esc_html__( 'Dots Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-dotnav li a' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'dots', 'arrows_dots' ],
				],
			]
		);

		$this->add_control(
			'active_dot_color',
			[
				'label'     => esc_html__( 'Active Dot Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-dotnav li.bdt-active a' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'dots', 'arrows_dots' ],
				],
			]
		);

		$this->add_control(
			'dots_size',
			[
				'label' => esc_html__( 'Dots Size', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-device-slider-container .bdt-dotnav a' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'arrows_dots' ],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_animation',
			[
				'label' => esc_html__( 'Animation', 'bdthemes-element-pack' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'   => esc_html__( 'Autoplay', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay_interval',
			[
				'label' => esc_html__( 'Autoplay Interval', 'bdthemes-element-pack' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 7000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'     => esc_html__( 'Pause on Hover', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'speed',
			[
				'label'              => esc_html__( 'Animation Speed', 'bdthemes-element-pack' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slider_animations',
			[
				'label'     => esc_html__( 'Slider Animations', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'default'   => 'slide',
				'options'   => [
					'slide' => esc_html__( 'Slide', 'bdthemes-element-pack' ),
					'fade'  => esc_html__( 'Fade', 'bdthemes-element-pack' ),
					'scale' => esc_html__( 'Scale', 'bdthemes-element-pack' ),
					'push'  => esc_html__( 'Push', 'bdthemes-element-pack' ),
					'pull'  => esc_html__( 'Pull', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'kenburns_animation',
			[
				'label'     => esc_html__( 'Kenburns Animation', 'bdthemes-element-pack' ),
				'separator' => 'before',
				'type'      => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

	}
	
	protected function render_header() {
		$settings        = $this->get_settings();
		$slides_settings = [];
		$device_type     = $settings['device_type'];
		$ratio           = '1280:720';
		
		if ('desktop' === $device_type) {
			$ratio = '1280:720';
		} elseif ('macbookpro' === $device_type) {
			$ratio = '1280:815';
		} elseif ('macbookair' === $device_type) {
			$ratio = '1280:810';
		} elseif ('tablet' === $device_type) {
			$ratio = '768:1024';
		} elseif ('galaxy' === $device_type) {
			$ratio = '634:1280';
		} elseif ('iphonex' === $device_type) {
			$ratio = '600:1280';
		}


		$slider_settings['bdt-slideshow'] = json_encode(array_filter([
			'animation'         => $settings['slider_animations'],
			'ratio'             => $ratio,
			'autoplay'          => $settings['autoplay'],
			'autoplay-interval' => $settings['autoplay_interval'],
			'pause-on-hover'    => $settings['pause_on_hover'],
	    ]));


		?>
		<div class="bdt-device-slider-container">
			<div class="bdt-device-slider bdt-device-slider-<?php echo esc_attr($device_type); ?>">
				<div <?php echo \element_pack_helper::attrs($slider_settings); ?>>
					<div class="bdt-position-relative bdt-visible-toggle">
						<ul class="bdt-slideshow-items">
		<?php
	}

	protected function render_footer() {
		$settings = $this->get_settings();
		$device_type = $settings['device_type'];
			?>
				</ul>
						<?php if ('arrows' == $settings['navigation'] or 'arrows_dots' == $settings['navigation']) : ?>
							<a class="bdt-position-center-left bdt-position-small bdt-hidden-hover" href="#" bdt-slidenav-previous bdt-slideshow-item="previous"></a>
				    		<a class="bdt-position-center-right bdt-position-small bdt-hidden-hover" href="#" bdt-slidenav-next bdt-slideshow-item="next"></a>
						<?php endif; ?>


						<?php if ('dots' == $settings['navigation'] or 'arrows_dots' == $settings['navigation']) : ?>
							<div class="bdt-dotnav-wrapper">
								<ul class="bdt-dotnav bdt-flex-center">

								    <?php		
									$bdt_counter = 0;
									$slideshow_dots = $this->get_settings('slides');
									      
									foreach ( $slideshow_dots as $dot ) :

										
										echo '<li class="bdt-slideshow-dotnav bdt-active" bdt-slideshow-item="'.$bdt_counter.'"><a href="#"></a></li>';
										$bdt_counter++;

									endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="bdt-device-slider-device">
					<img src="<?php echo BDTEP_ASSETS_URL; ?>images/devices/<?php echo esc_attr( $device_type ); ?>.svg" alt="">
				</div>
			</div>
		</div>
		<?php
	}

	protected function rendar_item_image($image) {
		$image_src = wp_get_attachment_image_src( $image['image']['id'], 'full' );

		if ($image_src) :
			echo '<img src="'.esc_url($image_src[0]).'" alt="" bdt-cover>';
		endif;

		return 0;
	}

	protected function rendar_item_video($link) {
		$video_src = $link['video_link'];

		?>
		<video autoplay loop muted playslinline bdt-cover>
			<source src="<?php echo  $video_src; ?>" type="video/mp4">
		</video>
		<?php
	}

	protected function rendar_item_youtube($link) {

		$id = (preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link['youtube_link'], $match ) ) ? $match[1] : false;
		 $url = '//www.youtube.com/embed/' . $id . '?autoplay=1&amp;controls=0&amp;showinfo=0&amp;rel=0&amp;loop=1&amp;modestbranding=1&amp;wmode=transparent&amp;playsinline=1';

		?>
		<iframe src="<?php echo  esc_url( $url); ?>" frameborder="0" allowfullscreen bdt-cover></iframe>
		<?php
	}

	protected function rendar_item_content($content) {
		$settings            = $this->get_settings();

		?>
        <div class="bdt-slideshow-content-wrapper bdt-position-z-index bdt-position-<?php echo $settings['content_position']; ?> bdt-position-large bdt-text-<?php echo $settings['content_align']; ?>">

			<?php if ($content['title'] && ( 'yes' == $settings['show_title'] )) : ?>
				<div>
					<h2 class="bdt-device-slider-title bdt-display-inline-block" bdt-slideshow-parallax="x:300, -300">
						<?php echo wp_kses_post($content['title']); ?>
					</h2>
				</div>
			<?php endif; ?>

		</div>

		<?php
	}

	public function render() {
		$settings  = $this->get_settings();

		$this->render_header();

			foreach ( $settings['slides'] as $slide ) : ?>
					    
			        <li class="bdt-slideshow-item elementor-repeater-item-<?php echo $slide['_id']; ?>">
				        <?php if( 'yes' == $settings['kenburns_animation'] ) : ?>
							<div class="bdt-position-cover bdt-animation-kenburns bdt-animation-reverse bdt-transform-origin-center-left">
						<?php endif; ?>

				            <?php if (( $slide['background'] == 'image' ) && $slide['image']) : ?>
					            <?php $this->rendar_item_image($slide); ?>
					        <?php elseif (( $slide['background'] == 'video' ) && $slide['video_link']) : ?>
					            <?php $this->rendar_item_video($slide); ?>
					        <?php elseif (( $slide['background'] == 'youtube' ) && $slide['youtube_link']) : ?>
					            <?php $this->rendar_item_youtube($slide); ?>
					        <?php endif; ?>

				        <?php if( 'yes' == $settings['kenburns_animation'] ) : ?>
				            </div>
				        <?php endif; ?>

				        <?php if( 'none' !== $settings['overlay'] ) :
				        	$blend_type = ( 'blend' == $settings['overlay']) ? ' bdt-blend-'.$settings['blend_type'] : ''; ?>
				            <div class="bdt-overlay-default bdt-position-cover<?php echo esc_attr($blend_type); ?>"></div>
				        <?php endif; ?>

			            <?php $this->rendar_item_content($slide); ?>
			        </li>

				<?php endforeach;

		$this->render_footer();
	}
}
