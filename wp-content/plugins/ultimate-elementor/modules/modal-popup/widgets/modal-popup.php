<?php
/**
 * UAEL Modal Popup.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\ModalPopup\Widgets;


// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;

// UltimateElementor Classes.
use UltimateElementor\Base\Common_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Modal_Popup.
 */
class Modal_Popup extends Common_Widget {

	/**
	 * Retrieve Modal Popup Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_slug( 'Modal_Popup' );
	}

	/**
	 * Retrieve Modal Popup Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Modal_Popup' );
	}

	/**
	 * Retrieve Modal Popup Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Modal_Popup' );
	}

	/**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'uael-cookie-lib', 'uael-modal-popup' ];
	}


	/**
	 * Register Modal Popup controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _register_controls() {

		$this->register_general_content_controls();
		$this->register_modal_popup_content_controls();
		$this->register_close_content_controls();
		$this->register_display_content_controls();

		$this->register_title_style_controls();
		$this->register_content_style_controls();
		$this->register_button_style_controls();
		$this->register_cta_style_controls();
		$this->register_helpful_information();
	}

	/**
	 * Register Modal Popup General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_modal_popup_content_controls() {

		$this->start_controls_section(
			'section_modal',
			[
				'label' => __( 'Modal Popup', 'uael' ),
			]
		);

			$this->add_responsive_control(
				'modal_width',
				[
					'label'          => __( 'Modal Popup Width', 'uael' ),
					'type'           => Controls_Manager::SLIDER,
					'size_units'     => [ 'px', 'em', '%' ],
					'default'        => [
						'size' => '500',
						'unit' => 'px',
					],
					'tablet_default' => [
						'size' => '500',
						'unit' => 'px',
					],
					'mobile_default' => [
						'size' => '300',
						'unit' => 'px',
					],
					'range'          => [
						'px' => [
							'min' => 0,
							'max' => 1500,
						],
						'em' => [
							'min' => 0,
							'max' => 100,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'      => [
						'.uamodal-{{ID}} .uael-content' => 'width: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->add_control(
				'modal_effect',
				[
					'label'       => __( 'Modal Appear Effect', 'uael' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'uael-effect-1',
					'label_block' => true,
					'options'     => [
						'uael-effect-1'  => __( 'Fade in &amp; Scale', 'uael' ),
						'uael-effect-2'  => __( 'Slide in (right)', 'uael' ),
						'uael-effect-3'  => __( 'Slide in (bottom)', 'uael' ),
						'uael-effect-4'  => __( 'Newspaper', 'uael' ),
						'uael-effect-5'  => __( 'Fall', 'uael' ),
						'uael-effect-6'  => __( 'Side Fall', 'uael' ),
						'uael-effect-8'  => __( '3D Flip (horizontal)', 'uael' ),
						'uael-effect-9'  => __( '3D Flip (vertical)', 'uael' ),
						'uael-effect-10' => __( '3D Sign', 'uael' ),
						'uael-effect-11' => __( 'Super Scaled', 'uael' ),
						'uael-effect-13' => __( '3D Slit', 'uael' ),
						'uael-effect-14' => __( '3D Rotate Bottom', 'uael' ),
						'uael-effect-15' => __( '3D Rotate In Left', 'uael' ),
						'uael-effect-17' => __( 'Let me in', 'uael' ),
						'uael-effect-18' => __( 'Make way!', 'uael' ),
						'uael-effect-19' => __( 'Slip from top', 'uael' ),
					],
				]
			);

			$this->add_control(
				'overlay_color',
				[
					'label'     => __( 'Overlay Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => 'rgba(0,0,0,0.75)',
					'selectors' => [
						'.uamodal-{{ID}} .uael-overlay' => 'background: {{VALUE}};',
					],
				]
			);

		$this->end_controls_section();

	}

	/**
	 * Register Modal Popup Title Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'content',
			[
				'label' => __( 'Content', 'uael' ),
			]
		);

			$this->add_control(
				'preview_modal',
				[
					'label'        => __( 'Preview Modal Popup', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
				]
			);

			$this->add_control(
				'title',
				[
					'label'   => __( 'Title', 'uael' ),
					'type'    => Controls_Manager::TEXT,
					'dynamic' => [
						'active' => true,
					],
					'default' => __( 'This is Modal Title', 'uael' ),
				]
			);

			$this->add_control(
				'content_type',
				[
					'label'   => __( 'Content Type', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'photo',
					'options' => $this->get_content_type(),
				]
			);

			$this->add_control(
				'ct_content',
				[
					'label'      => __( 'Description', 'uael' ),
					'type'       => Controls_Manager::WYSIWYG,
					'default'    => __( 'Enter content here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​ Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'uael' ),
					'rows'       => 10,
					'show_label' => false,
					'dynamic'    => [
						'active' => true,
					],
					'condition'  => [
						'content_type' => 'content',
					],
				]
			);

			$this->add_control(
				'ct_photo',
				[
					'label'     => __( 'Photo', 'uael' ),
					'type'      => Controls_Manager::MEDIA,
					'default'   => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'dynamic'   => [
						'active' => true,
					],
					'condition' => [
						'content_type' => 'photo',
					],
				]
			);

			$this->add_control(
				'ct_video',
				[
					'label'       => __( 'Embed Code / URL', 'uael' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'dynamic'     => [
						'active' => true,
					],
					'condition'   => [
						'content_type' => 'video',
					],
				]
			);

			$this->add_control(
				'ct_saved_rows',
				[
					'label'     => __( 'Select Section', 'uael' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $this->get_saved_data( 'section' ),
					'default'   => '-1',
					'condition' => [
						'content_type' => 'saved_rows',
					],
				]
			);

			$this->add_control(
				'ct_saved_modules',
				[
					'label'     => __( 'Select Widget', 'uael' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $this->get_saved_data( 'widget' ),
					'default'   => '-1',
					'condition' => [
						'content_type' => 'saved_modules',
					],
				]
			);

			$this->add_control(
				'ct_page_templates',
				[
					'label'     => __( 'Select Page', 'uael' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $this->get_saved_data( 'page' ),
					'default'   => '-1',
					'condition' => [
						'content_type' => 'saved_page_templates',
					],
				]
			);

			$this->add_control(
				'video_url',
				[
					'label'       => __( 'Video URL', 'uael' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'dynamic'     => [
						'active' => true,
					],
					'condition'   => [
						'content_type' => [ 'youtube', 'vimeo' ],
					],
				]
			);

			$this->add_control(
				'iframe_url',
				[
					'label'       => __( 'iFrame URL', 'uael' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'dynamic'     => [
						'active' => true,
					],
					'condition'   => [
						'content_type' => 'iframe',
					],
				]
			);

			$this->add_control(
				'iframe_height',
				[
					'label'      => __( 'Height of iFrame', 'uael' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', '%' ],
					'default'    => [
						'size' => '500',
						'unit' => 'px',
					],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 2000,
						],
						'em' => [
							'min' => 0,
							'max' => 100,
						],
						'%'  => [
							'min' => 0,
							'max' => 2000,
						],
					],
					'selectors'  => [
						'.uamodal-{{ID}} .uael-modal-iframe .uael-modal-content-data' => 'height: {{SIZE}}{{UNIT}}',
					],
					'condition'  => [
						'content_type' => 'iframe',
					],
				]
			);

			$this->add_control(
				'video_ratio',
				[
					'label'              => __( 'Aspect Ratio', 'uael' ),
					'type'               => Controls_Manager::SELECT,
					'options'            => [
						'16_9' => '16:9',
						'4_3'  => '4:3',
						'3_2'  => '3:2',
					],
					'default'            => '16_9',
					'prefix_class'       => 'uael-aspect-ratio-',
					'frontend_available' => true,
					'condition'          => [
						'content_type' => [ 'youtube', 'vimeo' ],
					],
				]
			);

			$this->add_control(
				'video_autoplay',
				[
					'label'        => __( 'Autoplay', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
					'condition'    => [
						'content_type' => [ 'youtube', 'vimeo' ],
					],
					'selectors'    => [
						'.uamodal-{{ID}}' => '',
					],
				]
			);

			$this->add_control(
				'youtube_related_videos',
				[
					'label'        => __( 'Disable Related Videos', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
					'condition'    => [
						'content_type' => 'youtube',
					],
				]
			);

			$this->add_control(
				'youtube_title_controls',
				[
					'label'        => __( 'Disable Video Title', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
					'condition'    => [
						'content_type' => 'youtube',
					],
				]
			);

			$this->add_control(
				'youtube_player_controls',
				[
					'label'        => __( 'Disable Player Controls', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
					'condition'    => [
						'content_type' => 'youtube',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Modal Popup Title Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_close_content_controls() {

		$this->start_controls_section(
			'close_options',
			[
				'label' => __( 'Close Button', 'uael' ),
			]
		);

			$this->add_control(
				'close_source',
				[
					'label'   => __( 'Close As', 'uael' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => [
						'img'  => [
							'title' => __( 'Image', 'uael' ),
							'icon'  => 'fa fa-picture-o',
						],
						'icon' => [
							'title' => __( 'Icon', 'uael' ),
							'icon'  => 'fa fa-info-circle',
						],
					],
					'default' => 'icon',
				]
			);

			/**
			 * Condition: 'close_source' => 'img'
			 */
			$this->add_control(
				'close_photo',
				[
					'label'     => __( 'Close Image', 'uael' ),
					'type'      => Controls_Manager::MEDIA,
					'default'   => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'dynamic'   => [
						'active' => true,
					],
					'condition' => [
						'close_source' => 'img',
					],
				]
			);

