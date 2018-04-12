<?php
namespace ElementorExtras\Modules\Switcher\Widgets;

// Elementor Extras Classes
use ElementorExtras\Base\Extras_Widget;
use ElementorExtras\Modules\Switcher\Skins;
use ElementorExtras\Group_Control_Transition;

// Elementor Classes
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Switcher
 *
 * @since 1.6.0
 */
class Switcher extends Extras_Widget {

	protected $_has_template_content = false;

	public function get_name() {
		return 'ee-switcher';
	}

	public function get_title() {
		return __( 'Switcher', 'elementor-extras' );
	}

	public function get_icon() {
		return 'nicon nicon-switcher';
	}

	public function get_categories() {
		return [ 'elementor-extras' ];
	}

	public function get_script_depends() {
		return [
			'ee-switcher',
			'parallax-element',
			'gsap-js',
			'splittext',
			'custom-ease',
			'jquery-resize',
			'jquery-appear',
		];
	}

	public static function requires_elementor_pro() {
		return false;
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Classic( $this ) );
	}

	protected function _register_controls() {

		$this->register_content_controls();
		$this->register_settings_controls();
		$this->register_effects_controls();
		$this->register_interaction_controls();
		$this->register_layout_style_controls();
		$this->register_media_style_controls();
		$this->register_title_style_controls();
		$this->register_menu_style_controls();
		$this->register_arrows_style_controls();
	}

	protected function register_content_controls() {

		$this->start_controls_section(
			'section_items',
			[
				'label' => __( 'Content', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'layout',
				[
					'label' 	=> __( 'Skin', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default'	=> 'default',
					'options' 	=> [
						'default' 	=> __( 'Default', 'elementor-extras' ),
						'overlay' 	=> __( 'Overlay', 'elementor-extras' ),
					],
					'prefix_class' => 'ee-switcher-skin--',
				]
			);

			$content = new Repeater();

			$content->start_controls_tabs( 'items_repeater' );

			$content->start_controls_tab( 'tab_content', [ 'label' => __( 'Content', 'elementor-extras' ) ] );

				$content->add_control(
					'image',
					[
						'label' 	=> __( 'Image', 'elementor-extras' ),
						'type' 		=> Controls_Manager::MEDIA,
						'default' 	=> [
							'url' 	=> Utils::get_placeholder_image_src(),
						],
					]
				);

				$content->add_control(
					'title',
					[
						'label' 	=> __( 'Title', 'elementor-extras' ),
						'type' 		=> Controls_Manager::TEXT,
						'default' 	=> __( 'Content Title', 'elementor-extras' ),
					]
				);

				$content->add_control(
					'label',
					[
						'label' 	=> __( 'Label', 'elementor-extras' ),
						'type' 		=> Controls_Manager::TEXT,
						'default' 	=> __( 'Navigation Label', 'elementor-extras' ),
					]
				);

				$content->add_control(
					'icon',
					[
						'label' 		=> __( 'Icon', 'elementor-extras' ),
						'type' 			=> Controls_Manager::ICON,
						'label_block' 	=> false,
					]
				);

				$content->add_control(
					'icon_align',
					[
						'label' 	=> __( 'Icon Position', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SELECT,
						'default' 	=> 'left',
						'options' 	=> [
							'left' 		=> __( 'Before', 'elementor-extras' ),
							'right' 	=> __( 'After', 'elementor-extras' ),
						],
						'condition' => [
							'icon!' => '',
						],
					]
				);

				$content->add_control(
					'icon_indent',
					[
						'label' 	=> __( 'Icon Spacing', 'elementor-extras' ),
						'type' 		=> Controls_Manager::SLIDER,
						'range' 	=> [
							'px' 	=> [
								'min' => 0,
								'max' => 50,
							],
						],
						'condition' => [
							'icon!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-icon--right' => 'margin-left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} {{CURRENT_ITEM}} .ee-icon--left' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

			$content->end_controls_tab();

			$content->start_controls_tab( 'tab_settings', [ 'label' => __( 'Settings', 'elementor-extras' ) ] );

				$content->add_control(
					'link',
					[
						'label' 		=> __( 'Link', 'elementor-extras' ),
						'type' 			=> Controls_Manager::URL,
						'placeholder' 	=> esc_url( home_url( '/' ) ),
						'frontend_available' => true,
					]
				);

			$content->end_controls_tab();

			$content->start_controls_tab( 'tab_style', [ 'label' => __( 'Style', 'elementor-extras' ) ] );

				$content->add_control(
					'background_switcher_heading',
					[
						'label' => __( 'Background Switcher', 'elementor-extras' ),
						'type'	=> Controls_Manager::HEADING,
					]
				);

				$content->add_control(
					'background_switcher_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'frontend_available' => true,
					]
				);

			$content->end_controls_tab();

			$content->end_controls_tabs();

			$this->add_control(
				'items',
				[
					'label' 	=> __( 'Items', 'elementor-extras' ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'title' 	=> __( 'Title', 'elementor-extras' ),
							'label' 	=> __( 'Item #1', 'elementor-extras' ),
						],
						[
							'title' 	=> __( 'Title', 'elementor-extras' ),
							'label' 	=> __( 'Item #2', 'elementor-extras' ),
						],
						[
							'title' 	=> __( 'Title', 'elementor-extras' ),
							'label' 	=> __( 'Item #2', 'elementor-extras' ),
						],
					],
					'fields' 		=> array_values( $content->get_controls() ),
					'title_field' 	=> '{{{ label }}}',
				]
			);

			$this->add_control(
				'linking_heading',
				[
					'label' => __( 'Linking', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'link_to',
				[
					'label' 	=> __( 'Link to', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'custom',
					'options' 	=> [
						'file' 			=> __( 'Media File', 'elementor-extras' ),
						'attachment' 	=> __( 'Attachment Page', 'elementor-extras' ),
						'custom' 		=> __( 'Item URL', 'elementor-extras' ),
					],
				]
			);

			$this->add_control(
				'link_image',
				[
					'label' 		=> __( 'Link Image', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'return_value' 	=> 'yes',
				]
			);

			$this->add_control(
				'link_open_lightbox',
				[
					'label' 	=> __( 'Lightbox', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'default',
					'options' 	=> [
						'default' 	=> __( 'Default', 'elementor-extras' ),
						'yes' 		=> __( 'Yes', 'elementor-extras' ),
						'no' 		=> __( 'No', 'elementor-extras' ),
					],
					'condition' => [
						'link_image!' 	=> '',
						'link_to' 		=> 'file',
					],
				]
			);

			$this->add_control(
				'link_title',
				[
					'label' 		=> __( 'Link Title', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'return_value' 	=> 'yes',
				]
			);

		$this->end_controls_section();

	}

	protected function register_settings_controls() {

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'settings_switcher_heading',
				[
					'label' => __( 'General', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'autoplay',
				[
					'label' 		=> __( 'Autoplay', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'duration',
				[
					'label' 		=> __( 'Autoplay Duration (s)', 'elementor-extras' ),
					'description'	=> __( 'How long should an item stay on screen before being switched.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0.2,
							'max' => 5,
							'step'=> 0.1,
						],
					],
					'condition'		=> [
						'autoplay!' => '',
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'autoplay_preview',
				[
					'label' 		=> __( 'Autoplay in Editor', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'return_value' 	=> 'yes',
					'condition'		=> [
						'autoplay!' => '',
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'autoplay_cancel',
				[
					'label' 		=> __( 'Stop on Interaction', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'return_value' 	=> 'yes',
					'condition'		=> [
						'autoplay!' => '',
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'loop',
				[
					'label' 		=> __( 'Loop', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'background_switcher_heading',
				[
					'label' => __( 'Background switcher', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'background_switcher',
				[
					'label' 		=> __( 'Enable', 'elementor-extras' ),
					'description' 	=> __( 'Turn on changing of widget, section or page background color when switching items.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'background_switcher_notice',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> __( 'Select the background color for each item under Content > Item > Style Tab.', 'elementor-extras' ),
					'content_classes' 	=> 'ee-raw-html ee-raw-html__info',
					'condition' 		=> [
						'background_switcher!' => ''
					],
				]
			);

			$this->add_control(
				'background_switcher_element',
				[
					'label' 	=> __( 'Change Element', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> '',
					'options' 	=> [
						'' 			=> __( 'Body', 'elementor-extras' ),
						'widget' 	=> __( 'Widget', 'elementor-extras' ),
						'section' 	=> __( 'Section', 'elementor-extras' ),
					],
					'condition' 		=> [
						'background_switcher!' => ''
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'settings_images_heading',
				[
					'label' => __( 'Images', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 		=> 'image', // Actually its `original_image_size`.
					'label' 	=> __( 'Image Size', 'elementor-extras' ),
					'exclude'	=> ['custom'],
					'default' 	=> 'full',
				]
			);

			$this->add_control(
				'settings_title_heading',
				[
					'label' => __( 'Title', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'title_heading_tag',
				[
					'label' 	=> __( 'HTML Tag', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'options' 	=> [
						'h1' 	=> __( 'H1', 'elementor-extras' ),
						'h2' 	=> __( 'H2', 'elementor-extras' ),
						'h3' 	=> __( 'H3', 'elementor-extras' ),
						'h4' 	=> __( 'H4', 'elementor-extras' ),
						'h5' 	=> __( 'H5', 'elementor-extras' ),
						'h6' 	=> __( 'H6', 'elementor-extras' ),
						'div'	=> __( 'div', 'elementor-extras' ),
						'span' 	=> __( 'span', 'elementor-extras' ),
					],
					'default' => 'h1',
				]
			);

			$this->add_control(
				'settings_navigation_heading',
				[
					'label' => __( 'Navigation', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'menu',
				[
					'label' 	=> __( 'Menu', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default'	=> 'show',
					'tablet_default' 	=> 'show',
					'mobile_default' 	=> 'hide',
					'options' 	=> [
						'show' 	=> __( 'Show', 'elementor-extras' ),
						'hide' 	=> __( 'Hide', 'elementor-extras' ),
					],
					'frontend_available' => true,
					'prefix_class' => 'ee-switcher-menu%s-',
				]
			);

			$this->add_responsive_control(
				'arrows',
				[
					'label' 			=> __( 'Arrows', 'elementor-extras' ),
					'type' 				=> Controls_Manager::SELECT,
					'default'			=> 'hide',
					'tablet_default' 	=> 'hide',
					'mobile_default' 	=> 'show',
					'options' 	=> [
						'show' 	=> __( 'Show', 'elementor-extras' ),
						'hide' 	=> __( 'Hide', 'elementor-extras' ),
					],
					'frontend_available' => true,
					'prefix_class' => 'ee-switcher-arrows%s-',
				]
			);

		$this->end_controls_section();
	}

	protected function register_effects_controls() {

		$this->start_controls_section(
			'section_effects',
			[
				'label' => __( 'Effects', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'effect_entrance',
				[
					'label' 		=> __( 'Entrance Animation', 'elementor-extras' ),
					'description'	=> __( 'Animate the first item when entering viewport.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'effect_entrance_preview',
				[
					'label' 		=> __( 'Preview in Editor', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
					'separator'		=> 'after',
					'condition'		=> [
						'effect_entrance!' => '',
					],
				]
			);

			$this->add_control(
				'speed',
				[
					'label' 		=> __( 'Animation Speed (s)', 'elementor-extras' ),
					'description'	=> __( 'The time it takes for the transition to complete.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0.2,
							'max' => 3,
							'step'=> 0.1,
						],
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'effects_media_heading',
				[
					'label' => __( 'Media', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator'	=> 'before',
				]
			);

			$this->add_control(
				'effect_media',
				[
					'label' 	=> __( 'Media Effect', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default'	=> 'swipeLeft',
					'options' 	=> [
						'coverLeft' 			=> __( 'Cover Left', 'elementor-extras' ),
						'coverRight' 			=> __( 'Cover Right', 'elementor-extras' ),
						'coverBottom' 			=> __( 'Cover Bottom', 'elementor-extras' ),
						'coverTop'	 			=> __( 'Cover Top', 'elementor-extras' ),
						'uncoverLeft' 			=> __( 'Uncover Left', 'elementor-extras' ),
						'uncoverRight' 			=> __( 'Uncover Right', 'elementor-extras' ),
						'uncoverBottom' 		=> __( 'Uncover Bottom', 'elementor-extras' ),
						'uncoverTop' 			=> __( 'Uncover Top', 'elementor-extras' ),
						'fade' 					=> __( 'Fade', 'elementor-extras' ),
						'slideLeft' 			=> __( 'Slide Left', 'elementor-extras' ),
						'slideRight' 			=> __( 'Slide Right', 'elementor-extras' ),
						'slideTop' 				=> __( 'Slide Top', 'elementor-extras' ),
						'slideBottom' 			=> __( 'Slide Bottom', 'elementor-extras' ),
						'flipHorizontal' 		=> __( 'Flip Horizontal', 'elementor-extras' ),
						'flipVertical' 			=> __( 'Flip Vertical', 'elementor-extras' ),
						'swipeLeft' 			=> __( 'Swipe Left', 'elementor-extras' ),
						'swipeRight' 			=> __( 'Swipe Right', 'elementor-extras' ),
						'swipeBottom' 			=> __( 'Swipe Bottom', 'elementor-extras' ),
						'swipeTop' 				=> __( 'Swipe Top', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'effect_media_zoom',
				[
					'label' 		=> __( 'Zoom', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
					'conditions'	=> [
						'relation'	=> 'or',
						'terms'		=> [
							[
								'name' 		=> 'effect_media',
								'operator'	=> '!=',
								'value'		=> 'flipVertical',
							],
							[
								'name' 		=> 'effect_media',
								'operator'	=> '!=',
								'value'		=> 'flipHorizontal',
							],
						]
					],
				]
			);

			$this->add_control(
				'effects_text_heading',
				[
					'label' => __( 'Title', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator'	=> 'before',
				]
			);

			$this->add_control(
				'effect_title',
				[
					'label' 	=> __( 'Title Effect', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default'	=> 'slideLeft',
					'options' 	=> [
						'slideLeft' 	=> __( 'Slide Left', 'elementor-extras' ),
						'slideRight' 	=> __( 'Slide Right', 'elementor-extras' ),
						'slideTop' 		=> __( 'Slide Top', 'elementor-extras' ),
						'slideBottom' 	=> __( 'Slide Bottom', 'elementor-extras' ),
						'fade' 			=> __( 'Fade', 'elementor-extras' ),
						'scale'			=> __( 'Scale', 'elementor-extras' ),
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'effect_title_stagger',
				[
					'label' 		=> __( 'Character Delay', 'elementor-extras' ),
					'description'	=> __( 'Wether or not to animate each character with a slight delay.', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> 'yes',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

	}

	protected function register_interaction_controls() {
		$this->start_controls_section(
			'section_interactions',
			[
				'label' => __( 'Interactions', 'elementor-extras' ),
			]
		);

			$this->add_control(
				'pan_heading',
				[
					'label' => __( 'Mouse Parallax', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'parallax_enable',
				[
					'label' 		=> __( 'Enable', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'return_value' 	=> 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'parallax_amount',
				[
					'label' 		=> __( 'Pan Amount', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0.1,
							'max' => 1,
							'step'=> 0.01,
						],
					],
					'frontend_available' => true,
					'condition'	=> [
						'parallax_enable!'	=> '',
					],
				]
			);

			$this->add_control(
				'parallax_pan_axis',
				[
					'label' 	=> __( 'Pan Axis', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default'	=> 'both',
					'options' 	=> [
						'both' 			=> __( 'Both', 'elementor-extras' ),
						'vertical' 		=> __( 'Vertical', 'elementor-extras' ),
						'horizontal' 	=> __( 'Horizontal', 'elementor-extras' ),
					],
					'frontend_available' => true,
					'condition'	=> [
						'parallax_enable!'	=> '',
					],
				]
			);

			// $this->add_control(
			// 	'tilt_heading',
			// 	[
			// 		'label' => __( 'Tilt', 'elementor-extras' ),
			// 		'type'	=> Controls_Manager::HEADING,
			// 		'separator' => 'before',
			// 		'condition'	=> [
			// 			'skin!' => 'overlay',
			// 		],
			// 	]
			// );

			// $this->add_control(
			// 	'tilt_enable',
			// 	[
			// 		'label'			=> __( 'Enable', 'elementor-extras' ),
			// 		'type' 			=> Controls_Manager::SWITCHER,
			// 		'default' 		=> '',
			// 		'label_on' 		=> __( 'Yes', 'elementor-extras' ),
			// 		'label_off' 	=> __( 'No', 'elementor-extras' ),
			// 		'return_value' 	=> 'yes',
			// 		'frontend_available' => true,
			// 		'condition'	=> [
			// 			'skin!' => 'overlay',
			// 		],
			// 	]
			// );

			// $this->add_control(
			// 	'tilt_axis',
			// 	[
			// 		'label'			=> __( 'Axis', 'elementor-extras' ),
			// 		'type' 			=> Controls_Manager::SELECT,
			// 		'default' 		=> '',
			// 		'options' 			=> [
			// 			'' 		=> __( 'Both', 'elementor-extras' ),
			// 			'x' 	=> __( 'X Only', 'elementor-extras' ),
			// 			'y' 	=> __( 'Y Only', 'elementor-extras' ),
			// 		],
			// 		'frontend_available' => true,
			// 		'condition' => [
			// 			'skin!' => 'overlay',
			// 			'tilt_enable' => 'yes',
			// 		],
			// 	]
			// );

			// $this->add_control(
			// 	'tilt_amount',
			// 	[
			// 		'label' 	=> __( 'Amount', 'elementor-extras' ),
			// 		'type' 		=> Controls_Manager::SLIDER,
			// 		'range' 	=> [
			// 			'px' 	=> [
			// 				'min' => 10,
			// 				'max' => 40,
			// 			],
			// 		],
			// 		'default' 	=> [
			// 			'size' 	=> 20,
			// 		],
			// 		'frontend_available' => true,
			// 		'condition' => [
			// 			'skin!' => 'overlay',
			// 			'tilt_enable' => 'yes',
			// 		],
			// 	]
			// );

			// $this->add_control(
			// 	'tilt_scale',
			// 	[
			// 		'label' 	=> __( 'Scale', 'elementor-extras' ),
			// 		'type' 		=> Controls_Manager::SLIDER,
			// 		'range' 	=> [
			// 			'px' 	=> [
			// 				'min' 	=> 1,
			// 				'max' 	=> 1.5,
			// 				'step'	=> 0.01,
			// 			],
			// 		],
			// 		'default' 		=> [
			// 			'size' 		=> 1.05,
			// 		],
			// 		'frontend_available' => true,
			// 		'condition' => [
			// 			'skin!' => 'overlay',
			// 			'tilt_enable' => 'yes',
			// 		],
			// 	]
			// );

			// $this->add_control(
			// 	'tilt_speed',
			// 	[
			// 		'label' 	=> __( 'Speed', 'elementor-extras' ),
			// 		'type' 		=> Controls_Manager::SLIDER,
			// 		'range' 	=> [
			// 			'px' 	=> [
			// 				'min' 	=> 100,
			// 				'max' 	=> 1000,
			// 				'step'	=> 50,
			// 			],
			// 		],
			// 		'default' 		=> [
			// 			'size' 		=> 800,
			// 		],
			// 		'frontend_available' => true,
			// 		'condition' => [
			// 			'skin!' => 'overlay',
			// 			'tilt_enable' => 'yes',
			// 		],
			// 	]
			// );

		$this->end_controls_section();
	}

	protected function register_layout_style_controls() {
		$this->start_controls_section(
			'section_style_layout',
			[
				'label' => __( 'Layout', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'layout_stack',
				[
					'label' 	=> __( 'Stack on', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SELECT,
					'default'	=> 'tablet',
					'options' 	=> [
						'mobile' 	=> __( 'Mobile', 'elementor-extras' ),
						'tablet' 	=> __( 'Tablet', 'elementor-extras' ),
						'desktop' 	=> __( 'Desktop', 'elementor-extras' ),
					],
					'condition'		=> [
						'layout' => 'default',
					],
					'prefix_class' => 'ee-switcher-stack-'
				]
			);

			$this->add_responsive_control(
				'layout_height',
				[
					'label' 		=> __( 'Min Height', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 800,
						],
						'vh' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'condition'		=> [
						'layout' => 'overlay',
					],
					'size_units' => [ 'px', 'vh' ],
					'selectors'	=> [
						'{{WRAPPER}}.ee-switcher-skin--overlay .ee-switcher__wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
					]
				]
			);

			$this->add_responsive_control(
				'layout_spacing',
				[
					'label' 	=> __( 'Spacing', 'elementor-extras' ),
					'type' 		=> Controls_Manager::SLIDER,
					'range' 	=> [
						'px' 	=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'	=> [
						'{{WRAPPER}}.ee-switcher-skin--default .ee-switcher__wrapper' => 'margin-left: -{{SIZE}}px;',
						'{{WRAPPER}}.ee-switcher-skin--default .ee-switcher__media-wrapper,
						 {{WRAPPER}}.ee-switcher-skin--default .ee-switcher__content-wrapper' => 'padding-left: {{SIZE}}px;',
					],
					'condition'	=> [
						'layout' => 'default',
					]
				]
			);

			$this->add_responsive_control(
				'layout_content_padding',
				[
					'label' 		=> __( 'Content Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-switcher__content' => 'padding: {{SIZE}}px',
					],
				]
			);

			$this->add_control(
				'layout_vertical_aligment',
				[
					'label' 		=> __( 'Vertical Align', 'elementor-extras' ),
					'label_block' 	=> false,
					'type' 			=> Controls_Manager::CHOOSE,
					'options' 		=> [
						'flex-start' 	=> [
							'title' 	=> __( 'Top', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-top',
						],
						'center' 		=> [
							'title' 	=> __( 'Middle', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-middle',
						],
						'flex-end' 		=> [
							'title' 	=> __( 'Bottom', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-bottom',
						],
						'stretch' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-v-align-stretch',
						],
					],
					'default' 		=> 'center',
					'selectors'		=> [
						'{{WRAPPER}} .ee-switcher__wrapper' => 'align-items: {{VALUE}};',
						'{{WRAPPER}} .ee-switcher__content-wrapper' => 'align-items: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'layout_reverse',
				[
					'label' 		=> __( 'Reverse', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default'		=> '',
					'return_value' 	=> 'reverse',
					'prefix_class'	=> 'ee-switcher-layout--',
					'condition'		=> [
						'layout' => 'default',
					],
				]
			);

		$this->end_controls_section();
	}

	protected function register_media_style_controls() {

		$this->start_controls_section(
			'section_style_media',
			[
				'label' => __( 'Media', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'media_width',
				[
					'label' 		=> __( 'Width', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 500,
						],
						'%' 		=> [
							'min' => 10,
							'max' => 80,
						],
					],
					'condition'		=> [
						'layout' => 'default',
					],
					'size_units' 	=> [ 'px', '%' ],
					'selectors'		=> [
						'{{WRAPPER}}.ee-switcher-skin--default .ee-switcher__media-wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
					],
					'condition'		=> [
						'layout' => 'default',
					],
				]
			);

			$this->add_responsive_control(
				'media_height',
				[
					'label' 		=> __( 'Min Height', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 800,
						],
						'vh' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'condition'		=> [
						'layout' => 'default',
					],
					'size_units' => [ 'px', 'vh' ],
					'selectors'	=> [
						'{{WRAPPER}} .ee-switcher__media' => 'min-height: {{SIZE}}{{UNIT}}',
					],
					'condition'		=> [
						'layout' => 'default',
					],
				]
			);

			$this->add_responsive_control(
				'media_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 20,
						],
					],
					'condition'		=> [
						'layout' => 'default',
					],
					'selectors'	=> [
						'{{WRAPPER}} .ee-switcher__media' => 'border-radius: {{SIZE}}px',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'media',
					'selector' 	=> '{{WRAPPER}} .ee-switcher__media',
					'exclude'	=> [ 'box_shadow_position' ],
					'separator'	=> '',
				]
			);

			$this->add_control(
				'media_overlay',
				[
					'label' => __( 'Overlay', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'condition'		=> [
						'layout' => 'overlay',
					],
					'separator' => 'before',
				]
			);

			$this->add_control(
				'media_overlay_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-media__overlay' => 'background-color: {{VALUE}};',
					],
					'condition'		=> [
						'layout' => 'overlay',
					],
				]
			);

			$this->add_control(
				'media_overlay_blend',
				[
					'label' 		=> __( 'Blend mode', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'normal',
					'options' => [
						'normal'			=> __( 'Normal', 'elementor-extras' ),
						'multiply'			=> __( 'Multiply', 'elementor-extras' ),
						'screen'			=> __( 'Screen', 'elementor-extras' ),
						'overlay'			=> __( 'Overlay', 'elementor-extras' ),
						'darken'			=> __( 'Darken', 'elementor-extras' ),
						'lighten'			=> __( 'Lighten', 'elementor-extras' ),
						'color'				=> __( 'Color', 'elementor-extras' ),
						'color-dodge'		=> __( 'Color Dodge', 'elementor-extras' ),
						'hue'				=> __( 'Hue', 'elementor-extras' ),
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-switcher__media__overlay' => 'mix-blend-mode: {{VALUE}};',
					],
					'condition'		=> [
						'layout' => 'overlay',
					],
				]
			);

			$this->add_control(
				'media_overlay_blend_notice',
				[
					'type' 				=> Controls_Manager::RAW_HTML,
					'raw' 				=> sprintf( __( 'Please check blend mode support for your browser %1$s here %2$s', 'elementor-extras' ), '<a href="https://caniuse.com/#search=mix-blend-mode" target="_blank">', '</a>' ),
					'content_classes' 	=> 'ee-raw-html ee-raw-html__warning',
					'condition' 		=> [
						'overlay_blend!' => 'normal',
						'layout' => 'overlay',
					],
				]
			);

		$this->end_controls_section();
	}

	protected function register_title_style_controls() {

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'title_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-switcher__title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'title_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-switcher__items' 	=> 'text-align: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'title_overlap',
				[
					'label' 		=> __( 'Overlap', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'vw' 		=> [
							'min' => 0,
							'max' => 50,
						],
						'px' 		=> [
							'min' => 0,
							'max' => 200,
						],
					],
					'size_units' => [ 'vw', 'px' ],
					'condition'		=> [
						'layout' => 'default',
					],
					'selectors'		=> [
						'{{WRAPPER}} .ee-switcher__items' 								=> 'margin-left: -{{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.ee-switcher-layout--reverse .ee-switcher__items' 	=> 'margin-left: 0px; margin-right: -{{SIZE}}{{UNIT}};',
					]
				]
			);

			$this->add_responsive_control(
				'title_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-switcher__items' => 'margin-top: {{SIZE}}px',
					],
				]
			);

			$this->add_responsive_control(
				'title_font_size',
				[
					'label' 		=> __( 'Font Size (vw)', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 30,
						],
					],
					'default'		=> [
						'size'		=> 8,
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-switcher__title' => 'font-size: {{SIZE}}vw',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'title_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'exclude'	=> [ 'font_size', 'font_style' ],
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-switcher__title',
				]
			);

		$this->end_controls_section();

	}

	protected function register_menu_style_controls() {

		$this->start_controls_section(
			'section_style_menu',
			[
				'label' => __( 'Menu', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'menu_layout_heading',
				[
					'label' => __( 'Layout', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'menu_direction',
				[
					'label' 		=> __( 'Direction', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'row',
					'options' 		=> [
						'row'    	=> [
							'title' 	=> __( 'Vertical', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-block',
						],
						'column' 		=> [
							'title' 	=> __( 'Horizontal', 'elementor-extras' ),
							'icon' 		=> 'nicon nicon-inline',
						],
					],
					'label_block'	=> false,
				]
			);

			$this->add_responsive_control(
				'menu_align',
				[
					'label' 		=> __( 'Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'justify',
					'options' 		=> [
						'left' 			=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'justify' 		=> [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'label_block'		=> false,
					'prefix_class' 		=> 'ee-switcher-menu%s-align--',
				]
			);

			$this->add_responsive_control(
				'menu_text_align',
				[
					'label' 		=> __( 'Text Align', 'elementor-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'flex-start'	=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'flex-end' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'condition'			=> [
						'menu_align' => 'justify',
					],
					'label_block'		=> false,
					'selectors'			=> [
						'{{WRAPPER}} .ee-switcher__nav__item' => 'justify-content: {{VALUE}};',
					]
				]
			);

			$this->add_responsive_control(
				'menu_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-switcher__nav' => 'margin-top: {{SIZE}}px',
					],
				]
			);

			$this->add_control(
				'menu_items_heading',
				[
					'label' => __( 'Items', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'menu_items_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 48,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-nav.ee-nav--stacked .ee-switcher__nav__item' 	=> 'margin-bottom: {{SIZE}}px',
						'{{WRAPPER}} .ee-nav.ee-nav--inline' 							=> 'margin-left: -{{SIZE}}px; margin-bottom: {{SIZE}}px',
						'{{WRAPPER}} .ee-nav.ee-nav--inline .ee-switcher__nav__item' 	=> 'margin-left: {{SIZE}}px',
					],
				]
			);

			$this->add_responsive_control(
				'menu_items_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-switcher__nav__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'menu_typography',
					'label' 	=> __( 'Typography', 'elementor-extras' ),
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'selector' 	=> '{{WRAPPER}} .ee-switcher__nav__item',
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'menu',
					'selector' 		=> '{{WRAPPER}} .ee-switcher__nav__item',
				]
			);

			$this->update_control( 'menu_transition', array(
				'default' => 'custom',
			));

			$this->start_controls_tabs( 'menu_items_tabs' );

			$this->start_controls_tab( 'menu_items_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'menu_items_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-switcher__nav__item' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'menu_items_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-switcher__nav__item' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'menu_items_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'menu_items_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-switcher__nav__item:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'menu_items_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-switcher__nav__item:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'menu_items_tab_active', [ 'label' => __( 'Active', 'elementor-extras' ) ] );

				$this->add_control(
					'menu_items_color_active',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-switcher__nav__item.is--active' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'menu_items_background_color_active',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-switcher__nav__item.is--active' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'menu_style_loader',
				[
					'label' => __( 'Separator', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'menu_separator_color',
				[
					'label' 	=> __( 'Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ee-loader' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'menu_loader_color',
				[
					'label' 	=> __( 'Loader Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
					    'type' 	=> Scheme_Color::get_type(),
					    'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ee-loader__progress' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'menu_separator_thickness',
				[
					'label' 		=> __( 'Thickness', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 10,
						],
					],
					'default'		=> [
						'size'		=> 1,
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-loader' => 'height: {{SIZE}}{{UNIT}}',
					],
				]
			);

		$this->end_controls_section();

	}

	protected function register_arrows_style_controls() {

		$this->start_controls_section(
			'section_style_arrows',
			[
				'label' => __( 'Arrows', 'elementor-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'arrows_distance',
				[
					'label' 		=> __( 'Distance', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-switcher__arrows' => 'margin-top: {{SIZE}}px',
					],
				]
			);

			$this->add_responsive_control(
				'arrows_align',
				[
					'label' 			=> __( 'Align', 'elementor-extras' ),
					'type' 				=> Controls_Manager::CHOOSE,
					'mobile_default' 	=> 'center',
					'options' 			=> [
						'flex-start' 	=> [
							'title' 	=> __( 'Left', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> __( 'Center', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'flex-end' 		=> [
							'title' 	=> __( 'Right', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-right',
						],
						'space-between' => [
							'title' 	=> __( 'Stretch', 'elementor-extras' ),
							'icon' 		=> 'eicon-h-align-stretch',
						],
					],
					'label_block'	=> false,
					'selectors' 	=> [
						'{{WRAPPER}} .ee-switcher__arrows' => 'justify-content: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'arrows_spacing',
				[
					'label' 		=> __( 'Spacing', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-arrow--next' => 'margin-left: {{SIZE}}px;',
					],
					'condition'		=> [
						'arrows_align!' => 'space-between'
					],
				]
			);

			$this->add_responsive_control(
				'arrows_padding',
				[
					'label' 		=> __( 'Padding', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0.2,
							'max' => 2,
							'step'=> 0.1
						],
					],
					'default'		=> [
						'size'		=> 0.6,
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-arrow' => 'padding: {{SIZE}}em;',
					],
				]
			);

			$this->add_responsive_control(
				'arrows_size',
				[
					'label' 		=> __( 'Size', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 100,
						],
					],
					'default'		=> [
						'size'		=> 24,
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-arrow' => 'font-size: {{SIZE}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Transition::get_type(),
				[
					'name' 			=> 'arrows',
					'selector' 		=> '{{WRAPPER}} .ee-arrow',
				]
			);

			$this->update_control( 'arrows_transition', array(
				'default' => 'custom',
			));

			$this->start_controls_tabs( 'arrows_tabs' );

			$this->start_controls_tab( 'arrows_tab_default', [ 'label' => __( 'Default', 'elementor-extras' ) ] );

				$this->add_control(
					'arrows_color',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-arrow' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'arrows_background_color',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-arrow' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'arrows_opacity',
					[
						'label' 		=> __( 'Opacity', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 1,
								'step'=> 0.1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-arrow' => 'opacity: {{SIZE}}',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'arrows_tab_hover', [ 'label' => __( 'Hover', 'elementor-extras' ) ] );

				$this->add_control(
					'arrows_color_hover',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-arrow:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'arrows_background_color_hover',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-arrow:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'arrows_opacity_hover',
					[
						'label' 		=> __( 'Opacity', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 1,
								'step'=> 0.1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-arrow:hover' => 'opacity: {{SIZE}}',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'arrows_tab_disabled', [ 'label' => __( 'Disabled', 'elementor-extras' ) ] );

				$this->add_control(
					'arrows_color_disabled',
					[
						'label' 	=> __( 'Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-arrow.ee-arrow--disabled' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'arrows_background_color_disabled',
					[
						'label' 	=> __( 'Background Color', 'elementor-extras' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ee-arrow.ee-arrow--disabled' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'arrows_opacity_disabled',
					[
						'label' 		=> __( 'Opacity', 'elementor-extras' ),
						'type' 			=> Controls_Manager::SLIDER,
						'range' 		=> [
							'px' 		=> [
								'min' => 0,
								'max' => 1,
								'step'=> 0.1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .ee-arrow.ee-arrow--disabled' => 'opacity: {{SIZE}}',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'arrows_style_loader',
				[
					'label' => __( 'Loader', 'elementor-extras' ),
					'type'	=> Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'arrows_loader_color',
				[
					'label' 	=> __( 'Loader Color', 'elementor-extras' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
					    'type' 	=> Scheme_Color::get_type(),
					    'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ee-arrow__circle--loader.is--animating' => 'stroke: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'arrows_loader_thickness',
				[
					'label' 		=> __( 'Thickness', 'elementor-extras' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 0,
							'max' => 10,
						],
					],
					'default'		=> [
						'size'		=> 2,
					],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-arrow__circle--loader' => 'stroke-width: calc({{SIZE}} * 2);',
						'{{WRAPPER}} .ee-arrow .ee-arrow__circle--loader' => 'stroke-width: -webkit-calc({{SIZE}}px * 2);',
					],
				]
			);

		$this->end_controls_section();
	}

	public function render() {

		$this->add_render_attribute( 'switcher', 'class', [
			'ee-switcher',
			'ee-switcher--stack-' . $this->get_settings( 'layout_stack' )
		] );
		$this->add_render_attribute( 'switcher-wrapper', 'class', 'ee-switcher__wrapper' );
		$this->add_render_attribute( 'switcher-media-wrapper', 'class', [
			'ee-switcher__media-wrapper',
			'ee-media--stretch',
		]);
		$this->add_render_attribute( 'switcher-content-wrapper', 'class', 'ee-switcher__content-wrapper' );
		$this->add_render_attribute( 'switcher-content', 'class', 'ee-switcher__content' );

		?>

		<div <?php echo $this->get_render_attribute_string( 'switcher' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'switcher-wrapper' ); ?>>
	
				<div <?php echo $this->get_render_attribute_string( 'switcher-media-wrapper' ); ?>>
					<?php $this->render_media_loop(); ?>
				</div>

				<div <?php echo $this->get_render_attribute_string( 'switcher-content-wrapper' ); ?>>
					<div <?php echo $this->get_render_attribute_string( 'switcher-content' ); ?>>
						<?php
						$this->render_items_loop();
						$this->render_nav_loop();
						$this->render_arrows(); ?>
					</div>
				</div>

			</div>
		</div>

		<?php

	}

	public function render_items_loop() {

		$settings = $this->get_settings();

		$this->add_render_attribute( 'items', 'class', 'ee-switcher__items' );

		?>

		<div <?php echo $this->get_render_attribute_string( 'items' ); ?>>
			<?php foreach ( $settings['items'] as $index => $item ) {

				$title_tag 		= 'div';
				$heading_tag 	= $settings['title_heading_tag'];
				$item_key 		= $this->get_repeater_setting_key( 'item', 'items', $index );
				$item_title_key = $this->get_repeater_setting_key( 'item-title', 'items', $index );

				$this->add_render_attribute( $item_key, 'class', 'ee-switcher__items__item' );
				$this->add_render_attribute( $item_title_key, 'class', 'ee-switcher__title' );

				if ( 'yes' === $settings['link_title'] ) {
					$title_tag = 'a';
					$this->set_item_link_attributes( $item, $item_key );
				}
			?>

			<<?php echo $title_tag; ?> <?php echo $this->get_render_attribute_string( $item_key ); ?>>
				<<?php echo $heading_tag; ?> <?php echo $this->get_render_attribute_string( $item_title_key ); ?>><?php echo $item['title']; ?></<?php echo $heading_tag; ?>>
			</<?php echo $title_tag; ?>>

			<?php } ?>
		</div>

		<?php
	}

	public function render_media_loop() {

		$settings = $this->get_settings();

		$this->add_render_attribute( 'media', 'class', [
			'ee-switcher__media',
			'ee-media',
			'ee-effect--' . $settings['effect_media'],
		] );

		// if ( $settings['tilt_enable'] ) {
		// 	$this->add_render_attribute( 'media', 'class', 'ee-switcher__media--tilt' );
		// }

		$this->add_render_attribute( 'media-items', 'class', [
			'ee-switcher__media__items',
		] );

		$this->add_render_attribute( 'media-overlay', 'class', [
			'ee-switcher__media__overlay',
			'ee-media__overlay',
		] );

		?>

		<div <?php echo $this->get_render_attribute_string( 'media' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'media-items' ); ?>>
				<?php foreach ( $settings['items'] as $index => $item ) {

					$media_tag			= 'div';
					$media_item_key 	= $this->get_repeater_setting_key( 'media-item', 'items', $index );
					$image_key 			= $this->get_repeater_setting_key( 'image', 'items', $index );

					$this->add_render_attribute( $media_item_key, 'class', [
						'ee-switcher__media__item',
						'ee-media__thumbnail',
					]);

					if ( 'yes' === $settings['link_image'] ) {
						$media_tag = 'a';
						$this->set_item_link_attributes( $item, $media_item_key );
					}
				?>

				<<?php echo $media_tag; ?> <?php echo $this->get_render_attribute_string( $media_item_key ); ?>><?php
					if ( ! empty( $item['image']['url'] ) ) {
						$this->add_render_attribute( $image_key, [
							'src' 	=> $this->get_item_image_url( $item, $settings ),
							'alt' 	=> Control_Media::get_image_alt( $item['image'] ),
							'title' => Control_Media::get_image_title( $item['image'] )
						] );
					} ?>

					<img <?php echo $this->get_render_attribute_string( $image_key ) ?>/>
				</<?php echo $media_tag; ?>>

				<?php } ?>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'media-overlay' ); ?>></div>
		</div>

		<?php
	}

	public function render_nav_loop() {

		$settings = $this->get_settings();

		$this->add_render_attribute( 'nav', 'class', [
			'ee-switcher__nav',
			'ee-nav',
		]);

		if ( 'row' === $settings['menu_direction'] ) {
			$this->add_render_attribute( 'nav', 'class', 'ee-nav--stacked' );
		} else {
			$this->add_render_attribute( 'nav', 'class', 'ee-nav--inline' );
		}

		$this->add_render_attribute( 'loader', 'class', 'ee-loader' );
		$this->add_render_attribute( 'loader-progress', 'class', 'ee-loader__progress' );

		?>

		<ul  <?php echo $this->get_render_attribute_string( 'nav' ); ?>>
			<?php foreach ( $settings['items'] as $index => $item ) {

				$_has_icon 			= false;
				$nav_item_key 		= $this->get_repeater_setting_key( 'nav-item', 'items', $index );
				$nav_item_label_key = $this->get_repeater_setting_key( 'nav-item-label', 'items', $index );
				$nav_item_icon_key 	= $this->get_repeater_setting_key( 'nav-item-icon', 'items', $index );

				if ( ! empty( $item['icon'] ) ) {
					$_has_icon = true;

					$this->add_render_attribute( $nav_item_icon_key, 'class', [
						esc_attr( $item['icon'] ),
						'ee-icon--' . $item['icon_align'],
					] );

					$this->add_render_attribute( $nav_item_key, 'class', 'has--icon' );
				}

				if ( 'yes' === $settings['background_switcher'] ) {
					$this->add_render_attribute( $nav_item_key, 'data-switcher-background', $item['background_switcher_color'] );
				}

				// $this->add_inline_editing_attributes( $navItemLabelKey, 'none' );

				$this->add_render_attribute( $nav_item_key, 'class', [
					'ee-switcher__nav__item',
					'ee-nav__item',
					'elementor-repeater-item-' . $item['_id'],
				]);
			?>

			<li <?php echo $this->get_render_attribute_string( $nav_item_key ); ?>>
				<?php if ( $_has_icon ) { ?>
					<span <?php echo $this->get_render_attribute_string( $nav_item_icon_key ); ?>></span>
				<?php } ?>
				<?php if ( '' !== $item['label'] ) { ?>
					<span <?php echo $this->get_render_attribute_string( $nav_item_label_key ); ?>><?php echo $item['label']; ?></span>
				<?php } ?>
				<span <?php echo $this->get_render_attribute_string( 'loader' ); ?>>
					<span <?php echo $this->get_render_attribute_string( 'loader-progress' ); ?>></span>
				</span>
			</li>

			<?php } ?>
		</ul>

		<?php
	}

	public function render_arrows() {

		?>

		<ul class="ee-switcher__arrows">
			<li class="ee-arrow ee-arrow--prev"><i class="eicon-chevron-left"></i></li>
			<li class="ee-arrow ee-arrow--next"><i class="eicon-chevron-right"></i>
				<svg x="0px" y="0px" viewBox="0 0 80 80" xml:space="preserve" class="ee-arrow__svg">
					<defs>
						<clipPath id="clipLoader<?php echo $this->get_id(); ?>">
							<circle cx="40" cy="40" r="40"/>
						</clipPath>
					</defs>
					<circle transform="rotate(-90 40 40)" class="ee-arrow__circle--loader" stroke-dasharray="227" stroke-dashoffset="227" cx="40" cy="40" r="40" fill="transparent" stroke="transparent" stroke-width="4" vector-effect="non-scaling-stroke" clip-path="url(#clipLoader<?php echo $this->get_id(); ?>)" />
				</svg>
			</li>
		</ul>

		<?php
	}

	public function render_loader() {
		?>

		<div class="ee-switcher__loader ee-progress-loader">
			<span class="ee-progress-loader__inner"></span>
		</div>

		<?php
	}

	public function _content_template() { ?><#

			view.addRenderAttribute( 'switcher', 'class', [
				'ee-switcher',
				'ee-switcher--stack-' + settings.layout_stack,
			] );
			view.addRenderAttribute( 'switcher-wrapper', 'class', 'ee-switcher__wrapper' );
			view.addRenderAttribute( 'switcher-media-wrapper', 'class', [
				'ee-switcher__media-wrapper',
				'ee-media--stretch',
			]);
			view.addRenderAttribute( 'switcher-content-wrapper', 'class', 'ee-switcher__content-wrapper' );
			view.addRenderAttribute( 'switcher-content', 'class', 'ee-switcher__content' );

		#><div {{{ view.getRenderAttributeString( 'switcher' ) }}}>
			<div {{{ view.getRenderAttributeString( 'switcher-wrapper' ) }}}>
				
				<div {{{ view.getRenderAttributeString( 'switcher-media-wrapper' ) }}}>
					<?php $this->_media_loop_template(); ?>
				</div>

				<div {{{ view.getRenderAttributeString( 'switcher-content-wrapper' ) }}}>
					<div {{{ view.getRenderAttributeString( 'switcher-content' ) }}}>
						<?php
						$this->_items_loop_template();
						$this->_nav_loop_template();
						$this->render_arrows(); ?>
					</div>
				</div>

			</div>
		</div>

		<?php
	}

	public function _items_loop_template() { ?><#

		view.addRenderAttribute( 'items', 'class', 'ee-switcher__items' );

		#><div {{{ view.getRenderAttributeString( 'items' ) }}}>
			<# _.each( settings.items, function( item, index ) {

				var titleTag 		= 'div',
					headingTag 		= settings.title_heading_tag,
					itemKey 		= view.getRepeaterSettingKey( 'item', 'items', index ),
					itemTitleKey 	= view.getRepeaterSettingKey( 'itemTitle', 'items', index );

				view.addRenderAttribute( itemKey, 'class', 'ee-switcher__items__item' );
				view.addRenderAttribute( itemTitleKey, 'class', 'ee-switcher__title' );

				if ( 'yes' === settings.link_title ) {
					if ( 'file' == settings.link_to ) {

						titleTag = 'a';
						view.addRenderAttribute( itemKey, 'href', item.image.url );
						view.addRenderAttribute( itemKey, 'class', 'elementor-clickable' );
						view.addRenderAttribute( itemKey, 'data-elementor-open-lightbox', settings.link_open_lightbox );
						view.addRenderAttribute( itemKey, 'data-elementor-lightbox-slideshow', view.$el.data('id') );

					} else if ( 'attachment' === settings.link_to ) {

						titleTag = 'a';
						view.addRenderAttribute( itemKey, 'href', '' );

					} else if ( 'custom' === settings.link_to && '' !== item.link.url ) {

						titleTag = 'a';
						view.addRenderAttribute( itemKey, 'href', item.link.url );

					}
				}

			#>

			<{{{ titleTag }}} {{{ view.getRenderAttributeString( itemKey ) }}}>
				<{{{ headingTag }}} {{{ view.getRenderAttributeString( itemTitleKey ) }}}>{{ item.title }}</{{{ headingTag }}}>
			</{{{ titleTag }}}>

			<# }); #>
		</div>

		<?php
	}

	public function _media_loop_template() { ?><#

		view.addRenderAttribute( 'media', 'class', [
			'ee-switcher__media',
			'ee-media',
			'ee-effect--' + settings.effect_media,
		] );

		/* if ( settings.tilt_enable ) {
			view.addRenderAttribute( 'media', 'class', 'ee-switcher__media--tilt' );
		} */

		view.addRenderAttribute( 'media-items', 'class', [
			'ee-switcher__media__items',
		] );

		view.addRenderAttribute( 'media-overlay', 'class', [
			'ee-switcher__media__overlay',
			'ee-media__overlay',
		] );

		#>

		<div {{{ view.getRenderAttributeString( 'media' ) }}}>
			<div {{{ view.getRenderAttributeString( 'media-items' ) }}}>
				<# _.each( settings.items, function( item, index ) {

					var image = {
							id: item.image.id,
							url: item.image.url,
							size: settings.image_size,
							dimension: settings.image_custom_dimension,
							model: view.getEditModel()
						},
						mediaTag 		= 'div',
						imageURL 		= elementor.imagesManager.getImageUrl( image ),
						mediaItemKey 	= view.getRepeaterSettingKey( 'media-item', 'items', index ),
						imageKey 		= view.getRepeaterSettingKey( 'image', 'items', index );

					view.addRenderAttribute( mediaItemKey, 'class', [
						'ee-switcher__media__item',
						'ee-media__thumbnail',
					]);

					if ( 'yes' === settings.link_image ) {
						if ( 'file' == settings.link_to ) {

							mediaTag = 'a';
							view.addRenderAttribute( mediaItemKey, 'href', item.image.url );
							view.addRenderAttribute( mediaItemKey, 'class', 'elementor-clickable' );
							view.addRenderAttribute( mediaItemKey, 'data-elementor-open-lightbox', settings.link_open_lightbox );
							view.addRenderAttribute( mediaItemKey, 'data-elementor-lightbox-slideshow', view.$el.data('id') );

						} else if ( 'attachment' === settings.link_to ) {

							mediaTag = 'a';
							view.addRenderAttribute( mediaItemKey, 'href', '' );

						} else if ( 'custom' === settings.link_to && '' !== item.link.url ) {

							mediaTag = 'a';
							view.addRenderAttribute( mediaItemKey, 'href', item.link.url );

						}
					}

					view.addRenderAttribute( imageKey, 'src', imageURL );
				#>

				<{{{ mediaTag }}} {{{ view.getRenderAttributeString( mediaItemKey ) }}}>
					<# if ( imageURL ) { #>
						<img {{{ view.getRenderAttributeString( imageKey ) }}}>
					<# } #>
				</{{{ mediaTag }}}>

				<# }); #>
			</div>
			<div {{{ view.getRenderAttributeString( 'media-overlay' ) }}}></div>
		</div>

		<?php
	}

	public function _nav_loop_template() { ?><#

		view.addRenderAttribute( 'nav', 'class', [
			'ee-switcher__nav',
			'ee-nav',
		]);

		if ( 'row' === settings.menu_direction ) {
			view.addRenderAttribute( 'nav', 'class', 'ee-nav--stacked' );
		} else {
			view.addRenderAttribute( 'nav', 'class', 'ee-nav--inline' );
		}

		view.addRenderAttribute( 'loader', 'class', 'ee-loader' );
		view.addRenderAttribute( 'loader-progress', 'class', 'ee-loader__progress' );

		#><ul {{{ view.getRenderAttributeString( 'nav' ) }}}>
			<# _.each( settings.items, function( item, index ) {

				var _has_icon 		= false;
					navItemKey 		= view.getRepeaterSettingKey( 'nav-item', 'items', index ),
					navItemLabelKey = view.getRepeaterSettingKey( 'nav-item-label', 'items', index ),
					navItemIconKey 	= view.getRepeaterSettingKey( 'nav-item-icon', 'items', index );

				if ( '' !== item.icon ) {
					_has_icon = true;

					view.addRenderAttribute( navItemIconKey, 'class', [
						item.icon,
						'ee-icon--' + item.icon_align
					] );
					view.addRenderAttribute( navItemKey, 'class', 'has--icon' );
				}

				if ( 'yes' === settings.background_switcher ) {
					view.addRenderAttribute( navItemKey, 'data-switcher-background', item.background_switcher_color );
				}

				// view.addInlineEditingAttributes( navItemLabelKey, 'none' );

				view.addRenderAttribute( navItemKey, 'class', [
					'ee-switcher__nav__item',
					'ee-nav__item',
					'elementor-repeater-item-' + item._id
				]);

			#><li {{{ view.getRenderAttributeString( navItemKey ) }}}>
				<# if ( _has_icon ) { #>
					<span {{{ view.getRenderAttributeString( navItemIconKey ) }}}></span>
				<# } #>
				<# if ( '' !== item.label ) { #>
					<span {{{ view.getRenderAttributeString( navItemLabelKey ) }}}>{{{ item.label }}}</span>
				<# } #>
				<span {{{ view.getRenderAttributeString( 'loader' ) }}}>
					<span {{{ view.getRenderAttributeString( 'loader-progress' ) }}}></span>
				</span>
			</li>
			
			<# }); #>
		</ul>

		<?php
	}

	protected function get_item_image_url( $item, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['image']['id'], 'image', $settings );

		if ( ! $image_url ) {
			$image_url = $item['image']['url'];
		}

		return $image_url;
	}

	protected function set_item_link_attributes( $item, $key ) {

		$link_to = $this->get_settings('link_to');
		$open_lightbox = $this->get_settings('link_open_lightbox');

		if ( 'file' == $link_to ) {

			$this->add_render_attribute( $key, 'href', $item['image']['url'] );

			$this->add_render_attribute( $key, [
				'class' 							=> 'elementor-clickable',
				'data-elementor-open-lightbox' 		=> $open_lightbox,
				'data-elementor-lightbox-slideshow' => $this->get_id(),
			] );

		} else if ( 'attachment' === $link_to ) {

			$this->add_render_attribute( $key, 'href', get_attachment_link( $item['image']['id'] ) );

		} else if ( 'custom' === $link_to ) {

			if ( ! empty( $item['link']['url'] ) ) {

				$this->add_render_attribute( $key, 'href', $item['link']['url'] );

				if ( $item['link']['is_external'] ) {
					$this->add_render_attribute( $key, 'target', '_blank' );
				}

				if ( ! empty( $item['link']['nofollow'] ) ) {
					$this->add_render_attribute( $key, 'rel', 'nofollow' );
				}
			}
		}
	}

}