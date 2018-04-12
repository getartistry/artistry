<?php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once CASE27_INTEGRATIONS_DIR . '/elementor/traits.php';

class CASE27_Elementor_Integration {

	public $widgets, $controls;

	public function __construct()
	{
		$this->widgets = [
			'page-heading',
			'title-bar',
			'featured-section',
			'section-heading',
			'listing-categories',
			'listing-feed',
			'add-listing',
			'info-cards',
			'featured-service',
			'testimonials',
			'team',
			'image',
			'clients-slider',
			'map',
			'package-selection',
			'explore',
			'blog-feed',

			// Block Elements
			'content-block',
			'gallery-block',
			'countdown-block',
			'list-block',
			'table-block',
			'accordion-block',
			'tabs-block',
			'video-block',
		];

		$this->controls = [
			'icon',
		];

		add_action( 'elementor/init', array( $this, 'widgets_registered' ) );
		add_action( 'elementor/init', array( $this, 'controls_registered' ) );

		add_action('elementor/element/page-settings/section_page_settings/before_section_end', [$this, 'elementor_page_settings_controls']);

		add_action('elementor/element/section/section_background/before_section_end', function($section, $tab) {
			// dd($section, $tab);
			$section->add_control(
				'c27_use_parallax',
				[
					'label' => __( 'Use Parallax Effect?', 'my-listing' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'label_on' => __( 'Yes', 'my-listing' ),
					'label_off' => __( 'No', 'my-listing' ),
					'condition' => [
						'background_background' => [ 'classic' ],
					],
					'prefix_class' => 'elementor-section-use-parallax-',
				]
			);

			$section->add_control(
				'c27_blur_on_scroll',
				[
					'label' => __( 'Blur on scroll?', 'my-listing' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'label_on' => __( 'Yes', 'my-listing' ),
					'label_off' => __( 'No', 'my-listing' ),
					'condition' => [
						'background_background' => [ 'classic' ],
						'c27_use_parallax' => '',
					],
					'prefix_class' => 'elementor-section-blur-on-scroll-',
				]
			);

			$section->add_control(
				'c27_header_background_transition',
				[
					'label' => __( 'Enable Header Background Transition?', 'my-listing' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'label_on' => __( 'Yes', 'my-listing' ),
					'label_off' => __( 'No', 'my-listing' ),
					'condition' => [
						'background_background' => [ 'classic' ],
						'c27_use_parallax' => '',
						'c27_blur_on_scroll' => 'yes',
					],
					'prefix_class' => 'elementor-section-enable-header-background-transition-',
				]
			);
		}, 10, 2);

		// add_filter('elementor/controls/get_available_tabs_controls', function($controls) {
		// 	dump($controls);
		// });

		// add_action( 'elementor/frontend/after_register_scripts', function() {
			// wp_enqueue_style('c27-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');
			// CASE27_Assets::instance()->enqueue_icons();
			// CASE27_Assets::instance()->enqueue_scripts();
		// });

		add_action( 'elementor/editor/after_enqueue_scripts', function() {
        	CASE27_Admin::instance()->enqueue_scripts();
		});

		add_action('elementor/init', function() {
			add_action( 'wp_enqueue_scripts', function() {
				wp_add_inline_style( 'theme-styles-default', $this->custom_styles());
			}, 50);
		});

		add_action('wp_enqueue_scripts', function() {
			if ( class_exists( 'Elementor\Frontend' ) ) {
				Elementor\Plugin::instance()->frontend->enqueue_styles();
			}
		});
	}

	public function widgets_registered()
	{
		if ( ! defined( 'ELEMENTOR_PATH' ) || ! class_exists( 'Elementor\Widget_Base' ) || ! class_exists( 'Elementor\Plugin' ) ) {
			return false;
		}

		$elementor = Elementor\Plugin::instance();


		foreach ($this->widgets as $widget) {
			$template_file = CASE27_INTEGRATIONS_DIR . "/elementor/widgets/{$widget}.php";
			if ( file_exists( $template_file ) ) {
				require_once $template_file;
			}
		}
	}

	public function controls_registered()
	{
		if ( ! defined( 'ELEMENTOR_PATH' ) || ! class_exists( 'Elementor\Widget_Base' ) || ! class_exists( 'Elementor\Plugin' ) ) {
			return false;
		}

		foreach ($this->controls as $control) {
			$template_file = CASE27_INTEGRATIONS_DIR . "/elementor/controls/{$control}.php";
			if ( file_exists( $template_file ) ) {
				require_once $template_file;
			}
		}
	}

	public function elementor_page_settings_controls( $page )
	{
		$page->add_control(
			'c27_header_heading',
			[
				'label' => __( 'Header Options', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$page->add_control(
			'c27_hide_header',
			[
				'label' => __( 'Hide Header?', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Hide', 'my-listing' ),
				'label_off' => __( 'Show', 'my-listing' ),
			]
		);

		$page->add_control(
			'c27_header_blend_to_next_section',
			[
				'label' => __( 'Blend header to the next section?', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Yes', 'my-listing' ),
				'label_off' => __( 'No', 'my-listing' ),
			]
		);

		$page->add_control(
			'c27_show_title_bar',
			[
				'label' => __( 'Show Title Bar?', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => c27()->get_setting('header_show_title_bar', false) ? 'yes' : '',
				'label_on' => __( 'Show', 'my-listing' ),
				'label_off' => __( 'Hide', 'my-listing' ),
				'condition' => ['c27_hide_header' => ''],
			]
		);

		$page->add_control(
			'c27_customize_header',
			[
				'label' => __( 'Customize Header?', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Yes', 'my-listing' ),
				'label_off' => __( 'No', 'my-listing' ),
				'condition' => ['c27_hide_header' => ''],
			]
		);

		$page->add_control(
			'c27_header_style',
			[
				'label' => __( 'Height', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => c27()->get_setting('header_style', 'default'),
				'options' => [
					'default' => __( 'Normal', 'my-listing' ),
					'alternate' => __( 'Extended', 'my-listing' ),
				],
				'condition' => ['c27_customize_header' => 'yes'],
			]
		);

		$page->add_control(
			'c27_header_skin',
			[
				'label' => __( 'Text Color', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => c27()->get_setting('header_skin', 'dark'),
				'options' => [
					'dark' => __( 'Light', 'my-listing' ),
					'light' => __( 'Dark', 'my-listing' ),
				],
				'condition' => ['c27_customize_header' => 'yes'],
			]
		);

		$page->add_control(
			'c27_header_position',
			[
				'label' => __( 'Sticky header on scroll?', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => c27()->get_setting('header_fixed', true) == true ? 'yes' : '',
				'label_on' => __( 'Yes', 'my-listing' ),
				'label_off' => __( 'No', 'my-listing' ),
				'return_value' => 'yes',
				'condition' => ['c27_customize_header' => 'yes'],
			]
		);

		$page->add_control(
		    'c27_header_background',
		    [
		        'label' => __( 'Background Color', 'my-listing' ),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'default' => c27()->get_setting('header_background_color', 'rgba(29, 29, 31, 0.95)'),
				'condition' => ['c27_customize_header' => 'yes'],
		    ]
		);

		$page->add_control(
		    'c27_header_border_color',
		    [
		        'label' => __( 'Border Color', 'my-listing' ),
		        'type' => \Elementor\Controls_Manager::COLOR,
		        'default' => c27()->get_setting('header_border_color', 'rgba(29, 29, 31, 0.95)'),
				'condition' => ['c27_customize_header' => 'yes'],
		    ]
		);

		$page->add_control(
			'c27_header_show_search_form',
			[
				'label' => __( 'Show Search Form?', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => c27()->get_setting('header_show_search_form', true) == true ? 'yes' : '',
				'label_on' => __( 'Show', 'my-listing' ),
				'label_off' => __( 'Hide', 'my-listing' ),
				'return_value' => 'yes',
				'condition' => ['c27_customize_header' => 'yes'],
			]
		);

		$page->add_control(
			'c27_header_menu_location',
			[
				'label' => __( 'Main Menu Location', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => c27()->get_setting('header_menu_location', 'right'),
				'options' => [
					'left' => __( 'Left', 'my-listing' ),
					'right' => __( 'Right', 'my-listing' ),
				],
				'condition' => ['c27_customize_header' => 'yes'],
			]
		);

		$page->add_control(
			'c27_header_show_call_to_action',
			[
				'label' => __( 'Show Call to Action button?', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => c27()->get_setting('header_show_call_to_action_button', false) == true ? 'yes' : '',
				'label_on' => __( 'Show', 'my-listing' ),
				'label_off' => __( 'Hide', 'my-listing' ),
				'return_value' => 'yes',
				'condition' => ['c27_customize_header' => 'yes'],
			]
		);

		$page->add_control(
			'c27_footer_heading',
			[
				'label' => __( 'Footer Options', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$page->add_control(
			'c27_hide_footer',
			[
				'label' => __( 'Hide footer?', 'my-listing' ),
				'description' => __( 'Useful when you want to add a custom footer.', 'my-listing'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Hide', 'my-listing' ),
				'label_off' => __( 'Show', 'my-listing' ),
			]
		);

		$page->add_control(
			'c27_customize_footer',
			[
				'label' => __( 'Customize Footer?', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Yes', 'my-listing' ),
				'label_off' => __( 'No', 'my-listing' ),
				'condition' => ['c27_hide_footer' => ''],
			]
		);

		$page->add_control(
			'c27_footer_show_widgets',
			[
				'label' => __( 'Show Widgets', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'my-listing' ),
				'label_off' => __( 'Hide', 'my-listing' ),
				'return_value' => 'yes',
				'condition' => ['c27_customize_footer' => 'yes'],
			]
		);

		$page->add_control(
			'c27_footer_show_footer_menu',
			[
				'label' => __( 'Show Footer Menu', 'my-listing' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'my-listing' ),
				'label_off' => __( 'Hide', 'my-listing' ),
				'return_value' => 'yes',
				'condition' => ['c27_customize_footer' => 'yes'],
			]
		);

	}

	public function custom_styles()
	{
		$elementor = Elementor\Plugin::instance();
		// dd($elementor);
			// dd($elementor->schemes_manager->get_scheme( 'typography' )->get_scheme_value(), \Elementor\Scheme_Typography::TYPOGRAPHY_1);
		$typography = $elementor->schemes_manager->get_scheme( 'typography' )->get_scheme_value();
		$typo_classes = [
			'primary' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
			'secondary' => \Elementor\Scheme_Typography::TYPOGRAPHY_2,
			'body' => \Elementor\Scheme_Typography::TYPOGRAPHY_3,
			'accent' => \Elementor\Scheme_Typography::TYPOGRAPHY_4,
		];

		ob_start();

			foreach ($typo_classes as $typo_class => $typo_id) {
				if ( !empty( $typography[ $typo_id ] ) && is_array( $typography[ $typo_id ] ) && !empty( $typography[ $typo_id ][ 'font_family' ] ) && !empty( $typography[ $typo_id ][ 'font_weight' ] ) ) {
					$font_family = "font-family: '{$typography[ $typo_id ][ 'font_family' ]}', 'Poppins', sans-serif";
					$font_weight = "font-weight: {$typography[ $typo_id ][ 'font_weight' ]}";
					echo ".case27-{$typo_class}-text { $font_family !important; $font_weight !important; } ";

					if ( $typo_class == 'primary' ) {
						echo ".featured-section .fc-description h1, .featured-section .fc-description h2, .featured-section .fc-description h3, ";
						echo ".featured-section .fc-description h4, .featured-section .fc-description h5, .featured-section .fc-description h6 { $font_family !important; $font_weight !important; } ";
					}

					if ( $typo_class == 'secondary' ) {
						echo ".title-style-1 h5 { $font_family !important; $font_weight !important; } ";
					}

					if ( $typo_class == 'body' ) {
						echo "body, p { $font_family; } ";
						echo "p { $font_weight !important; }";
					}
				}
			}

		return ob_get_clean();
	}
}

new CASE27_Elementor_Integration;