			/**
			 * Condition: 'close_source' => 'icon'
			 */
			$this->add_control(
				'close_icon',
				[
					'label'     => __( 'Close Icon', 'uael' ),
					'type'      => Controls_Manager::ICON,
					'default'   => 'fa fa-close',
					'condition' => [
						'close_source' => 'icon',
					],
				]
			);

			$this->add_responsive_control(
				'close_icon_size',
				[
					'label'     => __( 'Size', 'uael' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => [
						'size' => 20,
					],
					'range'     => [
						'px' => [
							'max' => 500,
						],
					],
					'selectors' => [
						'.uamodal-{{ID}} .uael-modal-close'   => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
						'.uamodal-{{ID}} .uael-modal-close i' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
					],
					'condition' => [
						'close_source' => 'icon',
						'close_icon!'  => '',
					],
				]
			);

			$this->add_responsive_control(
				'close_img_size',
				[
					'label'     => __( 'Size', 'uael' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => [
						'size' => 20,
					],
					'range'     => [
						'px' => [
							'max' => 500,
						],
					],
					'selectors' => [
						'.uamodal-{{ID}} .uael-modal-close' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
					],
					'condition' => [
						'close_source' => 'img',
					],
				]
			);

			$this->add_control(
				'close_icon_color',
				[
					'label'     => __( 'Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffffff',
					'selectors' => [
						'.uamodal-{{ID}} .uael-modal-close i' => 'color: {{VALUE}};',
					],
					'condition' => [
						'close_source' => 'icon',
						'close_icon!'  => '',
					],
				]
			);

			$this->add_control(
				'icon_position',
				[
					'label'       => __( 'Image / Icon Position', 'uael' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'top-right',
					'label_block' => true,
					'options'     => [
						'top-left'             => __( 'Window - Top Left', 'uael' ),
						'top-right'            => __( 'Window - Top Right', 'uael' ),
						'popup-top-left'       => __( 'Popup - Top Left', 'uael' ),
						'popup-top-right'      => __( 'Popup - Top Right', 'uael' ),
						'popup-edge-top-left'  => __( 'Popup Edge - Top Left', 'uael' ),
						'popup-edge-top-right' => __( 'Popup Edge - Top Right', 'uael' ),
					],
				]
			);

			$this->add_control(
				'esc_keypress',
				[
					'label'        => __( 'Close on ESC Keypress', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
				]
			);

			$this->add_control(
				'overlay_click',
				[
					'label'        => __( 'Close on Overlay Click', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Modal Popup Title Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_display_content_controls() {

		$this->start_controls_section(
			'modal',
			[
				'label' => __( 'Display Settings', 'uael' ),
			]
		);

			$this->add_control(
				'modal_on',
				[
					'label'   => __( 'Display Modal On', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'button',
					'options' => [
						'icon'      => __( 'Icon', 'uael' ),
						'photo'     => __( 'Image', 'uael' ),
						'text'      => __( 'Text', 'uael' ),
						'button'    => __( 'Button', 'uael' ),
						'custom'    => __( 'Custom Class', 'uael' ),
						'custom_id' => __( 'Custom ID', 'uael' ),
						'automatic' => __( 'Automatic', 'uael' ),
					],
				]
			);

			$this->add_control(
				'icon',
				[
					'label'     => __( 'Icon', 'uael' ),
					'type'      => Controls_Manager::ICON,
					'default'   => 'fa fa-home',
					'condition' => [
						'modal_on' => 'icon',
					],
				]
			);

			$this->add_control(
				'icon_size',
				[
					'label'     => __( 'Size', 'uael' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => [
						'size' => 60,
					],
					'range'     => [
						'px' => [
							'max' => 500,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .uael-modal-action i' => 'font-size: {{SIZE}}px;width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;',
					],
					'condition' => [
						'modal_on' => 'icon',
					],
				]
			);

			$this->add_control(
				'icon_color',
				[
					'label'     => __( 'Icon Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .uael-modal-action i' => 'color: {{VALUE}};',
					],
					'condition' => [
						'modal_on' => 'icon',
					],
				]
			);

			$this->add_control(
				'icon_hover_color',
				[
					'label'     => __( 'Icon Hover Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .uael-modal-action i:hover' => 'color: {{VALUE}};',
					],
					'condition' => [
						'modal_on' => 'icon',
					],
				]
			);

			$this->add_control(
				'photo',
				[
					'label'     => __( 'Image', 'uael' ),
					'type'      => Controls_Manager::MEDIA,
					'default'   => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'dynamic'   => [
						'active' => true,
					],
					'condition' => [
						'modal_on' => 'photo',
					],
				]
			);

			$this->add_control(
				'img_size',
				[
					'label'     => __( 'Size', 'uael' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => [
						'size' => 60,
					],
					'range'     => [
						'px' => [
							'max' => 500,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .uael-modal-action img' => 'width: {{SIZE}}px;',
					],
					'condition' => [
						'modal_on' => 'photo',
					],
				]
			);

			$this->add_control(
				'modal_text',
				[
					'label'     => __( 'Text', 'uael' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Click Here', 'uael' ),
					'dynamic'   => [
						'active' => true,
					],
					'condition' => [
						'modal_on' => 'text',
					],
				]
			);

			$this->add_control(
				'modal_custom',
				[
					'label'       => __( 'Class', 'uael' ),
					'type'        => Controls_Manager::TEXT,
					'description' => __( 'Add your custom class without the dot. e.g: my-class', 'uael' ),
					'condition'   => [
						'modal_on' => 'custom',
					],
				]
			);

			$this->add_control(
				'modal_custom_id',
				[
					'label'       => __( 'Custom ID', 'uael' ),
					'type'        => Controls_Manager::TEXT,
					'description' => __( 'Add your custom id without the Pound key. e.g: my-id', 'uael' ),
					'condition'   => [
						'modal_on' => 'custom_id',
					],
				]
			);

			$this->add_control(
				'exit_intent',
				[
					'label'        => __( 'Exit Intent', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
					'condition'    => [
						'modal_on' => 'automatic',
					],
					'selectors'    => [
						'.uamodal-{{ID}}' => '',
					],
				]
			);

			$this->add_control(
				'after_second',
				[
					'label'        => __( 'After Few Seconds', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
					'condition'    => [
						'modal_on' => 'automatic',
					],
					'selectors'    => [
						'.uamodal-{{ID}}' => '',
					],
				]
			);

			$this->add_control(
				'after_second_value',
				[
					'label'     => __( 'Load After Seconds', 'uael' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => [
						'size' => 1,
					],
					'condition' => [
						'after_second' => 'yes',
						'modal_on'     => 'automatic',
					],
					'selectors' => [
						'.uamodal-{{ID}}' => '',
					],
				]
			);

			$this->add_control(
				'enable_cookies',
				[
					'label'        => __( 'Enable Cookies', 'uael' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'return_value' => 'yes',
					'label_off'    => __( 'No', 'uael' ),
					'label_on'     => __( 'Yes', 'uael' ),
					'condition'    => [
						'modal_on' => 'automatic',
					],
					'selectors'    => [
						'.uamodal-{{ID}}' => '',
					],
				]
			);

			$this->add_control(
				'close_cookie_days',
				[
					'label'     => __( 'Do Not Show After Closing (days)', 'uael' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => [
						'size' => 1,
					],
					'condition' => [
						'enable_cookies' => 'yes',
						'modal_on'       => 'automatic',
					],
					'selectors' => [
						'.uamodal-{{ID}}' => '',
					],
				]
			);

			$this->add_control(
				'btn_text',
				[
					'label'       => __( 'Button Text', 'uael' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Click Me', 'uael' ),
					'placeholder' => __( 'Click Me', 'uael' ),
					'dynamic'     => [
						'active' => true,
					],
					'condition'   => [
						'modal_on' => 'button',
					],
				]
			);

			$this->add_responsive_control(
				'btn_align',
				[
					'label'     => __( 'Alignment', 'uael' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'    => [
							'title' => __( 'Left', 'uael' ),
							'icon'  => 'fa fa-align-left',
						],
						'center'  => [
							'title' => __( 'Center', 'uael' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'   => [
							'title' => __( 'Right', 'uael' ),
							'icon'  => 'fa fa-align-right',
						],
						'justify' => [
							'title' => __( 'Justified', 'uael' ),
							'icon'  => 'fa fa-align-justify',
						],
					],
					'default'   => 'left',
					'condition' => [
						'modal_on' => 'button',
					],
					'toggle'    => false,
				]
			);

			$this->add_control(
				'btn_size',
				[
					'label'     => __( 'Size', 'uael' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'sm',
					'options'   => [
						'xs' => __( 'Extra Small', 'uael' ),
						'sm' => __( 'Small', 'uael' ),
						'md' => __( 'Medium', 'uael' ),
						'lg' => __( 'Large', 'uael' ),
						'xl' => __( 'Extra Large', 'uael' ),
					],
					'condition' => [
						'modal_on' => 'button',
					],
				]
			);

			$this->add_responsive_control(
				'btn_padding',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-modal-action-wrap .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'modal_on' => 'button',
					],
				]
			);

			$this->add_control(
				'btn_icon',
				[
					'label'       => __( 'Icon', 'uael' ),
					'type'        => Controls_Manager::ICON,
					'label_block' => true,
					'default'     => '',
					'condition'   => [
						'modal_on' => 'button',
					],
				]
			);

			$this->add_control(
				'btn_icon_align',
				[
					'label'     => __( 'Icon Position', 'uael' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'left',
					'options'   => [
						'left'  => __( 'Before', 'uael' ),
						'right' => __( 'After', 'uael' ),
					],
					'condition' => [
						'btn_icon!' => '',
						'modal_on'  => 'button',
					],
				]
			);

			$this->add_control(
				'btn_icon_indent',
				[
					'label'     => __( 'Icon Spacing', 'uael' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'condition' => [
						'btn_icon!' => '',
						'modal_on'  => 'button',
					],
					'selectors' => [
						'{{WRAPPER}} .uael-modal-action-wrap .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .uael-modal-action-wrap .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'all_align',
				[
					'label'     => __( 'Alignment', 'uael' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'   => [
							'title' => __( 'Left', 'uael' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'uael' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'uael' ),
							'icon'  => 'fa fa-align-right',
						],
					],
					'default'   => 'left',
					'condition' => [
						'modal_on' => array( 'icon', 'photo', 'text' ),
					],
					'selectors' => [
						'{{WRAPPER}} .uael-modal-action-wrap' => 'text-align: {{VALUE}};',
					],
					'toggle'    => false,
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Modal Popup Title Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_title_style_controls() {

		$this->start_controls_section(
			'section_title_typography',
			[
				'label'     => __( 'Title', 'uael' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'title!' => '',
				],
			]
		);

			$this->add_responsive_control(
				'title_alignment',
				[
					'label'     => __( 'Alignment', 'uael' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'   => [
							'title' => __( 'Left', 'uael' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'uael' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'uael' ),
							'icon'  => 'fa fa-align-right',
						],
					],
					'default'   => 'left',
					'selectors' => [
						'.uamodal-{{ID}} .uael-modal-title-wrap' => 'text-align: {{VALUE}};',
					],
					'toggle'    => false,
				]
			);

			$this->add_responsive_control(
				'title_spacing',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'.uamodal-{{ID}} .uael-modal-title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'    => [
						'top'    => '15',
						'bottom' => '15',
						'left'   => '25',
						'right'  => '25',
						'unit'   => 'px',
					],
				]
			);

			$this->add_control(
				'title_color',
				[
					'label'     => __( 'Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'.uamodal-{{ID}} .uael-modal-title-wrap .uael-modal-title' => 'color: {{VALUE}};',
						'{{WRAPPER}} .uael-modal-title-wrap .uael-modal-title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'title_bg_color',
				[
					'label'     => __( 'Background Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_2,
					],
					'selectors' => [
						'.uamodal-{{ID}} .uael-modal-title-wrap' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .uael-modal-title-wrap' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'title_tag',
				[
					'label'   => __( 'HTML Tag', 'uael' ),
					'type'    => Controls_Manager::SELECT,
					'options' => [
						'h1'   => __( 'H1', 'uael' ),
						'h2'   => __( 'H2', 'uael' ),
						'h3'   => __( 'H3', 'uael' ),
						'h4'   => __( 'H4', 'uael' ),
						'h5'   => __( 'H5', 'uael' ),
						'h6'   => __( 'H6', 'uael' ),
						'div'  => __( 'div', 'uael' ),
						'span' => __( 'span', 'uael' ),
						'p'    => __( 'p', 'uael' ),
					],
					'default' => 'h3',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'title_typography',
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '.uamodal-{{ID}} .uael-modal-title-wrap .uael-modal-title, {{WRAPPER}} .uael-modal-title-wrap .uael-modal-title',
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Modal Popup Title Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_content_style_controls() {

		$this->start_controls_section(
			'section_content_typography',
			[
				'label' => __( 'Content', 'uael' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'content_text_color',
				[
					'label'     => __( 'Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'.uamodal-{{ID}} .uael-content' => 'color: {{VALUE}};',
						'{{WRAPPER}} .uael-content'     => 'color: {{VALUE}};',
					],
					'condition' => [
						'content_type' => 'content',
					],
				]
			);

			$this->add_control(
				'content_bg_color',
				[
					'label'     => __( 'Background Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffffff',
					'selectors' => [
						'.uamodal-{{ID}} .uael-content' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'modal_spacing',
				[
					'label'      => __( 'Content Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'.uamodal-{{ID}} .uael-content .uael-modal-content-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'    => [
						'top'    => '25',
						'bottom' => '25',
						'left'   => '25',
						'right'  => '25',
						'unit'   => 'px',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'content_typography',
					'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
					'selector'  => '.uamodal-{{ID}} .uael-modal-content-data.uael-text-editor',
					'separator' => 'before',
					'condition' => [
						'content_type' => 'content',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Modal Popup Title Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_button_style_controls() {

		$this->start_controls_section(
			'section_button_style',
			[
				'label'     => __( 'Button', 'uael' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'modal_on' => 'button',
				],
			]
		);

			$this->add_control(
				'btn_html_message',
				[
					'type'      => Controls_Manager::RAW_HTML,
					'raw'       => sprintf( '<p style="font-size: 11px;font-style: italic;line-height: 1.4;color: #a4afb7;">%s</p>', __( 'To see these changes please turn off the preview setting from Content Tab.', 'uael' ) ),
					'condition' => [
						'preview_modal' => 'yes',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'btn_typography',
					'label'     => __( 'Typography', 'uael' ),
					'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
					'selector'  => '{{WRAPPER}} .uael-modal-action-wrap a.elementor-button, {{WRAPPER}} .uael-modal-action-wrap .elementor-button',
					'condition' => [
						'modal_on' => 'button',
					],
				]
			);

			$this->start_controls_tabs( 'tabs_button_style' );

				$this->start_controls_tab(
					'tab_button_normal',
					[
						'label'     => __( 'Normal', 'uael' ),
						'condition' => [
							'modal_on' => 'button',
						],
					]
				);

					$this->add_control(
						'button_text_color',
						[
							'label'     => __( 'Text Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .uael-modal-action-wrap a.elementor-button, {{WRAPPER}} .uael-modal-action-wrap .elementor-button' => 'color: {{VALUE}};',
							],
							'condition' => [
								'modal_on' => 'button',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name'           => 'btn_background_color',
							'label'          => __( 'Background Color', 'uael' ),
							'types'          => [ 'classic', 'gradient' ],
							'selector'       => '{{WRAPPER}} .uael-modal-action-wrap .elementor-button',
							'separator'      => 'before',
							'condition'      => [
								'modal_on' => 'button',
							],
							'fields_options' => [
								'color' => [
									'scheme' => [
										'type'  => Scheme_Color::get_type(),
										'value' => Scheme_Color::COLOR_4,
									],
								],
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_button_hover',
					[
						'label'     => __( 'Hover', 'uael' ),
						'condition' => [
							'modal_on' => 'button',
						],
					]
				);

					$this->add_control(
						'btn_hover_color',
						[
							'label'     => __( 'Text Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .uael-modal-action-wrap a.elementor-button:hover, {{WRAPPER}} .uael-modal-action-wrap .elementor-button:hover' => 'color: {{VALUE}};',
							],
							'condition' => [
								'modal_on' => 'button',
							],
						]
					);

					$this->add_control(
						'button_background_hover_color',
						[
							'label'     => __( 'Background Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'scheme'    => [
								'type'  => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'{{WRAPPER}} .uael-modal-action-wrap a.elementor-button:hover, {{WRAPPER}} .uael-modal-action-wrap .elementor-button:hover' => 'background-color: {{VALUE}};',
							],
							'condition' => [
								'modal_on' => 'button',
							],
						]
					);

					$this->add_control(
						'button_hover_border_color',
						[
							'label'     => __( 'Border Color', 'uael' ),
							'type'      => Controls_Manager::COLOR,
							'condition' => [
								'border_border!' => '',
							],
							'selectors' => [
								'{{WRAPPER}} .uael-modal-action-wrap a.elementor-button:hover, {{WRAPPER}} .uael-modal-action-wrap .elementor-button:hover' => 'border-color: {{VALUE}};',
							],
							'condition' => [
								'modal_on' => 'button',
							],
						]
					);

					$this->add_control(
						'btn_hover_animation',
						[
							'label'     => __( 'Hover Animation', 'uael' ),
							'type'      => Controls_Manager::HOVER_ANIMATION,
							'condition' => [
								'modal_on' => 'button',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'btn_border',
					'label'       => __( 'Border', 'uael' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .uael-modal-action-wrap .elementor-button',
					'separator'   => 'before',
					'condition'   => [
						'modal_on' => 'button',
					],
				]
			);

			$this->add_control(
				'btn_border_radius',
				[
					'label'      => __( 'Border Radius', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-modal-action-wrap a.elementor-button, {{WRAPPER}} .uael-modal-action-wrap .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'modal_on' => 'button',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'      => 'button_box_shadow',
					'selector'  => '{{WRAPPER}} .uael-modal-action-wrap .elementor-button',
					'condition' => [
						'modal_on' => 'button',
					],
				]
			);

			$this->add_control(
				'text_padding',
				[
					'label'      => __( 'Padding', 'uael' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uael-modal-action-wrap a.elementor-button, {{WRAPPER}} .uael-modal-action-wrap .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator'  => 'before',
					'condition'  => [
						'modal_on' => 'button',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Modal Popup Title Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_cta_style_controls() {

		$this->start_controls_section(
			'section_cta_style',
			[
				'label'     => __( 'Display Text', 'uael' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'modal_on' => 'text',
				],
			]
		);

			$this->add_control(
				'text_html_message',
				[
					'type'      => Controls_Manager::RAW_HTML,
					'raw'       => sprintf( '<p style="font-size: 11px;font-style: italic;line-height: 1.4;color: #a4afb7;">%s</p>', __( 'To see these changes please turn off the preview setting from Content Tab.', 'uael' ) ),
					'condition' => [
						'preview_modal' => 'yes',
					],
				]
			);

			$this->add_control(
				'text_color',
				[
					'label'     => __( 'Text Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .uael-modal-action' => 'color: {{VALUE}};',
					],
					'condition' => [
						'modal_on' => 'text',
					],
				]
			);

			$this->add_control(
				'text_hover_color',
				[
					'label'     => __( 'Text Hover Color', 'uael' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'scheme'    => [
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_3,
					],
					'selectors' => [
						'{{WRAPPER}} .uael-modal-action:hover' => 'color: {{VALUE}};',
					],
					'condition' => [
						'modal_on' => 'text',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'cta_text_typography',
					'label'     => __( 'Typography', 'uael' ),
					'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
					'selector'  => '{{WRAPPER}} .uael-modal-action-wrap .uael-modal-action',
					'condition' => [
						'modal_on' => 'text',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Helpful Information.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_helpful_information() {

		if ( parent::is_internal_links() ) {
			$this->start_controls_section(
				'section_helpful_info',
				[
					'label' => __( 'Helpful Information', 'uael' ),
				]
			);

			$this->add_control(
				'help_doc_1',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s doc link */
					'raw'             => sprintf( __( '%1$s Trigger Modal Popup on the click of menu » %2$s', 'uael' ), '<a href="https://uaelementor.com/docs/how-to-trigger-a-modal-popup-on-the-click-of-a-menu-element/" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
				]
			);

			$this->add_control(
				'help_doc_2',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s doc link */
					'raw'             => sprintf( __( '%1$s Close Modal Popup on click of button or link » %2$s', 'uael' ), '<a href="https://uaelementor.com/docs/is-it-possible-to-close-a-modal-popup-on-the-click-of-a-button-or-text/" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
				]
			);

			$this->add_control(
				'help_doc_3',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s doc link */
					'raw'             => sprintf( __( '%1$s Trigger Modal Popup from another widget » %2$s', 'uael' ), '<a href="https://uaelementor.com/docs/how-to-open-a-modal-popup-from-another-widget/" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'uael-editor-doc',
				]
			);

			$this->end_controls_section();
		}
	}
	/**
	 * Render content type list.
	 *
	 * @since 0.0.1
	 * @return array Array of content type
	 * @access public
	 */
	public function get_content_type() {

		$content_type = array(
			'content'              => __( 'Content', 'uael' ),
			'photo'                => __( 'Photo', 'uael' ),
			'video'                => __( 'Video Embed Code', 'uael' ),
			'saved_rows'           => __( 'Saved Section', 'uael' ),
			'saved_page_templates' => __( 'Saved Page', 'uael' ),
			'youtube'              => __( 'YouTube', 'uael' ),
			'vimeo'                => __( 'Vimeo', 'uael' ),
			'iframe'               => __( 'iFrame', 'uael' ),
		);

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_type['saved_modules'] = __( 'Saved Widget', 'uael' );
		}

		return $content_type;
	}

	/**
	 * Render button widget classes names.
	 *
	 * @since 0.0.1
	 * @param array $settings The settings array.
	 * @param int   $node_id The node id.
	 * @return string Concatenated string of classes
	 * @access public
	 */
	public function get_modal_content( $settings, $node_id ) {

		$content_type = $settings['content_type'];

		switch ( $content_type ) {
			case 'content':
				global $wp_embed;
				return '<div class="uael-text-editor elementor-inline-editing" data-elementor-setting-key="ct_content" data-elementor-inline-editing-toolbar="advanced">' . wpautop( $wp_embed->autoembed( $settings['ct_content'] ) ) . '</div>';
			break;
			case 'photo':
				if ( isset( $settings['ct_photo']['url'] ) ) {
					return '<img src="' . $settings['ct_photo']['url'] . '" />';
				}
				return '<img src="" />';
			break;

			case 'video':
				global $wp_embed;
				return $wp_embed->autoembed( $settings['ct_video'] );
			break;
			case 'iframe':
				return '<iframe src="' . $settings['iframe_url'] . '" class="uael-content-iframe" frameborder="0" width="100%" height="100%" allowfullscreen></iframe>';
			break;
			case 'saved_rows':
				return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['ct_saved_rows'] );
			case 'saved_modules':
				return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['ct_saved_modules'] );
			case 'saved_page_templates':
				return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['ct_page_templates'] );
			break;
			case 'youtube':
			case 'vimeo':
				return $this->get_video_embed( $settings, $node_id );
			default:
				return;
			break;
		}
	}

	/**
	 * Render Video.
	 *
	 * @since 0.0.1
	 * @param array $settings The settings array.
	 * @param int   $node_id The node id.
	 * @return string Concatenated string of html
	 * @access public
	 */
	public function get_video_embed( $settings, $node_id ) {

		if ( '' == $settings['video_url'] ) {
			return '';
		}

		$url            = $settings['video_url'];
		$vid_id         = '';
		$html           = '<div class="uael-video-wrap">';
		$related_videos = '';

		if ( 'youtube' == $settings['content_type'] ) {

			if ( preg_match( '/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches ) ) {
				$vid_id = $matches[1];
			}

			$related_videos = ( 'yes' == $settings['youtube_related_videos'] ) ? '&rel=0' : '';

			$title_controls = ( 'yes' == $settings['youtube_title_controls'] ) ? '&showinfo=0' : '';

			$player_controls = ( 'yes' == $settings['youtube_player_controls'] ) ? '&controls=0' : '';

			$html .= '<iframe id="uael-' . $node_id . '" class="uael-modal-iframe" src="https://www.youtube.com/embed/' . $vid_id . '?version=3&enablejsapi=1' . $related_videos . $title_controls . $player_controls . '" frameborder="0" allowfullscreen></iframe>';

		} elseif ( 'vimeo' == $settings['content_type'] ) {

			$vid_id = preg_replace( '/[^\/]+[^0-9]|(\/)/', '', rtrim( $url, '/' ) );
			$html  .= '<iframe id="uael-' . $node_id . '" class="uael-modal-iframe" src="https://player.vimeo.com/video/' . $vid_id . '?title=0&byline=0&portrait=0&badge=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		}
		$html .= '</div>';
		return $html;
	}

	/**
	 * Render Button.
	 *
	 * @since 0.0.1
	 * @param int   $node_id The node id.
	 * @param array $settings The settings array.
	 * @access public
	 */
	public function render_button( $node_id, $settings ) {

		$this->add_render_attribute( 'wrapper', 'class', 'uael-button-wrapper elementor-button-wrapper' );
		$this->add_render_attribute( 'button', 'href', 'javascript:void(0);' );
		$this->add_render_attribute( 'button', 'class', 'uael-trigger elementor-button-link elementor-button elementor-clickable' );

		if ( ! empty( $settings['btn_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['btn_size'] );
		}

		if ( ! empty( $settings['btn_align'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'elementor-align-' . $settings['btn_align'] );
		}

		if ( $settings['btn_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['btn_hover_animation'] );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<a <?php echo $this->get_render_attribute_string( 'button' ); ?> data-modal="<?php echo $node_id; ?>">
				<?php $this->render_button_text(); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render_button_text() {

		$settings = $this->get_settings();

		$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );
		$this->add_render_attribute(
			'icon-align',
			'class',
			[
				'elementor-align-icon-' . $settings['btn_icon_align'],
				'elementor-button-icon',
			]
		);

		$this->add_render_attribute(
			'btn-text',
			[
				'class'                                 => 'elementor-button-text elementor-inline-editing',
				'data-elementor-setting-key'            => 'btn_text',
				'data-elementor-inline-editing-toolbar' => 'none',
			]
		);

		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['btn_icon'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<i class="<?php echo esc_attr( $settings['btn_icon'] ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'btn-text' ); ?> ><?php echo $settings['btn_text']; ?></span>
		</span>
		<?php
	}

	/**
	 * Render close image/icon.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render_close_icon() {

		$settings = $this->get_settings();
		?>

		<span class="uael-modal-close elementor-clickable uael-close-custom-<?php echo $settings['icon_position']; ?>" >
		<?php
		if ( 'icon' == $settings['close_source'] ) {
		?>
			<i class="uael-close-icon <?php echo $settings['close_icon']; ?>"></i>
		<?php
		} else {
		?>
			<img class="uael-close-image" src="<?php echo ( isset( $settings['close_photo']['url'] ) ) ? $settings['close_photo']['url'] : ''; ?>"/>
		<?php
		}
		?>
		</span>
		<?php
	}

	/**
	 * Render action HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render_action_html() {

		$settings  = $this->get_settings();
		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

		if ( 'button' == $settings['modal_on'] ) {

			$this->render_button( $this->get_id(), $settings );

		} elseif (
			(
				'custom' == $settings['modal_on'] ||
				'custom_id' == $settings['modal_on'] ||
				'automatic' == $settings['modal_on']
			) &&
			$is_editor
		) {

			?>
			<div class="uael-builder-msg" style="text-align: center;">
				<h5><?php _e( 'Modal Popup - ID ', 'uael' ); ?><?php echo $this->get_id(); ?></h5>
				<p><?php _e( 'Click here to edit the "Modal Popup" settings. This text will not be visible on frontend.', 'uael' ); ?></p>
			</div>
			<?php

		} else {

			$inner_html = '';

			$this->add_render_attribute(
				'action-wrap',
				'class',
				[
					'uael-modal-action',
					'elementor-clickable',
					'uael-trigger',
				]
			);

			$this->add_render_attribute( 'action-wrap', 'data-modal', $this->get_id() );

			switch ( $settings['modal_on'] ) {
				case 'text':
					$this->add_render_attribute(
						'action-wrap',
						[
							'data-elementor-setting-key' => 'modal_text',
							'data-elementor-inline-editing-toolbar' => 'basic',
						]
					);

					$this->add_render_attribute( 'action-wrap', 'class', 'elementor-inline-editing' );

					$inner_html = $settings['modal_text'];

					break;

				case 'icon':
					$this->add_render_attribute( 'action-wrap', 'class', 'uael-modal-icon-wrap' );

					$inner_html = '<i class="uael-modal-icon ' . $settings['icon'] . '"></i>';

					break;

				case 'photo':
					$this->add_render_attribute( 'action-wrap', 'class', 'uael-modal-photo-wrap' );

					$url = ( isset( $settings['photo']['url'] ) && ! empty( $settings['photo']['url'] ) ) ? $settings['photo']['url'] : '';

					$inner_html = '<img class="uael-modal-photo" src="' . $url . '">';

					break;
			}

			?>

			<div <?php echo $this->get_render_attribute_string( 'action-wrap' ); ?>><?php echo $inner_html; ?></div>

			<?php
		}
	}

	/**
	 * Get Data Attributes.
	 *
	 * @since 0.0.1
	 * @param array $settings The settings array.
	 * @return string Data Attributes
	 * @access public
	 */
	public function get_parent_wrapper_attributes( $settings ) {

		$this->add_render_attribute(
			'parent-wrapper',
			[
				'id'                    => $this->get_id() . '-overlay',
				'data-trigger-on'       => $settings['modal_on'],
				'data-close-on-esc'     => $settings['esc_keypress'],
				'data-close-on-overlay' => $settings['overlay_click'],
				'data-exit-intent'      => $settings['exit_intent'],
				'data-after-sec'        => $settings['after_second'],
				'data-after-sec-val'    => $settings['after_second_value']['size'],
				'data-cookies'          => $settings['enable_cookies'],
				'data-cookies-days'     => $settings['close_cookie_days']['size'],
				'data-custom'           => $settings['modal_custom'],
				'data-custom-id'        => $settings['modal_custom_id'],
				'data-content'          => $settings['content_type'],
				'data-autoplay'         => $settings['video_autoplay'],
			]
		);

		$this->add_render_attribute(
			'parent-wrapper',
			'class',
			[
				'uael-modal-parent-wrapper',
				'uael-module-content',
				'uamodal-' . $this->get_id(),
				'uael-aspect-ratio-' . $settings['video_ratio'],
			]
		);

		return $this->get_render_attribute_string( 'parent-wrapper' );
	}

	/**
	 *  Get Saved Widgets
	 *
	 *  @param string $type Type.
	 *  @since 0.0.1
	 *  @return string
	 */
	public function get_saved_data( $type = 'page' ) {

		$saved_widgets = $this->get_post_template( $type );
		$options[-1]   = __( 'Select', 'uael' );
		if ( count( $saved_widgets ) ) {
			foreach ( $saved_widgets as $saved_row ) {
				$options[ $saved_row['id'] ] = $saved_row['name'];
			}
		} else {
			$options['no_template'] = __( 'It seems that, you have not saved any template yet.', 'uael' );
		}
		return $options;
	}

	/**
	 *  Get Templates based on category
	 *
	 *  @param string $type Type.
	 *  @since 0.0.1
	 *  @return string
	 */
	public function get_post_template( $type = 'page' ) {
		$posts = get_posts(
			array(
				'post_type'      => 'elementor_library',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => '-1',
				'tax_query'      => array(
					array(
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms'    => $type,
					),
				),
			)
		);

		$templates = array();

		foreach ( $posts as $post ) {

			$templates[] = array(
				'id'   => $post->ID,
				'name' => $post->post_title,
			);
		}

		return $templates;
	}

	/**
	 * Render Modal Popup output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		$settings  = $this->get_settings();
		$node_id   = $this->get_id();
		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

		$this->add_inline_editing_attributes( 'ct_content', 'advanced' );
		$this->add_inline_editing_attributes( 'title', 'basic' );
		$this->add_inline_editing_attributes( 'modal_text', 'basic' );
		$this->add_inline_editing_attributes( 'btn_text', 'none' );

		ob_start();
		include 'template.php';
		$html = ob_get_clean();
		echo $html;
	}

	/**
	 * Render Modal Popup output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function _content_template() {}

}

